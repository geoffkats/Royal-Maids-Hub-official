<?php

namespace App\Livewire\Tickets;

use App\Models\Ticket;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Analytics extends Component
{
    public $totalTickets;
    public $openTickets;
    public $slaBreached;
    public $avgResponseTime;
    public $resolutionRate;
    public $satisfactionRating;
    public $platinumTickets;
    public $activeStaff;
    public $recentTickets;
    public $staffPerformance;
    
    // Chart data
    public $volumeLabels = [];
    public $volumeData = [];
    public $statusLabels = [];
    public $statusData = [];
    public $priorityLabels = [];
    public $priorityData = [];
    public $tierResponseTimes = [];

    public function mount()
    {
        $this->loadAnalytics();
    }

    public function loadAnalytics()
    {
        // Basic metrics
        $this->totalTickets = Ticket::count();
        $this->openTickets = Ticket::whereIn('status', ['open', 'in_progress', 'pending'])->count();
        $this->slaBreached = Ticket::where('sla_breached', true)->count();
        
        // Average response time
        $avgResponse = Ticket::whereNotNull('first_response_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, first_response_at)) as avg_hours')
            ->value('avg_hours');
        $this->avgResponseTime = $avgResponse ? round($avgResponse, 1) : 0;
        
        // Resolution rate
        $resolvedTickets = Ticket::whereIn('status', ['resolved', 'closed'])->count();
        $this->resolutionRate = $this->totalTickets > 0 ? round(($resolvedTickets / $this->totalTickets) * 100, 1) : 0;
        
        // Satisfaction rating
        $this->satisfactionRating = Ticket::whereNotNull('satisfaction_rating')
            ->avg('satisfaction_rating');
        $this->satisfactionRating = $this->satisfactionRating ? round($this->satisfactionRating, 1) : 0;
        
        // Platinum tickets
        $this->platinumTickets = Ticket::where('tier_based_priority', 'platinum')->count();
        
        // Active staff
        $this->activeStaff = User::whereHas('assignedTickets', function($query) {
            $query->whereIn('status', ['open', 'in_progress', 'pending']);
        })->count();
        
        // Recent tickets
        $this->recentTickets = Ticket::with(['requester', 'assignedTo'])
            ->latest()
            ->limit(5)
            ->get();
        
        // Staff performance
        $this->staffPerformance = User::withCount(['assignedTickets as tickets_count'])
            ->whereHas('assignedTickets')
            ->limit(5)
            ->get()
            ->map(function($user) {
                // Calculate average resolution time manually
                $avgResolutionTime = $user->assignedTickets()
                    ->whereNotNull('resolved_at')
                    ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, resolved_at)) as avg_hours')
                    ->value('avg_hours');
                
                // Calculate average satisfaction rating manually
                $avgSatisfaction = $user->assignedTickets()
                    ->whereNotNull('satisfaction_rating')
                    ->avg('satisfaction_rating');
                
                return (object) [
                    'id' => $user->id,
                    'name' => $user->name,
                    'tickets_count' => $user->tickets_count,
                    'avg_resolution_time' => $avgResolutionTime ? round($avgResolutionTime, 1) : 0,
                    'satisfaction_rating' => $avgSatisfaction ? round($avgSatisfaction, 1) : 0
                ];
            });
        
        // Chart data
        $this->loadChartData();
    }

    private function loadChartData()
    {
        // Volume trend (last 30 days)
        $this->volumeLabels = [];
        $this->volumeData = [];
        
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $this->volumeLabels[] = $date->format('M d');
            $this->volumeData[] = Ticket::whereDate('created_at', $date)->count();
        }
        
        // Status distribution
        $statusCounts = Ticket::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
        
        $this->statusLabels = array_keys($statusCounts);
        $this->statusData = array_values($statusCounts);
        
        // Priority breakdown
        $priorityCounts = Ticket::select('priority', DB::raw('count(*) as count'))
            ->groupBy('priority')
            ->pluck('count', 'priority')
            ->toArray();
        
        $this->priorityLabels = array_keys($priorityCounts);
        $this->priorityData = array_values($priorityCounts);
        
        // Tier response times
        $tierData = Ticket::select('tier_based_priority', DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, first_response_at)) as avg_hours'))
            ->whereNotNull('first_response_at')
            ->whereNotNull('tier_based_priority')
            ->groupBy('tier_based_priority')
            ->pluck('avg_hours', 'tier_based_priority')
            ->toArray();
        
        $this->tierResponseTimes = [
            $tierData['silver'] ?? 0,
            $tierData['gold'] ?? 0,
            $tierData['platinum'] ?? 0
        ];
    }

    public function render()
    {
        return view('livewire.tickets.analytics');
    }
}