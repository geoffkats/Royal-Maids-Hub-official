<?php

namespace App\Console\Commands;

use App\Models\Deployment;
use App\Models\MaidContract;
use Carbon\Carbon;
use Illuminate\Console\Command;

class BackfillContractsFromDeployments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contracts:backfill-deployments {--dry-run : Preview changes without writing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backfill maid contracts from existing deployments';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        if ($dryRun) {
            $this->info('Running in DRY RUN mode - no changes will be made.');
            $this->newLine();
        }

        $deployments = Deployment::query()
            ->withTrashed()
            ->with('maid')
            ->orderBy('id')
            ->get();

        if ($deployments->isEmpty()) {
            $this->info('No deployments found.');
            return Command::SUCCESS;
        }

        $created = 0;
        $skipped = 0;

        foreach ($deployments as $deployment) {
            $startDate = $deployment->contract_start_date
                ? Carbon::parse($deployment->contract_start_date)
                : Carbon::parse($deployment->deployment_date);

            $endDate = $deployment->contract_end_date
                ? Carbon::parse($deployment->contract_end_date)
                : ($deployment->end_date ? Carbon::parse($deployment->end_date) : null);

            if ($this->contractExists($deployment->maid_id, $startDate, $endDate, $deployment->contract_type)) {
                $skipped++;
                continue;
            }

            $status = $this->resolveStatus($deployment, $endDate);

            if ($dryRun) {
                $created++;
                continue;
            }

            $contract = MaidContract::create([
                'maid_id' => $deployment->maid_id,
                'contract_start_date' => $startDate,
                'contract_end_date' => $endDate,
                'contract_status' => $status,
                'contract_type' => $deployment->contract_type,
                'notes' => $deployment->notes,
                'created_by' => $deployment->created_by,
                'updated_by' => $deployment->updated_by,
            ]);

            $contract->recalculateDayCounts();
            $created++;
        }

        $this->info("Contracts created: {$created}");
        $this->info("Deployments skipped: {$skipped}");

        if ($dryRun) {
            $this->warn('Dry run completed. Re-run without --dry-run to apply changes.');
        } else {
            $this->info('Backfill completed successfully.');
        }

        return Command::SUCCESS;
    }

    protected function resolveStatus(Deployment $deployment, ?Carbon $endDate): string
    {
        if ($deployment->status === 'terminated') {
            return 'terminated';
        }

        if ($endDate && $endDate->isPast()) {
            return 'completed';
        }

        return 'active';
    }

    protected function contractExists(int $maidId, Carbon $startDate, ?Carbon $endDate, ?string $type): bool
    {
        $query = MaidContract::query()
            ->where('maid_id', $maidId)
            ->whereDate('contract_start_date', $startDate)
            ->where('contract_type', $type);

        if ($endDate) {
            $query->whereDate('contract_end_date', $endDate);
        } else {
            $query->whereNull('contract_end_date');
        }

        return $query->exists();
    }
}
