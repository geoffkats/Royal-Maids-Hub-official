<?php

namespace App\Livewire\Evaluations;

use App\Models\Evaluation;
use App\Models\Trainer;
use App\Models\Maid;
use App\Models\TrainingProgram;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Create extends Component
{
    use AuthorizesRequests;

    public ?int $trainer_id = null;
    public ?int $maid_id = null;
    public ?int $program_id = null;
    public string $evaluation_date = '';
    public string $status = 'pending';
    
    // Personality Evaluation
    public int $confidence = 3;
    public int $self_awareness = 3;
    public int $emotional_stability = 3;
    public int $growth_mindset = 3;
    public ?string $personality_comments = null;
    
    // Behavior Evaluation
    public int $punctuality = 3;
    public int $respect_for_instructions = 3;
    public int $work_ownership = 3;
    public int $interpersonal_conduct = 3;
    public ?string $behavior_comments = null;
    
    // Performance Evaluation
    public int $alertness = 3;
    public int $first_aid_ability = 3;
    public int $security_consciousness = 3;
    public ?string $performance_comments = null;
    
    // General
    public ?string $general_comments = null;
    public ?string $strengths = null;
    public ?string $areas_for_improvement = null;

    public function mount(): void
    {
        $this->authorize('create', Evaluation::class);
        
        $user = auth()->user();
        
        // Auto-assign trainer if user is a trainer
        if ($user->role === 'trainer') {
            $trainer = Trainer::where('user_id', $user->id)->first();
            if ($trainer) {
                $this->trainer_id = $trainer->id;
            }
            // Trainers can only create pending evaluations
            $this->status = 'pending';
        }

        $this->evaluation_date = now()->format('Y-m-d');
    }

    protected function rules(): array
    {
        return [
            'trainer_id' => ['required', 'exists:trainers,id'],
            'maid_id' => ['required', 'exists:maids,id'],
            'program_id' => ['nullable', 'exists:training_programs,id'],
            'evaluation_date' => ['required', 'date'],
            'status' => ['required', 'in:pending,approved,rejected'],
            
            // Personality scores
            'confidence' => ['required', 'integer', 'min:1', 'max:5'],
            'self_awareness' => ['required', 'integer', 'min:1', 'max:5'],
            'emotional_stability' => ['required', 'integer', 'min:1', 'max:5'],
            'growth_mindset' => ['required', 'integer', 'min:1', 'max:5'],
            'personality_comments' => ['nullable', 'string'],
            
            // Behavior scores
            'punctuality' => ['required', 'integer', 'min:1', 'max:5'],
            'respect_for_instructions' => ['required', 'integer', 'min:1', 'max:5'],
            'work_ownership' => ['required', 'integer', 'min:1', 'max:5'],
            'interpersonal_conduct' => ['required', 'integer', 'min:1', 'max:5'],
            'behavior_comments' => ['nullable', 'string'],
            
            // Performance scores
            'alertness' => ['required', 'integer', 'min:1', 'max:5'],
            'first_aid_ability' => ['required', 'integer', 'min:1', 'max:5'],
            'security_consciousness' => ['required', 'integer', 'min:1', 'max:5'],
            'performance_comments' => ['nullable', 'string'],
            
            // General comments
            'general_comments' => ['nullable', 'string'],
            'strengths' => ['nullable', 'string'],
            'areas_for_improvement' => ['nullable', 'string'],
        ];
    }

    public function updatedStatus($value): void
    {
        // Prevent trainers from changing status
        if (auth()->user()->role === 'trainer' && $value !== 'pending') {
            $this->status = 'pending';
        }
    }

    public function save(): void
    {
        // Ensure trainers can only save pending evaluations
        if (auth()->user()->role === 'trainer') {
            $this->status = 'pending';
        }
        
        $this->validate();

        // Build scores structure
        $scores = [
            'personality' => [
                'confidence' => $this->confidence,
                'self_awareness' => $this->self_awareness,
                'emotional_stability' => $this->emotional_stability,
                'growth_mindset' => $this->growth_mindset,
                'comments' => $this->personality_comments,
            ],
            'behavior' => [
                'punctuality' => $this->punctuality,
                'respect_for_instructions' => $this->respect_for_instructions,
                'work_ownership' => $this->work_ownership,
                'interpersonal_conduct' => $this->interpersonal_conduct,
                'comments' => $this->behavior_comments,
            ],
            'performance' => [
                'alertness' => $this->alertness,
                'first_aid_ability' => $this->first_aid_ability,
                'security_consciousness' => $this->security_consciousness,
                'comments' => $this->performance_comments,
            ],
        ];

        // Calculate overall rating
        $allScores = [
            $this->confidence, $this->self_awareness, $this->emotional_stability, $this->growth_mindset,
            $this->punctuality, $this->respect_for_instructions, $this->work_ownership, $this->interpersonal_conduct,
            $this->alertness, $this->first_aid_ability, $this->security_consciousness,
        ];
        $overallRating = round(array_sum($allScores) / count($allScores), 1);

        Evaluation::create([
            'trainer_id' => $this->trainer_id,
            'maid_id' => $this->maid_id,
            'program_id' => $this->program_id,
            'evaluation_date' => $this->evaluation_date,
            'status' => $this->status,
            'scores' => $scores,
            'overall_rating' => $overallRating,
            'general_comments' => $this->general_comments,
            'strengths' => $this->strengths,
            'areas_for_improvement' => $this->areas_for_improvement,
        ]);

        session()->flash('success', __('Evaluation created successfully.'));
        $this->redirectRoute('evaluations.index', navigate: true);
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        $user = auth()->user();
        
        // Get trainers and programs based on user role
        if ($user->role === 'admin') {
            $trainers = Trainer::with('user')->get();
            $programs = TrainingProgram::with(['trainer.user', 'maid'])->get();
        } else {
            // Trainer sees only their data
            $trainers = Trainer::with('user')->where('user_id', $user->id)->get();
            $programs = TrainingProgram::with(['trainer.user', 'maid'])
                ->whereHas('trainer', fn($q) => $q->where('user_id', $user->id))
                ->get();
        }

        return view('livewire.evaluations.create', [
            'title' => __('New Evaluation'),
            'trainers' => $trainers,
            'maids' => Maid::all(),
            'programs' => $programs,
        ]);
    }
}
