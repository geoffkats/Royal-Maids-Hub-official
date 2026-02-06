<div class="space-y-6">
    @php
        $prefix = auth()->user()->role === 'trainer' ? 'trainer.' : '';
    @endphp
    <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div class="flex items-center gap-3">
                <div class="rounded-full bg-[#F5B301] p-2">
                    <flux:icon.user class="size-6 text-[#3B0A45]" />
                </div>
                <div>
                    <flux:heading size="xl" class="text-white">{{ __('Edit Trainer') }}</flux:heading>
                    <flux:subheading class="mt-1 text-[#D1C4E9]">{{ $trainer->user?->name }}</flux:subheading>
                </div>
            </div>

            <flux:button as="a" :href="route($prefix . 'trainers.index')" variant="ghost" icon="arrow-left" class="text-white">
                {{ __('Back') }}
            </flux:button>
        </div>
    </div>

    <form wire:submit.prevent="update" class="space-y-6">
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <flux:heading size="lg" class="mb-4 text-white">{{ __('Account Information') }}</flux:heading>
            
            <div class="grid gap-4 md:grid-cols-2">
                <flux:input value="{{ $trainer->user?->name }}" :label="__('Full Name')" disabled />
                <flux:input value="{{ $trainer->user?->email }}" :label="__('Email')" disabled />
            </div>

            <div class="mt-6">
                <flux:heading size="sm" class="mb-2">{{ __('Profile Photo') }}</flux:heading>
                <div class="flex items-center gap-4">
                    <div class="h-16 w-16 overflow-hidden rounded-full bg-[#3B0A45]">
                        @if ($photo)
                            <img src="{{ $photo->temporaryUrl() }}" class="h-16 w-16 object-cover" alt="Preview">
                        @else
                            <img src="{{ $trainer->photo_url }}" class="h-16 w-16 object-cover" alt="Profile">
                        @endif
                    </div>
                    <div>
                        <input type="file" wire:model="photo" accept="image/*" class="block w-full text-sm text-[#D1C4E9] file:mr-4 file:rounded-md file:border-0 file:bg-[#F5B301]/20 file:px-4 file:py-2 file:text-sm file:font-medium file:text-[#F5B301] hover:file:bg-[#F5B301]/30" />
                        @error('photo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-[#D1C4E9]">{{ __('PNG, JPG up to 2MB') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <flux:heading size="lg" class="mb-4 text-white">{{ __('Professional Details') }}</flux:heading>
            
            <div class="grid gap-4 md:grid-cols-2">
                <flux:select wire:model.defer="specialization" :label="__('Specialization')" placeholder="{{ __('Select your training specialty') }}">
                    <option value="">{{ __('Select Specialization') }}</option>
                    <option value="Housekeeping">{{ __('Housekeeping') }}</option>
                    <option value="Childcare">{{ __('Childcare') }}</option>
                    <option value="Elderly Care">{{ __('Elderly Care') }}</option>
                    <option value="Cooking">{{ __('Cooking') }}</option>
                    <option value="Language Training">{{ __('Language Training') }}</option>
                    <option value="Professional Development">{{ __('Professional Development') }}</option>
                    <option value="General Training">{{ __('General Training') }}</option>
                </flux:select>
                <flux:input type="number" min="0" wire:model.defer="experience_years" :label="__('Experience (years)')" />
            </div>
            
            <div class="mt-4">
                <flux:textarea wire:model.defer="bio" :label="__('Bio')" rows="4" />
            </div>
            
            <div class="mt-4">
                <flux:select wire:model.defer="status" :label="__('Status')">
                    <option value="active">{{ __('Active') }}</option>
                    <option value="inactive">{{ __('Inactive') }}</option>
                </flux:select>
            </div>
        </div>

        <div class="flex justify-end gap-2">
            <flux:button as="a" :href="route($prefix . 'trainers.index')" variant="ghost" class="text-[#D1C4E9]">
                {{ __('Cancel') }}
            </flux:button>
            <flux:button type="submit" variant="primary" icon="check" class="bg-[#F5B301] text-[#3B0A45] hover:bg-[#F5B301]/90">
                {{ __('Update Trainer') }}
            </flux:button>
        </div>
    </form>
</div>
