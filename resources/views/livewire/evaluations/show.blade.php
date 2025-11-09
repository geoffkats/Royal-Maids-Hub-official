<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <flux:heading size="xl">{{ __('View Evaluation') }}</flux:heading>
            <flux:subheading class="mt-1">
                {{ __('Evaluation for') }} {{ $evaluation->maid?->first_name }} {{ $evaluation->maid?->last_name }}
                @if($evaluation->archived)
                    <flux:badge color="gray" size="sm" class="ml-2">{{ __('Archived') }}</flux:badge>
                @endif
            </flux:subheading>
        </div>

        <div class="flex gap-3">
            @can('update', $evaluation)
                @if(!$evaluation->archived)
                    <flux:button as="a" :href="route('evaluations.edit', $evaluation)" variant="primary" icon="pencil" wire:navigate>
                        {{ __('Edit') }}
                    </flux:button>
                @endif
                
                @if(auth()->user()->role === 'admin')
                <flux:button wire:click="toggleArchive" variant="ghost" :icon="$evaluation->archived ? 'arrow-uturn-left' : 'archive-box'">
                    {{ $evaluation->archived ? __('Unarchive') : __('Archive') }}
                </flux:button>
                @endif
            @endcan

            @can('delete', $evaluation)
                <flux:button wire:click="confirmDelete" variant="ghost" icon="trash" class="text-red-600 hover:text-red-700">
                    {{ __('Delete') }}
                </flux:button>
            @endcan

            <flux:button as="a" :href="route('evaluations.index')" variant="ghost" icon="arrow-left">
                {{ __('Back') }}
            </flux:button>
        </div>
    </div>

    <flux:separator variant="subtle" />

    @if (session('success'))
        <flux:callout variant="success" icon="check-circle" class="!border-green-200 !bg-green-50 dark:!border-green-900 dark:!bg-green-950">
            {{ session('success') }}
        </flux:callout>
    @endif

    {{-- 1. Session Information --}}
    <div class="details-card">
        <flux:heading size="lg" class="mb-4">{{ __('Session Information') }}</flux:heading>
        
        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="text-xs font-semibold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Maid') }}</label>
                <div class="mt-1">
                    <a href="{{ route('maids.show', $evaluation->maid) }}" wire:navigate class="text-base font-medium text-blue-600 hover:underline dark:text-blue-400">
                        {{ $evaluation->maid?->first_name }} {{ $evaluation->maid?->last_name }}
                    </a>
                </div>
            </div>

            <div>
                <label class="text-xs font-semibold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Trainer') }}</label>
                <div class="mt-1">
                    <a href="{{ route('trainers.show', $evaluation->trainer) }}" wire:navigate class="text-base font-medium text-blue-600 hover:underline dark:text-blue-400">
                        {{ $evaluation->trainer?->user?->name }}
                    </a>
                </div>
            </div>

            <div>
                <label class="text-xs font-semibold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Date of Observation') }}</label>
                <div class="mt-1 text-base text-neutral-900 dark:text-white">
                    {{ $evaluation->evaluation_date?->format('Y-m-d') }}
                </div>
            </div>

            <div>
                <label class="text-xs font-semibold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Status') }}</label>
                <div class="mt-1">
                    <flux:badge :color="$evaluation->getStatusBadgeColor()" size="sm">
                        {{ $evaluation->status_label }}
                    </flux:badge>
                </div>
            </div>

            @if($evaluation->program)
            <div class="md:col-span-2">
                <label class="text-xs font-semibold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Training Program') }}</label>
                <div class="mt-1">
                    <a href="{{ route('programs.show', $evaluation->program) }}" wire:navigate class="text-base font-medium text-blue-600 hover:underline dark:text-blue-400">
                        {{ $evaluation->program->program_type }}
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- 2. Personality Evaluation --}}
    <div class="details-card">
        <flux:heading size="lg" class="mb-4">{{ __('2. Personality Evaluation') }}</flux:heading>
        
        @if($evaluation->personality_scores)
            <div class="space-y-4">
                <div class="grid gap-6 md:grid-cols-2">
                    {{-- Confidence --}}
                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <label class="text-sm font-medium text-neutral-700 dark:text-neutral-300">{{ __('Confidence') }}</label>
                            <flux:badge :color="$evaluation->getScoreBadgeColor($evaluation->personality_scores['confidence'] ?? 0)" size="sm">
                                {{ $evaluation->personality_scores['confidence'] ?? 0 }}
                            </flux:badge>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-neutral-200 dark:bg-neutral-700">
                            <div class="h-full bg-{{ $evaluation->getScoreBadgeColor($evaluation->personality_scores['confidence'] ?? 0) }}-600" style="width: {{ (($evaluation->personality_scores['confidence'] ?? 0) / 5) * 100 }}%"></div>
                        </div>
                        @if(isset($evaluation->personality_scores['confidence']))
                            <p class="mt-1 text-xs text-neutral-500">{{ $evaluation->personality_scores['confidence'] >= 4 ? 'Confident' : ($evaluation->personality_scores['confidence'] >= 3 ? 'Moderately Confident' : 'Needs Confidence Building') }}</p>
                        @endif
                    </div>

                    {{-- Self-awareness --}}
                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <label class="text-sm font-medium text-neutral-700 dark:text-neutral-300">{{ __('Self-awareness') }}</label>
                            <flux:badge :color="$evaluation->getScoreBadgeColor($evaluation->personality_scores['self_awareness'] ?? 0)" size="sm">
                                {{ $evaluation->personality_scores['self_awareness'] ?? 0 }}
                            </flux:badge>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-neutral-200 dark:bg-neutral-700">
                            <div class="h-full bg-{{ $evaluation->getScoreBadgeColor($evaluation->personality_scores['self_awareness'] ?? 0) }}-600" style="width: {{ (($evaluation->personality_scores['self_awareness'] ?? 0) / 5) * 100 }}%"></div>
                        </div>
                        @if(isset($evaluation->personality_scores['self_awareness']))
                            <p class="mt-1 text-xs text-neutral-500">{{ $evaluation->personality_scores['self_awareness'] >= 4 ? 'Clearly self aware' : ($evaluation->personality_scores['self_awareness'] >= 3 ? 'Developing awareness' : 'Limited awareness') }}</p>
                        @endif
                    </div>

                    {{-- Emotional Stability --}}
                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <label class="text-sm font-medium text-neutral-700 dark:text-neutral-300">{{ __('Emotional Stability') }}</label>
                            <flux:badge :color="$evaluation->getScoreBadgeColor($evaluation->personality_scores['emotional_stability'] ?? 0)" size="sm">
                                {{ $evaluation->personality_scores['emotional_stability'] ?? 0 }}
                            </flux:badge>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-neutral-200 dark:bg-neutral-700">
                            <div class="h-full bg-{{ $evaluation->getScoreBadgeColor($evaluation->personality_scores['emotional_stability'] ?? 0) }}-600" style="width: {{ (($evaluation->personality_scores['emotional_stability'] ?? 0) / 5) * 100 }}%"></div>
                        </div>
                        @if(isset($evaluation->personality_scores['emotional_stability']))
                            <p class="mt-1 text-xs text-neutral-500">{{ $evaluation->personality_scores['emotional_stability'] >= 4 ? 'Stable' : ($evaluation->personality_scores['emotional_stability'] >= 3 ? 'Mostly stable' : 'Emotionally unstable') }}</p>
                        @endif
                    </div>

                    {{-- Growth Mindset --}}
                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <label class="text-sm font-medium text-neutral-700 dark:text-neutral-300">{{ __('Growth Mindset') }}</label>
                            <flux:badge :color="$evaluation->getScoreBadgeColor($evaluation->personality_scores['growth_mindset'] ?? 0)" size="sm">
                                {{ $evaluation->personality_scores['growth_mindset'] ?? 0 }}
                            </flux:badge>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-neutral-200 dark:bg-neutral-700">
                            <div class="h-full bg-{{ $evaluation->getScoreBadgeColor($evaluation->personality_scores['growth_mindset'] ?? 0) }}-600" style="width: {{ (($evaluation->personality_scores['growth_mindset'] ?? 0) / 5) * 100 }}%"></div>
                        </div>
                        @if(isset($evaluation->personality_scores['growth_mindset']))
                            <p class="mt-1 text-xs text-neutral-500">{{ $evaluation->personality_scores['growth_mindset'] >= 4 ? 'Grown' : ($evaluation->personality_scores['growth_mindset'] >= 3 ? 'Growing' : 'Slow growth') }}</p>
                        @endif
                    </div>
                </div>

                @if(!empty($evaluation->personality_scores['comments']))
                    <div class="mt-4 rounded-lg bg-neutral-50 p-4 dark:bg-neutral-900/50">
                        <label class="text-xs font-semibold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Comments') }}</label>
                        <p class="mt-1 text-sm text-neutral-700 dark:text-neutral-300">{{ $evaluation->personality_scores['comments'] }}</p>
                    </div>
                @endif
            </div>
        @else
            <p class="text-sm text-neutral-500">{{ __('No personality scores available.') }}</p>
        @endif
    </div>

    {{-- 3. Behavior Evaluation --}}
    <div class="details-card">
        <flux:heading size="lg" class="mb-4">{{ __('3. Behavior Evaluation') }}</flux:heading>
        
        @if($evaluation->behavior_scores)
            <div class="space-y-4">
                <div class="grid gap-6 md:grid-cols-2">
                    {{-- Punctuality & Time Discipline --}}
                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <label class="text-sm font-medium text-neutral-700 dark:text-neutral-300">{{ __('Punctuality & Time Discipline') }}</label>
                            <flux:badge :color="$evaluation->getScoreBadgeColor($evaluation->behavior_scores['punctuality'] ?? 0)" size="sm">
                                {{ $evaluation->behavior_scores['punctuality'] ?? 0 }}
                            </flux:badge>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-neutral-200 dark:bg-neutral-700">
                            <div class="h-full bg-{{ $evaluation->getScoreBadgeColor($evaluation->behavior_scores['punctuality'] ?? 0) }}-600" style="width: {{ (($evaluation->behavior_scores['punctuality'] ?? 0) / 5) * 100 }}%"></div>
                        </div>
                        @if(isset($evaluation->behavior_scores['punctuality']))
                            <p class="mt-1 text-xs text-neutral-500">{{ $evaluation->behavior_scores['punctuality'] >= 4 ? 'Punctual' : ($evaluation->behavior_scores['punctuality'] >= 3 ? 'Usually punctual' : 'Often late') }}</p>
                        @endif
                    </div>

                    {{-- Respect for Instructions --}}
                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <label class="text-sm font-medium text-neutral-700 dark:text-neutral-300">{{ __('Respect for Instructions') }}</label>
                            <flux:badge :color="$evaluation->getScoreBadgeColor($evaluation->behavior_scores['respect_for_instructions'] ?? 0)" size="sm">
                                {{ $evaluation->behavior_scores['respect_for_instructions'] ?? 0 }}
                            </flux:badge>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-neutral-200 dark:bg-neutral-700">
                            <div class="h-full bg-{{ $evaluation->getScoreBadgeColor($evaluation->behavior_scores['respect_for_instructions'] ?? 0) }}-600" style="width: {{ (($evaluation->behavior_scores['respect_for_instructions'] ?? 0) / 5) * 100 }}%"></div>
                        </div>
                        @if(isset($evaluation->behavior_scores['respect_for_instructions']))
                            <p class="mt-1 text-xs text-neutral-500">{{ $evaluation->behavior_scores['respect_for_instructions'] >= 4 ? 'Good' : ($evaluation->behavior_scores['respect_for_instructions'] >= 3 ? 'Fair' : 'Poor') }}</p>
                        @endif
                    </div>

                    {{-- Work Ownership --}}
                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <label class="text-sm font-medium text-neutral-700 dark:text-neutral-300">{{ __('Work Ownership') }}</label>
                            <flux:badge :color="$evaluation->getScoreBadgeColor($evaluation->behavior_scores['work_ownership'] ?? 0)" size="sm">
                                {{ $evaluation->behavior_scores['work_ownership'] ?? 0 }}
                            </flux:badge>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-neutral-200 dark:bg-neutral-700">
                            <div class="h-full bg-{{ $evaluation->getScoreBadgeColor($evaluation->behavior_scores['work_ownership'] ?? 0) }}-600" style="width: {{ (($evaluation->behavior_scores['work_ownership'] ?? 0) / 5) * 100 }}%"></div>
                        </div>
                        @if(isset($evaluation->behavior_scores['work_ownership']))
                            <p class="mt-1 text-xs text-neutral-500">{{ $evaluation->behavior_scores['work_ownership'] >= 4 ? 'Excellent' : ($evaluation->behavior_scores['work_ownership'] >= 3 ? 'Good' : 'Poor ownership') }}</p>
                        @endif
                    </div>

                    {{-- Interpersonal Conduct --}}
                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <label class="text-sm font-medium text-neutral-700 dark:text-neutral-300">{{ __('Interpersonal Conduct') }}</label>
                            <flux:badge :color="$evaluation->getScoreBadgeColor($evaluation->behavior_scores['interpersonal_conduct'] ?? 0)" size="sm">
                                {{ $evaluation->behavior_scores['interpersonal_conduct'] ?? 0 }}
                            </flux:badge>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-neutral-200 dark:bg-neutral-700">
                            <div class="h-full bg-{{ $evaluation->getScoreBadgeColor($evaluation->behavior_scores['interpersonal_conduct'] ?? 0) }}-600" style="width: {{ (($evaluation->behavior_scores['interpersonal_conduct'] ?? 0) / 5) * 100 }}%"></div>
                        </div>
                        @if(isset($evaluation->behavior_scores['interpersonal_conduct']))
                            <p class="mt-1 text-xs text-neutral-500">{{ $evaluation->behavior_scores['interpersonal_conduct'] >= 4 ? 'Good' : ($evaluation->behavior_scores['interpersonal_conduct'] >= 3 ? 'Fair' : 'Needs improvement') }}</p>
                        @endif
                    </div>
                </div>

                @if(!empty($evaluation->behavior_scores['comments']))
                    <div class="mt-4 rounded-lg bg-neutral-50 p-4 dark:bg-neutral-900/50">
                        <label class="text-xs font-semibold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Comments') }}</label>
                        <p class="mt-1 text-sm text-neutral-700 dark:text-neutral-300">{{ $evaluation->behavior_scores['comments'] }}</p>
                    </div>
                @endif
            </div>
        @else
            <p class="text-sm text-neutral-500">{{ __('No behavior scores available.') }}</p>
        @endif
    </div>

    {{-- 4. Performance Evaluation --}}
    <div class="details-card">
        <flux:heading size="lg" class="mb-4">{{ __('4. Performance Evaluation') }}</flux:heading>
        <flux:subheading class="mb-4">{{ __('Performance Area Details - Security and Safety') }}</flux:subheading>
        
        @if($evaluation->performance_scores)
            <div class="space-y-4">
                <div class="grid gap-6 md:grid-cols-2">
                    {{-- Alertness --}}
                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <label class="text-sm font-medium text-neutral-700 dark:text-neutral-300">{{ __('Alertness') }}</label>
                            <flux:badge :color="$evaluation->getScoreBadgeColor($evaluation->performance_scores['alertness'] ?? 0)" size="sm">
                                {{ $evaluation->performance_scores['alertness'] ?? 0 }}
                            </flux:badge>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-neutral-200 dark:bg-neutral-700">
                            <div class="h-full bg-{{ $evaluation->getScoreBadgeColor($evaluation->performance_scores['alertness'] ?? 0) }}-600" style="width: {{ (($evaluation->performance_scores['alertness'] ?? 0) / 5) * 100 }}%"></div>
                        </div>
                        @if(isset($evaluation->performance_scores['alertness']))
                            <p class="mt-1 text-xs text-neutral-500">{{ $evaluation->performance_scores['alertness'] >= 4 ? 'Very alert' : ($evaluation->performance_scores['alertness'] >= 3 ? 'Alert' : 'Not very alert') }}</p>
                        @endif
                    </div>

                    {{-- Ability to offer first aid --}}
                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <label class="text-sm font-medium text-neutral-700 dark:text-neutral-300">{{ __('Ability to offer first aid') }}</label>
                            <flux:badge :color="$evaluation->getScoreBadgeColor($evaluation->performance_scores['first_aid_ability'] ?? 0)" size="sm">
                                {{ $evaluation->performance_scores['first_aid_ability'] ?? 0 }}
                            </flux:badge>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-neutral-200 dark:bg-neutral-700">
                            <div class="h-full bg-{{ $evaluation->getScoreBadgeColor($evaluation->performance_scores['first_aid_ability'] ?? 0) }}-600" style="width: {{ (($evaluation->performance_scores['first_aid_ability'] ?? 0) / 5) * 100 }}%"></div>
                        </div>
                        @if(isset($evaluation->performance_scores['first_aid_ability']))
                            <p class="mt-1 text-xs text-neutral-500">{{ $evaluation->performance_scores['first_aid_ability'] >= 4 ? 'Excellent' : ($evaluation->performance_scores['first_aid_ability'] >= 3 ? 'Improved' : 'Needs training') }}</p>
                        @endif
                    </div>

                    {{-- Security consciousness --}}
                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <label class="text-sm font-medium text-neutral-700 dark:text-neutral-300">{{ __('Security consciousness') }}</label>
                            <flux:badge :color="$evaluation->getScoreBadgeColor($evaluation->performance_scores['security_consciousness'] ?? 0)" size="sm">
                                {{ $evaluation->performance_scores['security_consciousness'] ?? 0 }}
                            </flux:badge>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-neutral-200 dark:bg-neutral-700">
                            <div class="h-full bg-{{ $evaluation->getScoreBadgeColor($evaluation->performance_scores['security_consciousness'] ?? 0) }}-600" style="width: {{ (($evaluation->performance_scores['security_consciousness'] ?? 0) / 5) * 100 }}%"></div>
                        </div>
                        @if(isset($evaluation->performance_scores['security_consciousness']))
                            <p class="mt-1 text-xs text-neutral-500">{{ $evaluation->performance_scores['security_consciousness'] >= 4 ? 'Improved' : ($evaluation->performance_scores['security_consciousness'] >= 3 ? 'Aware' : 'Low awareness') }}</p>
                        @endif
                    </div>
                </div>

                @if(!empty($evaluation->performance_scores['comments']))
                    <div class="mt-4 rounded-lg bg-neutral-50 p-4 dark:bg-neutral-900/50">
                        <label class="text-xs font-semibold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Comments') }}</label>
                        <p class="mt-1 text-sm text-neutral-700 dark:text-neutral-300">{{ $evaluation->performance_scores['comments'] }}</p>
                    </div>
                @endif
            </div>
        @else
            <p class="text-sm text-neutral-500">{{ __('No performance scores available.') }}</p>
        @endif
    </div>

    {{-- Additional Comments --}}
    @if($evaluation->general_comments || $evaluation->strengths || $evaluation->areas_for_improvement)
        <div class="details-card">
            <flux:heading size="lg" class="mb-4">{{ __('Additional Comments') }}</flux:heading>
            
            <div class="space-y-4">
                @if($evaluation->general_comments)
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('General Comments') }}</label>
                        <p class="mt-1 text-sm text-neutral-700 dark:text-neutral-300">{{ $evaluation->general_comments }}</p>
                    </div>
                @endif

                @if($evaluation->strengths)
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Strengths') }}</label>
                        <p class="mt-1 text-sm text-neutral-700 dark:text-neutral-300">{{ $evaluation->strengths }}</p>
                    </div>
                @endif

                @if($evaluation->areas_for_improvement)
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Areas for Improvement') }}</label>
                        <p class="mt-1 text-sm text-neutral-700 dark:text-neutral-300">{{ $evaluation->areas_for_improvement }}</p>
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- Overall Rating & Metadata --}}
    <div class="details-card">
        <div class="grid gap-4 md:grid-cols-3">
            <div>
                <label class="text-xs font-semibold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Overall Rating') }}</label>
                <div class="mt-1">
                    <flux:badge :color="$evaluation->getScoreBadgeColor($evaluation->overall_rating ?? 0)" size="lg">
                        {{ number_format($evaluation->overall_rating ?? 0, 1) }}/5.0
                    </flux:badge>
                </div>
            </div>

            <div>
                <label class="text-xs font-semibold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Created') }}</label>
                <div class="mt-1 text-sm text-neutral-700 dark:text-neutral-300">
                    {{ $evaluation->created_at?->format('M d, Y h:i A') }}
                </div>
            </div>

            <div>
                <label class="text-xs font-semibold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Last Updated') }}</label>
                <div class="mt-1 text-sm text-neutral-700 dark:text-neutral-300">
                    {{ $evaluation->updated_at?->format('M d, Y h:i A') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" wire:click.self="showDeleteModal = false">
            <div class="w-full max-w-md rounded-lg border border-neutral-200 bg-white p-6 shadow-xl dark:border-neutral-700 dark:bg-neutral-800">
                <div class="mb-4">
                    <flux:heading size="lg">{{ __('Delete Evaluation') }}</flux:heading>
                    <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400">
                        {{ __('Are you sure you want to delete this evaluation for :name? This action cannot be undone.', ['name' => $evaluation->maid?->first_name . ' ' . $evaluation->maid?->last_name]) }}
                    </p>
                </div>

                <div class="flex justify-end gap-3">
                    <flux:button wire:click="showDeleteModal = false" variant="ghost">
                        {{ __('Cancel') }}
                    </flux:button>
                    <flux:button wire:click="delete" variant="danger">
                        {{ __('Delete') }}
                    </flux:button>
                </div>
            </div>
        </div>
    @endif
</div>
