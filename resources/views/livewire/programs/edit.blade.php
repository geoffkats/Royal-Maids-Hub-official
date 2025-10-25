<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <flux:heading size="xl">{{ __('Edit Training Program') }}</flux:heading>
            <flux:subheading class="mt-1">{{ $program->program_type }}</flux:subheading>
        </div>

        <div class="flex gap-2">
            <flux:button as="a" :href="route('programs.index')" variant="ghost" icon="arrow-left">
                {{ __('Back') }}
            </flux:button>
        </div>
    </div>

    <flux:separator variant="subtle" />

    <form wire:submit.prevent="update" class="space-y-6">
        <div class="rounded-lg border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
            <flux:heading size="lg" class="mb-4">{{ __('Program Details') }}</flux:heading>
            
            <div class="grid gap-4 md:grid-cols-2">
                <flux:select wire:model.defer="trainer_id" :label="__('Trainer')" required>
                    <option value="">{{ __('Select Trainer') }}</option>
                    @foreach ($trainers as $trainer)
                        <option value="{{ $trainer->id }}">{{ $trainer->user?->name }} - {{ $trainer->specialization }}</option>
                    @endforeach
                </flux:select>

                <flux:select wire:model.defer="maid_id" :label="__('Maid')" required>
                    <option value="">{{ __('Select Maid') }}</option>
                    @foreach ($maids as $maid)
                        <option value="{{ $maid->id }}">{{ $maid->first_name }} {{ $maid->last_name }}</option>
                    @endforeach
                </flux:select>

                <flux:input wire:model.defer="program_type" :label="__('Program Type')" required />
                
                <flux:select wire:model.defer="status" :label="__('Status')" required>
                    <option value="scheduled">{{ __('Scheduled') }}</option>
                    <option value="in-progress">{{ __('In Progress') }}</option>
                    <option value="completed">{{ __('Completed') }}</option>
                    <option value="cancelled">{{ __('Cancelled') }}</option>
                </flux:select>

                <flux:input type="date" wire:model.defer="start_date" :label="__('Start Date')" required />
                <flux:input type="date" wire:model.defer="end_date" :label="__('End Date')" />

                <flux:input type="number" min="0" :max="$hours_required" wire:model.defer="hours_completed" :label="__('Hours Completed')" required />
                <flux:input type="number" min="1" wire:model.defer="hours_required" :label="__('Hours Required')" required />
            </div>
            
            <div class="mt-4">
                <flux:textarea wire:model.defer="notes" :label="__('Notes')" rows="3" />
            </div>
        </div>

        <div class="flex justify-end gap-2">
            <flux:button as="a" :href="route('programs.index')" variant="ghost">
                {{ __('Cancel') }}
            </flux:button>
            <flux:button type="submit" variant="primary" icon="check">
                {{ __('Update Program') }}
            </flux:button>
        </div>
    </form>
</div>
