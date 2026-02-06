<div>
    {{-- Success is as dangerous as failure. --}}
<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <flux:heading size="xl">{{ __('Evaluation Tasks') }}</flux:heading>
            <flux:subheading class="mt-1">{{ __('3, 6, and 12-month evaluation reminders') }}</flux:subheading>
        </div>
    </div>

    <flux:separator variant="subtle" />

    <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <flux:input
                wire:model.live.debounce.400ms="search"
                :label="__('Search')"
                placeholder="{{ __('Maid or client name...') }}"
                icon="magnifying-glass"
                class="md:col-span-2"
            />
            <flux:select wire:model.live="status" :label="__('Status')">
                <option value="">{{ __('All') }}</option>
                <option value="pending">{{ __('Pending') }}</option>
                <option value="completed">{{ __('Completed') }}</option>
            </flux:select>
        </div>

        <div class="mt-4">
            <flux:select wire:model.live="perPage" :label="__('Per Page')">
                @foreach ([10, 15, 25, 50] as $size)
                    <option value="{{ $size }}">{{ $size }}</option>
                @endforeach
            </flux:select>
        </div>
    </div>

    @php
        $routePrefix = auth()->user()->role === 'trainer' ? 'trainer.' : '';
    @endphp

    <div class="overflow-hidden rounded-lg border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
        <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
            <thead class="bg-neutral-50 dark:bg-neutral-900/50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Maid') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Client') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Interval') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Due Date') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Status') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-800">
                @forelse ($tasks as $task)
                    <tr class="transition-colors hover:bg-neutral-50 dark:hover:bg-neutral-900/30">
                        <td class="px-6 py-4 text-sm text-neutral-900 dark:text-white">
                            @if ($task->maid)
                                <a class="font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400" href="{{ route($routePrefix . 'maids.show', $task->maid) }}">
                                    {{ $task->maid->full_name }}
                                </a>
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300">
                            @if ($task->client)
                                <a class="font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400" href="{{ route($routePrefix . 'clients.show', $task->client) }}">
                                    {{ $task->client->contact_person }}
                                </a>
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300">
                            {{ $task->interval_months }} {{ __('months') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300">
                            {{ $task->due_date?->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <flux:badge size="sm" :color="$task->status === 'completed' ? 'green' : 'yellow'">
                                {{ ucfirst($task->status) }}
                            </flux:badge>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-sm text-neutral-500 dark:text-neutral-400">
                            {{ __('No evaluation tasks found.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $tasks->links() }}
    </div>
</div>
