<div class="space-y-6">
    @php
        $prefix = auth()->user()->role === 'trainer' ? 'trainer.' : '';
    @endphp
    <!-- Success Message -->
    @if (session('success'))
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-200 px-6 py-4 rounded-xl shadow-sm flex items-center gap-3">
            <flux:icon.check-circle class="w-5 h-5 text-green-600 dark:text-green-400" />
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Message from Livewire -->
    @if (session('message'))
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-200 px-6 py-4 rounded-xl shadow-sm flex items-center gap-3">
            <flux:icon.check-circle class="w-5 h-5 text-green-600 dark:text-green-400" />
            <span class="font-medium">{{ session('message') }}</span>
        </div>
    @endif

    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <flux:heading size="xl">{{ __('Trainers') }}</flux:heading>
            <flux:subheading class="mt-1">{{ __('Manage trainer profiles') }}</flux:subheading>
        </div>

        <flux:button as="a" :href="route($prefix . 'trainers.create')" variant="primary" icon="plus">
            {{ __('New Trainer') }}
        </flux:button>
    </div>

    <flux:separator variant="subtle" />

    {{-- Filter Section --}}
    <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
        <div class="flex items-center gap-2 mb-4">
            <flux:icon.funnel class="size-5 text-[#F5B301]" />
            <h3 class="text-lg font-semibold text-white">{{ __('Filter & Search Trainers') }}</h3>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Search Input -->
            <div class="lg:col-span-2">
                <flux:input 
                    wire:model.live.debounce.400ms="search" 
                    :label="__('Search Trainers')"
                    placeholder="{{ __('Name, email, specialization, phone...') }}"
                    icon="magnifying-glass"
                    class="filter-input"
                />
            </div>

            <!-- Specialization Filter -->
            <div>
                <flux:select wire:model.live="specializationFilter" :label="__('Specialization')" class="filter-select">
                    <option value="">{{ __('All Specializations') }}</option>
                    <option value="housekeeping">{{ __('Housekeeping') }}</option>
                    <option value="childcare">{{ __('Childcare') }}</option>
                    <option value="cooking">{{ __('Cooking') }}</option>
                    <option value="elderly-care">{{ __('Elderly Care') }}</option>
                    <option value="general">{{ __('General Training') }}</option>
                </flux:select>
            </div>

            <!-- Status Filter -->
            <div>
                <flux:select wire:model.live="statusFilter" :label="__('Status')" class="filter-select">
                    <option value="">{{ __('All Statuses') }}</option>
                    <option value="active">{{ __('Active') }}</option>
                    <option value="inactive">{{ __('Inactive') }}</option>
                    <option value="on-leave">{{ __('On Leave') }}</option>
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
                    <option value="name">{{ __('Name') }}</option>
                    <option value="experience_years">{{ __('Experience') }}</option>
                    <option value="specialization">{{ __('Specialization') }}</option>
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
                <span>{{ __('Showing') }} {{ $trainers->count() }} {{ __('of') }} {{ $trainers->total() }} {{ __('trainers') }}</span>
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
                    wire:click="exportTrainers" 
                    variant="primary" 
                    size="sm"
                    icon="arrow-down-tray"
                >
                    {{ __('Export') }}
                </flux:button>
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-lg border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
        <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
            <thead class="bg-neutral-50 dark:bg-neutral-900/50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Name') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Email') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Specialization') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Experience') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Status') }}</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-800">
                @forelse ($trainers as $trainer)
                    <tr class="transition-colors hover:bg-neutral-50 dark:hover:bg-neutral-900/30">
                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                            <a href="{{ route($prefix . 'trainers.show', $trainer) }}" wire:navigate class="flex items-center gap-3 font-medium text-neutral-900 hover:underline dark:text-white">
                                <img src="{{ $trainer->photo_url }}" alt="{{ $trainer->user?->name }}" class="h-8 w-8 rounded-full object-cover ring-1 ring-neutral-200 dark:ring-neutral-700">
                                <span>{{ $trainer->user?->name }}</span>
                            </a>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300">{{ $trainer->user?->email }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300">{{ $trainer->specialization ?? '—' }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300">{{ $trainer->experience_years }} {{ __('yrs') }}</td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                            <flux:badge :color="$trainer->status === 'active' ? 'green' : 'zinc'" size="sm">
                                {{ ucfirst($trainer->status) }}
                            </flux:badge>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                            <div class="flex items-center justify-end gap-2">
                                <flux:button
                                    as="a"
                                    :href="route($prefix . 'trainers.edit', $trainer)"
                                    variant="ghost"
                                    size="sm"
                                    icon="pencil-square"
                                    title="{{ __('Edit') }}"
                                    wire:navigate
                                ></flux:button>

                                @livewire('trainers.reset-password', ['trainer' => $trainer], key('reset-password-' . $trainer->id))

                                <flux:button
                                    variant="ghost"
                                    size="sm"
                                    icon="trash"
                                    class="!text-red-600 hover:!bg-red-50 dark:!text-red-400 dark:hover:!bg-red-950"
                                    title="{{ __('Delete') }}"
                                    wire:click.prevent="confirmDelete({{ $trainer->id }})"
                                ></flux:button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-sm text-neutral-500 dark:text-neutral-400">
                            {{ __('No trainers found.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <flux:modal name="confirm-trainer-delete" class="max-w-md" wire:model="showDeleteModal">
        <div class="space-y-4">
            <flux:heading size="lg">{{ __('Delete trainer?') }}</flux:heading>
            <flux:text>{{ __('This will also delete the linked user account and cannot be undone.') }}</flux:text>
            <div class="rounded-md bg-yellow-50 p-3 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                <div class="text-sm">
                    <strong>{{ __('Trainer') }}:</strong>
                    <span>{{ $deleteName }}</span>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3">
                <flux:button variant="outline" wire:click="cancelDelete">{{ __('Cancel') }}</flux:button>
                <flux:button variant="danger" wire:click="deleteConfirmed">{{ __('Delete') }}</flux:button>
            </div>
        </div>
    </flux:modal>

    <div class="mt-4">
        {{ $trainers->links() }}
    </div>
</div>
