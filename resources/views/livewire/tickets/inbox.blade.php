<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-[#3B0A45] to-[#512B58] rounded-xl border border-[#F5B301]/30 shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" class="text-white">My Ticket Inbox</flux:heading>
                <flux:subheading class="text-[#D1C4E9] mt-2">Your assigned tickets and notifications</flux:subheading>
            </div>
            <div class="flex items-center gap-4">
                <div class="relative">
                    <flux:icon.bell class="w-8 h-8 text-[#F5B301]" />
                    @if($unreadCount > 0)
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-6 h-6 flex items-center justify-center font-bold">
                        {{ $unreadCount }}
                    </span>
                    @endif
                </div>
                <div class="text-right">
                    <div class="text-sm text-[#D1C4E9]">Assigned to You</div>
                    <div class="text-white font-semibold">{{ count($assignedTickets) }} tickets</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-[#512B58] rounded-lg border border-[#F5B301]/30 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-900/30 rounded-lg flex items-center justify-center">
                    <flux:icon.ticket class="w-5 h-5 text-blue-400" />
                </div>
                <div>
                    <div class="text-[#D1C4E9] text-sm">Open</div>
                    <div class="text-white font-bold text-lg">{{ $openCount }}</div>
                </div>
            </div>
        </div>

        <div class="bg-[#512B58] rounded-lg border border-[#F5B301]/30 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-purple-900/30 rounded-lg flex items-center justify-center">
                    <flux:icon.clock class="w-5 h-5 text-purple-400" />
                </div>
                <div>
                    <div class="text-[#D1C4E9] text-sm">In Progress</div>
                    <div class="text-white font-bold text-lg">{{ $inProgressCount }}</div>
                </div>
            </div>
        </div>

        <div class="bg-[#512B58] rounded-lg border border-[#F5B301]/30 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-red-900/30 rounded-lg flex items-center justify-center">
                    <flux:icon.exclamation-triangle class="w-5 h-5 text-red-400" />
                </div>
                <div>
                    <div class="text-[#D1C4E9] text-sm">SLA Breached</div>
                    <div class="text-white font-bold text-lg">{{ $slaBreachedCount }}</div>
                </div>
            </div>
        </div>

        <div class="bg-[#512B58] rounded-lg border border-[#F5B301]/30 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-900/30 rounded-lg flex items-center justify-center">
                    <flux:icon.check-circle class="w-5 h-5 text-green-400" />
                </div>
                <div>
                    <div class="text-[#D1C4E9] text-sm">Resolved</div>
                    <div class="text-white font-bold text-lg">{{ $resolvedCount }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-[#512B58] rounded-xl border border-[#F5B301]/30 shadow-lg p-6">
        <div class="flex flex-wrap gap-4 items-center">
            <div class="flex items-center gap-2">
                <flux:icon.funnel class="w-5 h-5 text-[#F5B301]" />
                <span class="text-white font-medium">Filters:</span>
            </div>
            
            <select wire:model.live="statusFilter" class="bg-[#3B0A45] border border-[#F5B301]/30 rounded-lg px-3 py-2 text-[#D1C4E9] focus:border-[#F5B301] focus:ring-1 focus:ring-[#F5B301]">
                <option value="">All Status</option>
                <option value="open">Open</option>
                <option value="in_progress">In Progress</option>
                <option value="pending">Pending</option>
                <option value="resolved">Resolved</option>
            </select>

            <select wire:model.live="priorityFilter" class="bg-[#3B0A45] border border-[#F5B301]/30 rounded-lg px-3 py-2 text-[#D1C4E9] focus:border-[#F5B301] focus:ring-1 focus:ring-[#F5B301]">
                <option value="">All Priority</option>
                <option value="critical">Critical</option>
                <option value="urgent">Urgent</option>
                <option value="high">High</option>
                <option value="medium">Medium</option>
                <option value="low">Low</option>
            </select>

            <select wire:model.live="slaFilter" class="bg-[#3B0A45] border border-[#F5B301]/30 rounded-lg px-3 py-2 text-[#D1C4E9] focus:border-[#F5B301] focus:ring-1 focus:ring-[#F5B301]">
                <option value="">All SLA</option>
                <option value="breached">SLA Breached</option>
                <option value="approaching">Approaching SLA</option>
                <option value="safe">Within SLA</option>
            </select>

            <button wire:click="resetFilters" class="px-4 py-2 bg-[#3B0A45] hover:bg-[#3B0A45]/80 text-[#D1C4E9] rounded-lg border border-[#F5B301]/30 transition-colors">
                Reset
            </button>
        </div>
    </div>

    <!-- Tickets List -->
    <div class="bg-[#512B58] rounded-xl border border-[#F5B301]/30 shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-[#3B0A45]/50 border-b border-[#F5B301]/20">
            <div class="flex items-center justify-between">
                <flux:heading size="lg" class="text-white">Assigned Tickets</flux:heading>
                <div class="flex items-center gap-4">
                    <div class="text-[#D1C4E9] text-sm">
                        {{ count($assignedTickets) }} tickets
                    </div>
                    <button wire:click="markAllAsRead" class="px-3 py-1 bg-[#F5B301] text-[#3B0A45] rounded-lg text-sm font-medium hover:bg-[#F5B301]/80 transition-colors">
                        Mark All Read
                    </button>
                </div>
            </div>
        </div>

        <div class="divide-y divide-[#F5B301]/10">
            @forelse($assignedTickets as $ticket)
            @php
                // Priority-based styling
                $priorityBg = match($ticket->priority) {
                    'critical' => 'bg-red-900/40 border-l-4 border-red-500',
                    'urgent' => 'bg-orange-900/30 border-l-4 border-orange-500',
                    'high' => 'bg-amber-900/25 border-l-4 border-amber-500',
                    'medium' => 'bg-yellow-900/15 border-l-4 border-yellow-500',
                    'low' => 'bg-zinc-800/20 border-l-4 border-zinc-500',
                    default => 'bg-zinc-800/10 border-l-4 border-zinc-500'
                };
                
                // SLA breach highlighting
                $slaBreach = $ticket->sla_breached ? 'animate-pulse bg-red-800/50' : '';
                $approachingSLA = $ticket->isApproachingSLA() ? 'bg-orange-800/30' : '';
                
                $rowClasses = "hover:bg-[#3B0A45]/30 transition cursor-pointer {$priorityBg} {$slaBreach} {$approachingSLA}";
            @endphp
            <div class="p-6 {{ $rowClasses }}" onclick="window.location='{{ route('tickets.show', $ticket) }}'">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-white font-mono text-sm font-bold">{{ $ticket->ticket_number }}</span>
                            
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
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wide {{ $colorClass }} border-2">
                                {{ ucfirst($ticket->priority) }}
                            </span>

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
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wide {{ $statusClass }} border-2">
                                {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                            </span>

                            @if($ticket->tier_based_priority)
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-{{ $ticket->getTierBadgeColor() }}-500/20 text-{{ $ticket->getTierBadgeColor() }}-400 border border-{{ $ticket->getTierBadgeColor() }}-500/50">
                                {{ ucfirst($ticket->tier_based_priority) }}
                            </span>
                            @endif

                            @if($ticket->auto_priority)
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-[#F5B301]/20 text-[#F5B301] border border-[#F5B301]/50">
                                ⚡ Auto-Boosted
                            </span>
                            @endif
                        </div>

                        <h3 class="text-white font-semibold text-lg mb-2">{{ $ticket->subject }}</h3>
                        
                        <div class="flex items-center gap-4 text-sm text-[#D1C4E9] mb-3">
                            <div class="flex items-center gap-1">
                                <flux:icon.user class="w-4 h-4" />
                                <span>{{ $ticket->requester->name }}</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <flux:icon.clock class="w-4 h-4" />
                                <span>{{ $ticket->created_at->diffForHumans() }}</span>
                            </div>
                            @if($ticket->client)
                            <div class="flex items-center gap-1">
                                <flux:icon.user class="w-4 h-4" />
                                <span>{{ $ticket->client->contact_person }}</span>
                            </div>
                            @endif
                        </div>

                        <p class="text-[#D1C4E9] text-sm mb-4">{{ Str::limit($ticket->description, 150) }}</p>

                        <!-- SLA Status -->
                        <div class="flex items-center gap-4">
                            @if($ticket->isOpen())
                                @if($ticket->isSLABreached())
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg bg-red-600 text-white font-bold text-xs border-2 border-red-600 shadow-lg shadow-red-500/25">
                                        <flux:icon.exclamation-triangle class="w-3 h-3 mr-1" />
                                        SLA BREACHED
                                    </span>
                                @elseif($ticket->isApproachingSLA())
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg bg-orange-600 text-white font-bold text-xs border-2 border-orange-600 shadow-lg shadow-orange-500/25">
                                        <flux:icon.clock class="w-3 h-3 mr-1" />
                                        APPROACHING SLA
                                    </span>
                                @elseif($ticket->sla_resolution_due)
                                    <span class="inline-flex items-center px-2 py-1 rounded bg-green-600 text-white text-xs font-medium">
                                        <flux:icon.check-circle class="w-3 h-3 mr-1" />
                                        {{ $ticket->getTimeUntilSLABreach() }}
                                    </span>
                                @endif
                            @else
                                <span class="text-[#D1C4E9]/50">—</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-2 ml-4">
                        @if($ticket->hasUnreadComments())
                        <span class="w-3 h-3 bg-[#F5B301] rounded-full animate-pulse" title="New comments"></span>
                        @endif
                        
                        <flux:icon.chevron-right class="w-5 h-5 text-[#D1C4E9]" />
                    </div>
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                <flux:icon.ticket class="w-16 h-16 text-[#D1C4E9]/50 mx-auto mb-4" />
                <div class="text-[#D1C4E9] text-lg font-medium mb-2">No tickets assigned to you</div>
                <div class="text-[#D1C4E9]/70">You'll see assigned tickets here when they're created</div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if(count($assignedTickets) > 10)
        <div class="px-6 py-4 bg-[#3B0A45]/30 border-t border-[#F5B301]/20 text-center text-[#D1C4E9] text-sm">
            Showing {{ count($assignedTickets) }} tickets (limited to 10 for performance)
        </div>
        @endif
    </div>
</div>
