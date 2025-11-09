<?php

namespace App\Livewire\Dashboard;

use App\Models\Booking;
use App\Models\Client;
use App\Models\Deployment;
use App\Models\Evaluation;
use App\Models\Maid;
use App\Models\Package;
use App\Models\Trainer;
use App\Models\TrainingProgram;
use App\Models\Ticket;
use App\Models\User;
use App\Models\CRM\Lead;
use App\Models\CRM\Opportunity;
use App\Models\CRM\Activity;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AdminDashboard extends Component
{
    public function render()
    {
        // ============ USER STATISTICS ============
        $totalUsers = User::count();
        $verifiedUsers = User::whereNotNull('email_verified_at')->count();
        $adminUsers = User::where('role', 'admin')->count();
        $trainerUsers = User::where('role', 'trainer')->count();
        $clientUsers = User::where('role', 'client')->count();
        
        // ============ MAID STATISTICS ============
        $totalMaids = Maid::count();
        $availableMaids = Maid::where('status', 'available')->count();
        $inTrainingMaids = Maid::where('status', 'in-training')->count();
        $deployedMaids = Maid::where('status', 'deployed')->count();
        $bookedMaids = Maid::where('status', 'booked')->count();
        
        // Maid status breakdown
        $maidStatusBreakdown = [
            'available' => Maid::where('status', 'available')->count(),
            'in-training' => Maid::where('status', 'in-training')->count(),
            'booked' => Maid::where('status', 'booked')->count(),
            'deployed' => Maid::where('status', 'deployed')->count(),
            'absconded' => Maid::where('status', 'absconded')->count(),
            'terminated' => Maid::where('status', 'terminated')->count(),
            'on-leave' => Maid::where('status', 'on-leave')->count(),
        ];

        // Maid role distribution
        $roleDistribution = [
            'housekeeper' => Maid::where('role', 'housekeeper')->count(),
            'house_manager' => Maid::where('role', 'house_manager')->count(),
            'nanny' => Maid::where('role', 'nanny')->count(),
            'chef' => Maid::where('role', 'chef')->count(),
            'elderly_caretaker' => Maid::where('role', 'elderly_caretaker')->count(),
            'nakawere_caretaker' => Maid::where('role', 'nakawere_caretaker')->count(),
        ];

        // Recent maids (last 7)
        $recentMaids = Maid::latest()->take(7)->get();
        
        // ============ CLIENT STATISTICS ============
        $totalClients = Client::count();
        $activeClients = Client::where('subscription_status', 'active')->count();
        $pendingClients = Client::where('subscription_status', 'pending')->count();
        $expiredClients = Client::where('subscription_status', 'expired')->count();
        $cancelledClients = Client::where('subscription_status', 'cancelled')->count();
        
        // ============ BOOKING STATISTICS ============
        $totalBookings = Booking::count();
        $activeBookings = Booking::where('status', 'active')->count();
        $completedBookings = Booking::where('status', 'completed')->count();
        $cancelledBookings = Booking::where('status', 'cancelled')->count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        
        // Booking status breakdown
        $bookingStatusBreakdown = [
            'pending' => $pendingBookings,
            'active' => $activeBookings,
            'completed' => $completedBookings,
            'cancelled' => $cancelledBookings,
        ];
        
        // Recent bookings
        $recentBookings = Booking::with(['client.user', 'maid', 'package'])->latest()->take(5)->get();
        
        // ============ TRAINER STATISTICS ============
        $totalTrainers = Trainer::count();
        $activeTrainers = Trainer::where('status', 'active')->count();
        
        // ============ TRAINING PROGRAM STATISTICS ============
        $totalPrograms = TrainingProgram::where('archived', false)->count();
        $activePrograms = TrainingProgram::where('archived', false)->where('status', 'in-progress')->count();
        $completedPrograms = TrainingProgram::where('archived', false)->where('status', 'completed')->count();
        
        // Program type breakdown
        $programTypeBreakdown = TrainingProgram::where('archived', false)
            ->select('program_type', DB::raw('count(*) as total'))
            ->groupBy('program_type')
            ->pluck('total', 'program_type')
            ->toArray();
        
        // ============ EVALUATION STATISTICS ============
        $totalEvaluations = Evaluation::where('archived', false)->count();
        $approvedEvaluations = Evaluation::where('archived', false)->where('status', 'approved')->count();
        $pendingEvaluations = Evaluation::where('archived', false)->where('status', 'pending')->count();
        $rejectedEvaluations = Evaluation::where('archived', false)->where('status', 'rejected')->count();
        $averageRating = Evaluation::where('archived', false)->avg('overall_rating');
        
        // Evaluation status breakdown
        $evaluationStatusBreakdown = [
            'approved' => $approvedEvaluations,
            'pending' => $pendingEvaluations,
            'rejected' => $rejectedEvaluations,
        ];
        
        // Recent evaluations
        $recentEvaluations = Evaluation::where('archived', false)
            ->with(['maid', 'trainer.user'])
            ->latest('evaluation_date')
            ->take(5)
            ->get();
        
        // Monthly evaluation trends (last 6 months)
        $evaluationTrends = Evaluation::where('archived', false)
            ->select(
                DB::raw('DATE_FORMAT(evaluation_date, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count'),
                DB::raw('AVG(overall_rating) as avg_rating')
            )
            ->where('evaluation_date', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        // ============ PERFORMANCE METRICS ============
        // Top rated maids
        $topRatedMaids = Evaluation::where('archived', false)
            ->select('maid_id', DB::raw('AVG(overall_rating) as avg_rating'), DB::raw('COUNT(*) as eval_count'))
            ->groupBy('maid_id')
            ->having('eval_count', '>=', 2)
            ->orderByDesc('avg_rating')
            ->with('maid')
            ->take(5)
            ->get();
        
        // ============ ADVANCED KPIs ============
        // Deployment Success Rate: (Deployed Maids / Total Maids) * 100
        $deploymentSuccessRate = $totalMaids > 0 
            ? round(($deployedMaids / $totalMaids) * 100, 1) 
            : 0;
        
        // Training Success Rate: (Approved Evaluations / Total Evaluations) * 100
        $trainingSuccessRate = $totalEvaluations > 0 
            ? round(($approvedEvaluations / $totalEvaluations) * 100, 1) 
            : 0;
        
        // Client Retention Rate: Clients with 2+ bookings
        $clientsWithMultipleBookings = Client::has('bookings', '>=', 2)->count();
        $clientRetentionRate = $totalClients > 0 
            ? round(($clientsWithMultipleBookings / $totalClients) * 100, 1) 
            : 0;
        
        // Maid Turnover Rate: (Absconded + Terminated) / Total Maids
        $turnedOverMaids = Maid::whereIn('status', ['absconded', 'terminated'])->count();
        $maidTurnoverRate = $totalMaids > 0 
            ? round(($turnedOverMaids / $totalMaids) * 100, 1) 
            : 0;
        
        // Booking Conversion Rate: Active / (Pending + Active)
        $bookingConversionRate = ($pendingBookings + $activeBookings) > 0 
            ? round(($activeBookings / ($pendingBookings + $activeBookings)) * 100, 1) 
            : 0;
        
        // Package Revenue Breakdown (if packages exist)
        $packageRevenue = Booking::join('packages', 'bookings.package_id', '=', 'packages.id')
            ->select('packages.tier', DB::raw('SUM(bookings.calculated_price) as revenue'), DB::raw('COUNT(bookings.id) as booking_count'))
            ->whereNotNull('bookings.package_id')
            ->groupBy('packages.tier')
            ->get()
            ->keyBy('tier');
        
        $silverRevenue = $packageRevenue->get('Standard')?->revenue ?? 0;
        $goldRevenue = $packageRevenue->get('Premium')?->revenue ?? 0;
        $platinumRevenue = $packageRevenue->get('Elite')?->revenue ?? 0;
        
        $silverBookingCount = $packageRevenue->get('Standard')?->booking_count ?? 0;
        $goldBookingCount = $packageRevenue->get('Premium')?->booking_count ?? 0;
        $platinumBookingCount = $packageRevenue->get('Elite')?->booking_count ?? 0;
        
        // Total Revenue from all bookings
        $totalRevenue = Booking::sum('calculated_price') ?? 0;
        
        // Average Booking Value
        $averageBookingValue = $totalBookings > 0 
            ? round($totalRevenue / $totalBookings, 0) 
            : 0;
        
        // Average Family Size
        $averageFamilySize = Booking::whereNotNull('family_size')->avg('family_size') ?? 0;
        
        // Active Deployments
        $activeDeployments = Deployment::where('status', 'active')->count();
        $completedDeployments = Deployment::where('status', 'completed')->count();
        $terminatedDeployments = Deployment::where('status', 'terminated')->count();
        
        // Average Deployment Duration (for completed deployments)
        $avgDeploymentDays = Deployment::where('status', 'completed')
            ->whereNotNull('end_date')
            ->select(DB::raw('AVG(DATEDIFF(end_date, deployment_date)) as avg_days'))
            ->value('avg_days');
        $avgDeploymentDuration = $avgDeploymentDays ? round($avgDeploymentDays) : 0;

        // ============ TICKET STATISTICS ============
        $totalTickets = Ticket::count();
        $openTickets = Ticket::whereIn('status', ['open', 'in_progress', 'pending'])->count();
        $slaBreachedTickets = Ticket::where('sla_breached', true)->count();
        $resolvedTickets = Ticket::whereIn('status', ['resolved', 'closed'])->count();

        // ============ CRM STATISTICS ============
        $totalLeads = Lead::count();
        $newLeads = Lead::where('status', 'new')->count();
        $workingLeads = Lead::where('status', 'working')->count();
        $qualifiedLeads = Lead::where('status', 'qualified')->count();
        $convertedLeads = Lead::where('status', 'converted')->count();
        $disqualifiedLeads = Lead::where('status', 'disqualified')->count();

        // Lead conversion rate
        $leadConversionRate = $totalLeads > 0 ? round(($convertedLeads / $totalLeads) * 100, 1) : 0;

        // Opportunities
        $totalOpportunities = Opportunity::count();
        $openOpportunities = Opportunity::whereNull('won_at')->whereNull('lost_at')->count();
        $wonOpportunities = Opportunity::whereNotNull('won_at')->count();
        $lostOpportunities = Opportunity::whereNotNull('lost_at')->count();

        // Pipeline value
        $pipelineValue = Opportunity::whereNull('won_at')->whereNull('lost_at')->sum('amount');
        $weightedPipelineValue = Opportunity::whereNull('won_at')->whereNull('lost_at')
            ->get()
            ->sum(function($opp) {
                return ($opp->amount * $opp->probability) / 100;
            });

        // Win rate
        $closedOpportunities = $wonOpportunities + $lostOpportunities;
        $winRate = $closedOpportunities > 0 ? round(($wonOpportunities / $closedOpportunities) * 100, 1) : 0;

        // Activities
        $totalActivities = Activity::count();
        $pendingActivities = Activity::where('status', 'pending')->count();
        $overdueActivities = Activity::where('status', 'pending')
            ->where('due_date', '<', now())
            ->count();
        $completedActivities = Activity::where('status', 'completed')->count();

        // Top leads by score
        $topLeads = Lead::whereIn('status', ['new', 'working', 'qualified'])
            ->orderBy('score', 'desc')
            ->limit(5)
            ->get();

        return view('livewire.dashboard.admin-dashboard', compact(
            // Users
            'totalUsers', 'verifiedUsers', 'adminUsers', 'trainerUsers', 'clientUsers',
            // Maids
            'totalMaids', 'availableMaids', 'inTrainingMaids', 'deployedMaids', 'bookedMaids',
            'maidStatusBreakdown', 'roleDistribution', 'recentMaids',
            // Clients
            'totalClients', 'activeClients', 'pendingClients', 'expiredClients', 'cancelledClients',
            // Bookings
            'totalBookings', 'activeBookings', 'completedBookings', 'cancelledBookings', 'pendingBookings',
            'bookingStatusBreakdown', 'recentBookings',
            // Trainers
            'totalTrainers', 'activeTrainers',
            // Programs
            'totalPrograms', 'activePrograms', 'completedPrograms', 'programTypeBreakdown',
            // Evaluations
            'totalEvaluations', 'approvedEvaluations', 'pendingEvaluations', 'rejectedEvaluations',
            'averageRating', 'evaluationStatusBreakdown', 'recentEvaluations', 'evaluationTrends',
            // Performance
            'topRatedMaids',
            // Advanced KPIs
            'deploymentSuccessRate', 'trainingSuccessRate', 'clientRetentionRate', 
            'maidTurnoverRate', 'bookingConversionRate',
            // Revenue & Packages
            'totalRevenue', 'averageBookingValue', 'averageFamilySize',
            'silverRevenue', 'goldRevenue', 'platinumRevenue',
            'silverBookingCount', 'goldBookingCount', 'platinumBookingCount',
            // Deployments
            'activeDeployments', 'completedDeployments', 'terminatedDeployments', 'avgDeploymentDuration',
            // Tickets
            'totalTickets', 'openTickets', 'slaBreachedTickets', 'resolvedTickets',
            // CRM
            'totalLeads', 'newLeads', 'workingLeads', 'qualifiedLeads', 'convertedLeads', 'disqualifiedLeads',
            'leadConversionRate', 'totalOpportunities', 'openOpportunities', 'wonOpportunities', 'lostOpportunities',
            'pipelineValue', 'weightedPipelineValue', 'winRate', 'totalActivities', 'pendingActivities',
            'overdueActivities', 'completedActivities', 'topLeads'
        ))->layout('components.layouts.app', ['title' => __('Admin Dashboard')]);
    }
}
