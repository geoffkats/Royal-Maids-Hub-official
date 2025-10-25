<div class="space-y-6">

    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <flux:heading size="xl">{{ __('Evaluations') }}</flux:heading>
            <flux:subheading class="mt-1">
                {{ $showArchived ? __('Archived evaluations') : __('Active training performance evaluations') }}
            </flux:subheading>
        </div>

        <div class="flex gap-3">
            @if(!$showArchived)
                <flux:button as="a" :href="route('evaluations.create')" variant="primary" icon="plus">
                    {{ __('New Evaluation') }}
                </flux:button>
            @endif
            <flux:button wire:click="exportPdf" variant="ghost" icon="arrow-down-tray">
                <span wire:loading.remove wire:target="exportPdf">{{ __('Export PDF') }}</span>
                <span wire:loading wire:target="exportPdf">{{ __('Generating PDF...') }}</span>
            </flux:button>
        </div>
    </div>

    <flux:separator variant="subtle" />

    {{-- Filter Section --}}
    <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
        <div class="flex items-center gap-2 mb-4">
            <flux:icon.funnel class="size-5 text-[#F5B301]" />
            <h3 class="text-lg font-semibold text-white">{{ __('Filter & Search Evaluations') }}</h3>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Search Input -->
            <div class="lg:col-span-2">
                <flux:input 
                    wire:model.live.debounce.400ms="search" 
                    :label="__('Search Evaluations')"
                    placeholder="{{ __('Maid name, trainer, program, comments...') }}"
                    icon="magnifying-glass"
                    class="filter-input"
                />
            </div>

            <!-- Rating Filter -->
            <div>
                <flux:select wire:model.live="ratingFilter" :label="__('Rating')" class="filter-select">
                    <option value="">{{ __('All Ratings') }}</option>
                    <option value="5">{{ __('5 Stars - Excellent') }}</option>
                    <option value="4">{{ __('4 Stars - Good') }}</option>
                    <option value="3">{{ __('3 Stars - Average') }}</option>
                    <option value="2">{{ __('2 Stars - Below Average') }}</option>
                    <option value="1">{{ __('1 Star - Poor') }}</option>
                </flux:select>
            </div>

            <!-- Trainer Filter -->
            <div>
                <flux:select wire:model.live="trainerFilter" :label="__('Trainer')" class="filter-select">
                    <option value="">{{ __('All Trainers') }}</option>
                    @foreach($trainers ?? [] as $trainer)
                        <option value="{{ $trainer->id }}">{{ $trainer->name }}</option>
                    @endforeach
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
                    <option value="evaluation_date">{{ __('Evaluation Date') }}</option>
                    <option value="overall_rating">{{ __('Rating') }}</option>
                    <option value="maid_name">{{ __('Maid Name') }}</option>
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
                <span>{{ __('Showing') }} {{ $evaluations->count() }} {{ __('of') }} {{ $evaluations->total() }} {{ __('evaluations') }}</span>
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
                    <span wire:loading.remove wire:target="exportPdf">{{ __('Export PDF') }}</span>
                    <span wire:loading wire:target="exportPdf">{{ __('Generating...') }}</span>
                </flux:button>
            </div>
        </div>
    </div>

    {{-- Bulk Actions --}}
    @if(count($selectedIds) > 0)
        <div class="rounded-lg border border-blue-200 bg-blue-50 p-4 shadow-sm dark:border-blue-900 dark:bg-blue-950">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-blue-900 dark:text-blue-100">
                    {{ __(':count evaluation(s) selected', ['count' => count($selectedIds)]) }}
                </span>

                <div class="flex items-center gap-3">
                    <flux:select wire:model.defer="bulkAction" class="w-48" placeholder="{{ __('Select action...') }}">
                        <option value="">{{ __('Select action...') }}</option>
                        <option value="approve">{{ __('Approve Selected') }}</option>
                        <option value="reject">{{ __('Reject Selected') }}</option>
                        <option value="pending">{{ __('Set to Pending') }}</option>
                        @if($showArchived)
                            <option value="unarchive">{{ __('Unarchive Selected') }}</option>
                        @else
                            <option value="archive">{{ __('Archive Selected') }}</option>
                        @endif
                    </flux:select>

                    <flux:button wire:click="applyBulkAction" variant="primary" size="sm">
                        {{ __('Apply') }}
                    </flux:button>
                </div>
            </div>
        </div>
    @endif

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

    <div class="overflow-hidden rounded-lg border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-800">
        <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
            <thead class="bg-neutral-50 dark:bg-neutral-900/50">
                <tr>
                    <th class="px-6 py-3 text-left">
                        <input type="checkbox" wire:model.live="selectAll" class="rounded border-neutral-300 text-blue-600 focus:ring-blue-500">
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Maid') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Trainer') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Date') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Status') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Rating') }}</th>
                    @if(!$showArchived)
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Program') }}</th>
                    @endif
                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-800">
                @forelse ($evaluations as $evaluation)
                    <tr class="transition-colors hover:bg-neutral-50 dark:hover:bg-neutral-900/30">
                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                            <input 
                                type="checkbox" 
                                wire:model.live="selectedIds" 
                                value="{{ $evaluation->id }}" 
                                class="rounded border-neutral-300 text-blue-600 focus:ring-blue-500"
                            >
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                            <a href="{{ route('evaluations.show', $evaluation) }}" wire:navigate class="font-medium text-neutral-900 hover:underline dark:text-white">
                                {{ $evaluation->maid?->first_name }} {{ $evaluation->maid?->last_name }}
                            </a>
                            @if($evaluation->archived)
                                <flux:badge color="gray" size="xs" class="ml-2">{{ __('Archived') }}</flux:badge>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300">
                            {{ $evaluation->trainer?->user?->name }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300">
                            {{ $evaluation->evaluation_date?->format('M d, Y') }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                            <flux:badge 
                                :color="$evaluation->getStatusBadgeColor()" 
                                size="sm"
                            >
                                {{ $evaluation->status_label }}
                            </flux:badge>
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm">
                            <flux:badge 
                                :color="$evaluation->getScoreBadgeColor($evaluation->overall_rating ?? 0)" 
                                size="sm"
                            >
                                {{ number_format($evaluation->overall_rating ?? 0, 1) }}/5.0
                            </flux:badge>
                        </td>
                        @if(!$showArchived)
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-neutral-700 dark:text-neutral-300">
                                @if($evaluation->program)
                                    <span class="text-xs">{{ $evaluation->program->program_type }}</span>
                                @else
                                    <span class="text-neutral-400">—</span>
                                @endif
                            </td>
                        @endif
                        <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                            <div class="flex items-center justify-end gap-2">
                                @can('update', $evaluation)
                                    @if(!$showArchived)
                                        <flux:button 
                                            as="a" 
                                            :href="route('evaluations.edit', $evaluation)" 
                                            size="sm" 
                                            icon="pencil" 
                                            variant="ghost" 
                                            wire:navigate 
                                        />
                                    @endif
                                    
                                    <flux:button 
                                        wire:click="toggleArchive({{ $evaluation->id }})" 
                                        size="sm" 
                                        :icon="$evaluation->archived ? 'arrow-uturn-left' : 'archive-box'" 
                                        variant="ghost"
                                        :title="$evaluation->archived ? __('Unarchive') : __('Archive')"
                                    />
                                @endcan

                                @can('delete', $evaluation)
                                    <flux:button 
                                        wire:click="confirmDelete({{ $evaluation->id }})" 
                                        size="sm" 
                                        icon="trash" 
                                        variant="ghost"
                                        class="text-red-600 hover:text-red-700"
                                    />
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $showArchived ? 7 : 8 }}" class="px-6 py-12 text-center text-sm text-neutral-500 dark:text-neutral-400">
                            @if($showArchived)
                                {{ __('No archived evaluations found.') }}
                            @else
                                {{ __('No evaluations found.') }}
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $evaluations->links() }}
    </div>

    {{-- Delete Confirmation Modal --}}
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" wire:click.self="showDeleteModal = false">
            <div class="w-full max-w-md rounded-lg border border-neutral-200 bg-white p-6 shadow-xl dark:border-neutral-700 dark:bg-neutral-800">
                <div class="mb-4">
                    <flux:heading size="lg">{{ __('Delete Evaluation') }}</flux:heading>
                    <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400">
                        {{ __('Are you sure you want to delete the evaluation for :name? This action cannot be undone.', ['name' => $deleteName]) }}
                    </p>
                </div>

                <div class="flex justify-end gap-3">
                    <flux:button wire:click="showDeleteModal = false" variant="ghost">
                        {{ __('Cancel') }}
                    </flux:button>
                    <flux:button wire:click="delete" variant="danger">
                        {{ __('Delete') }}
                    </flux:button>
                </div>
            </div>
        </div>
    @endif
</div>
