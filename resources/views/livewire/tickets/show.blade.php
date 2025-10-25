<div class="p-6">
    <!-- Back Button and Actions -->
    <div class="mb-4 flex items-center justify-between">
        <a href="{{ route('tickets.index') }}" class="text-[#64B5F6] hover:text-[#F5B301] transition">
            ← Back to Tickets
        </a>
        <div class="flex gap-2">
            <flux:button as="a" :href="route('tickets.edit', $ticket)" variant="primary" icon="pencil-square">
                {{ __('Edit Ticket') }}
            </flux:button>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="mb-4 p-3 rounded bg-[#4CAF50]/20 text-[#4CAF50] border border-[#4CAF50]">
            {{ session('success') }}
        </div>
    @endif

    <!-- Header -->
    <div class="mb-6 p-6 bg-[#512B58] rounded-xl border border-[#F5B301]/30">
        <div class="flex items-start justify-between">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-2xl font-bold text-white">{{ $ticket->ticket_number }}</h1>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $ticket->getPriorityColor() }}-500/20 text-{{ $ticket->getPriorityColor() }}-400 border border-{{ $ticket->getPriorityColor() }}-500/50">
                        {{ ucfirst($ticket->priority) }}
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $ticket->getStatusColor() }}-500/20 text-{{ $ticket->getStatusColor() }}-400 border border-{{ $ticket->getStatusColor() }}-500/50">
                        {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                    </span>
                    @if($ticket->tier_based_priority)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $ticket->getTierBadgeColor() }}-500/20 text-{{ $ticket->getTierBadgeColor() }}-400 border border-{{ $ticket->getTierBadgeColor() }}-500/50">
                            {{ ucfirst($ticket->tier_based_priority) }} Client
                        </span>
                    @endif
                    @if($ticket->auto_priority)
                        <span class="text-[#F5B301] text-sm font-semibold" title="Priority Auto-Boosted">⚡ Auto-Boosted</span>
                    @endif
                </div>
                <h2 class="text-xl text-white mb-2">{{ $ticket->subject }}</h2>
                <div class="space-y-1 text-sm text-[#D1C4E9]">
                    <p><span class="text-[#F5B301]">Created by:</span> {{ $ticket->getCreatedByText() }} • {{ $ticket->created_at->format('M d, Y H:i') }}</p>
                    <p><span class="text-[#F5B301]">Type:</span> {{ ucfirst(str_replace('_', ' ', $ticket->type)) }}</p>
                    <p><span class="text-[#F5B301]">Category:</span> {{ $ticket->category }}</p>
                    @if($ticket->subcategory)
                        <p><span class="text-[#F5B301]">Subcategory:</span> {{ $ticket->subcategory }}</p>
                    @endif
                    @if($ticket->department)
                        <p><span class="text-[#F5B301]">Department:</span> {{ ucfirst(str_replace('_', ' ', $ticket->department)) }}</p>
                    @endif
                </div>
                @if($ticket->created_on_behalf_of && $ticket->created_on_behalf_type)
                    <p class="text-[#64B5F6] text-sm mt-2 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Created on behalf of: {{ $ticket->getCreatedOnBehalfText() }}
                    </p>
                @endif
                
                @if($ticket->assignedTo)
                    <p class="text-[#F5B301] text-sm mt-2 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Assigned to: {{ $ticket->assignedTo->name }}
                    </p>
                @else
                    <p class="text-[#FFC107] text-sm mt-2 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        Unassigned - needs attention
                    </p>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column (2/3) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Description -->
            <div class="p-6 bg-[#512B58] rounded-xl border border-[#F5B301]/30">
                <h3 class="text-lg font-semibold text-white mb-4">Description</h3>
                <div class="text-[#D1C4E9] whitespace-pre-wrap">{{ $ticket->description }}</div>
            </div>

            <!-- Comments Section -->
            <div class="p-6 bg-[#512B58] rounded-xl border border-[#F5B301]/30">
                <h3 class="text-lg font-semibold text-white mb-4">Activity & Comments</h3>
                
                <!-- Add Comment Form -->
                <div class="mb-4">
                    <textarea wire:model="newComment" rows="3" class="w-full rounded border-[#F5B301]/30 bg-[#3B0A45] text-white placeholder-[#D1C4E9]" placeholder="Add a comment..."></textarea>
                    @error('newComment') <span class="text-[#E53935] text-xs">{{ $message }}</span> @enderror
                    <button wire:click="addComment" class="mt-2 px-4 py-2 rounded-lg bg-[#F5B301] text-[#3B0A45] font-bold hover:bg-[#FFD700] transition">
                        Add Comment
                    </button>
                </div>

                <!-- Comments Display -->
                <div class="space-y-4">
                    @forelse($comments as $comment)
                        <div class="border-l-4 border-[#F5B301]/30 pl-4 py-3 bg-[#3B0A45]/30 rounded-r-lg">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium text-white">{{ $comment->user->name }}</span>
                                    <span class="text-xs text-[#D1C4E9]/70">{{ $comment->created_at->format('M d, Y H:i') }}</span>
                                    @if($comment->is_internal)
                                        <span class="text-xs bg-[#F5B301]/20 text-[#F5B301] px-2 py-1 rounded">Internal</span>
                                    @endif
                                </div>
                                <span class="text-xs text-[#D1C4E9]/70">{{ ucfirst($comment->comment_type) }}</span>
                            </div>
                            <div class="text-[#D1C4E9] whitespace-pre-wrap">{{ $comment->body }}</div>
                        </div>
                    @empty
                        <div class="text-[#D1C4E9]/70 text-sm text-center py-8">
                            <p>No comments yet. Be the first to add a comment!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Sidebar (1/3) -->
        <div class="space-y-6">
            <!-- Ticket Details & Actions -->
            <div class="p-6 bg-[#512B58] rounded-xl border border-[#F5B301]/30">
                <h3 class="text-lg font-semibold text-white mb-4">Ticket Details</h3>
                
                <form wire:submit.prevent="updateTicket" class="space-y-4">
                    <!-- Status -->
                    <div>
                        <label class="text-[#D1C4E9] text-sm font-medium">Status</label>
                        <select wire:model="status" class="w-full mt-1 rounded border-[#F5B301]/30 bg-[#3B0A45] text-white">
                            <option value="open">Open</option>
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="on_hold">On Hold</option>
                            <option value="resolved">Resolved</option>
                            <option value="closed">Closed</option>
                        </select>
                    </div>

                    <!-- Priority -->
                    <div>
                        <label class="text-[#D1C4E9] text-sm font-medium">Priority</label>
                        <select wire:model="priority" class="w-full mt-1 rounded border-[#F5B301]/30 bg-[#3B0A45] text-white">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                            <option value="critical">Critical</option>
                        </select>
                    </div>

                    <!-- Assigned To -->
                    <div>
                        <label class="text-[#D1C4E9] text-sm font-medium">Assign to Staff</label>
                        <select wire:model="assigned_to" class="w-full mt-1 rounded border-[#F5B301]/30 bg-[#3B0A45] text-white">
                            <option value="">Unassigned</option>
                            @foreach($admins as $admin)
                                <option value="{{ $admin->id }}">{{ $admin->name }} ({{ ucfirst($admin->role) }})</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="w-full px-4 py-2 rounded-lg bg-[#F5B301] text-[#3B0A45] font-bold hover:bg-[#FFD700] transition">
                        Update Ticket
                    </button>
                </form>
            </div>

            <!-- Related Entities -->
            <div class="p-6 bg-[#512B58] rounded-xl border border-[#F5B301]/30">
                <h3 class="text-lg font-semibold text-white mb-4">Related Entities</h3>
                
                <div class="space-y-3 text-sm">
                    @if($ticket->client)
                        <div>
                            <span class="text-[#D1C4E9]">Client:</span>
                            <a href="{{ route('clients.show', $ticket->client) }}" class="text-[#64B5F6] hover:text-[#F5B301] ml-2">
                                {{ $ticket->client->contact_person }}
                            </a>
                        </div>
                    @endif

                    @if($ticket->maid)
                        <div>
                            <span class="text-[#D1C4E9]">Maid:</span>
                            <a href="{{ route('maids.show', $ticket->maid) }}" class="text-[#64B5F6] hover:text-[#F5B301] ml-2">
                                {{ $ticket->maid->first_name }} {{ $ticket->maid->last_name }}
                            </a>
                        </div>
                    @endif

                    @if($ticket->booking)
                        <div>
                            <span class="text-[#D1C4E9]">Booking:</span>
                            <a href="{{ route('bookings.show', $ticket->booking) }}" class="text-[#64B5F6] hover:text-[#F5B301] ml-2">
                                #{{ $ticket->booking->id }}
                            </a>
                        </div>
                    @endif

                    @if($ticket->package)
                        <div>
                            <span class="text-[#D1C4E9]">Package:</span>
                            <span class="text-white ml-2">{{ $ticket->package->name }}</span>
                        </div>
                    @endif

                    @if(!$ticket->client && !$ticket->maid && !$ticket->booking && !$ticket->package)
                        <p class="text-[#D1C4E9]/70">No related entities</p>
                    @endif
                </div>
            </div>

            <!-- SLA Timeline -->
            @if($ticket->isOpen())
            <div class="p-6 bg-[#512B58] rounded-xl border border-[#F5B301]/30">
                <h3 class="text-lg font-semibold text-white mb-4">SLA Timeline</h3>
                
                <div class="space-y-3 text-sm">
                    @if($ticket->sla_response_due)
                        <div>
                            <span class="text-[#D1C4E9]">Response Due:</span>
                            <span class="text-white ml-2">{{ $ticket->sla_response_due->format('M d, H:i') }}</span>
                            @if(!$ticket->first_response_at)
                                <div class="text-xs text-[#F5B301] mt-1">
                                    {{ $ticket->sla_response_due->diffForHumans() }}
                                </div>
                            @endif
                        </div>
                    @endif

                    @if($ticket->sla_resolution_due)
                        <div>
                            <span class="text-[#D1C4E9]">Resolution Due:</span>
                            <span class="text-white ml-2">{{ $ticket->sla_resolution_due->format('M d, H:i') }}</span>
                            @if(!$ticket->resolved_at)
                                <div class="text-xs text-[#F5B301] mt-1">
                                    {{ $ticket->sla_resolution_due->diffForHumans() }}
                                </div>
                            @endif
                        </div>
                    @endif

                    @if($ticket->isSLABreached())
                        <div class="text-[#E53935] font-semibold">
                            ⚠ SLA Breached
                        </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Status History -->
            @if($statusHistory->count() > 0)
            <div class="p-6 bg-[#512B58] rounded-xl border border-[#F5B301]/30">
                <h3 class="text-lg font-semibold text-white mb-4">Status History</h3>
                
                <div class="space-y-3">
                    @foreach($statusHistory as $history)
                        <div class="flex items-start gap-3 p-3 bg-[#3B0A45]/30 rounded-lg">
                            <div class="flex-shrink-0 w-2 h-2 bg-[#F5B301] rounded-full mt-2"></div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-white font-medium">{{ ucfirst($history->new_status) }}</span>
                                    @if($history->old_status)
                                        <span class="text-[#D1C4E9]/70">from {{ ucfirst($history->old_status) }}</span>
                                    @endif
                                </div>
                                <div class="text-sm text-[#D1C4E9]/70">
                                    by {{ $history->changedBy->name ?? 'System' }} • {{ $history->created_at->format('M d, Y H:i') }}
                                </div>
                                @if($history->notes)
                                    <div class="text-sm text-[#D1C4E9] mt-1">{{ $history->notes }}</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Attachments -->
            @if($attachments->count() > 0)
            <div class="p-6 bg-[#512B58] rounded-xl border border-[#F5B301]/30">
                <h3 class="text-lg font-semibold text-white mb-4">Attachments</h3>
                
                <div class="space-y-2">
                    @foreach($attachments as $attachment)
                        <div class="flex items-center justify-between p-3 bg-[#3B0A45]/30 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-[#F5B301]/20 rounded flex items-center justify-center">
                                    <svg class="w-4 h-4 text-[#F5B301]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-white font-medium">{{ $attachment->file_name }}</div>
                                    <div class="text-xs text-[#D1C4E9]/70">{{ $attachment->file_type }} • {{ number_format($attachment->file_size / 1024, 1) }} KB</div>
                                </div>
                            </div>
                            <a href="{{ $attachment->url }}" target="_blank" class="text-[#64B5F6] hover:text-[#F5B301] transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Satisfaction Rating -->
            @if($ticket->status === 'resolved' || $ticket->status === 'closed')
            <div class="p-6 bg-[#512B58] rounded-xl border border-[#F5B301]/30">
                <h3 class="text-lg font-semibold text-white mb-4">Customer Satisfaction</h3>
                
                @if($ticket->satisfaction_rating)
                    <div class="flex items-center gap-2">
                        <span class="text-[#D1C4E9]">Rating:</span>
                        <div class="flex items-center gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= $ticket->satisfaction_rating ? 'text-[#F5B301]' : 'text-[#D1C4E9]/30' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            @endfor
                        </div>
                        <span class="text-white font-medium">{{ $ticket->satisfaction_rating }}/5</span>
                    </div>
                @else
                    <div class="text-[#D1C4E9]/70 text-sm">
                        <p>No satisfaction rating provided yet.</p>
                    </div>
                @endif
            </div>
            @endif

            <!-- Timestamps -->
            <div class="p-6 bg-[#512B58] rounded-xl border border-[#F5B301]/30">
                <h3 class="text-lg font-semibold text-white mb-4">Timeline</h3>
                
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="text-[#D1C4E9]">Created:</span>
                        <span class="text-white ml-2">{{ $ticket->created_at->format('M d, Y H:i') }}</span>
                    </div>

                    @if($ticket->first_response_at)
                        <div>
                            <span class="text-[#D1C4E9]">First Response:</span>
                            <span class="text-white ml-2">{{ $ticket->first_response_at->format('M d, Y H:i') }}</span>
                        </div>
                    @endif

                    @if($ticket->resolved_at)
                        <div>
                            <span class="text-[#D1C4E9]">Resolved:</span>
                            <span class="text-white ml-2">{{ $ticket->resolved_at->format('M d, Y H:i') }}</span>
                        </div>
                    @endif

                    @if($ticket->closed_at)
                        <div>
                            <span class="text-[#D1C4E9]">Closed:</span>
                            <span class="text-white ml-2">{{ $ticket->closed_at->format('M d, Y H:i') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
