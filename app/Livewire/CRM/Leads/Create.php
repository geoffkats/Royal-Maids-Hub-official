<?php

namespace App\Livewire\CRM\Leads;

use App\Models\CRM\Lead;
use App\Models\CRM\Source;
use App\Models\Package;
use App\Models\User;
use App\Services\CRM\DuplicateDetectionService;
use Livewire\Component;

class Create extends Component
{
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

    // Duplicate detection
    public $showDuplicateModal = false;
    public $duplicates = [];
    public $duplicateSummary = [];
    public $ignoreDuplicates = false;

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

    public function mount()
    {
        // Set default owner to current user
        $this->owner_id = auth()->id();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function checkDuplicates()
    {
        $this->validate();

        // Create temporary lead object for duplicate detection
        $tempLead = new Lead([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'company' => $this->company,
        ]);

        $dupeService = new DuplicateDetectionService();
        $this->duplicateSummary = $dupeService->getDuplicateSummary($tempLead);

        // Show modal if high-confidence duplicates found
        if ($this->duplicateSummary['high_confidence_count'] > 0 && !$this->ignoreDuplicates) {
            $this->duplicates = $this->duplicateSummary['duplicates'];
            $this->showDuplicateModal = true;
            return;
        }

        // No duplicates or user chose to ignore - proceed with save
        $this->save();
    }

    public function continueAnyway()
    {
        $this->ignoreDuplicates = true;
        $this->showDuplicateModal = false;
        $this->save();
    }

    public function closeDuplicateModal()
    {
        $this->showDuplicateModal = false;
        $this->duplicates = [];
    }

    public function save()
    {
        $this->validate();

        $lead = Lead::create([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'company' => $this->company,
            'job_title' => $this->job_title,
            'industry' => $this->industry,
            'city' => $this->city,
            'address' => $this->address,
            'source_id' => $this->source_id,
            'owner_id' => $this->owner_id,
            'status' => $this->status,
            'interested_package_id' => $this->interested_package_id,
            'notes' => $this->notes,
            'score' => 0,
        ]);

        session()->flash('message', 'Lead created successfully.');
        return redirect()->route('crm.leads.show', $lead);
    }

    public function render()
    {
        return view('livewire.c-r-m.leads.create', [
            'sources' => Source::all(),
            'packages' => Package::where('is_active', true)->orderBy('sort_order')->get(),
            'users' => User::whereIn('role', ['admin', 'trainer'])->get(),
            'statusOptions' => [
                'new' => 'New',
                'working' => 'Working',
                'qualified' => 'Qualified',
                'disqualified' => 'Disqualified',
            ],
        ]);
    }
}
