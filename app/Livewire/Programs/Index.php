<?php

namespace App\Livewire\Programs;

use App\Models\{TrainingProgram, Trainer};
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
    public ?string $status = null;

    #[Url]
    public int $perPage = 15;

    #[Url]
    public bool $showArchived = false;

    public array $selectedIds = [];
    public bool $selectAll = false;
    public bool $showDeleteModal = false;
    public ?int $deleteId = null;
    public ?string $deleteName = null;
    public string $bulkAction = '';

    public function updating($name, $value): void
    {
        if (in_array($name, ['search', 'status', 'perPage', 'showArchived'], true)) {
            $this->resetPage();
            $this->selectedIds = [];
            $this->selectAll = false;
        }

        if ($name === 'selectAll') {
            if ($value) {
                $this->selectedIds = $this->query()->pluck('id')->toArray();
            } else {
                $this->selectedIds = [];
            }
        }
    }

    protected function query(): LengthAwarePaginator
    {
        $term = trim($this->search);
        $user = auth()->user();

        return TrainingProgram::query()
            ->with(['trainer.user', 'maid'])
            ->when(!$this->showArchived, fn($q) => $q->active())
            ->when($this->showArchived, fn($q) => $q->archived())
            ->when($user->role === 'trainer', function ($q) use ($user) {
                // Trainers only see their own programs
                $q->whereHas('trainer', fn($tq) => $tq->where('user_id', $user->id));
            })
            ->when($term !== '', function ($q) use ($term) {
                $like = '%' . str_replace(['%','_'], ['\\%','\\_'], $term) . '%';
                $q->where(function ($qq) use ($like) {
                    $qq->where('program_type', 'like', $like)
                        ->orWhereHas('maid', fn($mq) => $mq->where('first_name', 'like', $like)->orWhere('last_name', 'like', $like))
                        ->orWhereHas('trainer.user', fn($uq) => $uq->where('name', 'like', $like));
                });
            })
            ->when(!empty($this->status), fn($q) => $q->where('status', $this->status))
            ->orderByDesc('id')
            ->paginate(max(5, min(100, (int) $this->perPage)));
    }

    public function confirmDelete(int $id, string $name): void
    {
        $this->deleteId = $id;
        $this->deleteName = $name;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if (!$this->deleteId) {
            return;
        }

        $program = TrainingProgram::findOrFail($this->deleteId);
        $this->authorize('delete', $program);

        $program->delete();

        $this->showDeleteModal = false;
        $this->deleteId = null;
        $this->deleteName = null;

        session()->flash('success', __('Training program deleted successfully.'));
        $this->dispatch('$refresh');
    }

    public function toggleArchive(int $id): void
    {
        $program = TrainingProgram::findOrFail($id);
        $this->authorize('update', $program);

        if ($program->archived) {
            $program->unarchive();
            session()->flash('success', __('Training program unarchived successfully.'));
        } else {
            $program->archive();
            session()->flash('success', __('Training program archived successfully.'));
        }
    }

    public function applyBulkAction(): void
    {
        if (empty($this->selectedIds) || empty($this->bulkAction)) {
            return;
        }

        $programs = TrainingProgram::whereIn('id', $this->selectedIds)->get();

        foreach ($programs as $program) {
            $this->authorize('update', $program);
        }

        $count = 0;
        foreach ($programs as $program) {
            switch ($this->bulkAction) {
                case 'scheduled':
                case 'in-progress':
                case 'completed':
                case 'cancelled':
                    $program->update(['status' => $this->bulkAction]);
                    $count++;
                    break;
                case 'archive':
                    if (!$program->archived) {
                        $program->archive();
                        $count++;
                    }
                    break;
                case 'unarchive':
                    if ($program->archived) {
                        $program->unarchive();
                        $count++;
                    }
                    break;
            }
        }

        $this->selectedIds = [];
        $this->selectAll = false;
        $this->bulkAction = '';

        session()->flash('success', __(':count training programs updated successfully.', ['count' => $count]));
        $this->dispatch('$refresh');
    }

    public function exportPdf(): void
    {
        session()->flash('info', __('PDF export feature coming soon.'));
    }

    public function render()
    {
        $this->authorize('viewAny', TrainingProgram::class);

        return view('livewire.programs.index', [
            'programs' => $this->query(),
            'statusOptions' => ['scheduled', 'in-progress', 'completed', 'cancelled'],
        ])->layout('components.layouts.app', ['title' => __('Training Programs')]);
    }
}
