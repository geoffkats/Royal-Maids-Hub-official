<?php

namespace App\Livewire\Contracts;

use App\Models\Maid;
use App\Models\MaidContract;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Url;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Maid Contracts')]
class Index extends Component
{
    use AuthorizesRequests, WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $status_filter = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = MaidContract::query()
            ->with(['maid', 'maid.deployments.client'])
            ->orderByDesc('created_at');

        if ($this->search) {
            $query->whereHas('maid', function ($q) {
                $q->where('first_name', 'like', "%{$this->search}%")
                    ->orWhere('last_name', 'like', "%{$this->search}%")
                    ->orWhere('maid_code', 'like', "%{$this->search}%");
            });
        }

        if ($this->status_filter) {
            $query->where('contract_status', $this->status_filter);
        }

        $contracts = $query->paginate(15);

        return view('livewire.contracts.index', [
            'contracts' => $contracts,
        ]);
    }
}
