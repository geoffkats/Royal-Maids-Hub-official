<div class="p-6">
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

    <!-- Header with Pipeline Stats -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold text-white">{{ $pipeline->name }} Pipeline</h1>
                <p class="text-[#D1C4E9] mt-1">Drag and drop opportunities to update their stage</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="bg-[#512B58] border border-[#F5B301]/30 rounded-xl px-6 py-3">
                    <div class="text-[#D1C4E9] text-sm">Pipeline Value</div>
                    <div class="text-2xl font-bold text-[#F5B301]">
                        @currency($pipelineValue)
                    </div>
                </div>
                <div class="bg-[#512B58] border border-[#F5B301]/30 rounded-xl px-6 py-3">
                    <div class="text-[#D1C4E9] text-sm">Weighted Value</div>
                    <div class="text-2xl font-bold text-green-400">
                        @currency($weightedValue)
                    </div>
                </div>
                <flux:button 
                    href="{{ route('crm.opportunities.create') }}"
                    variant="primary"
                >
                    <x-flux::icon.plus class="w-4 h-4 mr-2" />
                    New Opportunity
                </flux:button>
            </div>
        </div>
    </div>

    <!-- Kanban Board -->
    <div class="flex gap-4 overflow-x-auto pb-4" style="min-height: 600px;">
        <!-- All Leads Section -->
        <div class="flex-shrink-0" style="width: 320px;" x-data>
            <!-- Leads Header -->
            <div class="bg-[#512B58] border-2 border-[#F5B301]/30 rounded-t-xl p-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-lg font-semibold text-white">Qualified Leads</h3>
                    <span class="px-2 py-1 rounded-full text-xs font-bold bg-[#F5B301] text-[#3B0A45]">
                        {{ count($leads) }}
                    </span>
                </div>
                <div class="text-sm text-[#D1C4E9]">
                    Ready for opportunities - Drag to any stage
                </div>
            </div>

            <!-- Leads Drop Zone -->
            <div 
                class="bg-[#3B0A45]/50 border-2 border-[#F5B301]/20 border-t-0 rounded-b-xl p-3 space-y-3 min-h-[500px]"
            >
                @forelse($leads as $lead)
                <div 
                    draggable="true"
                    x-on:dragstart="
                        $event.dataTransfer.effectAllowed = 'move';
                        $event.dataTransfer.setData('text/plain', '');
                        $event.dataTransfer.setData('leadId', String({{ $lead->id }}));
                        $event.dataTransfer.setData('type', 'lead');
                        $event.target.style.opacity = '0.5';
                    "
                    x-on:dragend="$event.target.style.opacity = '1'"
                    class="bg-[#512B58] border border-[#F5B301]/30 rounded-lg p-4 cursor-move hover:border-[#F5B301] hover:shadow-lg transition-all"
                >
                    <!-- Lead Card Header -->
                    <div class="flex items-start justify-between mb-2">
                        <h4 class="text-white font-medium text-sm line-clamp-2 flex-1">
                            {{ $lead->full_name }}
                        </h4>
                        @if($lead->score >= 70)
                        <svg class="w-4 h-4 text-green-400 flex-shrink-0 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        @endif
                    </div>

                    <!-- Score Badge -->
                    @if($lead->score)
                    <div class="flex items-center gap-2 mb-3">
                        <div class="flex-1 bg-[#3B0A45] rounded-full h-2">
                            <div 
                                class="bg-gradient-to-r from-[#F5B301] to-[#FFD54F] h-2 rounded-full transition-all"
                                style="width: {{ $lead->score }}%"
                            ></div>
                        </div>
                        <span class="text-xs text-[#D1C4E9] font-medium">{{ $lead->score }}%</span>
                    </div>
                    @endif

                    <!-- Contact Info -->
                    <div class="flex items-center gap-2 mb-2">
                        <x-flux::icon.envelope class="w-4 h-4 text-[#D1C4E9]" />
                        <span class="text-xs text-[#D1C4E9] truncate">
                            {{ $lead->email }}
                        </span>
                    </div>

                    <!-- Source -->
                    @if($lead->source)
                    <div class="flex items-center gap-2 mb-2">
                        <x-flux::icon.tag class="w-4 h-4 text-[#D1C4E9]" />
                        <span class="text-xs text-[#D1C4E9]">
                            {{ $lead->source->name }}
                        </span>
                    </div>
                    @endif

                    <!-- Assigned To -->
                    @if($lead->owner)
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-[#F5B301] flex items-center justify-center text-xs font-bold text-[#3B0A45]">
                            {{ substr($lead->owner->name, 0, 1) }}
                        </div>
                        <span class="text-xs text-[#D1C4E9]">{{ $lead->owner->name }}</span>
                    </div>
                    @endif
                </div>
                @empty
                <div class="text-center py-8 text-[#D1C4E9]/50">
                    <svg class="w-12 h-12 mx-auto mb-2 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <p class="text-sm">No qualified leads</p>
                    <p class="text-xs mt-1">Mark leads as "qualified" to see them here</p>
                </div>
                @endforelse
            </div>
        </div>

        @foreach($stages as $stage)
        <div class="flex-shrink-0" style="width: 320px;">
            <!-- Stage Header -->
            <div class="bg-[#512B58] border-2 border-[#F5B301]/30 rounded-t-xl p-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-lg font-semibold text-white">{{ $stage->name }}</h3>
                    <span class="px-2 py-1 rounded-full text-xs font-bold bg-[#F5B301] text-[#3B0A45]">
                        {{ isset($opportunitiesByStage[$stage->id]) ? $opportunitiesByStage[$stage->id]->count() : 0 }}
                    </span>
                </div>
                <div class="text-sm text-[#D1C4E9]">
                    @currency(isset($opportunitiesByStage[$stage->id]) ? $opportunitiesByStage[$stage->id]->sum('amount') : 0)
                </div>
                @if($stage->probability_default)
                <div class="text-xs text-[#D1C4E9]/70 mt-1">
                    {{ $stage->probability_default }}% probability
                </div>
                @endif
            </div>

            <!-- Opportunities Drop Zone -->
            <div 
                class="bg-[#3B0A45]/50 border-2 border-[#F5B301]/20 border-t-0 rounded-b-xl p-3 space-y-3 min-h-[500px]"
                x-data="kanbanStage({{ $stage->id }})"
                @drop.prevent="handleDrop($event)"
                @dragover.prevent="isDragOver = true; $event.dataTransfer.dropEffect = 'move'"
                @dragenter.prevent="isDragOver = true"
                @dragleave.prevent="isDragOver = false"
                :class="{ 'bg-[#F5B301]/10 border-[#F5B301]': isDragOver }"
                data-stage-id="{{ $stage->id }}"
            >
                @forelse(isset($opportunitiesByStage[$stage->id]) ? $opportunitiesByStage[$stage->id] : [] as $opportunity)
                <div 
                    draggable="true"
                    x-on:dragstart="handleDragStart($event, {{ $opportunity->id }}, {{ $stage->id }})"
                    x-on:dragend="handleDragEnd($event)"
                    x-on:click.stop=""
                    wire:click.stop="showOpportunity({{ $opportunity->id }})"
                    class="bg-[#512B58] border border-[#F5B301]/30 rounded-lg p-4 cursor-move hover:border-[#F5B301] hover:shadow-lg transition-all"
                >
                    <!-- Opportunity Card Header -->
                    <div class="flex items-start justify-between mb-2">
                        <h4 class="text-white font-medium text-sm line-clamp-2 flex-1">
                            {{ $opportunity->name }}
                        </h4>
                        @if($opportunity->priority === 'high')
                        <svg class="w-4 h-4 text-red-400 flex-shrink-0 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        @endif
                    </div>

                    <!-- Amount -->
                    <div class="text-2xl font-bold text-[#F5B301] mb-2">
                        @currency($opportunity->amount)
                    </div>

                    <!-- Probability Badge -->
                    <div class="flex items-center gap-2 mb-3">
                        <div class="flex-1 bg-[#3B0A45] rounded-full h-2">
                            <div 
                                class="bg-gradient-to-r from-[#F5B301] to-[#FFD54F] h-2 rounded-full transition-all"
                                style="width: {{ $opportunity->probability }}%"
                            ></div>
                        </div>
                        <span class="text-xs text-[#D1C4E9] font-medium">{{ $opportunity->probability }}%</span>
                    </div>

                    <!-- Contact Info -->
                    <div class="flex items-center gap-2 mb-2">
                        <x-flux::icon.user class="w-4 h-4 text-[#D1C4E9]" />
                        <span class="text-xs text-[#D1C4E9]">
                            @if($opportunity->client)
                                {{ $opportunity->client->contact_person }}
                            @elseif($opportunity->lead)
                                {{ $opportunity->lead->full_name }}
                            @else
                                No contact
                            @endif
                        </span>
                    </div>

                    <!-- Close Date -->
                    <div class="flex items-center gap-2 mb-2">
                        <x-flux::icon.calendar class="w-4 h-4 text-[#D1C4E9]" />
                        <span class="text-xs text-[#D1C4E9]">
                            {{ $opportunity->close_date?->format('M j, Y') ?? 'No date' }}
                        </span>
                    </div>

                    <!-- Assigned To -->
                    @if($opportunity->assignedTo)
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-[#F5B301] flex items-center justify-center text-xs font-bold text-[#3B0A45]">
                            {{ substr($opportunity->assignedTo->name, 0, 1) }}
                        </div>
                        <span class="text-xs text-[#D1C4E9]">{{ $opportunity->assignedTo->name }}</span>
                    </div>
                    @endif
                </div>
                @empty
                <div class="text-center py-8 text-[#D1C4E9]/50">
                    <svg class="w-12 h-12 mx-auto mb-2 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <p class="text-sm">No opportunities</p>
                </div>
                @endforelse
            </div>
        </div>
        @endforeach
    </div>

    <!-- Opportunity Details Modal -->
    @if($showOpportunityModal && $selectedOpportunity)
    <flux:modal name="opportunity-details" wire:model="showOpportunityModal" class="max-w-2xl">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg" class="flex items-center gap-3">
                    <x-flux::icon.briefcase class="w-6 h-6 text-[#F5B301]" />
                    <span>{{ $selectedOpportunity->name }}</span>
                </flux:heading>
            </div>

            <div class="space-y-4">
                <!-- Amount & Probability -->
                <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Amount</label>
                            <div class="text-2xl font-bold text-[#F5B301]">
                                @currency($selectedOpportunity->amount)
                            </div>
                        </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Probability</label>
                        <div class="text-2xl font-bold text-green-400">
                            {{ $selectedOpportunity->probability }}%
                        </div>
                    </div>
                </div>

                <!-- Stage & Close Date -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Stage</label>
                        <div class="text-white mt-1">{{ $selectedOpportunity->stage->name }}</div>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Expected Close</label>
                        <div class="text-white mt-1">
                            {{ $selectedOpportunity->close_date?->format('M j, Y') ?? 'Not set' }}
                        </div>
                    </div>
                </div>

                <!-- Contact -->
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Contact</label>
                    <div class="text-white mt-1">
                        @if($selectedOpportunity->client)
                            {{ $selectedOpportunity->client->contact_person }}
                            <span class="text-sm text-gray-500">(Client)</span>
                        @elseif($selectedOpportunity->lead)
                            {{ $selectedOpportunity->lead->full_name }}
                            <span class="text-sm text-gray-500">(Lead)</span>
                        @endif
                    </div>
                </div>

                <!-- Description -->
                @if($selectedOpportunity->description)
                <div>
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                    <div class="text-white mt-1">{{ $selectedOpportunity->description }}</div>
                </div>
                @endif
            </div>

            <div class="flex gap-3 justify-end">
                <flux:button 
                    wire:click="closeOpportunityModal"
                    variant="ghost"
                >
                    Close
                </flux:button>
                <flux:button 
                    href="{{ route('crm.opportunities.show', $selectedOpportunity) }}"
                    variant="primary"
                >
                    View Full Details
                </flux:button>
            </div>
        </div>
    </flux:modal>
    @endif
</div>

@script
<script>
Alpine.data('kanbanStage', (stageId) => ({
    isDragOver: false,
    
    handleDragStart(event, opportunityId, fromStageId) {
        event.dataTransfer.effectAllowed = 'move';
        event.dataTransfer.setData('text/plain', ''); // Required for some browsers
        event.dataTransfer.setData('opportunityId', opportunityId.toString());
        event.dataTransfer.setData('fromStageId', fromStageId.toString());
        event.dataTransfer.setData('type', 'opportunity');
        event.target.style.opacity = '0.5';
    },
    
    handleDragEnd(event) {
        event.target.style.opacity = '1';
        this.isDragOver = false;
    },
    
    handleDrop(event) {
        event.preventDefault();
        event.stopPropagation();
        this.isDragOver = false;
        
        const type = event.dataTransfer.getData('type');
        
        if (type === 'opportunity') {
            const opportunityId = event.dataTransfer.getData('opportunityId');
            const fromStageId = event.dataTransfer.getData('fromStageId');
            
            if (opportunityId && fromStageId && parseInt(fromStageId) !== stageId) {
                @this.updateOpportunityStage(
                    parseInt(opportunityId),
                    stageId,
                    parseInt(fromStageId)
                );
            }
        } else if (type === 'lead') {
            const leadId = event.dataTransfer.getData('leadId');
            
            if (leadId) {
                @this.convertLeadToOpportunity(parseInt(leadId), stageId);
            }
        }
    }
}));
</script>
@endscript
