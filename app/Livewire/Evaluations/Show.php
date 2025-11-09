<?php

namespace App\Livewire\Evaluations;

use App\Models\Evaluation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Show extends Component
{
    use AuthorizesRequests;

    public Evaluation $evaluation;
    
    public bool $showDeleteModal = false;

    public function mount(Evaluation $evaluation): void
    {
        $this->evaluation = $evaluation->load(['trainer.user', 'maid', 'program']);
        $this->authorize('view', $this->evaluation);
    }

    public function toggleArchive(): void
    {
        // Only admins can archive/unarchive evaluations
        if (auth()->user()->role !== 'admin') {
            session()->flash('error', __('Only administrators can archive evaluations.'));
            return;
        }

        $this->authorize('update', $this->evaluation);

        if ($this->evaluation->archived) {
            $this->evaluation->unarchive();
            session()->flash('success', __('Evaluation unarchived successfully.'));
        } else {
            $this->evaluation->archive();
            session()->flash('success', __('Evaluation archived successfully.'));
        }

        $this->evaluation->refresh();
    }

    public function confirmDelete(): void
    {
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $this->authorize('delete', $this->evaluation);

        $this->evaluation->delete();

        session()->flash('success', __('Evaluation deleted successfully.'));
        $this->redirectRoute('evaluations.index', navigate: true);
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.evaluations.show', [
            'title' => __('Evaluation Details'),
        ]);
    }
}
