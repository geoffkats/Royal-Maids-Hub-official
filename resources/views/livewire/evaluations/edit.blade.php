<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <flux:heading size="xl">{{ __('Edit Evaluation') }}</flux:heading>
            <flux:subheading class="mt-1">{{ __('Evaluation for') }} {{ $evaluation->maid?->first_name }} {{ $evaluation->maid?->last_name }}</flux:subheading>
        </div>

        <flux:button as="a" :href="route('evaluations.index')" variant="ghost" icon="arrow-left">
            {{ __('Back') }}
        </flux:button>
    </div>

    <flux:separator variant="subtle" />

    <form wire:submit.prevent="update" class="space-y-6">
        {{-- 1. Session Information --}}
        <div class="rounded-lg border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
            <flux:heading size="lg" class="mb-4">{{ __('1. Session Information') }}</flux:heading>
            
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

                <flux:select wire:model.defer="status" :label="__('Status')" required>
                    <option value="pending">{{ __('Pending') }}</option>
                    <option value="approved">{{ __('Approved') }}</option>
                    <option value="rejected">{{ __('Rejected') }}</option>
                </flux:select>

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
        <div class="rounded-lg border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
            <flux:heading size="lg" class="mb-4">{{ __('2. Personality Evaluation') }}</flux:heading>
            
            <div class="space-y-6">
                {{-- Confidence --}}
                <div>
                    <div class="mb-2 flex items-center justify-between">
                        <label class="text-sm font-medium text-neutral-700 dark:text-neutral-300">{{ __('Confidence') }}</label>
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
                    <div class="mt-1 flex justify-between text-xs text-neutral-500">
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
                        <label class="text-sm font-medium text-neutral-700 dark:text-neutral-300">{{ __('Self-awareness') }}</label>
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
                    <div class="mt-1 flex justify-between text-xs text-neutral-500">
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
                        <label class="text-sm font-medium text-neutral-700 dark:text-neutral-300">{{ __('Emotional Stability') }}</label>
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
                    <div class="mt-1 flex justify-between text-xs text-neutral-500">
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
                        <label class="text-sm font-medium text-neutral-700 dark:text-neutral-300">{{ __('Growth Mindset') }}</label>
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
                    <div class="mt-1 flex justify-between text-xs text-neutral-500">
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
        <div class="rounded-lg border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
            <flux:heading size="lg" class="mb-4">{{ __('3. Behavior Evaluation') }}</flux:heading>
            
            <div class="space-y-6">
                {{-- Punctuality & Time Discipline --}}
                <div>
                    <div class="mb-2 flex items-center justify-between">
                        <label class="text-sm font-medium text-neutral-700 dark:text-neutral-300">{{ __('Punctuality & Time Discipline') }}</label>
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
                    <div class="mt-1 flex justify-between text-xs text-neutral-500">
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
                        <label class="text-sm font-medium text-neutral-700 dark:text-neutral-300">{{ __('Respect for Instructions') }}</label>
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
                    <div class="mt-1 flex justify-between text-xs text-neutral-500">
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
                        <label class="text-sm font-medium text-neutral-700 dark:text-neutral-300">{{ __('Work Ownership') }}</label>
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
                    <div class="mt-1 flex justify-between text-xs text-neutral-500">
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
                        <label class="text-sm font-medium text-neutral-700 dark:text-neutral-300">{{ __('Interpersonal Conduct') }}</label>
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
                    <div class="mt-1 flex justify-between text-xs text-neutral-500">
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
        <div class="rounded-lg border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
            <flux:heading size="lg" class="mb-4">{{ __('4. Performance Evaluation') }}</flux:heading>
            <flux:subheading class="mb-4">{{ __('Security and Safety') }}</flux:subheading>
            
            <div class="space-y-6">
                {{-- Alertness --}}
                <div>
                    <div class="mb-2 flex items-center justify-between">
                        <label class="text-sm font-medium text-neutral-700 dark:text-neutral-300">{{ __('Alertness') }}</label>
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
                    <div class="mt-1 flex justify-between text-xs text-neutral-500">
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
                        <label class="text-sm font-medium text-neutral-700 dark:text-neutral-300">{{ __('Ability to offer first aid') }}</label>
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
                    <div class="mt-1 flex justify-between text-xs text-neutral-500">
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
                        <label class="text-sm font-medium text-neutral-700 dark:text-neutral-300">{{ __('Security consciousness') }}</label>
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
                    <div class="mt-1 flex justify-between text-xs text-neutral-500">
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
        <div class="rounded-lg border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
            <flux:heading size="lg" class="mb-4">{{ __('Additional Comments') }}</flux:heading>
            
            <div class="space-y-4">
                <flux:textarea wire:model.defer="general_comments" :label="__('General Comments')" rows="4" placeholder="{{ __('Overall observations and feedback...') }}" />
                <flux:textarea wire:model.defer="strengths" :label="__('Strengths')" rows="3" placeholder="{{ __('Key strengths observed...') }}" />
                <flux:textarea wire:model.defer="areas_for_improvement" :label="__('Areas for Improvement')" rows="3" placeholder="{{ __('Areas that need development...') }}" />
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <flux:button type="button" as="a" :href="route('evaluations.index')" variant="ghost">
                {{ __('Cancel') }}
            </flux:button>
            <flux:button type="submit" variant="primary">
                {{ __('Update Evaluation') }}
            </flux:button>
        </div>
    </form>
</div>
