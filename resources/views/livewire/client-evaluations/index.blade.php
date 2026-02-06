<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <flux:heading size="xl">{{ __('Client Evaluations') }}</flux:heading>
            <flux:subheading class="mt-1">{{ __('Client feedback and follow-up reviews') }}</flux:subheading>
        </div>

        <flux:button as="a" :href="route('client-evaluations.create')" variant="primary" icon="plus">
            {{ __('New Client Evaluation') }}
        </flux:button>
    </div>

    <flux:separator variant="subtle" />

    <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <flux:input
                wire:model.live.debounce.400ms="search"
                :label="__('Search')"
                placeholder="{{ __('Client or trainer name...') }}"
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
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Trainer') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Type') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Rating') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Date') }}</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-800">
                @forelse ($evaluations as $evaluation)
                    <tr class="transition-colors hover:bg-neutral-50 dark:hover:bg-neutral-900/30">
                        <td class="px-6 py-4 text-sm text-neutral-900 dark:text-white">
                            {{ $evaluation->client?->contact_person }}
                        </td>
                        <td class="px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300">
                            {{ $evaluation->trainer?->user?->name ?? '—' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300">
                            {{ str_replace('_', ' ', $evaluation->evaluation_type) }}
                        </td>
                        <td class="px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300">
                            @if($evaluation->overall_rating)
                                <flux:badge size="sm" color="blue">{{ number_format($evaluation->overall_rating, 1) }}/5</flux:badge>
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300">
                            {{ $evaluation->evaluation_date?->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-right text-sm">
                            <flux:button as="a" :href="route('client-evaluations.show', $evaluation)" variant="ghost" size="sm" icon="eye">
                                {{ __('View') }}
                            </flux:button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-sm text-neutral-500 dark:text-neutral-400">
                            {{ __('No client evaluations found.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $evaluations->links() }}
    </div>
</div>
