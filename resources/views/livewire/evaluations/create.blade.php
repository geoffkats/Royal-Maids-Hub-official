<div class="min-h-screen bg-[#3B0A45] dark:bg-[#3B0A45] p-6">
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-[#3B0A45] to-[#512B58] border border-[#F5B301]/30 rounded-xl p-6 shadow-lg">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-[#F5B301] to-[#FF9800] rounded-lg flex items-center justify-center">
                        <flux:icon.clipboard-document-check class="w-6 h-6 text-[#3B0A45]" />
                    </div>
                    <div>
                        <flux:heading size="xl" class="text-white">{{ __('New Evaluation') }}</flux:heading>
                        <flux:subheading class="mt-1 text-[#D1C4E9]">{{ __('Comprehensive trainer evaluation form') }}</flux:subheading>
                    </div>
                </div>

                <flux:button as="a" :href="route('evaluations.index')" variant="ghost" icon="arrow-left" class="bg-[#F5B301]/10 hover:bg-[#F5B301]/20 text-[#F5B301] border-[#F5B301]/30">
                    {{ __('Back') }}
                </flux:button>
            </div>
        </div>

    <form wire:submit.prevent="save" class="space-y-6">
        {{-- 1. Session Information --}}
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <flux:heading size="lg" class="mb-4 text-white">{{ __('1. Session Information') }}</flux:heading>
            
            <div class="grid gap-4 md:grid-cols-2">
                <flux:select wire:model.defer="trainer_id" :label="__('Trainer')" required>
                    <option value="">{{ __('Select Trainer') }}</option>
                    @foreach ($trainers as $trainer)
                        <option value="{{ $trainer->id }}">{{ $trainer->user?->name }}</option>
                    @endforeach
                </flux:select>

                <flux:select wire:model.defer="maid_id" :label="__('Maid')" required>
                    <option value="">{{ __('Select Maid') }}</option>
                    @foreach ($maids as $maid)
                        <option value="{{ $maid->id }}">{{ $maid->first_name }} {{ $maid->last_name }}</option>
                    @endforeach
                </flux:select>

                <flux:input type="date" wire:model.defer="evaluation_date" :label="__('Date of Observation')" required />

                @if(auth()->user()->role === 'admin')
                <flux:select wire:model.defer="status" :label="__('Status')" required>
                    <option value="pending">{{ __('Pending') }}</option>
                    <option value="approved">{{ __('Approved') }}</option>
                    <option value="rejected">{{ __('Rejected') }}</option>
                </flux:select>
                @else
                <div>
                    <label class="block text-sm font-medium text-[#D1C4E9] mb-2">{{ __('Status') }}</label>
                    <div class="px-3 py-2 bg-[#3B0A45] border border-[#F5B301]/30 rounded-lg text-[#D1C4E9]">
                        <flux:badge color="yellow" size="sm">{{ __('Pending') }}</flux:badge>
                        <span class="text-xs text-[#D1C4E9]/70 ml-2">{{ __('Awaiting admin approval') }}</span>
                    </div>
                </div>
                @endif

                <flux:select wire:model.defer="program_id" :label="__('Training Program (Optional)')" class="md:col-span-2">
                    <option value="">{{ __('None') }}</option>
                    @foreach ($programs as $program)
                        <option value="{{ $program->id }}">
                            {{ $program->program_type }} - {{ $program->maid?->first_name }} {{ $program->maid?->last_name }}
                        </option>
                    @endforeach
                </flux:select>
            </div>
        </div>

        {{-- 2. Personality Evaluation --}}
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <flux:heading size="lg" class="mb-4 text-white">{{ __('2. Personality Evaluation') }}</flux:heading>
            
            <div class="space-y-6">
                {{-- Confidence --}}
                <div>
                    <div class="mb-2 flex items-center justify-between">
                        <label class="text-sm font-medium text-[#D1C4E9]">{{ __('Confidence') }}</label>
                        <flux:badge color="{{ $confidence >= 4 ? 'green' : ($confidence >= 3 ? 'blue' : 'yellow') }}" size="sm">
                            {{ $confidence }}
                        </flux:badge>
                    </div>
                    <input 
                        type="range" 
                        wire:model.live="confidence" 
                        min="1" 
                        max="5" 
                        step="1"
                        class="w-full"
                    />
                    <div class="mt-1 flex justify-between text-xs text-[#D1C4E9]/70">
                        <span>1</span>
                        <span>2</span>
                        <span>3</span>
                        <span>4</span>
                        <span>5</span>
                    </div>
                </div>

                {{-- Self-awareness --}}
                <div>
                    <div class="mb-2 flex items-center justify-between">
                        <label class="text-sm font-medium text-[#D1C4E9]">{{ __('Self-awareness') }}</label>
                        <flux:badge color="{{ $self_awareness >= 4 ? 'green' : ($self_awareness >= 3 ? 'blue' : 'yellow') }}" size="sm">
                            {{ $self_awareness }}
                        </flux:badge>
                    </div>
                    <input 
                        type="range" 
                        wire:model.live="self_awareness" 
                        min="1" 
                        max="5" 
                        step="1"
                        class="w-full"
                    />
                    <div class="mt-1 flex justify-between text-xs text-[#D1C4E9]/70">
                        <span>1</span>
                        <span>2</span>
                        <span>3</span>
                        <span>4</span>
                        <span>5</span>
                    </div>
                </div>

                {{-- Emotional Stability --}}
                <div>
                    <div class="mb-2 flex items-center justify-between">
                        <label class="text-sm font-medium text-[#D1C4E9]">{{ __('Emotional Stability') }}</label>
                        <flux:badge color="{{ $emotional_stability >= 4 ? 'green' : ($emotional_stability >= 3 ? 'blue' : 'yellow') }}" size="sm">
                            {{ $emotional_stability }}
                        </flux:badge>
                    </div>
                    <input 
                        type="range" 
                        wire:model.live="emotional_stability" 
                        min="1" 
                        max="5" 
                        step="1"
                        class="w-full"
                    />
                    <div class="mt-1 flex justify-between text-xs text-[#D1C4E9]/70">
                        <span>1</span>
                        <span>2</span>
                        <span>3</span>
                        <span>4</span>
                        <span>5</span>
                    </div>
                </div>

                {{-- Growth Mindset --}}
                <div>
                    <div class="mb-2 flex items-center justify-between">
                        <label class="text-sm font-medium text-[#D1C4E9]">{{ __('Growth Mindset') }}</label>
                        <flux:badge color="{{ $growth_mindset >= 4 ? 'green' : ($growth_mindset >= 3 ? 'blue' : 'yellow') }}" size="sm">
                            {{ $growth_mindset }}
                        </flux:badge>
                    </div>
                    <input 
                        type="range" 
                        wire:model.live="growth_mindset" 
                        min="1" 
                        max="5" 
                        step="1"
                        class="w-full"
                    />
                    <div class="mt-1 flex justify-between text-xs text-[#D1C4E9]/70">
                        <span>1</span>
                        <span>2</span>
                        <span>3</span>
                        <span>4</span>
                        <span>5</span>
                    </div>
                </div>

                {{-- Personality Comments --}}
                <flux:textarea wire:model.defer="personality_comments" :label="__('Comments')" rows="3" placeholder="{{ __('Optional comments on personality evaluation...') }}" />
            </div>
        </div>

        {{-- 3. Behavior Evaluation --}}
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <flux:heading size="lg" class="mb-4 text-white">{{ __('3. Behavior Evaluation') }}</flux:heading>
            
            <div class="space-y-6">
                {{-- Punctuality & Time Discipline --}}
                <div>
                    <div class="mb-2 flex items-center justify-between">
                        <label class="text-sm font-medium text-[#D1C4E9]">{{ __('Punctuality & Time Discipline') }}</label>
                        <flux:badge color="{{ $punctuality >= 4 ? 'green' : ($punctuality >= 3 ? 'blue' : 'yellow') }}" size="sm">
                            {{ $punctuality }}
                        </flux:badge>
                    </div>
                    <input 
                        type="range" 
                        wire:model.live="punctuality" 
                        min="1" 
                        max="5" 
                        step="1"
                        class="w-full"
                    />
                    <div class="mt-1 flex justify-between text-xs text-[#D1C4E9]/70">
                        <span>1</span>
                        <span>2</span>
                        <span>3</span>
                        <span>4</span>
                        <span>5</span>
                    </div>
                </div>

                {{-- Respect for Instructions --}}
                <div>
                    <div class="mb-2 flex items-center justify-between">
                        <label class="text-sm font-medium text-[#D1C4E9]">{{ __('Respect for Instructions') }}</label>
                        <flux:badge color="{{ $respect_for_instructions >= 4 ? 'green' : ($respect_for_instructions >= 3 ? 'blue' : 'yellow') }}" size="sm">
                            {{ $respect_for_instructions }}
                        </flux:badge>
                    </div>
                    <input 
                        type="range" 
                        wire:model.live="respect_for_instructions" 
                        min="1" 
                        max="5" 
                        step="1"
                        class="w-full"
                    />
                    <div class="mt-1 flex justify-between text-xs text-[#D1C4E9]/70">
                        <span>1</span>
                        <span>2</span>
                        <span>3</span>
                        <span>4</span>
                        <span>5</span>
                    </div>
                </div>

                {{-- Work Ownership --}}
                <div>
                    <div class="mb-2 flex items-center justify-between">
                        <label class="text-sm font-medium text-[#D1C4E9]">{{ __('Work Ownership') }}</label>
                        <flux:badge color="{{ $work_ownership >= 4 ? 'green' : ($work_ownership >= 3 ? 'blue' : 'yellow') }}" size="sm">
                            {{ $work_ownership }}
                        </flux:badge>
                    </div>
                    <input 
                        type="range" 
                        wire:model.live="work_ownership" 
                        min="1" 
                        max="5" 
                        step="1"
                        class="w-full"
                    />
                    <div class="mt-1 flex justify-between text-xs text-[#D1C4E9]/70">
                        <span>1</span>
                        <span>2</span>
                        <span>3</span>
                        <span>4</span>
                        <span>5</span>
                    </div>
                </div>

                {{-- Interpersonal Conduct --}}
                <div>
                    <div class="mb-2 flex items-center justify-between">
                        <label class="text-sm font-medium text-[#D1C4E9]">{{ __('Interpersonal Conduct') }}</label>
                        <flux:badge color="{{ $interpersonal_conduct >= 4 ? 'green' : ($interpersonal_conduct >= 3 ? 'blue' : 'yellow') }}" size="sm">
                            {{ $interpersonal_conduct }}
                        </flux:badge>
                    </div>
                    <input 
                        type="range" 
                        wire:model.live="interpersonal_conduct" 
                        min="1" 
                        max="5" 
                        step="1"
                        class="w-full"
                    />
                    <div class="mt-1 flex justify-between text-xs text-[#D1C4E9]/70">
                        <span>1</span>
                        <span>2</span>
                        <span>3</span>
                        <span>4</span>
                        <span>5</span>
                    </div>
                </div>

                {{-- Behavior Comments --}}
                <flux:textarea wire:model.defer="behavior_comments" :label="__('Comments')" rows="3" placeholder="{{ __('Optional comments on behavior evaluation...') }}" />
            </div>
        </div>

        {{-- 4. Performance Evaluation - Security and Safety --}}
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <flux:heading size="lg" class="mb-4 text-white">{{ __('4. Performance Evaluation') }}</flux:heading>
            <flux:subheading class="mb-4 text-[#D1C4E9]">{{ __('Security and Safety') }}</flux:subheading>
            
            <div class="space-y-6">
                {{-- Alertness --}}
                <div>
                    <div class="mb-2 flex items-center justify-between">
                        <label class="text-sm font-medium text-[#D1C4E9]">{{ __('Alertness') }}</label>
                        <flux:badge color="{{ $alertness >= 4 ? 'green' : ($alertness >= 3 ? 'blue' : 'yellow') }}" size="sm">
                            {{ $alertness }}
                        </flux:badge>
                    </div>
                    <input 
                        type="range" 
                        wire:model.live="alertness" 
                        min="1" 
                        max="5" 
                        step="1"
                        class="w-full"
                    />
                    <div class="mt-1 flex justify-between text-xs text-[#D1C4E9]/70">
                        <span>1</span>
                        <span>2</span>
                        <span>3</span>
                        <span>4</span>
                        <span>5</span>
                    </div>
                </div>

                {{-- Ability to offer first aid --}}
                <div>
                    <div class="mb-2 flex items-center justify-between">
                        <label class="text-sm font-medium text-[#D1C4E9]">{{ __('Ability to offer first aid') }}</label>
                        <flux:badge color="{{ $first_aid_ability >= 4 ? 'green' : ($first_aid_ability >= 3 ? 'blue' : 'yellow') }}" size="sm">
                            {{ $first_aid_ability }}
                        </flux:badge>
                    </div>
                    <input 
                        type="range" 
                        wire:model.live="first_aid_ability" 
                        min="1" 
                        max="5" 
                        step="1"
                        class="w-full"
                    />
                    <div class="mt-1 flex justify-between text-xs text-[#D1C4E9]/70">
                        <span>1</span>
                        <span>2</span>
                        <span>3</span>
                        <span>4</span>
                        <span>5</span>
                    </div>
                </div>

                {{-- Security consciousness --}}
                <div>
                    <div class="mb-2 flex items-center justify-between">
                        <label class="text-sm font-medium text-[#D1C4E9]">{{ __('Security consciousness') }}</label>
                        <flux:badge color="{{ $security_consciousness >= 4 ? 'green' : ($security_consciousness >= 3 ? 'blue' : 'yellow') }}" size="sm">
                            {{ $security_consciousness }}
                        </flux:badge>
                    </div>
                    <input 
                        type="range" 
                        wire:model.live="security_consciousness" 
                        min="1" 
                        max="5" 
                        step="1"
                        class="w-full"
                    />
                    <div class="mt-1 flex justify-between text-xs text-[#D1C4E9]/70">
                        <span>1</span>
                        <span>2</span>
                        <span>3</span>
                        <span>4</span>
                        <span>5</span>
                    </div>
                </div>

                {{-- Performance Comments --}}
                <flux:textarea wire:model.defer="performance_comments" :label="__('Comments')" rows="3" placeholder="{{ __('Optional comments on performance evaluation...') }}" />
            </div>
        </div>

        {{-- General Comments --}}
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <flux:heading size="lg" class="mb-4 text-white">{{ __('Additional Comments') }}</flux:heading>
            
            <div class="space-y-4">
                <flux:textarea wire:model.defer="general_comments" :label="__('General Comments')" rows="4" placeholder="{{ __('Overall observations and feedback...') }}" />
                <flux:textarea wire:model.defer="strengths" :label="__('Strengths')" rows="3" placeholder="{{ __('Key strengths observed...') }}" />
                <flux:textarea wire:model.defer="areas_for_improvement" :label="__('Areas for Improvement')" rows="3" placeholder="{{ __('Areas that need development...') }}" />
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <flux:button type="button" as="a" :href="route('evaluations.index')" variant="ghost" class="bg-[#F5B301]/10 hover:bg-[#F5B301]/20 text-[#F5B301] border-[#F5B301]/30">
                {{ __('Cancel') }}
            </flux:button>
            <flux:button type="submit" variant="primary" class="bg-gradient-to-r from-[#F5B301] to-[#FF9800] hover:from-[#FF9800] hover:to-[#F5B301] text-[#3B0A45] font-semibold shadow-lg hover:shadow-xl transition-all duration-200">
                {{ __('Create Evaluation') }}
            </flux:button>
        </div>
    </form>
    </div>
</div>
