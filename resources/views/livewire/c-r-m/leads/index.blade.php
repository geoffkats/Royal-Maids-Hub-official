<div>
    <!-- Header Section with Stats -->
    <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-xl p-6 text-white mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold flex items-center gap-3">
                    <x-flux::icon.user-plus class="w-8 h-8" />
                    {{ __('CRM Leads') }}
                </h1>
                <p class="text-indigo-100 mt-2">
                    {{ __('Manage and track your potential customers') }}
                </p>
            </div>
            <div class="mt-4 md:mt-0 flex items-center gap-3">
                <form action="{{ route('crm.leads.import') }}" method="POST" enctype="multipart/form-data" id="lead-import-form" class="hidden">
                    @csrf
                    <input type="file" name="file" id="lead-import-file" accept=".xlsx,.csv,.txt" onchange="document.getElementById('lead-import-form').submit();" />
                </form>

                <a href="{{ route('crm.leads.export', request()->only(['status','owner_id','source_id','search'])) }}"
                   class="inline-flex items-center gap-2 px-5 py-3 bg-emerald-600 border border-emerald-500 rounded-lg font-semibold text-white hover:bg-emerald-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <x-flux::icon.arrow-down-tray class="w-5 h-5" />
                    {{ __('Export') }}
                </a>
                <button type="button" onclick="document.getElementById('lead-import-file').click();"
                        class="inline-flex items-center gap-2 px-5 py-3 bg-indigo-600 border border-indigo-500 rounded-lg font-semibold text-white hover:bg-indigo-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <x-flux::icon.arrow-up-tray class="w-5 h-5" />
                    {{ __('Import') }}
                </button>
                <button wire:click="deleteSelected"
                        @disabled(empty($selectedLeads))
                        wire:confirm="Are you sure you want to delete selected leads?"
                        class="inline-flex items-center gap-2 px-5 py-3 bg-red-600 border border-red-500 rounded-lg font-semibold text-white hover:bg-red-700 transition-all duration-200 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed">
                    <x-flux::icon.trash class="w-5 h-5" />
                    {{ __('Delete Selected') }}
                </button>
                <a href="{{ route('crm.leads.create') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg font-semibold text-white hover:bg-white/30 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <x-flux::icon.plus class="w-5 h-5" />
                    {{ __('Add New Lead') }}
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

    <!-- Filters Section -->
    <div class="bg-gradient-to-r from-[#512B58] to-[#3B0A45] border border-[#F5B301]/30 rounded-xl p-6 mb-6 shadow-lg">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-[#D1C4E9] mb-2">
                    <x-flux::icon.magnifying-glass class="w-4 h-4 inline mr-1" />
                    {{ __('Search') }}
                </label>
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       placeholder="{{ __('Search leads...') }}"
                       class="w-full px-4 py-2 bg-white/10 border border-[#F5B301]/30 rounded-lg text-white placeholder-gray-300 focus:ring-2 focus:ring-[#F5B301] focus:border-transparent">
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-[#D1C4E9] mb-2">
                    <x-flux::icon.funnel class="w-4 h-4 inline mr-1" />
                    {{ __('Status') }}
                </label>
                <select wire:model.live="statusFilter" 
                        class="w-full px-4 py-2 bg-white/10 border border-[#F5B301]/30 rounded-lg text-white focus:ring-2 focus:ring-[#F5B301] focus:border-transparent">
                    <option value="">{{ __('All Statuses') }}</option>
                    @foreach($statusOptions as $value => $label)
                        <option value="{{ $value }}" class="bg-gray-800">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Assigned Filter -->
            <div>
                <label class="block text-sm font-medium text-[#D1C4E9] mb-2">
                    <x-flux::icon.user class="w-4 h-4 inline mr-1" />
                    {{ __('Assigned To') }}
                </label>
                <select wire:model.live="assignedFilter" 
                        class="w-full px-4 py-2 bg-white/10 border border-[#F5B301]/30 rounded-lg text-white focus:ring-2 focus:ring-[#F5B301] focus:border-transparent">
                    <option value="">{{ __('All Users') }}</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" class="bg-gray-800">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Source Filter -->
            <div>
                <label class="block text-sm font-medium text-[#D1C4E9] mb-2">
                    <x-flux::icon.chart-bar class="w-4 h-4 inline mr-1" />
                    {{ __('Source') }}
                </label>
                <select wire:model.live="sourceFilter" 
                        class="w-full px-4 py-2 bg-white/10 border border-[#F5B301]/30 rounded-lg text-white focus:ring-2 focus:ring-[#F5B301] focus:border-transparent">
                    <option value="">{{ __('All Sources') }}</option>
                    @foreach($sourceOptions as $value => $label)
                        <option value="{{ $value }}" class="bg-gray-800">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Additional Controls -->
        <div class="flex flex-wrap items-center justify-between mt-4 pt-4 border-t border-[#F5B301]/20">
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <label class="text-sm text-[#D1C4E9]">{{ __('Per Page') }}:</label>
                    <select wire:model.live="perPage" 
                            class="px-3 py-1 bg-white/10 border border-[#F5B301]/30 rounded text-white text-sm focus:ring-2 focus:ring-[#F5B301] focus:border-transparent">
                        <option value="10" class="bg-gray-800">10</option>
                        <option value="25" class="bg-gray-800">25</option>
                        <option value="50" class="bg-gray-800">50</option>
                        <option value="100" class="bg-gray-800">100</option>
                    </select>
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                <button wire:click="clearSelection" 
                        class="px-4 py-2 bg-white/10 border border-[#F5B301]/30 rounded-lg text-[#D1C4E9] hover:bg-white/20 transition-all duration-200">
                    {{ __('Reset Filters') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="table-container">
        <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
            <thead class="bg-gradient-to-r from-neutral-50 to-neutral-100 dark:from-neutral-700 dark:to-neutral-800">
                <tr>
                    <th class="px-4 py-4">
                        <input type="checkbox" wire:model.live="selectPage" class="rounded text-indigo-600 border-neutral-300 dark:border-neutral-600 focus:ring-indigo-500">
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-300 cursor-pointer" 
                        wire:click="sortBy('first_name')">
                        <x-flux::icon.user class="w-4 h-4 inline mr-1" />
                        {{ __('Name') }}
                        @if($sortBy === 'first_name')
                            <x-flux::icon.arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} class="w-3 h-3 inline ml-1" />
                        @endif
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-300 cursor-pointer" 
                        wire:click="sortBy('email')">
                        <x-flux::icon.envelope class="w-4 h-4 inline mr-1" />
                        {{ __('Email') }}
                        @if($sortBy === 'email')
                            <x-flux::icon.arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} class="w-3 h-3 inline ml-1" />
                        @endif
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-300 cursor-pointer" 
                        wire:click="sortBy('company')">
                        <x-flux::icon.briefcase class="w-4 h-4 inline mr-1" />
                        {{ __('Company') }}
                        @if($sortBy === 'company')
                            <x-flux::icon.arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} class="w-3 h-3 inline ml-1" />
                        @endif
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-300 cursor-pointer" 
                        wire:click="sortBy('status')">
                        <x-flux::icon.flag class="w-4 h-4 inline mr-1" />
                        {{ __('Status') }}
                        @if($sortBy === 'status')
                            <x-flux::icon.arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} class="w-3 h-3 inline ml-1" />
                        @endif
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-300">
                        <x-flux::icon.user class="w-4 h-4 inline mr-1" />
                        {{ __('Assigned To') }}
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-300 cursor-pointer" 
                        wire:click="sortBy('created_at')">
                        <x-flux::icon.calendar-days class="w-4 h-4 inline mr-1" />
                        {{ __('Created') }}
                        @if($sortBy === 'created_at')
                            <x-flux::icon.arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} class="w-3 h-3 inline ml-1" />
                        @endif
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-neutral-600 dark:text-neutral-300">
                        <x-flux::icon.cog-6-tooth class="w-4 h-4 inline mr-1" />
                        {{ __('Actions') }}
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                @forelse ($leads as $lead)
                    <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50 transition-colors duration-150">
                        <td class="px-4 py-4">
                            <input type="checkbox" value="{{ $lead->id }}" wire:model.live="selectedLeads" class="rounded text-indigo-600 border-neutral-300 dark:border-neutral-600 focus:ring-indigo-500">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center">
                                        <span class="text-sm font-medium text-white">
                                            {{ strtoupper(substr($lead->first_name, 0, 1) . substr($lead->last_name, 0, 1)) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-neutral-900 dark:text-neutral-100">
                                        {{ $lead->full_name }}
                                    </div>
                                    <div class="text-sm text-neutral-500 dark:text-neutral-400">
                                        {{ $lead->job_title }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900 dark:text-neutral-100">
                            {{ $lead->email ?: 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900 dark:text-neutral-100">
                            {{ $lead->company ?: 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($lead->status === 'new') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                @elseif($lead->status === 'contacted') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                @elseif($lead->status === 'qualified') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @elseif($lead->status === 'unqualified') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                @elseif($lead->status === 'converted') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                @endif">
                                {{ ucfirst($lead->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900 dark:text-neutral-100">
                            {{ $lead->assignedTo?->name ?: 'Unassigned' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500 dark:text-neutral-400">
                            {{ $lead->created_at->format('M j, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-1.5">
                                <a href="{{ route('crm.leads.show', $lead) }}" 
                                   class="inline-flex items-center justify-center p-2 text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-md transition-colors duration-150"
                                   title="View" aria-label="View">
                                    <x-flux::icon.eye class="w-4 h-4" />
                                    <span class="sr-only">{{ __('View') }}</span>
                                </a>
                                <a href="{{ route('crm.leads.edit', $lead) }}" 
                                   class="inline-flex items-center justify-center p-2 text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-md transition-colors duration-150"
                                   title="Edit" aria-label="Edit">
                                    <x-flux::icon.pencil class="w-4 h-4" />
                                    <span class="sr-only">{{ __('Edit') }}</span>
                                </a>
                                @if($lead->status !== 'converted')
                                <button wire:click="openConvertModal({{ $lead->id }})" 
                                        class="inline-flex items-center justify-center p-2 text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-md transition-colors duration-150"
                                        title="Convert to Opportunity" aria-label="Convert to Opportunity">
                                    <x-flux::icon.arrow-right class="w-4 h-4" />
                                    <span class="sr-only">{{ __('Convert') }}</span>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-neutral-500 dark:text-neutral-400">
                            {{ __('No leads found.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $leads->links() }}
    </div>

    <!-- Convert to Opportunity Modal -->
    @if($showConvertModal && $leadToConvert)
    <flux:modal name="convert-lead-modal" wire:model="showConvertModal" class="max-w-md">
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
                            <span class="font-medium text-gray-900 dark:text-white">{{ $leadToConvert->full_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">{{ __('Email:') }}</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $leadToConvert->email }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">{{ __('Status:') }}</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ ucfirst($leadToConvert->status) }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                    <h3 class="font-semibold text-blue-900 dark:text-blue-100 mb-2">{{ __('Opportunity Details') }}</h3>
                    <div class="space-y-2 text-sm text-blue-800 dark:text-blue-200">
                        <div class="flex justify-between">
                            <span>{{ __('Title:') }}</span>
                            <span class="font-medium">{{ $leadToConvert->full_name }} - Opportunity</span>
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
                            <p>{{ __('This action will convert the lead to an opportunity and mark the lead as "converted". The opportunity can be edited later with additional details.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex gap-3 justify-end">
                <flux:button wire:click="closeConvertModal" variant="ghost">
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
