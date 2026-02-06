<?php

namespace App\Livewire\ClientEvaluations;

use App\Models\ClientEvaluation;
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
        $this->authorize('viewAny', ClientEvaluation::class);

        $term = trim($this->search);
        $like = '%' . str_replace(['%','_'], ['\%','\_'], $term) . '%';

        $evaluations = ClientEvaluation::query()
            ->with(['client.user', 'trainer.user', 'booking'])
            ->when($term !== '', function ($query) use ($like) {
                $query->whereHas('client.user', fn ($q) => $q->where('name', 'like', $like))
                    ->orWhereHas('client', fn ($q) => $q->where('contact_person', 'like', $like))
                    ->orWhereHas('trainer.user', fn ($q) => $q->where('name', 'like', $like));
            })
            ->orderByDesc('evaluation_date')
            ->paginate(max(5, min(100, (int) $this->perPage)));

        return view('livewire.client-evaluations.index', [
            'evaluations' => $evaluations,
        ])->layout('components.layouts.app', ['title' => __('Client Evaluations')]);
    }
}
