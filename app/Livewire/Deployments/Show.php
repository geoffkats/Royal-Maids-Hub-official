<?php

namespace App\Livewire\Deployments;

use App\Models\Deployment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Deployment Details')]
class Show extends Component
{
    use AuthorizesRequests;

    public Deployment $deployment;

    public function mount(Deployment $deployment)
    {
        $this->deployment = $deployment;
        $this->authorize('view', $deployment);
    }

    public function restore(): void
    {
        $this->authorize('restore', $this->deployment);
        
        $this->deployment->restore();
        $this->deployment->refresh();
        
        session()->flash('success', 'Deployment restored successfully.');
    }

    public function toJSON(): array
    {
        return [];
    }

    public function render()
    {
        return view('livewire.deployments.show', [
            'deployment' => $this->deployment->load(['maid', 'client'])
        ]);
    }
}
