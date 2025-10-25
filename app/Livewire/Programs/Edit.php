<?php

namespace App\Livewire\Programs;

use App\Models\{TrainingProgram, Trainer, Maid};
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Edit extends Component
{
    use AuthorizesRequests;

    public TrainingProgram $program;

    public ?int $trainer_id = null;
    public ?int $maid_id = null;
    public string $program_type = '';
    public string $start_date = '';
    public ?string $end_date = null;
    public string $status = 'scheduled';
    public ?string $notes = null;
    public int $hours_completed = 0;
    public int $hours_required = 40;

    public function mount(TrainingProgram $program): void
    {
        $this->program = $program->load(['trainer.user', 'maid']);
        $this->authorize('update', $this->program);

        $this->trainer_id = $program->trainer_id;
        $this->maid_id = $program->maid_id;
        $this->program_type = $program->program_type;
        $this->start_date = $program->start_date?->format('Y-m-d') ?? '';
        $this->end_date = $program->end_date?->format('Y-m-d');
        $this->status = $program->status;
        $this->notes = $program->notes;
        $this->hours_completed = (int) $program->hours_completed;
        $this->hours_required = (int) $program->hours_required;
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
            'hours_completed' => ['required', 'integer', 'min:0', 'lte:hours_required'],
        ];
    }

    public function update(): void
    {
        $this->validate();

        $this->program->update([
            'trainer_id' => $this->trainer_id,
            'maid_id' => $this->maid_id,
            'program_type' => $this->program_type,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
            'notes' => $this->notes,
            'hours_completed' => $this->hours_completed,
            'hours_required' => $this->hours_required,
        ]);

        session()->flash('success', __('Training program updated successfully.'));
        $this->redirectRoute('programs.index', navigate: true);
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.programs.edit', [
            'title' => __('Edit Training Program'),
            'trainers' => Trainer::with('user')->get(),
            'maids' => Maid::where('status', 'in-training')->get(),
        ]);
    }
}
