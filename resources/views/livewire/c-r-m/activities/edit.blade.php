<div class="min-h-screen bg-gradient-to-br from-[#3B0A45] to-[#512B58]">
    <div class="mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Header Section --}}
        <div class="bg-gradient-to-r from-[#512B58] to-[#3B0A45] border border-[#F5B301]/30 rounded-xl p-6 text-white mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <x-flux::icon.pencil class="w-8 h-8 text-[#F5B301]" />
                    <div>
                        <h1 class="text-3xl font-bold">{{ __('Edit Activity') }}</h1>
                        <p class="text-[#D1C4E9] mt-1">{{ $activity->subject }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('crm.activities.show', $activity) }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg font-semibold text-white hover:bg-white/30 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <x-flux::icon.arrow-left class="w-4 h-4" />
                        {{ __('Back') }}
                    </a>
                </div>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if (session()->has('message'))
            <div class="mb-6 bg-gradient-to-r from-[#4CAF50]/20 to-[#4CAF50]/10 border border-[#4CAF50]/30 rounded-lg p-4">
                <div class="flex">
                    <x-flux::icon.check-circle class="w-5 h-5 text-[#4CAF50]" />
                    <div class="ml-3">
                        <p class="text-sm font-medium text-white">{{ session('message') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 bg-gradient-to-r from-[#E53935]/20 to-[#E53935]/10 border border-[#E53935]/30 rounded-lg p-4">
                <div class="flex">
                    <x-flux::icon.exclamation-triangle class="w-5 h-5 text-[#E53935]" />
                    <div class="ml-3">
                        <p class="text-sm font-medium text-white mb-2">{{ __('Please correct the following errors:') }}</p>
                        <ul class="text-sm text-white list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- Edit Form --}}
        <div class="bg-gradient-to-r from-[#512B58] to-[#3B0A45] border border-[#F5B301]/30 rounded-xl p-6 shadow-lg">
            <form wire:submit="save" class="space-y-6">
                {{-- Basic Information --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Activity Type --}}
                    <div>
                        <flux:field>
                            <flux:label>{{ __('Activity Type') }}</flux:label>
                            <flux:select wire:model="type" placeholder="{{ __('Select activity type') }}">
                                <option value="call">{{ __('Call') }}</option>
                                <option value="email">{{ __('Email') }}</option>
                                <option value="meeting">{{ __('Meeting') }}</option>
                                <option value="task">{{ __('Task') }}</option>
                                <option value="note">{{ __('Note') }}</option>
                            </flux:select>
                            <flux:error name="type" />
                        </flux:field>
                    </div>

                    {{-- Priority --}}
                    <div>
                        <flux:field>
                            <flux:label>{{ __('Priority') }}</flux:label>
                            <flux:select wire:model="priority" placeholder="{{ __('Select priority') }}">
                                <option value="low">{{ __('Low') }}</option>
                                <option value="medium">{{ __('Medium') }}</option>
                                <option value="high">{{ __('High') }}</option>
                            </flux:select>
                            <flux:error name="priority" />
                        </flux:field>
                    </div>

                    {{-- Status --}}
                    <div>
                        <flux:field>
                            <flux:label>{{ __('Status') }}</flux:label>
                            <flux:select wire:model="status" placeholder="{{ __('Select status') }}">
                                <option value="pending">{{ __('Pending') }}</option>
                                <option value="completed">{{ __('Completed') }}</option>
                                <option value="cancelled">{{ __('Cancelled') }}</option>
                            </flux:select>
                            <flux:error name="status" />
                        </flux:field>
                    </div>

                    {{-- Assigned To --}}
                    <div>
                        <flux:field>
                            <flux:label>{{ __('Assigned To') }}</flux:label>
                            <flux:select wire:model="assigned_to" placeholder="{{ __('Select user') }}">
                                <option value="">{{ __('Unassigned') }}</option>
                                @foreach($this->users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </flux:select>
                            <flux:error name="assigned_to" />
                        </flux:field>
                    </div>
                </div>

                {{-- Subject --}}
                <div>
                    <flux:field>
                        <flux:label>{{ __('Subject') }}</flux:label>
                        <flux:input wire:model="subject" placeholder="{{ __('Enter activity subject') }}" />
                        <flux:error name="subject" />
                    </flux:field>
                </div>

                {{-- Description --}}
                <div>
                    <flux:field>
                        <flux:label>{{ __('Description') }}</flux:label>
                        <flux:textarea wire:model="description" placeholder="{{ __('Enter activity description') }}" rows="4" />
                        <flux:error name="description" />
                    </flux:field>
                </div>

                {{-- Due Date and Outcome --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Due Date --}}
                    <div>
                        <flux:field>
                            <flux:label>{{ __('Due Date') }}</flux:label>
                            <flux:input wire:model="due_date" type="datetime-local" />
                            <flux:error name="due_date" />
                        </flux:field>
                    </div>

                    {{-- Outcome --}}
                    <div>
                        <flux:field>
                            <flux:label>{{ __('Outcome') }}</flux:label>
                            <flux:input wire:model="outcome" placeholder="{{ __('Enter activity outcome') }}" />
                            <flux:error name="outcome" />
                        </flux:field>
                    </div>
                </div>

                {{-- Related Information --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Related Type --}}
                    <div>
                        <flux:field>
                            <flux:label>{{ __('Related To') }}</flux:label>
                            <flux:select wire:model="related_type" placeholder="{{ __('Select related type') }}">
                                <option value="">{{ __('None') }}</option>
                                <option value="App\Models\CRM\Lead">{{ __('Lead') }}</option>
                                <option value="App\Models\CRM\Opportunity">{{ __('Opportunity') }}</option>
                            </flux:select>
                            <flux:error name="related_type" />
                        </flux:field>
                    </div>

                    {{-- Related ID --}}
                    <div>
                        <flux:field>
                            <flux:label>{{ __('Related Item') }}</flux:label>
                            <flux:select wire:model="related_id" placeholder="{{ __('Select related item') }}">
                                <option value="">{{ __('None') }}</option>
                                @if($related_type === 'App\Models\CRM\Lead')
                                    @foreach($this->leads as $lead)
                                        <option value="{{ $lead->id }}">{{ $lead->first_name }} {{ $lead->last_name }}</option>
                                    @endforeach
                                @elseif($related_type === 'App\Models\CRM\Opportunity')
                                    @foreach($this->opportunities as $opportunity)
                                        <option value="{{ $opportunity->id }}">{{ $opportunity->title }}</option>
                                    @endforeach
                                @endif
                            </flux:select>
                            <flux:error name="related_id" />
                        </flux:field>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="flex items-center justify-end gap-4 pt-6 border-t border-[#F5B301]/20">
                    <a href="{{ route('crm.activities.show', $activity) }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg font-semibold text-white hover:bg-white/30 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <x-flux::icon.x-mark class="w-4 h-4" />
                        {{ __('Cancel') }}
                    </a>
                    
                    <flux:button type="submit" variant="primary" class="bg-gradient-gold hover:bg-gradient-to-r hover:from-[#F5B301] hover:to-[#FFD54F] text-[#3B0A45] font-semibold">
                        <x-flux::icon.check class="w-4 h-4" />
                        {{ __('Update Activity') }}
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</div>
