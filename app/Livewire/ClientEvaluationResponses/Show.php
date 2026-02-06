<?php

namespace App\Livewire\ClientEvaluationResponses;

use App\Models\ClientEvaluationQuestion;
use App\Models\ClientEvaluationResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Show extends Component
{
    use AuthorizesRequests;

    public ClientEvaluationResponse $response;

    public function mount(ClientEvaluationResponse $response): void
    {
        $this->response = $response->load(['booking', 'client.user', 'maid', 'package', 'link']);
        $this->authorize('view', $this->response);
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        $questions = ClientEvaluationQuestion::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('livewire.client-evaluation-responses.show', [
            'questions' => $questions,
        ]);
    }
}
