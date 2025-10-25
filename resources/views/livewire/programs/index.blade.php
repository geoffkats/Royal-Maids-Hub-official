<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <flux:heading size="xl">{{ __('Training Programs') }}</flux:heading>
            <flux:subheading class="mt-1">{{ __('Manage training programs and assignments') }}</flux:subheading>
        </div>

        <flux:button as="a" :href="route('programs.create')" variant="primary" icon="plus">
            {{ __('New Program') }}
        </flux:button>
    </div>

    <flux:separator variant="subtle" />

    {{-- Filter Section --}}
    <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
        <div class="flex items-center gap-2 mb-4">
            <flux:icon.funnel class="size-5 text-[#F5B301]" />
            <h3 class="text-lg font-semibold text-white">{{ __('Filter & Search Training Programs') }}</h3>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Search Input -->
            <div class="lg:col-span-2">
                <flux:input 
                    wire:model.live.debounce.400ms="search" 
                    :label="__('Search Programs')"
                    placeholder="{{ __('Program type, maid, trainer, location...') }}"
                    icon="magnifying-glass"
                    class="filter-input"
                />
            </div>

            <!-- Status Filter -->
            <div>
                <flux:select wire:model.live="status" :label="__('Program Status')" class="filter-select">
                    <option value="">{{ __('All Statuses') }}</option>
                    @foreach ($statusOptions as $opt)
                        <option value="{{ $opt }}">{{ ucfirst($opt) }}</option>
                    @endforeach
                </flux:select>
            </div>

            <!-- Program Type Filter -->
            <div>
                <flux:select wire:model.live="programType" :label="__('Program Type')" class="filter-select">
                    <option value="">{{ __('All Types') }}</option>
                    <option value="orientation">{{ __('Orientation') }}</option>
                    <option value="skills">{{ __('Skills Training') }}</option>
                    <option value="language">{{ __('Language') }}</option>
                    <option value="safety">{{ __('Safety') }}</option>
                </flux:select>
            </div>
        </div>

        <!-- Additional Filters Row -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
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
                    <option value="program_type">{{ __('Program Type') }}</option>
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

            <!-- Show Archived -->
            <div class="flex items-end">
                <label class="flex items-center gap-2 px-4 py-2 bg-[#3B0A45] rounded-lg border border-[#F5B301]/30 cursor-pointer hover:bg-[#3B0A45]/80 transition-colors">
                    <input 
                        type="checkbox" 
                        wire:model.live="showArchived" 
                        class="rounded border-[#F5B301] text-[#F5B301] focus:ring-[#F5B301]"
                    />
                    <span class="text-sm font-medium text-white">{{ __('Show Archived') }}</span>
                </label>
            </div>
        </div>

        <!-- Filter Actions -->
        <div class="flex items-center justify-between mt-6 pt-4 border-t border-[#F5B301]/20">
            <div class="flex items-center gap-2 text-sm text-[#D1C4E9]">
                <flux:icon.information-circle class="size-4" />
                <span>{{ __('Showing') }} {{ $programs->count() }} {{ __('of') }} {{ $programs->total() }} {{ __('programs') }}</span>
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
                    wire:click="exportPdf" 
                    variant="primary" 
                    size="sm"
                    icon="arrow-down-tray"
                >
                    {{ __('Export PDF') }}
                </flux:button>
            </div>
        </div>
    </div>

    {{-- Success/Info Messages --}}
    @if (session('success'))
        <flux:callout variant="success" icon="check-circle" class="!border-green-200 !bg-green-50 dark:!border-green-900 dark:!bg-green-950">
            {{ session('success') }}
        </flux:callout>
    @endif

    @if (session('info'))
        <flux:callout variant="info" icon="information-circle" class="!border-blue-200 !bg-blue-50 dark:!border-blue-900 dark:!bg-blue-950">
            {{ session('info') }}
        </flux:callout>
    @endif

    {{-- Bulk Actions Bar --}}
    @if (count($selectedIds) > 0)
        <div class="rounded-lg border border-blue-200 bg-blue-50 p-4 dark:border-blue-900 dark:bg-blue-950">
            <div class="flex flex-wrap items-center gap-3">
                <span class="text-sm font-medium text-blue-900 dark:text-blue-100">
                    {{ __(':count programs selected', ['count' => count($selectedIds)]) }}
                </span>

                <flux:select wire:model="bulkAction" placeholder="{{ __('Select action...') }}" class="w-48">
                    <option value="scheduled">{{ __('Set as Scheduled') }}</option>
                    <option value="in-progress">{{ __('Set as In Progress') }}</option>
                    <option value="completed">{{ __('Set as Completed') }}</option>
                    <option value="cancelled">{{ __('Set as Cancelled') }}</option>
                    <option value="archive">{{ __('Archive') }}</option>
                    <option value="unarchive">{{ __('Unarchive') }}</option>
                </flux:select>

                <flux:button 
                    wire:click="applyBulkAction" 
                    size="sm" 
                    variant="primary"
                    :disabled="empty($bulkAction)"
                >
                    {{ __('Apply') }}
                </flux:button>

                <flux:button 
                    wire:click="$set('selectedIds', []); $set('selectAll', false)" 
                    size="sm" 
                    variant="ghost"
                >
                    {{ __('Clear Selection') }}
                </flux:button>
            </div>
        </div>
    @endif

    {{-- Programs Table --}}
    <div class="overflow-hidden rounded-lg border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
        <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
            <thead class="bg-neutral-50 dark:bg-neutral-900/50">
                <tr>
                    <th class="w-12 px-6 py-3">
                        <input 
                            type="checkbox" 
                            wire:model.live="selectAll"
                            class="rounded border-neutral-300 text-blue-600 focus:ring-blue-500 dark:border-neutral-600 dark:bg-neutral-800"
                        />
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Program') }}</th>
                    @unless($showArchived)
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Maid') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Trainer') }}</th>
                    @endunless
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Dates') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Progress') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Status') }}</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-800">
                @forelse ($programs as $program)
                    <tr class="transition-colors hover:bg-neutral-50 dark:hover:bg-neutral-900/30">
                        <td class="whitespace-nowrap px-6 py-4">
                            <input 
                                type="checkbox" 
                                wire:model.live="selectedIds"
                                value="{{ $program->id }}"
                                class="rounded border-neutral-300 text-blue-600 focus:ring-blue-500 dark:border-neutral-600 dark:bg-neutral-800"
                            />
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                            <a href="{{ route('programs.show', $program) }}" wire:navigate class="font-medium text-neutral-900 hover:underline dark:text-white">
                                {{ $program->program_type }}
                            </a>
                            @if($program->archived)
                                <flux:badge color="zinc" size="sm" class="ml-2">{{ __('Archived') }}</flux:badge>
                            @endif
                        </td>
                        @unless($showArchived)
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300">
                                {{ $program->maid?->first_name }} {{ $program->maid?->last_name }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300">
                                {{ $program->trainer?->user?->name }}
                            </td>
                        @endunless
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300">
                            {{ $program->start_date?->format('M d') }} - {{ $program->end_date?->format('M d, Y') ?? 'Ongoing' }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300">
                            {{ $program->hours_completed }}/{{ $program->hours_required }} hrs
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                            <flux:badge 
                                :color="match($program->status) {
                                    'scheduled' => 'blue',
                                    'in-progress' => 'yellow',
                                    'completed' => 'green',
                                    'cancelled' => 'red',
                                    default => 'zinc'
                                }" 
                                size="sm"
                            >
                                {{ ucfirst($program->status) }}
                            </flux:badge>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                            <div class="flex items-center justify-end gap-1">
                                @can('update', $program)
                                    <flux:button 
                                        as="a" 
                                        :href="route('programs.edit', $program)" 
                                        size="sm" 
                                        icon="pencil" 
                                        variant="ghost" 
                                        wire:navigate 
                                    />
                                    
                                    <flux:button 
                                        wire:click="toggleArchive({{ $program->id }})" 
                                        size="sm" 
                                        :icon="$program->archived ? 'arrow-uturn-left' : 'archive-box'" 
                                        variant="ghost"
                                        :title="$program->archived ? __('Unarchive') : __('Archive')"
                                    />
                                @endcan

                                @can('delete', $program)
                                    <flux:button 
                                        wire:click="confirmDelete({{ $program->id }}, '{{ addslashes($program->program_type) }}')" 
                                        size="sm" 
                                        icon="trash" 
                                        variant="ghost"
                                        class="text-red-600 hover:text-red-700 dark:text-red-400"
                                    />
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-sm text-neutral-500 dark:text-neutral-400">
                            {{ __('No training programs found.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $programs->links() }}
    </div>

    {{-- Delete Confirmation Modal --}}
    <flux:modal name="delete-program" wire:model="showDeleteModal" class="space-y-6">
        <div>
            <flux:heading size="lg">{{ __('Delete Training Program') }}</flux:heading>
            <flux:subheading class="mt-2">
                {{ __('Are you sure you want to delete the program ":name"? This action cannot be undone.', ['name' => $deleteName]) }}
            </flux:subheading>
        </div>

        <div class="flex gap-2 justify-end">
            <flux:button wire:click="$set('showDeleteModal', false)" variant="ghost">
                {{ __('Cancel') }}
            </flux:button>
            <flux:button wire:click="delete" variant="danger">
                {{ __('Delete Program') }}
            </flux:button>
        </div>
    </flux:modal>
</div>
