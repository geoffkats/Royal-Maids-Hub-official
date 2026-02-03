<?php

namespace App\Livewire\Trainers;

use App\Models\Trainer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    #[Url]
    public string $search = '';

    #[Url]
    public int $perPage = 15;

    // Deletion confirmation state
    public bool $showDeleteModal = false;
    public ?int $deleteId = null;
    public ?string $deleteName = null;

    public function mount(): void
    {
        $this->authorize('viewAny', Trainer::class);
    }

    public function updating($name, $value): void
    {
        if (in_array($name, ['search', 'perPage'], true)) {
            $this->resetPage();
        }
    }

    protected function query(): LengthAwarePaginator
    {
        $term = trim($this->search);

        return Trainer::query()
            ->with('user')
            ->when($term !== '', function ($q) use ($term) {
                $like = '%' . str_replace(['%','_'], ['\\%','\\_'], $term) . '%';
                $q->whereHas('user', function ($uq) use ($like) {
                    $uq->where('name', 'like', $like)
                        ->orWhere('email', 'like', $like);
                })->orWhere('specialization', 'like', $like);
            })
            ->orderByDesc('id')
            ->paginate(max(5, min(100, (int) $this->perPage)));
    }

    public function delete(int $trainerId): void
    {
        $trainer = Trainer::with('user')->findOrFail($trainerId);
        $this->authorize('delete', $trainer);

        // Deleting the user will cascade and delete the trainer via FK
        if ($trainer->user) {
            $trainer->user->delete();
        } else {
            $trainer->delete();
        }

        session()->flash('success', __('Trainer deleted successfully.'));
        
        // Reset to first page if current page becomes empty after deletion
        if ($this->getPage() > 1 && $this->query()->isEmpty()) {
            $this->resetPage();
        }
    }

    public function confirmDelete(int $trainerId): void
    {
        $trainer = Trainer::with('user')->findOrFail($trainerId);
        $this->deleteId = $trainer->id;
        $this->deleteName = trim(($trainer->user?->name ?? '') . ' (' . ($trainer->user?->email ?? '') . ')');
        $this->showDeleteModal = true;
    }

    public function cancelDelete(): void
    {
        $this->reset('showDeleteModal', 'deleteId', 'deleteName');
    }

    public function deleteConfirmed(): void
    {
        if ($this->deleteId) {
            $this->delete($this->deleteId);
        }
        $this->cancelDelete();
    }

    public function render()
    {
        $this->authorize('viewAny', Trainer::class);

        return view('livewire.trainers.index', [
            'trainers' => $this->query(),
        ])->layout('components.layouts.app', ['title' => __('Trainers')]);
    }
}
