<?php

namespace App\Livewire\ClientEvaluationResponses;

use App\Models\ClientEvaluationResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public int $perPage = 15;

    public function updating($name, $value): void
    {
        if (in_array($name, ['search', 'perPage'], true)) {
            $this->resetPage();
        }
    }

    public function render()
    {
        $this->authorize('viewAny', ClientEvaluationResponse::class);

        $term = trim($this->search);
        $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $term) . '%';

        $responses = ClientEvaluationResponse::query()
            ->with(['booking', 'client.user', 'maid', 'package'])
            ->when($term !== '', function ($query) use ($like) {
                $query->where('respondent_name', 'like', $like)
                    ->orWhere('respondent_email', 'like', $like)
                    ->orWhereHas('client.user', fn ($q) => $q->where('name', 'like', $like))
                    ->orWhereHas('maid', fn ($q) => $q->where('first_name', 'like', $like)->orWhere('last_name', 'like', $like));
            })
            ->orderByDesc('submitted_at')
            ->orderByDesc('id')
            ->paginate(max(5, min(100, (int) $this->perPage)));

        return view('livewire.client-evaluation-responses.index', [
            'responses' => $responses,
        ])->layout('components.layouts.app', ['title' => __('Client Feedback')]);
    }
}
