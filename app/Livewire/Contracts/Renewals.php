<?php

namespace App\Livewire\Contracts;

use App\Models\MaidContract;
use Carbon\Carbon;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Contract Renewals')]
class Renewals extends Component
{
    use WithPagination;

    public string $filter = 'expiring';

    public function getExpiringContracts(): mixed
    {
        return MaidContract::query()
            ->with(['maid', 'maid.deployments.client'])
            ->where('contract_status', '!=', 'terminated')
            ->whereBetween('contract_end_date', [
                Carbon::now(),
                Carbon::now()->addDays(90),
            ])
            ->orderBy('contract_end_date', 'asc')
            ->paginate(15);
    }

    public function getRecentlyExpired(): mixed
    {
        return MaidContract::query()
            ->with(['maid', 'maid.deployments.client'])
            ->where('contract_status', '!=', 'terminated')
            ->whereBetween('contract_end_date', [
                Carbon::now()->subDays(30),
                Carbon::now(),
            ])
            ->orderBy('contract_end_date', 'desc')
            ->paginate(15);
    }

    public function getRenewalStats(): array
    {
        $expiring = MaidContract::where('contract_status', '!=', 'terminated')
            ->whereBetween('contract_end_date', [
                Carbon::now(),
                Carbon::now()->addDays(30),
            ])
            ->count();

        $expiring90 = MaidContract::where('contract_status', '!=', 'terminated')
            ->whereBetween('contract_end_date', [
                Carbon::now(),
                Carbon::now()->addDays(90),
            ])
            ->count();

        $recentlyExpired = MaidContract::where('contract_status', '!=', 'terminated')
            ->whereBetween('contract_end_date', [
                Carbon::now()->subDays(30),
                Carbon::now(),
            ])
            ->count();

        $renewalRate = MaidContract::where('contract_status', 'active')->count() > 0
            ? round((MaidContract::where('contract_status', 'completed')->count() / MaidContract::count()) * 100)
            : 0;

        return [
            'expiring_30_days' => $expiring,
            'expiring_90_days' => $expiring90,
            'recently_expired' => $recentlyExpired,
            'renewal_rate' => $renewalRate,
        ];
    }

    public function renewContract(MaidContract $contract): void
    {
        if (! auth()->user()->can('update', $contract)) {
            abort(403);
        }

        $newEndDate = $contract->contract_end_date->addDays(365);
        $contract->update([
            'contract_end_date' => $newEndDate,
            'contract_status' => 'active',
            'pending_days' => 365,
            'worked_days' => 0,
        ]);

        $this->dispatch('contract-renewed', contractId: $contract->id);
    }

    public function render()
    {
        $contracts = match ($this->filter) {
            'expired' => $this->getRecentlyExpired(),
            default => $this->getExpiringContracts(),
        };

        $stats = $this->getRenewalStats();

        return view('livewire.contracts.renewals', [
            'contracts' => $contracts,
            'stats' => $stats,
        ]);
    }
}
