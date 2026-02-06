<?php

namespace App\Livewire\Reports;

use App\Models\Booking;
use App\Models\Client;
use App\Models\Evaluation;
use App\Models\Maid;
use App\Models\Trainer;
use App\Models\TrainingProgram;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Index extends Component
{
    public $dateFrom;
    public $dateTo;
    public $reportType = 'overview'; // overview, evaluations, bookings, maids, training

    public function mount()
    {
        // Default to last 30 days
        $this->dateFrom = now()->subDays(30)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }

    public function exportPdf()
    {
        $data = $this->getReportData();
        
        $pdf = Pdf::loadView('livewire.reports.pdf', array_merge($data, [
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
            'reportType' => $this->reportType,
        ]));

        $pdf->setPaper('a4', 'portrait');
        
        $filename = 'report-' . $this->reportType . '-' . now()->format('Y-m-d-His') . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }

    protected function getReportData(): array
    {
        $from = $this->dateFrom ? \Carbon\Carbon::parse($this->dateFrom)->startOfDay() : null;
        $to = $this->dateTo ? \Carbon\Carbon::parse($this->dateTo)->endOfDay() : null;

        return [
            // Overview stats
            'totalUsers' => User::count(),
            'totalMaids' => Maid::count(),
            'totalClients' => Client::count(),
            'totalBookings' => Booking::count(),
            'totalEvaluations' => Evaluation::count(),
            'totalPrograms' => TrainingProgram::count(),
            
            // Evaluations
            'evaluationsByStatus' => Evaluation::when($from && $to, function ($q) use ($from, $to) {
                    $q->whereBetween('evaluation_date', [$from, $to]);
                })
                ->select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray(),
            
            'evaluationsByStatusLabels' => array_map('ucfirst', 
                array_keys(Evaluation::when($from && $to, function ($q) use ($from, $to) {
                    $q->whereBetween('evaluation_date', [$from, $to]);
                })
                ->select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray())
            ),
            
            'evaluationsByRating' => Evaluation::when($from && $to, function ($q) use ($from, $to) {
                    $q->whereBetween('evaluation_date', [$from, $to]);
                })
                ->select(
                    DB::raw('FLOOR(overall_rating) as rating'),
                    DB::raw('count(*) as count')
                )
                ->groupBy('rating')
                ->orderBy('rating', 'desc')
                ->get(),
            
            'averageRating' => Evaluation::when($from && $to, function ($q) use ($from, $to) {
                    $q->whereBetween('evaluation_date', [$from, $to]);
                })
                ->avg('overall_rating'),
            
            'recentEvaluations' => Evaluation::with(['maid', 'trainer.user'])
                ->when($from && $to, function ($q) use ($from, $to) {
                    $q->whereBetween('evaluation_date', [$from, $to]);
                })
                ->orderByDesc('evaluation_date')
                ->take(10)
                ->get(),
            
            // Maids
            'maidsByStatus' => Maid::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray(),
            
            'maidsByStatusLabels' => array_map('ucfirst', 
                array_keys(Maid::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray())
            ),
            
            'maidsByRole' => Maid::select('role', DB::raw('count(*) as count'))
                ->groupBy('role')
                ->pluck('count', 'role')
                ->toArray(),
            
            'maidsByRoleLabels' => array_map(fn($r) => ucwords(str_replace('_', ' ', $r)), 
                array_keys(Maid::select('role', DB::raw('count(*) as count'))
                ->groupBy('role')
                ->pluck('count', 'role')
                ->toArray())
            ),
            
            // Bookings
            'bookingsByStatus' => Booking::when($from && $to, function ($q) use ($from, $to) {
                    $q->whereBetween('start_date', [$from, $to]);
                })
                ->select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray(),
            
            'bookingsByStatusLabels' => array_map('ucfirst', 
                array_keys(Booking::when($from && $to, function ($q) use ($from, $to) {
                    $q->whereBetween('start_date', [$from, $to]);
                })
                ->select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray())
            ),
            
            'totalRevenue' => Booking::when($from && $to, function ($q) use ($from, $to) {
                    $q->whereBetween('start_date', [$from, $to]);
                })
                ->where('status', '!=', 'cancelled')
                ->sum('calculated_price'),
            
            // Revenue by package tier
            'revenueByPackage' => Booking::join('packages', 'bookings.package_id', '=', 'packages.id')
                ->when($from && $to, function ($q) use ($from, $to) {
                    $q->whereBetween('bookings.start_date', [$from, $to]);
                })
                ->where('bookings.status', '!=', 'cancelled')
                ->select('packages.name', DB::raw('SUM(bookings.calculated_price) as revenue'), DB::raw('COUNT(bookings.id) as count'))
                ->groupBy('packages.name')
                ->orderBy('revenue', 'desc')
                ->get(),
            
            'recentBookings' => Booking::with(['client', 'maid'])
                ->when($from && $to, function ($q) use ($from, $to) {
                    $q->whereBetween('start_date', [$from, $to]);
                })
                ->latest()
                ->take(10)
                ->get(),
            
            // Training
            'programsByStatus' => TrainingProgram::when($from && $to, function ($q) use ($from, $to) {
                    $q->whereBetween('start_date', [$from, $to]);
                })
                ->select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray(),
            
            'programsByStatusLabels' => array_map('ucfirst', 
                array_keys(TrainingProgram::when($from && $to, function ($q) use ($from, $to) {
                    $q->whereBetween('start_date', [$from, $to]);
                })
                ->select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray())
            ),
            
            'programsByType' => TrainingProgram::when($from && $to, function ($q) use ($from, $to) {
                    $q->whereBetween('start_date', [$from, $to]);
                })
                ->select('program_type', DB::raw('count(*) as count'))
                ->groupBy('program_type')
                ->pluck('count', 'program_type')
                ->toArray(),
            
            'programsByTypeLabels' => array_map('ucfirst', 
                array_keys(TrainingProgram::when($from && $to, function ($q) use ($from, $to) {
                    $q->whereBetween('start_date', [$from, $to]);
                })
                ->select('program_type', DB::raw('count(*) as count'))
                ->groupBy('program_type')
                ->pluck('count', 'program_type')
                ->toArray())
            ),
            
            'totalTrainers' => Trainer::count(),
            'activeTrainers' => Trainer::where('status', 'active')->count(),
            
            // Performance metrics
            'topRatedMaids' => Evaluation::select('maid_id', DB::raw('AVG(overall_rating) as avg_rating'), DB::raw('COUNT(*) as eval_count'))
                ->when($from && $to, function ($q) use ($from, $to) {
                    $q->whereBetween('evaluation_date', [$from, $to]);
                })
                ->groupBy('maid_id')
                ->having('eval_count', '>=', 2)
                ->orderByDesc('avg_rating')
                ->with('maid')
                ->take(10)
                ->get(),
            
            'monthlyTrends' => Evaluation::select(
                    DB::raw('DATE_FORMAT(evaluation_date, "%Y-%m") as month'),
                    DB::raw('COUNT(*) as count'),
                    DB::raw('AVG(overall_rating) as avg_rating')
                )
                ->when($from && $to, function ($q) use ($from, $to) {
                    $q->whereBetween('evaluation_date', [$from, $to]);
                })
                ->groupBy('month')
                ->orderBy('month')
                ->get(),
        ];
    }

    public function render()
    {
        // Admin and trainers can view reports
        // Trainers will see filtered data based on their programs/evaluations
        $user = auth()->user();
        $allowedRoles = [
            User::ROLE_TRAINER,
            User::ROLE_OPERATIONS_MANAGER,
            User::ROLE_FINANCE_OFFICER,
        ];

        if (!$user->isAdminLike() && !in_array($user->role, $allowedRoles, true)) {
            abort(403);
        }

        $data = $this->getReportData();

        return view('livewire.reports.index', $data)
            ->layout('components.layouts.app', ['title' => __('Comprehensive Reports')]);
    }
}

