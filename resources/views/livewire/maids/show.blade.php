<div class="space-y-6">
    @php
        $prefix = auth()->user()->role === 'trainer' ? 'trainer.' : '';
    @endphp

    {{-- Soft Delete Notice --}}
    @if($maid->trashed())
        <flux:callout variant="warning">
            {{ __('This maid has been deleted. Click "Restore Maid" to undelete it.') }}
        </flux:callout>
    @endif

        <!-- Header Section -->
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0">
                        @if($maid->profile_image)
                            <img class="h-20 w-20 rounded-full object-cover border-4 border-white/30" src="{{ Storage::url($maid->profile_image) }}" alt="{{ $maid->full_name }}">
                        @else
                            <div class="h-20 w-20 rounded-full bg-gradient-to-br from-[#F5B301] to-[#FFD700] flex items-center justify-center border-4 border-[#F5B301]/30">
                                <x-flux::icon.user class="w-10 h-10 text-[#3B0A45]" />
                            </div>
                        @endif
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold">{{ $maid->full_name }}</h1>
                        <p class="text-[#D1C4E9] mt-1">{{ $maid->maid_code ?? 'No Code' }} • {{ ucfirst(str_replace('_', ' ', $maid->role)) }}</p>
                        <div class="flex items-center gap-2 mt-2">
                            @php
                                $statusColors = [
                                    'available' => 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300',
                                    'in-training' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300',
                                    'booked' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300',
                                    'deployed' => 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300',
                                    'absconded' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/20 dark:text-orange-300',
                                    'terminated' => 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-300',
                                    'on-leave' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/20 dark:text-purple-300',
                                ];
                                $colorClass = $statusColors[$maid->status] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-300';
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $colorClass }}">
                                {{ ucfirst(str_replace('-', ' ', $maid->status)) }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mt-4 md:mt-0 flex gap-3">
                    @if($maid->trashed())
                        <flux:button wire:click="restore" variant="filled" icon="arrow-path">
                            {{ __('Restore Maid') }}
                        </flux:button>
                    @else
                        <flux:button as="a" :href="route($prefix . 'tickets.create', ['maid_id' => $maid->id])" variant="primary" icon="ticket">
                            {{ __('Create Ticket') }}
                        </flux:button>
                        <flux:button as="a" :href="route($prefix . 'maids.edit', $maid)" variant="outline" icon="pencil-square">
                            {{ __('Edit') }}
                        </flux:button>
                    @endif
                    <flux:button as="a" :href="route($prefix . 'maids.index')" variant="outline" icon="arrow-left">
                        {{ __('Back to List') }}
                    </flux:button>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-4 shadow-lg">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-[#F5B301]/20">
                        <x-flux::icon.calendar class="w-6 h-6 text-[#F5B301]" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-[#D1C4E9]">{{ __('Arrival Date') }}</p>
                        <p class="text-lg font-bold text-white">{{ optional($maid->date_of_arrival)->format('M d, Y') ?: '—' }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-4 shadow-lg">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-[#4CAF50]/20">
                        <x-flux::icon.academic-cap class="w-6 h-6 text-[#4CAF50]" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-[#D1C4E9]">{{ __('Experience') }}</p>
                        <p class="text-lg font-bold text-white">{{ $maid->experience_years ?? 0 }} {{ __('years') }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-4 shadow-lg">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-[#64B5F6]/20">
                        <x-flux::icon.phone class="w-6 h-6 text-[#64B5F6]" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-[#D1C4E9]">{{ __('Phone') }}</p>
                        <p class="text-lg font-bold text-white">{{ $maid->phone }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-4 shadow-lg">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-[#FFC107]/20">
                        <x-flux::icon.flag class="w-6 h-6 text-[#FFC107]" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-[#D1C4E9]">{{ __('Nationality') }}</p>
                        <p class="text-lg font-bold text-white">{{ $maid->nationality ?? '—' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <button type="button" wire:click="setActiveTab('overview')"
                    class="rounded-lg px-4 py-2 text-sm font-semibold border {{ $activeTab === 'overview' ? 'bg-[#F5B301]/20 text-[#F5B301] border-[#F5B301]/40' : 'bg-[#3B0A45] text-[#D1C4E9] border-[#F5B301]/20' }}">
                {{ __('Overview') }}
            </button>
            <button type="button" wire:click="setActiveTab('tickets')"
                    class="rounded-lg px-4 py-2 text-sm font-semibold border {{ $activeTab === 'tickets' ? 'bg-[#F5B301]/20 text-[#F5B301] border-[#F5B301]/40' : 'bg-[#3B0A45] text-[#D1C4E9] border-[#F5B301]/20' }}">
                {{ __('Tickets') }}
            </button>
        </div>

        <!-- Main Content Grid -->
        @if($activeTab === 'overview')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Personal Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Personal Information -->
                <div class="details-card">
                    <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-4 flex items-center gap-2">
                        <x-flux::icon.user class="w-5 h-5 text-indigo-600" />
                        {{ __('Personal Information') }}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Full Name') }}</label>
                            <p class="mt-1 text-sm text-neutral-900 dark:text-white">{{ $maid->full_name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Date of Birth') }}</label>
                            <p class="mt-1 text-sm text-neutral-900 dark:text-white">{{ optional($maid->date_of_birth)->format('M d, Y') ?: '—' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('National ID') }}</label>
                            <p class="mt-1 text-sm text-neutral-900 dark:text-white">{{ $maid->nin ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Marital Status') }}</label>
                            <p class="mt-1 text-sm text-neutral-900 dark:text-white">{{ ucfirst($maid->marital_status ?? '—') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Children') }}</label>
                            <p class="mt-1 text-sm text-neutral-900 dark:text-white">{{ $maid->number_of_children ?? 0 }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Mother Tongue') }}</label>
                            <p class="mt-1 text-sm text-neutral-900 dark:text-white">{{ $maid->mother_tongue ?? '—' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Location Information -->
                <div class="details-card">
                    <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-4 flex items-center gap-2">
                        <x-flux::icon.map-pin class="w-5 h-5 text-indigo-600" />
                        {{ __('Location Information') }}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Tribe') }}</label>
                            <p class="mt-1 text-sm text-neutral-900 dark:text-white">{{ $maid->tribe ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Village') }}</label>
                            <p class="mt-1 text-sm text-neutral-900 dark:text-white">{{ $maid->village ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('District') }}</label>
                            <p class="mt-1 text-sm text-neutral-900 dark:text-white">{{ $maid->district ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('LC1 Chairperson') }}</label>
                            <p class="mt-1 text-sm text-neutral-900 dark:text-white">{{ $maid->lc1_chairperson ?? '—' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Family Information -->
                <div class="details-card">
                    <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-4 flex items-center gap-2">
                        <x-flux::icon.user-group class="w-5 h-5 text-indigo-600" />
                        {{ __('Family Information') }}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Mother') }}</label>
                            <p class="mt-1 text-sm text-neutral-900 dark:text-white">{{ $maid->mother_name_phone ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Father') }}</label>
                            <p class="mt-1 text-sm text-neutral-900 dark:text-white">{{ $maid->father_name_phone ?? '—' }}</p>
                        </div>
                    </div>

                    @if(!empty($maid->family_members))
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Additional Family Members') }}</label>
                            <div class="mt-3 space-y-2">
                                @foreach($maid->family_members as $member)
                                    <div class="rounded-md border border-neutral-200 dark:border-neutral-700 p-3 text-sm">
                                        <div class="font-semibold text-neutral-900 dark:text-white">{{ $member['name'] ?? '—' }}</div>
                                        <div class="text-neutral-600 dark:text-neutral-400">
                                            {{ $member['relationship'] ?? '—' }}
                                            @if(!empty($member['phone']))
                                                • {{ $member['phone'] }}
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Education & Experience -->
                <div class="details-card">
                    <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-4 flex items-center gap-2">
                        <x-flux::icon.academic-cap class="w-5 h-5 text-indigo-600" />
                        {{ __('Education & Experience') }}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Education Level') }}</label>
                            <p class="mt-1 text-sm text-neutral-900 dark:text-white">{{ $maid->education_level ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Experience Years') }}</label>
                            <p class="mt-1 text-sm text-neutral-900 dark:text-white">{{ $maid->experience_years ?? 0 }} {{ __('years') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('English Proficiency') }}</label>
                            <p class="mt-1 text-sm text-neutral-900 dark:text-white">{{ $maid->english_proficiency ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Previous Work') }}</label>
                            <p class="mt-1 text-sm text-neutral-900 dark:text-white">{{ $maid->previous_work ?? '—' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Medical Information -->
                <div class="details-card">
                    <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-4 flex items-center gap-2">
                        <x-flux::icon.heart class="w-5 h-5 text-indigo-600" />
                        {{ __('Medical Information') }}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @php
                            $medicalStatus = $maid->medical_status ?? [];
                            $hepatitisB = $medicalStatus['hepatitis_b']['result'] ?? null;
                            $hepatitisBDate = $medicalStatus['hepatitis_b']['date'] ?? null;
                            $hiv = $medicalStatus['hiv']['result'] ?? null;
                            $hivDate = $medicalStatus['hiv']['date'] ?? null;
                            $urineHcg = $medicalStatus['urine_hcg']['result'] ?? null;
                            $urineHcgDate = $medicalStatus['urine_hcg']['date'] ?? null;
                            $medicalNotes = $medicalStatus['notes'] ?? null;
                            
                            $formatResult = function($result) {
                                if (!$result) return '—';
                                return match($result) {
                                    'positive' => 'Positive',
                                    'negative' => 'Negative',
                                    'pending' => 'Pending',
                                    'not_tested' => 'Not Tested',
                                    default => ucfirst($result),
                                };
                            };
                        @endphp
                        <div>
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Hepatitis B Test Result') }}</label>
                            <p class="mt-1 text-sm text-neutral-900 dark:text-white">{{ $formatResult($hepatitisB) }}</p>
                            @if($hepatitisBDate)
                                <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                                    {{ __('Date:') }} {{ \Carbon\Carbon::parse($hepatitisBDate)->format('M d, Y') }}
                                </p>
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('HIV Test Result') }}</label>
                            <p class="mt-1 text-sm text-neutral-900 dark:text-white">{{ $formatResult($hiv) }}</p>
                            @if($hivDate)
                                <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                                    {{ __('Date:') }} {{ \Carbon\Carbon::parse($hivDate)->format('M d, Y') }}
                                </p>
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Urine HCG Test Result') }}</label>
                            <p class="mt-1 text-sm text-neutral-900 dark:text-white">{{ $formatResult($urineHcg) }}</p>
                            @if($urineHcgDate)
                                <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                                    {{ __('Date:') }} {{ \Carbon\Carbon::parse($urineHcgDate)->format('M d, Y') }}
                                </p>
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Medical Notes') }}</label>
                            <p class="mt-1 text-sm text-neutral-900 dark:text-white">{{ $medicalNotes ?? '—' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Additional Notes -->
                @if($maid->additional_notes)
                <div class="details-card">
                    <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-4 flex items-center gap-2">
                        <x-flux::icon.chat-bubble-left-ellipsis class="w-5 h-5 text-indigo-600" />
                        {{ __('Additional Notes') }}
                    </h3>
                    <p class="text-sm text-neutral-900 dark:text-white">{{ $maid->additional_notes }}</p>
                </div>
                @endif
            </div>

            <!-- Right Column - Professional Info & Documents -->
            <div class="space-y-6">
                <!-- Professional Information -->
                <div class="details-card">
                    <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-4 flex items-center gap-2">
                        <x-flux::icon.briefcase class="w-5 h-5 text-indigo-600" />
                        {{ __('Professional Information') }}
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Role') }}</label>
                            <p class="mt-1 text-sm text-neutral-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $maid->role)) }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Primary Status') }}</label>
                            <p class="mt-1 text-sm text-neutral-900 dark:text-white">{{ ucfirst(str_replace('-', ' ', $maid->status)) }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Secondary Status') }}</label>
                            <p class="mt-1 text-sm text-neutral-900 dark:text-white">{{ $maid->secondary_status ? ucfirst(str_replace('-', ' ', $maid->secondary_status)) : '—' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400">{{ __('Work Status') }}</label>
                            <p class="mt-1 text-sm text-neutral-900 dark:text-white">{{ $maid->work_status ? ucfirst(str_replace('-', ' ', $maid->work_status)) : '—' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Documents -->
                <div class="details-card">
                    <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-4 flex items-center gap-2">
                        <x-flux::icon.document-text class="w-5 h-5 text-indigo-600" />
                        {{ __('Documents') }}
                    </h3>
                    
                    <!-- Profile Image -->
                    @if($maid->profile_image)
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-2">{{ __('Profile Image') }}</label>
                        <img src="{{ Storage::url($maid->profile_image) }}" alt="Profile Image" class="w-full h-48 object-cover rounded-lg border border-neutral-200 dark:border-neutral-700">
                    </div>
                    @endif

                    <!-- Additional Documents -->
                    @if($maid->additional_documents && count($maid->additional_documents) > 0)
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-3">{{ __('Additional Documents') }}</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($maid->additional_documents as $index => $document)
                                <flux:button as="a" href="{{ Storage::url($document) }}" target="_blank" variant="outline" size="sm" icon="arrow-top-right-on-square">
                                    {{ __('View doc') }} {{ $index + 1 }}
                                </flux:button>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- ID Scans -->
                    @if($maid->id_scans && count($maid->id_scans) > 0)
                    <div>
                        <label class="block text-sm font-medium text-neutral-600 dark:text-neutral-400 mb-3">{{ __('ID Scans') }}</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($maid->id_scans as $index => $scan)
                                <flux:button as="a" href="{{ Storage::url($scan) }}" target="_blank" variant="outline" size="sm" icon="arrow-top-right-on-square">
                                    {{ __('ID') }} {{ $index + 1 }}
                                </flux:button>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if(!$maid->profile_image && (!$maid->additional_documents || count($maid->additional_documents) == 0) && (!$maid->id_scans || count($maid->id_scans) == 0))
                    <p class="text-sm text-neutral-500 dark:text-neutral-400 italic">{{ __('No documents uploaded') }}</p>
                    @endif
                </div>
            </div>


            <!-- Contracts Section -->
            <div class="details-card">
                <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-4 flex items-center gap-2">
                    <x-flux::icon.document-text class="w-5 h-5 text-purple-600" />
                    {{ __('Maid Contracts') }}
                </h3>

                @if($contracts->isEmpty())
                    <p class="text-sm text-neutral-500 dark:text-neutral-400 italic">{{ __('No contracts for this maid yet.') }}</p>
                @else
                    <div class="space-y-3">
                        @foreach($contracts as $contract)
                            <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4 hover:bg-neutral-50 dark:hover:bg-neutral-900/30 transition-colors">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <a href="{{ route('contracts.show', $contract) }}" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">
                                            {{ $contract->getClient()?->company_name ?? 'Contract #' . $contract->id }}
                                        </a>
                                        <div class="mt-2 grid gap-2 grid-cols-3 text-xs">
                                            <div>
                                                <span class="text-neutral-600 dark:text-neutral-400">{{ __('Start') }}:</span>
                                                <span class="ml-1 text-neutral-900 dark:text-white">{{ $contract->contract_start_date?->format('M d, Y') }}</span>
                                            </div>
                                            <div>
                                                <span class="text-neutral-600 dark:text-neutral-400">{{ __('End') }}:</span>
                                                <span class="ml-1 text-neutral-900 dark:text-white">{{ $contract->contract_end_date?->format('M d, Y') }}</span>
                                            </div>
                                            <div>
                                                <span class="text-neutral-600 dark:text-neutral-400">{{ __('Status') }}:</span>
                                                <flux:badge size="sm" :color="$contract->contract_status === 'active' ? 'green' : 'gray'" class="ml-1">{{ ucfirst($contract->contract_status) }}</flux:badge>
                                            </div>
                                            <div>
                                                <span class="text-neutral-600 dark:text-neutral-400">{{ __('Worked') }}:</span>
                                                <span class="ml-1 text-neutral-900 dark:text-white">{{ $contract->worked_days ?? 0 }} {{ __('days') }}</span>
                                            </div>
                                            <div>
                                                <span class="text-neutral-600 dark:text-neutral-400">{{ __('Pending') }}:</span>
                                                <span class="ml-1 text-neutral-900 dark:text-white">{{ $contract->pending_days ?? 0 }} {{ __('days') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Training & Deployment Info -->
            <div class="details-card">
                <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-4 flex items-center gap-2">
                    <x-flux::icon.academic-cap class="w-5 h-5 text-blue-600" />
                    {{ __('Training & Deployment') }}
                </h3>

                @if($deployment)
                    <div class="mb-6 rounded-lg border border-green-200 dark:border-green-900/50 bg-green-50 dark:bg-green-900/10 p-4">
                        <h4 class="font-medium text-green-900 dark:text-green-300 mb-3">{{ __('Current Deployment') }}</h4>
                        <div class="grid gap-3 text-sm">
                            <div>
                                <span class="text-green-700 dark:text-green-400">{{ __('Client') }}:</span>
                                <span class="ml-2 text-neutral-900 dark:text-white">{{ $deployment->client->company_name ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="text-green-700 dark:text-green-400">{{ __('Location') }}:</span>
                                <span class="ml-2 text-neutral-900 dark:text-white">{{ $deployment->deployment_location ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="text-green-700 dark:text-green-400">{{ __('Start Date') }}:</span>
                                <span class="ml-2 text-neutral-900 dark:text-white">{{ $deployment->deployment_start_date?->format('M d, Y') }}</span>
                            </div>
                            @if($deployment->deployment_start_date)
                                <div>
                                    <span class="text-green-700 dark:text-green-400">{{ __('Days on Deployment') }}:</span>
                                    <span class="ml-2 text-neutral-900 dark:text-white font-medium">{{ $deployment->deployment_start_date->diffInDays(now()) }} {{ __('days') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                @if($trainingPrograms->isNotEmpty())
                    <div>
                        <h4 class="font-medium text-neutral-900 dark:text-white mb-3">{{ __('Training Programs') }}</h4>
                        <div class="space-y-2">
                            @foreach($trainingPrograms as $program)
                                <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-3">
                                    <div class="flex items-center justify-between">
                                        <a href="{{ route('programs.show', $program) }}" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">
                                            {{ $program->name }}
                                        </a>
                                        <flux:badge size="sm" :color="$program->status === 'completed' ? 'green' : 'yellow'">
                                            {{ ucfirst($program->status) }}
                                        </flux:badge>
                                    </div>
                                    <div class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                                        {{ $program->start_date?->format('M d, Y') }} - {{ $program->end_date?->format('M d, Y') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <p class="text-sm text-neutral-500 dark:text-neutral-400 italic">{{ __('No training programs assigned.') }}</p>
                @endif
            </div>

            <!-- Recent Bookings -->
            <div class="details-card">
                <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-4 flex items-center gap-2">
                    <x-flux::icon.calendar class="w-5 h-5 text-indigo-600" />
                    {{ __('Recent Bookings') }}
                </h3>

                @php
                    $recentBookings = $maid->bookings()->with('client.user')->latest()->take(5)->get();
                @endphp

                @if($recentBookings->isEmpty())
                    <p class="text-sm text-neutral-500 dark:text-neutral-400 italic">{{ __('No bookings for this maid yet.') }}</p>
                @else
                    <div class="overflow-hidden rounded-lg border border-neutral-200 dark:border-neutral-700">
                        <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                            <thead class="bg-neutral-50 dark:bg-neutral-900/50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('ID') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Client') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Type') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Status') }}</th>
                                    <th class="px-4 py-2 text-right text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-800">
                                @foreach($recentBookings as $booking)
                                <tr class="transition-colors hover:bg-neutral-50 dark:hover:bg-neutral-900/30">
                                    <td class="px-4 py-3 text-sm font-medium text-neutral-900 dark:text-white">#{{ $booking->id }}</td>
                                    <td class="px-4 py-3 text-sm text-neutral-700 dark:text-neutral-300">{{ $booking->client?->contact_person ?? $booking->client?->user?->name }}</td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center rounded-md bg-blue-100 px-2 py-1 text-xs font-medium text-blue-800 dark:bg-blue-900/20 dark:text-blue-300">{{ ucwords(str_replace('-', ' ', $booking->booking_type)) }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium {{ match($booking->status) {
                                            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300',
                                            'confirmed' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300',
                                            'active' => 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300',
                                            'completed' => 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-300',
                                            'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300',
                                            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-300'
                                        } }}">{{ ucfirst($booking->status) }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route($prefix . 'bookings.show', $booking) }}" class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 text-sm">
                                            <x-flux::icon.eye class="w-4 h-4" /> {{ __('View') }}
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Deployment History -->
            <div class="details-card">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-neutral-900 dark:text-white flex items-center gap-2">
                        <x-flux::icon.map-pin class="w-5 h-5 text-indigo-600" />
                        {{ __('Deployment History') }}
                    </h3>
                    <a href="{{ route('deployments.index', ['search' => $maid->maid_code]) }}" 
                       class="text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium">
                        {{ __('View All') }} →
                    </a>
                </div>

                @php
                    $deployments = $maid->deployments()->latest('deployment_date')->take(3)->get();
                @endphp

                @if($deployments->isEmpty())
                    <p class="text-sm text-neutral-500 dark:text-neutral-400 italic">{{ __('No deployment records yet.') }}</p>
                @else
                    <div class="space-y-3">
                        @foreach($deployments as $deployment)
                            <div class="p-4 rounded-lg border border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-900/50 hover:border-indigo-300 dark:hover:border-indigo-700 transition-colors">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="font-medium text-neutral-900 dark:text-white">{{ $deployment->client_name }}</span>
                                            <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium {{ match($deployment->status) {
                                                'active' => 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300',
                                                'completed' => 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-300',
                                                'terminated' => 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300',
                                                default => 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-300'
                                            } }}">{{ ucfirst($deployment->status) }}</span>
                                        </div>
                                        <div class="grid grid-cols-2 gap-2 text-sm text-neutral-600 dark:text-neutral-400">
                                            <div class="flex items-center gap-1">
                                                <x-flux::icon.map-pin class="w-4 h-4" />
                                                <span>{{ $deployment->deployment_location }}</span>
                                            </div>
                                            <div class="flex items-center gap-1">
                                                <x-flux::icon.calendar class="w-4 h-4" />
                                                <span>{{ $deployment->deployment_date?->format('M d, Y') }}</span>
                                            </div>
                                            <div class="flex items-center gap-1">
                                                <x-flux::icon.briefcase class="w-4 h-4" />
                                                <span>{{ ucfirst(str_replace('-', ' ', $deployment->contract_type)) }}</span>
                                            </div>
                                            @if($deployment->monthly_salary)
                                                <div class="flex items-center gap-1">
                                                    <x-flux::icon.currency-dollar class="w-4 h-4" />
                                                    <span>UGX {{ number_format($deployment->monthly_salary) }}/mo</span>
                                                </div>
                                            @endif
                                        </div>
                                        @if($deployment->status !== 'active' && $deployment->end_date)
                                            <div class="mt-2 text-xs text-neutral-500 dark:text-neutral-400">
                                                <span class="font-medium">{{ __('Ended:') }}</span> {{ $deployment->end_date->format('M d, Y') }}
                                                @if($deployment->end_reason)
                                                    <span class="ml-2">• {{ $deployment->end_reason }}</span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        @endif

        @if($activeTab === 'tickets')
            <div class="details-card">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-neutral-900 dark:text-white flex items-center gap-2">
                        <x-flux::icon.ticket class="w-5 h-5 text-red-600" />
                        {{ __('Support Tickets') }}
                    </h3>
                    <a href="{{ route($prefix . 'tickets.create', ['maid_id' => $maid->id]) }}" class="text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium">
                        {{ __('Create Ticket') }}
                    </a>
                </div>

                @if($tickets->count() === 0)
                    <p class="text-sm text-neutral-500 dark:text-neutral-400 italic">{{ __('No tickets for this maid yet.') }}</p>
                @else
                    <div class="overflow-hidden rounded-lg border border-neutral-200 dark:border-neutral-700">
                        <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                            <thead class="bg-neutral-50 dark:bg-neutral-900/50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('ID') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Subject') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Status') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Priority') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold uppercase tracking-wider text-neutral-700 dark:text-neutral-300">{{ __('Created') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-800">
                                @foreach($tickets as $ticket)
                                    <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/50">
                                        <td class="px-4 py-3 text-sm text-neutral-900 dark:text-white">{{ $ticket->id }}</td>
                                        <td class="px-4 py-3 text-sm text-blue-600 dark:text-blue-400"><a href="{{ route($prefix . 'tickets.show', $ticket) }}">{{ $ticket->subject }}</a></td>
                                        <td class="px-4 py-3 text-sm"><flux:badge>{{ ucfirst($ticket->status) }}</flux:badge></td>
                                        <td class="px-4 py-3 text-sm"><flux:badge :color="$ticket->priority === 'high' ? 'red' : ($ticket->priority === 'medium' ? 'yellow' : 'green')">{{ ucfirst($ticket->priority) }}</flux:badge></td>
                                        <td class="px-4 py-3 text-sm text-neutral-500 dark:text-neutral-400">{{ $ticket->created_at->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $tickets->links() }}
                    </div>
                @endif
            </div>
        @endif
    </div>