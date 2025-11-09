<?php

namespace App\Livewire\Reports;

use App\Models\TrainingProgram;
use App\Models\Evaluation;
use App\Models\Maid;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class TrainerReports extends Component
{
    use AuthorizesRequests;

    public $dateRange = '30'; // days
    public $selectedPeriod = 'month';
    public $exportFormat = 'pdf';
    public $showExportModal = false;
    public $selectedMaid = null;
    public $statusFilter = 'all';
    
    // Overview Metrics
    public $totalEvaluations = 0;
    public $totalTrainingPrograms = 0;
    public $totalTrainees = 0;
    public $totalHours = 0;
    
    // Evaluation KPIs
    public $evaluationCompletionRate = 0;
    public $averageOverallRating = 0;
    public $pendingEvaluations = 0;
    public $approvedEvaluations = 0;
    
    // Training Program KPIs
    public $programCompletionRate = 0;
    public $activePrograms = 0;
    public $scheduledPrograms = 0;
    public $completedPrograms = 0;
    
    // Performance KPIs
    public $averageRatingTrend = 0;
    public $trainingEfficiency = 0;
    public $submissionRate = 0;
    public $approvalRate = 0;
    
    // Score Breakdowns
    public $personalityAverage = 0;
    public $behaviorAverage = 0;
    public $performanceAverage = 0;
    
    // Status Distribution
    public $evaluationStatusDistribution = [];
    public $programStatusDistribution = [];
    
    public function mount(): void
    {
        $user = auth()->user();
        
        // Ensure user is a trainer
        if ($user->role !== 'trainer') {
            abort(403, 'Access denied. Trainer role required.');
        }
        
        $this->calculateMetrics();
    }

    public function updatedDateRange(): void
    {
        $this->calculateMetrics();
    }

    public function updatedSelectedPeriod(): void
    {
        $this->calculateMetrics();
    }

    public function updatedSelectedMaid(): void
    {
        $this->calculateMetrics();
    }

    public function updatedStatusFilter(): void
    {
        $this->calculateMetrics();
    }

    protected function getTrainer()
    {
        return auth()->user()->trainer;
    }

    protected function getStartDate(): Carbon
    {
        return match($this->selectedPeriod) {
            'week' => now()->subWeek(),
            'month' => now()->subMonth(),
            'quarter' => now()->subQuarter(),
            'year' => now()->subYear(),
            'all' => Carbon::create(2020, 1, 1), // All time - use a very early date
            default => now()->subDays((int) $this->dateRange),
        };
    }

    protected function calculateMetrics(): void
    {
        $trainer = $this->getTrainer();
        
        if (!$trainer) {
            return;
        }

        $startDate = $this->getStartDate();
        $endDate = now();
        
        // Build base queries with date filter
        $evaluationQuery = Evaluation::where('trainer_id', $trainer->id)
            ->whereBetween('evaluation_date', [$startDate, $endDate]);
            
        $programQuery = TrainingProgram::where('trainer_id', $trainer->id)
            ->whereBetween('start_date', [$startDate, $endDate]);

        // Apply maid filter if selected
        if ($this->selectedMaid) {
            $evaluationQuery->where('maid_id', $this->selectedMaid);
            $programQuery->where('maid_id', $this->selectedMaid);
        }

        // Apply status filter for evaluations
        if ($this->statusFilter !== 'all') {
            $evaluationQuery->where('status', $this->statusFilter);
        }

        // Get all evaluations and programs
        $evaluations = $evaluationQuery->get();
        $programs = $programQuery->get();
        
        // Overview Metrics
        $this->totalEvaluations = $evaluations->count();
        $this->totalTrainingPrograms = $programs->count();
        $this->totalTrainees = $programs->pluck('maid_id')->unique()->count();
        $this->totalHours = $programs->sum('hours_completed') ?? 0;
        
        // Evaluation KPIs
        $this->pendingEvaluations = $evaluations->where('status', 'pending')->count();
        $this->approvedEvaluations = $evaluations->where('status', 'approved')->count();
        $this->evaluationCompletionRate = $this->totalEvaluations > 0 
            ? round(($this->approvedEvaluations / $this->totalEvaluations) * 100, 2) 
            : 0;
        $this->averageOverallRating = $evaluations->avg('overall_rating') ?? 0;
        
        // Training Program KPIs
        $this->activePrograms = $programs->where('status', 'in-progress')->count();
        $this->scheduledPrograms = $programs->where('status', 'scheduled')->count();
        $this->completedPrograms = $programs->where('status', 'completed')->count();
        $this->programCompletionRate = $this->totalTrainingPrograms > 0 
            ? round(($this->completedPrograms / $this->totalTrainingPrograms) * 100, 2) 
            : 0;
        
        // Performance KPIs
        $this->approvalRate = $this->totalEvaluations > 0 
            ? round(($this->approvedEvaluations / $this->totalEvaluations) * 100, 2) 
            : 0;
        $this->trainingEfficiency = $this->totalTrainingPrograms > 0 
            ? round(($this->completedPrograms / $this->totalTrainingPrograms) * 100, 2) 
            : 0;
        
        // Calculate score breakdowns from evaluations
        $personalityScores = [];
        $behaviorScores = [];
        $performanceScores = [];
        
        foreach ($evaluations as $evaluation) {
            if ($evaluation->scores) {
                // Personality scores
                if (isset($evaluation->scores['personality'])) {
                    foreach ($evaluation->scores['personality'] as $key => $value) {
                        if ($key !== 'comments' && is_numeric($value)) {
                            $personalityScores[] = $value;
                        }
                    }
                }
                
                // Behavior scores
                if (isset($evaluation->scores['behavior'])) {
                    foreach ($evaluation->scores['behavior'] as $key => $value) {
                        if ($key !== 'comments' && is_numeric($value)) {
                            $behaviorScores[] = $value;
                        }
                    }
                }
                
                // Performance scores
                if (isset($evaluation->scores['performance'])) {
                    foreach ($evaluation->scores['performance'] as $key => $value) {
                        if ($key !== 'comments' && is_numeric($value)) {
                            $performanceScores[] = $value;
                        }
                    }
                }
            }
        }
        
        $this->personalityAverage = count($personalityScores) > 0 
            ? round(array_sum($personalityScores) / count($personalityScores), 2) 
            : 0;
        $this->behaviorAverage = count($behaviorScores) > 0 
            ? round(array_sum($behaviorScores) / count($behaviorScores), 2) 
            : 0;
        $this->performanceAverage = count($performanceScores) > 0 
            ? round(array_sum($performanceScores) / count($performanceScores), 2) 
            : 0;
        
        // Status distributions
        $this->evaluationStatusDistribution = [
            'pending' => $evaluations->where('status', 'pending')->count(),
            'approved' => $evaluations->where('status', 'approved')->count(),
            'rejected' => $evaluations->where('status', 'rejected')->count(),
        ];
        
        $this->programStatusDistribution = [
            'scheduled' => $programs->where('status', 'scheduled')->count(),
            'in-progress' => $programs->where('status', 'in-progress')->count(),
            'completed' => $programs->where('status', 'completed')->count(),
            'cancelled' => $programs->where('status', 'cancelled')->count(),
        ];
    }

    public function exportReport(): void
    {
        $this->showExportModal = true;
    }

    public function generateExport(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $this->showExportModal = false;
        
        if ($this->exportFormat === 'pdf') {
            return $this->exportPdf();
        } else {
            return $this->exportCsv();
        }
    }

    protected function exportPdf(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $trainer = $this->getTrainer();
        $data = $this->getExportData();
        
        $pdf = Pdf::loadView('reports.trainer-export', [
            'trainer' => $trainer,
            'data' => $data,
            'period' => $this->selectedPeriod,
            'generatedAt' => now(),
        ]);
        
        $filename = 'trainer-report-' . now()->format('Y-m-d-His') . '.pdf';
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }

    protected function exportCsv(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $data = $this->getExportData();
        $csv = $this->generateCsvData($data);
        
        $filename = 'trainer-report-' . now()->format('Y-m-d-His') . '.csv';
        
        return response()->streamDownload(function () use ($csv) {
            echo $csv;
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    protected function getExportData(): array
    {
        return [
            'totalEvaluations' => $this->totalEvaluations,
            'totalTrainingPrograms' => $this->totalTrainingPrograms,
            'totalTrainees' => $this->totalTrainees,
            'totalHours' => $this->totalHours,
            'averageOverallRating' => $this->averageOverallRating,
            'evaluationCompletionRate' => $this->evaluationCompletionRate,
            'programCompletionRate' => $this->programCompletionRate,
            'approvalRate' => $this->approvalRate,
            'trainingEfficiency' => $this->trainingEfficiency,
            'personalityAverage' => $this->personalityAverage,
            'behaviorAverage' => $this->behaviorAverage,
            'performanceAverage' => $this->performanceAverage,
        ];
    }

    protected function generateCsvData(array $data): string
    {
        $csv = "Trainer Performance Report\n";
        $csv .= "Generated: " . now()->format('Y-m-d H:i:s') . "\n";
        $csv .= "Period: " . $this->selectedPeriod . "\n\n";
        
        $csv .= "Key Metrics\n";
        $csv .= "Total Evaluations," . $data['totalEvaluations'] . "\n";
        $csv .= "Total Training Programs," . $data['totalTrainingPrograms'] . "\n";
        $csv .= "Total Trainees," . $data['totalTrainees'] . "\n";
        $csv .= "Total Hours," . $data['totalHours'] . "\n";
        $csv .= "Average Overall Rating," . $data['averageOverallRating'] . "\n";
        $csv .= "Evaluation Completion Rate," . $data['evaluationCompletionRate'] . "%\n";
        $csv .= "Program Completion Rate," . $data['programCompletionRate'] . "%\n";
        $csv .= "Approval Rate," . $data['approvalRate'] . "%\n";
        $csv .= "Training Efficiency," . $data['trainingEfficiency'] . "%\n\n";
        
        $csv .= "Score Averages\n";
        $csv .= "Personality Average," . $data['personalityAverage'] . "\n";
        $csv .= "Behavior Average," . $data['behaviorAverage'] . "\n";
        $csv .= "Performance Average," . $data['performanceAverage'] . "\n";
        
        return $csv;
    }

    public function resetFilters(): void
    {
        $this->dateRange = '30';
        $this->selectedPeriod = 'month';
        $this->selectedMaid = null;
        $this->statusFilter = 'all';
        $this->calculateMetrics();
    }

    public function getMaidsProperty()
    {
        $trainer = $this->getTrainer();
        if (!$trainer) {
            return collect();
        }
        
        return Maid::whereHas('trainingPrograms', function($query) use ($trainer) {
            $query->where('trainer_id', $trainer->id);
        })->get();
    }

    public function getTopPerformersProperty()
    {
        $trainer = $this->getTrainer();
        if (!$trainer) {
            return collect();
        }
        
        $startDate = $this->getStartDate();
        
        return Evaluation::where('trainer_id', $trainer->id)
            ->whereBetween('evaluation_date', [$startDate, now()])
            ->with('maid')
            ->orderBy('overall_rating', 'desc')
            ->limit(5)
            ->get();
    }

    public function getRecentEvaluationsProperty()
    {
        $trainer = $this->getTrainer();
        if (!$trainer) {
            return collect();
        }
        
        return Evaluation::where('trainer_id', $trainer->id)
            ->with(['maid', 'program'])
            ->latest('evaluation_date')
            ->limit(10)
            ->get();
    }

    public function getRecentProgramsProperty()
    {
        $trainer = $this->getTrainer();
        if (!$trainer) {
            return collect();
        }
        
        return TrainingProgram::where('trainer_id', $trainer->id)
            ->with('maid')
            ->latest('start_date')
            ->limit(10)
            ->get();
    }

    public function getChartData()
    {
        $trainer = $this->getTrainer();
        if (!$trainer) {
            return [
                'months' => [],
                'evaluations' => [],
                'ratings' => [],
                'programs' => [],
            ];
        }
        
        $months = [];
        $evaluationData = [];
        $ratingData = [];
        $programData = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();
            
            $evaluations = Evaluation::where('trainer_id', $trainer->id)
                ->whereBetween('evaluation_date', [$startOfMonth, $endOfMonth])
                ->get();
            
            $evaluationData[] = $evaluations->count();
            $ratingData[] = $evaluations->avg('overall_rating') ?? 0;
            
            $programs = TrainingProgram::where('trainer_id', $trainer->id)
                ->whereBetween('start_date', [$startOfMonth, $endOfMonth])
                ->get();
            
            $programData[] = $programs->count();
        }
        
        return [
            'months' => $months,
            'evaluations' => $evaluationData,
            'ratings' => $ratingData,
            'programs' => $programData,
        ];
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.reports.trainer-reports', [
            'topPerformers' => $this->topPerformers,
            'recentEvaluations' => $this->recentEvaluations,
            'recentPrograms' => $this->recentPrograms,
            'chartData' => $this->getChartData(),
        ]);
    }
}
