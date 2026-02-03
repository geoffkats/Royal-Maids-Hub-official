    <div class="space-y-6">
    @php
        $prefix = auth()->user()->role === 'trainer' ? 'trainer.' : '';
    @endphp

        <!-- Success Message -->
        @if (session('message'))
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-200 px-6 py-4 rounded-xl shadow-sm flex items-center gap-3">
                <x-flux::icon.check-circle class="w-5 h-5 text-green-600 dark:text-green-400" />
                <span class="font-medium">{{ session('message') }}</span>
            </div>
        @endif

        <!-- Header Section with Stats -->
        <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-xl p-6 text-white">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                    <h1 class="text-3xl font-bold flex items-center gap-3">
                        <x-flux::icon.users class="w-8 h-8" />
                        @if(auth()->user()->role === 'trainer')
                            {{ __('My Trainees') }}
                        @else
                            {{ __('Maids Management') }}
                        @endif
                    </h1>
                    <p class="text-indigo-100 mt-2">
                        @if(auth()->user()->role === 'trainer')
                            {{ __('View and manage your assigned trainees') }}
                        @else
                            {{ __('Manage, search and filter your domestic workers') }}
                        @endif
                    </p>
            </div>
                <div class="mt-4 md:mt-0 flex items-center gap-3">
                    <a href="{{ route('maids.export.pdf') }}"
                       class="inline-flex items-center gap-2 px-5 py-3 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg font-semibold text-white hover:bg-white/30 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <x-flux::icon.document-arrow-down class="w-5 h-5" />
                        {{ __('Export PDF') }}
                    </a>
                    @if(auth()->user()->role === 'admin')
                    <button wire:click="deleteSelected"
                        @disabled(empty($selected))
                                wire:confirm="Are you sure you want to delete selected maids?"
                                class="inline-flex items-center gap-2 px-5 py-3 bg-red-600 border border-red-500 rounded-lg font-semibold text-white hover:bg-red-700 transition-all duration-200 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed">
                            <x-flux::icon.trash class="w-5 h-5" />
                            {{ __('Bulk Delete') }}
                        </button>
                    @endif
                    @if(auth()->user()->role === 'admin')
                    <a href="{{ route('maids.create') }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg font-semibold text-white hover:bg-white/30 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <x-flux::icon.plus class="w-5 h-5" />
                        {{ __('Add New Maid') }}
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-neutral-800 rounded-xl p-6 shadow-lg border border-neutral-200 dark:border-neutral-700">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 dark:bg-green-900/20">
                        <x-flux::icon.check-circle class="w-6 h-6 text-green-600 dark:text-green-400" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Available') }}</p>
                        <p class="text-2xl font-bold text-neutral-900 dark:text-white">{{ $allMaids->where('status', 'available')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-neutral-800 rounded-xl p-6 shadow-lg border border-neutral-200 dark:border-neutral-700">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900/20">
                        <x-flux::icon.academic-cap class="w-6 h-6 text-yellow-600 dark:text-yellow-400" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('In Training') }}</p>
                        <p class="text-2xl font-bold text-neutral-900 dark:text-white">{{ $allMaids->where('status', 'in-training')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-neutral-800 rounded-xl p-6 shadow-lg border border-neutral-200 dark:border-neutral-700">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900/20">
                        <x-flux::icon.briefcase class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Deployed') }}</p>
                        <p class="text-2xl font-bold text-neutral-900 dark:text-white">{{ $allMaids->where('status', 'deployed')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-neutral-800 rounded-xl p-6 shadow-lg border border-neutral-200 dark:border-neutral-700">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900/20">
                        <x-flux::icon.users class="w-6 h-6 text-purple-600 dark:text-purple-400" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Total Maids') }}</p>
                        <p class="text-2xl font-bold text-neutral-900 dark:text-white">{{ $allMaids->count() }}</p>
                    </div>
                </div>
            </div>
                </div>

        <!-- Filter Section -->
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <div class="flex items-center gap-2 mb-4">
                <flux:icon.funnel class="size-5 text-[#F5B301]" />
                <h3 class="text-lg font-semibold text-white">{{ __('Filter & Search Maids') }}</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search Input -->
                <div class="lg:col-span-2">
                    <flux:input 
                        wire:model.live.debounce.400ms="search" 
                        :label="__('Search Maids')"
                        placeholder="{{ __('Name, code, phone, email...') }}"
                        icon="magnifying-glass"
                        class="filter-input"
                    />
                </div>

                <!-- Status Filter -->
                <div>
                    <flux:select wire:model.live="status" :label="__('Employment Status')" class="filter-select">
                        <option value="">{{ __('All Statuses') }}</option>
                        @foreach ($statusOptions as $opt)
                            <option value="{{ $opt }}">{{ ucfirst(str_replace('-', ' ', $opt)) }}</option>
                        @endforeach
                    </flux:select>
                </div>

                <!-- Role Filter -->
                <div>
                    <flux:select wire:model.live="role" :label="__('Job Role')" class="filter-select">
                        <option value="">{{ __('All Roles') }}</option>
                        @foreach ($roleOptions as $opt)
                            <option value="{{ $opt }}">{{ ucfirst(str_replace('_', ' ', $opt)) }}</option>
                        @endforeach
                    </flux:select>
                </div>
            </div>

            <!-- Additional Filters Row -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                <!-- Per Page -->
                <div>
                    <flux:select wire:model.live="perPage" :label="__('Results Per Page')" class="filter-select">
                        @foreach ([10,15,25,50] as $n)
                            <option value="{{ $n }}">{{ $n }} {{ __('results') }}</option>
                        @endforeach
                    </flux:select>
                </div>

                <!-- Sort By -->
                <div>
                    <flux:select wire:model.live="sortBy" :label="__('Sort By')" class="filter-select">
                        <option value="created_at">{{ __('Date Added') }}</option>
                        <option value="full_name">{{ __('Name') }}</option>
                        <option value="maid_code">{{ __('Maid Code') }}</option>
                        <option value="status">{{ __('Status') }}</option>
                        <option value="role">{{ __('Role') }}</option>
                    </flux:select>
                </div>

                <!-- Sort Direction -->
                <div>
                    <flux:select wire:model.live="sortDirection" :label="__('Order')" class="filter-select">
                        <option value="desc">{{ __('Newest First') }}</option>
                        <option value="asc">{{ __('Oldest First') }}</option>
                    </flux:select>
                </div>
            </div>

            <!-- Filter Actions -->
            <div class="flex items-center justify-between mt-6 pt-4 border-t border-[#F5B301]/20">
                <div class="flex items-center gap-2 text-sm text-[#D1C4E9]">
                    <flux:icon.information-circle class="size-4" />
                    <span>{{ __('Showing') }} {{ $maids->count() }} {{ __('of') }} {{ $maids->total() }} {{ __('maids') }}</span>
                </div>
                
                <div class="flex items-center gap-2">
                    <flux:button 
                        wire:click="resetFilters" 
                        variant="outline" 
                        size="sm"
                        icon="arrow-path"
                    >
                        {{ __('Reset Filters') }}
                    </flux:button>
                    
                    <flux:button 
                        as="a"
                        :href="route($prefix . 'maids.export.pdf')"
                        variant="primary" 
                        size="sm"
                        icon="arrow-down-tray"
                    >
                        {{ __('Export PDF') }}
                    </flux:button>
                </div>
            </div>
        </div>

        <!-- Maids Table -->
        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-lg border border-neutral-200 dark:border-neutral-700 overflow-hidden">
            <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                    <thead class="bg-gradient-to-r from-neutral-50 to-neutral-100 dark:from-neutral-700 dark:to-neutral-800">
                        <tr>
                        @if(auth()->user()->role === 'admin')
                        <th class="px-4 py-4">
                            <input type="checkbox" wire:model.live="selectPage" class="rounded text-indigo-600 border-neutral-300 dark:border-neutral-600 focus:ring-indigo-500">
                        </th>
                        @endif
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-300">
                                <x-flux::icon.identification class="w-4 h-4 inline mr-1" />
                                {{ __('Code') }}
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-300">
                                <x-flux::icon.user class="w-4 h-4 inline mr-1" />
                                {{ __('Name') }}
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-300">
                                <x-flux::icon.phone class="w-4 h-4 inline mr-1" />
                                {{ __('Phone') }}
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-300">
                                <x-flux::icon.signal class="w-4 h-4 inline mr-1" />
                                {{ __('Status') }}
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-300">
                                <x-flux::icon.briefcase class="w-4 h-4 inline mr-1" />
                                {{ __('Role') }}
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-300">
                                <x-flux::icon.clock class="w-4 h-4 inline mr-1" />
                                {{ __('Work Type') }}
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-300">
                                <x-flux::icon.calendar class="w-4 h-4 inline mr-1" />
                                {{ __('Arrived') }}
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-300">
                                <x-flux::icon.cog-6-tooth class="w-4 h-4 inline mr-1" />
                                {{ __('Actions') }}
                            </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                    @forelse ($maids as $maid)
                            <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50 transition-colors duration-150">
                                @if(auth()->user()->role === 'admin')
                                <td class="px-4 py-4">
                                    <input type="checkbox" value="{{ $maid->id }}" wire:model.live="selected" class="rounded text-indigo-600 border-neutral-300 dark:border-neutral-600 focus:ring-indigo-500">
                                </td>
                                @endif
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/20 dark:text-indigo-300">
                                            {{ $maid->maid_code ?? '—' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if($maid->profile_image)
                                                <img class="h-10 w-10 rounded-full object-cover" src="{{ Storage::url($maid->profile_image) }}" alt="{{ $maid->full_name }}">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center">
                                                    <x-flux::icon.user class="w-5 h-5 text-white" />
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-neutral-900 dark:text-white">
                                                <a href="{{ route($prefix . 'maids.show', $maid) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 hover:underline transition-colors duration-150">
                                                    {{ $maid->full_name }}
                                                </a>
                                            </div>
                                            <div class="text-sm text-neutral-500 dark:text-neutral-400">{{ $maid->nationality ?? '—' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-700 dark:text-neutral-300">
                                    <div class="flex items-center">
                                        <x-flux::icon.phone class="w-4 h-4 mr-2 text-neutral-400" />
                                        {{ $maid->phone }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'available' => 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300',
                                            'in-training' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300',
                                            'booked' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300',
                                            'deployed' => 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300',
                                            'absconded' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/20 dark:text-orange-300',
                                            'terminated' => 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-300',
                                            'on-leave' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/20 dark:text-purple-300',
                                        ];
                                        $colorClass = $statusColors[$maid->status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-300';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClass }}">
                                        {{ ucfirst(str_replace('-', ' ', $maid->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-700 dark:text-neutral-300">
                                    <div class="flex items-center">
                                        <x-flux::icon.briefcase class="w-4 h-4 mr-2 text-neutral-400" />
                                        {{ ucfirst(str_replace('_', ' ', $maid->role)) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-700 dark:text-neutral-300">
                                    @if($maid->work_status)
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-neutral-100 text-neutral-800 dark:bg-neutral-700 dark:text-neutral-200">
                                            {{ ucfirst(str_replace('-', ' ', $maid->work_status)) }}
                                        </span>
                                    @else
                                        <span class="text-neutral-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-700 dark:text-neutral-300">
                                    <div class="flex items-center">
                                        <x-flux::icon.calendar class="w-4 h-4 mr-2 text-neutral-400" />
                                        {{ optional($maid->date_of_arrival)->format('M d, Y') ?: '—' }}
                                    </div>
                            </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-1.5">
                                    <a href="{{ route($prefix . 'maids.show', $maid) }}" 
                                       class="inline-flex items-center justify-center p-2 text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-md transition-colors duration-150"
                                       title="View" aria-label="View">
                                        <x-flux::icon.eye class="w-4 h-4" />
                                        <span class="sr-only">{{ __('View') }}</span>
                                    </a>
                                    @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('maids.edit', $maid) }}" 
                                       class="inline-flex items-center justify-center p-2 text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-md transition-colors duration-150"
                                       title="Edit" aria-label="Edit">
                                        <x-flux::icon.pencil class="w-4 h-4" />
                                        <span class="sr-only">{{ __('Edit') }}</span>
                                    </a>
                                    <button wire:click="delete({{ $maid->id }})" 
                                            wire:confirm="Are you sure you want to delete this maid?"
                                            class="inline-flex items-center justify-center p-2 text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md transition-colors duration-150"
                                            title="Delete" aria-label="Delete">
                                        <x-flux::icon.trash class="w-4 h-4" />
                                        <span class="sr-only">{{ __('Delete') }}</span>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                                <td colspan="9" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <x-flux::icon.user-group class="w-12 h-12 text-neutral-400 mb-4" />
                                        <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-2">{{ __('No maids found') }}</h3>
                                        <p class="text-neutral-500 dark:text-neutral-400 mb-4">{{ __('Get started by adding your first maid.') }}</p>
                                        <a href="{{ route('maids.create') }}" 
                                           class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors duration-150">
                                            <x-flux::icon.plus class="w-4 h-4 mr-2" />
                                            {{ __('Add New Maid') }}
                                        </a>
                                    </div>
                                </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($maids->hasPages())
            <div class="flex justify-center">
                <div class="bg-white dark:bg-neutral-800 rounded-lg shadow-lg border border-neutral-200 dark:border-neutral-700 p-4">
            {{ $maids->onEachSide(1)->links() }}
        </div>
    </div>
        @endif
    </div>