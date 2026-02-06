<?php

namespace App\Livewire\Contracts;

use App\Models\MaidContract;
use Carbon\Carbon;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Contract Reports')]
class Reports extends Component
{
    public string $period = 'all'; // all, month, quarter, year

    public function getContractStats(): array
    {
        $query = MaidContract::query();

        // Filter by period
        if ($this->period === 'month') {
            $query->whereMonth('contract_start_date', Carbon::now()->month)
                ->whereYear('contract_start_date', Carbon::now()->year);
        } elseif ($this->period === 'quarter') {
            $quarter = ceil(Carbon::now()->month / 3);
            $startMonth = ($quarter - 1) * 3 + 1;
            $endMonth = $quarter * 3;
            $query->whereRaw("MONTH(contract_start_date) >= ? AND MONTH(contract_start_date) <= ?", [$startMonth, $endMonth])
                ->whereYear('contract_start_date', Carbon::now()->year);
        } elseif ($this->period === 'year') {
            $query->whereYear('contract_start_date', Carbon::now()->year);
        }

        $allContracts = $query->get();

        return [
            'total_contracts' => $allContracts->count(),
            'active_contracts' => $allContracts->where('contract_status', 'active')->count(),
            'expired_contracts' => $allContracts->where('contract_status', 'expired')->count(),
            'terminated_contracts' => $allContracts->where('contract_status', 'terminated')->count(),
            'expiring_soon' => $allContracts->filter(fn ($contract) => $contract->isExpiringSoon())->count(),
            'total_worked_days' => $allContracts->sum('worked_days') ?? 0,
            'total_pending_days' => $allContracts->sum('pending_days') ?? 0,
            'avg_completion' => $allContracts->count() > 0
                ? (int) ($allContracts->average(fn ($c) => $c->getCompletionPercentage()))
                : 0,
        ];
    }

    public function getExpiringContracts()
    {
        return MaidContract::where('contract_status', '!=', 'expired')
            ->where('contract_status', '!=', 'terminated')
            ->where('contract_end_date', '!=', null)
            ->whereDate('contract_end_date', '>', Carbon::today())
            ->whereDate('contract_end_date', '<=', Carbon::today()->addDays(30))
            ->with('maid')
            ->orderBy('contract_end_date')
            ->get();
    }

    public function getRecentContracts()
    {
        return MaidContract::with('maid')
            ->latest('created_at')
            ->take(10)
            ->get();
    }

    public function getContractsByType()
    {
        return MaidContract::selectRaw('contract_type, COUNT(*) as count')
            ->where('contract_status', '!=', 'terminated')
            ->groupBy('contract_type')
            ->get();
    }

    public function render()
    {
        return view('livewire.contracts.reports', [
            'stats' => $this->getContractStats(),
            'expiring_contracts' => $this->getExpiringContracts(),
            'recent_contracts' => $this->getRecentContracts(),
            'contracts_by_type' => $this->getContractsByType(),
        ]);
    }
}
