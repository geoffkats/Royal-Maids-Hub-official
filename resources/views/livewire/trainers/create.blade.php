<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <flux:heading size="xl">{{ __('New Trainer') }}</flux:heading>
            <flux:subheading class="mt-1">{{ __('Add a new trainer to the system') }}</flux:subheading>
        </div>

        <flux:button as="a" :href="route('trainers.index')" variant="ghost" icon="arrow-left">
            {{ __('Back') }}
        </flux:button>
    </div>

    <flux:separator variant="subtle" />

    <form wire:submit.prevent="save" class="space-y-6">
        <div class="rounded-lg border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
            <flux:heading size="lg" class="mb-4">{{ __('Account Information') }}</flux:heading>
            
            <div class="grid gap-4 md:grid-cols-2">
                <flux:input wire:model.defer="name" :label="__('Full Name')" required />
                <flux:input type="email" wire:model.defer="email" :label="__('Email')" required />
                <flux:input type="password" wire:model.defer="password" :label="__('Password')" required />
                <flux:input type="password" wire:model.defer="password_confirmation" :label="__('Confirm Password')" required />
            </div>

            <div class="mt-6">
                <flux:heading size="sm" class="mb-2">{{ __('Profile Photo (optional)') }}</flux:heading>
                <div class="flex items-center gap-4">
                    <div class="h-16 w-16 overflow-hidden rounded-full bg-neutral-100 dark:bg-neutral-700 flex items-center justify-center">
                        @if ($photo)
                            <img src="{{ $photo->temporaryUrl() }}" class="h-16 w-16 object-cover" alt="Preview">
                        @else
                            <svg class="h-8 w-8 text-neutral-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/>
                            </svg>
                        @endif
                    </div>
                    <div>
                        <input type="file" wire:model="photo" accept="image/*" class="block w-full text-sm text-neutral-700 file:mr-4 file:rounded-md file:border-0 file:bg-neutral-100 file:px-4 file:py-2 file:text-sm file:font-medium hover:file:bg-neutral-200 dark:text-neutral-300 dark:file:bg-neutral-700 dark:hover:file:bg-neutral-600" />
                        @error('photo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-neutral-500">{{ __('PNG, JPG up to 2MB') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
            <flux:heading size="lg" class="mb-4">{{ __('Professional Details') }}</flux:heading>
            
            <div class="grid gap-4 md:grid-cols-2">
                <flux:input wire:model.defer="specialization" :label="__('Specialization')" />
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
            <flux:button as="a" :href="route('trainers.index')" variant="ghost">
                {{ __('Cancel') }}
            </flux:button>
            <flux:button type="submit" variant="primary" icon="check">
                {{ __('Create Trainer') }}
            </flux:button>
        </div>
    </form>
</div>
