<?php

namespace App\Livewire\Programs;

use App\Models\{TrainingProgram, Trainer, Maid};
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Create extends Component
{
    use AuthorizesRequests;

    public ?int $trainer_id = null;
    public ?int $maid_id = null;
    public string $program_type = '';
    public string $start_date = '';
    public ?string $end_date = null;
    public string $status = 'scheduled';
    public ?string $notes = null;
    public int $hours_required = 40;

    public function mount(): void
    {
        $this->authorize('create', TrainingProgram::class);

        $user = auth()->user();
        // If trainer, pre-select their own trainer_id
        if ($user->role === 'trainer') {
            $trainer = Trainer::where('user_id', $user->id)->first();
            if ($trainer) {
                $this->trainer_id = $trainer->id;
            }
        }
    }

    protected function rules(): array
    {
        return [
            'trainer_id' => ['required', 'exists:trainers,id'],
            'maid_id' => ['required', 'exists:maids,id'],
            'program_type' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['required', Rule::in(['scheduled', 'in-progress', 'completed', 'cancelled'])],
            'notes' => ['nullable', 'string'],
            'hours_required' => ['required', 'integer', 'min:1', 'max:500'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        TrainingProgram::create([
            'trainer_id' => $this->trainer_id,
            'maid_id' => $this->maid_id,
            'program_type' => $this->program_type,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
            'notes' => $this->notes,
            'hours_required' => $this->hours_required,
            'hours_completed' => 0,
        ]);

        session()->flash('success', __('Training program created successfully.'));
        $this->redirectRoute('programs.index', navigate: true);
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.programs.create', [
            'title' => __('New Training Program'),
            'trainers' => Trainer::with('user')->get(),
            'maids' => Maid::where('status', 'in-training')->get(),
        ]);
    }
}
