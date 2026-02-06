<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <flux:heading size="xl">{{ __('Client Evaluation') }}</flux:heading>
            <flux:subheading class="mt-1">
                {{ $evaluation->client?->contact_person }} • {{ $evaluation->evaluation_date?->format('M d, Y') }}
            </flux:subheading>
        </div>
        <flux:button as="a" :href="route('client-evaluations.index')" variant="ghost" icon="arrow-left">
            {{ __('Back') }}
        </flux:button>
    </div>

    <div class="details-card">
        <flux:heading size="lg" class="mb-4">{{ __('Evaluation Details') }}</flux:heading>

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="text-xs font-semibold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Client') }}</label>
                <div class="mt-1 text-sm text-neutral-900 dark:text-white">
                    {{ $evaluation->client?->contact_person }}
                </div>
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Trainer') }}</label>
                <div class="mt-1 text-sm text-neutral-900 dark:text-white">
                    {{ $evaluation->trainer?->user?->name ?? '—' }}
                </div>
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Evaluation Type') }}</label>
                <div class="mt-1 text-sm text-neutral-900 dark:text-white">
                    {{ str_replace('_', ' ', $evaluation->evaluation_type) }}
                </div>
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Overall Rating') }}</label>
                <div class="mt-1 text-sm text-neutral-900 dark:text-white">
                    @if($evaluation->overall_rating)
                        <flux:badge color="blue" size="sm">{{ number_format($evaluation->overall_rating, 1) }}/5</flux:badge>
                    @else
                        —
                    @endif
                </div>
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Next Evaluation Date') }}</label>
                <div class="mt-1 text-sm text-neutral-900 dark:text-white">
                    {{ $evaluation->next_evaluation_date?->format('M d, Y') ?? '—' }}
                </div>
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Booking') }}</label>
                <div class="mt-1 text-sm text-neutral-900 dark:text-white">
                    {{ $evaluation->booking_id ? '#' . $evaluation->booking_id : '—' }}
                </div>
            </div>
        </div>
    </div>

    <div class="details-card">
        <flux:heading size="lg" class="mb-4">{{ __('Feedback') }}</flux:heading>

        <div class="space-y-4">
            <div>
                <label class="text-xs font-semibold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Strengths') }}</label>
                <p class="mt-1 text-sm text-neutral-700 dark:text-neutral-300">{{ $evaluation->strengths ?? '—' }}</p>
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Areas for Improvement') }}</label>
                <p class="mt-1 text-sm text-neutral-700 dark:text-neutral-300">{{ $evaluation->areas_for_improvement ?? '—' }}</p>
            </div>
            <div>
                <label class="text-xs font-semibold uppercase tracking-wider text-neutral-500 dark:text-neutral-400">{{ __('Comments') }}</label>
                <p class="mt-1 text-sm text-neutral-700 dark:text-neutral-300">{{ $evaluation->comments ?? '—' }}</p>
            </div>
        </div>
    </div>
</div>
