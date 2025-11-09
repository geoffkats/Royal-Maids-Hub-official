<?php

namespace App\Livewire\CRM\Reports;

use App\Models\CRM\Opportunity;
use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

#[Title('Revenue Forecasting Report')]
class RevenueForecasting extends Component
{
    public $forecastPeriod = 'quarter'; // quarter, year
    public $confidenceLevel = 'medium'; // low, medium, high
    
    // Forecast metrics
    public $totalPipelineValue = 0;
    public $weightedPipelineValue = 0;
    public $expectedRevenue = 0;
    public $bestCase = 0;
    public $worstCase = 0;
    
    // Breakdown
    public $revenueByStage = [];
    public $revenueByMonth = [];
    public $topDeals = [];
    public $riskAssessment = [];

    public function mount()
    {
        $this->calculateForecast();
    }

    public function updatedForecastPeriod()
    {
        $this->calculateForecast();
    }

    public function updatedConfidenceLevel()
    {
        $this->calculateForecast();
    }

    protected function calculateForecast()
    {
        // Define period
        $endDate = $this->forecastPeriod === 'quarter' 
            ? now()->addMonths(3) 
            : now()->addYear();

        // Get open opportunities expected to close in the period
        $opportunities = Opportunity::whereNull('won_at')
            ->whereNull('lost_at')
            ->where(function($q) use ($endDate) {
                $q->where('expected_close_date', '<=', $endDate)
                  ->orWhereNull('expected_close_date');
            })
            ->get();

        // Total pipeline value
        $this->totalPipelineValue = $opportunities->sum('amount');

        // Weighted pipeline (probability-based)
        $this->weightedPipelineValue = $opportunities->sum(function($opp) {
            return ($opp->amount * $opp->probability) / 100;
        });

        // Expected revenue based on confidence level
        $this->expectedRevenue = $this->calculateExpectedRevenue($opportunities);

        // Best case (80% probability or higher)
        $this->bestCase = $opportunities->filter(function($opp) {
            return $opp->probability >= 80;
        })->sum('amount');

        // Worst case (only 90%+ probability deals)
        $this->worstCase = $opportunities->filter(function($opp) {
            return $opp->probability >= 90;
        })->sum('amount');

        // Revenue by stage
        $this->revenueByStage = $opportunities
            ->load('stage:id,name')
            ->groupBy(function($opp) { return $opp->stage?->name ?? 'Unassigned'; })
            ->map(function($group, $stageName) {
                return [
                    'stage' => $stageName,
                    'count' => $group->count(),
                    'total_value' => $group->sum('amount'),
                    'weighted_value' => $group->sum(function($opp) {
                        return ($opp->amount * $opp->probability) / 100;
                    }),
                    'avg_probability' => round($group->avg('probability'), 1),
                ];
            })->sortByDesc('weighted_value')->values();

        // Revenue by month (for period)
        $monthsToForecast = $this->forecastPeriod === 'quarter' ? 3 : 12;
        $this->revenueByMonth = collect(range(0, $monthsToForecast - 1))->map(function($offset) use ($opportunities) {
            $month = now()->addMonths($offset);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();

            $monthOpps = $opportunities->filter(function($opp) use ($monthStart, $monthEnd) {
                return $opp->expected_close_date 
                    && $opp->expected_close_date >= $monthStart 
                    && $opp->expected_close_date <= $monthEnd;
            });

            return [
                'month' => $month->format('M Y'),
                'count' => $monthOpps->count(),
                'total_value' => $monthOpps->sum('amount'),
                'weighted_value' => $monthOpps->sum(function($opp) {
                    return ($opp->amount * $opp->probability) / 100;
                }),
            ];
        });

        // Top deals (by value)
        $this->topDeals = $opportunities->load('assignedTo:id,name')->sortByDesc('amount')->take(10)->map(function($opp) {
            return [
                'id' => $opp->id,
                'title' => $opp->title,
                'amount' => $opp->amount,
                'probability' => $opp->probability,
                'weighted_value' => ($opp->amount * $opp->probability) / 100,
                'stage' => $opp->stage?->name,
                'expected_close_date' => $opp->expected_close_date,
                'owner_name' => $opp->assignedTo?->name ?? 'Unassigned',
            ];
        })->values();

        // Risk assessment
        $this->riskAssessment = [
            'high_risk' => $opportunities->filter(fn($o) => $o->probability < 30)->count(),
            'medium_risk' => $opportunities->filter(fn($o) => $o->probability >= 30 && $o->probability < 70)->count(),
            'low_risk' => $opportunities->filter(fn($o) => $o->probability >= 70)->count(),
            'no_close_date' => $opportunities->filter(fn($o) => !$o->expected_close_date)->count(),
        ];
    }

    protected function calculateExpectedRevenue($opportunities)
    {
        switch ($this->confidenceLevel) {
            case 'low':
                // Conservative: Only count 90%+ deals at full value, others at 50% of probability
                return $opportunities->sum(function($opp) {
                    if ($opp->probability >= 90) {
                        return $opp->amount;
                    }
                    return ($opp->amount * $opp->probability * 0.5) / 100;
                });
            
            case 'high':
                // Optimistic: Count 50%+ deals at full value, others at full probability
                return $opportunities->sum(function($opp) {
                    if ($opp->probability >= 50) {
                        return $opp->amount;
                    }
                    return ($opp->amount * $opp->probability) / 100;
                });
            
            case 'medium':
            default:
                // Realistic: Use weighted pipeline (probability-based)
                return $this->weightedPipelineValue;
        }
    }

    public function render()
    {
        return view('livewire.c-r-m.reports.revenue-forecasting')->layout('components.layouts.app', ['title' => __('Revenue Forecasting Report')]);
    }
}
