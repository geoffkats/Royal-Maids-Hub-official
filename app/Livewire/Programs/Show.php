<?php

namespace App\Livewire\Programs;

use App\Models\TrainingProgram;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Show extends Component
{
    use AuthorizesRequests;

    public TrainingProgram $program;
    public bool $showDeleteModal = false;

    public function mount(TrainingProgram $program): void
    {
        $this->program = $program->load(['trainer.user', 'maid']);
        $this->authorize('view', $this->program);
    }

    public function toggleArchive(): void
    {
        $this->authorize('update', $this->program);

        if ($this->program->archived) {
            $this->program->unarchive();
            session()->flash('success', __('Training program unarchived successfully.'));
        } else {
            $this->program->archive();
            session()->flash('success', __('Training program archived successfully.'));
        }

        $this->program->refresh();
    }

    public function confirmDelete(): void
    {
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $this->authorize('delete', $this->program);

        $this->program->delete();

        session()->flash('success', __('Training program deleted successfully.'));
        $this->redirect(route('programs.index'), navigate: true);
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.programs.show', [
            'title' => __('Training Program Details'),
        ]);
    }
}
