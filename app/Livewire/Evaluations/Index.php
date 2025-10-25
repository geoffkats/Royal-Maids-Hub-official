<?php

namespace App\Livewire\Evaluations;

use App\Models\Evaluation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Index extends Component
{
    use WithPagination, AuthorizesRequests;

    #[Url]
    public string $search = '';

    #[Url]
    public int $perPage = 15;

    #[Url]
    public string $statusFilter = 'all'; // all, pending, approved, rejected

    #[Url]
    public bool $showArchived = false;

    public array $selectedIds = [];
    public bool $selectAll = false;

    // Delete confirmation
    public bool $showDeleteModal = false;
    public ?int $deleteId = null;
    public ?string $deleteName = null;

    // Bulk action
    public ?string $bulkAction = null;

    public function updating($name, $value): void
    {
        if (in_array($name, ['search', 'perPage', 'statusFilter', 'showArchived'], true)) {
            $this->resetPage();
            $this->selectedIds = [];
            $this->selectAll = false;
        }

        // Handle select all checkbox
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
        $user = auth()->user();
        $term = trim($this->search);

        $query = Evaluation::query()
            ->with(['trainer.user', 'maid', 'program']);

        // Scope: trainers see only their evaluations; admin sees all
        if ($user->role === 'trainer') {
            $query->whereHas('trainer', fn($q) => $q->where('user_id', $user->id));
        }

        // Archive filter
        if ($this->showArchived) {
            $query->archived();
        } else {
            $query->active();
        }

        // Status filter
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        // Search
        if ($term !== '') {
            $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $term) . '%';
            $query->where(function ($q) use ($like) {
                $q->whereHas('maid', fn($mq) => $mq->where('first_name', 'like', $like)
                    ->orWhere('last_name', 'like', $like))
                  ->orWhereHas('trainer.user', fn($tq) => $tq->where('name', 'like', $like))
                  ->orWhere('general_comments', 'like', $like);
            });
        }

        return $query->orderByDesc('evaluation_date')
            ->orderByDesc('id')
            ->paginate(max(5, min(100, (int) $this->perPage)));
    }

    public function confirmDelete(int $id): void
    {
        $evaluation = Evaluation::with('maid')->findOrFail($id);
        $this->deleteId = $id;
        $this->deleteName = ($evaluation->maid?->first_name ?? '') . ' ' . ($evaluation->maid?->last_name ?? '');
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if ($this->deleteId === null) {
            return;
        }

        $evaluation = Evaluation::with('trainer')->findOrFail($this->deleteId);
        $this->authorize('delete', $evaluation);

        $evaluation->delete();

        $this->showDeleteModal = false;
        $this->deleteId = null;
        $this->deleteName = null;

        session()->flash('success', __('Evaluation deleted successfully.'));
    }

    public function toggleArchive(int $id): void
    {
        $evaluation = Evaluation::with('trainer')->findOrFail($id);
        $this->authorize('update', $evaluation);

        if ($evaluation->archived) {
            $evaluation->unarchive();
            session()->flash('success', __('Evaluation unarchived successfully.'));
        } else {
            $evaluation->archive();
            session()->flash('success', __('Evaluation archived successfully.'));
        }
    }

    public function applyBulkAction(): void
    {
        if (empty($this->selectedIds) || !$this->bulkAction) {
            return;
        }

        $evaluations = Evaluation::whereIn('id', $this->selectedIds)->get();

        foreach ($evaluations as $evaluation) {
            $this->authorize('update', $evaluation);
        }

        switch ($this->bulkAction) {
            case 'approve':
                Evaluation::whereIn('id', $this->selectedIds)->update(['status' => 'approved']);
                session()->flash('success', __(':count evaluations approved.', ['count' => count($this->selectedIds)]));
                break;

            case 'reject':
                Evaluation::whereIn('id', $this->selectedIds)->update(['status' => 'rejected']);
                session()->flash('success', __(':count evaluations rejected.', ['count' => count($this->selectedIds)]));
                break;

            case 'pending':
                Evaluation::whereIn('id', $this->selectedIds)->update(['status' => 'pending']);
                session()->flash('success', __(':count evaluations set to pending.', ['count' => count($this->selectedIds)]));
                break;

            case 'archive':
                foreach ($evaluations as $evaluation) {
                    $evaluation->archive();
                }
                session()->flash('success', __(':count evaluations archived.', ['count' => count($this->selectedIds)]));
                break;

            case 'unarchive':
                foreach ($evaluations as $evaluation) {
                    $evaluation->unarchive();
                }
                session()->flash('success', __(':count evaluations unarchived.', ['count' => count($this->selectedIds)]));
                break;
        }

        $this->selectedIds = [];
        $this->selectAll = false;
        $this->bulkAction = null;
    }

    public function exportPdf(): StreamedResponse
    {
        $this->authorize('viewAny', Evaluation::class);

        // Get all evaluations based on current filters (not paginated)
        $user = auth()->user();
        $term = trim($this->search);

        $query = Evaluation::query()
            ->with(['trainer.user', 'maid', 'program']);

        // Scope: trainers see only their evaluations; admin sees all
        if ($user->role === 'trainer') {
            $query->whereHas('trainer', fn($q) => $q->where('user_id', $user->id));
        }

        // Archive filter
        if ($this->showArchived) {
            $query->archived();
        } else {
            $query->active();
        }

        // Status filter
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        // Search
        if ($term !== '') {
            $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $term) . '%';
            $query->where(function ($q) use ($like) {
                $q->whereHas('maid', fn($mq) => $mq->where('first_name', 'like', $like)
                    ->orWhere('last_name', 'like', $like))
                  ->orWhereHas('trainer.user', fn($tq) => $tq->where('name', 'like', $like))
                  ->orWhere('general_comments', 'like', $like);
            });
        }

        $evaluations = $query->orderByDesc('evaluation_date')
            ->orderByDesc('id')
            ->paginate(100); // Use pagination for better performance with large datasets

        $filters = [
            'search' => $this->search,
            'status' => $this->statusFilter,
            'archived' => $this->showArchived,
        ];

        $pdf = Pdf::loadView('livewire.evaluations.pdf', [
            'evaluations' => $evaluations,
            'filters' => $filters,
        ]);

        $pdf->setPaper('a4', 'landscape');

        $filename = 'evaluations-' . now()->format('Y-m-d-His') . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }

    public function render()
    {
        $this->authorize('viewAny', Evaluation::class);

        return view('livewire.evaluations.index', [
            'evaluations' => $this->query(),
        ])->layout('components.layouts.app', ['title' => __('Evaluations')]);
    }
}
