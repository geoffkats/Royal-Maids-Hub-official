<div class="space-y-6">
    <!-- Header + Actions -->
    <div class="bg-[#512B58] border border-[#F5B301]/30 rounded-xl p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold flex items-center gap-3">
                    <x-flux::icon.user class="w-8 h-8" />
                    {{ $lead->full_name }}
                </h1>
                <p class="text-indigo-100 mt-2">
                    {{ $lead->email }} • {{ $lead->phone ?: __('No phone') }} • {{ ucfirst($lead->status) }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                @if($lead->isConverted())
                    <!-- Lead already converted - show client link -->
                    @if($lead->client)
                        <a href="{{ route('clients.show', $lead->client) }}"
                           class="inline-flex items-center gap-2 px-5 py-3 bg-green-600 border border-green-500 rounded-lg font-semibold text-white hover:bg-green-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                            <x-flux::icon.check-circle class="w-5 h-5" />
                            {{ __('View Client') }}
                        </a>
                    @endif
                    <span class="px-4 py-2 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-lg font-semibold">
                        ✓ {{ __('Converted') }}
                    </span>
                @else
                    <!-- Lead not converted - show conversion buttons -->
                    @if(!$lead->opportunities->isEmpty())
                        <!-- Already has opportunity, don't show convert to opportunity button -->
                    @else
                        <button wire:click="openOpportunityConvertModal"
                                class="inline-flex items-center gap-2 px-5 py-3 bg-emerald-600 border border-emerald-500 rounded-lg font-semibold text-white hover:bg-emerald-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                            <x-flux::icon.arrow-right class="w-5 h-5" />
                            {{ __('Convert to Opportunity') }}
                        </button>
                    @endif

                    @can('convert', $lead)
                        @if($lead->canBeConverted())
                            <button wire:click="openConvertModal"
                                    class="inline-flex items-center gap-2 px-5 py-3 bg-indigo-600 border border-indigo-500 rounded-lg font-semibold text-white hover:bg-indigo-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                                <x-flux::icon.user-group class="w-5 h-5" />
                                {{ __('Convert to Client') }}
                            </button>
                        @endif
                    @endcan
                @endif
            </div>
        </div>
    </div>

    <!-- Flash messages -->
    @if (session('message'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-xl shadow-sm">
            {{ session('message') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-xl shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-xl p-6">
                <h2 class="text-lg font-semibold mb-4">{{ __('Lead Details') }}</h2>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-sm">
                    <div><dt class="text-neutral-500">{{ __('Email') }}</dt><dd class="text-neutral-900 dark:text-neutral-100">{{ $lead->email ?: '—' }}</dd></div>
                    <div><dt class="text-neutral-500">{{ __('Phone') }}</dt><dd class="text-neutral-900 dark:text-neutral-100">{{ $lead->phone ?: '—' }}</dd></div>
                    <div><dt class="text-neutral-500">{{ __('Company') }}</dt><dd class="text-neutral-900 dark:text-neutral-100">{{ $lead->company ?: '—' }}</dd></div>
                    <div><dt class="text-neutral-500">{{ __('Job Title') }}</dt><dd class="text-neutral-900 dark:text-neutral-100">{{ $lead->job_title ?: '—' }}</dd></div>
                    <div><dt class="text-neutral-500">{{ __('Source') }}</dt><dd class="text-neutral-900 dark:text-neutral-100">{{ $lead->source?->name ?: '—' }}</dd></div>
                    <div><dt class="text-neutral-500">{{ __('Assigned To') }}</dt><dd class="text-neutral-900 dark:text-neutral-100">{{ $lead->owner?->name ?: '—' }}</dd></div>
                    <div><dt class="text-neutral-500">{{ __('Status') }}</dt><dd class="text-neutral-900 dark:text-neutral-100"><span class="px-2 py-0.5 rounded-full text-xs bg-neutral-100 dark:bg-neutral-800">{{ ucfirst($lead->status) }}</span></dd></div>
                    <div><dt class="text-neutral-500">{{ __('Score') }}</dt><dd class="text-neutral-900 dark:text-neutral-100">{{ $lead->score ?? '—' }}</dd></div>
                    @if($lead->isConverted() && $lead->client)
                        <div class="sm:col-span-2">
                            <dt class="text-neutral-500 mb-1">{{ __('Converted to Client') }}</dt>
                            <dd class="text-neutral-900 dark:text-neutral-100">
                                <a href="{{ route('clients.show', $lead->client) }}" 
                                   class="inline-flex items-center gap-2 px-3 py-1.5 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg text-green-700 dark:text-green-300 hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors">
                                    <x-flux::icon.check-circle class="w-4 h-4" />
                                    <span class="font-medium">{{ $lead->client->contact_person ?: 'Client #' . $lead->client->id }}</span>
                                    <span class="text-xs text-neutral-500">→</span>
                                </a>
                                @if($lead->converted_at)
                                    <span class="ml-2 text-xs text-neutral-500">
                                        {{ __('on') }} {{ $lead->converted_at->format('M d, Y') }}
                                    </span>
                                @endif
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>

            <!-- Bookings Section -->
            <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold flex items-center gap-2">
                        <x-flux::icon.calendar class="w-5 h-5" />
                        {{ __('Bookings') }}
                    </h2>
                    @if($lead->hasBookings())
                        <span class="px-3 py-1 bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 text-xs font-semibold rounded-full">
                            {{ $lead->getBookingCount() }} {{ $lead->getBookingCount() === 1 ? __('Booking') : __('Bookings') }}
                        </span>
                    @endif
                </div>
                <div class="space-y-3">
                    @forelse($lead->bookings as $booking)
                        <div class="flex items-start gap-3 p-4 rounded-lg border border-neutral-200 dark:border-neutral-700 hover:border-indigo-300 dark:hover:border-indigo-600 transition-colors">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-sm font-medium text-neutral-900 dark:text-neutral-100">
                                        {{ __('Booking #') }}{{ $booking->id }}
                                    </span>
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                        @if($booking->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                        @elseif($booking->status === 'confirmed') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                        @elseif($booking->status === 'active') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @elseif($booking->status === 'completed') bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                        @elseif($booking->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                        @else bg-neutral-100 text-neutral-800 dark:bg-neutral-900 dark:text-neutral-200
                                        @endif">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </div>
                                <div class="text-xs text-neutral-600 dark:text-neutral-400 space-y-1">
                                    @if($booking->service_tier)
                                        <div>{{ __('Service:') }} <span class="font-medium">{{ $booking->service_tier }}</span></div>
                                    @endif
                                    @if($booking->start_date)
                                        <div>{{ __('Start Date:') }} <span class="font-medium">{{ $booking->start_date->format('M d, Y') }}</span></div>
                                    @endif
                                    @if($booking->maid)
                                        <div>{{ __('Maid:') }} <span class="font-medium">{{ $booking->maid->first_name }} {{ $booking->maid->last_name }}</span></div>
                                    @endif
                                </div>
                            </div>
                            <a href="{{ route('bookings.show', $booking) }}" 
                               class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 text-sm font-medium">
                                {{ __('View') }} →
                            </a>
                        </div>
                    @empty
                        <div class="text-sm text-neutral-500 text-center py-4">
                            <x-flux::icon.calendar class="w-8 h-8 mx-auto mb-2 text-neutral-400" />
                            {{ __('No bookings linked to this lead yet.') }}
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-xl p-6">
                <h2 class="text-lg font-semibold mb-4">{{ __('Activities') }}</h2>
                <div class="space-y-3">
                    @forelse($activities as $act)
                        <div class="flex items-start gap-3 p-3 rounded-lg border border-neutral-200 dark:border-neutral-700">
                            <div class="text-sm font-medium">{{ $act->subject }}</div>
                            <div class="ml-auto text-xs text-neutral-500">{{ $act->created_at?->diffForHumans() }}</div>
                        </div>
                    @empty
                        <div class="text-sm text-neutral-500">{{ __('No activities yet.') }}</div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="space-y-6">
            <div class="bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-xl p-6">
                <h2 class="text-lg font-semibold mb-4">{{ __('Notes') }}</h2>
                <p class="text-sm text-neutral-700 dark:text-neutral-300 whitespace-pre-line">{{ $lead->notes ?: '—' }}</p>
            </div>
        </div>
    </div>

    <!-- Convert to Client Modal -->
    <x-flux::modal wire:model="showConvertModal" class="max-w-2xl">
        <x-slot name="title">
            <div class="flex items-center gap-3">
                <x-flux::icon.user-group class="w-6 h-6 text-indigo-600" />
                {{ __('Convert Lead to Client') }}
            </div>
        </x-slot>
        <div class="space-y-6">
            <!-- Lead Summary -->
            <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-lg p-4 border border-indigo-200 dark:border-indigo-800">
                <h3 class="font-semibold text-indigo-900 dark:text-indigo-100 mb-2">{{ __('Lead Information') }}</h3>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <span class="text-indigo-600 dark:text-indigo-400">{{ __('Name:') }}</span>
                        <span class="ml-2 font-medium text-indigo-900 dark:text-indigo-100">{{ $lead->full_name }}</span>
                    </div>
                    <div>
                        <span class="text-indigo-600 dark:text-indigo-400">{{ __('Email:') }}</span>
                        <span class="ml-2 font-medium text-indigo-900 dark:text-indigo-100">{{ $lead->email }}</span>
                    </div>
                    @if($lead->phone)
                    <div>
                        <span class="text-indigo-600 dark:text-indigo-400">{{ __('Phone:') }}</span>
                        <span class="ml-2 font-medium text-indigo-900 dark:text-indigo-100">{{ $lead->phone }}</span>
                    </div>
                    @endif
                    @if($lead->hasBookings())
                    <div>
                        <span class="text-indigo-600 dark:text-indigo-400">{{ __('Bookings:') }}</span>
                        <span class="ml-2 font-medium text-indigo-900 dark:text-indigo-100">{{ $lead->getBookingCount() }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Conversion Options -->
            <div class="space-y-4">
                <h3 class="font-semibold text-neutral-900 dark:text-neutral-100">{{ __('Conversion Method') }}</h3>
                <p class="text-sm text-neutral-600 dark:text-neutral-400">
                    {{ __('Choose how you want to convert this lead to a client.') }}
                </p>
                
                <div class="space-y-3">
                    <!-- Option 1: Create New Client -->
                    <label class="flex items-start gap-3 p-4 border-2 rounded-lg cursor-pointer transition-all
                        {{ $convertAction === 'create' ? 'border-indigo-600 bg-indigo-50 dark:bg-indigo-900/20' : 'border-neutral-200 dark:border-neutral-700 hover:border-indigo-300' }}">
                        <input type="radio" wire:model.live="convertAction" value="create" class="mt-1 text-indigo-600 focus:ring-indigo-500" />
                        <div class="flex-1">
                            <div class="font-semibold text-neutral-900 dark:text-neutral-100 mb-1">
                                {{ __('Create a New Client') }}
                            </div>
                            <p class="text-sm text-neutral-600 dark:text-neutral-400">
                                {{ __('Create a fresh client record from this lead\'s information. All bookings will be transferred to the new client.') }}
                            </p>
                            @if($convertAction === 'create')
                                <div class="mt-3 p-3 bg-white dark:bg-neutral-800 rounded border border-indigo-200 dark:border-indigo-700">
                                    <div class="flex items-center gap-2 text-sm text-green-700 dark:text-green-300">
                                        <x-flux::icon.check-circle class="w-4 h-4" />
                                        <span class="font-medium">{{ __('Ready to convert') }}</span>
                                    </div>
                                    <p class="text-xs text-neutral-600 dark:text-neutral-400 mt-1">
                                        {{ __('Click "Convert to Client" below to proceed.') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </label>

                    <!-- Option 2: Link to Existing Client -->
                    <label class="flex items-start gap-3 p-4 border-2 rounded-lg cursor-pointer transition-all
                        {{ $convertAction === 'existing' ? 'border-indigo-600 bg-indigo-50 dark:bg-indigo-900/20' : 'border-neutral-200 dark:border-neutral-700 hover:border-indigo-300' }}">
                        <input type="radio" wire:model.live="convertAction" value="existing" class="mt-1 text-indigo-600 focus:ring-indigo-500" />
                        <div class="flex-1">
                            <div class="font-semibold text-neutral-900 dark:text-neutral-100 mb-1">
                                {{ __('Link to Existing Client') }}
                            </div>
                            <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-3">
                                {{ __('Merge this lead with an existing client record. Prevents duplicate clients.') }}
                            </p>
                            @if ($convertAction === 'existing')
                                <div class="space-y-3 mt-3">
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">
                                            {{ __('Search for Client') }}
                                        </label>
                                        <input type="text" 
                                               wire:model.debounce.300ms="clientQuery" 
                                               placeholder="{{ __('Type name, email, or phone...') }}" 
                                               class="w-full px-4 py-2 border border-neutral-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-neutral-900 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                                    </div>
                                    @if (!empty($clientSuggestions))
                                        <div class="border border-neutral-200 dark:border-neutral-700 rounded-lg divide-y divide-neutral-200 dark:divide-neutral-700 bg-white dark:bg-neutral-900 max-h-56 overflow-auto">
                                            @foreach ($clientSuggestions as $s)
                                                <button type="button" 
                                                        wire:click="selectExistingClient({{ $s['id'] }})" 
                                                        class="w-full text-left px-4 py-3 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-colors
                                                        {{ $existingClientId === $s['id'] ? 'bg-indigo-50 dark:bg-indigo-900/20' : '' }}">
                                                    <div class="flex items-center gap-2">
                                                        @if($existingClientId === $s['id'])
                                                            <x-flux::icon.check-circle class="w-4 h-4 text-green-600" />
                                                        @endif
                                                        <span class="text-sm font-medium text-neutral-900 dark:text-neutral-100">{{ $s['label'] }}</span>
                                                    </div>
                                                </button>
                                            @endforeach
                                        </div>
                                    @endif
                                    @if ($existingClientId)
                                        <div class="p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg">
                                            <div class="flex items-center gap-2 text-sm text-green-700 dark:text-green-300">
                                                <x-flux::icon.check-circle class="w-4 h-4" />
                                                <span class="font-medium">{{ __('Client selected') }}</span>
                                            </div>
                                            <p class="text-xs text-neutral-600 dark:text-neutral-400 mt-1">
                                                {{ __('Client ID:') }} <span class="font-mono font-semibold">{{ $existingClientId }}</span>
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </label>
                </div>
            </div>

            <!-- Warning Message -->
            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                <div class="flex gap-3">
                    <x-flux::icon.exclamation-triangle class="w-5 h-5 text-yellow-600 dark:text-yellow-400 flex-shrink-0 mt-0.5" />
                    <div class="text-sm">
                        <p class="font-semibold text-yellow-800 dark:text-yellow-200 mb-1">{{ __('Important') }}</p>
                        <p class="text-yellow-700 dark:text-yellow-300">
                            {{ __('This action cannot be undone. The lead will be marked as "converted" and all bookings will be transferred to the client.') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between items-center pt-4 border-t border-neutral-200 dark:border-neutral-700">
                <button wire:click="$set('showConvertModal', false)" 
                        type="button"
                        class="px-5 py-2.5 text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-800 rounded-lg transition-colors font-medium">
                    {{ __('Cancel') }}
                </button>
                <button wire:click="convertToClient" 
                        type="button"
                        @if($convertAction === 'existing' && !$existingClientId) disabled @endif
                        class="inline-flex items-center gap-2 px-8 py-3 bg-indigo-600 hover:bg-indigo-700 disabled:bg-neutral-400 disabled:cursor-not-allowed text-white font-bold rounded-lg transition-all shadow-lg hover:shadow-xl disabled:shadow-none text-base">
                    <x-flux::icon.user-group class="w-5 h-5" />
                    {{ __('Convert to Client') }}
                </button>
            </div>
        </div>
    </x-flux::modal>

    <!-- Convert to Opportunity Modal -->
    @if($showOpportunityConvertModal)
    <flux:modal name="convert-lead-to-opportunity-modal" wire:model="showOpportunityConvertModal" class="max-w-md">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg" class="flex items-center gap-3">
                    <x-flux::icon.arrow-right class="w-6 h-6 text-green-500" />
                    {{ __('Convert Lead to Opportunity') }}
                </flux:heading>
            </div>
            
            <div class="space-y-4">
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2">{{ __('Lead Details') }}</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">{{ __('Name:') }}</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $lead->full_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">{{ __('Email:') }}</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $lead->email }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">{{ __('Status:') }}</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ ucfirst($lead->status) }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                    <h3 class="font-semibold text-blue-900 dark:text-blue-100 mb-2">{{ __('Opportunity Details') }}</h3>
                    <div class="space-y-2 text-sm text-blue-800 dark:text-blue-200">
                        <div class="flex justify-between">
                            <span>{{ __('Title:') }}</span>
                            <span class="font-medium">{{ $lead->full_name }} - Opportunity</span>
                        </div>
                        <div class="flex justify-between">
                            <span>{{ __('Amount:') }}</span>
                            <span class="font-medium">UGX 0 (Default)</span>
                        </div>
                        <div class="flex justify-between">
                            <span>{{ __('Probability:') }}</span>
                            <span class="font-medium">50%</span>
                        </div>
                        <div class="flex justify-between">
                            <span>{{ __('Close Date:') }}</span>
                            <span class="font-medium">{{ now()->addDays(30)->format('M j, Y') }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <x-flux::icon.exclamation-triangle class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mt-0.5" />
                        <div class="text-sm text-yellow-800 dark:text-yellow-200">
                            <p class="font-medium mb-1">{{ __('Important:') }}</p>
                            <p>{{ __('This action will convert the lead to an opportunity and update the lead status to "working". The opportunity can be edited later with additional details.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex gap-3 justify-end">
                <flux:button wire:click="closeOpportunityConvertModal" variant="ghost">
                    {{ __('Cancel') }}
                </flux:button>
                <flux:button wire:click="confirmConvertToOpportunity" variant="primary" class="bg-green-600 hover:bg-green-700">
                    <x-flux::icon.arrow-right class="w-4 h-4" />
                    {{ __('Convert to Opportunity') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>
    @endif
</div>
