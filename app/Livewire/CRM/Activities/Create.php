<?php

namespace App\Livewire\CRM\Activities;

use App\Models\CRM\Activity;
use App\Models\CRM\Lead;
use App\Models\CRM\Opportunity;
use App\Models\User;
use Livewire\Component;

class Create extends Component
{
    public $type = '';
    public $subject = '';
    public $description = '';
    public $due_date = '';
    public $priority = 'medium';
    public $related_type = '';
    public $related_id = '';
    public $assigned_to = '';

    protected function rules()
    {
        $rules = [
            'type' => 'required|in:call,email,meeting,task,note',
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date|after:now',
            'priority' => 'required|in:low,medium,high',
            'related_type' => 'required|in:lead,opportunity',
            'assigned_to' => 'nullable|exists:users,id',
        ];

        // Dynamic validation for related_id based on related_type
        if ($this->related_type === 'lead') {
            $rules['related_id'] = 'required|exists:crm_leads,id';
        } elseif ($this->related_type === 'opportunity') {
            $rules['related_id'] = 'required|exists:crm_opportunities,id';
        } else {
            $rules['related_id'] = 'required';
        }

        return $rules;
    }

    public function mount()
    {
        $this->assigned_to = auth()->id();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function updatedRelatedType()
    {
        $this->related_id = '';
    }

    public function save()
    {
        $this->validate();

        Activity::create([
            'type' => $this->type,
            'subject' => $this->subject,
            'description' => $this->description,
            'due_date' => $this->due_date ?: null,
            'priority' => $this->priority,
            'related_type' => $this->related_type,
            'related_id' => $this->related_id,
            'assigned_to' => $this->assigned_to ?: null,
            'created_by' => auth()->id(),
        ]);

        session()->flash('message', 'Activity created successfully.');
        return redirect()->route('crm.activities.index');
    }

    public function getUsersProperty()
    {
        return User::whereIn('role', ['admin', 'trainer'])->get();
    }

    public function getTypeOptionsProperty()
    {
        return [
            'call' => 'Call',
            'email' => 'Email',
            'meeting' => 'Meeting',
            'task' => 'Task',
            'note' => 'Note',
        ];
    }

    public function getPriorityOptionsProperty()
    {
        return [
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High',
        ];
    }

    public function getRelatedTypeOptionsProperty()
    {
        return [
            'lead' => 'Lead',
            'opportunity' => 'Opportunity',
        ];
    }

    public function getLeadsProperty()
    {
        return Lead::all();
    }

    public function getOpportunitiesProperty()
    {
        return Opportunity::all();
    }

    public function getRelatedItemsProperty()
    {
        if ($this->related_type === 'lead') {
            return Lead::all();
        } elseif ($this->related_type === 'opportunity') {
            return Opportunity::all();
        }
        return collect();
    }

    public function render()
    {
        return view('livewire.c-r-m.activities.create', [
            'users' => $this->users,
            'typeOptions' => $this->typeOptions,
            'priorityOptions' => $this->priorityOptions,
            'relatedTypeOptions' => $this->relatedTypeOptions,
            'relatedItems' => $this->relatedItems,
            'related_type' => $this->related_type,
        ]);
    }
}