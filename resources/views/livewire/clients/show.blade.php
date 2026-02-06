<div class="space-y-6">
    @php
        $prefix = auth()->user()->role === 'trainer' ? 'trainer.' : '';
    @endphp

    {{-- Soft Delete Notice --}}
    @if($client->trashed())
        <flux:callout variant="warning">
            {{ __('This client has been deleted. Click "Restore Client" to undelete it.') }}
        </flux:callout>
    @endif

    <!-- Header Section -->
    <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
        <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
            <div class="flex items-start gap-4">
                @if($client->profile_image)
                    <img src="{{ Storage::url($client->profile_image) }}" alt="{{ $client->contact_person }}" class="h-16 w-16 rounded-full object-cover shadow-lg border-2 border-[#F5B301]/30">
                @else
                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-[#F5B301] to-[#FFD700] text-2xl font-bold text-[#3B0A45] shadow-lg">
                        {{ strtoupper(substr($client->contact_person, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <flux:heading size="xl" class="text-white">{{ $client->contact_person }}</flux:heading>
                    <div class="mt-1 flex items-center gap-2">
                        <flux:badge 
                            size="sm" 
                            :color="match($client->subscription_status) {
                                'active' => 'green',
                                'expired' => 'red',
                                'pending' => 'yellow',
                                default => 'zinc'
                            }"
                        >
                            {{ ucfirst($client->subscription_status) }}
                        </flux:badge>
                        <flux:badge size="sm" color="blue">{{ ucfirst($client->subscription_tier) }}</flux:badge>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2">
                @if($client->trashed())
                    <flux:button wire:click="restore" variant="filled" icon="arrow-path">
                        {{ __('Restore Client') }}
                    </flux:button>
                @else
                    <flux:button as="a" :href="route($prefix . 'tickets.create', ['client_id' => $client->id])" variant="primary" icon="ticket">
                        {{ __('Create Ticket') }}
                    </flux:button>
                    <flux:button as="a" :href="route('client-evaluations.create', ['client_id' => $client->id])" variant="outline" icon="clipboard-document-check">
                        {{ __('New Evaluation') }}
                    </flux:button>
                    @if(auth()->user()->isAdminLike())
                        <flux:button wire:click="openSendEvaluationModal" variant="outline" icon="paper-airplane">
                            {{ __('Send Evaluation Link') }}
                        </flux:button>
                    @endif
                    <flux:button as="a" :href="route($prefix . 'clients.edit', $client)" variant="outline" icon="pencil-square">
                        {{ __('Edit Client') }}
                    </flux:button>
                @endif
                <flux:button as="a" :href="route($prefix . 'clients.index')" variant="outline" icon="arrow-left">
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
    </div>

    <!-- Stats Cards -->
    <div class="grid gap-4 md:grid-cols-4">
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-4 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-[#D1C4E9]">{{ __('Total Bookings') }}</div>
                    <div class="mt-1 text-2xl font-bold text-white">{{ $client->total_bookings }}</div>
                </div>
                <flux:icon.calendar class="size-8 text-[#F5B301]" />
            </div>
        </div>

        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-4 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-[#D1C4E9]">{{ __('Active Bookings') }}</div>
                    <div class="mt-1 text-2xl font-bold text-white">{{ $client->active_bookings }}</div>
                </div>
                <flux:icon.check-circle class="size-8 text-[#4CAF50]" />
            </div>
        </div>

        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-4 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-[#D1C4E9]">{{ __('Account Age') }}</div>
                    <div class="mt-1 text-2xl font-bold text-white" title="{{ __('Since') }}: {{ $client->created_at->toDayDateTimeString() }} ({{ $client->created_at->diffForHumans() }})">
                        {{ $this->accountAge }}
                    </div>
                </div>
                <flux:icon.clock class="size-8 text-[#F5B301]" />
            </div>
        </div>

        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-4 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-[#D1C4E9]">{{ __('Member Since') }}</div>
                    <div class="mt-1 text-lg font-semibold text-white" title="{{ $client->created_at->toDayDateTimeString() }}">
                        {{ $client->created_at->format('M Y') }}
                        <span class="ml-2 text-xs font-normal text-[#D1C4E9]">({{ $client->created_at->diffForHumans() }})</span>
                    </div>
                </div>
                <flux:icon.user-circle class="size-8 text-[#F5B301]" />
            </div>
        </div>
    </div>

    <div class="flex flex-wrap items-center gap-2">
        <button type="button" wire:click="setActiveTab('overview')"
                class="rounded-lg px-4 py-2 text-sm font-semibold border {{ $activeTab === 'overview' ? 'bg-[#F5B301]/20 text-[#F5B301] border-[#F5B301]/40' : 'bg-[#3B0A45] text-[#D1C4E9] border-[#F5B301]/20' }}">
            {{ __('Overview') }}
        </button>
        <button type="button" wire:click="setActiveTab('tickets')"
                class="rounded-lg px-4 py-2 text-sm font-semibold border {{ $activeTab === 'tickets' ? 'bg-[#F5B301]/20 text-[#F5B301] border-[#F5B301]/40' : 'bg-[#3B0A45] text-[#D1C4E9] border-[#F5B301]/20' }}">
            {{ __('Tickets') }}
        </button>
        @if ($canViewFeedback)
            <button type="button" wire:click="setActiveTab('feedback')"
                    class="rounded-lg px-4 py-2 text-sm font-semibold border {{ $activeTab === 'feedback' ? 'bg-[#F5B301]/20 text-[#F5B301] border-[#F5B301]/40' : 'bg-[#3B0A45] text-[#D1C4E9] border-[#F5B301]/20' }}">
                {{ __('Feedback') }}
            </button>
        @endif
    </div>

    {{-- Main Content Grid --}}
    @if($activeTab === 'overview')
    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Left Column: Contact & Address --}}
        <div class="space-y-6 lg:col-span-2">
            {{-- Contact Information --}}
            <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
                <div class="mb-4 flex items-center gap-2">
                    <flux:icon.user class="size-5 text-[#F5B301]" />
                    <flux:heading size="lg" class="text-white">{{ __('Contact Information') }}</flux:heading>
                </div>
                
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <div class="text-sm font-medium text-[#D1C4E9]">{{ __('Full Name') }}</div>
                        <div class="mt-1 text-base text-white">{{ $client->user->name }}</div>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-[#D1C4E9]">{{ __('Email') }}</div>
                        <div class="mt-1 text-base text-white">{{ $client->user->email }}</div>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-[#D1C4E9]">{{ __('Primary Phone') }}</div>
                        <div class="mt-1 text-base text-white">{{ $client->phone }}</div>
                    </div>
                    @can('viewSensitiveIdentity')
                        @if($client->identity_number)
                        <div>
                            <div class="text-sm font-medium text-[#D1C4E9]">{{ __('Identity') }}</div>
                            <div class="mt-1 text-base text-white">
                                {{ strtoupper($client->identity_type ?? '') }}: {{ $client->identity_number }}
                            </div>
                        </div>
                        @endif
                    @endcan
                    @if($client->secondary_phone)
                    <div>
                        <div class="text-sm font-medium text-[#D1C4E9]">{{ __('Secondary Phone') }}</div>
                        <div class="mt-1 text-base text-white">{{ $client->secondary_phone }}</div>
                    </div>
                    @endif
                    @if($client->company_name)
                    <div class="md:col-span-2">
                        <div class="text-sm font-medium text-[#D1C4E9]">{{ __('Company Name') }}</div>
                        <div class="mt-1 text-base text-white">{{ $client->company_name }}</div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Address Information --}}
            <div class="details-card">
                <div class="mb-4 flex items-center gap-2">
                    <flux:icon.map-pin class="size-5 text-neutral-600 dark:text-neutral-400" />
                    <flux:heading size="lg">{{ __('Address') }}</flux:heading>
                </div>
                
                <div class="space-y-3">
                    <div>
                        <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Street Address') }}</div>
                        <div class="mt-1 text-base text-neutral-900 dark:text-white">{{ $client->address }}</div>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('City') }}</div>
                            <div class="mt-1 text-base text-neutral-900 dark:text-white">{{ $client->city }}</div>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('District') }}</div>
                            <div class="mt-1 text-base text-neutral-900 dark:text-white">{{ $client->district }}</div>
                        </div>
                    </div>
                    <div class="rounded-md bg-neutral-50 p-3 dark:bg-neutral-900">
                        <div class="text-sm text-neutral-700 dark:text-neutral-300">
                            <strong>{{ __('Full Address') }}:</strong> {{ $client->fullAddress }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Booking History --}}
            <div class="details-card">
                <div class="mb-4 flex items-center gap-2">
                    <flux:icon.calendar class="size-5 text-neutral-600 dark:text-neutral-400" />
                    <flux:heading size="lg">{{ __('Recent Bookings') }}</flux:heading>
                </div>

                @if($recentBookings->isEmpty())
                    <div class="flex flex-col items-center justify-center space-y-3 py-8">
                        <flux:icon.calendar class="size-12 text-neutral-400 dark:text-neutral-600" />
                        <div class="text-center text-neutral-500 dark:text-neutral-400">
                            {{ __('No bookings yet.') }}
                        </div>
                    </div>
                @else
                    <div class="overflow-hidden rounded-lg border border-neutral-200 dark:border-neutral-700">
                        <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                            <thead class="bg-neutral-50 dark:bg-neutral-900/50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('ID') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Maid') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Type') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Dates') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Status') }}</th>
                                    <th class="px-4 py-2 text-right text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-800">
                                @foreach($recentBookings as $booking)
                                <tr class="transition-colors hover:bg-neutral-50 dark:hover:bg-neutral-900/30">
                                    <td class="px-4 py-3 text-sm font-medium text-neutral-900 dark:text-white">#{{ $booking->id }}</td>
                                    <td class="px-4 py-3 text-sm text-neutral-700 dark:text-neutral-300">{{ $booking->maid?->full_name }}</td>
                                    <td class="px-4 py-3">
                                        <flux:badge size="sm" color="blue">{{ ucwords(str_replace('-', ' ', $booking->booking_type)) }}</flux:badge>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <div class="text-neutral-700 dark:text-neutral-300">{{ $booking->start_date?->format('M d, Y') }}</div>
                                        @if($booking->end_date)
                                            <div class="text-xs text-neutral-500 dark:text-neutral-400">to {{ $booking->end_date->format('M d, Y') }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <flux:badge size="sm" :color="$booking->statusColor">{{ ucfirst($booking->status) }}</flux:badge>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <flux:button as="a" :href="route($prefix . 'bookings.show', $booking)" variant="ghost" size="xs" icon="eye">{{ __('View') }}</flux:button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- Tickets Section --}}
        </div>

        {{-- Right Column: Subscription & Notes --}}
        <div class="space-y-6">
            {{-- Subscription Status --}}
            <div class="details-card">
                <div class="mb-4 flex items-center gap-2">
                    <flux:icon.credit-card class="size-5 text-neutral-600 dark:text-neutral-400" />
                    <flux:heading size="lg">{{ __('Subscription') }}</flux:heading>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Current Plan') }}</div>
                        <div class="mt-1">
                            @if($client->package)
                                <a href="{{ route($prefix . 'packages.index') }}" class="hover:text-[#F5B301] transition-colors">
                                    <flux:badge size="lg" color="blue">{{ $client->package->name }}</flux:badge>
                                    <div class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">{{ $client->package->tier }} Tier</div>
                                </a>
                            @else
                                <flux:badge size="lg" color="gray">{{ __('No Package Selected') }}</flux:badge>
                            @endif
                        </div>
                    </div>

                    <div>
                        <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Status') }}</div>
                        <div class="mt-1">
                            <flux:badge 
                                size="lg" 
                                :color="match($client->subscription_status) {
                                    'active' => 'green',
                                    'expired' => 'red',
                                    'pending' => 'yellow',
                                    default => 'zinc'
                                }"
                            >
                                {{ ucfirst($client->subscription_status) }}
                            </flux:badge>
                        </div>
                    </div>

                    @if($client->subscription_start_date)
                    <div>
                        <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Start Date') }}</div>
                        <div class="mt-1 text-base text-neutral-900 dark:text-white">
                            {{ \Carbon\Carbon::parse($client->subscription_start_date)->format('M d, Y') }}
                        </div>
                    </div>
                    @endif

                    @if($client->subscription_end_date)
                    <div>
                        <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('End Date') }}</div>
                        <div class="mt-1 text-base text-neutral-900 dark:text-white">
                            {{ \Carbon\Carbon::parse($client->subscription_end_date)->format('M d, Y') }}
                        </div>
                        @if($this->subscriptionDaysRemaining !== null)
                            @if($this->subscriptionDaysRemaining > 0)
                                <div class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                                    {{ $this->subscriptionDaysRemaining }} {{ __('days remaining') }}
                                </div>
                            @else
                                <div class="mt-1 text-sm text-red-600 dark:text-red-400">
                                    {{ __('Expired') }}
                                </div>
                            @endif
                        @endif
                    </div>
                    @endif

                    @if($client->subscription_status === 'active')
                        <div class="rounded-md bg-green-50 p-3 dark:bg-green-950">
                            <div class="flex items-center gap-2 text-sm text-green-800 dark:text-green-200">
                                <flux:icon.check-circle class="size-4" />
                                <span>{{ __('Active subscription') }}</span>
                            </div>
                        </div>
                    @else
                        <div class="rounded-md bg-yellow-50 p-3 dark:bg-yellow-950">
                            <div class="flex items-center gap-2 text-sm text-yellow-800 dark:text-yellow-200">
                                <flux:icon.exclamation-triangle class="size-4" />
                                <span>{{ __('No active subscription') }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Active Deployments --}}
            <div class="details-card">
                <div class="mb-4 flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <flux:icon.briefcase class="size-5 text-neutral-600 dark:text-neutral-400" />
                        <flux:heading size="lg">{{ __('Active Deployments') }}</flux:heading>
                    </div>
                </div>

                @if($deployments->isEmpty())
                    <div class="flex flex-col items-center justify-center space-y-3 py-8">
                        <flux:icon.briefcase class="size-12 text-neutral-400 dark:text-neutral-600" />
                        <div class="text-center text-neutral-500 dark:text-neutral-400">
                            {{ __('No active deployments.') }}
                        </div>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($deployments as $deployment)
                        <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4 hover:bg-neutral-50 dark:hover:bg-neutral-900/30 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <a href="{{ route($prefix . 'maids.show', $deployment->maid) }}" class="text-sm font-medium text-[#64B5F6] hover:text-[#F5B301]">
                                        {{ $deployment->maid?->full_name }}
                                    </a>
                                    <div class="mt-2 grid gap-2 grid-cols-2 text-xs">
                                        <div>
                                            <span class="text-neutral-600 dark:text-neutral-400">{{ __('Start Date') }}:</span>
                                            <span class="ml-1 text-neutral-900 dark:text-white">{{ $deployment->deployment_start_date?->format('M d, Y') }}</span>
                                        </div>
                                        <div>
                                            <span class="text-neutral-600 dark:text-neutral-400">{{ __('Payment') }}:</span>
                                            <span class="ml-1 text-neutral-900 dark:text-white">{{ $deployment->client_payment ? number_format($deployment->client_payment, 2) : '—' }}</span>
                                        </div>
                                    </div>
                                </div>
                                <flux:badge size="sm" :color="match($deployment->status) {
                                    'active' => 'green',
                                    'pending' => 'yellow',
                                    'completed' => 'gray',
                                    default => 'gray'
                                }">{{ ucfirst($deployment->status) }}</flux:badge>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Active Contracts --}}
            <div class="details-card">
                <div class="mb-4 flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <flux:icon.document-text class="size-5 text-neutral-600 dark:text-neutral-400" />
                        <flux:heading size="lg">{{ __('Active Contracts') }}</flux:heading>
                    </div>
                </div>

                @if($contracts->isEmpty())
                    <div class="flex flex-col items-center justify-center space-y-3 py-8">
                        <flux:icon.document-text class="size-12 text-neutral-400 dark:text-neutral-600" />
                        <div class="text-center text-neutral-500 dark:text-neutral-400">
                            {{ __('No active contracts.') }}
                        </div>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($contracts as $contract)
                        <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4 hover:bg-neutral-50 dark:hover:bg-neutral-900/30 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <a href="{{ route($prefix . 'maids.show', $contract->maid) }}" class="text-sm font-medium text-[#64B5F6] hover:text-[#F5B301]">
                                        {{ $contract->maid?->full_name }}
                                    </a>
                                    <div class="mt-2 grid gap-2 grid-cols-2 text-xs">
                                        <div>
                                            <span class="text-neutral-600 dark:text-neutral-400">{{ __('Start') }}:</span>
                                            <span class="ml-1 text-neutral-900 dark:text-white">{{ $contract->contract_start_date?->format('M d, Y') }}</span>
                                        </div>
                                        <div>
                                            <span class="text-neutral-600 dark:text-neutral-400">{{ __('End') }}:</span>
                                            <span class="ml-1 text-neutral-900 dark:text-white">{{ $contract->contract_end_date?->format('M d, Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <flux:badge size="sm" :color="match($contract->contract_status) {
                                    'active' => 'green',
                                    'completed' => 'gray',
                                    'suspended' => 'orange',
                                    default => 'gray'
                                }">{{ ucfirst($contract->contract_status) }}</flux:badge>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Additional Info --}}
            @if($client->special_requirements || $client->preferred_maid_types)
            <div class="details-card">
                <div class="mb-4 flex items-center gap-2">
                    <flux:icon.information-circle class="size-5 text-neutral-600 dark:text-neutral-400" />
                    <flux:heading size="lg">{{ __('Additional Information') }}</flux:heading>
                </div>
                
                <div class="space-y-4">
                    @if($client->preferred_maid_types)
                    <div>
                        <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Preferred Maid Types') }}</div>
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach(is_array($client->preferred_maid_types) ? $client->preferred_maid_types : (json_decode($client->preferred_maid_types, true) ?? []) as $type)
                                <flux:badge size="sm" color="zinc">{{ ucfirst($type) }}</flux:badge>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($client->special_requirements)
                    <div>
                        <div class="text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Special Requirements') }}</div>
                        <div class="mt-1 text-sm text-neutral-700 dark:text-neutral-300">{{ $client->special_requirements }}</div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Notes --}}
            @if($client->notes)
            <div class="details-card">
                <div class="mb-4 flex items-center gap-2">
                    <flux:icon.document-text class="size-5 text-neutral-600 dark:text-neutral-400" />
                    <flux:heading size="lg">{{ __('Notes') }}</flux:heading>
                </div>
                
                <div class="text-sm text-neutral-700 dark:text-neutral-300">{{ $client->notes }}</div>
            </div>
            @endif
        </div>
    </div>
    @endif

    @if($activeTab === 'tickets')
        <div class="details-card">
            <div class="mb-4 flex items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <flux:icon.ticket class="size-5 text-neutral-600 dark:text-neutral-400" />
                    <flux:heading size="lg">{{ __('Support Tickets') }}</flux:heading>
                </div>
                <a href="{{ route($prefix . 'tickets.create', ['client_id' => $client->id]) }}" class="text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium">
                    {{ __('Create New') }}
                </a>
            </div>

            @if($tickets->count() === 0)
                <div class="flex flex-col items-center justify-center space-y-3 py-8">
                    <flux:icon.ticket class="size-12 text-neutral-400 dark:text-neutral-600" />
                    <div class="text-center text-neutral-500 dark:text-neutral-400">
                        {{ __('No tickets yet.') }}
                    </div>
                </div>
            @else
                <div class="overflow-hidden rounded-lg border border-neutral-200 dark:border-neutral-700">
                    <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                        <thead class="bg-neutral-50 dark:bg-neutral-900/50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('ID') }}</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Subject') }}</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Priority') }}</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('SLA Status') }}</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Status') }}</th>
                                <th class="px-4 py-2 text-right text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-800">
                            @foreach($tickets as $ticket)
                            <tr class="transition-colors hover:bg-neutral-50 dark:hover:bg-neutral-900/30">
                                <td class="px-4 py-3 text-sm font-medium text-neutral-900 dark:text-white">#{{ $ticket->id }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ route($prefix . 'tickets.show', $ticket) }}" class="text-sm text-[#64B5F6] hover:text-[#F5B301] font-medium">
                                        {{ Str::limit($ticket->subject, 40) }}
                                    </a>
                                </td>
                                <td class="px-4 py-3">
                                    <flux:badge size="sm" :color="match($ticket->priority) {
                                        'critical' => 'red',
                                        'high' => 'orange',
                                        'medium' => 'yellow',
                                        'low' => 'blue',
                                        default => 'gray'
                                    }">{{ ucfirst($ticket->priority) }}</flux:badge>
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        $slaStatus = $ticket->getSLAStatus();
                                    @endphp
                                    @if($slaStatus === 'breached')
                                        <flux:badge size="sm" color="red">{{ __('Breached') }}</flux:badge>
                                    @elseif($slaStatus === 'at-risk')
                                        <flux:badge size="sm" color="orange">{{ __('At Risk') }}</flux:badge>
                                    @else
                                        <flux:badge size="sm" color="green">{{ __('On Track') }}</flux:badge>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <flux:badge size="sm" :color="match($ticket->status) {
                                        'open' => 'blue',
                                        'in-progress' => 'yellow',
                                        'resolved' => 'green',
                                        'closed' => 'gray',
                                        default => 'gray'
                                    }">{{ ucfirst($ticket->status) }}</flux:badge>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <flux:button as="a" :href="route($prefix . 'tickets.show', $ticket)" variant="ghost" size="xs" icon="eye">{{ __('View') }}</flux:button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $tickets->links() }}
                </div>
            @endif
        </div>
    @endif

    @if($activeTab === 'feedback' && $canViewFeedback)
        <div class="details-card">
            <div class="mb-4 flex items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <flux:icon.chat-bubble-left-right class="size-5 text-neutral-600 dark:text-neutral-400" />
                    <flux:heading size="lg">{{ __('Client Feedback') }}</flux:heading>
                </div>
                <a href="{{ route('client-feedback.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium">
                    {{ __('View All') }}
                </a>
            </div>

            @if(!$feedback || $feedback->count() === 0)
                <div class="flex flex-col items-center justify-center space-y-3 py-8">
                    <flux:icon.chat-bubble-left-right class="size-12 text-neutral-400 dark:text-neutral-600" />
                    <div class="text-center text-neutral-500 dark:text-neutral-400">
                        {{ __('No feedback submitted yet.') }}
                    </div>
                </div>
            @else
                <div class="overflow-hidden rounded-lg border border-neutral-200 dark:border-neutral-700">
                    <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                        <thead class="bg-neutral-50 dark:bg-neutral-900/50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Respondent') }}</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Maid') }}</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Rating') }}</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Submitted') }}</th>
                                <th class="px-4 py-2 text-right text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-800">
                            @foreach($feedback as $response)
                                <tr class="transition-colors hover:bg-neutral-50 dark:hover:bg-neutral-900/30">
                                    <td class="px-4 py-3 text-sm text-neutral-700 dark:text-neutral-300">
                                        <div class="font-medium text-neutral-900 dark:text-white">{{ $response->respondent_name }}</div>
                                        <div class="text-xs text-neutral-500 dark:text-neutral-400">{{ $response->respondent_email }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-neutral-700 dark:text-neutral-300">{{ $response->maid?->full_name ?? '—' }}</td>
                                    <td class="px-4 py-3 text-sm text-neutral-700 dark:text-neutral-300">
                                        {{ $response->overall_rating ? number_format((float) $response->overall_rating, 1) : '—' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-neutral-700 dark:text-neutral-300">
                                        {{ $response->submitted_at?->format('M d, Y') ?? __('N/A') }}
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <flux:button as="a" :href="route('client-feedback.show', $response)" variant="ghost" size="xs" icon="eye">
                                            {{ __('View') }}
                                        </flux:button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $feedback->links() }}
                </div>
            @endif
        </div>
    @endif

    <flux:modal name="send-evaluation-link" wire:model="showSendEvaluationModal" class="max-w-lg">
        <div class="space-y-4">
            <flux:heading size="lg">{{ __('Send Client Evaluation Link') }}</flux:heading>
            <p class="text-sm text-neutral-600 dark:text-neutral-400">
                {{ __('Choose the booking to link with this feedback form. The link will be emailed to the booking email or client account email.') }}
            </p>

            <flux:select wire:model.defer="selectedBookingId" :label="__('Booking')">
                <option value="">{{ __('Select a booking...') }}</option>
                @foreach ($bookingsForEvaluation as $booking)
                    <option value="{{ $booking->id }}">
                        #{{ $booking->id }} — {{ $booking->maid?->full_name ?? __('No maid') }} — {{ $booking->start_date?->format('M d, Y') }}
                    </option>
                @endforeach
            </flux:select>
            @error('selectedBookingId') <span class="text-sm text-red-600">{{ $message }}</span> @enderror

            <flux:select wire:model.defer="linkExpiresInDays" :label="__('Link Expiry')">
                <option value="7">{{ __('7 days') }}</option>
                <option value="14">{{ __('14 days') }}</option>
                <option value="30">{{ __('30 days') }}</option>
            </flux:select>

            <div class="flex items-center justify-end gap-3">
                <flux:button variant="outline" wire:click="$set('showSendEvaluationModal', false)">
                    {{ __('Cancel') }}
                </flux:button>
                <flux:button variant="primary" wire:click="sendClientEvaluationLink">
                    {{ __('Send Link') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
