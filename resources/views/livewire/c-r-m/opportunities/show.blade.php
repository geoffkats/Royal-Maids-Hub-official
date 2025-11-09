<div>
    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
            <div class="flex items-center gap-2 text-green-700 dark:text-green-300">
                <x-flux::icon.check-circle class="w-5 h-5" />
                <span class="font-medium">{{ session('message') }}</span>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
            <div class="flex items-center gap-2 text-red-700 dark:text-red-300">
                <x-flux::icon.x-circle class="w-5 h-5" />
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Header Section -->
    <div class="bg-[#512B58] border border-[#F5B301]/30 rounded-xl p-6 text-white mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <x-flux::icon.chart-bar class="w-8 h-8" />
                <div>
                    <h1 class="text-3xl font-bold">{{ $opportunity->name }}</h1>
                    <p class="text-indigo-100 mt-1">{{ __('Opportunity Details') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('crm.opportunities.edit', $opportunity) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg font-semibold text-white hover:bg-white/30 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <x-flux::icon.pencil class="w-4 h-4" />
                    {{ __('Edit') }}
                </a>
                <a href="{{ route('crm.opportunities.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg font-semibold text-white hover:bg-white/30 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <x-flux::icon.arrow-left class="w-4 h-4" />
                    {{ __('Back') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('message'))
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-200 px-6 py-4 rounded-xl shadow-sm flex items-center gap-3 mb-6">
            <x-flux::icon.check-circle class="w-5 h-5 text-green-600 dark:text-green-400" />
            <span class="font-medium">{{ session('message') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Opportunity Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-gradient-to-r from-[#512B58] to-[#3B0A45] border border-[#F5B301]/30 rounded-xl p-6 shadow-lg">
                <h2 class="text-xl font-semibold text-[#D1C4E9] mb-6 flex items-center gap-2">
                    <x-flux::icon.information-circle class="w-5 h-5" />
                    {{ __('Opportunity Information') }}
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-[#D1C4E9] mb-2">{{ __('Name') }}</label>
                        <p class="text-white">{{ $opportunity->name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#D1C4E9] mb-2">{{ __('Status') }}</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($opportunity->isOpen()) bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                            @elseif($opportunity->isWon()) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                            @elseif($opportunity->isLost()) bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                            @endif">
                            {{ $opportunity->getStatusLabel() }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#D1C4E9] mb-2">{{ __('Amount') }}</label>
                        <p class="text-white">{{ $opportunity->amount ? \App\Helpers\CurrencyHelper::format($opportunity->amount) : 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#D1C4E9] mb-2">{{ __('Probability') }}</label>
                        <div class="flex items-center">
                            <div class="w-full bg-neutral-200 dark:bg-neutral-700 rounded-full h-2 mr-2">
                                <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $opportunity->probability }}%"></div>
                            </div>
                            <span class="text-sm text-white">{{ $opportunity->probability }}%</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#D1C4E9] mb-2">{{ __('Close Date') }}</label>
                        <p class="text-white">{{ $opportunity->close_date ? $opportunity->close_date->format('M j, Y') : 'No close date' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#D1C4E9] mb-2">{{ __('Stage') }}</label>
                        <p class="text-white">{{ $opportunity->stage?->name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#D1C4E9] mb-2">{{ __('Assigned To') }}</label>
                        <p class="text-white">{{ $opportunity->assignedTo?->name ?: 'Unassigned' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#D1C4E9] mb-2">{{ __('Created By') }}</label>
                        <p class="text-white">{{ $opportunity->createdBy?->name ?: 'Unknown' }}</p>
                    </div>
                </div>

                @if($opportunity->description)
                <div class="mt-6">
                    <label class="block text-sm font-medium text-[#D1C4E9] mb-2">{{ __('Description') }}</label>
                    <div class="bg-white/10 border border-[#F5B301]/30 rounded-lg p-4 text-white">
                        {{ $opportunity->description }}
                    </div>
                </div>
                @endif
            </div>

            <!-- Related Lead -->
            @if($opportunity->lead)
            <div class="bg-gradient-to-r from-[#512B58] to-[#3B0A45] border border-[#F5B301]/30 rounded-xl p-6 shadow-lg">
                <h2 class="text-xl font-semibold text-[#D1C4E9] mb-6 flex items-center gap-2">
                    <x-flux::icon.user-plus class="w-5 h-5" />
                    {{ __('Related Lead') }}
                </h2>
                
                <div class="bg-white/10 border border-[#F5B301]/30 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-white font-medium">{{ $opportunity->lead->first_name }} {{ $opportunity->lead->last_name }}</p>
                            <p class="text-[#D1C4E9] text-sm">{{ $opportunity->lead->email }}</p>
                        </div>
                        <a href="{{ route('crm.leads.show', $opportunity->lead) }}" 
                           class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold transition-all duration-200">
                            <x-flux::icon.eye class="w-4 h-4" />
                            {{ __('View Lead') }}
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Actions -->
            <div class="bg-gradient-to-r from-[#512B58] to-[#3B0A45] border border-[#F5B301]/30 rounded-xl p-6 shadow-lg">
                <h3 class="text-lg font-semibold text-[#D1C4E9] mb-4">{{ __('Actions') }}</h3>
                
                <div class="space-y-3">
                    @if($opportunity->isOpen())
                    <button wire:click="markWon" 
                            wire:confirm="Are you sure you want to mark this opportunity as won?"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 disabled:bg-green-400 text-white rounded-lg font-semibold transition-all duration-200 shadow-lg hover:shadow-xl">
                        <x-flux::icon.check-circle class="w-4 h-4" wire:loading.remove wire:target="markWon" />
                        <svg wire:loading wire:target="markWon" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="markWon">{{ __('Mark Won') }}</span>
                        <span wire:loading wire:target="markWon">{{ __('Processing...') }}</span>
                    </button>
                    @else
                    <div class="w-full p-4 bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-center">
                        <p class="text-gray-600 dark:text-gray-400 font-medium">
                            @if($opportunity->isWon())
                                <span class="text-green-600 dark:text-green-400">✓ {{ __('Opportunity Won') }}</span>
                            @elseif($opportunity->isLost())
                                <span class="text-red-600 dark:text-red-400">✗ {{ __('Opportunity Lost') }}</span>
                            @else
                                {{ __('Opportunity Closed') }}
                            @endif
                        </p>
                    </div>

                    {{-- Convert to Client Button (appears after winning) --}}
                    @if($opportunity->isWon() && $opportunity->lead && !$opportunity->lead->isConverted())
                    <div class="mt-4 p-4 bg-indigo-50 dark:bg-indigo-900/20 border-2 border-indigo-200 dark:border-indigo-800 rounded-lg">
                        <div class="flex items-start gap-3 mb-3">
                            <x-flux::icon.light-bulb class="w-5 h-5 text-indigo-600 dark:text-indigo-400 flex-shrink-0 mt-0.5" />
                            <div class="text-sm text-indigo-700 dark:text-indigo-300">
                                <p class="font-semibold mb-1">{{ __('Next Step: Convert to Client') }}</p>
                                <p class="text-xs">{{ __('This opportunity is won! Convert the lead to a client to start service delivery.') }}</p>
                            </div>
                        </div>
                        <a href="{{ route('crm.leads.show', $opportunity->lead) }}" 
                           class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold transition-all duration-200 shadow-lg hover:shadow-xl">
                            <x-flux::icon.user-group class="w-4 h-4" />
                            {{ __('Convert Lead to Client') }}
                        </a>
                    </div>
                    @elseif($opportunity->isWon() && $opportunity->lead && $opportunity->lead->isConverted())
                    <div class="mt-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                        <div class="flex items-center gap-2 text-green-700 dark:text-green-300 mb-2">
                            <x-flux::icon.check-circle class="w-5 h-5" />
                            <span class="font-semibold text-sm">{{ __('Lead Already Converted') }}</span>
                        </div>
                        <a href="{{ route('clients.show', $opportunity->lead->client) }}" 
                           class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition-all duration-200 shadow-lg hover:shadow-xl">
                            <x-flux::icon.eye class="w-4 h-4" />
                            {{ __('View Client') }}
                        </a>
                    </div>
                    @endif
                    @endif

                    @if($opportunity->isOpen())
                    <button wire:click="markLost" 
                            wire:confirm="Are you sure you want to mark this opportunity as lost?"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 disabled:bg-red-400 text-white rounded-lg font-semibold transition-all duration-200 shadow-lg hover:shadow-xl">
                        <x-flux::icon.x-circle class="w-4 h-4" wire:loading.remove wire:target="markLost" />
                        <svg wire:loading wire:target="markLost" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="markLost">{{ __('Mark Lost') }}</span>
                        <span wire:loading wire:target="markLost">{{ __('Processing...') }}</span>
                    </button>
                    @endif
                </div>
            </div>

            <!-- Expected Value -->
            <div class="bg-gradient-to-r from-[#512B58] to-[#3B0A45] border border-[#F5B301]/30 rounded-xl p-6 shadow-lg">
                <h3 class="text-lg font-semibold text-[#D1C4E9] mb-4">{{ __('Expected Value') }}</h3>
                
                <div class="text-center">
                    <div class="text-3xl font-bold text-white">
                        @currency($opportunity->expected_value)
                    </div>
                    <div class="text-sm text-[#D1C4E9] mt-1">
                        {{ $opportunity->probability }}% of {{ \App\Helpers\CurrencyHelper::format($opportunity->amount ?: 0) }}
                    </div>
                </div>
            </div>

            <!-- Timestamps -->
            <div class="bg-gradient-to-r from-[#512B58] to-[#3B0A45] border border-[#F5B301]/30 rounded-xl p-6 shadow-lg">
                <h3 class="text-lg font-semibold text-[#D1C4E9] mb-4">{{ __('Timestamps') }}</h3>
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-[#D1C4E9] mb-1">{{ __('Created') }}</label>
                        <p class="text-white text-sm">{{ $opportunity->created_at->format('M j, Y g:i A') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-[#D1C4E9] mb-1">{{ __('Updated') }}</label>
                        <p class="text-white text-sm">{{ $opportunity->updated_at->format('M j, Y g:i A') }}</p>
</div>

                    @if($opportunity->won_at)
                    <div>
                        <label class="block text-sm font-medium text-[#D1C4E9] mb-1">{{ __('Won') }}</label>
                        <p class="text-white text-sm">{{ $opportunity->won_at->format('M j, Y g:i A') }}</p>
                    </div>
                    @endif
                    
                    @if($opportunity->lost_at)
                    <div>
                        <label class="block text-sm font-medium text-[#D1C4E9] mb-1">{{ __('Lost') }}</label>
                        <p class="text-white text-sm">{{ $opportunity->lost_at->format('M j, Y g:i A') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>