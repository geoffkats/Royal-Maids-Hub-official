<?php

namespace App\Livewire\Clients;

use App\Models\Client;
use App\Models\Package;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Edit extends Component
{
    use AuthorizesRequests, WithFileUploads;

    public Client $client;

    public string $name = '';
    public string $email = '';
    public ?string $password = null;
    public ?string $password_confirmation = null;

    public $profile_image;
    public ?string $current_profile_image = null;
    public ?string $company_name = null;
    public string $contact_person = '';
    public string $phone = '';
    public ?string $secondary_phone = null;
    public ?string $next_of_kin_name = null;
    public ?string $next_of_kin_phone = null;
    public ?string $next_of_kin_relationship = null;
    public ?string $identity_type = null;
    public ?string $identity_number = null;
    public string $address = '';
    public string $city = '';
    public string $district = '';
    public ?int $package_id = null;
    public string $subscription_tier = 'basic';
    public string $subscription_status = 'pending';

    public function mount(Client $client): void
    {
        $this->client = $client;
        $this->authorize('update', $this->client);

        $canViewIdentity = Gate::allows('viewSensitiveIdentity', $this->client);

        $this->name = $client->user->name;
        $this->email = $client->user->email;

        $this->current_profile_image = $client->profile_image;
        $this->company_name = $client->company_name;
        $this->contact_person = $client->contact_person;
        $this->phone = $client->phone;
        $this->secondary_phone = $client->secondary_phone;
        $this->next_of_kin_name = $client->next_of_kin_name;
        $this->next_of_kin_phone = $client->next_of_kin_phone;
        $this->next_of_kin_relationship = $client->next_of_kin_relationship;
        $this->identity_type = $canViewIdentity ? $client->identity_type : null;
        $this->identity_number = $canViewIdentity ? $client->identity_number : null;
        $this->address = $client->address;
        $this->city = $client->city;
        $this->district = $client->district;
        $this->package_id = $client->package_id;
        $this->subscription_tier = $client->subscription_tier;
        $this->subscription_status = $client->subscription_status;
    }

    protected function rules(): array
    {
        $userId = $this->client->user_id;
        $canUpdateIdentity = Gate::allows('updateSensitiveIdentity', $this->client);

        return [
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255', Rule::unique('users','email')->ignore($userId)],
            'password' => ['nullable','confirmed','min:8'],

            'profile_image' => ['nullable','image','max:2048'],
            'contact_person' => ['required','string','max:255'],
            'phone' => ['required','string','max:50'],
            'secondary_phone' => ['nullable','string','max:50'],
            'next_of_kin_name' => ['nullable','string','max:255'],
            'next_of_kin_phone' => ['nullable','string','max:50'],
            'next_of_kin_relationship' => ['nullable','string','max:100'],
            'identity_type' => $canUpdateIdentity
                ? ['nullable', 'required_with:identity_number', Rule::in(['nin', 'passport'])]
                : ['nullable'],
            'identity_number' => $canUpdateIdentity
                ? [
                    'nullable',
                    'string',
                    'max:50',
                    Rule::unique('clients', 'identity_number')
                        ->ignore($this->client->id)
                        ->where(function ($query) {
                            return $query->where('identity_type', $this->identity_type);
                        }),
                ]
                : ['nullable'],
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
        $canUpdateIdentity = Gate::allows('updateSensitiveIdentity', $this->client);

        if (!$canUpdateIdentity) {
            $this->identity_type = $this->client->identity_type;
            $this->identity_number = $this->client->identity_number;
        }

        $this->validate();

        $user = $this->client->user;
        $user->name = $this->name;
        $user->email = $this->email;
        if (!empty($this->password)) {
            $user->password = Hash::make($this->password);
        }
        $user->save();

        // Handle profile image upload
        $profileImagePath = $this->current_profile_image;
        if ($this->profile_image) {
            // Delete old image if exists
            if ($this->current_profile_image) {
                Storage::disk('public')->delete($this->current_profile_image);
            }
            $profileImagePath = $this->profile_image->store('clients/profile-images', 'public');
        }

        $this->client->update([
            'profile_image' => $profileImagePath,
            'company_name' => $this->company_name,
            'contact_person' => $this->contact_person,
            'phone' => $this->phone,
            'secondary_phone' => $this->secondary_phone,
            'next_of_kin_name' => $this->next_of_kin_name,
            'next_of_kin_phone' => $this->next_of_kin_phone,
            'next_of_kin_relationship' => $this->next_of_kin_relationship,
            'identity_type' => $canUpdateIdentity ? $this->identity_type : $this->client->identity_type,
            'identity_number' => $canUpdateIdentity ? $this->identity_number : $this->client->identity_number,
            'address' => $this->address,
            'city' => $this->city,
            'district' => $this->district,
            'package_id' => $this->package_id,
            'subscription_tier' => $this->subscription_tier,
            'subscription_status' => $this->subscription_status,
            'updated_by' => auth()->id(),
        ]);

        session()->flash('success', __('Client updated successfully.'));
        $prefix = auth()->user()->role === 'trainer' ? 'trainer.' : '';
        $this->redirectRoute($prefix . 'clients.index', navigate: true);
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.clients.edit', [
            'title' => __('Edit Client'),
            'packages' => Package::where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }
}
