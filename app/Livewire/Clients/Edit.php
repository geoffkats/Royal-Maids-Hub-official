<?php

namespace App\Livewire\Clients;

use App\Models\Client;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
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
    public string $address = '';
    public string $city = '';
    public string $district = '';
    public string $subscription_tier = 'basic';
    public string $subscription_status = 'pending';

    public function mount(Client $client): void
    {
        $this->client = $client;
        $this->authorize('update', $this->client);

        $this->name = $client->user->name;
        $this->email = $client->user->email;

        $this->current_profile_image = $client->profile_image;
        $this->company_name = $client->company_name;
        $this->contact_person = $client->contact_person;
        $this->phone = $client->phone;
        $this->secondary_phone = $client->secondary_phone;
        $this->address = $client->address;
        $this->city = $client->city;
        $this->district = $client->district;
        $this->subscription_tier = $client->subscription_tier;
        $this->subscription_status = $client->subscription_status;
    }

    protected function rules(): array
    {
        $userId = $this->client->user_id;
        return [
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255', Rule::unique('users','email')->ignore($userId)],
            'password' => ['nullable','confirmed','min:8'],

            'profile_image' => ['nullable','image','max:2048'],
            'contact_person' => ['required','string','max:255'],
            'phone' => ['required','string','max:50'],
            'secondary_phone' => ['nullable','string','max:50'],
            'address' => ['required','string'],
            'city' => ['required','string','max:100'],
            'district' => ['required','string','max:100'],
            'company_name' => ['nullable','string','max:255'],
            'subscription_tier' => ['required', Rule::in(['basic','premium','enterprise'])],
            'subscription_status' => ['required', Rule::in(['active','expired','pending','cancelled'])],
        ];
    }

    public function save(): void
    {
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
            'address' => $this->address,
            'city' => $this->city,
            'district' => $this->district,
            'subscription_tier' => $this->subscription_tier,
            'subscription_status' => $this->subscription_status,
        ]);

        session()->flash('success', __('Client updated successfully.'));
        $this->redirectRoute('clients.index', navigate: true);
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.clients.edit', [
            'title' => __('Edit Client'),
        ]);
    }
}
