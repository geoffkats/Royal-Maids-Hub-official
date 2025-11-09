<div>
    <!-- Header Section -->
    <div class="bg-[#512B58] border border-[#F5B301]/30 rounded-xl p-6 text-white mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <x-flux::icon.clipboard-document-list class="w-8 h-8" />
                <div>
                    <h1 class="text-3xl font-bold">{{ $activity->subject }}</h1>
                    <p class="text-indigo-100 mt-1">{{ __('Activity Details') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('crm.activities.edit', $activity) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg font-semibold text-white hover:bg-white/30 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <x-flux::icon.pencil class="w-4 h-4" />
                    {{ __('Edit') }}
                </a>
                <a href="{{ route('crm.activities.index') }}" 
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
        <!-- Activity Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-gradient-to-r from-[#512B58] to-[#3B0A45] border border-[#F5B301]/30 rounded-xl p-6 shadow-lg">
                <h2 class="text-xl font-semibold text-[#D1C4E9] mb-6 flex items-center gap-2">
                    <x-flux::icon.information-circle class="w-5 h-5" />
                    {{ __('Activity Information') }}
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-[#D1C4E9] mb-2">{{ __('Type') }}</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($activity->type === 'call') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                            @elseif($activity->type === 'email') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                            @elseif($activity->type === 'meeting') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                            @elseif($activity->type === 'task') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                            @elseif($activity->type === 'note') bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                            @endif">
                            {{ ucfirst($activity->type) }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#D1C4E9] mb-2">{{ __('Status') }}</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($activity->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                            @elseif($activity->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                            @elseif($activity->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                            @endif">
                            {{ ucfirst($activity->status) }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#D1C4E9] mb-2">{{ __('Priority') }}</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($activity->priority === 'low') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                            @elseif($activity->priority === 'medium') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                            @elseif($activity->priority === 'high') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                            @endif">
                            {{ ucfirst($activity->priority) }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#D1C4E9] mb-2">{{ __('Due Date') }}</label>
                        <p class="text-white">{{ $activity->due_date ? $activity->due_date->format('M j, Y g:i A') : 'No due date' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#D1C4E9] mb-2">{{ __('Assigned To') }}</label>
                        <p class="text-white">{{ $activity->assignedTo?->name ?: 'Unassigned' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#D1C4E9] mb-2">{{ __('Created By') }}</label>
                        <p class="text-white">{{ $activity->createdBy?->name ?: 'Unknown' }}</p>
                    </div>
                </div>

                @if($activity->description)
                <div class="mt-6">
                    <label class="block text-sm font-medium text-[#D1C4E9] mb-2">{{ __('Description') }}</label>
                    <div class="bg-white/10 border border-[#F5B301]/30 rounded-lg p-4 text-white">
                        {{ $activity->description }}
                    </div>
                </div>
                @endif
            </div>

            <!-- Related Information -->
            @if($activity->related)
            <div class="bg-gradient-to-r from-[#512B58] to-[#3B0A45] border border-[#F5B301]/30 rounded-xl p-6 shadow-lg">
                <h2 class="text-xl font-semibold text-[#D1C4E9] mb-6 flex items-center gap-2">
                    <x-flux::icon.link class="w-5 h-5" />
                    {{ __('Related Information') }}
                </h2>
                
                <div class="bg-white/10 border border-[#F5B301]/30 rounded-lg p-4">
                    <p class="text-white font-medium">{{ ucfirst($activity->related_type) }}: {{ $activity->related->name ?? $activity->related->first_name . ' ' . $activity->related->last_name }}</p>
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
                    @if($activity->status === 'pending')
                    <button wire:click="markCompleted" 
                            wire:confirm="Are you sure you want to mark this activity as completed?"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition-all duration-200 shadow-lg hover:shadow-xl">
                        <x-flux::icon.check-circle class="w-4 h-4" />
                        {{ __('Mark Completed') }}
                    </button>
                    @endif

                    @if($activity->status !== 'cancelled')
                    <button wire:click="markCancelled" 
                            wire:confirm="Are you sure you want to cancel this activity?"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold transition-all duration-200 shadow-lg hover:shadow-xl">
                        <x-flux::icon.x-circle class="w-4 h-4" />
                        {{ __('Cancel Activity') }}
                    </button>
                    @endif
                </div>
            </div>

            <!-- Timestamps -->
            <div class="bg-gradient-to-r from-[#512B58] to-[#3B0A45] border border-[#F5B301]/30 rounded-xl p-6 shadow-lg">
                <h3 class="text-lg font-semibold text-[#D1C4E9] mb-4">{{ __('Timestamps') }}</h3>
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-[#D1C4E9] mb-1">{{ __('Created') }}</label>
                        <p class="text-white text-sm">{{ $activity->created_at->format('M j, Y g:i A') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-[#D1C4E9] mb-1">{{ __('Updated') }}</label>
                        <p class="text-white text-sm">{{ $activity->updated_at->format('M j, Y g:i A') }}</p>
</div>

                    @if($activity->completed_at)
                    <div>
                        <label class="block text-sm font-medium text-[#D1C4E9] mb-1">{{ __('Completed') }}</label>
                        <p class="text-white text-sm">{{ $activity->completed_at->format('M j, Y g:i A') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>