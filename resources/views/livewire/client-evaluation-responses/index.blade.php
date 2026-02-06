<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <flux:heading size="xl">{{ __('Client Feedback') }}</flux:heading>
            <flux:subheading class="mt-1">{{ __('Review client feedback submissions.') }}</flux:subheading>
        </div>
    </div>

    @if (session('success'))
        <flux:callout variant="success">{{ session('success') }}</flux:callout>
    @endif
    @if (session('error'))
        <flux:callout variant="danger">{{ session('error') }}</flux:callout>
    @endif

    <flux:separator variant="subtle" />

    <div class="flex flex-wrap items-center gap-2">
        <button type="button" wire:click="setActiveTab('feedback')"
                class="rounded-lg px-4 py-2 text-sm font-semibold border {{ $activeTab === 'feedback' ? 'bg-[#F5B301]/20 text-[#F5B301] border-[#F5B301]/40' : 'bg-white text-neutral-700 border-neutral-200 dark:bg-neutral-800 dark:text-neutral-300 dark:border-neutral-700' }}">
            {{ __('Feedback') }}
        </button>
        <button type="button" wire:click="setActiveTab('send-links')"
                class="rounded-lg px-4 py-2 text-sm font-semibold border {{ $activeTab === 'send-links' ? 'bg-[#F5B301]/20 text-[#F5B301] border-[#F5B301]/40' : 'bg-white text-neutral-700 border-neutral-200 dark:bg-neutral-800 dark:text-neutral-300 dark:border-neutral-700' }}">
            {{ __('Send Links') }}
        </button>
    </div>

    @if ($activeTab === 'feedback')
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
                                @if ($response->client)
                                    <a class="font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400" href="{{ route('clients.show', $response->client) }}">
                                        {{ $response->client->contact_person ?? $response->client->user?->name }}
                                    </a>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300">
                                @if ($response->maid)
                                    <a class="font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400" href="{{ route('maids.show', $response->maid) }}">
                                        {{ $response->maid->full_name }}
                                    </a>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300">
                                <a class="font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400" href="{{ route('client-feedback.show', $response) }}">
                                    {{ $response->respondent_name }}
                                </a>
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
    @endif

    @if ($activeTab === 'send-links')
        <div class="rounded-lg border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <flux:select wire:model.live="selectedClientId" :label="__('Client')" class="md:col-span-2">
                    <option value="">{{ __('Select a client...') }}</option>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}">
                            {{ $client->contact_person ?? $client->user?->name }}
                        </option>
                    @endforeach
                </flux:select>
                <flux:select wire:model.live="linkExpiresInDays" :label="__('Link Expiry')">
                    <option value="7">{{ __('7 days') }}</option>
                    <option value="14">{{ __('14 days') }}</option>
                    <option value="30">{{ __('30 days') }}</option>
                </flux:select>
            </div>
            @error('selectedClientId') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
        </div>

        <div class="overflow-hidden rounded-lg border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
            <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                <thead class="bg-neutral-50 dark:bg-neutral-900/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Booking') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Maid') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Package') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Dates') }}</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-800">
                    @forelse ($clientBookings as $booking)
                        <tr class="transition-colors hover:bg-neutral-50 dark:hover:bg-neutral-900/30">
                            <td class="px-6 py-4 text-sm text-neutral-900 dark:text-white">#{{ $booking->id }}</td>
                            <td class="px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300">
                                {{ $booking->maid?->full_name ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300">
                                {{ $booking->package?->name ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300">
                                {{ $booking->start_date?->format('M d, Y') ?? __('N/A') }}
                            </td>
                            <td class="px-6 py-4 text-right text-sm">
                                <flux:button wire:click="sendClientEvaluationLink({{ $booking->id }})" variant="primary" size="sm">
                                    {{ __('Send Link') }}
                                </flux:button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-sm text-neutral-500 dark:text-neutral-400">
                                {{ __('Select a client to view bookings.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
</div>
