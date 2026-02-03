<?php

namespace App\Livewire\Maids;

use App\Models\Maid;
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
    public ?string $role = null;

    #[Url]
    public int $perPage = 15;

    // Bulk selection state
    public array $selected = [];
    public bool $selectPage = false;

    public function mount(): void
    {
        $this->authorize('viewAny', Maid::class);
    }

    public function updating($name, $value): void
    {
        // Reset to first page whenever filters or search change
        if (in_array($name, ['search', 'status', 'role', 'perPage'], true)) {
            $this->resetPage();
            // Reset selections when changing dataset
            $this->resetSelection();
        }
    }

    public function updatedSelectPage($value): void
    {
        // Toggle selecting the current page of results
        $pageIds = $this->getCurrentPageIds();
        if ($value) {
            $this->selected = array_values(array_unique(array_merge($this->selected, $pageIds)));
        } else {
            // Remove current page IDs from selection
            $this->selected = array_values(array_diff($this->selected, $pageIds));
        }
    }

    public function deleteSelected(): void
    {
        if (empty($this->selected)) {
            return;
        }

        Maid::query()
            ->whereIn('id', $this->selected)
            ->delete();

        $count = count($this->selected);
        $this->resetSelection();

        session()->flash('message', "$count maids deleted successfully!");
    }

    protected function resetSelection(): void
    {
        $this->selected = [];
        $this->selectPage = false;
    }

    public function getStatuses(): array
    {
        return ['available','in-training','booked','deployed','absconded','terminated','on-leave'];
    }

    public function getRoles(): array
    {
        return ['housekeeper','house_manager','nanny','chef','elderly_caretaker','nakawere_caretaker'];
    }

    public function delete($id): void
    {
        $maid = Maid::findOrFail($id);
        $maid->delete();
        
        session()->flash('message', 'Maid deleted successfully!');
    }

    public function render()
    {
        $maids = $this->queryMaids();
        
        // Get statistics from all maids, not just filtered results
        $allMaids = Maid::all();

        return view('livewire.maids.index', [
            'maids' => $maids,
            'allMaids' => $allMaids, // Add this for KPI calculations
            'statusOptions' => $this->getStatuses(),
            'roleOptions' => $this->getRoles(),
            'pageIds' => $maids->pluck('id')->toArray(),
        ]);
    }

    protected function queryMaids(): LengthAwarePaginator
    {
        $term = trim($this->search);

        return Maid::query()
            ->when($term !== '', function ($q) use ($term) {
                $like = '%' . str_replace(['%','_'], ['\%','\_'], $term) . '%';
                $q->where(function ($qq) use ($like) {
                    $qq->where('first_name', 'like', $like)
                        ->orWhere('last_name', 'like', $like)
                        ->orWhere('maid_code', 'like', $like)
                        ->orWhere('phone', 'like', $like);
                });
            })
            ->when(!empty($this->status), fn($q) => $q->where('status', $this->status))
            ->when(!empty($this->role), fn($q) => $q->where('role', $this->role))
            ->orderByDesc('id')
            ->paginate(max(5, min(100, (int) $this->perPage)));
    }

    protected function getCurrentPageIds(): array
    {
        // Get IDs for the currently visible page based on current filters and pagination
        return $this->queryMaids()->pluck('id')->toArray();
    }
}
