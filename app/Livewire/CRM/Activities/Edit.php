<?php

namespace App\Livewire\CRM\Activities;

use Livewire\Component;
use App\Models\CRM\Activity;
use App\Models\CRM\Lead;
use App\Models\CRM\Opportunity;
use App\Models\User;

class Edit extends Component
{
    public Activity $activity;
    
    // Form fields
    public $type;
    public $subject;
    public $description;
    public $due_date;
    public $priority;
    public $status;
    public $outcome;
    public $assigned_to;
    public $related_type;
    public $related_id;

    protected $rules = [
        'type' => 'required|in:call,email,meeting,task,note',
        'subject' => 'required|string|max:255',
        'description' => 'nullable|string',
        'due_date' => 'nullable|date|after:now',
        'priority' => 'required|in:low,medium,high',
        'status' => 'required|in:pending,completed,cancelled',
        'outcome' => 'nullable|string|max:500',
        'assigned_to' => 'nullable|exists:users,id',
        'related_type' => 'nullable|string',
        'related_id' => 'nullable|integer',
    ];

    public function mount(Activity $activity)
    {
        $this->activity = $activity;
        
        // Populate form fields
        $this->type = $activity->type;
        $this->subject = $activity->subject;
        $this->description = $activity->description;
        $this->due_date = $activity->due_date?->format('Y-m-d\TH:i');
        $this->priority = $activity->priority;
        $this->status = $activity->status;
        $this->outcome = $activity->outcome;
        $this->assigned_to = $activity->assigned_to;
        $this->related_type = $activity->related_type;
        $this->related_id = $activity->related_id;
    }

    public function save()
    {
        $this->validate();

        $updateData = [
            'type' => $this->type,
            'subject' => $this->subject,
            'description' => $this->description,
            'due_date' => $this->due_date ? \Carbon\Carbon::parse($this->due_date) : null,
            'priority' => $this->priority,
            'status' => $this->status,
            'outcome' => $this->outcome,
            'assigned_to' => $this->assigned_to,
            'related_type' => $this->related_type,
            'related_id' => $this->related_id,
        ];

        // Set completed_at if status is completed
        if ($this->status === 'completed' && !$this->activity->completed_at) {
            $updateData['completed_at'] = now();
        }

        $this->activity->update($updateData);

        session()->flash('message', 'Activity updated successfully.');
        
        return redirect()->route('crm.activities.show', $this->activity);
    }

    public function getUsersProperty()
    {
        return User::whereIn('role', ['admin', 'trainer'])->get();
    }

    public function getLeadsProperty()
    {
        return Lead::select('id', 'first_name', 'last_name')->get();
    }

    public function getOpportunitiesProperty()
    {
        return Opportunity::select('id', 'title')->get();
    }

    public function render()
    {
        return view('livewire.c-r-m.activities.edit');
    }
}
