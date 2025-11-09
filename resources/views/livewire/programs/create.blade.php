<div class="min-h-screen bg-[#3B0A45] dark:bg-[#3B0A45] p-6">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-[#3B0A45] to-[#512B58] border border-[#F5B301]/30 rounded-xl p-6 shadow-lg mb-6">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-[#F5B301] to-[#FF9800] rounded-lg flex items-center justify-center">
                        <flux:icon.academic-cap class="w-6 h-6 text-white" />
                    </div>
                    <div>
                        <flux:heading size="xl" class="text-white">{{ __('New Training Program') }}</flux:heading>
                        <flux:subheading class="mt-1 text-[#D1C4E9]">{{ __('Create a new training program assignment') }}</flux:subheading>
                    </div>
                </div>

                <flux:button as="a" :href="route('programs.index')" variant="ghost" icon="arrow-left" class="text-[#D1C4E9] hover:text-white hover:bg-[#F5B301]/20">
                    {{ __('Back') }}
                </flux:button>
            </div>
        </div>

        <form wire:submit.prevent="save" class="space-y-6">
            <div class="bg-[#512B58] border border-[#F5B301]/30 rounded-xl p-6 shadow-lg">
                <flux:heading size="lg" class="mb-4 text-white">{{ __('Program Details') }}</flux:heading>
            
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

                <flux:input wire:model.defer="program_type" :label="__('Program Type')" placeholder="e.g., Housekeeping, Childcare" required />
                
                <flux:select wire:model.defer="status" :label="__('Status')" required>
                    <option value="scheduled">{{ __('Scheduled') }}</option>
                    <option value="in-progress">{{ __('In Progress') }}</option>
                    <option value="completed">{{ __('Completed') }}</option>
                    <option value="cancelled">{{ __('Cancelled') }}</option>
                </flux:select>

                <flux:input type="date" wire:model.defer="start_date" :label="__('Start Date')" required />
                <flux:input type="date" wire:model.defer="end_date" :label="__('End Date')" />

                <flux:input type="number" min="1" wire:model.defer="hours_required" :label="__('Hours Required')" required />
            </div>
            
            <div class="mt-4">
                <flux:textarea wire:model.defer="notes" :label="__('Notes')" rows="3" />
            </div>
        </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-[#F5B301]/20">
                <flux:button as="a" :href="route('programs.index')" variant="ghost" class="text-[#D1C4E9] hover:text-white hover:bg-[#F5B301]/20">
                    {{ __('Cancel') }}
                </flux:button>
                <flux:button type="submit" variant="primary" icon="check" class="bg-[#F5B301] hover:bg-[#F5B301]/80 text-[#3B0A45] font-medium">
                    {{ __('Create Program') }}
                </flux:button>
            </div>
        </form>
</div>
