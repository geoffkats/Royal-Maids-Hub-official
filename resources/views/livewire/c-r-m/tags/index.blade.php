<div>
    <!-- Header Section with Stats -->
    <div class="bg-[#512B58] border border-[#F5B301]/30 rounded-xl p-6 text-white mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold flex items-center gap-3">
                    <x-flux::icon.tag class="w-8 h-8" />
                    {{ __('CRM Tags') }}
                </h1>
                <p class="text-indigo-100 mt-2">
                    {{ __('Manage and organize your CRM tags') }}
                </p>
            </div>
            <div class="mt-4 md:mt-0 flex items-center gap-3">
                <button wire:click="deleteSelected"
                        @disabled(empty($selectedTags))
                        wire:confirm="Are you sure you want to delete selected tags?"
                        class="inline-flex items-center gap-2 px-5 py-3 bg-red-600 border border-red-500 rounded-lg font-semibold text-white hover:bg-red-700 transition-all duration-200 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed">
                    <x-flux::icon.trash class="w-5 h-5" />
                    {{ __('Delete Selected') }}
                </button>
                <a href="{{ route('crm.tags.create') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg font-semibold text-white hover:bg-white/30 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <x-flux::icon.plus class="w-5 h-5" />
                    {{ __('Add New Tag') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('message'))
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-200 px-6 py-4 rounded-xl shadow-sm flex items-center gap-3 mb-6">
            <x-flux::icon.check-circle class="w-5 h-5 text-green-600 dark:text-green-400" />
            <span class="font-medium">{{ session('message') }}</span>
        </div>
    @endif

    <!-- Filters Section -->
    <div class="bg-gradient-to-r from-[#512B58] to-[#3B0A45] border border-[#F5B301]/30 rounded-xl p-6 mb-6 shadow-lg">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-[#D1C4E9] mb-2">
                    <x-flux::icon.magnifying-glass class="w-4 h-4 inline mr-1" />
                    {{ __('Search') }}
                </label>
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       placeholder="{{ __('Search tags...') }}"
                       class="w-full px-4 py-2 bg-white/10 border border-[#F5B301]/30 rounded-lg text-white placeholder-gray-300 focus:ring-2 focus:ring-[#F5B301] focus:border-transparent">
            </div>

            <!-- Per Page -->
            <div>
                <label class="block text-sm font-medium text-[#D1C4E9] mb-2">
                    <x-flux::icon.funnel class="w-4 h-4 inline mr-1" />
                    {{ __('Per Page') }}
                </label>
                <select wire:model.live="perPage" 
                        class="w-full px-4 py-2 bg-white/10 border border-[#F5B301]/30 rounded-lg text-white focus:ring-2 focus:ring-[#F5B301] focus:border-transparent">
                    <option value="10" class="bg-gray-800">10</option>
                    <option value="25" class="bg-gray-800">25</option>
                    <option value="50" class="bg-gray-800">50</option>
                    <option value="100" class="bg-gray-800">100</option>
                </select>
            </div>

            <!-- Sort -->
            <div>
                <label class="block text-sm font-medium text-[#D1C4E9] mb-2">
                    <x-flux::icon.chevrons-up-down class="w-4 h-4 inline mr-1" />
                    {{ __('Sort By') }}
                </label>
                <select wire:model.live="sortBy" 
                        class="w-full px-4 py-2 bg-white/10 border border-[#F5B301]/30 rounded-lg text-white focus:ring-2 focus:ring-[#F5B301] focus:border-transparent">
                    <option value="name" class="bg-gray-800">{{ __('Name') }}</option>
                    <option value="created_at" class="bg-gray-800">{{ __('Created Date') }}</option>
                </select>
            </div>
        </div>

        <!-- Additional Controls -->
        <div class="flex flex-wrap items-center justify-between mt-4 pt-4 border-t border-[#F5B301]/20">
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <label class="text-sm text-[#D1C4E9]">{{ __('Sort Direction') }}:</label>
                    <select wire:model.live="sortDirection" 
                            class="px-3 py-1 bg-white/10 border border-[#F5B301]/30 rounded text-white text-sm focus:ring-2 focus:ring-[#F5B301] focus:border-transparent">
                        <option value="asc" class="bg-gray-800">{{ __('Ascending') }}</option>
                        <option value="desc" class="bg-gray-800">{{ __('Descending') }}</option>
                    </select>
                </div>
</div>

            <div class="flex items-center gap-2">
                <button wire:click="clearSelection" 
                        class="px-4 py-2 bg-white/10 border border-[#F5B301]/30 rounded-lg text-[#D1C4E9] hover:bg-white/20 transition-all duration-200">
                    {{ __('Reset Filters') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="table-container">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-neutral-50 to-neutral-100 dark:from-neutral-700 dark:to-neutral-800">
                <tr>
                    <th class="px-4 py-4">
                        <input type="checkbox" wire:model.live="selectPage" class="rounded text-indigo-600 border-neutral-300 dark:border-neutral-600 focus:ring-indigo-500">
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-300 cursor-pointer" 
                        wire:click="sortBy('name')">
                        <x-flux::icon.tag class="w-4 h-4 inline mr-1" />
                        {{ __('Name') }}
                        @if($sortBy === 'name')
                            <x-flux::icon.arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} class="w-3 h-3 inline ml-1" />
                        @endif
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-300">
                        <x-flux::icon.user-plus class="w-4 h-4 inline mr-1" />
                        {{ __('Leads') }}
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-300">
                        <x-flux::icon.chart-bar class="w-4 h-4 inline mr-1" />
                        {{ __('Opportunities') }}
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-300 cursor-pointer" 
                        wire:click="sortBy('created_at')">
                        <x-flux::icon.calendar-days class="w-4 h-4 inline mr-1" />
                        {{ __('Created') }}
                        @if($sortBy === 'created_at')
                            <x-flux::icon.arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} class="w-3 h-3 inline ml-1" />
                        @endif
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-300">
                        <x-flux::icon.cog-6-tooth class="w-4 h-4 inline mr-1" />
                        {{ __('Actions') }}
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                @forelse ($this->tags as $tag)
                    <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50 transition-colors duration-150">
                        <td class="px-4 py-4">
                            <input type="checkbox" value="{{ $tag->id }}" wire:model.live="selectedTags" class="rounded text-indigo-600 border-neutral-300 dark:border-neutral-600 focus:ring-indigo-500">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                    {{ $tag->name }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900 dark:text-neutral-100">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                {{ $tag->leads_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900 dark:text-neutral-100">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                {{ $tag->opportunities_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500 dark:text-neutral-400">
                            {{ $tag->created_at->format('M j, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-1.5">
                                <a href="{{ route('crm.tags.edit', $tag) }}" 
                                   class="inline-flex items-center justify-center p-2 text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-md transition-colors duration-150"
                                   title="Edit" aria-label="Edit">
                                    <x-flux::icon.pencil class="w-4 h-4" />
                                    <span class="sr-only">{{ __('Edit') }}</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-neutral-500 dark:text-neutral-400">
                            {{ __('No tags found.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $this->tags->links() }}
    </div>
</div>