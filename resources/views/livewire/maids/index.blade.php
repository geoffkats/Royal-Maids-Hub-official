<x-layouts.app :title="__('Maids')">
    <div class="space-y-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">{{ __('Maids') }}</h1>
                <p class="text-neutral-600 dark:text-neutral-400">{{ __('Manage, search and filter maids') }}</p>
            </div>

            <div class="flex flex-wrap items-end gap-3">
                <div>
                    <label class="block text-sm text-neutral-600 dark:text-neutral-400 mb-1">{{ __('Search') }}</label>
                    <input type="text" wire:model.live.debounce.400ms="search" placeholder="{{ __('Name, code, or phone...') }}" class="w-64 rounded-md border-neutral-300 bg-white text-neutral-900 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white" />
                </div>

                <div>
                    <label class="block text-sm text-neutral-600 dark:text-neutral-400 mb-1">{{ __('Status') }}</label>
                    <select wire:model.live="status" class="rounded-md border-neutral-300 bg-white text-neutral-900 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white">
                        <option value="">{{ __('All') }}</option>
                        @foreach ($statusOptions as $opt)
                            <option value="{{ $opt }}">{{ ucfirst(str_replace('-', ' ', $opt)) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-neutral-600 dark:text-neutral-400 mb-1">{{ __('Role') }}</label>
                    <select wire:model.live="role" class="rounded-md border-neutral-300 bg-white text-neutral-900 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white">
                        <option value="">{{ __('All') }}</option>
                        @foreach ($roleOptions as $opt)
                            <option value="{{ $opt }}">{{ ucfirst(str_replace('_', ' ', $opt)) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-neutral-600 dark:text-neutral-400 mb-1">{{ __('Per Page') }}</label>
                    <select wire:model.live="perPage" class="rounded-md border-neutral-300 bg-white text-neutral-900 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white">
                        @foreach ([10,15,25,50] as $n)
                            <option value="{{ $n }}">{{ $n }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-lg border border-neutral-200 bg-white shadow dark:border-neutral-700 dark:bg-neutral-800">
            <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                <thead class="bg-neutral-50 dark:bg-neutral-900">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-600 dark:text-neutral-400">{{ __('Code') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-600 dark:text-neutral-400">{{ __('Name') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-600 dark:text-neutral-400">{{ __('Phone') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-600 dark:text-neutral-400">{{ __('Status') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-600 dark:text-neutral-400">{{ __('Role') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-600 dark:text-neutral-400">{{ __('Work') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-neutral-600 dark:text-neutral-400">{{ __('Arrived') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                    @forelse ($maids as $maid)
                        <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-900/50">
                            <td class="whitespace-nowrap px-4 py-3 text-sm text-neutral-900 dark:text-white">{{ $maid->maid_code ?? '—' }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-sm text-neutral-900 dark:text-white">{{ $maid->full_name }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-sm text-neutral-700 dark:text-neutral-300">{{ $maid->phone }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-xs">
                                <span class="rounded-full bg-neutral-100 px-2 py-1 text-neutral-700 dark:bg-neutral-900 dark:text-neutral-300">{{ ucfirst(str_replace('-', ' ', $maid->status)) }}</span>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-sm text-neutral-700 dark:text-neutral-300">{{ ucfirst(str_replace('_', ' ', $maid->role)) }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-sm text-neutral-700 dark:text-neutral-300">{{ $maid->work_status ? ucfirst(str_replace('-', ' ', $maid->work_status)) : '—' }}</td>
                            <td class="whitespace-nowrap px-4 py-3 text-sm text-neutral-700 dark:text-neutral-300">{{ optional($maid->date_of_arrival)->format('Y-m-d') ?: '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-neutral-500 dark:text-neutral-400">{{ __('No maids found') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $maids->onEachSide(1)->links() }}
        </div>
    </div>
</x-layouts.app>
