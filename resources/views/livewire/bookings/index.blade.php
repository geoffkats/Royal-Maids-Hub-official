<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <flux:heading size="xl">{{ __('Bookings') }}</flux:heading>
            <flux:subheading class="mt-1">{{ __('Manage all booking records') }}</flux:subheading>
        </div>

        @can('create', App\Models\Booking::class)
            <flux:button as="a" href="{{ route('bookings.create') }}" variant="primary" icon="plus">
                {{ __('New Booking') }}
            </flux:button>
        @endcan
    </div>

    <flux:separator variant="subtle" />

    <!-- Filter Section -->
    <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
        <div class="flex items-center gap-2 mb-4">
            <flux:icon.funnel class="size-5 text-[#F5B301]" />
            <h3 class="text-lg font-semibold text-white">{{ __('Filter & Search Bookings') }}</h3>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Search Input -->
            <div class="lg:col-span-2">
                <flux:input 
                    wire:model.live.debounce.400ms="search" 
                    :label="__('Search Bookings')"
                    placeholder="{{ __('Client, maid, phone, location...') }}"
                    icon="magnifying-glass"
                    class="filter-input"
                />
            </div>

            <!-- Status Filter -->
            <div>
                <flux:select wire:model.live="status" :label="__('Booking Status')" class="filter-select">
                    <option value="">{{ __('All Statuses') }}</option>
                    @foreach ($statusOptions as $opt)
                        <option value="{{ $opt }}">{{ ucfirst($opt) }}</option>
                    @endforeach
                </flux:select>
            </div>

            <!-- Type Filter -->
            <div>
                <flux:select wire:model.live="booking_type" :label="__('Booking Type')" class="filter-select">
                    <option value="">{{ __('All Types') }}</option>
                    @foreach ($typeOptions as $opt)
                        <option value="{{ $opt }}">{{ ucwords(str_replace('-', ' ', $opt)) }}</option>
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
                    <option value="created_at">{{ __('Date Created') }}</option>
                    <option value="start_date">{{ __('Start Date') }}</option>
                    <option value="full_name">{{ __('Client Name') }}</option>
                    <option value="status">{{ __('Status') }}</option>
                    <option value="booking_type">{{ __('Type') }}</option>
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
                <span>{{ __('Showing') }} {{ $bookings->count() }} {{ __('of') }} {{ $bookings->total() }} {{ __('bookings') }}</span>
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
                    wire:click="exportBookings" 
                    variant="primary" 
                    size="sm"
                    icon="arrow-down-tray"
                >
                    {{ __('Export') }}
                </flux:button>
            </div>
        </div>
    </div>

    @if (session('success'))
        <flux:callout variant="success" icon="check-circle" class="!border-green-200 !bg-green-50 dark:!border-green-900 dark:!bg-green-950">
            {{ session('success') }}
        </flux:callout>
    @endif

    <div class="overflow-hidden rounded-lg border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
        <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
            <thead class="bg-neutral-50 dark:bg-neutral-900/50">
                <tr>
                    <th class="px-3 md:px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('ID') }}</th>
                    <th class="px-3 md:px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Contact') }}</th>
                    <th class="px-3 md:px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300 hidden md:table-cell">{{ __('Maid') }}</th>
                    <th class="px-3 md:px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300 hidden lg:table-cell">{{ __('Location') }}</th>
                    <th class="px-3 md:px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300 hidden lg:table-cell">{{ __('Service') }}</th>
                    <th class="px-3 md:px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Dates') }}</th>
                    <th class="px-3 md:px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Status') }}</th>
                    <th class="px-3 md:px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-800">
                @forelse ($bookings as $booking)
                    <tr class="transition-colors hover:bg-neutral-50 dark:hover:bg-neutral-900/30">
                        <td class="whitespace-nowrap px-3 md:px-6 py-4 text-sm font-medium text-neutral-900 dark:text-white">
                            #{{ $booking->id }}
                        </td>
                        <td class="whitespace-nowrap px-3 md:px-6 py-4 text-sm">
                            <div class="font-medium text-neutral-900 dark:text-white">{{ $booking->full_name }}</div>
                            <div class="text-xs text-neutral-500 dark:text-neutral-400">{{ $booking->phone }}</div>
                        </td>
                        <td class="whitespace-nowrap px-3 md:px-6 py-4 text-sm hidden md:table-cell">
                            @if($booking->maid)
                                <div class="text-neutral-900 dark:text-white">{{ $booking->maid->full_name }}</div>
                            @else
                                <span class="text-neutral-500 dark:text-neutral-400 italic">Pending assignment</span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-3 md:px-6 py-4 text-sm hidden lg:table-cell">
                            <div class="text-neutral-700 dark:text-neutral-300">{{ $booking->city }}</div>
                            <div class="text-xs text-neutral-500 dark:text-neutral-400">{{ $booking->division }}</div>
                        </td>
                        <td class="whitespace-nowrap px-3 md:px-6 py-4 hidden lg:table-cell">
                            @if($booking->service_tier)
                                <flux:badge size="sm" :color="match($booking->service_tier) {
                                    'Silver' => 'zinc',
                                    'Gold' => 'yellow',
                                    'Platinum' => 'purple',
                                    default => 'neutral'
                                }">
                                    {{ $booking->service_tier }}
                                </flux:badge>
                                <div class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">{{ $booking->service_mode }}</div>
                            @else
                                <span class="text-neutral-500 dark:text-neutral-400">—</span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-3 md:px-6 py-4 text-sm">
                            <div class="text-neutral-700 dark:text-neutral-300">{{ $booking->start_date?->format('M d, Y') ?? 'No start date' }}</div>
                            @if($booking->end_date)
                                <div class="text-xs text-neutral-500 dark:text-neutral-400">to {{ $booking->end_date->format('M d, Y') }}</div>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-3 md:px-6 py-4">
                            <div class="relative group">
                                <button 
                                    wire:click="updateStatus({{ $booking->id }}, '{{ $booking->status === 'pending' ? 'confirmed' : ($booking->status === 'confirmed' ? 'active' : ($booking->status === 'active' ? 'completed' : 'pending')) }}')"
                                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-medium transition-all duration-200 hover:scale-105 cursor-pointer group-hover:shadow-md
                                           {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200 dark:bg-yellow-900/20 dark:text-yellow-300' : 
                                              ($booking->status === 'confirmed' ? 'bg-blue-100 text-blue-800 hover:bg-blue-200 dark:bg-blue-900/20 dark:text-blue-300' : 
                                               ($booking->status === 'active' ? 'bg-green-100 text-green-800 hover:bg-green-200 dark:bg-green-900/20 dark:text-green-300' : 
                                                ($booking->status === 'completed' ? 'bg-gray-100 text-gray-800 hover:bg-gray-200 dark:bg-gray-900/20 dark:text-gray-300' : 
                                                 'bg-red-100 text-red-800 hover:bg-red-200 dark:bg-red-900/20 dark:text-red-300'))) }}"
                                    title="Click to cycle through statuses"
                                >
                                    <div class="w-2 h-2 rounded-full 
                                               {{ $booking->status === 'pending' ? 'bg-yellow-500' : 
                                                  ($booking->status === 'confirmed' ? 'bg-blue-500' : 
                                                   ($booking->status === 'active' ? 'bg-green-500' : 
                                                    ($booking->status === 'completed' ? 'bg-gray-500' : 'bg-red-500'))) }}">
                                    </div>
                                    {{ ucfirst($booking->status) }}
                                    <x-flux::icon.arrow-path class="w-3 h-3 opacity-60 group-hover:opacity-100 transition-opacity" />
                                </button>
                                
                                <!-- Status change hint -->
                                <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-slate-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-10">
                                    Click to change to: {{ $booking->status === 'pending' ? 'Confirmed' : ($booking->status === 'confirmed' ? 'Active' : ($booking->status === 'active' ? 'Completed' : 'Pending')) }}
                                </div>
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-3 md:px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @can('view', $booking)
                                    <flux:button
                                        as="a"
                                        :href="route('bookings.show', $booking)"
                                        variant="ghost"
                                        size="sm"
                                        icon="eye"
                                        title="{{ __('View') }}"
                                    ></flux:button>
                                @endcan

                                @can('update', $booking)
                                    <flux:button
                                        as="a"
                                        :href="route('bookings.edit', $booking)"
                                        variant="ghost"
                                        size="sm"
                                        icon="pencil-square"
                                        title="{{ __('Edit') }}"
                                    ></flux:button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center space-y-3">
                                <flux:icon.calendar class="size-12 text-neutral-400 dark:text-neutral-600" />
                                <div class="text-neutral-500 dark:text-neutral-400">{{ __('No bookings found') }}</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $bookings->onEachSide(1)->links() }}
    </div>
</div>
