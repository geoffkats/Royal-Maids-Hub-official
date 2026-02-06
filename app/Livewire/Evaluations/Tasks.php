<?php

namespace App\Livewire\Evaluations;

use App\Models\EvaluationTask;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Tasks extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public ?string $status = null;

    #[Url]
    public int $perPage = 15;

    public function updating($name, $value): void
    {
        if (in_array($name, ['search', 'status', 'perPage'], true)) {
            $this->resetPage();
        }
    }

    public function render()
    {
        $this->authorize('viewAny', EvaluationTask::class);

        $term = trim($this->search);
        $like = '%' . str_replace(['%','_'], ['\%','\_'], $term) . '%';

        $tasks = EvaluationTask::query()
            ->with(['maid', 'client'])
            ->when($term !== '', function ($query) use ($like) {
                $query->whereHas('maid', fn ($q) => $q->where('first_name', 'like', $like)->orWhere('last_name', 'like', $like))
                    ->orWhereHas('client', fn ($q) => $q->where('contact_person', 'like', $like));
            })
            ->when(!empty($this->status), fn ($query) => $query->where('status', $this->status))
            ->orderBy('due_date')
            ->paginate(max(5, min(100, (int) $this->perPage)));

        return view('livewire.evaluations.tasks', [
            'tasks' => $tasks,
        ])->layout('components.layouts.app', ['title' => __('Evaluation Tasks')]);
    }
}
