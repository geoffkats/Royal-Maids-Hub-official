<?php

namespace App\Livewire;

use App\Models\ContactSubmission;
use Livewire\Component;
use Livewire\WithPagination;

class ContactSubmissions extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $serviceFilter = '';
    public $selectedSubmission = null;
    public $showModal = false;
    public $notes = '';
    public $status = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'serviceFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingServiceFilter()
    {
        $this->resetPage();
    }

    public function viewSubmission($id)
    {
        $this->selectedSubmission = ContactSubmission::findOrFail($id);
        $this->notes = $this->selectedSubmission->notes ?? '';
        $this->status = $this->selectedSubmission->status;
        $this->showModal = true;
    }

    public function updateSubmission()
    {
        $this->validate([
            'status' => 'required|in:new,contacted,converted,closed',
            'notes' => 'nullable|string|max:1000',
        ]);

        $this->selectedSubmission->update([
            'status' => $this->status,
            'notes' => $this->notes,
            'contacted_at' => $this->status === 'contacted' ? now() : $this->selectedSubmission->contacted_at,
        ]);

        $this->showModal = false;
        $this->selectedSubmission = null;
        
        session()->flash('message', 'Submission updated successfully!');
    }

    public function deleteSubmission($id)
    {
        try {
            $submission = ContactSubmission::findOrFail($id);
            $submission->delete();
            
            session()->flash('message', 'Submission deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete submission. Please try again.');
        }
    }

    public function render()
    {
        $submissions = ContactSubmission::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->serviceFilter, function ($query) {
                $query->where('service', $this->serviceFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $statusCounts = ContactSubmission::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $serviceCounts = ContactSubmission::selectRaw('service, COUNT(*) as count')
            ->groupBy('service')
            ->pluck('count', 'service')
            ->toArray();

        return view('livewire.contact-submissions', [
            'submissions' => $submissions,
            'statusCounts' => $statusCounts,
            'serviceCounts' => $serviceCounts,
        ]);
    }
}