<?php

namespace App\Livewire\Clients;

use App\Models\Client;
use App\Models\Package;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Create extends Component
{
    use AuthorizesRequests, WithFileUploads;

    // Validation modal state
    public bool $showValidationModal = false;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public $profile_image;
    public ?string $company_name = null;
    public string $contact_person = '';
    public string $phone = '';
    public ?string $secondary_phone = null;
    public ?string $next_of_kin_name = null;
    public ?string $next_of_kin_phone = null;
    public ?string $next_of_kin_relationship = null;
    public string $address = '';
    public string $city = '';
    public string $district = '';
    public ?int $package_id = null;
    public string $subscription_tier = 'basic';
    public string $subscription_status = 'pending';

    public function mount(): void
    {
        $this->authorize('create', Client::class);
    }

    protected function rules(): array
    {
        return [
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255', Rule::unique('users','email')],
            'password' => ['required','confirmed','min:8'],

            'profile_image' => ['nullable','image','max:2048'],
            'contact_person' => ['required','string','max:255'],
            'phone' => ['required','string','max:50'],
            'secondary_phone' => ['nullable','string','max:50'],
            'next_of_kin_name' => ['nullable','string','max:255'],
            'next_of_kin_phone' => ['nullable','string','max:50'],
            'next_of_kin_relationship' => ['nullable','string','max:100'],
            'address' => ['required','string'],
            'city' => ['required','string','max:100'],
            'district' => ['required','string','max:100'],
            'company_name' => ['nullable','string','max:255'],
            'package_id' => ['nullable', 'exists:packages,id'],
            'subscription_tier' => ['required', Rule::in(['basic','premium','enterprise'])],
            'subscription_status' => ['required', Rule::in(['active','expired','pending','cancelled'])],
        ];
    }

    public function save(): void
    {
        try {
            $this->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->showValidationModal = true;
            throw $e;
        }

        // Handle profile image upload
        $profileImagePath = null;
        if ($this->profile_image) {
            $profileImagePath = $this->profile_image->store('clients/profile-images', 'public');
        }

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => User::ROLE_CLIENT,
            'email_verified_at' => now(),
        ]);

        Client::create([
            'user_id' => $user->id,
            'profile_image' => $profileImagePath,
            'company_name' => $this->company_name,
            'contact_person' => $this->contact_person,
            'phone' => $this->phone,
            'secondary_phone' => $this->secondary_phone,
            'next_of_kin_name' => $this->next_of_kin_name,
            'next_of_kin_phone' => $this->next_of_kin_phone,
            'next_of_kin_relationship' => $this->next_of_kin_relationship,
            'address' => $this->address,
            'city' => $this->city,
            'district' => $this->district,
            'package_id' => $this->package_id,
            'subscription_tier' => $this->subscription_tier,
            'subscription_status' => $this->subscription_status,
        ]);

        session()->flash('success', __('Client created successfully.'));
        $prefix = auth()->user()->role === 'trainer' ? 'trainer.' : '';
        $this->redirectRoute($prefix . 'clients.index', navigate: true);
    }

    /**
     * Close validation modal
     */
    public function closeValidationModal(): void
    {
        $this->showValidationModal = false;
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.clients.create', [
            'title' => __('New Client'),
            'packages' => Package::where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }
}
