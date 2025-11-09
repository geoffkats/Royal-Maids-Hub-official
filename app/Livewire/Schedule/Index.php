<?php

namespace App\Livewire\Schedule;

use App\Models\TrainingProgram;
use App\Models\Evaluation;
use App\Models\Maid;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class Index extends Component
{
    use WithPagination;

    public $view = 'calendar'; // 'calendar' or 'list'
    public $selectedDate;
    public $selectedMonth;
    public $search = '';
    public $statusFilter = 'all';
    public $perPage = 10;

    public function mount()
    {
        $this->selectedDate = now()->format('Y-m-d');
        $this->selectedMonth = now()->format('Y-m');
    }

    public function updatedView()
    {
        $this->resetPage();
    }

    public function updatedSelectedMonth()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function selectDate($date)
    {
        $this->selectedDate = $date;
    }

    public function getSessionsProperty()
    {
        $query = TrainingProgram::where('trainer_id', auth()->user()->trainer->id)
            ->with(['maid']);

        // Only show training programs from the last 6 months to current + 3 months ahead
        $startDate = now()->subMonths(6);
        $endDate = now()->addMonths(3);
        $query->whereBetween('start_date', [$startDate, $endDate]);

        // Apply search filter
        if ($this->search) {
            $query->whereHas('maid', function($q) {
                $q->where('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%');
            });
        }

        // Apply status filter
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        // Apply month filter for calendar view
        if ($this->view === 'calendar') {
            $startOfMonth = Carbon::parse($this->selectedMonth)->startOfMonth();
            $endOfMonth = Carbon::parse($this->selectedMonth)->endOfMonth();
            $query->whereBetween('start_date', [$startOfMonth, $endOfMonth]);
        }

        return $query->orderBy('start_date')->get();
    }

    public function getCalendarDaysProperty()
    {
        $startOfMonth = Carbon::parse($this->selectedMonth)->startOfMonth();
        $endOfMonth = Carbon::parse($this->selectedMonth)->endOfMonth();
        
        // Get all sessions for the month (already filtered by date range in getSessionsProperty)
        $sessions = TrainingProgram::where('trainer_id', auth()->user()->trainer->id)
            ->whereBetween('start_date', [$startOfMonth, $endOfMonth])
            ->with(['maid'])
            ->get()
            ->groupBy(function($session) {
                return $session->start_date->format('Y-m-d');
            });
        
        $days = [];
        $current = $startOfMonth->copy()->startOfWeek();
        $end = $endOfMonth->copy()->endOfWeek();

        while ($current->lte($end)) {
            $dateString = $current->format('Y-m-d');
            $daySessions = $sessions->get($dateString, collect());

            $days[] = [
                'date' => $current->copy(),
                'sessions' => $daySessions,
                'isCurrentMonth' => $current->month === $startOfMonth->month,
                'isToday' => $current->isToday(),
                'isSelected' => $dateString === $this->selectedDate,
            ];

            $current->addDay();
        }

        return $days;
    }

    public function getSelectedDaySessionsProperty()
    {
        // Only show training programs from the last 6 months to current + 3 months ahead
        $startDate = now()->subMonths(6);
        $endDate = now()->addMonths(3);
        
        return TrainingProgram::where('trainer_id', auth()->user()->trainer->id)
            ->whereDate('start_date', $this->selectedDate)
            ->whereBetween('start_date', [$startDate, $endDate])
            ->with(['maid'])
            ->orderBy('start_date')
            ->get();
    }

    public function getUpcomingSessionsProperty()
    {
        // Only show upcoming training programs within the next 3 months
        $endDate = now()->addMonths(3);
        
        return TrainingProgram::where('trainer_id', auth()->user()->trainer->id)
            ->where('status', 'scheduled')
            ->whereDate('start_date', '>=', now())
            ->whereDate('start_date', '<=', $endDate)
            ->with(['maid'])
            ->orderBy('start_date')
            ->limit(5)
            ->get();
    }


    public function render()
    {
        return view('livewire.schedule.index')
            ->layout('components.layouts.app', ['title' => __('Training Schedule')]);
    }
}
