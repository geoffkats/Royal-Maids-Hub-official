<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <flux:heading size="xl">{{ __('Client Feedback') }}</flux:heading>
            <flux:subheading class="mt-1">{{ __('Review client feedback submissions.') }}</flux:subheading>
        </div>
    </div>

    <flux:separator variant="subtle" />

    <div class="rounded-lg border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <flux:input
                wire:model.live.debounce.400ms="search"
                :label="__('Search')"
                placeholder="{{ __('Client, maid, or respondent...') }}"
                icon="magnifying-glass"
                class="md:col-span-2"
            />
            <flux:select wire:model.live="perPage" :label="__('Per Page')">
                @foreach ([10, 15, 25, 50] as $size)
                    <option value="{{ $size }}">{{ $size }}</option>
                @endforeach
            </flux:select>
        </div>
    </div>

    <div class="overflow-hidden rounded-lg border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
        <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
            <thead class="bg-neutral-50 dark:bg-neutral-900/50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Client') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Maid') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Respondent') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Rating') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Submitted') }}</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-800">
                @forelse ($responses as $response)
                    <tr class="transition-colors hover:bg-neutral-50 dark:hover:bg-neutral-900/30">
                        <td class="px-6 py-4 text-sm text-neutral-900 dark:text-white">
                            {{ $response->client?->contact_person ?? $response->client?->user?->name ?? '—' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300">
                            {{ $response->maid?->full_name ?? '—' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300">
                            <div class="font-medium text-neutral-900 dark:text-white">{{ $response->respondent_name }}</div>
                            <div class="text-xs text-neutral-500 dark:text-neutral-400">{{ $response->respondent_email }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300">
                            {{ $response->overall_rating ? number_format((float) $response->overall_rating, 1) : '—' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300">
                            {{ $response->submitted_at?->format('M d, Y') ?? __('N/A') }}
                        </td>
                        <td class="px-6 py-4 text-right text-sm">
                            <flux:button as="a" :href="route('client-feedback.show', $response)" variant="ghost" size="xs" icon="eye">
                                {{ __('View') }}
                            </flux:button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-sm text-neutral-500 dark:text-neutral-400">
                            {{ __('No feedback submitted yet.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $responses->links() }}
    </div>
</div>
