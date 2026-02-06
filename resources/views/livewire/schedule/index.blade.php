@php
    $statusColors = [
        'scheduled' => 'yellow',
        'in-progress' => 'blue',
        'completed' => 'green',
        'cancelled' => 'red',
        'active' => 'green',
        'ended' => 'gray',
        'pending' => 'yellow',
        'partial' => 'blue',
        'paid' => 'green',
        'approved' => 'green',
        'rejected' => 'red',
    ];

    $formatAmount = function ($amount, $currency) {
        return $amount === null ? '—' : $currency . ' ' . number_format((float) $amount, 2);
    };
@endphp

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
                        <flux:heading size="xl" class="text-white">{{ __('Schedule Overview') }}</flux:heading>
                        <flux:subheading class="mt-1 text-[#D1C4E9]">
                            {{ __('Deployments, training, evaluations, and finance in one view') }}
                        </flux:subheading>
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
                        <p class="text-sm text-[#D1C4E9]">{{ __('Today\'s Activity') }}</p>
                        <p class="text-2xl font-bold text-white">{{ $this->selectedDayEvents->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-[#512B58] border border-[#F5B301]/30 rounded-lg p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center">
                        <flux:icon.clock class="w-5 h-5 text-blue-400" />
                    </div>
                    <div>
                        <p class="text-sm text-[#D1C4E9]">{{ __('Upcoming Activity') }}</p>
                        <p class="text-2xl font-bold text-white">{{ $this->upcomingEvents->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-[#512B58] border border-[#F5B301]/30 rounded-lg p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-500/20 rounded-lg flex items-center justify-center">
                        <flux:icon.user class="w-5 h-5 text-green-400" />
                    </div>
                    <div>
                        <p class="text-sm text-[#D1C4E9]">{{ __('Month Deployments') }}</p>
                        <p class="text-2xl font-bold text-white">{{ $this->monthEvents->where('type', 'deployment')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-[#512B58] border border-[#F5B301]/30 rounded-lg p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center">
                        <flux:icon.star class="w-5 h-5 text-purple-400" />
                    </div>
                    <div>
                        <p class="text-sm text-[#D1C4E9]">{{ __('Pending Payments') }}</p>
                        <p class="text-2xl font-bold text-white">
                            {{ $this->monthEvents->where('type', 'finance')->whereIn('status', ['pending', 'partial'])->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        @if($view === 'calendar')
        <!-- Calendar View -->
        <div class="bg-[#512B58] border border-[#F5B301]/30 rounded-xl p-6 shadow-lg">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-white">{{ __('Activity Calendar') }}</h3>
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

            <div class="flex flex-wrap items-center gap-4 text-xs text-[#D1C4E9] mb-6">
                <div class="flex items-center gap-2">
                    <span class="inline-block w-3 h-3 bg-emerald-400"></span>
                    <span>{{ __('Deployment') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-block w-3 h-3 bg-sky-400 rounded-full"></span>
                    <span>{{ __('Training') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-block w-0 h-0 border-l-[6px] border-r-[6px] border-b-[10px] border-transparent border-b-amber-400"></span>
                    <span>{{ __('Evaluation') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-block w-3 h-3 bg-fuchsia-400 rotate-45"></span>
                    <span>{{ __('Finance') }}</span>
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
                <div wire:click="selectDate('{{ $day['date']->format('Y-m-d') }}')"
                     class="min-h-[100px] p-2 border border-[#F5B301]/20 rounded-lg cursor-pointer {{ $day['isCurrentMonth'] ? 'bg-[#3B0A45]' : 'bg-[#3B0A45]/30' }} {{ $day['isSelected'] ? 'ring-2 ring-[#F5B301]' : '' }} {{ $day['isToday'] ? 'border-[#F5B301]' : '' }}">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm font-medium {{ $day['isCurrentMonth'] ? 'text-white' : 'text-[#D1C4E9]/50' }} {{ $day['isToday'] ? 'text-[#F5B301] font-bold' : '' }}">
                            {{ $day['date']->format('j') }}
                        </span>
                        @if($day['events']->count() > 0)
                        <span class="text-xs bg-[#F5B301] text-[#3B0A45] px-2 py-1 rounded-full font-medium">
                            {{ $day['events']->count() }}
                        </span>
                        @endif
                    </div>
                    
                    <div class="space-y-1">
                        @foreach($day['events']->take(2) as $event)
                        <div class="flex items-center gap-2 text-xs p-1 bg-[#F5B301]/10 text-[#D1C4E9] rounded truncate">
                            @switch($event['type'])
                                @case('deployment')
                                    <span class="inline-block w-2.5 h-2.5 bg-emerald-400"></span>
                                    @break
                                @case('training')
                                    <span class="inline-block w-2.5 h-2.5 bg-sky-400 rounded-full"></span>
                                    @break
                                @case('evaluation')
                                    <span class="inline-block w-0 h-0 border-l-[5px] border-r-[5px] border-b-[9px] border-transparent border-b-amber-400"></span>
                                    @break
                                @case('finance')
                                    <span class="inline-block w-2.5 h-2.5 bg-fuchsia-400 rotate-45"></span>
                                    @break
                            @endswitch
                            <span class="truncate">{{ $event['title'] }}</span>
                        </div>
                        @endforeach
                        @if($day['events']->count() > 2)
                        <div class="text-xs text-[#D1C4E9]/70">
                            +{{ $day['events']->count() - 2 }} more
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
                {{ __('Activity for') }} {{ Carbon\Carbon::parse($selectedDate)->format('F j, Y') }}
            </h3>

            @if($this->selectedDayEvents->count() > 0)
            @php
                $groupedEvents = $this->selectedDayEvents->groupBy('type');
            @endphp
            <div class="space-y-6">
                @foreach(['deployment', 'finance', 'training', 'evaluation'] as $group)
                    @if($groupedEvents->has($group))
                    <div class="space-y-3">
                        <div class="flex items-center gap-2 text-sm font-semibold text-white">
                            @switch($group)
                                @case('deployment')
                                    <span class="inline-block w-3 h-3 bg-emerald-400"></span>
                                    <span>{{ __('Deployments') }}</span>
                                    @break
                                @case('finance')
                                    <span class="inline-block w-3 h-3 bg-fuchsia-400 rotate-45"></span>
                                    <span>{{ __('Finance') }}</span>
                                    @break
                                @case('training')
                                    <span class="inline-block w-3 h-3 bg-sky-400 rounded-full"></span>
                                    <span>{{ __('Training') }}</span>
                                    @break
                                @case('evaluation')
                                    <span class="inline-block w-0 h-0 border-l-[6px] border-r-[6px] border-b-[10px] border-transparent border-b-amber-400"></span>
                                    <span>{{ __('Evaluations') }}</span>
                                    @break
                            @endswitch
                        </div>

                        <div class="space-y-3">
                            @foreach($groupedEvents[$group] as $event)
                            <div class="bg-[#3B0A45] border border-[#F5B301]/20 rounded-lg p-4" wire:key="event-{{ $event['key'] }}">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-medium text-white">{{ $event['title'] }}</p>
                                        <p class="text-sm text-[#D1C4E9]">{{ $event['subtitle'] }}</p>
                                    </div>
                                    <div class="text-right">
                                        <flux:badge color="{{ $statusColors[$event['status']] ?? 'gray' }}" size="sm">
                                            {{ ucfirst($event['status'] ?? 'n/a') }}
                                        </flux:badge>
                                        <p class="text-xs text-[#D1C4E9]/70 mt-1">{{ $event['date']->format('M j, Y') }}</p>
                                    </div>
                                </div>

                                @if($event['type'] === 'deployment')
                                    @php $deployment = $event['model']; @endphp
                                    <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3 text-xs text-[#D1C4E9]">
                                        <div>
                                            <span class="text-[#D1C4E9]/70">{{ __('Location') }}:</span>
                                            {{ $deployment->deployment_location ?? __('Not set') }}
                                        </div>
                                        <div>
                                            <span class="text-[#D1C4E9]/70">{{ __('Contract') }}:</span>
                                            {{ $deployment->contract_start_date?->format('M j, Y') ?? '—' }}
                                            {{ $deployment->contract_end_date ? ' - ' . $deployment->contract_end_date->format('M j, Y') : '' }}
                                        </div>
                                        <div>
                                            <span class="text-[#D1C4E9]/70">{{ __('Address') }}:</span>
                                            {{ $deployment->deployment_address ?? __('Not set') }}
                                        </div>
                                        <div>
                                            <span class="text-[#D1C4E9]/70">{{ __('Status') }}:</span>
                                            {{ ucfirst($deployment->status ?? 'n/a') }}
                                        </div>
                                    </div>
                                @endif

                                @if($event['type'] === 'finance')
                                    @php
                                        $finance = $event['finance'] ?? [];
                                        $currency = $finance['currency'] ?? 'UGX';
                                    @endphp
                                    <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3 text-xs text-[#D1C4E9]">
                                        <div>
                                            <span class="text-[#D1C4E9]/70">{{ __('Monthly Salary') }}:</span>
                                            {{ $formatAmount($finance['monthly_salary'] ?? null, $currency) }}
                                        </div>
                                        <div>
                                            <span class="text-[#D1C4E9]/70">{{ __('Maid Salary') }}:</span>
                                            {{ $formatAmount($finance['maid_salary'] ?? null, $currency) }}
                                        </div>
                                        <div>
                                            <span class="text-[#D1C4E9]/70">{{ __('Client Payment') }}:</span>
                                            {{ $formatAmount($finance['client_payment'] ?? null, $currency) }}
                                        </div>
                                        <div>
                                            <span class="text-[#D1C4E9]/70">{{ __('Service Fee') }}:</span>
                                            {{ $formatAmount($finance['service_paid'] ?? null, $currency) }}
                                        </div>
                                        <div>
                                            <span class="text-[#D1C4E9]/70">{{ __('Payment Status') }}:</span>
                                            {{ ucfirst($finance['payment_status'] ?? 'pending') }}
                                        </div>
                                        <div>
                                            <span class="text-[#D1C4E9]/70">{{ __('Salary Paid Date') }}:</span>
                                            {{ $finance['salary_paid_date']?->format('M j, Y') ?? '—' }}
                                        </div>
                                    </div>
                                @endif

                                @if($event['type'] === 'training')
                                    @php $program = $event['model']; @endphp
                                    <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3 text-xs text-[#D1C4E9]">
                                        <div>
                                            <span class="text-[#D1C4E9]/70">{{ __('Program') }}:</span>
                                            {{ $program->program_type }}
                                        </div>
                                        <div>
                                            <span class="text-[#D1C4E9]/70">{{ __('Hours') }}:</span>
                                            {{ $program->hours_completed }} / {{ $program->hours_required }}
                                        </div>
                                    </div>
                                @endif

                                @if($event['type'] === 'evaluation')
                                    @php $evaluation = $event['model']; @endphp
                                    <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3 text-xs text-[#D1C4E9]">
                                        <div>
                                            <span class="text-[#D1C4E9]/70">{{ __('Overall Rating') }}:</span>
                                            {{ $evaluation->overall_rating ?? '—' }}
                                        </div>
                                        <div>
                                            <span class="text-[#D1C4E9]/70">{{ __('Average Score') }}:</span>
                                            {{ $evaluation->average_score ?? '—' }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <flux:icon.calendar-days class="w-12 h-12 text-[#D1C4E9]/50 mx-auto mb-4" />
                <p class="text-[#D1C4E9]">{{ __('No activity scheduled for this day') }}</p>
            </div>
            @endif
        </div>
        @endif

        @else
        <!-- List View -->
        <div class="bg-[#512B58] border border-[#F5B301]/30 rounded-xl p-6 shadow-lg">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-6">
                <h3 class="text-lg font-semibold text-white">{{ __('Schedule Activity') }}</h3>
                
                <!-- Filters -->
                <div class="flex flex-wrap items-center gap-3">
                    <flux:input wire:model.live.debounce.300ms="search" placeholder="{{ __('Search maids or clients...') }}" class="w-64" />
                    <flux:select wire:model.live="typeFilter" class="w-44">
                        <option value="all">{{ __('All Types') }}</option>
                        <option value="deployment">{{ __('Deployments') }}</option>
                        <option value="training">{{ __('Training') }}</option>
                        <option value="evaluation">{{ __('Evaluations') }}</option>
                        <option value="finance">{{ __('Finance') }}</option>
                    </flux:select>
                    <flux:select wire:model.live="statusFilter" class="w-44">
                        <option value="all">{{ __('All Status') }}</option>
                        <option value="scheduled">{{ __('Scheduled') }}</option>
                        <option value="in-progress">{{ __('In Progress') }}</option>
                        <option value="completed">{{ __('Completed') }}</option>
                        <option value="cancelled">{{ __('Cancelled') }}</option>
                        <option value="active">{{ __('Active') }}</option>
                        <option value="ended">{{ __('Ended') }}</option>
                        <option value="pending">{{ __('Pending') }}</option>
                        <option value="partial">{{ __('Partial') }}</option>
                        <option value="paid">{{ __('Paid') }}</option>
                        <option value="approved">{{ __('Approved') }}</option>
                        <option value="rejected">{{ __('Rejected') }}</option>
                    </flux:select>
                </div>
            </div>

            <div class="space-y-3">
                @forelse($this->events as $event)
                <div class="bg-[#3B0A45] border border-[#F5B301]/20 rounded-lg p-4" wire:key="list-{{ $event['key'] }}">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-[#F5B301]/10 rounded-lg flex items-center justify-center">
                                @switch($event['type'])
                                    @case('deployment')
                                        <span class="inline-block w-4 h-4 bg-emerald-400"></span>
                                        @break
                                    @case('training')
                                        <span class="inline-block w-4 h-4 bg-sky-400 rounded-full"></span>
                                        @break
                                    @case('evaluation')
                                        <span class="inline-block w-0 h-0 border-l-[7px] border-r-[7px] border-b-[12px] border-transparent border-b-amber-400"></span>
                                        @break
                                    @case('finance')
                                        <span class="inline-block w-4 h-4 bg-fuchsia-400 rotate-45"></span>
                                        @break
                                @endswitch
                            </div>
                            <div>
                                <p class="font-medium text-white">{{ $event['title'] }}</p>
                                <p class="text-sm text-[#D1C4E9]">{{ $event['subtitle'] }}</p>
                                <p class="text-xs text-[#D1C4E9]/70">{{ $event['date']->format('M j, Y') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <flux:badge color="{{ $statusColors[$event['status']] ?? 'gray' }}" size="sm">
                                {{ ucfirst($event['status'] ?? 'n/a') }}
                            </flux:badge>
                            <p class="text-xs text-[#D1C4E9]/70 mt-1">{{ ucfirst($event['type']) }}</p>
                        </div>
                    </div>

                    @if($event['type'] === 'finance')
                        @php
                            $finance = $event['finance'] ?? [];
                            $currency = $finance['currency'] ?? 'UGX';
                        @endphp
                        <div class="mt-3 grid grid-cols-1 md:grid-cols-4 gap-3 text-xs text-[#D1C4E9]">
                            <div>
                                <span class="text-[#D1C4E9]/70">{{ __('Monthly') }}:</span>
                                {{ $formatAmount($finance['monthly_salary'] ?? null, $currency) }}
                            </div>
                            <div>
                                <span class="text-[#D1C4E9]/70">{{ __('Maid Salary') }}:</span>
                                {{ $formatAmount($finance['maid_salary'] ?? null, $currency) }}
                            </div>
                            <div>
                                <span class="text-[#D1C4E9]/70">{{ __('Client Payment') }}:</span>
                                {{ $formatAmount($finance['client_payment'] ?? null, $currency) }}
                            </div>
                            <div>
                                <span class="text-[#D1C4E9]/70">{{ __('Service Fee') }}:</span>
                                {{ $formatAmount($finance['service_paid'] ?? null, $currency) }}
                            </div>
                        </div>
                    @endif
                </div>
                @empty
                <div class="text-center py-12">
                    <flux:icon.calendar-days class="w-16 h-16 text-[#D1C4E9]/50 mx-auto mb-4" />
                    <p class="text-[#D1C4E9] text-lg">{{ __('No schedule activity found') }}</p>
                    <p class="text-[#D1C4E9]/70">{{ __('Try adjusting your filters or date range') }}</p>
                </div>
                @endforelse
            </div>
        </div>
        @endif

        <!-- Upcoming Activity -->
        <div class="bg-[#512B58] border border-[#F5B301]/30 rounded-xl p-6 shadow-lg">
            <h3 class="text-lg font-semibold text-white mb-4">{{ __('Upcoming Activity') }}</h3>
            
            @if($this->upcomingEvents->count() > 0)
            <div class="space-y-3">
                @foreach($this->upcomingEvents as $event)
                <div class="bg-[#3B0A45] border border-[#F5B301]/20 rounded-lg p-4" wire:key="upcoming-{{ $event['key'] }}">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#F5B301]/20 rounded-lg flex items-center justify-center">
                                @switch($event['type'])
                                    @case('deployment')
                                        <span class="inline-block w-3 h-3 bg-emerald-400"></span>
                                        @break
                                    @case('training')
                                        <span class="inline-block w-3 h-3 bg-sky-400 rounded-full"></span>
                                        @break
                                    @case('evaluation')
                                        <span class="inline-block w-0 h-0 border-l-[6px] border-r-[6px] border-b-[10px] border-transparent border-b-amber-400"></span>
                                        @break
                                    @case('finance')
                                        <span class="inline-block w-3 h-3 bg-fuchsia-400 rotate-45"></span>
                                        @break
                                @endswitch
                            </div>
                            <div>
                                <p class="font-medium text-white">{{ $event['title'] }}</p>
                                <p class="text-sm text-[#D1C4E9]">{{ $event['subtitle'] }}</p>
                                <p class="text-xs text-[#D1C4E9]/70">{{ $event['date']->format('M j, Y') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <flux:badge color="{{ $statusColors[$event['status']] ?? 'gray' }}" size="sm">
                                {{ ucfirst($event['status'] ?? 'n/a') }}
                            </flux:badge>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <flux:icon.clock class="w-12 h-12 text-[#D1C4E9]/50 mx-auto mb-4" />
                <p class="text-[#D1C4E9]">{{ __('No upcoming activity') }}</p>
            </div>
            @endif
        </div>
    </div>
</div>