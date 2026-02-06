<div class="space-y-6">
    @php
        $prefix = auth()->user()->role === 'trainer' ? 'trainer.' : '';
    @endphp

    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <flux:heading size="xl">{{ __('Clients') }}</flux:heading>
            <flux:subheading class="mt-1">{{ __('Manage and search client records') }}</flux:subheading>
        </div>

        <flux:button as="a" :href="route($prefix . 'clients.create')" variant="primary" icon="plus">
            {{ __('New Client') }}
        </flux:button>
    </div>

    <flux:separator variant="subtle" />

    <!-- Filter Section -->
    <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg mb-6">
        <div class="flex items-center gap-2 mb-4">
            <flux:icon.funnel class="size-5 text-[#F5B301]" />
            <h3 class="text-lg font-semibold text-white">{{ __('Filter & Search') }}</h3>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Search Input -->
            <div class="lg:col-span-2">
                <flux:input 
                    wire:model.live.debounce.400ms="search" 
                    :label="__('Search Clients')"
                    placeholder="{{ __('Name, NIN, phone, email...') }}"
                    icon="magnifying-glass"
                    class="filter-input"
                />
            </div>

            <!-- Status Filter -->
            <div>
                <flux:select wire:model.live="subscription_status" :label="__('Subscription Status')" class="filter-select">
                    <option value="">{{ __('All Statuses') }}</option>
                    @foreach ($statusOptions as $opt)
                        <option value="{{ $opt }}">{{ ucfirst($opt) }}</option>
                    @endforeach
                </flux:select>
            </div>

            <!-- Tier Filter -->
            <div>
                <flux:select wire:model.live="subscription_tier" :label="__('Package Tier')" class="filter-select">
                    <option value="">{{ __('All Tiers') }}</option>
                    @foreach ($tierOptions as $opt)
                        <option value="{{ $opt }}">{{ ucfirst($opt) }}</option>
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
                    <option value="contact_person">{{ __('Name') }}</option>
                    <option value="nid">{{ __('NIN/ID') }}</option>
                    <option value="subscription_tier">{{ __('Package Tier') }}</option>
                    <option value="subscription_status">{{ __('Status') }}</option>
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
                <span>{{ __('Showing') }} {{ $clients->count() }} {{ __('of') }} {{ $clients->total() }} {{ __('clients') }}</span>
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
                    wire:click="exportClients" 
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

    <div class="table-container">
        <table class="min-w-full divide-y divide-[#F5B301]/20">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">{{ __('Contact') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">{{ __('NIN/ID') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">{{ __('Phone') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">{{ __('Location') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">{{ __('Tier') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">{{ __('Status') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">{{ __('Bookings') }}</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($clients as $client)
                    <tr>
                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                            <div class="flex items-center gap-3">
                                @if($client->profile_image)
                                    <img src="{{ Storage::url($client->profile_image) }}" alt="{{ $client->contact_person }}" class="h-10 w-10 rounded-full object-cover border border-neutral-200 dark:border-neutral-700">
                                @else
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-emerald-500 to-emerald-600 text-sm font-bold text-white">
                                        {{ strtoupper(substr($client->contact_person, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <a href="{{ route($prefix . 'clients.show', $client) }}" class="font-medium hover:underline">
                                        {{ $client->contact_person }}
                                    </a>
                                    <div class="text-xs text-[#D1C4E9]">{{ $client->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        @can('viewSensitiveIdentity')
                            <td class="whitespace-nowrap px-6 py-4 text-sm">
                                <div class="font-medium">{{ $client->identity_number ?? '—' }}</div>
                                <div class="text-xs text-[#D1C4E9]">{{ ucfirst($client->identity_type ?? 'nin') }}</div>
                            </td>
                        @else
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-[#D1C4E9]">—</td>
                        @endcan
                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                            {{ $client->phone }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                            <div>{{ $client->city }}</div>
                            <div class="text-xs text-[#D1C4E9]">{{ $client->district }}</div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
                            <flux:badge size="sm" color="blue">{{ ucfirst($client->subscription_tier) }}</flux:badge>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4">
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
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                            <div>{{ __('Total') }}: {{ $client->total_bookings }}</div>
                            <div class="text-xs text-[#D1C4E9]">{{ __('Active') }}: {{ $client->active_bookings }}</div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <flux:button
                                    as="a"
                                    :href="route($prefix . 'clients.edit', $client)"
                                    variant="ghost"
                                    size="sm"
                                    icon="pencil-square"
                                    title="{{ __('Edit') }}"
                                ></flux:button>

                                <flux:button
                                    variant="ghost"
                                    size="sm"
                                    icon="trash"
                                    class="!text-red-600 hover:!bg-red-50 dark:!text-red-400 dark:hover:!bg-red-950"
                                    title="{{ __('Delete') }}"
                                    wire:click.prevent="confirmDelete({{ $client->id }})"
                                ></flux:button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center space-y-3">
                                <flux:icon.users class="size-12 text-neutral-400 dark:text-neutral-600" />
                                <div class="text-neutral-500 dark:text-neutral-400">{{ __('No clients found') }}</div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <flux:modal name="confirm-client-delete" class="max-w-md" wire:model="showDeleteModal">
        <div class="space-y-4">
            <flux:heading size="lg">{{ __('Delete client?') }}</flux:heading>
            <flux:text>{{ __('This will also delete the linked user account and cannot be undone.') }}</flux:text>
            <div class="rounded-md bg-yellow-50 p-3 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                <div class="text-sm">
                    <strong>{{ __('Client') }}:</strong>
                    <span>{{ $deleteName }}</span>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3">
                <flux:button variant="outline" wire:click="cancelDelete">{{ __('Cancel') }}</flux:button>
                <flux:button variant="danger" wire:click="deleteConfirmed">{{ __('Delete') }}</flux:button>
            </div>
        </div>
    </flux:modal>

    <div>
        {{ $clients->onEachSide(1)->links() }}
    </div>
</div>
