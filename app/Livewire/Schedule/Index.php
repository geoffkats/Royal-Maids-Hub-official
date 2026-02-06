<?php

namespace App\Livewire\Schedule;

use App\Models\Deployment;
use App\Models\TrainingProgram;
use App\Models\Evaluation;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;

class Index extends Component
{
    public $view = 'calendar'; // 'calendar' or 'list'
    public $selectedDate;
    public $selectedMonth;
    public $search = '';
    public $statusFilter = 'all';
    public $typeFilter = 'all';

    public function mount()
    {
        $this->selectedDate = now()->format('Y-m-d');
        $this->selectedMonth = now()->format('Y-m');
    }

    public function updatedView()
    {
    }

    public function updatedSelectedMonth()
    {
    }

    public function updatedSearch()
    {
    }

    public function updatedStatusFilter()
    {
    }

    public function updatedTypeFilter()
    {
    }

    public function selectDate($date)
    {
        $this->selectedDate = $date;
    }

    public function getEventsProperty()
    {
        $startDate = now()->subMonths(6)->startOfDay();
        $endDate = now()->addMonths(3)->endOfDay();

        return $this->buildEvents($startDate, $endDate);
    }

    public function getMonthEventsProperty()
    {
        $startOfMonth = Carbon::parse($this->selectedMonth)->startOfMonth()->startOfDay();
        $endOfMonth = Carbon::parse($this->selectedMonth)->endOfMonth()->endOfDay();

        return $this->buildEvents($startOfMonth, $endOfMonth);
    }

    public function getCalendarDaysProperty()
    {
        $startOfMonth = Carbon::parse($this->selectedMonth)->startOfMonth();
        $endOfMonth = Carbon::parse($this->selectedMonth)->endOfMonth();
        
        // Get all events for the month
        $events = $this->monthEvents
            ->groupBy(function($session) {
                return $session['date']->format('Y-m-d');
            });
        
        $days = [];
        $current = $startOfMonth->copy()->startOfWeek();
        $end = $endOfMonth->copy()->endOfWeek();

        while ($current->lte($end)) {
            $dateString = $current->format('Y-m-d');
            $dayEvents = $events->get($dateString, collect());

            $days[] = [
                'date' => $current->copy(),
                'events' => $dayEvents,
                'isCurrentMonth' => $current->month === $startOfMonth->month,
                'isToday' => $current->isToday(),
                'isSelected' => $dateString === $this->selectedDate,
            ];

            $current->addDay();
        }

        return $days;
    }

    public function getSelectedDayEventsProperty()
    {
        if (!$this->selectedDate) {
            return collect();
        }

        $date = Carbon::parse($this->selectedDate)->startOfDay();

        return $this->buildEvents($date, $date->copy()->endOfDay());
    }

    public function getUpcomingEventsProperty()
    {
        $endDate = now()->addMonths(3)->endOfDay();

        return $this->buildEvents(now()->startOfDay(), $endDate)
            ->sortBy('date')
            ->take(5)
            ->values();
    }

    protected function isTrainer(): bool
    {
        return auth()->user()->role === 'trainer' && auth()->user()->trainer;
    }

