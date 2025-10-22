<?php

namespace App\Livewire\Maids;

use App\Models\Maid;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public ?string $status = null;

    #[Url]
    public ?string $role = null;

    #[Url]
    public int $perPage = 15;

    public function updating($name, $value): void
    {
        // Reset to first page whenever filters or search change
        if (in_array($name, ['search', 'status', 'role', 'perPage'], true)) {
            $this->resetPage();
        }
    }

    public function getStatuses(): array
    {
        return ['available','in-training','booked','deployed','absconded','terminated','on-leave'];
    }

    public function getRoles(): array
    {
        return ['housekeeper','house_manager','nanny','chef','elderly_caretaker','nakawere_caretaker'];
    }

    public function render()
    {
        $maids = $this->queryMaids();

        return view('livewire.maids.index', [
            'maids' => $maids,
            'statusOptions' => $this->getStatuses(),
            'roleOptions' => $this->getRoles(),
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
}
