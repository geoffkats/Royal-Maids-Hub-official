<div>
    <!-- Header Section -->
    <div class="bg-[#512B58] border border-[#F5B301]/30 rounded-xl p-6 text-white mb-6">
        <div class="flex items-center gap-3">
            <x-flux::icon.plus class="w-8 h-8" />
            <div>
                <h1 class="text-3xl font-bold">{{ __('Create Opportunity') }}</h1>
                <p class="text-[#D1C4E9] mt-1">{{ __('Add a new sales opportunity') }}</p>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('message'))
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-200 px-6 py-4 rounded-xl shadow-sm flex items-center gap-3 mb-6">
            <x-flux::icon.check-circle class="w-5 h-5 text-green-600 dark:text-green-400" />
            <span class="font-medium">{{ session('message') }}</span>
        </div>
    @endif

    <!-- Form -->
    <form wire:submit.prevent="save" class="space-y-8">
        <!-- Opportunity Information -->
        <div class="bg-gradient-to-r from-[#512B58] to-[#3B0A45] border border-[#F5B301]/30 rounded-xl p-6 shadow-lg">
            <h2 class="text-xl font-semibold text-[#D1C4E9] mb-6 flex items-center gap-2">
                <x-flux::icon.chart-bar class="w-5 h-5" />
                {{ __('Opportunity Information') }}
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <flux:input 
                        wire:model="name" 
                        label="{{ __('Opportunity Name') }}"
                        placeholder="{{ __('Enter opportunity name') }}"
                        class="w-full"
                    />
                    <flux:error name="name" />
                </div>

                <div>
                    <flux:input 
                        wire:model="amount" 
                        type="number"
                        step="0.01"
                        label="{{ __('Amount') }}"
                        placeholder="{{ __('Enter amount') }}"
                        class="w-full"
                    />
                    <flux:error name="amount" />
                </div>

                <div>
                    <flux:input 
                        wire:model="probability" 
                        type="number"
                        min="0"
                        max="100"
                        label="{{ __('Probability (%)') }}"
                        placeholder="{{ __('Enter probability') }}"
                        class="w-full"
                    />
                    <flux:error name="probability" />
                </div>

                <div>
                    <flux:input 
                        wire:model="close_date" 
                        type="date"
                        label="{{ __('Close Date') }}"
                        class="w-full"
                    />
                    <flux:error name="close_date" />
                </div>

                <div class="md:col-span-2">
                    <flux:textarea 
                        wire:model="description" 
                        label="{{ __('Description') }}"
                        placeholder="{{ __('Enter opportunity description') }}"
                        rows="3"
                        class="w-full"
                    />
                    <flux:error name="description" />
                </div>
            </div>
        </div>

        <!-- Related Information -->
        <div class="bg-gradient-to-r from-[#512B58] to-[#3B0A45] border border-[#F5B301]/30 rounded-xl p-6 shadow-lg">
            <h2 class="text-xl font-semibold text-[#D1C4E9] mb-6 flex items-center gap-2">
                <x-flux::icon.link class="w-5 h-5" />
                {{ __('Related Information') }}
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <flux:select 
                        wire:model="lead_id" 
                        label="{{ __('Related Lead') }}"
                        class="w-full"
                    >
                        <option value="">{{ __('Select Lead') }}</option>
                        @foreach($leads as $lead)
                            <option value="{{ $lead->id }}">{{ $lead->first_name }} {{ $lead->last_name }} ({{ $lead->email }})</option>
                        @endforeach
                    </flux:select>
                    <flux:error name="lead_id" />
                </div>

                <div>
                    <flux:select 
                        wire:model="stage_id" 
                        label="{{ __('Stage') }}"
                        class="w-full"
                    >
                        <option value="">{{ __('Select Stage') }}</option>
                        @foreach($stages as $stage)
                            <option value="{{ $stage->id }}">{{ $stage->name }} ({{ $stage->pipeline->name }})</option>
                        @endforeach
                    </flux:select>
                    <flux:error name="stage_id" />
                </div>

                <div>
                    <flux:select 
                        wire:model="assigned_to" 
                        label="{{ __('Assigned To') }}"
                        class="w-full"
                    >
                        <option value="">{{ __('Select User') }}</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </flux:select>
                    <flux:error name="assigned_to" />
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('crm.opportunities.index') }}" 
               class="px-6 py-3 bg-neutral-500 hover:bg-neutral-600 text-white rounded-lg font-semibold transition-all duration-200 shadow-lg hover:shadow-xl">
                {{ __('Cancel') }}
            </a>
            <flux:button type="submit" variant="primary" class="px-6 py-3">
                {{ __('Create Opportunity') }}
            </flux:button>
        </div>
    </form>
</div>