    protected function buildEvents(Carbon $startDate, Carbon $endDate)
    {
        $events = collect();

        $trainingQuery = TrainingProgram::with(['maid'])
            ->whereBetween('start_date', [$startDate, $endDate]);

        if ($this->isTrainer()) {
            $trainingQuery->where('trainer_id', auth()->user()->trainer->id);
        }

        if ($this->search) {
            $search = $this->search;
            $trainingQuery->whereHas('maid', function ($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%');
            });
        }

        $trainingQuery->get()->each(function ($program) use ($events) {
            $events->push([
                'key' => 'training-' . $program->id,
                'type' => 'training',
                'date' => $program->start_date,
                'title' => $program->maid?->full_name ?? __('Unknown Maid'),
                'subtitle' => $program->program_type,
                'status' => $program->status,
                'model' => $program,
            ]);
        });

        $evaluationQuery = Evaluation::with(['maid', 'program'])
            ->whereBetween('evaluation_date', [$startDate, $endDate]);

        if ($this->isTrainer()) {
            $evaluationQuery->where('trainer_id', auth()->user()->trainer->id);
        }

        if ($this->search) {
            $search = $this->search;
            $evaluationQuery->whereHas('maid', function ($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%');
            });
        }

        $evaluationQuery->get()->each(function ($evaluation) use ($events) {
            $events->push([
                'key' => 'evaluation-' . $evaluation->id,
                'type' => 'evaluation',
                'date' => $evaluation->evaluation_date,
                'title' => $evaluation->maid?->full_name ?? __('Unknown Maid'),
                'subtitle' => $evaluation->program?->program_type ?? __('Evaluation'),
                'status' => $evaluation->status,
                'model' => $evaluation,
            ]);
        });

        $deploymentQuery = Deployment::with(['maid', 'client'])
            ->whereNotNull('deployment_date')
            ->whereBetween('deployment_date', [$startDate, $endDate]);

        if ($this->isTrainer()) {
            $deploymentQuery->whereHas('maid.trainingPrograms', function ($q) {
                $q->where('trainer_id', auth()->user()->trainer->id);
            });
        }

        if ($this->search) {
            $search = $this->search;
            $deploymentQuery->where(function ($q) use ($search) {
                $q->whereHas('maid', function ($maidQuery) use ($search) {
                    $maidQuery->where('first_name', 'like', '%' . $search . '%')
                        ->orWhere('last_name', 'like', '%' . $search . '%');
                })
                ->orWhere('client_name', 'like', '%' . $search . '%')
                ->orWhereHas('client', function ($clientQuery) use ($search) {
                    $clientQuery->where('company_name', 'like', '%' . $search . '%')
                        ->orWhere('name', 'like', '%' . $search . '%');
                });
            });
        }

        $deploymentQuery->get()->each(function ($deployment) use ($events) {
            $events->push([
                'key' => 'deployment-' . $deployment->id,
                'type' => 'deployment',
                'date' => $deployment->deployment_date,
                'title' => $deployment->maid?->full_name ?? __('Unknown Maid'),
                'subtitle' => $deployment->client?->company_name ?? $deployment->client_name ?? __('Client'),
                'status' => $deployment->status,
                'model' => $deployment,
            ]);

            $hasFinance = $deployment->monthly_salary
                || $deployment->maid_salary
                || $deployment->client_payment
                || $deployment->service_paid
                || $deployment->payment_status
                || $deployment->salary_paid_date;

            if ($hasFinance && Gate::allows('viewSensitiveFinancials', $deployment)) {
                $events->push([
                    'key' => 'finance-' . $deployment->id,
                    'type' => 'finance',
                    'date' => $deployment->salary_paid_date ?? $deployment->deployment_date,
                    'title' => $deployment->maid?->full_name ?? __('Unknown Maid'),
                    'subtitle' => $deployment->client?->company_name ?? $deployment->client_name ?? __('Client'),
                    'status' => $deployment->payment_status ?? 'pending',
                    'model' => $deployment,
                    'finance' => [
                        'monthly_salary' => $deployment->monthly_salary,
                        'maid_salary' => $deployment->maid_salary,
                        'client_payment' => $deployment->client_payment,
                        'service_paid' => $deployment->service_paid,
                        'payment_status' => $deployment->payment_status,
                        'salary_paid_date' => $deployment->salary_paid_date,
                        'currency' => $deployment->currency,
                    ],
                ]);
            }
        });

        $events = $events->filter(function ($event) {
            if ($this->typeFilter !== 'all' && $event['type'] !== $this->typeFilter) {
                return false;
            }

            if ($this->statusFilter !== 'all' && ($event['status'] ?? null) !== $this->statusFilter) {
                return false;
            }

            return true;
        });

        return $events->sortBy('date')->values();
    }


    public function render()
    {
        return view('livewire.schedule.index')
            ->layout('components.layouts.app', ['title' => __('Training Schedule')]);
    }
}
