<div class="space-y-6">
    @php
        $prefix = auth()->user()->role === 'trainer' ? 'trainer.' : '';
    @endphp

    {{-- Page Header --}}
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <flux:heading size="xl">{{ __('Maid Deployments') }}</flux:heading>
            <flux:subheading class="mt-1">{{ __('View and manage all maid deployments') }}</flux:subheading>
        </div>
    </div>

    <flux:separator variant="subtle" />

    {{-- Filter Section --}}
    <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
        <div class="flex items-center gap-2 mb-4">
            <flux:icon.funnel class="size-5 text-[#F5B301]" />
            <h3 class="text-lg font-semibold text-white">{{ __('Filter & Search Deployments') }}</h3>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Search Input -->
            <div class="lg:col-span-2">
                <flux:input 
                    wire:model.live.debounce.400ms="search" 
                    :label="__('Search Deployments')"
                    placeholder="{{ __('Maid name, client, location, contract...') }}"
                    icon="magnifying-glass"
                    class="filter-input"
                />
            </div>

            <!-- Status Filter -->
            <div>
                <flux:select wire:model.live="status" :label="__('Deployment Status')" class="filter-select">
                    <option value="">{{ __('All Statuses') }}</option>
                    @foreach ($statusOptions as $opt)
                        <option value="{{ $opt }}">{{ ucfirst($opt) }}</option>
                    @endforeach
                </flux:select>
            </div>

            <!-- Contract Type Filter -->
            <div>
                <flux:select wire:model.live="contractType" :label="__('Contract Type')" class="filter-select">
                    <option value="">{{ __('All Types') }}</option>
                    @foreach ($contractTypeOptions as $opt)
                        <option value="{{ $opt }}">{{ ucfirst(str_replace('-', ' ', $opt)) }}</option>
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
                    <option value="deployment_date">{{ __('Deployment Date') }}</option>
                    <option value="contract_end_date">{{ __('Contract End') }}</option>
                    <option value="status">{{ __('Status') }}</option>
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
                <span>{{ __('Showing') }} {{ $deployments->count() }} {{ __('of') }} {{ $deployments->total() }} {{ __('deployments') }}</span>
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
                    wire:click="exportDeployments" 
                    variant="primary" 
                    size="sm"
                    icon="arrow-down-tray"
                >
                    {{ __('Export') }}
                </flux:button>
            </div>
        </div>
    </div>

    {{-- Success Message --}}
    @if (session('success'))
        <flux:callout variant="success" icon="check-circle" class="!border-green-200 !bg-green-50 dark:!border-green-900 dark:!bg-green-950">
            {{ session('success') }}
        </flux:callout>
    @endif

    {{-- Deployments Table --}}
    <div class="overflow-hidden rounded-lg border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
        <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
            <thead class="bg-neutral-50 dark:bg-neutral-900/50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Maid') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Client') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Location') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Deployed Date') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Contract') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Status') }}</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-800">
                @forelse ($deployments as $deployment)
                    <tr class="transition-colors hover:bg-neutral-50 dark:hover:bg-neutral-900/30">
                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                            <div class="flex items-center gap-3">
                                <div>
                                    <a href="{{ route($prefix . 'maids.show', $deployment->maid) }}" wire:navigate class="font-medium text-neutral-900 hover:underline dark:text-white">
                                        {{ $deployment->maid->full_name }}
                                    </a>
                                    <div class="text-xs text-neutral-500 dark:text-neutral-400">{{ $deployment->maid->maid_code }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                            <div class="text-neutral-900 dark:text-white">{{ $deployment->client_name }}</div>
                            <div class="text-xs text-neutral-500 dark:text-neutral-400">{{ $deployment->client_phone }}</div>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300">
                            {{ $deployment->deployment_location }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300">
                            {{ $deployment->deployment_date?->format('M d, Y') }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                            <div class="text-neutral-900 dark:text-white">{{ ucfirst(str_replace('-', ' ', $deployment->contract_type)) }}</div>
                            @can('viewSensitiveFinancials')
                                @if($deployment->monthly_salary)
                                    <div class="text-xs text-neutral-500 dark:text-neutral-400">UGX {{ number_format($deployment->monthly_salary) }}</div>
                                @endif
                            @endcan
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                            <flux:badge 
                                :color="match($deployment->status) {
                                    'active' => 'green',
                                    'completed' => 'zinc',
                                    'terminated' => 'red',
                                    default => 'zinc'
                                }" 
                                size="sm"
                            >
                                {{ ucfirst($deployment->status) }}
                            </flux:badge>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                            <div class="flex items-center justify-end gap-2">
                                <flux:button
                                    wire:click="viewDetails({{ $deployment->id }})"
                                    size="sm"
                                    icon="eye"
                                    variant="ghost"
                                >
                                    {{ __('View Details') }}
                                </flux:button>
                                @can('update', $deployment)
                                    <flux:button
                                        as="a"
                                        href="{{ route('deployments.edit', $deployment) }}"
                                        size="sm"
                                        icon="pencil-square"
                                        variant="outline"
                                    >
                                        {{ __('Edit') }}
                                    </flux:button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-sm text-neutral-500 dark:text-neutral-400">
                            {{ __('No deployments found.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $deployments->links() }}
    </div>

    {{-- Deployment Details Modal --}}
    @if($selectedDeployment)
        <flux:modal name="deployment-details" wire:model="showDetailsModal" class="space-y-6 max-w-4xl">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <flux:heading size="lg">{{ __('Deployment Details') }}</flux:heading>
                    <flux:subheading class="mt-2">
                        {{ __('Complete information for this deployment') }}
                    </flux:subheading>
                </div>
                @can('update', $selectedDeployment)
                    <flux:button
                        as="a"
                        href="{{ route('deployments.edit', $selectedDeployment) }}"
                        size="sm"
                        icon="pencil-square"
                        variant="primary"
                    >
                        {{ __('Edit Deployment') }}
                    </flux:button>
                @endcan
            </div>

            <div class="space-y-6">
                {{-- Maid Information --}}
                <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-900/50">
                    <h3 class="mb-3 text-sm font-semibold text-neutral-900 dark:text-white">{{ __('Maid Information') }}</h3>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <span class="text-neutral-500 dark:text-neutral-400">{{ __('Name:') }}</span>
                            <a href="{{ route($prefix . 'maids.show', $selectedDeployment->maid) }}" wire:navigate class="ml-2 font-medium text-blue-600 hover:underline dark:text-blue-400">
                                {{ $selectedDeployment->maid->full_name }}
                            </a>
                        </div>
                        <div>
                            <span class="text-neutral-500 dark:text-neutral-400">{{ __('Code:') }}</span>
                            <span class="ml-2 font-medium text-neutral-900 dark:text-white">{{ $selectedDeployment->maid->maid_code }}</span>
                        </div>
                        <div>
                            <span class="text-neutral-500 dark:text-neutral-400">{{ __('Phone:') }}</span>
                            <span class="ml-2 font-medium text-neutral-900 dark:text-white">{{ $selectedDeployment->maid->phone }}</span>
                        </div>
                        <div>
                            <span class="text-neutral-500 dark:text-neutral-400">{{ __('Role:') }}</span>
                            <span class="ml-2 font-medium text-neutral-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $selectedDeployment->maid->role)) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Client Information --}}
                <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-900/50">
                    <h3 class="mb-3 text-sm font-semibold text-neutral-900 dark:text-white">{{ __('Client Information') }}</h3>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <span class="text-neutral-500 dark:text-neutral-400">{{ __('Name:') }}</span>
                            <span class="ml-2 font-medium text-neutral-900 dark:text-white">{{ $selectedDeployment->client_name }}</span>
                        </div>
                        <div>
                            <span class="text-neutral-500 dark:text-neutral-400">{{ __('Phone:') }}</span>
                            <span class="ml-2 font-medium text-neutral-900 dark:text-white">{{ $selectedDeployment->client_phone }}</span>
                        </div>
                        <div>
                            <span class="text-neutral-500 dark:text-neutral-400">{{ __('Location:') }}</span>
                            <span class="ml-2 font-medium text-neutral-900 dark:text-white">{{ $selectedDeployment->deployment_location }}</span>
                        </div>
                    </div>
                </div>

                {{-- Contract Details --}}
                <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-900/50">
                    <h3 class="mb-3 text-sm font-semibold text-neutral-900 dark:text-white">{{ __('Contract Details') }}</h3>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <span class="text-neutral-500 dark:text-neutral-400">{{ __('Deployment Date:') }}</span>
                            <span class="ml-2 font-medium text-neutral-900 dark:text-white">{{ $selectedDeployment->deployment_date?->format('M d, Y') }}</span>
                        </div>
                        <div>
                            <span class="text-neutral-500 dark:text-neutral-400">{{ __('Contract Type:') }}</span>
                            <span class="ml-2 font-medium text-neutral-900 dark:text-white">{{ ucfirst(str_replace('-', ' ', $selectedDeployment->contract_type)) }}</span>
                        </div>
                        <div>
                            <span class="text-neutral-500 dark:text-neutral-400">{{ __('Start Date:') }}</span>
                            <span class="ml-2 font-medium text-neutral-900 dark:text-white">{{ $selectedDeployment->contract_start_date?->format('M d, Y') }}</span>
                        </div>
                        <div>
                            <span class="text-neutral-500 dark:text-neutral-400">{{ __('End Date:') }}</span>
                            <span class="ml-2 font-medium text-neutral-900 dark:text-white">{{ $selectedDeployment->contract_end_date?->format('M d, Y') ?? 'Ongoing' }}</span>
                        </div>
                        @can('viewSensitiveFinancials')
                            @if($selectedDeployment->monthly_salary)
                                <div>
                                    <span class="text-neutral-500 dark:text-neutral-400">{{ __('Monthly Salary:') }}</span>
                                    <span class="ml-2 font-medium text-green-600 dark:text-green-400">UGX {{ number_format($selectedDeployment->monthly_salary) }}</span>
                                </div>
                            @endif
                        @endcan
                        <div>
                            <span class="text-neutral-500 dark:text-neutral-400">{{ __('Status:') }}</span>
                            <flux:badge 
                                :color="match($selectedDeployment->status) {
                                    'active' => 'green',
                                    'completed' => 'zinc',
                                    'terminated' => 'red',
                                    default => 'zinc'
                                }" 
                                size="sm"
                                class="ml-2"
                            >
                                {{ ucfirst($selectedDeployment->status) }}
                            </flux:badge>
                        </div>
                    </div>
                </div>

                {{-- Special Instructions & Notes --}}
                @if($selectedDeployment->special_instructions || $selectedDeployment->notes)
                    <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-4 dark:border-neutral-700 dark:bg-neutral-900/50">
                        <h3 class="mb-3 text-sm font-semibold text-neutral-900 dark:text-white">{{ __('Additional Information') }}</h3>
                        
                        @if($selectedDeployment->special_instructions)
                            <div class="mb-3">
                                <span class="text-xs font-medium text-neutral-500 dark:text-neutral-400">{{ __('Special Instructions:') }}</span>
                                <p class="mt-1 text-sm text-neutral-700 dark:text-neutral-300">{{ $selectedDeployment->special_instructions }}</p>
                            </div>
                        @endif

                        @if($selectedDeployment->notes)
                            <div>
                                <span class="text-xs font-medium text-neutral-500 dark:text-neutral-400">{{ __('Notes:') }}</span>
                                <p class="mt-1 text-sm text-neutral-700 dark:text-neutral-300">{{ $selectedDeployment->notes }}</p>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- End Details (if completed/terminated) --}}
                @if($selectedDeployment->status !== 'active')
                    <div class="rounded-lg border border-red-200 bg-red-50 p-4 dark:border-red-900 dark:bg-red-950">
                        <h3 class="mb-3 text-sm font-semibold text-red-900 dark:text-red-100">{{ __('Deployment End Information') }}</h3>
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <span class="text-red-700 dark:text-red-300">{{ __('End Date:') }}</span>
                                <span class="ml-2 font-medium text-red-900 dark:text-red-100">{{ $selectedDeployment->end_date?->format('M d, Y') }}</span>
                            </div>
                            <div>
                                <span class="text-red-700 dark:text-red-300">{{ __('Status:') }}</span>
                                <span class="ml-2 font-medium text-red-900 dark:text-red-100">{{ ucfirst($selectedDeployment->status) }}</span>
                            </div>
                            @if($selectedDeployment->end_reason)
                                <div class="col-span-2">
                                    <span class="text-red-700 dark:text-red-300">{{ __('Reason:') }}</span>
                                    <p class="mt-1 font-medium text-red-900 dark:text-red-100">{{ $selectedDeployment->end_reason }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <div class="flex gap-2 justify-end pt-4 border-t border-neutral-200 dark:border-neutral-700">
                <flux:button wire:click="closeDetailsModal" variant="ghost">
                    {{ __('Close') }}
                </flux:button>
            </div>
        </flux:modal>
    @endif
</div>
