<?php

namespace App\Console\Commands;

use App\Models\MaidContract;
use App\Models\User;
use App\Notifications\ContractExpiringNotification;
use Illuminate\Console\Command;

class SendContractExpiringNotifications extends Command
{
    protected $signature = 'contracts:send-expiring-notifications {--days=30 : Number of days to look ahead}';
    protected $description = 'Send notifications for contracts expiring within the specified number of days';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $now = now();
        $expiryDate = $now->copy()->addDays($days);

        // Find contracts expiring within the specified days
        $expiringContracts = MaidContract::query()
            ->whereBetween('contract_end_date', [$now, $expiryDate])
            ->where('contract_status', 'active')
            ->with('maid')
            ->get();

        if ($expiringContracts->isEmpty()) {
            $this->info('No contracts expiring in the next ' . $days . ' days.');
            return self::SUCCESS;
        }

        // Get all admins and trainers to notify
        $notifiableUsers = User::query()
            ->whereIn('role', ['admin', 'trainer'])
            ->get();

        if ($notifiableUsers->isEmpty()) {
            $this->warn('No active admin or trainer users found to notify.');
            return self::SUCCESS;
        }

        $this->info("Found {$expiringContracts->count()} contract(s) expiring in the next {$days} days.");

        foreach ($expiringContracts as $contract) {
            $daysUntilExpiry = $now->diffInDays($contract->contract_end_date);

            // Send notification to all notifiable users
            foreach ($notifiableUsers as $user) {
                $user->notify(new ContractExpiringNotification($contract, $daysUntilExpiry));
            }

            $this->line("✓ Notification sent for contract #{$contract->id} ({$contract->maid->full_name}) - expires in {$daysUntilExpiry} days");
        }

        $this->info('Contract expiring notifications sent successfully.');
        return self::SUCCESS;
    }
}
