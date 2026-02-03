<?php

namespace App\Livewire\Maids;

use App\Models\Maid;
use App\Models\Deployment;
use App\Models\Client;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Edit extends Component
{
    use WithFileUploads;

    public Maid $maid;

    // Personal Information
    public $first_name = '';
    public $last_name = '';
    public $phone = '';
    public $mobile_number_2 = '';
    public $date_of_birth = '';
    public $date_of_arrival = '';
    public $nationality = '';
    public $nin_number = '';

    // Location Details
    public $tribe = '';
    public $village = '';
    public $district = '';
    public $lc1_chairperson = '';

    // Family Information
    public $mother_name_phone = '';
    public $father_name_phone = '';

    // Personal Details
    public $marital_status = 'single';
    public $number_of_children = 0;

    // Education & Experience
    public $education_level = 'P.7';
    public $experience_years = 0;
    public $mother_tongue = '';
    public $english_proficiency = 1;
    public $previous_work = '';

    // Professional Information
    public $role = 'housekeeper';

    // Status Management
    public $status = 'available';
    public $secondary_status = 'available';
    public $work_status = 'full-time';
    public $previousStatus = '';

    // Deployment Modal
    public $showDeploymentModal = false;
    public $deployment_date = '';
    public $deployment_location = '';
    public $deployment_client_id = null;
    public $deployment_client_name = '';
    public $deployment_client_phone = '';
    public $deployment_address = '';
    public $monthly_salary = null;
    public $contract_type = 'full-time';
    public $contract_start_date = '';
    public $contract_end_date = '';
    public $deployment_special_instructions = '';
    public $deployment_notes = '';

    // Medical Information
    public $hepatitis_b_result = null;
    public $hepatitis_b_date = null;
    public $hiv_result = null;
    public $hiv_date = null;
    public $urine_hcg_result = null;
    public $urine_hcg_date = null;
    public $medical_notes = null;

    // File Uploads
    public $profile_image;
    public $additional_documents = [];
    public $id_scans = [];

    // Additional Information
    public $additional_notes = '';

    // Current file paths (for display)
    public $current_profile_image = '';
    public $current_additional_documents = [];
    public $current_id_scans = [];

    // Options for dropdowns
    public $tribes = [
        'Acholi', 'Alur', 'Baganda', 'Bagisu', 'Bagwere', 'Bakiga', 'Bakonjo', 'Banyankole', 
        'Banyoro', 'Batoro', 'Bunyoro', 'Iteso', 'Jopadhola', 'Karamojong', 'Langi', 'Lugbara', 
        'Madi', 'Muganda', 'Munyankole', 'Mukiga', 'Mugisu', 'Mugwere', 'Munyoro', 'Mutoro', 
        'Mukonjo', 'Mugungu', 'Mufumbira', 'Other'
    ];

    public $districts = [
        'Abim', 'Adjumani', 'Agago', 'Alebtong', 'Amolatar', 'Amudat', 'Amuria', 'Amuru', 'Apac', 
        'Arua', 'Budaka', 'Bududa', 'Bugiri', 'Bugweri', 'Buhweju', 'Buikwe', 'Bukedea', 'Bukomansimbi', 
        'Bukwo', 'Bulambuli', 'Buliisa', 'Bundibugyo', 'Bunyangabu', 'Bushenyi', 'Busia', 'Butaleja', 
        'Butebo', 'Buvuma', 'Buyende', 'Dokolo', 'Gomba', 'Gulu', 'Hoima', 'Ibanda', 'Iganga', 'Isingiro', 
        'Jinja', 'Kaabong', 'Kabale', 'Kabarole', 'Kaberamaido', 'Kalaki', 'Kalangala', 'Kaliro', 'Kalungu', 
        'Kampala', 'Kamuli', 'Kamwenge', 'Kanungu', 'Kapchorwa', 'Kapelebyong', 'Karenga', 'Kasese', 
        'Kassanda', 'Katakwi', 'Kayunga', 'Kazo', 'Kibaale', 'Kiboga', 'Kibuku', 'Kikuube', 'Kiruhura', 
        'Kiryandongo', 'Kisoro', 'Kitagwenda', 'Kitgum', 'Koboko', 'Kole', 'Kotido', 'Kumi', 'Kwania', 
        'Kyegegwa', 'Kyenjojo', 'Kyotera', 'Lamwo', 'Lira', 'Luuka', 'Luwero', 'Lwengo', 'Lyantonde', 
        'Madi-Okollo', 'Manafwa', 'Maracha', 'Masaka', 'Masindi', 'Mayuge', 'Mbale', 'Mbarara', 'Mitooma', 
        'Mityana', 'Moroto', 'Moyo', 'Mpigi', 'Mubende', 'Mukono', 'Nabilatuk', 'Nakapiripirit', 'Nakaseke', 
        'Nakasongola', 'Namayingo', 'Namisindwa', 'Namutumba', 'Napak', 'Nebbi', 'Ngora', 'Ntoroko', 'Ntungamo', 
        'Nwoya', 'Obongi', 'Omoro', 'Otuke', 'Oyam', 'Pader', 'Pakwach', 'Pallisa', 'Rakai', 'Rubanda', 
        'Rubirizi', 'Rukiga', 'Rukungiri', 'Rwampara', 'Sembabule', 'Serere', 'Sheema', 'Sironko', 'Soroti', 
        'Tororo', 'Wakiso', 'Yumbe', 'Zombo', 'Other'
    ];

    public $education_levels = ['P.7', 'S.4', 'S.6', 'Certificate', 'Diploma'];
    public $marital_statuses = ['single', 'married'];
    public $roles = [
        'housekeeper', 'house_manager', 'nanny', 'chef',
        'elderly_caretaker', 'nakawere_caretaker'
    ];
    public $statuses = [
        'available', 'in-training', 'booked', 'deployed',
        'absconded', 'terminated', 'on-leave'
    ];
    public $work_statuses = ['brokerage', 'long-term', 'part-time', 'full-time'];

    protected $rules = [
        // Rules are now defined in the save() method for proper validation
    ];

    public function mount(Maid $maid)
    {
        $this->maid = $maid;
        
        // Store initial status
        $this->previousStatus = $maid->status;
        
        // Load existing data
        $this->first_name = $maid->first_name;
        $this->last_name = $maid->last_name;
        $this->phone = $maid->phone;
        $this->mobile_number_2 = $maid->mobile_number_2;
        $this->date_of_birth = $maid->date_of_birth?->format('Y-m-d');
        $this->date_of_arrival = $maid->date_of_arrival?->format('Y-m-d');
        $this->nationality = $maid->nationality;
        $this->nin_number = $maid->nin_number;
        $this->tribe = $maid->tribe;
        $this->village = $maid->village;
        $this->district = $maid->district;
        $this->lc1_chairperson = $maid->lc1_chairperson;
        $this->mother_name_phone = $maid->mother_name_phone;
        $this->father_name_phone = $maid->father_name_phone;
        $this->marital_status = $maid->marital_status;
        $this->number_of_children = $maid->number_of_children;
        $this->education_level = $maid->education_level;
        $this->experience_years = $maid->experience_years;
        $this->mother_tongue = $maid->mother_tongue;
        $this->english_proficiency = $maid->english_proficiency;
        $this->previous_work = $maid->previous_work;
        $this->role = $maid->role;
        $this->status = $maid->status;
        $this->secondary_status = $maid->secondary_status;
        $this->work_status = $maid->work_status;
        $this->additional_notes = $maid->additional_notes;

        // Load medical status
        if ($maid->medical_status && is_array($maid->medical_status)) {
            $this->hepatitis_b_result = $maid->medical_status['hepatitis_b']['result'] ?? null;
            $this->hepatitis_b_date = !empty($maid->medical_status['hepatitis_b']['date']) 
                ? (is_string($maid->medical_status['hepatitis_b']['date']) 
                    ? $maid->medical_status['hepatitis_b']['date'] 
                    : \Carbon\Carbon::parse($maid->medical_status['hepatitis_b']['date'])->format('Y-m-d'))
                : null;
            $this->hiv_result = $maid->medical_status['hiv']['result'] ?? null;
            $this->hiv_date = !empty($maid->medical_status['hiv']['date']) 
                ? (is_string($maid->medical_status['hiv']['date']) 
                    ? $maid->medical_status['hiv']['date'] 
                    : \Carbon\Carbon::parse($maid->medical_status['hiv']['date'])->format('Y-m-d'))
                : null;
            $this->urine_hcg_result = $maid->medical_status['urine_hcg']['result'] ?? null;
            $this->urine_hcg_date = !empty($maid->medical_status['urine_hcg']['date']) 
                ? (is_string($maid->medical_status['urine_hcg']['date']) 
                    ? $maid->medical_status['urine_hcg']['date'] 
                    : \Carbon\Carbon::parse($maid->medical_status['urine_hcg']['date'])->format('Y-m-d'))
                : null;
            $this->medical_notes = $maid->medical_status['notes'] ?? null;
        }

        // Load current file paths
        $this->current_profile_image = $maid->profile_image;
        $this->current_additional_documents = $maid->additional_documents ?? [];
        $this->current_id_scans = $maid->id_scans ?? [];
        
        // Set default deployment date
        $this->deployment_date = now()->format('Y-m-d');
        $this->contract_start_date = now()->format('Y-m-d');
    }

    public function updatedStatus($value)
    {
        // If status changed to 'deployed', show deployment modal
        if ($value === 'deployed' && $this->previousStatus !== 'deployed') {
            $this->showDeploymentModal = true;
        }
    }

    public function closeDeploymentModal()
    {
        // Reset status to previous value if modal is closed without saving
        $this->status = $this->previousStatus;
        $this->showDeploymentModal = false;
        $this->resetDeploymentFields();
    }

    public function saveDeployment()
    {
        $this->validate([
            'deployment_date' => 'required|date',
            'deployment_location' => 'required|string|max:255',
            'deployment_client_name' => 'required|string|max:255',
            'deployment_client_phone' => 'required|string|max:20',
            'deployment_address' => 'required|string',
            'monthly_salary' => 'nullable|numeric|min:0',
            'contract_type' => 'required|in:full-time,part-time,live-in,live-out',
            'contract_start_date' => 'required|date',
            'contract_end_date' => 'nullable|date|after:contract_start_date',
        ]);

        // Create deployment record
        Deployment::create([
            'maid_id' => $this->maid->id,
            'client_id' => $this->deployment_client_id,
            'deployment_date' => $this->deployment_date,
            'deployment_location' => $this->deployment_location,
            'client_name' => $this->deployment_client_name,
            'client_phone' => $this->deployment_client_phone,
            'deployment_address' => $this->deployment_address,
            'monthly_salary' => $this->monthly_salary,
            'contract_type' => $this->contract_type,
            'contract_start_date' => $this->contract_start_date,
            'contract_end_date' => $this->contract_end_date,
            'special_instructions' => $this->deployment_special_instructions,
            'notes' => $this->deployment_notes,
            'status' => 'active',
        ]);

        // Update previous status and close modal
        $this->previousStatus = 'deployed';
        $this->showDeploymentModal = false;
        
        // Now save the maid with the updated status
        $this->save();
    }

    protected function resetDeploymentFields()
    {
        $this->deployment_location = '';
        $this->deployment_client_id = null;
        $this->deployment_client_name = '';
        $this->deployment_client_phone = '';
        $this->deployment_address = '';
        $this->monthly_salary = null;
        $this->contract_type = 'full-time';
        $this->deployment_special_instructions = '';
        $this->deployment_notes = '';
    }

    public function save()
    {
        // If status is being changed to 'deployed' and modal hasn't been filled yet
        if ($this->status === 'deployed' && $this->previousStatus !== 'deployed' && $this->showDeploymentModal) {
            // Don't proceed with save, wait for deployment modal to be filled
            return;
        }

        // Convert empty strings to null for medical test results
        $this->hepatitis_b_result = $this->hepatitis_b_result === '' ? null : $this->hepatitis_b_result;
        $this->hiv_result = $this->hiv_result === '' ? null : $this->hiv_result;
        $this->urine_hcg_result = $this->urine_hcg_result === '' ? null : $this->urine_hcg_result;
        $this->hepatitis_b_date = $this->hepatitis_b_date === '' ? null : $this->hepatitis_b_date;
        $this->hiv_date = $this->hiv_date === '' ? null : $this->hiv_date;
        $this->urine_hcg_date = $this->urine_hcg_date === '' ? null : $this->urine_hcg_date;

        // Validate all fields
        $this->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20|unique:maids,phone,' . $this->maid->id,
            'mobile_number_2' => 'nullable|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'date_of_arrival' => 'required|date',
            'nationality' => 'required|string|max:50',
            'nin_number' => 'required|string|max:50|unique:maids,nin_number,' . $this->maid->id,
            'tribe' => 'required|string|max:100',
            'village' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'lc1_chairperson' => 'required|string',
            'mother_name_phone' => 'required|string|max:255',
            'father_name_phone' => 'required|string|max:255',
            'marital_status' => 'required|in:single,married',
            'number_of_children' => 'required|integer|min:0',
            'education_level' => 'required|in:P.7,S.4,S.6,Certificate,Diploma',
            'experience_years' => 'required|integer|min:0',
            'mother_tongue' => 'required|string|max:100',
            'english_proficiency' => 'required|integer|min:1|max:10',
            'previous_work' => 'nullable|string',
            'role' => 'required|in:housekeeper,house_manager,nanny,chef,elderly_caretaker,nakawere_caretaker',
            'status' => 'required|in:available,in-training,booked,deployed,absconded,terminated,on-leave',
            'secondary_status' => 'required|in:booked,available,deployed,on-leave,absconded,terminated',
            'work_status' => 'required|in:brokerage,long-term,part-time,full-time',
            'hepatitis_b_result' => 'nullable|in:positive,negative,pending,not_tested',
            'hepatitis_b_date' => 'nullable|date',
            'hiv_result' => 'nullable|in:positive,negative,pending,not_tested',
            'hiv_date' => 'nullable|date',
            'urine_hcg_result' => 'nullable|in:positive,negative,pending,not_tested',
            'urine_hcg_date' => 'nullable|date',
            'medical_notes' => 'nullable|string',
            'profile_image' => 'nullable|image|max:2048',
            'additional_documents.*' => 'nullable|file|max:5120',
            'id_scans.*' => 'nullable|file|max:5120',
            'additional_notes' => 'nullable|string',
        ]);

        // Prepare medical status
        $medicalStatus = [
            'hepatitis_b' => [
                'result' => !empty($this->hepatitis_b_result) ? $this->hepatitis_b_result : null,
                'date' => !empty($this->hepatitis_b_date) ? $this->hepatitis_b_date : null,
            ],
            'hiv' => [
                'result' => !empty($this->hiv_result) ? $this->hiv_result : null,
                'date' => !empty($this->hiv_date) ? $this->hiv_date : null,
            ],
            'urine_hcg' => [
                'result' => !empty($this->urine_hcg_result) ? $this->urine_hcg_result : null,
                'date' => !empty($this->urine_hcg_date) ? $this->urine_hcg_date : null,
            ],
            'notes' => !empty($this->medical_notes) ? $this->medical_notes : null,
        ];

        // Handle file uploads
        $profileImagePath = $this->current_profile_image;
        if ($this->profile_image) {
            // Delete old image if exists
            if ($this->current_profile_image) {
                Storage::disk('public')->delete($this->current_profile_image);
            }
            $profileImagePath = $this->profile_image->store('maids/profile-images', 'public');
        }

        $additionalDocuments = $this->current_additional_documents;
        if ($this->additional_documents) {
            foreach ($this->additional_documents as $document) {
                $additionalDocuments[] = $document->store('maids/documents', 'public');
            }
        }

        $idScans = $this->current_id_scans;
        if ($this->id_scans) {
            foreach ($this->id_scans as $scan) {
                $idScans[] = $scan->store('maids/id-scans', 'public');
            }
        }

        // Update the maid
        $this->maid->update([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'mobile_number_2' => $this->mobile_number_2,
            'date_of_birth' => $this->date_of_birth,
            'date_of_arrival' => $this->date_of_arrival,
            'nationality' => $this->nationality,
            'nin_number' => $this->nin_number,
            'tribe' => $this->tribe,
            'village' => $this->village,
            'district' => $this->district,
            'lc1_chairperson' => $this->lc1_chairperson,
            'mother_name_phone' => $this->mother_name_phone,
            'father_name_phone' => $this->father_name_phone,
            'marital_status' => $this->marital_status,
            'number_of_children' => $this->number_of_children,
            'education_level' => $this->education_level,
            'experience_years' => $this->experience_years,
            'mother_tongue' => $this->mother_tongue,
            'english_proficiency' => $this->english_proficiency,
            'previous_work' => $this->previous_work,
            'role' => $this->role,
            'status' => $this->status,
            'secondary_status' => $this->secondary_status,
            'work_status' => $this->work_status,
            'medical_status' => $medicalStatus,
            'profile_image' => $profileImagePath,
            'additional_documents' => $additionalDocuments,
            'id_scans' => $idScans,
            'additional_notes' => $this->additional_notes,
        ]);

        session()->flash('message', 'Maid updated successfully!');
        
        return redirect()->route((auth()->user()->role === 'trainer' ? 'trainer.' : '') . 'maids.index');
    }

    public function removeProfileImage()
    {
        if ($this->current_profile_image) {
            Storage::disk('public')->delete($this->current_profile_image);
            $this->current_profile_image = null;
            $this->maid->update(['profile_image' => null]);
            session()->flash('message', 'Profile image removed successfully!');
        }
    }

    public function removeDocument($index, $type)
    {
        if ($type === 'additional_documents') {
            if (isset($this->current_additional_documents[$index])) {
                Storage::disk('public')->delete($this->current_additional_documents[$index]);
                unset($this->current_additional_documents[$index]);
                $this->current_additional_documents = array_values($this->current_additional_documents);
            }
        } elseif ($type === 'id_scans') {
            if (isset($this->current_id_scans[$index])) {
                Storage::disk('public')->delete($this->current_id_scans[$index]);
                unset($this->current_id_scans[$index]);
                $this->current_id_scans = array_values($this->current_id_scans);
            }
        }
    }

    public function render()
    {
        $clients = Client::orderBy('contact_person')->get();
        
        return view('livewire.maids.edit', [
            'clients' => $clients,
        ]);
    }
}