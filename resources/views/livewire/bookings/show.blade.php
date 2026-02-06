<div class="min-h-screen bg-gradient-to-br from-[#512B58] via-[#3B0A45] to-[#2D1B69] py-8">
    @php
        $prefix = auth()->user()->role === 'trainer' ? 'trainer.' : '';
    @endphp
    <div class="container mx-auto px-4">
        <div class="max-w-7xl mx-auto space-y-6">
            {{-- Header Section --}}
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-[#F5B301] to-[#FFD700] shadow-lg">
                        <flux:icon.calendar class="size-8 text-[#512B58]" />
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold text-white mb-2">{{ __('Booking #') . $booking->id }}</h1>
                        <div class="flex items-center gap-2 text-sm text-[#D1C4E9]">
                            <flux:badge :color="match(strtolower($booking->service_tier ?? 'silver')) {
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
                    <flux:button as="a" :href="route($prefix . 'tickets.create', ['booking_id' => $booking->id, 'client_id' => $booking->client_id])" variant="primary" icon="ticket" class="bg-gradient-to-r from-[#F5B301] to-[#FFD700] text-[#512B58] border-none hover:from-[#FFD700] hover:to-[#F5B301]">
                        {{ __('Create Ticket') }}
                    </flux:button>
                    <flux:button wire:click="sendClientEvaluationLink" variant="outline" icon="paper-airplane" class="border-white/30 text-white hover:bg-white/10">
                        {{ __('Send Evaluation Link') }}
                    </flux:button>
                    <flux:button as="a" :href="route($prefix . 'bookings.edit', $booking)" variant="outline" icon="pencil-square" class="border-white/30 text-white hover:bg-white/10">
                        {{ __('Edit') }}
                    </flux:button>
                    <flux:button as="a" :href="route($prefix . 'bookings.index')" variant="ghost" icon="arrow-left" class="text-white border-white/30 hover:bg-white/10">
                        {{ __('Back') }}
                    </flux:button>
                </div>
            </div>

            @if (session('success'))
                <flux:callout variant="success" class="mt-4">
                    {{ session('success') }}
                </flux:callout>
            @endif
            @if (session('error'))
                <flux:callout variant="danger" class="mt-4">
                    {{ session('error') }}
                </flux:callout>
            @endif

            {{-- Main Content Grid --}}
            <div class="grid gap-6 lg:grid-cols-3">
                {{-- Left Column: Detail Sections --}}
                <div class="space-y-6 lg:col-span-2">
                    {{-- Section 1: Contact Information --}}
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl border border-[#F5B301]/30 shadow-2xl p-8">
                        <div class="mb-6 flex items-center gap-3 border-b border-[#F5B301]/30 pb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-xl flex items-center justify-center">
                                <flux:icon.user class="size-6 text-[#512B58]" />
                            </div>
                            <h2 class="text-2xl font-bold text-white">{{ __('Contact Information') }}</h2>
                        </div>
                        <div class="grid gap-6 md:grid-cols-2">
                            <div>
                                <div class="text-sm font-semibold text-[#D1C4E9] mb-2">{{ __('Full Name') }}</div>
                                <div class="text-lg font-semibold text-white">{{ $booking->full_name }}</div>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-[#D1C4E9] mb-2">{{ __('Phone') }}</div>
                                <div class="text-lg text-white">
                                    <a href="tel:{{ $booking->phone }}" class="text-[#F5B301] hover:text-[#FFD700] hover:underline">{{ $booking->phone }}</a>
                                </div>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-[#D1C4E9] mb-2">{{ __('Email') }}</div>
                                <div class="text-lg text-white">
                                    <a href="mailto:{{ $booking->email }}" class="text-[#F5B301] hover:text-[#FFD700] hover:underline">{{ $booking->email }}</a>
                                </div>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-[#D1C4E9] mb-2">{{ __('Country') }}</div>
                                <div class="text-lg text-white">{{ $booking->country }}</div>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-[#D1C4E9] mb-2">{{ __('City') }}</div>
                                <div class="text-lg text-white">{{ $booking->city }}</div>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-[#D1C4E9] mb-2">{{ __('Division') }}</div>
                                <div class="text-lg text-white">{{ $booking->division }}</div>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-[#D1C4E9] mb-2">{{ __('Parish') }}</div>
                                <div class="text-lg text-white">{{ $booking->parish }}</div>
                            </div>
                            @if($booking->hasNationalId())
                            <div class="md:col-span-2">
                                <div class="text-sm font-semibold text-[#D1C4E9] mb-2">{{ __('National ID / Passport') }}</div>
                                <div class="flex items-center gap-2 mt-2">
                                    <a href="{{ $booking->national_id_url }}" target="_blank" download class="inline-flex items-center gap-2 rounded-xl border border-[#F5B301]/30 bg-[#F5B301]/10 px-4 py-2 text-sm font-medium text-[#F5B301] hover:bg-[#F5B301]/20 transition-all">
                                        <flux:icon.arrow-down-tray class="size-4" />
                                        <span>{{ __('Download') }}</span>
                                    </a>
                                    <a href="{{ $booking->national_id_url }}" target="_blank" class="inline-flex items-center gap-2 rounded-xl border border-white/20 bg-white/5 px-4 py-2 text-sm text-white hover:bg-white/10 transition-all">
                                        <flux:icon.eye class="size-4" />
                                        <span>{{ __('View') }}</span>
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Section 2: Home Details --}}
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl border border-[#F5B301]/30 shadow-2xl p-8">
                        <div class="mb-6 flex items-center gap-3 border-b border-[#F5B301]/30 pb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-xl flex items-center justify-center">
                                <flux:icon.home class="size-6 text-[#512B58]" />
                            </div>
                            <h2 class="text-2xl font-bold text-white">{{ __('Home Details') }}</h2>
                        </div>
                        <div class="grid gap-6 md:grid-cols-2">
                            @if($booking->parish && $booking->parish !== $booking->division)
                            <div>
                                <div class="text-sm font-semibold text-[#D1C4E9] mb-2">{{ __('Village') }}</div>
                                <div class="text-lg text-white">{{ $booking->parish }}</div>
                            </div>
                            @endif
                            <div>
                                <div class="text-sm font-semibold text-[#D1C4E9] mb-2">{{ __('House Type') }}</div>
                                <div class="text-lg text-white">{{ ucfirst($booking->home_type ?? '—') }}</div>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-[#D1C4E9] mb-2">{{ __('Number of Rooms') }}</div>
                                <div class="text-lg text-white">{{ $booking->bedrooms ?? '—' }}</div>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-[#D1C4E9] mb-2">{{ __('Bedrooms') }}</div>
                                <div class="text-lg text-white">{{ $booking->bedrooms ?? '—' }}</div>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-[#D1C4E9] mb-2">{{ __('Bathrooms/Toilets') }}</div>
                                <div class="text-lg text-white">{{ $booking->bathrooms ?? '—' }}</div>
                            </div>
                            @if($booking->outdoor_responsibilities && is_array($booking->outdoor_responsibilities) && count($booking->outdoor_responsibilities) > 0)
                            <div class="md:col-span-2">
                                <div class="text-sm font-semibold text-[#D1C4E9] mb-3">{{ __('Outdoor Responsibilities') }}</div>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($booking->outdoor_responsibilities as $responsibility)
                                        <span class="inline-flex items-center px-3 py-1 rounded-xl bg-[#F5B301]/20 border border-[#F5B301]/30 text-[#F5B301] text-sm font-medium">{{ $responsibility }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            @if($booking->appliances && is_array($booking->appliances) && count($booking->appliances) > 0)
                            <div class="md:col-span-2">
                                <div class="text-sm font-semibold text-[#D1C4E9] mb-3">{{ __('Appliances') }}</div>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($booking->appliances as $appliance)
                                        <span class="inline-flex items-center px-3 py-1 rounded-xl bg-white/10 border border-white/20 text-white text-sm font-medium">{{ $appliance }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            @if($booking->notes && !empty(trim($booking->notes)))
                            <div class="md:col-span-2">
                                <div class="text-sm font-semibold text-[#D1C4E9] mb-2">{{ __('Special Requirements') }}</div>
                                <div class="text-base text-white whitespace-pre-line bg-white/5 rounded-xl p-4 border border-white/10">{{ $booking->notes }}</div>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Section 3: Household Information --}}
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl border border-[#F5B301]/30 shadow-2xl p-8">
                        <div class="mb-6 flex items-center gap-3 border-b border-[#F5B301]/30 pb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-xl flex items-center justify-center">
                                <flux:icon.user-group class="size-6 text-[#512B58]" />
                            </div>
                            <h2 class="text-2xl font-bold text-white">{{ __('Household Information') }}</h2>
                        </div>
                        <div class="grid gap-6 md:grid-cols-2">
                            <div>
                                <div class="text-sm font-semibold text-[#D1C4E9] mb-2">{{ __('Family Size') }}</div>
                                <div class="text-lg text-white">{{ is_numeric($booking->family_size) ? $booking->family_size : ucfirst($booking->family_size ?? '—') }}</div>
                            </div>
                            @if($booking->has_children === 'Yes' || ($booking->children_ages && !empty($booking->children_ages)))
                            <div>
                                <div class="text-sm font-semibold text-[#D1C4E9] mb-2">{{ __('Number of Children') }}</div>
                                <div class="text-lg text-white">
                                    @if($booking->children_ages)
                                        @php
                                            $childrenCount = is_array($booking->children_ages) ? count($booking->children_ages) : (substr_count($booking->children_ages, ',') + 1);
                                        @endphp
                                        {{ $childrenCount }}
                                    @elseif($booking->has_children === 'Yes')
                                        1+
                                    @else
                                        —
                                    @endif
                                </div>
                            </div>
                            @endif
                            @if($booking->has_elderly === 'Yes')
                            <div>
                                <div class="text-sm font-semibold text-[#D1C4E9] mb-2">{{ __('Number of Elderly (65+)') }}</div>
                                <div class="text-lg text-white">1+</div>
                            </div>
                            @endif
                            <div>
                                <div class="text-sm font-semibold text-[#D1C4E9] mb-2">{{ __('Pets') }}</div>
                                <div class="text-lg text-white">
                                    @if($booking->pets && strtolower($booking->pets) !== 'none' && strtolower($booking->pets) !== 'no')
                                        {{ ucfirst($booking->pets) }}
                                        @if($booking->pet_kind)
                                            <span class="text-sm text-[#D1C4E9]">({{ $booking->pet_kind }})</span>
                                        @endif
                                    @else
                                        No pets
                                    @endif
                                </div>
                            </div>
                            @if($booking->anything_else && !empty(trim($booking->anything_else)))
                            <div class="md:col-span-2">
                                <div class="text-sm font-semibold text-[#D1C4E9] mb-2">{{ __('Special Needs') }}</div>
                                <div class="text-base text-white whitespace-pre-line bg-white/5 rounded-xl p-4 border border-white/10">{{ $booking->anything_else }}</div>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Section 4: Service Expectations --}}
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl border border-[#F5B301]/30 shadow-2xl p-8">
                        <div class="mb-6 flex items-center gap-3 border-b border-[#F5B301]/30 pb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-xl flex items-center justify-center">
                                <flux:icon.clipboard-document-list class="size-6 text-[#512B58]" />
                            </div>
                            <h2 class="text-2xl font-bold text-white">{{ __('Service Expectations') }}</h2>
                        </div>
                        <div class="grid gap-6 md:grid-cols-2">
                            <div>
                                <div class="text-sm font-semibold text-[#D1C4E9] mb-2">{{ __('Service Tier') }}</div>
                                <div class="mt-1">
                                    <flux:badge :color="match(strtolower($booking->service_tier ?? 'silver')) {
                                        'silver' => 'zinc',
                                        'gold' => 'yellow',
                                        'platinum' => 'purple',
                                        default => 'zinc'
                                    }" size="md">
                                        {{ ucfirst($booking->service_tier ?? 'Silver') }}
                                    </flux:badge>
                                </div>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-[#D1C4E9] mb-2">{{ __('Service Mode') }}</div>
                                <div class="text-lg text-white">{{ ucfirst($booking->service_mode ?? '—') }}</div>
                            </div>
                            @if($booking->work_days && (is_array($booking->work_days) ? count($booking->work_days) > 0 : !empty($booking->work_days)))
                            <div class="md:col-span-2">
                                <div class="text-sm font-semibold text-[#D1C4E9] mb-3">{{ __('Work Days') }}</div>
                                <div class="flex flex-wrap gap-2">
                                    @php
                                        $workDays = is_array($booking->work_days) ? $booking->work_days : json_decode($booking->work_days, true) ?? [];
                                    @endphp
                                    @foreach($workDays as $day)
                                        <span class="inline-flex items-center px-3 py-1 rounded-xl bg-[#F5B301]/20 border border-[#F5B301]/30 text-[#F5B301] text-sm font-medium">{{ ucfirst($day) }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            @if($booking->working_hours)
                            <div>
                                <div class="text-sm font-semibold text-[#D1C4E9] mb-2">{{ __('Working Hours') }}</div>
                                <div class="text-lg text-white">{{ $booking->working_hours }}</div>
                            </div>
                            @endif
                            @if($booking->responsibilities && is_array($booking->responsibilities) && count($booking->responsibilities) > 0)
                            <div class="md:col-span-2">
                                <div class="text-sm font-semibold text-[#D1C4E9] mb-3">{{ __('Main Responsibilities') }}</div>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($booking->responsibilities as $responsibility)
                                        <span class="inline-flex items-center px-3 py-1 rounded-xl bg-[#F5B301]/20 border border-[#F5B301]/30 text-[#F5B301] text-sm font-medium">{{ $responsibility }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            @if($booking->cuisine_type && in_array('Cooking', is_array($booking->responsibilities) ? $booking->responsibilities : []))
                            <div class="md:col-span-2">
                                <div class="text-sm font-semibold text-[#D1C4E9] mb-2">{{ __('Cuisine Type') }}</div>
                                <div class="text-lg text-white">{{ $booking->cuisine_type }}</div>
                            </div>
                            @endif
                            @if($booking->atmosphere)
                            <div>
                                <div class="text-sm font-semibold text-[#D1C4E9] mb-2">{{ __('Household Atmosphere') }}</div>
                                <div class="text-lg text-white">{{ $booking->atmosphere }}</div>
                            </div>
                            @endif
                            @if($booking->manage_tasks)
                            <div>
                                <div class="text-sm font-semibold text-[#D1C4E9] mb-2">{{ __('Task Management Preference') }}</div>
                                <div class="text-lg text-white">{{ $booking->manage_tasks }}</div>
                            </div>
                            @endif
                            @if($booking->unspoken_rules)
                            <div class="md:col-span-2">
                                <div class="text-sm font-semibold text-[#D1C4E9] mb-2">{{ __('Unspoken Rules') }}</div>
                                <div class="text-base text-white whitespace-pre-line bg-white/5 rounded-xl p-4 border border-white/10">{{ $booking->unspoken_rules }}</div>
                            </div>
                            @endif
                            @if($booking->anything_else && !empty(trim($booking->anything_else)) && $booking->anything_else !== $booking->notes)
                            <div class="md:col-span-2">
                                <div class="text-sm font-semibold text-[#D1C4E9] mb-2">{{ __('Additional Requirements') }}</div>
                                <div class="text-base text-white whitespace-pre-line bg-white/5 rounded-xl p-4 border border-white/10">{{ $booking->anything_else }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Right Column: Metadata & Actions --}}
                <div class="space-y-6">
                    {{-- Assigned Maid Card --}}
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl border border-[#F5B301]/30 shadow-2xl p-6">
                        <div class="mb-4 flex items-center gap-2">
                            <div class="w-10 h-10 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-xl flex items-center justify-center">
                                <flux:icon.user-circle class="size-5 text-[#512B58]" />
                            </div>
                            <h3 class="text-xl font-bold text-white">{{ __('Assigned Maid') }}</h3>
                        </div>
                        @if($booking->maid)
                            <div class="space-y-4">
                                <div>
                                    <div class="text-xs font-semibold text-[#D1C4E9] mb-1">{{ __('Name') }}</div>
                                    <div class="text-base font-semibold text-white">
                                        <a href="{{ route($prefix . 'maids.show', $booking->maid) }}" class="text-[#F5B301] hover:text-[#FFD700] hover:underline">
                                            {{ $booking->maid->full_name }}
                                        </a>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-xs font-semibold text-[#D1C4E9] mb-1">{{ __('Phone') }}</div>
                                    <div class="text-base text-white">
                                        <a href="tel:{{ $booking->maid->phone }}" class="text-[#F5B301] hover:text-[#FFD700] hover:underline">{{ $booking->maid->phone }}</a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center py-8 text-center">
                                <flux:icon.user-circle class="size-16 text-white/20" />
                                <p class="mt-3 text-sm text-[#D1C4E9]">{{ __('No maid assigned yet') }}</p>
                            </div>
                        @endif
                    </div>

                    {{-- Booking Dates Card --}}
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl border border-[#F5B301]/30 shadow-2xl p-6">
                        <div class="mb-4 flex items-center gap-2">
                            <div class="w-10 h-10 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-xl flex items-center justify-center">
                                <flux:icon.calendar-days class="size-5 text-[#512B58]" />
                            </div>
                            <h3 class="text-xl font-bold text-white">{{ __('Booking Period') }}</h3>
                        </div>
                        <div class="space-y-4 text-sm">
                            <div>
                                <div class="text-xs font-semibold text-[#D1C4E9] mb-1">{{ __('Start Date') }}</div>
                                <div class="text-base font-semibold text-white">{{ $booking->start_date?->format('M d, Y') }}</div>
                            </div>
                            @if($booking->end_date)
                            <div>
                                <div class="text-xs font-semibold text-[#D1C4E9] mb-1">{{ __('End Date') }}</div>
                                <div class="text-base font-semibold text-white">{{ $booking->end_date?->format('M d, Y') }}</div>
                            </div>
                            <div class="pt-4 border-t border-white/10">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-semibold text-[#D1C4E9]">{{ __('Duration') }}</span>
                                    <span class="text-base font-bold text-[#F5B301]">{{ $booking->start_date->diffInDays($booking->end_date) }} {{ __('days') }}</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    @if($booking->calculated_price)
                    {{-- Pricing Card --}}
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl border border-[#F5B301]/30 shadow-2xl p-6">
                        <div class="mb-4 flex items-center gap-2">
                            <div class="w-10 h-10 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-xl flex items-center justify-center">
                                <flux:icon.currency-dollar class="size-5 text-[#512B58]" />
                            </div>
                            <h3 class="text-xl font-bold text-white">{{ __('Pricing') }}</h3>
                        </div>
                        <div>
                            <div class="text-xs font-semibold text-[#D1C4E9] mb-1">{{ __('Calculated Price') }}</div>
                            <div class="text-2xl font-bold text-[#F5B301]">
                                UGX {{ number_format($booking->calculated_price, 2) }}
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Meta Information Card --}}
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl border border-[#F5B301]/30 shadow-2xl p-6">
                        <div class="mb-4 flex items-center gap-2">
                            <div class="w-10 h-10 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-xl flex items-center justify-center">
                                <flux:icon.clock class="size-5 text-[#512B58]" />
                            </div>
                            <h3 class="text-xl font-bold text-white">{{ __('Meta') }}</h3>
                        </div>
                        <div class="space-y-3 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold text-[#D1C4E9]">{{ __('Created') }}</span>
                                <span class="text-sm text-white" title="{{ $booking->created_at->toDayDateTimeString() }}">{{ $booking->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold text-[#D1C4E9]">{{ __('Updated') }}</span>
                                <span class="text-sm text-white" title="{{ $booking->updated_at->toDayDateTimeString() }}">{{ $booking->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
