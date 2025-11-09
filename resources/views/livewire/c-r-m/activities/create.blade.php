<div>
    <!-- Header Section -->
    <div class="bg-[#512B58] border border-[#F5B301]/30 rounded-xl p-6 text-white mb-6">
        <div class="flex items-center gap-3">
            <x-flux::icon.plus class="w-8 h-8" />
            <div>
                <h1 class="text-3xl font-bold">{{ __('Create Activity') }}</h1>
                <p class="text-[#D1C4E9] mt-1">{{ __('Add a new CRM activity') }}</p>
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
        <!-- Activity Information -->
        <div class="bg-gradient-to-r from-[#512B58] to-[#3B0A45] border border-[#F5B301]/30 rounded-xl p-6 shadow-lg">
            <h2 class="text-xl font-semibold text-[#D1C4E9] mb-6 flex items-center gap-2">
                <x-flux::icon.clipboard-document-list class="w-5 h-5" />
                {{ __('Activity Information') }}
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <flux:select 
                        wire:model="type" 
                        label="{{ __('Type') }}"
                        class="w-full"
                    >
                        <option value="">{{ __('Select Type') }}</option>
                        @foreach($typeOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </flux:select>
                    <flux:error name="type" />
                </div>

                <div>
                    <flux:input 
                        wire:model="subject" 
                        label="{{ __('Subject') }}"
                        placeholder="{{ __('Enter activity subject') }}"
                        class="w-full"
                    />
                    <flux:error name="subject" />
                </div>

                <div class="md:col-span-2">
                    <flux:textarea 
                        wire:model="description" 
                        label="{{ __('Description') }}"
                        placeholder="{{ __('Enter activity description') }}"
                        rows="3"
                        class="w-full"
                    />
                    <flux:error name="description" />
                </div>

                <div>
                    <flux:input 
                        wire:model="due_date" 
                        type="datetime-local"
                        label="{{ __('Due Date') }}"
                        class="w-full"
                    />
                    <flux:error name="due_date" />
                </div>

                <div>
                    <flux:select 
                        wire:model="priority" 
                        label="{{ __('Priority') }}"
                        class="w-full"
                    >
                        @foreach($priorityOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </flux:select>
                    <flux:error name="priority" />
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
                        wire:model="related_type" 
                        label="{{ __('Related Type') }}"
                        class="w-full"
                    >
                        <option value="">{{ __('Select Type') }}</option>
                        @foreach($relatedTypeOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </flux:select>
                    <flux:error name="related_type" />
                </div>

                <div>
                    <flux:select 
                        wire:model="related_id" 
                        wire:key="related-items-{{ $related_type }}"
                        label="{{ __('Related Item') }}"
                        class="w-full"
                    >
                        <option value="">{{ __('Select Item') }}</option>
                        @if($related_type)
                            @if($relatedItems->count() > 0)
                                @foreach($relatedItems as $item)
                                    <option value="{{ $item->id }}">
                                        @if($related_type === 'lead')
                                            {{ $item->first_name }} {{ $item->last_name }} ({{ $item->email }})
                                        @elseif($related_type === 'opportunity')
                                            {{ $item->name }} ({{ currency($item->amount ?: 0) }})
                                        @endif
                                    </option>
                                @endforeach
                            @else
                                <option value="" disabled>{{ __('No items found') }}</option>
                            @endif
                        @else
                            <option value="" disabled>{{ __('Please select a type first') }}</option>
                        @endif
                    </flux:select>
                    <flux:error name="related_id" />
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
            <a href="{{ route('crm.activities.index') }}" 
               class="px-6 py-3 bg-neutral-500 hover:bg-neutral-600 text-white rounded-lg font-semibold transition-all duration-200 shadow-lg hover:shadow-xl">
                {{ __('Cancel') }}
            </a>
            <flux:button type="submit" variant="primary" class="px-6 py-3">
                {{ __('Create Activity') }}
            </flux:button>
        </div>
    </form>
</div>