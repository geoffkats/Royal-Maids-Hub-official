<div class="space-y-6">
    {{-- Header Section --}}
    <div class="flex items-start justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-[#F5B301] to-[#FFD700] text-xl font-bold text-[#3B0A45] shadow-lg">
                <flux:icon.calendar class="size-6" />
            </div>
            <div>
                <flux:heading size="xl">{{ __('Booking #') . $booking->id }}</flux:heading>
                <div class="mt-1 flex items-center gap-2 text-sm text-[#D1C4E9]">
                    <flux:badge :color="match($booking->service_tier ?? 'silver') {
                        'silver' => 'zinc',
                        'gold' => 'yellow',
                        'platinum' => 'purple',
                        default => 'zinc'
                    }" size="sm">
                        {{ ucfirst($booking->service_tier ?? 'Silver') }}
                    </flux:badge>
                    <span>•</span>
                    <span>{{ $booking->start_date?->format('M d, Y') }}</span>
                    @if($booking->end_date)
                        <span>-</span>
                        <span>{{ $booking->end_date?->format('M d, Y') }}</span>
                    @endif
                    <span>•</span>
                    <flux:badge :color="match($booking->status) {
                        'pending' => 'yellow',
                        'approved' => 'blue',
                        'confirmed' => 'emerald',
                        'active' => 'green',
                        'completed' => 'zinc',
                        'cancelled' => 'red',
                        'rejected' => 'red',
                        default => 'zinc'
                    }" size="sm">
                        {{ ucfirst($booking->status) }}
                    </flux:badge>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <flux:button as="a" :href="route('tickets.create', ['booking_id' => $booking->id, 'client_id' => $booking->client_id])" variant="primary" icon="ticket">
                {{ __('Create Ticket') }}
            </flux:button>
            <flux:button as="a" :href="route('bookings.edit', $booking)" variant="outline" icon="pencil-square">
                {{ __('Edit') }}
            </flux:button>
            <flux:button as="a" :href="route('bookings.index')" variant="ghost" icon="arrow-left">
                {{ __('Back') }}
            </flux:button>
        </div>
    </div>

    <flux:separator variant="subtle" />

    {{-- Main Content Grid --}}
    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Left Column: 4 Detail Sections --}}
        <div class="space-y-6 lg:col-span-2">
            {{-- Section 1: Contact Information --}}
            <div class="details-card">
                <div class="mb-4 flex items-center gap-2 border-b border-indigo-200 pb-3 dark:border-indigo-900/50">
                    <flux:icon.user class="size-5 text-indigo-600 dark:text-indigo-400" />
                    <flux:heading size="lg" class="text-indigo-900 dark:text-indigo-300">{{ __('Contact Information') }}</flux:heading>
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Full Name') }}</div>
                        <div class="mt-1 text-base font-semibold text-neutral-900 dark:text-white">{{ $booking->full_name }}</div>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Phone') }}</div>
                        <div class="mt-1 text-base text-neutral-900 dark:text-white">
                            <a href="tel:{{ $booking->phone }}" class="text-indigo-600 hover:underline dark:text-indigo-400">{{ $booking->phone }}</a>
                        </div>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Email') }}</div>
                        <div class="mt-1 text-base text-neutral-900 dark:text-white">
                            <a href="mailto:{{ $booking->email }}" class="text-indigo-600 hover:underline dark:text-indigo-400">{{ $booking->email }}</a>
                        </div>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Country') }}</div>
                        <div class="mt-1 text-base text-neutral-900 dark:text-white">{{ $booking->country }}</div>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('City') }}</div>
                        <div class="mt-1 text-base text-neutral-900 dark:text-white">{{ $booking->city }}</div>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Division') }}</div>
                        <div class="mt-1 text-base text-neutral-900 dark:text-white">{{ $booking->division }}</div>
                    </div>
                    <div class="md:col-span-2">
                        <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Parish') }}</div>
                        <div class="mt-1 text-base text-neutral-900 dark:text-white">{{ $booking->parish }}</div>
                    </div>
                    @if($booking->hasNationalId())
                    <div class="md:col-span-2">
                        <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('National ID / Passport') }}</div>
                        <div class="mt-2 flex items-center gap-2">
                            <a href="{{ $booking->national_id_url }}" target="_blank" download class="inline-flex items-center gap-2 rounded-lg border border-indigo-300 bg-indigo-50 px-3 py-2 text-sm font-medium text-indigo-700 hover:bg-indigo-100 dark:border-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-300 dark:hover:bg-indigo-900/50">
                                <flux:icon.arrow-down-tray class="size-4" />
                                <span>{{ __('Download') }}</span>
                            </a>
                            <a href="{{ $booking->national_id_url }}" target="_blank" class="inline-flex items-center gap-2 rounded-lg border border-neutral-300 bg-neutral-50 px-3 py-2 text-sm text-neutral-700 hover:bg-neutral-100 dark:border-neutral-600 dark:bg-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-600">
                                <flux:icon.eye class="size-4" />
                                <span>{{ __('View') }}</span>
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Section 2: Home & Environment --}}
            <div class="details-card">
                <div class="mb-4 flex items-center gap-2 border-b border-green-200 pb-3 dark:border-green-900/50">
                    <flux:icon.home class="size-5 text-green-600 dark:text-green-400" />
                    <flux:heading size="lg" class="text-green-900 dark:text-green-300">{{ __('Home & Environment') }}</flux:heading>
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Home Type') }}</div>
                        <div class="mt-1 text-base text-neutral-900 dark:text-white">{{ ucfirst($booking->home_type) }}</div>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Bedrooms') }}</div>
                        <div class="mt-1 text-base text-neutral-900 dark:text-white">{{ $booking->bedrooms }}</div>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Bathrooms') }}</div>
                        <div class="mt-1 text-base text-neutral-900 dark:text-white">{{ $booking->bathrooms }}</div>
                    </div>
                    @if($booking->outdoor_responsibilities && count($booking->outdoor_responsibilities) > 0)
                    <div class="md:col-span-2">
                        <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Outdoor Responsibilities') }}</div>
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach($booking->outdoor_responsibilities as $responsibility)
                                <flux:badge color="green" size="sm">{{ ucfirst($responsibility) }}</flux:badge>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @if($booking->appliances && count($booking->appliances) > 0)
                    <div class="md:col-span-2">
                        <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Appliances') }}</div>
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach($booking->appliances as $appliance)
                                <flux:badge color="zinc" size="sm">{{ ucfirst($appliance) }}</flux:badge>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Section 3: Household Composition --}}
            <div class="details-card">
                <div class="mb-4 flex items-center gap-2 border-b border-purple-200 pb-3 dark:border-purple-900/50">
                    <flux:icon.user-group class="size-5 text-purple-600 dark:text-purple-400" />
                    <flux:heading size="lg" class="text-purple-900 dark:text-purple-300">{{ __('Household Composition') }}</flux:heading>
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Number of Adults') }}</div>
                        <div class="mt-1 text-base text-neutral-900 dark:text-white">{{ $booking->adults }}</div>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Children') }}</div>
                        <div class="mt-1 text-base text-neutral-900 dark:text-white">{{ $booking->has_children }}</div>
                        @if($booking->has_children === 'Yes' && $booking->children_ages)
                            <div class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">Ages: {{ $booking->children_ages }}</div>
                        @endif
                    </div>
                    <div>
                        <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Elderly Persons') }}</div>
                        <div class="mt-1 text-base text-neutral-900 dark:text-white">{{ $booking->has_elderly }}</div>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Pets') }}</div>
                        <div class="mt-1 text-base text-neutral-900 dark:text-white">{{ $booking->pets }}</div>
                        @if($booking->pets === 'Yes' && $booking->pet_kind)
                            <div class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">{{ $booking->pet_kind }}</div>
                        @endif
                    </div>
                    <div class="md:col-span-2">
                        <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Preferred Language') }}</div>
                        <div class="mt-1 text-base text-neutral-900 dark:text-white">{{ ucfirst($booking->language) }}</div>
                    </div>
                </div>
            </div>

            {{-- Section 4: Job Expectations --}}
            <div class="details-card">
                <div class="mb-4 flex items-center gap-2 border-b border-amber-200 pb-3 dark:border-amber-900/50">
                    <flux:icon.clipboard-document-list class="size-5 text-amber-600 dark:text-amber-400" />
                    <flux:heading size="lg" class="text-amber-900 dark:text-amber-300">{{ __('Job Expectations') }}</flux:heading>
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Service Tier') }}</div>
                        <div class="mt-1">
                            <flux:badge :color="match($booking->service_tier ?? 'silver') {
                                'silver' => 'zinc',
                                'gold' => 'yellow',
                                'platinum' => 'purple',
                                default => 'zinc'
                            }">
                                {{ ucfirst($booking->service_tier ?? 'Silver') }}
                            </flux:badge>
                        </div>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Service Mode') }}</div>
                        <div class="mt-1 text-base text-neutral-900 dark:text-white">{{ ucfirst($booking->service_mode) }}</div>
                    </div>
                    @if($booking->work_days && count($booking->work_days) > 0)
                    <div class="md:col-span-2">
                        <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Work Days') }}</div>
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach($booking->work_days as $day)
                                <flux:badge color="amber" size="sm">{{ ucfirst($day) }}</flux:badge>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    <div>
                        <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Working Hours') }}</div>
                        <div class="mt-1 text-base text-neutral-900 dark:text-white">{{ $booking->working_hours ?? '—' }}</div>
                    </div>
                    @if($booking->responsibilities && count($booking->responsibilities) > 0)
                    <div class="md:col-span-2">
                        <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Responsibilities') }}</div>
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach($booking->responsibilities as $responsibility)
                                <flux:badge color="zinc" size="sm">{{ ucfirst($responsibility) }}</flux:badge>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    <div>
                        <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Cuisine Type') }}</div>
                        <div class="mt-1 text-base text-neutral-900 dark:text-white">{{ $booking->cuisine_type ?? '—' }}</div>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Preferred Atmosphere') }}</div>
                        <div class="mt-1 text-base text-neutral-900 dark:text-white">{{ ucfirst($booking->atmosphere) }}</div>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Manage Own Tasks') }}</div>
                        <div class="mt-1 text-base text-neutral-900 dark:text-white">{{ $booking->manage_tasks }}</div>
                    </div>
                    @if($booking->unspoken_rules)
                    <div class="md:col-span-2">
                        <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Unspoken Rules') }}</div>
                        <div class="mt-1 whitespace-pre-line text-sm text-neutral-700 dark:text-neutral-300">{{ $booking->unspoken_rules }}</div>
                    </div>
                    @endif
                    @if($booking->anything_else)
                    <div class="md:col-span-2">
                        <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Additional Information') }}</div>
                        <div class="mt-1 whitespace-pre-line text-sm text-neutral-700 dark:text-neutral-300">{{ $booking->anything_else }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right Column: Metadata & Actions --}}
        <div class="space-y-6">
            {{-- Assigned Maid Card --}}
            <div class="details-card">
                <div class="mb-4 flex items-center gap-2">
                    <flux:icon.user-circle class="size-5 text-neutral-600 dark:text-neutral-400" />
                    <flux:heading size="lg">{{ __('Assigned Maid') }}</flux:heading>
                </div>
                @if($booking->maid)
                    <div class="space-y-3">
                        <div>
                            <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Name') }}</div>
                            <div class="mt-1 text-base font-semibold text-neutral-900 dark:text-white">
                                <a href="{{ route('maids.show', $booking->maid) }}" class="text-blue-600 hover:underline dark:text-blue-400">
                                    {{ $booking->maid->full_name }}
                                </a>
                            </div>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Phone') }}</div>
                            <div class="mt-1 text-base text-neutral-900 dark:text-white">
                                <a href="tel:{{ $booking->maid->phone }}" class="text-blue-600 hover:underline dark:text-blue-400">{{ $booking->maid->phone }}</a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-6 text-center">
                        <flux:icon.user-circle class="size-12 text-neutral-400 dark:text-neutral-600" />
                        <p class="mt-2 text-sm italic text-neutral-500 dark:text-neutral-400">{{ __('No maid assigned yet') }}</p>
                    </div>
                @endif
            </div>

            {{-- Booking Dates Card --}}
            <div class="details-card">
                <div class="mb-4 flex items-center gap-2">
                    <flux:icon.calendar-days class="size-5 text-neutral-600 dark:text-neutral-400" />
                    <flux:heading size="lg">{{ __('Booking Period') }}</flux:heading>
                </div>
                <div class="space-y-3 text-sm">
                    <div>
                        <div class="text-neutral-600 dark:text-neutral-400">{{ __('Start Date') }}</div>
                        <div class="mt-1 font-semibold text-neutral-900 dark:text-white">{{ $booking->start_date?->format('M d, Y') }}</div>
                    </div>
                    @if($booking->end_date)
                    <div>
                        <div class="text-neutral-600 dark:text-neutral-400">{{ __('End Date') }}</div>
                        <div class="mt-1 font-semibold text-neutral-900 dark:text-white">{{ $booking->end_date?->format('M d, Y') }}</div>
                    </div>
                    <flux:separator variant="subtle" />
                    <div class="flex items-center justify-between">
                        <span class="text-neutral-600 dark:text-neutral-400">{{ __('Duration') }}</span>
                        <span class="font-semibold text-neutral-900 dark:text-white">{{ $booking->start_date->diffInDays($booking->end_date) }} {{ __('days') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Meta Information Card --}}
            <div class="details-card">
                <div class="mb-4 flex items-center gap-2">
                    <flux:icon.clock class="size-5 text-neutral-600 dark:text-neutral-400" />
                    <flux:heading size="lg">{{ __('Meta') }}</flux:heading>
                </div>
                <div class="space-y-3 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-neutral-600 dark:text-neutral-400">{{ __('Created') }}</span>
                        <span class="text-neutral-900 dark:text-white" title="{{ $booking->created_at->toDayDateTimeString() }}">{{ $booking->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-neutral-600 dark:text-neutral-400">{{ __('Updated') }}</span>
                        <span class="text-neutral-900 dark:text-white" title="{{ $booking->updated_at->toDayDateTimeString() }}">{{ $booking->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
