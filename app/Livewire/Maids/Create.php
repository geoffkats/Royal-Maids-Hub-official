<?php

namespace App\Livewire\Maids;

use App\Models\Maid;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Create extends Component
{
    use WithFileUploads;

    // Validation modal state
    public bool $showValidationModal = false;

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
        'first_name' => 'required|string|max:100',
        'last_name' => 'required|string|max:100',
        'phone' => 'required|string|max:20|unique:maids,phone',
        'mobile_number_2' => 'nullable|string|max:20',
        'date_of_birth' => 'required|date|before:today',
        'date_of_arrival' => 'required|date',
        'nationality' => 'required|string|max:50',
        'nin_number' => 'required|string|max:50|unique:maids,nin_number',
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
    ];

    public function mount()
    {
        // Set default values
        $this->date_of_arrival = now()->format('Y-m-d');
    }

    public function save()
    {
        // Convert empty strings to null for medical test results
        $this->hepatitis_b_result = $this->hepatitis_b_result === '' ? null : $this->hepatitis_b_result;
        $this->hiv_result = $this->hiv_result === '' ? null : $this->hiv_result;
        $this->urine_hcg_result = $this->urine_hcg_result === '' ? null : $this->urine_hcg_result;
        $this->hepatitis_b_date = $this->hepatitis_b_date === '' ? null : $this->hepatitis_b_date;
        $this->hiv_date = $this->hiv_date === '' ? null : $this->hiv_date;
        $this->urine_hcg_date = $this->urine_hcg_date === '' ? null : $this->urine_hcg_date;
        
        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->showValidationModal = true;
            throw $e;
        }

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
        $profileImagePath = null;
        if ($this->profile_image) {
            $profileImagePath = $this->profile_image->store('maids/profile-images', 'public');
        }

        $additionalDocuments = [];
        if ($this->additional_documents) {
            foreach ($this->additional_documents as $document) {
                $additionalDocuments[] = $document->store('maids/documents', 'public');
            }
        }

        $idScans = [];
        if ($this->id_scans) {
            foreach ($this->id_scans as $scan) {
                $idScans[] = $scan->store('maids/id-scans', 'public');
            }
        }

        // Create the maid
        Maid::create([
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

        session()->flash('message', 'Maid created successfully!');
        
        return redirect()->route((auth()->user()->role === 'trainer' ? 'trainer.' : '') . 'maids.index');
    }

    /**
     * Close validation modal
     */
    public function closeValidationModal(): void
    {
        $this->showValidationModal = false;
    }

    public function render()
    {
        return view('livewire.maids.create');
    }
}