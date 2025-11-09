<?php

namespace App\Livewire\Dashboard;

use App\Models\Trainer;
use App\Models\Evaluation;
use App\Models\Ticket;
use App\Models\TrainingProgram;
use Livewire\Component;

class TrainerDashboard extends Component
{
    public $trainer;
    public $myTrainees;
    public $activePrograms;
    public $recentEvaluations;
    public $upcomingSessions;
    public $myTickets;
    public $averageRating;
    public $completedTrainings;
    public $activeSessions;

    public function mount()
    {
        $user = auth()->user();
        
        // Check if user has trainer role
        if ($user->role !== 'trainer') {
            abort(403, 'Access denied. Trainer role required.');
        }
        
        $this->trainer = $user->trainer;
        
        if (!$this->trainer) {
            // If user has trainer role but no trainer profile, create one
            $this->trainer = Trainer::create([
                'user_id' => $user->id,
                'first_name' => explode(' ', $user->name)[0] ?? 'Trainer',
                'last_name' => explode(' ', $user->name)[1] ?? '',
                'email' => $user->email,
                'phone' => '',
                'specialization' => 'General Training',
                'status' => 'active',
            ]);
        }

        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        try {
            // My Trainees (distinct maids under this trainer's programs)
            $this->myTrainees = TrainingProgram::where('trainer_id', $this->trainer->id)
                ->distinct('maid_id')
                ->count('maid_id');

        // Active Training Sessions (in-progress programs for this trainer)
        $this->activeSessions = TrainingProgram::where('trainer_id', $this->trainer->id)
            ->where('status', 'in-progress')
            ->count();

        // Completed Trainings (completed programs for this trainer)
        $this->completedTrainings = TrainingProgram::where('trainer_id', $this->trainer->id)
            ->where('status', 'completed')
            ->count();
        
        // Average Rating from evaluations (using overall_rating column)
        $this->averageRating = Evaluation::where('trainer_id', $this->trainer->id)
            ->avg('overall_rating') ?? 0;
        
        // Active Programs (training programs for this trainer) - limit to 3 for performance
        $this->activePrograms = TrainingProgram::where('trainer_id', $this->trainer->id)
            ->whereIn('status', ['scheduled', 'in-progress'])
            ->with(['maid:id,first_name,last_name'])
            ->latest('start_date')
            ->limit(3)
            ->get();
        
        // Recent Evaluations - limit to 3 for performance
        $this->recentEvaluations = Evaluation::where('trainer_id', $this->trainer->id)
            ->with(['maid:id,first_name,last_name', 'program:id,program_type'])
            ->latest()
            ->limit(3)
            ->get();
        
        // Upcoming Sessions (next 7 days) from training programs - limit to 3 for performance
        $this->upcomingSessions = TrainingProgram::where('trainer_id', $this->trainer->id)
            ->where('status', 'scheduled')
            ->whereDate('start_date', '>=', now())
            ->whereDate('start_date', '<=', now()->addDays(7))
            ->with(['maid:id,first_name,last_name'])
            ->orderBy('start_date')
            ->limit(3)
            ->get();
        
        // My Support Tickets
        $this->myTickets = Ticket::where('trainer_id', $this->trainer->id)
            ->orWhere('requester_id', auth()->id())
            ->whereIn('status', ['open', 'in_progress', 'pending'])
            ->count();
            
        } catch (\Exception $e) {
            // Set default values if there's an error
            $this->myTrainees = 0;
            $this->activeSessions = 0;
            $this->completedTrainings = 0;
            $this->averageRating = 0;
            $this->activePrograms = collect();
            $this->recentEvaluations = collect();
            $this->upcomingSessions = collect();
            $this->myTickets = 0;
        }
    }

    public function render()
    {
        return view('livewire.dashboard.trainer-dashboard')
            ->layout('components.layouts.app', ['title' => __('Trainer Dashboard')]);
    }
}
