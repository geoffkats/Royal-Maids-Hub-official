<div class="space-y-6">
    <div class="flex items-center justify-between">
        <flux:heading size="xl">{{ __('Edit Client') }}</flux:heading>
    </div>

    <form wire:submit.prevent="save" class="space-y-8">
        <div class="grid gap-6 lg:grid-cols-2">
            <div class="space-y-4 rounded-lg border border-neutral-200 p-4 dark:border-neutral-700">
                <flux:heading size="md">{{ __('Account') }}</flux:heading>
                <div class="grid gap-4">
                    <flux:input wire:model.defer="name" :label="__('Name')" />
                    @error('name') <flux:text color="red" class="text-sm">{{ $message }}</flux:text> @enderror

                    <flux:input wire:model.defer="email" :label="__('Email')" type="email" />
                    @error('email') <flux:text color="red" class="text-sm">{{ $message }}</flux:text> @enderror

                    <flux:input wire:model.defer="password" :label="__('New Password (optional)')" type="password" />
                    @error('password') <flux:text color="red" class="text-sm">{{ $message }}</flux:text> @enderror

                    <flux:input wire:model.defer="password_confirmation" :label="__('Confirm Password')" type="password" />
                </div>
            </div>

            <div class="space-y-4 rounded-lg border border-neutral-200 p-4 dark:border-neutral-700">
                <flux:heading size="md">{{ __('Profile') }}</flux:heading>
                <div class="grid gap-4">
                    <div>
                        <label for="profile_image" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            {{ __('Profile Image') }}
                        </label>
                        <div class="mb-3 flex items-center gap-4">
                            @if($current_profile_image)
                                <div>
                                    <img src="{{ Storage::url($current_profile_image) }}" alt="Current profile" class="h-24 w-24 object-cover rounded-lg shadow-sm border-2 border-neutral-200 dark:border-neutral-700">
                                </div>
                            @endif
                            @if($profile_image)
                                <div>
                                    <img src="{{ $profile_image->temporaryUrl() }}" alt="New preview" class="h-24 w-24 object-cover rounded-lg shadow-sm border-2 border-green-200 dark:border-green-700">
                                    <p class="text-xs text-green-600 dark:text-green-400 mt-1">{{ __('New') }}</p>
                                </div>
                            @endif
                        </div>
                        <input type="file" wire:model="profile_image" id="profile_image" accept="image/*" 
                            class="block w-full text-sm text-neutral-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900/20 dark:file:text-indigo-400 dark:hover:file:bg-indigo-900/30" />
                        @error('profile_image') 
                            <flux:text color="red" class="text-sm mt-1">{{ $message }}</flux:text> 
                        @enderror
                        <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">{{ __('Max 2MB. JPG, PNG, GIF') }}</p>
                    </div>

                    <flux:input wire:model.defer="contact_person" :label="__('Contact Person')" />
                    @error('contact_person') <flux:text color="red" class="text-sm">{{ $message }}</flux:text> @enderror

                    <flux:input wire:model.defer="company_name" :label="__('Company Name')" />

                    <flux:input wire:model.defer="phone" :label="__('Phone')" />
                    @error('phone') <flux:text color="red" class="text-sm">{{ $message }}</flux:text> @enderror

                    <flux:input wire:model.defer="secondary_phone" :label="__('Secondary Phone')" />
                </div>
            </div>
        </div>

        <div class="space-y-4 rounded-lg border border-neutral-200 p-4 dark:border-neutral-700">
            <flux:heading size="md">{{ __('Next of Kin') }}</flux:heading>
            <div class="grid gap-4 md:grid-cols-3">
                <flux:input wire:model.defer="next_of_kin_name" :label="__('Next of Kin Name')" />
                @error('next_of_kin_name') <flux:text color="red" class="text-sm">{{ $message }}</flux:text> @enderror

                <flux:input wire:model.defer="next_of_kin_phone" :label="__('Next of Kin Phone')" />
                @error('next_of_kin_phone') <flux:text color="red" class="text-sm">{{ $message }}</flux:text> @enderror

                <flux:input wire:model.defer="next_of_kin_relationship" :label="__('Relationship')" placeholder="e.g., Spouse, Parent, Sibling" />
                @error('next_of_kin_relationship') <flux:text color="red" class="text-sm">{{ $message }}</flux:text> @enderror
            </div>
        </div>

        <div class="space-y-4 rounded-lg border border-neutral-200 p-4 dark:border-neutral-700">
            <flux:heading size="md">{{ __('Address & Subscription') }}</flux:heading>
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <div class="md:col-span-2">
                    <flux:input wire:model.defer="address" :label="__('Address')" />
                    @error('address') <flux:text color="red" class="text-sm">{{ $message }}</flux:text> @enderror
                </div>

                <flux:input wire:model.defer="city" :label="__('City')" />
                @error('city') <flux:text color="red" class="text-sm">{{ $message }}</flux:text> @enderror

                <flux:input wire:model.defer="district" :label="__('District')" />
                @error('district') <flux:text color="red" class="text-sm">{{ $message }}</flux:text> @enderror

                <div>
                    <label class="mb-1 block text-sm text-neutral-600 dark:text-neutral-400">{{ __('Package') }}</label>
                    <select wire:model.defer="package_id" class="w-full rounded-md border-neutral-300 bg-white text-neutral-900 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white">
                        <option value="">{{ __('Select Package...') }}</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}">
                                {{ $package->name }} - {{ $package->tier }} (UGX {{ number_format($package->base_price) }})
                            </option>
                        @endforeach
                    </select>
                    @error('package_id') <flux:text color="red" class="text-sm">{{ $message }}</flux:text> @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm text-neutral-600 dark:text-neutral-400">{{ __('Status') }}</label>
                    <select wire:model.defer="subscription_status" class="w-full rounded-md border-neutral-300 bg-white text-neutral-900 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white">
                        <option value="pending">Pending</option>
                        <option value="active">Active</option>
                        <option value="expired">Expired</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <flux:button as="a" :href="route('clients.index')" variant="outline">{{ __('Cancel') }}</flux:button>
            <flux:button variant="primary" type="submit">{{ __('Save Changes') }}</flux:button>
        </div>
    </form>
</div>
