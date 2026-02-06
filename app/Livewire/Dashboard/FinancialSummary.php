<?php

namespace App\Livewire\Dashboard;

use App\Models\Deployment;
use Livewire\Component;
use Carbon\Carbon;

class FinancialSummary extends Component
{
    public function render()
    {
        $thisMonth = now()->startOfMonth();
        $thisYear = now()->startOfYear();

        // This month totals
        $thisMonthData = Deployment::whereBetween('created_at', [$thisMonth, now()])
            ->selectRaw('
                COALESCE(SUM(maid_salary), 0) as total_maid_salary,
                COALESCE(SUM(client_payment), 0) as total_client_payment,
                COALESCE(SUM(service_paid), 0) as total_service_paid,
                COUNT(*) as total_deployments
            ')
            ->first();

        // This year totals
        $thisYearData = Deployment::whereBetween('created_at', [$thisYear, now()])
            ->selectRaw('
                COALESCE(SUM(maid_salary), 0) as total_maid_salary,
                COALESCE(SUM(client_payment), 0) as total_client_payment,
                COALESCE(SUM(service_paid), 0) as total_service_paid,
                COUNT(*) as total_deployments
            ')
            ->first();

        // Outstanding payments
        $outstandingDeployments = Deployment::whereIn('payment_status', ['pending', 'partial'])
            ->selectRaw('
                COALESCE(SUM(client_payment), 0) as total_outstanding
            ')
            ->first();

        return view('livewire.dashboard.financial-summary', [
            'thisMonthData' => $thisMonthData,
            'thisYearData' => $thisYearData,
            'outstandingDeployments' => $outstandingDeployments,
        ]);
    }
}
