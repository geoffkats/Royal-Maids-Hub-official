<div>
    <flux:header>
        <flux:heading size="xl">Create New Lead</flux:heading>
        <flux:subheading>Add a new lead to your CRM</flux:subheading>
    </flux:header>

    <form wire:submit="checkDuplicates" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Personal Information --}}
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm md:col-span-2">
                <flux:heading size="lg" class="mb-4">Personal Information</flux:heading>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:field>
                        <flux:label>First Name *</flux:label>
                        <flux:input wire:model.blur="first_name" placeholder="John" required />
                        <flux:error name="first_name" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Last Name *</flux:label>
                        <flux:input wire:model.blur="last_name" placeholder="Doe" required />
                        <flux:error name="last_name" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Email</flux:label>
                        <flux:input type="email" wire:model.blur="email" placeholder="john@example.com" />
                        <flux:error name="email" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Phone</flux:label>
                        <flux:input wire:model.blur="phone" placeholder="+1 (555) 123-4567" />
                        <flux:error name="phone" />
                    </flux:field>
                </div>
            </div>

            {{-- Company Information --}}
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm md:col-span-2">
                <flux:heading size="lg" class="mb-4">Company Information</flux:heading>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:field>
                        <flux:label>Company</flux:label>
                        <flux:input wire:model.blur="company" placeholder="Acme Corporation" />
                        <flux:error name="company" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Job Title</flux:label>
                        <flux:input wire:model="job_title" placeholder="CEO" />
                        <flux:error name="job_title" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Industry</flux:label>
                        <flux:input wire:model="industry" placeholder="Technology" />
                        <flux:error name="industry" />
                    </flux:field>

                    <flux:field>
                        <flux:label>City</flux:label>
                        <flux:input wire:model="city" placeholder="New York" />
                        <flux:error name="city" />
                    </flux:field>
                </div>

                <flux:field class="mt-4">
                    <flux:label>Address</flux:label>
                    <flux:textarea wire:model="address" rows="2" placeholder="123 Main St, Suite 100" />
                    <flux:error name="address" />
                </flux:field>
            </div>

            {{-- Lead Details --}}
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm md:col-span-2">
                <flux:heading size="lg" class="mb-4">Lead Details</flux:heading>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <flux:field>
                        <flux:label>Source</flux:label>
                        <flux:select wire:model="source_id">
                            <option value="">Select source...</option>
                            @foreach($sources as $source)
                                <option value="{{ $source->id }}">{{ $source->name }}</option>
                            @endforeach
                        </flux:select>
                        <flux:error name="source_id" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Status *</flux:label>
                        <flux:select wire:model="status" required>
                            @foreach($statusOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </flux:select>
                        <flux:error name="status" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Assigned To *</flux:label>
                        <flux:select wire:model="owner_id" required>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </flux:select>
                        <flux:error name="owner_id" />
                    </flux:field>
                </div>

                <flux:field class="mt-4">
                    <flux:label>Interested Package</flux:label>
                    <flux:select wire:model="interested_package_id">
                        <option value="">No package selected</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}">{{ $package->name }} - {{ $package->tier }}</option>
                        @endforeach
                    </flux:select>
                    <flux:error name="interested_package_id" />
                </flux:field>

                <flux:field class="mt-4">
                    <flux:label>Notes</flux:label>
                    <flux:textarea wire:model="notes" rows="4" placeholder="Additional information about this lead..." />
                    <flux:error name="notes" />
                </flux:field>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="flex items-center justify-between">
            <flux:button variant="ghost" href="{{ route('crm.leads.index') }}">
                Cancel
            </flux:button>
            
            <flux:button type="submit" variant="primary">
                Create Lead
            </flux:button>
        </div>
    </form>

    {{-- Duplicate Warning Modal --}}
    @if($showDuplicateModal)
        <flux:modal name="duplicate-warning" variant="flyout" class="w-full max-w-2xl" wire:model="showDuplicateModal">
            <div class="p-6">
                <div class="flex items-start gap-4 mb-6">
                    <div class="flex-shrink-0">
                        <svg class="w-12 h-12 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <flux:heading size="lg">Potential Duplicate Leads Found</flux:heading>
                        <flux:subheading class="mt-1">
                            We found {{ $duplicateSummary['total_count'] ?? 0 }} potential duplicate(s) in the system.
                            @if(($duplicateSummary['high_confidence_count'] ?? 0) > 0)
                                <span class="text-amber-600 font-semibold">
                                    {{ $duplicateSummary['high_confidence_count'] }} high confidence match(es)
                                </span>
                            @endif
                        </flux:subheading>
                    </div>
                </div>

                <div class="space-y-4 mb-6">
                    @foreach($duplicates as $duplicate)
                        <div class="border rounded-lg p-4 hover:bg-gray-50 transition">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <flux:heading size="md">{{ $duplicate['lead']->full_name }}</flux:heading>
                                        
                                        {{-- Match Score Badge --}}
                                        <flux:badge 
                                            :color="$duplicate['score'] >= 90 ? 'red' : ($duplicate['score'] >= 70 ? 'amber' : 'zinc')"
                                            size="sm"
                                        >
                                            {{ $duplicate['score'] }}% Match
                                        </flux:badge>

                                        {{-- Match Type Badge --}}
                                        <flux:badge variant="outline" size="sm">
                                            {{ ucfirst(str_replace('_', ' ', $duplicate['match_type'])) }}
                                        </flux:badge>
                                    </div>

                                    <div class="text-sm text-gray-600 space-y-1">
                                        @if($duplicate['lead']->email)
                                            <div>📧 {{ $duplicate['lead']->email }}</div>
                                        @endif
                                        @if($duplicate['lead']->phone)
                                            <div>📱 {{ $duplicate['lead']->phone }}</div>
                                        @endif
                                        @if($duplicate['lead']->company)
                                            <div>🏢 {{ $duplicate['lead']->company }}</div>
                                        @endif
                                        <div class="text-xs text-gray-500 mt-2">
                                            <strong>Reason:</strong> {{ $duplicate['reason'] }}
                                        </div>
                                    </div>
                                </div>

                                <div class="ml-4">
                                    <flux:button 
                                        size="sm" 
                                        variant="outline"
                                        href="{{ route('crm.leads.show', $duplicate['lead']->id) }}"
                                        target="_blank"
                                    >
                                        View Lead
                                    </flux:button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex items-center justify-between pt-4 border-t">
                    <flux:button 
                        variant="ghost" 
                        wire:click="closeDuplicateModal"
                    >
                        Go Back & Edit
                    </flux:button>

                    <div class="flex gap-2">
                        <flux:button 
                            variant="primary"
                            wire:click="continueAnyway"
                        >
                            Continue Anyway
                        </flux:button>
                    </div>
                </div>
            </div>
        </flux:modal>
    @endif
</div>
