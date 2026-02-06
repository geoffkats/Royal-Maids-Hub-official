<?php

namespace App\Livewire\ClientEvaluations;

use App\Models\ClientEvaluation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Show extends Component
{
    use AuthorizesRequests;

    public ClientEvaluation $evaluation;

    public function mount(ClientEvaluation $evaluation): void
    {
        $this->evaluation = $evaluation->load(['client.user', 'trainer.user', 'booking']);
        $this->authorize('view', $this->evaluation);
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.client-evaluations.show', [
            'title' => __('Client Evaluation Details'),
        ]);
    }
}
