<?php

namespace App\Livewire\CRM\Activities;

use Livewire\Component;
use App\Models\CRM\Activity;

class Show extends Component
{
    public Activity $activity;

    public function mount(Activity $activity)
    {
        $this->activity = $activity->load(['assignedTo', 'createdBy', 'related']);
    }

    public function markCompleted()
    {
        $this->activity->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        session()->flash('message', 'Activity marked as completed successfully.');
    }

    public function markCancelled()
    {
        $this->activity->update([
            'status' => 'cancelled',
        ]);

        session()->flash('message', 'Activity cancelled successfully.');
    }

    public function render()
    {
        return view('livewire.c-r-m.activities.show');
    }
}
