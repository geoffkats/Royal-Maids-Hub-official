<div class="min-h-screen bg-slate-50 dark:bg-slate-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Header --}}
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Subscription Packages</h1>
                    <p class="mt-2 text-slate-600 dark:text-slate-400">
                        @if($isAdmin)
                            Manage subscription packages and pricing tiers
                        @else
                            Choose the perfect package for your household needs
                        @endif
                    </p>
                </div>
                
                @can('create', App\Models\Package::class)
                    <div class="flex items-center gap-3">
                        <a href="{{ route('packages.create') }}" 
                           class="inline-flex items-center px-6 py-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 hover:scale-105">
                            <x-flux::icon.plus class="w-5 h-5 mr-2" />
                            Add Package
                        </a>
                    </div>
                @endcan
            </div>
        </div>

        {{-- Flash Messages --}}
        @if (session()->has('message'))
            <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl">
                <div class="flex items-center">
                    <x-flux::icon.check-circle class="w-5 h-5 text-emerald-600 dark:text-emerald-400 mr-3" />
                    <p class="text-sm font-medium text-emerald-800 dark:text-emerald-200">{{ session('message') }}</p>
                </div>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
                <div class="flex items-center">
                    <x-flux::icon.exclamation-triangle class="w-5 h-5 text-red-600 dark:text-red-400 mr-3" />
                    <p class="text-sm font-medium text-red-800 dark:text-red-200">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        {{-- Filter Section --}}
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg mb-8">
            <div class="flex items-center gap-2 mb-4">
                <flux:icon.funnel class="size-5 text-[#F5B301]" />
                <h3 class="text-lg font-semibold text-white">{{ __('Filter & Search Packages') }}</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search Input -->
                <div class="lg:col-span-2">
                    <flux:input 
                        wire:model.live.debounce.400ms="search" 
                        :label="__('Search Packages')"
                        placeholder="{{ __('Package name, tier, features...') }}"
                        icon="magnifying-glass"
                        class="filter-input"
                    />
                </div>

                <!-- Tier Filter -->
                <div>
                    <flux:select wire:model.live="tierFilter" :label="__('Package Tier')" class="filter-select">
                        <option value="">{{ __('All Tiers') }}</option>
                        <option value="Silver">{{ __('Silver') }}</option>
                        <option value="Gold">{{ __('Gold') }}</option>
                        <option value="Platinum">{{ __('Platinum') }}</option>
                    </flux:select>
                </div>

                <!-- Status Filter (Admin Only) -->
                @if($isAdmin)
                    <div>
                        <flux:select wire:model.live="statusFilter" :label="__('Status')" class="filter-select">
                            <option value="">{{ __('All Packages') }}</option>
                            <option value="active">{{ __('Active Only') }}</option>
                            <option value="inactive">{{ __('Inactive Only') }}</option>
                        </flux:select>
                    </div>
                @endif
            </div>

            <!-- Filter Actions -->
            <div class="flex items-center justify-between mt-6 pt-4 border-t border-[#F5B301]/20">
                <div class="flex items-center gap-2 text-sm text-[#D1C4E9]">
                    <flux:icon.information-circle class="size-4" />
                    <span>{{ __('Showing') }} {{ $packages->count() }} {{ __('packages') }}</span>
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
                    
                    @can('create', App\Models\Package::class)
                        <flux:button 
                            as="a"
                            :href="route('packages.create')"
                            variant="primary" 
                            size="sm"
                            icon="plus"
                        >
                            {{ __('Add Package') }}
                        </flux:button>
                    @endcan
                </div>
            </div>
        </div>

        {{-- Packages Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            @forelse($packages as $package)
                <div class="group relative bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 overflow-hidden transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
                    {{-- Package Header --}}
                    <div class="relative p-8 text-center {{ $package->name === 'Silver' ? 'bg-slate-100 dark:bg-slate-700' : ($package->name === 'Gold' ? 'bg-amber-50 dark:bg-amber-900/20' : 'bg-purple-50 dark:bg-purple-900/20') }}">
                        {{-- Status Badge --}}
                        @if($isAdmin)
                            <div class="absolute top-4 right-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $package->is_active ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' }}">
                                    {{ $package->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        @endif

                        {{-- Package Icon --}}
                        <div class="w-16 h-16 mx-auto mb-4 rounded-2xl flex items-center justify-center {{ $package->name === 'Silver' ? 'bg-slate-600' : ($package->name === 'Gold' ? 'bg-amber-500' : 'bg-purple-600') }}">
                            @if($package->name === 'Silver')
                                <x-flux::icon.check-circle class="w-8 h-8 text-white" />
                            @elseif($package->name === 'Gold')
                                <x-flux::icon.star class="w-8 h-8 text-white" />
                            @else
                                <x-flux::icon.bolt class="w-8 h-8 text-white" />
                            @endif
                        </div>

                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">
                            {{ $package->name }}
                        </h3>
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-6">
                            {{ $package->tier }} Package
                        </p>

                        {{-- Pricing --}}
                        <div class="mb-4">
                            <div class="flex items-baseline justify-center">
                                <span class="text-4xl font-bold text-slate-900 dark:text-white">
                                    {{ number_format($package->base_price / 1000) }}k
                                </span>
                                <span class="text-slate-600 dark:text-slate-400 ml-2">UGX/month</span>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                Base price for {{ $package->base_family_size }} members
                            </p>
                            @if($package->additional_member_cost > 0)
                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                    +{{ number_format($package->additional_member_cost) }} UGX per extra member
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Package Details --}}
                    <div class="p-8">
                        {{-- Training Section --}}
                        <div class="mb-6">
                            <div class="flex items-center mb-3">
                                <x-flux::icon.academic-cap class="w-5 h-5 text-slate-600 dark:text-slate-400 mr-2" />
                                <h4 class="font-semibold text-slate-900 dark:text-white">{{ $package->training_weeks }} Weeks Training</h4>
                            </div>
                            @if($package->training_includes && count($package->training_includes) > 0)
                                <ul class="space-y-2">
                                    @foreach($package->training_includes as $training)
                                        <li class="flex items-start text-sm text-slate-600 dark:text-slate-400">
                                            <x-flux::icon.check class="w-4 h-4 text-emerald-500 mr-2 mt-0.5 flex-shrink-0" />
                                            {{ $training }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        {{-- Features Grid --}}
                        <div class="grid grid-cols-1 gap-4 mb-6">
                            <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-700 rounded-xl">
                                <div class="flex items-center">
                                    <x-flux::icon.calendar class="w-5 h-5 text-slate-600 dark:text-slate-400 mr-3" />
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Backup Days</span>
                                </div>
                                <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $package->backup_days_per_year }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-700 rounded-xl">
                                <div class="flex items-center">
                                    <x-flux::icon.user-group class="w-5 h-5 text-slate-600 dark:text-slate-400 mr-3" />
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Free Replacements</span>
                                </div>
                                <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $package->free_replacements }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-700 rounded-xl">
                                <div class="flex items-center">
                                    <x-flux::icon.clipboard-document-check class="w-5 h-5 text-slate-600 dark:text-slate-400 mr-3" />
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Evaluations/Year</span>
                                </div>
                                <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $package->evaluations_per_year }}</span>
                            </div>
                        </div>

                        {{-- Features List --}}
                        @if($package->features && count($package->features) > 0)
                            <div class="mb-6">
                                <h4 class="font-semibold text-slate-900 dark:text-white mb-3">Key Features</h4>
                                <ul class="space-y-2">
                                    @foreach($package->features as $feature)
                                        <li class="flex items-start text-sm text-slate-600 dark:text-slate-400">
                                            <x-flux::icon.check class="w-4 h-4 text-emerald-500 mr-2 mt-0.5 flex-shrink-0" />
                                            {{ $feature }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Admin Actions --}}
                        @if($isAdmin)
                            <div class="pt-6 border-t border-slate-200 dark:border-slate-700">
                                <div class="flex gap-2">
                                    <a href="{{ route('packages.edit', $package) }}" 
                                       class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-sm font-semibold rounded-xl hover:bg-slate-800 dark:hover:bg-slate-100 transition-colors">
                                        <x-flux::icon.pencil class="w-4 h-4 mr-2" />
                                        Edit
                                    </a>
                                    
                                    <button wire:click="toggleActive({{ $package->id }})" 
                                            class="flex-1 inline-flex justify-center items-center px-4 py-2 {{ $package->is_active ? 'bg-amber-600 hover:bg-amber-700' : 'bg-emerald-600 hover:bg-emerald-700' }} text-white text-sm font-semibold rounded-xl transition-colors">
                                        <x-flux::icon.power class="w-4 h-4 mr-2" />
                                        {{ $package->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                    
                                    <button wire:click="delete({{ $package->id }})" 
                                            wire:confirm="Are you sure you want to delete this package?"
                                            class="inline-flex justify-center items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl transition-colors">
                                        <x-flux::icon.trash class="w-4 h-4" />
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16">
                    <x-flux::icon.cube class="w-16 h-16 text-slate-300 dark:text-slate-600 mx-auto mb-4" />
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">No packages found</h3>
                    <p class="text-slate-600 dark:text-slate-400">Try adjusting your search criteria or create a new package.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
