<?php

namespace App\Livewire\Reports;

use App\Models\Booking;
use App\Models\Maid;
use App\Models\Client;
use App\Models\Trainer;
use App\Models\TrainingProgram;
use App\Models\Evaluation;
use App\Models\Deployment;
use App\Models\Package;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class KpiDashboard extends Component
{
    use AuthorizesRequests, WithPagination;

    public $dateRange = '30'; // days
    public $selectedPeriod = 'month';
    public $exportFormat = 'pdf';
    public $showExportModal = false;
    public $comparisonPeriod = 'previous';
    public $selectedPackages = [];
    public $showPackageDetails = false;
    
    // KPI Data
    public $totalBookings = 0;
    public $totalMaids = 0;
    public $totalClients = 0;
    public $totalTrainers = 0;
    public $totalPrograms = 0;
    public $totalEvaluations = 0;
    public $totalDeployments = 0;
    
    // Booking KPIs
    public $bookingCompletionRate = 0;
    public $averageBookingValue = 0;
    public $bookingGrowthRate = 0;
    public $clientSatisfactionScore = 0;
    
    // Training KPIs
    public $trainingCompletionRate = 0;
    public $averageKpiScore = 0;
    public $trainerEfficiency = 0;
    public $programSuccessRate = 0;
    
    // Maid KPIs
    public $maidDeploymentRate = 0;
    public $maidRetentionRate = 0;
    public $averageMaidRating = 0;
    public $maidAvailabilityRate = 0;
    
    // Financial KPIs
    public $totalRevenue = 0;
    public $averageRevenuePerBooking = 0;
    public $revenueGrowthRate = 0;
    public $profitMargin = 0;
    
    // Operational KPIs
    public $averageResponseTime = 0;
    public $systemUptime = 0;
    public $userEngagementRate = 0;
    public $conversionRate = 0;
    
    // Package KPIs
    public $silverBookings = 0;
    public $goldBookings = 0;
    public $platinumBookings = 0;
    public $silverRevenue = 0;
    public $goldRevenue = 0;
    public $platinumRevenue = 0;
    public $averageFamilySize = 0;
    public $packageDistribution = [];
    public $packagePerformance = [];

    public function mount(): void
    {
        $this->authorize('viewAny', Booking::class);
        $this->calculateKpis();
    }

    public function updatedDateRange(): void
    {
        $this->calculateKpis();
    }

    public function updatedSelectedPeriod(): void
    {
        $this->calculateKpis();
    }

    public function exportReport(): void
    {
        $this->showExportModal = true;
    }

    public function generateExport(): void
    {
        $this->showExportModal = false;
        
        // For now, just show a success message
        session()->flash('success', 'Export functionality will be implemented in the next update.');
    }

    protected function getKpiData(): array
    {
        return [
            'totalBookings' => $this->totalBookings,
            'totalRevenue' => $this->totalRevenue,
            'bookingCompletionRate' => $this->bookingCompletionRate,
            'trainingCompletionRate' => $this->trainingCompletionRate,
            'maidDeploymentRate' => $this->maidDeploymentRate,
            'averageKpiScore' => $this->averageKpiScore,
            'averageFamilySize' => $this->averageFamilySize,
        ];
    }

    protected function generateCsvData(array $data): string
    {
        $csv = "KPI Dashboard Report\n";
        $csv .= "Generated: " . $data['generatedAt']->format('Y-m-d H:i:s') . "\n";
        $csv .= "Period: " . $data['period'] . "\n\n";
        
        $csv .= "Key Metrics\n";
        $csv .= "Total Bookings," . $data['kpis']['totalBookings'] . "\n";
        $csv .= "Total Revenue," . $data['kpis']['totalRevenue'] . "\n";
        $csv .= "Booking Completion Rate," . $data['kpis']['bookingCompletionRate'] . "%\n";
        $csv .= "Training Completion Rate," . $data['kpis']['trainingCompletionRate'] . "%\n";
        $csv .= "Maid Deployment Rate," . $data['kpis']['maidDeploymentRate'] . "%\n";
        $csv .= "Average KPI Score," . $data['kpis']['averageKpiScore'] . "%\n";
        $csv .= "Average Family Size," . $data['kpis']['averageFamilySize'] . "\n\n";
        
        $csv .= "Package Performance\n";
        $csv .= "Package,Bookings,Revenue,Avg Revenue\n";
        foreach ($data['packageMetrics'] as $package => $metrics) {
            $csv .= $package . "," . $metrics['bookings'] . "," . $metrics['revenue'] . "," . $metrics['avg_revenue'] . "\n";
        }
        
        return $csv;
    }

    public function togglePackageDetails(): void
    {
        $this->showPackageDetails = !$this->showPackageDetails;
    }

    protected function calculateKpis(): void
    {
        $startDate = $this->getStartDate();
        $endDate = now();
        
        // Basic counts
        $this->totalBookings = Booking::count();
        $this->totalMaids = Maid::count();
        $this->totalClients = Client::count();
        $this->totalTrainers = Trainer::count();
        $this->totalPrograms = TrainingProgram::count();
        $this->totalEvaluations = Evaluation::count();
        $this->totalDeployments = Deployment::count();
        
        // Booking KPIs
        $this->calculateBookingKpis($startDate, $endDate);
        
        // Training KPIs
        $this->calculateTrainingKpis($startDate, $endDate);
        
        // Maid KPIs
        $this->calculateMaidKpis($startDate, $endDate);
        
        // Financial KPIs
        $this->calculateFinancialKpis($startDate, $endDate);
        
        // Operational KPIs
        $this->calculateOperationalKpis($startDate, $endDate);
        
        // Package KPIs
        $this->calculatePackageKpis($startDate, $endDate);
    }

    protected function getStartDate(): Carbon
    {
        return match($this->selectedPeriod) {
            'week' => now()->subWeek(),
            'month' => now()->subMonth(),
            'quarter' => now()->subQuarter(),
            'year' => now()->subYear(),
            default => now()->subDays((int) $this->dateRange),
        };
    }

    protected function calculateBookingKpis($startDate, $endDate): void
    {
        $bookings = Booking::whereBetween('created_at', [$startDate, $endDate]);
        $totalBookings = $bookings->count();
        
        if ($totalBookings > 0) {
            $completedBookings = $bookings->where('status', 'completed')->count();
            $this->bookingCompletionRate = round(($completedBookings / $totalBookings) * 100, 2);
            
            // Calculate average booking value using actual calculated prices
            $totalValue = $bookings->sum('calculated_price');
            $this->averageBookingValue = $totalValue > 0 ? round($totalValue / $totalBookings) : 0;
        }
        
        // Calculate growth rate
        $periodDuration = $this->getPeriodDuration();
        $previousPeriodBookings = Booking::whereBetween('created_at', [
            $startDate->copy()->subDays($periodDuration),
            $startDate
        ])->count();
        
        if ($previousPeriodBookings > 0) {
            $this->bookingGrowthRate = round((($totalBookings - $previousPeriodBookings) / $previousPeriodBookings) * 100, 2);
        }
        
        // Client satisfaction (mock calculation)
        $this->clientSatisfactionScore = rand(85, 98);
    }

    protected function calculateTrainingKpis($startDate, $endDate): void
    {
        $evaluations = Evaluation::whereBetween('created_at', [$startDate, $endDate]);
        $totalEvaluations = $evaluations->count();
        
        if ($totalEvaluations > 0) {
            $completedEvaluations = $evaluations->where('status', 'approved')->count();
            $this->trainingCompletionRate = round(($completedEvaluations / $totalEvaluations) * 100, 2);
            
            // Calculate average KPI score
            $kpiFields = ['confidence', 'self_awareness', 'emotional_stability', 'growth_mindset', 
                         'punctuality', 'respect', 'ownership', 'conduct', 'cleaning', 'laundry', 'meals', 'childcare'];
            
            $totalKpiScore = 0;
            $kpiCount = 0;
            
            foreach ($evaluations->get() as $evaluation) {
                foreach ($kpiFields as $field) {
                    if (!is_null($evaluation->$field)) {
                        $totalKpiScore += $evaluation->$field;
                        $kpiCount++;
                    }
                }
            }
            
            $this->averageKpiScore = $kpiCount > 0 ? round(($totalKpiScore / $kpiCount) * 20, 2) : 0; // Convert to percentage
            
            // Trainer efficiency
            $this->trainerEfficiency = max(0, $this->trainingCompletionRate - 5);
            
            // Program success rate
            $this->programSuccessRate = $this->trainingCompletionRate;
        }
    }

    protected function calculateMaidKpis($startDate, $endDate): void
    {
        $totalMaids = Maid::count();
        
        if ($totalMaids > 0) {
            $deployedMaids = Maid::where('status', 'deployed')->count();
            $this->maidDeploymentRate = round(($deployedMaids / $totalMaids) * 100, 2);
            
            $availableMaids = Maid::where('status', 'available')->count();
            $this->maidAvailabilityRate = round(($availableMaids / $totalMaids) * 100, 2);
            
            // Maid retention (maids who haven't been terminated)
            $retainedMaids = Maid::whereNotIn('status', ['terminated', 'absconded'])->count();
            $this->maidRetentionRate = round(($retainedMaids / $totalMaids) * 100, 2);
            
            // Average maid rating (mock calculation)
            $this->averageMaidRating = rand(4, 5);
        }
    }

    protected function calculateFinancialKpis($startDate, $endDate): void
    {
        $bookings = Booking::whereBetween('created_at', [$startDate, $endDate]);
        
        // Calculate total revenue based on actual calculated prices
        $totalRevenue = $bookings->sum('calculated_price');
        
        $this->totalRevenue = $totalRevenue;
        
        $totalBookings = $bookings->count();
        $this->averageRevenuePerBooking = $totalBookings > 0 ? round($totalRevenue / $totalBookings) : 0;
        
        // Calculate growth rate
        $previousPeriodRevenue = $this->calculatePreviousPeriodRevenue($startDate);
        if ($previousPeriodRevenue > 0) {
            $this->revenueGrowthRate = round((($totalRevenue - $previousPeriodRevenue) / $previousPeriodRevenue) * 100, 2);
        }
        
        // Profit margin (mock calculation)
        $this->profitMargin = rand(15, 35);
    }

    protected function calculateOperationalKpis($startDate, $endDate): void
    {
        // Mock calculations for operational KPIs
        $this->averageResponseTime = rand(2, 8); // hours
        $this->systemUptime = rand(98, 100); // percentage
        $this->userEngagementRate = rand(75, 95); // percentage
        $this->conversionRate = rand(12, 25); // percentage
    }

    protected function calculatePackageKpis($startDate, $endDate): void
    {
        // Get all bookings with packages in the date range
        $bookings = Booking::with('package')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('package_id')
            ->get();
        
        // Initialize counters
        $this->silverBookings = 0;
        $this->goldBookings = 0;
        $this->platinumBookings = 0;
        $this->silverRevenue = 0;
        $this->goldRevenue = 0;
        $this->platinumRevenue = 0;
        $totalFamilySize = 0;
        
        // Process each booking
        foreach ($bookings as $booking) {
            if ($booking->package) {
                $totalFamilySize += $booking->family_size ?? 0;
                
                switch ($booking->package->name) {
                    case 'Silver':
                        $this->silverBookings++;
                        $this->silverRevenue += $booking->calculated_price ?? 0;
                        break;
                    case 'Gold':
                        $this->goldBookings++;
                        $this->goldRevenue += $booking->calculated_price ?? 0;
                        break;
                    case 'Platinum':
                        $this->platinumBookings++;
                        $this->platinumRevenue += $booking->calculated_price ?? 0;
                        break;
                }
            }
        }
        
        // Average family size
        $totalBookings = $bookings->count();
        $this->averageFamilySize = $totalBookings > 0 ? round($totalFamilySize / $totalBookings, 1) : 0;
        
        // Package distribution
        $this->packageDistribution = [
            'Silver' => $this->silverBookings,
            'Gold' => $this->goldBookings,
            'Platinum' => $this->platinumBookings,
        ];
        
        // Package performance metrics
        $this->packagePerformance = [
            'Silver' => [
                'bookings' => $this->silverBookings,
                'revenue' => $this->silverRevenue,
                'avg_revenue' => $this->silverBookings > 0 ? round($this->silverRevenue / $this->silverBookings) : 0,
            ],
            'Gold' => [
                'bookings' => $this->goldBookings,
                'revenue' => $this->goldRevenue,
                'avg_revenue' => $this->goldBookings > 0 ? round($this->goldRevenue / $this->goldBookings) : 0,
            ],
            'Platinum' => [
                'bookings' => $this->platinumBookings,
                'revenue' => $this->platinumRevenue,
                'avg_revenue' => $this->platinumBookings > 0 ? round($this->platinumRevenue / $this->platinumBookings) : 0,
            ],
        ];
    }

    protected function calculatePreviousPeriodRevenue($startDate): int
    {
        $periodDuration = $this->getPeriodDuration();
        $previousStart = $startDate->copy()->subDays($periodDuration);
        $previousEnd = $startDate;
        
        $bookings = Booking::whereBetween('created_at', [$previousStart, $previousEnd]);
        
        return $bookings->sum('calculated_price');
    }

    protected function getPeriodDuration(): int
    {
        return match($this->selectedPeriod) {
            'week' => 7,
            'month' => 30,
            'quarter' => 90,
            'year' => 365,
            default => (int) $this->dateRange,
        };
    }

    public function getTopPerformers()
    {
        return [
            'trainers' => Trainer::withCount('evaluations')
                ->orderBy('evaluations_count', 'desc')
                ->limit(5)
                ->get(),
            'maids' => Maid::where('status', 'deployed')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
            'clients' => Client::withCount('bookings')
                ->orderBy('bookings_count', 'desc')
                ->limit(5)
                ->get(),
        ];
    }

    public function getRecentActivity()
    {
        return [
            'bookings' => Booking::with(['client', 'maid'])
                ->latest()
                ->limit(5)
                ->get(),
            'evaluations' => Evaluation::with(['maid', 'trainer.user'])
                ->latest()
                ->limit(5)
                ->get(),
            'deployments' => Deployment::with(['maid', 'client'])
                ->latest()
                ->limit(5)
                ->get(),
        ];
    }

    public function getChartData()
    {
        $months = [];
        $bookingData = [];
        $revenueData = [];
        $trainingData = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();
            
            $bookings = Booking::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            $bookingData[] = $bookings;
            
            // Use calculated_price from database
            $revenue = Booking::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->sum('calculated_price') ?? 0;
            $revenueData[] = $revenue;
            
            $evaluations = Evaluation::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            $trainingData[] = $evaluations;
        }
        
        return [
            'months' => $months,
            'bookings' => $bookingData,
            'revenue' => $revenueData,
            'training' => $trainingData,
        ];
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.reports.kpi-dashboard', [
            'topPerformers' => $this->getTopPerformers(),
            'recentActivity' => $this->getRecentActivity(),
            'chartData' => $this->getChartData(),
        ]);
    }
}
