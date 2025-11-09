<?php

namespace App\Livewire\CRM\Reports;

use App\Models\CRM\Activity;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

#[Title('Activity Metrics Report')]
class ActivityMetrics extends Component
{
    public $dateRange = '30'; // days
    public $selectedOwner = 'all';
    public $selectedType = 'all';
    
    // Activity metrics
    public $totalActivities = 0;
    public $completedActivities = 0;
    public $pendingActivities = 0;
    public $overdueActivities = 0;
    public $completionRate = 0;
    
    // SLA metrics
    public $withinSLA = 0;
    public $breachedSLA = 0;
    public $slaComplianceRate = 0;
    public $avgResponseTime = 0; // hours
    
    // Breakdown
    public $activitiesByType = [];
    public $activitiesByOwner = [];
    public $overdueByOwner = [];
    public $completionTrend = [];

    public function mount()
    {
        $this->calculateActivityMetrics();
    }

    public function updatedDateRange()
    {
        $this->calculateActivityMetrics();
    }

    public function updatedSelectedOwner()
    {
        $this->calculateActivityMetrics();
    }

    public function updatedSelectedType()
    {
        $this->calculateActivityMetrics();
    }

    protected function calculateActivityMetrics()
    {
        $startDate = now()->subDays((int) $this->dateRange);
        
        // Base query
        $query = Activity::where('created_at', '>=', $startDate);
        
        if ($this->selectedOwner !== 'all') {
            $query->where('owner_id', $this->selectedOwner);
        }
        
        if ($this->selectedType !== 'all') {
            $query->where('type', $this->selectedType);
        }

        // Total counts
        $this->totalActivities = (clone $query)->count();
        $this->completedActivities = (clone $query)->where('status', 'completed')->count();
        $this->pendingActivities = (clone $query)->where('status', 'pending')->count();
        $this->overdueActivities = (clone $query)
            ->where('status', 'pending')
            ->where('due_date', '<', now())
            ->count();

        // Completion rate
        $this->completionRate = $this->totalActivities > 0 
            ? round(($this->completedActivities / $this->totalActivities) * 100, 1) 
            : 0;

        // SLA metrics
        $this->withinSLA = (clone $query)
            ->where('status', 'completed')
            ->where(function($q) {
                $q->whereColumn('completed_at', '<=', 'due_date')
                  ->orWhereNull('due_date');
            })
            ->count();

        $this->breachedSLA = (clone $query)
            ->where('status', 'completed')
            ->whereColumn('completed_at', '>', 'due_date')
            ->whereNotNull('due_date')
            ->count();

        $totalWithDueDate = $this->withinSLA + $this->breachedSLA;
        $this->slaComplianceRate = $totalWithDueDate > 0 
            ? round(($this->withinSLA / $totalWithDueDate) * 100, 1) 
            : 0;

        // Average response time (for calls and emails)
        $responseActivities = (clone $query)
            ->whereIn('type', ['call', 'email'])
            ->where('status', 'completed')
            ->whereNotNull('completed_at')
            ->get();

        if ($responseActivities->isNotEmpty()) {
            $totalHours = $responseActivities->sum(function($activity) {
                return $activity->created_at->diffInHours($activity->completed_at);
            });
            $this->avgResponseTime = round($totalHours / $responseActivities->count(), 1);
        } else {
            $this->avgResponseTime = 0;
        }

        // Activities by type
        $this->activitiesByType = Activity::where('created_at', '>=', $startDate)
            ->when($this->selectedOwner !== 'all', function($q) {
                $q->where('owner_id', $this->selectedOwner);
            })
            ->select('type')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed')
            ->selectRaw('SUM(CASE WHEN status = "pending" AND due_date < NOW() THEN 1 ELSE 0 END) as overdue')
            ->groupBy('type')
            ->orderByDesc('total')
            ->get()
            ->map(function($item) {
                $item->completion_rate = $item->total > 0 
                    ? round(($item->completed / $item->total) * 100, 1) 
                    : 0;
                return $item;
            });

        // Activities by owner
        $this->activitiesByOwner = Activity::where('created_at', '>=', $startDate)
            ->when($this->selectedType !== 'all', function($q) {
                $q->where('type', $this->selectedType);
            })
            ->select('owner_id')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed')
            ->groupBy('owner_id')
            ->with('owner:id,name')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(function($item) {
                $item->owner_name = $item->owner?->name ?? 'Unassigned';
                $item->completion_rate = $item->total > 0 
                    ? round(($item->completed / $item->total) * 100, 1) 
                    : 0;
                return $item;
            });

        // Overdue by owner
        $this->overdueByOwner = Activity::where('status', 'pending')
            ->where('due_date', '<', now())
            ->where('created_at', '>=', $startDate)
            ->select('owner_id')
            ->selectRaw('COUNT(*) as overdue_count')
            ->groupBy('owner_id')
            ->with('owner:id,name')
            ->orderByDesc('overdue_count')
            ->limit(10)
            ->get()
            ->map(function($item) {
                $item->owner_name = $item->owner?->name ?? 'Unassigned';
                return $item;
            });

        // Completion trend (last 7 days)
        $this->completionTrend = Activity::where('completed_at', '>=', now()->subDays(7))
            ->whereNotNull('completed_at')
            ->select(DB::raw('DATE(completed_at) as date'))
            ->selectRaw('COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->map(function($item) {
                $item->day_name = date('D M j', strtotime($item->date));
                return $item;
            });
    }

    public function render()
    {
        // Get users for owner filter
        $owners = User::whereIn('role', ['admin', 'trainer'])
            ->orderBy('name')
            ->get(['id', 'name']);

        // Activity types
        $activityTypes = Activity::distinct()->pluck('type')->filter()->sort()->values();

        return view('livewire.c-r-m.reports.activity-metrics', [
            'owners' => $owners,
            'activityTypes' => $activityTypes,
        ])->layout('components.layouts.app', ['title' => __('Activity Metrics Report')]);
    }
}
