<div class="space-y-6">
    <!-- Header Section -->
    <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
        <div class="flex items-center gap-3">
            <div class="rounded-full bg-[#F5B301] p-2">
                <flux:icon.user-plus class="size-6 text-[#3B0A45]" />
            </div>
            <div>
                <flux:heading size="xl" class="text-white">{{ __('New Client') }}</flux:heading>
                <flux:subheading class="text-[#D1C4E9]">{{ __('Create a new client account') }}</flux:subheading>
            </div>
        </div>
    </div>

    <form wire:submit.prevent="save" class="space-y-8">
        <div class="grid gap-6 lg:grid-cols-2">
            <div class="space-y-4 rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
                <div class="flex items-center gap-2 mb-4">
                    <flux:icon.user class="size-5 text-[#F5B301]" />
                    <flux:heading size="md" class="text-white">{{ __('Account Information') }}</flux:heading>
                </div>
                <div class="grid gap-4">
                    <flux:input wire:model.defer="name" :label="__('Name')" />
                    @error('name') <flux:text color="red" class="text-sm">{{ $message }}</flux:text> @enderror

                    <flux:input wire:model.defer="email" :label="__('Email')" type="email" />
                    @error('email') <flux:text color="red" class="text-sm">{{ $message }}</flux:text> @enderror

                    <flux:input wire:model.defer="password" :label="__('Password')" type="password" />
                    @error('password') <flux:text color="red" class="text-sm">{{ $message }}</flux:text> @enderror

                    <flux:input wire:model.defer="password_confirmation" :label="__('Confirm Password')" type="password" />
                </div>
            </div>

            <div class="space-y-4 rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
                <div class="flex items-center gap-2 mb-4">
                    <flux:icon.identification class="size-5 text-[#F5B301]" />
                    <flux:heading size="md" class="text-white">{{ __('Profile Information') }}</flux:heading>
                </div>
                <div class="grid gap-4">
                    <div>
                        <label for="profile_image" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                            {{ __('Profile Image') }}
                        </label>
                        @if($profile_image)
                            <div class="mb-3">
                                <img src="{{ $profile_image->temporaryUrl() }}" alt="Preview" class="h-24 w-24 object-cover rounded-lg shadow-sm border-2 border-neutral-200 dark:border-neutral-700">
                            </div>
                        @endif
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

        <div class="space-y-4 rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <div class="flex items-center gap-2 mb-4">
                <flux:icon.user-group class="size-5 text-[#F5B301]" />
                <flux:heading size="md" class="text-white">{{ __('Next of Kin') }}</flux:heading>
            </div>
            <div class="grid gap-4 md:grid-cols-3">
                <flux:input wire:model.defer="next_of_kin_name" :label="__('Next of Kin Name')" />
                @error('next_of_kin_name') <flux:text color="red" class="text-sm">{{ $message }}</flux:text> @enderror

                <flux:input wire:model.defer="next_of_kin_phone" :label="__('Next of Kin Phone')" />
                @error('next_of_kin_phone') <flux:text color="red" class="text-sm">{{ $message }}</flux:text> @enderror

                <flux:input wire:model.defer="next_of_kin_relationship" :label="__('Relationship')" placeholder="e.g., Spouse, Parent, Sibling" />
                @error('next_of_kin_relationship') <flux:text color="red" class="text-sm">{{ $message }}</flux:text> @enderror
            </div>
        </div>

        <div class="space-y-4 rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <div class="flex items-center gap-2 mb-4">
                <flux:icon.map-pin class="size-5 text-[#F5B301]" />
                <flux:heading size="md" class="text-white">{{ __('Address & Subscription') }}</flux:heading>
            </div>
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
                    <label class="block text-sm font-medium text-[#D1C4E9] mb-2">{{ __('Package') }}</label>
                    <select wire:model.defer="package_id" class="w-full rounded-lg border border-[#F5B301]/30 bg-[#3B0A45] text-white px-3 py-2 focus:border-[#F5B301] focus:ring-2 focus:ring-[#F5B301]/20">
                        <option value="" class="bg-[#3B0A45] text-white">{{ __('Select Package...') }}</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}" class="bg-[#3B0A45] text-white">
                                {{ $package->name }} - {{ $package->tier }} (UGX {{ number_format($package->base_price) }})
                            </option>
                        @endforeach
                    </select>
                    @error('package_id') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#D1C4E9] mb-2">{{ __('Status') }}</label>
                    <select wire:model.defer="subscription_status" class="w-full rounded-lg border border-[#F5B301]/30 bg-[#3B0A45] text-white px-3 py-2 focus:border-[#F5B301] focus:ring-2 focus:ring-[#F5B301]/20">
                        <option value="pending" class="bg-[#3B0A45] text-white">Pending</option>
                        <option value="active" class="bg-[#3B0A45] text-white">Active</option>
                        <option value="expired" class="bg-[#3B0A45] text-white">Expired</option>
                        <option value="cancelled" class="bg-[#3B0A45] text-white">Cancelled</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 text-sm text-[#D1C4E9]">
                    <flux:icon.information-circle class="size-4" />
                    <span>{{ __('Fill in all required fields to create a new client') }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <flux:button as="a" :href="route('clients.index')" variant="outline" icon="arrow-left">
                        {{ __('Cancel') }}
                    </flux:button>
                    <flux:button variant="primary" type="submit" icon="check">
                        {{ __('Create Client') }}
                    </flux:button>
                </div>
            </div>
        </div>
    </form>
</div>
