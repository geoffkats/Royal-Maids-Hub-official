<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;
    
    public string $name = '';
    public string $email = '';
    public $profile_picture;
    public $current_profile_picture;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
        $this->current_profile_picture = Auth::user()->profile_picture;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id)
            ],
            'profile_picture' => ['nullable', 'image', 'max:2048'],
        ]);

        // Update name and email
        $user->name = $this->name;
        $user->email = $this->email;

        // Handle profile picture upload
        if ($this->profile_picture) {
            // Delete old profile picture if exists
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            // Store new profile picture
            $path = $this->profile_picture->store('profile-pictures', 'public');
            $user->profile_picture = $path;
            $this->current_profile_picture = $path;
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Reset file input
        $this->reset('profile_picture');

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Profile')" :subheading="__('Update your profile information')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <!-- Profile Picture -->
            <div class="space-y-4">
                <flux:label>{{ __('Profile Picture') }}</flux:label>
                
                <div class="flex items-center gap-6">
                    <!-- Current Profile Picture Preview -->
                    <div class="flex-shrink-0">
                        @if($profile_picture)
                            <img src="{{ $profile_picture->temporaryUrl() }}" 
                                 alt="Preview" 
                                 class="w-24 h-24 rounded-full object-cover border-2 border-gray-300">
                        @elseif($current_profile_picture)
                            <img src="{{ Storage::url($current_profile_picture) }}" 
                                 alt="{{ auth()->user()->name }}" 
                                 class="w-24 h-24 rounded-full object-cover border-2 border-gray-300">
                        @else
                            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white text-2xl font-bold border-2 border-gray-300">
                                {{ auth()->user()->initials() }}
                            </div>
                        @endif
                    </div>
                    
                    <!-- Upload Button -->
                    <div class="flex-1">
                        <input type="file" 
                               wire:model="profile_picture" 
                               id="profile_picture" 
                               class="hidden" 
                               accept="image/*">
                        <flux:button type="button" 
                                    variant="outline" 
                                    onclick="document.getElementById('profile_picture').click()">
                            {{ __('Choose Photo') }}
                        </flux:button>
                        <p class="mt-2 text-sm text-gray-500">
                            {{ __('JPG, PNG or GIF. Max size 2MB.') }}
                        </p>
                        @error('profile_picture')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Loading indicator -->
                <div wire:loading wire:target="profile_picture" class="text-sm text-gray-500">
                    {{ __('Uploading...') }}
                </div>
            </div>

            <flux:input wire:model="name" :label="__('Name')" type="text" required autofocus autocomplete="name" />

            <div>
                <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="email" />

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail &&! auth()->user()->hasVerifiedEmail())
                    <div>
                        <flux:text class="mt-4">
                            {{ __('Your email address is unverified.') }}

                            <flux:link class="text-sm cursor-pointer" wire:click.prevent="resendVerificationNotification">
                                {{ __('Click here to re-send the verification email.') }}
                            </flux:link>
                        </flux:text>

                        @if (session('status') === 'verification-link-sent')
                            <flux:text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </flux:text>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full" data-test="update-profile-button">
                        {{ __('Save') }}
                    </flux:button>
                </div>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>

        <livewire:settings.delete-user-form />
    </x-settings.layout>
</section>
