<?php

namespace App\Livewire\Deployments;

use App\Models\Deployment;
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
    public ?string $contractType = null;

    #[Url]
    public int $perPage = 15;

    public bool $showDetailsModal = false;
    public ?Deployment $selectedDeployment = null;

    public function updating($name, $value): void
    {
        if (in_array($name, ['search', 'status', 'contractType', 'perPage'], true)) {
            $this->resetPage();
        }
    }

    public function viewDetails(int $deploymentId): void
    {
        $this->selectedDeployment = Deployment::with(['maid', 'client'])->findOrFail($deploymentId);
        $this->showDetailsModal = true;
    }

    public function closeDetailsModal(): void
    {
        $this->showDetailsModal = false;
        $this->selectedDeployment = null;
    }

    public function endDeployment(int $deploymentId, string $reason): void
    {
        $deployment = Deployment::findOrFail($deploymentId);
        
        $deployment->update([
            'status' => 'completed',
            'end_date' => now(),
            'end_reason' => $reason,
        ]);

        // Update maid status back to available
        $deployment->maid->update(['status' => 'available']);

        session()->flash('success', __('Deployment ended successfully.'));
        $this->closeDetailsModal();
    }

    protected function query(): LengthAwarePaginator
    {
        $term = trim($this->search);

        return Deployment::query()
            ->with(['maid', 'client'])
            ->when($term !== '', function ($q) use ($term) {
                $like = '%' . str_replace(['%','_'], ['\\%','\\_'], $term) . '%';
                $q->where(function ($qq) use ($like) {
                    $qq->where('deployment_location', 'like', $like)
                        ->orWhere('client_name', 'like', $like)
                        ->orWhere('client_phone', 'like', $like)
                        ->orWhereHas('maid', fn($mq) => 
                            $mq->where('first_name', 'like', $like)
                                ->orWhere('last_name', 'like', $like)
                                ->orWhere('maid_code', 'like', $like)
                        );
                });
            })
            ->when(!empty($this->status), fn($q) => $q->where('status', $this->status))
            ->when(!empty($this->contractType), fn($q) => $q->where('contract_type', $this->contractType))
            ->orderByDesc('deployment_date')
            ->paginate(max(5, min(100, (int) $this->perPage)));
    }

    public function render()
    {
        return view('livewire.deployments.index', [
            'deployments' => $this->query(),
            'statusOptions' => ['active', 'completed', 'terminated'],
            'contractTypeOptions' => ['full-time', 'part-time', 'live-in', 'live-out'],
        ])->layout('components.layouts.app', ['title' => __('Deployments')]);
    }
}
