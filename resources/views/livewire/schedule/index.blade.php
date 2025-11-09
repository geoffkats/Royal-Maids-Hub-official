<div class="min-h-screen bg-[#3B0A45] dark:bg-[#3B0A45] p-6">
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-[#3B0A45] to-[#512B58] border border-[#F5B301]/30 rounded-xl p-6 shadow-lg">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-[#F5B301] to-[#FF9800] rounded-lg flex items-center justify-center">
                        <flux:icon.calendar-days class="w-6 h-6 text-[#3B0A45]" />
                    </div>
                    <div>
                        <flux:heading size="xl" class="text-white">{{ __('Training Schedule') }}</flux:heading>
                        <flux:subheading class="mt-1 text-[#D1C4E9]">{{ __('Manage your training sessions and evaluations') }}</flux:subheading>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <!-- View Toggle -->
                    <div class="flex bg-[#3B0A45] rounded-lg p-1">
                        <button wire:click="$set('view', 'calendar')" 
                                class="px-3 py-2 rounded-md text-sm font-medium transition-colors {{ $view === 'calendar' ? 'bg-[#F5B301] text-[#3B0A45]' : 'text-[#D1C4E9] hover:text-white' }}">
                            <flux:icon.calendar-days class="w-4 h-4" />
                        </button>
                        <button wire:click="$set('view', 'list')" 
                                class="px-3 py-2 rounded-md text-sm font-medium transition-colors {{ $view === 'list' ? 'bg-[#F5B301] text-[#3B0A45]' : 'text-[#D1C4E9] hover:text-white' }}">
                            <flux:icon.clipboard-document-list class="w-4 h-4" />
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-[#512B58] border border-[#F5B301]/30 rounded-lg p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-[#F5B301]/20 rounded-lg flex items-center justify-center">
                        <flux:icon.calendar-days class="w-5 h-5 text-[#F5B301]" />
                    </div>
                    <div>
                        <p class="text-sm text-[#D1C4E9]">{{ __('Today\'s Sessions') }}</p>
                        <p class="text-2xl font-bold text-white">{{ $this->selectedDaySessions->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-[#512B58] border border-[#F5B301]/30 rounded-lg p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center">
                        <flux:icon.clock class="w-5 h-5 text-blue-400" />
                    </div>
                    <div>
                        <p class="text-sm text-[#D1C4E9]">{{ __('Upcoming') }}</p>
                        <p class="text-2xl font-bold text-white">{{ $this->upcomingSessions->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-[#512B58] border border-[#F5B301]/30 rounded-lg p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-500/20 rounded-lg flex items-center justify-center">
                        <flux:icon.check-circle class="w-5 h-5 text-green-400" />
                    </div>
                    <div>
                        <p class="text-sm text-[#D1C4E9]">{{ __('Completed') }}</p>
                        <p class="text-2xl font-bold text-white">{{ $this->sessions->where('status', 'completed')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-[#512B58] border border-[#F5B301]/30 rounded-lg p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center">
                        <flux:icon.clock class="w-5 h-5 text-blue-400" />
                    </div>
                    <div>
                        <p class="text-sm text-[#D1C4E9]">{{ __('Upcoming Sessions') }}</p>
                        <p class="text-2xl font-bold text-white">{{ $this->upcomingSessions->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if($view === 'calendar')
        <!-- Calendar View -->
        <div class="bg-[#512B58] border border-[#F5B301]/30 rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-white">{{ __('Training Calendar') }}</h3>
                <div class="flex items-center gap-2">
                    <button wire:click="$set('selectedMonth', '{{ Carbon\Carbon::parse($selectedMonth)->subMonth()->format('Y-m') }}')" 
                            class="p-2 text-[#D1C4E9] hover:text-white transition-colors">
                        <flux:icon.chevron-left class="w-5 h-5" />
                    </button>
                    <span class="text-white font-medium">{{ Carbon\Carbon::parse($selectedMonth)->format('F Y') }}</span>
                    <button wire:click="$set('selectedMonth', '{{ Carbon\Carbon::parse($selectedMonth)->addMonth()->format('Y-m') }}')" 
                            class="p-2 text-[#D1C4E9] hover:text-white transition-colors">
                        <flux:icon.chevron-right class="w-5 h-5" />
                    </button>
                </div>
            </div>

            <!-- Calendar Grid -->
            <div class="grid grid-cols-7 gap-1 mb-4">
                @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                <div class="p-3 text-center text-sm font-medium text-[#D1C4E9] bg-[#3B0A45]/50 rounded-lg">
                    {{ $day }}
                </div>
                @endforeach
            </div>

            <div class="grid grid-cols-7 gap-1">
                @foreach($this->calendarDays as $day)
                <div class="min-h-[100px] p-2 border border-[#F5B301]/20 rounded-lg {{ $day['isCurrentMonth'] ? 'bg-[#3B0A45]' : 'bg-[#3B0A45]/30' }} {{ $day['isSelected'] ? 'ring-2 ring-[#F5B301]' : '' }} {{ $day['isToday'] ? 'border-[#F5B301]' : '' }}">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm font-medium {{ $day['isCurrentMonth'] ? 'text-white' : 'text-[#D1C4E9]/50' }} {{ $day['isToday'] ? 'text-[#F5B301] font-bold' : '' }}">
                            {{ $day['date']->format('j') }}
                        </span>
                        @if($day['sessions']->count() > 0)
                        <span class="text-xs bg-[#F5B301] text-[#3B0A45] px-2 py-1 rounded-full font-medium">
                            {{ $day['sessions']->count() }}
                        </span>
                        @endif
                    </div>
                    
                    <div class="space-y-1">
                        @foreach($day['sessions']->take(2) as $session)
                        <div class="text-xs p-1 bg-[#F5B301]/20 text-[#D1C4E9] rounded truncate" 
                             wire:click="selectDate('{{ $session->start_date->format('Y-m-d') }}')">
                            {{ $session->maid->first_name }} {{ $session->maid->last_name }}
                        </div>
                        @endforeach
                        @if($day['sessions']->count() > 2)
                        <div class="text-xs text-[#D1C4E9]/70">
                            +{{ $day['sessions']->count() - 2 }} more
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Selected Day Details -->
        @if($selectedDate)
        <div class="bg-[#512B58] border border-[#F5B301]/30 rounded-xl p-6 shadow-lg">
            <h3 class="text-lg font-semibold text-white mb-4">
                {{ __('Sessions for') }} {{ Carbon\Carbon::parse($selectedDate)->format('F j, Y') }}
            </h3>
            
            @if($this->selectedDaySessions->count() > 0)
            <div class="space-y-3">
                @foreach($this->selectedDaySessions as $session)
                <div class="bg-[#3B0A45] border border-[#F5B301]/20 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#F5B301]/20 rounded-lg flex items-center justify-center">
                                <flux:icon.user class="w-5 h-5 text-[#F5B301]" />
                            </div>
                            <div>
                                <p class="font-medium text-white">{{ $session->maid->first_name }} {{ $session->maid->last_name }}</p>
                                <p class="text-sm text-[#D1C4E9]">{{ $session->program_type }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <flux:badge color="{{ $session->status === 'completed' ? 'green' : ($session->status === 'in-progress' ? 'blue' : 'yellow') }}" size="sm">
                                {{ ucfirst($session->status) }}
                            </flux:badge>
                            <p class="text-sm text-[#D1C4E9] mt-1">{{ $session->start_date->format('g:i A') }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <flux:icon.calendar-days class="w-12 h-12 text-[#D1C4E9]/50 mx-auto mb-4" />
                <p class="text-[#D1C4E9]">{{ __('No sessions scheduled for this day') }}</p>
            </div>
            @endif
        </div>
        @endif

        @else
        <!-- List View -->
        <div class="bg-[#512B58] border border-[#F5B301]/30 rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-white">{{ __('Training Sessions') }}</h3>
                
                <!-- Filters -->
                <div class="flex items-center gap-3">
                    <flux:input wire:model.live.debounce.300ms="search" placeholder="{{ __('Search trainees...') }}" class="w-64" />
                    <flux:select wire:model.live="statusFilter" class="w-40">
                        <option value="all">{{ __('All Status') }}</option>
                        <option value="scheduled">{{ __('Scheduled') }}</option>
                        <option value="in-progress">{{ __('In Progress') }}</option>
                        <option value="completed">{{ __('Completed') }}</option>
                        <option value="cancelled">{{ __('Cancelled') }}</option>
                    </flux:select>
                </div>
            </div>

            <div class="space-y-3">
                @forelse($this->sessions as $session)
                <div class="bg-[#3B0A45] border border-[#F5B301]/20 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-[#F5B301]/20 rounded-lg flex items-center justify-center">
                                <flux:icon.user class="w-6 h-6 text-[#F5B301]" />
                            </div>
                            <div>
                                <p class="font-medium text-white">{{ $session->maid->first_name }} {{ $session->maid->last_name }}</p>
                                <p class="text-sm text-[#D1C4E9]">{{ $session->program_type }}</p>
                                <p class="text-xs text-[#D1C4E9]/70">{{ $session->start_date->format('M j, Y g:i A') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <flux:badge color="{{ $session->status === 'completed' ? 'green' : ($session->status === 'in-progress' ? 'blue' : 'yellow') }}" size="sm">
                                {{ ucfirst($session->status) }}
                            </flux:badge>
                            <div class="text-right">
                                <p class="text-sm text-[#D1C4E9]">{{ $session->hours_completed }}/{{ $session->hours_required }} hours</p>
                                <div class="w-24 bg-[#3B0A45] rounded-full h-2 mt-1">
                                    <div class="bg-[#F5B301] h-2 rounded-full" style="width: {{ ($session->hours_completed / $session->hours_required) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-12">
                    <flux:icon.calendar-days class="w-16 h-16 text-[#D1C4E9]/50 mx-auto mb-4" />
                    <p class="text-[#D1C4E9] text-lg">{{ __('No training sessions found') }}</p>
                    <p class="text-[#D1C4E9]/70">{{ __('Your training schedule will appear here') }}</p>
                </div>
                @endforelse
            </div>
        </div>
        @endif

        <!-- Recent Evaluations -->
        <div class="bg-[#512B58] border border-[#F5B301]/30 rounded-xl p-6 shadow-lg">
            <h3 class="text-lg font-semibold text-white mb-4">{{ __('Upcoming Training Sessions') }}</h3>
            
            @if($this->upcomingSessions->count() > 0)
            <div class="space-y-3">
                @foreach($this->upcomingSessions as $session)
                <div class="bg-[#3B0A45] border border-[#F5B301]/20 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center">
                                <flux:icon.clock class="w-5 h-5 text-blue-400" />
                            </div>
                            <div>
                                <p class="font-medium text-white">{{ $session->maid->first_name }} {{ $session->maid->last_name }}</p>
                                <p class="text-sm text-[#D1C4E9]">{{ $session->program_type }}</p>
                                <p class="text-xs text-[#D1C4E9]/70">{{ $session->start_date->format('M j, Y g:i A') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <flux:badge color="yellow" size="sm">
                                {{ ucfirst($session->status) }}
                            </flux:badge>
                            <div class="text-right">
                                <p class="text-xs text-[#D1C4E9]">{{ $session->hours_required }} hours</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <flux:icon.clock class="w-12 h-12 text-[#D1C4E9]/50 mx-auto mb-4" />
                <p class="text-[#D1C4E9]">{{ __('No upcoming sessions') }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
