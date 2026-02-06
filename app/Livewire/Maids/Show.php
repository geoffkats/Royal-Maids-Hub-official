<?php

namespace App\Livewire\Maids;

use App\Models\Maid;
use App\Models\Ticket;
use App\Models\MaidContract;
use App\Models\Deployment;
use App\Models\TrainingProgram;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\WithPagination;

class Show extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public Maid $maid;
    public string $activeTab = 'overview';

    public function mount(Maid $maid)
    {
        $this->authorize('view', $maid);
        $this->maid = $maid;
    }

    public function restore(): void
    {
        $this->maid->restore();
        $this->maid->refresh();
        
        session()->flash('success', 'Maid restored successfully.');
    }

    public function setActiveTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function render()
    {
        // Get tickets for this maid
        $tickets = Ticket::where('maid_id', $this->maid->id)
            ->with(['client', 'requester'])
            ->latest()
            ->paginate(10);

        // Get contracts for this maid
        $contracts = MaidContract::where('maid_id', $this->maid->id)
            ->latest()
            ->take(5)
            ->get();

        // Get active deployment
        $deployment = Deployment::where('maid_id', $this->maid->id)
            ->where('status', 'active')
            ->with('client')
            ->first();

        // Get training programs
        $trainingPrograms = TrainingProgram::where('maid_id', $this->maid->id)
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.maids.show', [
            'tickets' => $tickets,
            'contracts' => $contracts,
            'deployment' => $deployment,
            'trainingPrograms' => $trainingPrograms,
        ]);
    }
}