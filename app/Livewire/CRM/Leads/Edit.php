<?php

namespace App\Livewire\CRM\Leads;

use App\Models\CRM\Lead;
use App\Models\CRM\Source;
use App\Models\Package;
use App\Models\User;
use Livewire\Component;

class Edit extends Component
{
    public Lead $lead;
    
    // Form fields
    public $first_name = '';
    public $last_name = '';
    public $email = '';
    public $phone = '';
    public $company = '';
    public $job_title = '';
    public $industry = '';
    public $city = '';
    public $address = '';
    public $source_id = '';
    public $owner_id = '';
    public $status = 'new';
    public $interested_package_id = '';
    public $notes = '';

    protected $rules = [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'nullable|email|max:255',
        'phone' => 'nullable|string|max:255',
        'company' => 'nullable|string|max:255',
        'job_title' => 'nullable|string|max:255',
        'industry' => 'nullable|string|max:255',
        'city' => 'nullable|string|max:255',
        'address' => 'nullable|string',
        'source_id' => 'nullable|exists:crm_sources,id',
        'owner_id' => 'required|exists:users,id',
        'status' => 'required|in:new,working,qualified,disqualified,converted',
        'interested_package_id' => 'nullable|exists:packages,id',
        'notes' => 'nullable|string',
    ];

    public function mount(Lead $lead)
    {
        $this->lead = $lead;
        
        // Populate form fields with lead data
        $this->first_name = $lead->first_name ?? '';
        $this->last_name = $lead->last_name ?? '';
        $this->email = $lead->email ?? '';
        $this->phone = $lead->phone ?? '';
        $this->company = $lead->company ?? '';
        $this->job_title = $lead->job_title ?? '';
        $this->industry = $lead->industry ?? '';
        $this->city = $lead->city ?? '';
        $this->address = $lead->address ?? '';
        $this->source_id = $lead->source_id ?? '';
        $this->owner_id = $lead->owner_id ?? auth()->id();
        $this->status = $lead->status ?? 'new';
        $this->interested_package_id = $lead->interested_package_id ?? '';
        $this->notes = $lead->notes ?? '';
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function update()
    {
        $this->validate();

        $this->lead->update([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email ?: null,
            'phone' => $this->phone ?: null,
            'company' => $this->company ?: null,
            'job_title' => $this->job_title ?: null,
            'industry' => $this->industry ?: null,
            'city' => $this->city ?: null,
            'address' => $this->address ?: null,
            'source_id' => $this->source_id ?: null,
            'owner_id' => $this->owner_id,
            'status' => $this->status,
            'interested_package_id' => $this->interested_package_id ?: null,
            'notes' => $this->notes ?: null,
        ]);

        session()->flash('message', 'Lead updated successfully.');
        return redirect()->route('crm.leads.show', $this->lead);
    }

    public function render()
    {
        return view('livewire.c-r-m.leads.edit', [
            'sources' => Source::all(),
            'packages' => Package::where('is_active', true)->orderBy('sort_order')->get(),
            'users' => User::whereIn('role', ['admin', 'trainer'])->get(),
            'statusOptions' => [
                'new' => 'New',
                'working' => 'Working',
                'qualified' => 'Qualified',
                'disqualified' => 'Disqualified',
                'converted' => 'Converted',
            ],
        ]);
    }
}
