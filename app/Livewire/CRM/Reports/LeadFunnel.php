<?php

namespace App\Livewire\CRM\Reports;

use App\Models\CRM\Lead;
use App\Models\CRM\Opportunity;
use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

#[Title('Lead Funnel Report')]
class LeadFunnel extends Component
{
    public $dateRange = '30'; // days
    public $selectedSource = 'all';
    
    // Funnel metrics
    public $funnelData = [];
    public $conversionRates = [];
    public $sourceBreakdown = [];
    public $avgTimeInStage = [];
    public $dropoffAnalysis = [];

    public function mount()
    {
        $this->calculateFunnelMetrics();
    }

    public function updatedDateRange()
    {
        $this->calculateFunnelMetrics();
    }

    public function updatedSelectedSource()
    {
        $this->calculateFunnelMetrics();
    }

    protected function calculateFunnelMetrics()
    {
        $startDate = now()->subDays((int) $this->dateRange);
        
        // Base query with date and source filters
        $query = Lead::where('created_at', '>=', $startDate);
        
        if ($this->selectedSource !== 'all') {
            $query->where('source_id', $this->selectedSource);
        }

        // Funnel stage counts
        $this->funnelData = [
            'new' => (clone $query)->where('status', 'new')->count(),
            'working' => (clone $query)->where('status', 'working')->count(),
            'qualified' => (clone $query)->where('status', 'qualified')->count(),
            'converted' => (clone $query)->where('status', 'converted')->count(),
        ];

        $this->funnelData['total'] = array_sum($this->funnelData);
        $this->funnelData['disqualified'] = (clone $query)->where('status', 'disqualified')->count();

        // Conversion rates between stages
        $total = $this->funnelData['total'];
        if ($total > 0) {
            $this->conversionRates = [
                'new_to_working' => $this->funnelData['working'] > 0 
                    ? round(($this->funnelData['working'] / $this->funnelData['new']) * 100, 1) 
                    : 0,
                'working_to_qualified' => $this->funnelData['qualified'] > 0 
                    ? round(($this->funnelData['qualified'] / max($this->funnelData['working'], 1)) * 100, 1) 
                    : 0,
                'qualified_to_converted' => $this->funnelData['converted'] > 0 
                    ? round(($this->funnelData['converted'] / max($this->funnelData['qualified'], 1)) * 100, 1) 
                    : 0,
                'overall_conversion' => round(($this->funnelData['converted'] / $total) * 100, 1),
            ];
        } else {
            $this->conversionRates = [
                'new_to_working' => 0,
                'working_to_qualified' => 0,
                'qualified_to_converted' => 0,
                'overall_conversion' => 0,
            ];
        }

        // Source breakdown
        $this->sourceBreakdown = Lead::where('crm_leads.created_at', '>=', $startDate)
            ->join('crm_sources', 'crm_leads.source_id', '=', 'crm_sources.id')
            ->select('crm_sources.name as source', DB::raw('COUNT(*) as total'))
            ->selectRaw('SUM(CASE WHEN crm_leads.status = "converted" THEN 1 ELSE 0 END) as converted')
            ->groupBy('crm_sources.id', 'crm_sources.name')
            ->get()
            ->map(function($item) {
                $item->conversion_rate = $item->total > 0 
                    ? round(($item->converted / $item->total) * 100, 1) 
                    : 0;
                return $item;
            })
            ->sortByDesc('total')
            ->take(10);

        // Average time in each stage (days)
        $this->avgTimeInStage = $this->calculateAvgTimeInStage($startDate);

        // Dropoff analysis
        $this->dropoffAnalysis = [
            'disqualified_from_new' => Lead::where('created_at', '>=', $startDate)
                ->where('status', 'disqualified')
                ->whereNull('disqualified_at')
                ->count(),
            'disqualified_from_working' => Lead::where('created_at', '>=', $startDate)
                ->where('status', 'disqualified')
                ->whereNotNull('disqualified_at')
                ->count(),
        ];
    }

    protected function calculateAvgTimeInStage($startDate)
    {
        // This is a simplified calculation
        // In production, you'd want to track status changes in a separate table
        $leads = Lead::where('created_at', '>=', $startDate)
            ->whereNotNull('updated_at')
            ->get();

        $avgDays = [
            'new' => 0,
            'working' => 0,
            'qualified' => 0,
        ];

        // Calculate based on created_at to updated_at for now
        foreach ($leads as $lead) {
            $days = $lead->created_at->diffInDays($lead->updated_at);
            
            switch ($lead->status) {
                case 'working':
                    $avgDays['new'] += $days;
                    break;
                case 'qualified':
                    $avgDays['working'] += $days;
                    break;
                case 'converted':
                    $avgDays['qualified'] += $days;
                    break;
            }
        }

        // Average out
        foreach ($avgDays as $stage => $total) {
            $count = $this->funnelData[$stage] ?? 1;
            $avgDays[$stage] = $count > 0 ? round($total / $count, 1) : 0;
        }

        return $avgDays;
    }

    public function render()
    {
        // Get available sources for filter
        $sources = \App\Models\CRM\Source::where('active', true)
            ->orderBy('name')
            ->get();

        return view('livewire.c-r-m.reports.lead-funnel', [
            'sources' => $sources,
        ])->layout('components.layouts.app', ['title' => __('Lead Funnel Report')]);
    }
}
