<?php

namespace App\Livewire\CRM\Reports;

use App\Models\CRM\Opportunity;
use App\Models\CRM\Lead;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

#[Title('Sales Performance Report')]
class SalesPerformance extends Component
{
    public $dateRange = '30'; // days
    public $selectedOwner = 'all';
    
    // Performance metrics
    public $totalRevenue = 0;
    public $avgDealSize = 0;
    public $salesVelocity = 0; // days from creation to close
    public $winRate = 0;
    public $lossRate = 0;
    
    // Leaderboard
    public $topPerformers = [];
    public $revenueByOwner = [];
    public $dealsByStage = [];
    
    // Trends
    public $monthlyTrends = [];

    public function mount()
    {
        $this->calculatePerformanceMetrics();
    }

    public function updatedDateRange()
    {
        $this->calculatePerformanceMetrics();
    }

    public function updatedSelectedOwner()
    {
        $this->calculatePerformanceMetrics();
    }

    protected function calculatePerformanceMetrics()
    {
        $startDate = now()->subDays((int) $this->dateRange);
        
        // Base query for won opportunities
        $wonQuery = Opportunity::whereNotNull('won_at')
            ->where('won_at', '>=', $startDate);
        
        if ($this->selectedOwner !== 'all') {
            $wonQuery->where('assigned_to', $this->selectedOwner);
        }

        // Total revenue from won deals
        $this->totalRevenue = $wonQuery->sum('amount');

        // Average deal size
        $wonCount = (clone $wonQuery)->count();
        $this->avgDealSize = $wonCount > 0 ? $this->totalRevenue / $wonCount : 0;

        // Win rate calculation
        $lostQuery = Opportunity::whereNotNull('lost_at')
            ->where('lost_at', '>=', $startDate);
        
        if ($this->selectedOwner !== 'all') {
            $lostQuery->where('assigned_to', $this->selectedOwner);
        }

        $lostCount = $lostQuery->count();
        $totalClosed = $wonCount + $lostCount;
        
        $this->winRate = $totalClosed > 0 ? round(($wonCount / $totalClosed) * 100, 1) : 0;
        $this->lossRate = $totalClosed > 0 ? round(($lostCount / $totalClosed) * 100, 1) : 0;

        // Sales velocity (average days to close)
        $wonOpps = (clone $wonQuery)->get();
        if ($wonOpps->isNotEmpty()) {
            $totalDays = $wonOpps->sum(function($opp) {
                return $opp->created_at->diffInDays($opp->won_at);
            });
            $this->salesVelocity = round($totalDays / $wonOpps->count(), 1);
        } else {
            $this->salesVelocity = 0;
        }

        // Top performers by revenue
        $this->topPerformers = Opportunity::whereNotNull('won_at')
            ->where('won_at', '>=', $startDate)
            ->select('assigned_to')
            ->selectRaw('SUM(amount) as total_revenue')
            ->selectRaw('COUNT(*) as deals_won')
            ->selectRaw('AVG(amount) as avg_deal_size')
            ->groupBy('assigned_to')
            ->with('assignedTo:id,name')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get()
            ->map(function($item) {
                $item->owner_name = $item->assignedTo?->name ?? 'Unassigned';
                return $item;
            });

        // Revenue by owner (for pie chart)
        $this->revenueByOwner = Opportunity::whereNotNull('won_at')
            ->where('won_at', '>=', $startDate)
            ->select('assigned_to')
            ->selectRaw('SUM(amount) as revenue')
            ->groupBy('assigned_to')
            ->with('assignedTo:id,name')
            ->orderByDesc('revenue')
            ->limit(8)
            ->get()
            ->map(function($item) {
                $item->owner_name = $item->assignedTo?->name ?? 'Unassigned';
                $item->percentage = $this->totalRevenue > 0 
                    ? round(($item->revenue / $this->totalRevenue) * 100, 1) 
                    : 0;
                return $item;
            });

        // Deals by stage (current pipeline)
        $this->dealsByStage = Opportunity::whereNull('won_at')
            ->whereNull('lost_at')
            ->with('stage:id,name')
            ->get()
            ->groupBy(function($opp) { return $opp->stage?->name ?? 'Unassigned'; })
            ->map(function($group, $stageName) {
                return (object) [
                    'stage' => $stageName,
                    'count' => $group->count(),
                    'total_value' => $group->sum('amount'),
                ];
            })
            ->values();

        // Monthly revenue trends (last 6 months)
        $this->monthlyTrends = Opportunity::whereNotNull('won_at')
            ->where('won_at', '>=', now()->subMonths(6))
            ->select(DB::raw('DATE_FORMAT(won_at, "%Y-%m") as month'))
            ->selectRaw('SUM(amount) as revenue')
            ->selectRaw('COUNT(*) as deals')
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get()
            ->map(function($item) {
                $item->month_name = date('M Y', strtotime($item->month . '-01'));
                return $item;
            });
    }

    public function render()
    {
        // Get users for owner filter (only those with CRM access)
        $owners = User::whereIn('role', ['admin', 'trainer'])
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('livewire.c-r-m.reports.sales-performance', [
            'owners' => $owners,
        ])->layout('components.layouts.app', ['title' => __('Sales Performance Report')]);
    }
}
