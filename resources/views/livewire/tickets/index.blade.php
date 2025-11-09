<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white">Support Tickets</h1>
            <p class="text-[#D1C4E9] mt-1">Manage and track all support requests</p>
        </div>
        <a href="{{ route('tickets.create') }}" class="px-6 py-3 rounded-lg bg-gradient-to-r from-[#F5B301] to-[#FFD54F] text-[#3B0A45] font-bold shadow-lg hover:shadow-xl hover:scale-105 transform transition-all duration-200 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            + Create Ticket
        </a>
    </div>

    <!-- Enhanced Filters Bar -->
    <div class="mb-6 rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
        <div class="flex items-center gap-2 mb-4">
            <flux:icon.funnel class="size-5 text-[#F5B301]" />
            <h3 class="text-lg font-semibold text-white">{{ __('Filter Tickets') }}</h3>
        </div>
        <p class="text-sm text-[#D1C4E9] mb-6">{{ __('Narrow down results to find what you need') }}</p>
        
        <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
        <!-- Search -->
        <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-[#F5B301] mb-2">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-[#D1C4E9]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by ticket # or subject..." class="w-full pl-10 pr-4 py-3 rounded-lg border-2 border-[#F5B301]/30 bg-[#3B0A45] text-white placeholder-[#D1C4E9]/60 focus:border-[#F5B301] focus:ring-2 focus:ring-[#F5B301]/50 transition-all">
                </div>
        </div>

        <!-- Status Filter -->
        <div>
                <label class="block text-sm font-semibold text-[#F5B301] mb-2">Status</label>
            <select wire:model.live="statusFilter" class="w-full py-3 px-4 rounded-lg border-2 border-[#F5B301]/30 bg-[#3B0A45] text-white focus:border-[#F5B301] focus:ring-2 focus:ring-[#F5B301]/50 transition-all">
                <option value="all">All Status</option>
                <option value="open">Open</option>
                <option value="in_progress">In Progress</option>
                <option value="pending">Pending</option>
                <option value="resolved">Resolved</option>
                <option value="closed">Closed</option>
            </select>
        </div>

        <!-- Priority Filter -->
        <div>
                <label class="block text-sm font-semibold text-[#F5B301] mb-2">Priority</label>
            <select wire:model.live="priorityFilter" class="w-full py-3 px-4 rounded-lg border-2 border-[#F5B301]/30 bg-[#3B0A45] text-white focus:border-[#F5B301] focus:ring-2 focus:ring-[#F5B301]/50 transition-all">
                <option value="all">All Priorities</option>
                <option value="critical">Critical</option>
                <option value="urgent">Urgent</option>
                <option value="high">High</option>
                <option value="medium">Medium</option>
                <option value="low">Low</option>
            </select>
        </div>

        <!-- Assigned Filter -->
        <div>
                <label class="block text-sm font-semibold text-[#F5B301] mb-2">Assignment</label>
            <select wire:model.live="assignedFilter" class="w-full py-3 px-4 rounded-lg border-2 border-[#F5B301]/30 bg-[#3B0A45] text-white focus:border-[#F5B301] focus:ring-2 focus:ring-[#F5B301]/50 transition-all">
                <option value="all">All Tickets</option>
                <option value="me">Assigned to Me</option>
                <option value="unassigned">Unassigned</option>
            </select>
        </div>

        <!-- Tier Filter -->
        <div>
                <label class="block text-sm font-semibold text-[#F5B301] mb-2">Client Tier</label>
            <select wire:model.live="tierFilter" class="w-full py-3 px-4 rounded-lg border-2 border-[#F5B301]/30 bg-[#3B0A45] text-white focus:border-[#F5B301] focus:ring-2 focus:ring-[#F5B301]/50 transition-all">
                <option value="all">All Tiers</option>
                <option value="platinum">Platinum</option>
                <option value="gold">Gold</option>
                <option value="silver">Silver</option>
            </select>
            </div>
        </div>
    </div>

    <!-- Tickets Table -->
    <div class="bg-[#512B58] rounded-xl border border-[#F5B301]/30 shadow-lg overflow-x-auto">
        <div class="min-w-full">
            <table class="min-w-full divide-y divide-[#F5B301]/20" style="min-width: 1200px;">
            <thead class="bg-[#3B0A45]/50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-[#F5B301] uppercase tracking-wider">Ticket #</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-[#F5B301] uppercase tracking-wider">Subject</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-[#F5B301] uppercase tracking-wider">Priority</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-[#F5B301] uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-[#F5B301] uppercase tracking-wider">Client/Maid</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-[#F5B301] uppercase tracking-wider">Assigned</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-[#F5B301] uppercase tracking-wider">SLA</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-[#F5B301] uppercase tracking-wider">Created</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-[#F5B301] uppercase tracking-wider w-20">View</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#F5B301]/10">
                @forelse($tickets as $ticket)
                @php
                    // Priority-based background colors
                    $priorityBg = match($ticket->priority) {
                        'critical' => 'bg-red-900/40 border-l-4 border-red-500',
                        'urgent' => 'bg-orange-900/30 border-l-4 border-orange-500',
                        'high' => 'bg-amber-900/25 border-l-4 border-amber-500',
                        'medium' => 'bg-yellow-900/15 border-l-4 border-yellow-500',
                        'low' => 'bg-zinc-800/20 border-l-4 border-zinc-500',
                        default => 'bg-zinc-800/10 border-l-4 border-zinc-500'
                    };
                    
                    // Status-based additional styling
                    $statusBg = match($ticket->status) {
                        'open' => 'ring-1 ring-blue-500/30',
                        'in_progress' => 'ring-1 ring-purple-500/30',
                        'pending' => 'ring-1 ring-yellow-500/30',
                        'on_hold' => 'ring-1 ring-orange-500/30',
                        'resolved' => 'ring-1 ring-green-500/30',
                        'closed' => 'ring-1 ring-zinc-500/30',
                        'cancelled' => 'ring-1 ring-red-500/30',
                        default => ''
                    };
                    
                    // SLA breach highlighting
                    $slaBreach = $ticket->sla_breached ? 'animate-pulse bg-red-800/50' : '';
                    
                    $rowClasses = "hover:bg-[#3B0A45]/30 transition cursor-pointer {$priorityBg} {$statusBg} {$slaBreach}";
                @endphp
                <tr class="{{ $rowClasses }}" onclick="window.location='{{ route('tickets.show', $ticket) }}'">
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <span class="text-white font-mono text-sm">{{ $ticket->ticket_number }}</span>
                            @if($ticket->auto_priority)
                                <span class="text-xs text-[#F5B301]" title="Priority Auto-Boosted">⚡</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="max-w-xs">
                            <p class="text-white font-medium truncate">{{ $ticket->subject }}</p>
                            @if($ticket->tier_based_priority)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-{{ $ticket->getTierBadgeColor() }}-500/20 text-{{ $ticket->getTierBadgeColor() }}-400 border border-{{ $ticket->getTierBadgeColor() }}-500/50">
                                    {{ ucfirst($ticket->tier_based_priority) }}
                                </span>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                            @php
                                $priorityColors = [
                                    'critical' => 'bg-red-600 text-white border-red-600 shadow-lg shadow-red-500/25',
                                    'urgent' => 'bg-orange-600 text-white border-orange-600 shadow-lg shadow-orange-500/25',
                                    'high' => 'bg-amber-600 text-white border-amber-600 shadow-lg shadow-amber-500/25',
                                    'medium' => 'bg-yellow-600 text-white border-yellow-600 shadow-lg shadow-yellow-500/25',
                                    'low' => 'bg-zinc-600 text-white border-zinc-600 shadow-lg shadow-zinc-500/25'
                                ];
                                $colorClass = $priorityColors[$ticket->priority] ?? 'bg-zinc-600 text-white border-zinc-600 shadow-lg shadow-zinc-500/25';
                            @endphp
                            <span class="inline-flex items-center px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wide {{ $colorClass }} border-2">
                                @if($ticket->priority === 'critical')
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                @elseif($ticket->priority === 'urgent')
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                    </svg>
                                @elseif($ticket->priority === 'high')
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                @endif
                                {{ ucfirst($ticket->priority) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        @php
                            $statusColors = [
                                'open' => 'bg-blue-600 text-white border-blue-600 shadow-lg shadow-blue-500/25',
                                'in_progress' => 'bg-purple-600 text-white border-purple-600 shadow-lg shadow-purple-500/25',
                                'pending' => 'bg-yellow-600 text-white border-yellow-600 shadow-lg shadow-yellow-500/25',
                                'on_hold' => 'bg-orange-600 text-white border-orange-600 shadow-lg shadow-orange-500/25',
                                'resolved' => 'bg-green-600 text-white border-green-600 shadow-lg shadow-green-500/25',
                                'closed' => 'bg-zinc-600 text-white border-zinc-600 shadow-lg shadow-zinc-500/25',
                                'cancelled' => 'bg-red-600 text-white border-red-600 shadow-lg shadow-red-500/25'
                            ];
                            $statusClass = $statusColors[$ticket->status] ?? 'bg-zinc-600 text-white border-zinc-600 shadow-lg shadow-zinc-500/25';
                        @endphp
                        <span class="inline-flex items-center px-3 py-2 rounded-lg text-xs font-bold uppercase tracking-wide {{ $statusClass }} border-2">
                            {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-[#D1C4E9]">
                            @php
                                $badge = $ticket->getRequesterTypeBadge();
                                $badgeColors = [
                                    'blue' => 'bg-blue-100 text-blue-800 border-blue-300 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-700',
                                    'purple' => 'bg-purple-100 text-purple-800 border-purple-300 dark:bg-purple-900/30 dark:text-purple-300 dark:border-purple-700',
                                    'green' => 'bg-green-100 text-green-800 border-green-300 dark:bg-green-900/30 dark:text-green-300 dark:border-green-700',
                                    'zinc' => 'bg-zinc-100 text-zinc-800 border-zinc-300 dark:bg-zinc-900/30 dark:text-zinc-300 dark:border-zinc-700',
                                ];
                            @endphp
                            <div class="flex flex-col gap-1">
                                @if($ticket->requester)
                                    <div class="text-white font-medium">
                                        @if($ticket->requester_type === 'lead')
                                            {{ $ticket->requester->full_name }}
                                        @elseif($ticket->requester_type === 'client')
                                            {{ $ticket->requester->contact_person }}
                                        @elseif($ticket->requester_type === 'maid')
                                            {{ $ticket->requester->first_name }} {{ $ticket->requester->last_name }}
                                        @elseif($ticket->requester_type === 'user')
                                            {{ $ticket->requester->name }}
                                        @else
                                            {{ $ticket->requester->name ?? 'Unknown' }}
                                        @endif
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium border {{ $badgeColors[$badge['color']] }}">
                                        {{ $badge['label'] }}
                                        @if($ticket->isPreSalesTicket())
                                            <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                            </svg>
                                        @endif
                                    </span>
                                @else
                                    <span class="text-[#D1C4E9]/50">—</span>
                                @endif
                            </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-[#D1C4E9]">
                        @if($ticket->assignedTo)
                            {{ $ticket->assignedTo->name }}
                        @else
                            <span class="text-[#FFC107]">Unassigned</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                        @if($ticket->isOpen())
                            @if($ticket->isSLABreached())
                                <span class="inline-flex items-center px-3 py-1 rounded-lg bg-red-600 text-white font-bold text-xs border-2 border-red-600 shadow-lg shadow-red-500/25">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    SLA BREACHED
                                </span>
                            @elseif($ticket->sla_resolution_due)
                                <span class="inline-flex items-center px-2 py-1 rounded bg-amber-600 text-white text-xs font-medium">
                                    {{ $ticket->getTimeUntilSLABreach() }}
                                </span>
                            @endif
                        @else
                            <span class="text-[#D1C4E9]/50">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-[#D1C4E9]">
                        {{ $ticket->created_at->diffForHumans() }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-center">
                        <a href="{{ route('tickets.show', $ticket) }}" 
                           class="inline-flex items-center justify-center w-10 h-10 rounded-lg hover:bg-[#F5B301]/20 text-[#64B5F6] hover:text-[#F5B301] transition-all duration-200 transform hover:scale-105"
                           title="View Ticket">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-4 py-12 text-center">
                        <div class="text-[#D1C4E9]">
                            <p class="text-lg font-medium">No tickets found</p>
                            <p class="text-sm mt-1">Try adjusting your filters or create a new ticket</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-4 py-3 bg-[#3B0A45]/30 border-t border-[#F5B301]/20">
            {{ $tickets->links() }}
        </div>
    </div>
</div>
