
<div class="space-y-6">
    <!-- Header Section -->
    <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
        <div class="flex items-center gap-3">
            <div class="rounded-full bg-[#F5B301] p-2">
                <flux:icon.ticket class="size-6 text-[#3B0A45]" />
            </div>
            <div>
                <flux:heading size="xl" class="text-white">{{ __('Create New Ticket') }}</flux:heading>
                <flux:subheading class="text-[#D1C4E9]">{{ __('Submit a support request and get help from our team') }}</flux:subheading>
            </div>
        </div>
    </div>

    <!-- Tier-Based Priority Preview -->
    @if($auto_boosted_priority && $auto_boosted_priority !== $priority)
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#F5B301]/10 p-4 shadow-lg">
            <div class="flex items-center gap-3">
                <flux:icon.arrow-up class="size-5 text-[#F5B301]" />
                <div>
                    <div class="font-semibold text-white">
                        Priority will be automatically boosted to 
                        <span class="text-[#F5B301] font-bold">{{ ucfirst($auto_boosted_priority) }}</span>
                        @if($selectedClient)
                            based on {{ ucfirst($selectedClient->subscription_tier ?? 'silver') }} package tier.
                        @endif
                    </div>
                    <div class="text-sm text-[#D1C4E9] mt-1">
                        Expected response: {{ $slaResponseTime }} minutes | 
                        Expected resolution: {{ $slaResolutionTime }} minutes
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Success Message -->
    @if (session()->has('success'))
        <div class="rounded-lg border border-[#4CAF50]/30 bg-[#4CAF50]/10 p-4 shadow-lg">
            <div class="flex items-center gap-3">
                <flux:icon.check-circle class="size-5 text-[#4CAF50]" />
                <span class="font-medium text-white">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <form wire:submit.prevent="submit" enctype="multipart/form-data" class="space-y-8">
        <!-- Ticket Information -->
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <div class="flex items-center gap-2 mb-4">
                <flux:icon.ticket class="size-5 text-[#F5B301]" />
                <flux:heading size="md" class="text-white">{{ __('Ticket Information') }}</flux:heading>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <!-- Ticket Type -->
                <div>
                    <flux:select wire:model="type" :label="__('Ticket Type')" :required="true">
                        <option value="">{{ __('Select type...') }}</option>
                        <option value="client_issue">{{ __('Client Issue') }}</option>
                        <option value="maid_support">{{ __('Maid Support') }}</option>
                        <option value="deployment_issue">{{ __('Deployment Issue') }}</option>
                        <option value="billing">{{ __('Billing') }}</option>
                        <option value="training">{{ __('Training') }}</option>
                        <option value="operations">{{ __('Operations') }}</option>
                        <option value="general">{{ __('General') }}</option>
                    </flux:select>
                    @error('type')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </div>

        <!-- Category -->
        <div>
            <label class="block text-sm font-bold text-[#F5B301] mb-2 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                </svg>
                Category <span class="text-red-400">*</span>
            </label>
            <select wire:model="category" class="w-full py-3 px-4 rounded-lg border-2 border-[#F5B301]/30 bg-[#3B0A45] text-white focus:border-[#F5B301] focus:ring-2 focus:ring-[#F5B301]/50 transition-all shadow-sm hover:shadow-md">
                <option value="">Select a category...</option>
                
                @php
                    $categories = config('tickets.categories', [
                        'Inquiry' => 'Inquiry',
                        'Quote Request' => 'Quote Request',
                        'Pre-Sales Support' => 'Pre-Sales Support',
                        'Service Quality' => 'Service Quality',
                        'Maid Absence' => 'Maid Absence',
                        'Maid Performance' => 'Maid Performance',
                        'Maid Request' => 'Maid Request',
                        'Rescheduling' => 'Rescheduling',
                        'Cancellation' => 'Cancellation',
                        'Payment Issue' => 'Payment Issue',
                        'Billing Error' => 'Billing Error',
                        'Refund Request' => 'Refund Request',
                        'Invoice Request' => 'Invoice Request',
                        'Technical Issue' => 'Technical Issue',
                        'Account Access' => 'Account Access',
                        'App Problem' => 'App Problem',
                        'Safety Concern' => 'Safety Concern',
                        'Legal Issue' => 'Legal Issue',
                        'Emergency' => 'Emergency',
                        'Harassment' => 'Harassment',
                        'Feedback' => 'Feedback',
                        'Complaint' => 'Complaint',
                        'Other' => 'Other',
                    ]);
                @endphp
                
                <!-- Pre-Sales Categories -->
                <optgroup label="Pre-Sales">
                    <option value="Inquiry">Inquiry</option>
                    <option value="Quote Request">Quote Request</option>
                    <option value="Pre-Sales Support">Pre-Sales Support</option>
                </optgroup>
                
                <!-- Service Categories -->
                <optgroup label="Service">
                    <option value="Service Quality">Service Quality</option>
                    <option value="Maid Absence">Maid Absence</option>
                    <option value="Maid Performance">Maid Performance</option>
                    <option value="Maid Request">Maid Request</option>
                    <option value="Rescheduling">Rescheduling</option>
                    <option value="Cancellation">Cancellation</option>
                </optgroup>
                
                <!-- Billing Categories -->
                <optgroup label="Billing">
                    <option value="Payment Issue">Payment Issue</option>
                    <option value="Billing Error">Billing Error</option>
                    <option value="Refund Request">Refund Request</option>
                    <option value="Invoice Request">Invoice Request</option>
                </optgroup>
                
                <!-- Technical Categories -->
                <optgroup label="Technical">
                    <option value="Technical Issue">Technical Issue</option>
                    <option value="Account Access">Account Access</option>
                    <option value="App Problem">App Problem</option>
                </optgroup>
                
                <!-- Critical Categories -->
                <optgroup label="Critical">
                    <option value="Safety Concern">Safety Concern</option>
                    <option value="Legal Issue">Legal Issue</option>
                    <option value="Emergency">Emergency</option>
                    <option value="Harassment">Harassment</option>
                </optgroup>
                
                <!-- General Categories -->
                <optgroup label="General">
                    <option value="Feedback">Feedback</option>
                    <option value="Complaint">Complaint</option>
                    <option value="Other">Other</option>
                </optgroup>
            </select>
            @error('category') <span class="text-red-400 text-sm mt-1 block flex items-center gap-1"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>{{ $message }}</span> @enderror
        </div>

        <!-- Priority with Auto-Boost Preview -->
        <div class="bg-[#3B0A45]/50 p-6 rounded-xl border-2 border-[#F5B301]/20 shadow-inner">
            <label class="block text-sm font-bold text-[#F5B301] mb-2 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                Priority <span class="text-red-400">*</span>
            </label>
            <select wire:model="priority" class="w-full py-3 px-4 rounded-lg border-2 border-[#F5B301]/30 bg-[#3B0A45] text-white focus:border-[#F5B301] focus:ring-2 focus:ring-[#F5B301]/50 transition-all shadow-sm hover:shadow-md">
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
                <option value="urgent">Urgent</option>
            </select>
            <div class="mt-3 p-3 bg-[#F5B301]/10 rounded-lg border border-[#F5B301]/30">
                <div class="flex items-center gap-2 text-sm">
                @if($priority_preview && $priority_preview !== $priority)
                        <svg class="w-5 h-5 text-[#F5B301] animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
                        </svg>
                        <span class="font-semibold text-[#F5B301]">Auto-Boosted:</span>
                        <span class="text-white">{{ ucfirst($priority) }}</span>
                        <span class="text-[#F5B301]">→</span>
                        <span class="font-bold text-red-400 text-lg">{{ ucfirst($priority_preview) }}</span>
                @else
                        <svg class="w-5 h-5 text-[#D1C4E9]" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span class="font-semibold text-[#D1C4E9]">Final Priority:</span>
                        <span class="text-white font-bold">{{ ucfirst($priority_preview ?? $priority) }}</span>
                @endif
                </div>
            </div>
            @error('priority') <span class="text-red-400 text-sm mt-2 block flex items-center gap-1"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>{{ $message }}</span> @enderror
        </div>

        <!-- Subject -->
        <div>
            <label class="block text-sm font-bold text-[#F5B301] mb-2 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                </svg>
                Subject <span class="text-red-400">*</span>
            </label>
            <input type="text" wire:model="subject" class="w-full py-3 px-4 rounded-lg border-2 border-[#F5B301]/30 bg-[#3B0A45] text-white placeholder-[#D1C4E9]/60 focus:border-[#F5B301] focus:ring-2 focus:ring-[#F5B301]/50 transition-all shadow-sm hover:shadow-md" maxlength="255" placeholder="Brief summary of the issue">
            @error('subject') <span class="text-red-400 text-sm mt-1 block flex items-center gap-1"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>{{ $message }}</span> @enderror
        </div>

        <!-- Description -->
        <div>
            <label class="block text-sm font-bold text-[#F5B301] mb-2 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                </svg>
                Description <span class="text-red-400">*</span>
            </label>
            <textarea wire:model="description" rows="6" class="w-full py-3 px-4 rounded-lg border-2 border-[#F5B301]/30 bg-[#3B0A45] text-white placeholder-[#D1C4E9]/60 focus:border-[#F5B301] focus:ring-2 focus:ring-[#F5B301]/50 transition-all shadow-sm hover:shadow-md" placeholder="Describe the issue in detail..."></textarea>
            @error('description') <span class="text-red-400 text-sm mt-1 block flex items-center gap-1"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>{{ $message }}</span> @enderror
        </div>

        <!-- Related Entities Section -->
        <div class="bg-[#3B0A45]/30 p-6 rounded-xl border border-[#F5B301]/20">
            <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-[#F5B301]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                </svg>
                Link Related Entities (Optional)
            </h3>
            <div class="space-y-4">
        <div>
                    <label class="block text-sm font-semibold text-[#D1C4E9] mb-2">Related Client</label>
            <select wire:model="related_client_id" class="w-full py-3 px-4 rounded-lg border-2 border-[#F5B301]/20 bg-[#3B0A45] text-white focus:border-[#F5B301] focus:ring-2 focus:ring-[#F5B301]/50 transition-all">
                <option value="">None</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->contact_person }} @if($client->subscription_tier) ({{ ucfirst($client->subscription_tier) }}) @endif</option>
                @endforeach
            </select>
                    @error('related_client_id') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
        </div>
        <div>
                    <label class="block text-sm font-semibold text-[#D1C4E9] mb-2">Related Maid</label>
            <select wire:model="related_maid_id" class="w-full py-3 px-4 rounded-lg border-2 border-[#F5B301]/20 bg-[#3B0A45] text-white focus:border-[#F5B301] focus:ring-2 focus:ring-[#F5B301]/50 transition-all">
                <option value="">None</option>
                @foreach($maids as $maid)
                    <option value="{{ $maid->id }}">{{ $maid->first_name }} {{ $maid->last_name }}</option>
                @endforeach
            </select>
                    @error('related_maid_id') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
        </div>
        <div>
                    <label class="block text-sm font-semibold text-[#D1C4E9] mb-2">Related Booking</label>
            <select wire:model="related_booking_id" class="w-full py-3 px-4 rounded-lg border-2 border-[#F5B301]/20 bg-[#3B0A45] text-white focus:border-[#F5B301] focus:ring-2 focus:ring-[#F5B301]/50 transition-all">
                <option value="">None</option>
                @foreach($bookings as $booking)
                    <option value="{{ $booking->id }}">Booking #{{ $booking->id }}</option>
                @endforeach
            </select>
                    @error('related_booking_id') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- On Behalf Of (Admin/Trainer only) -->
        @if($userRole === 'admin' || $userRole === 'trainer')
        <div class="bg-gradient-to-r from-[#F5B301]/10 to-transparent p-6 rounded-xl border-2 border-[#F5B301]/30">
            <h3 class="text-lg font-bold text-white mb-3 flex items-center gap-2">
                <flux:icon.user-group class="size-5 text-[#F5B301]" />
                Creating Ticket On Behalf Of
            </h3>
            <label class="block text-sm font-semibold text-[#D1C4E9] mb-3">Select user type:</label>
            <div class="flex gap-4 mt-2">
                <label class="inline-flex items-center">
                    <input type="radio" wire:model="on_behalf_type" value="self" class="form-radio text-[#F5B301] w-5 h-5">
                    <span class="ml-2 text-white font-semibold">Myself</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" wire:model="on_behalf_type" value="client" class="form-radio text-[#F5B301] w-5 h-5">
                    <span class="ml-2 text-white font-semibold">Client</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" wire:model="on_behalf_type" value="maid" class="form-radio text-[#F5B301] w-5 h-5">
                    <span class="ml-2 text-white font-semibold">Maid</span>
                </label>
            </div>
            <div class="mt-4">
                @if($on_behalf_type === 'client')
                    <flux:select wire:model="on_behalf_client_id" :label="__('Select Client')">
                        <option value="">{{ __('Select client...') }}</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">
                                {{ $client->contact_person }} 
                                @if($client->subscription_tier)
                                    <span class="badge badge-{{ $client->subscription_tier }}">
                                        {{ ucfirst($client->subscription_tier) }}
                                    </span>
                                @endif
                            </option>
                        @endforeach
                    </flux:select>
                    
                    @if($selectedClient)
                        <div class="mt-3 p-3 bg-[#F5B301]/10 rounded-lg border border-[#F5B301]/30">
                            <div class="text-sm text-white">
                                <strong>{{ $selectedClient->contact_person }}</strong> has a 
                                <strong class="text-[#F5B301]">{{ ucfirst($selectedClient->subscription_tier ?? 'silver') }}</strong> package.
                                Priority will be automatically adjusted for faster response.
                            </div>
                        </div>
                    @endif
                @elseif($on_behalf_type === 'maid')
                    <flux:select wire:model="on_behalf_maid_id" :label="__('Select Maid')">
                        <option value="">{{ __('Select maid...') }}</option>
                        @foreach($maids as $maid)
                            <option value="{{ $maid->id }}">{{ $maid->first_name }} {{ $maid->last_name }}</option>
                        @endforeach
                    </flux:select>
                @endif
            </div>
        </div>
        @endif

        <!-- Location (optional) -->
        <div>
            <label class="block text-sm font-bold text-[#F5B301] mb-2 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Location
            </label>
            <flux:input wire:model="location_address" :label="__('Location Address')" placeholder="Address or location details" />
            @error('location_address') <flux:error>{{ $message }}</flux:error> @enderror
        </div>

        <!-- Attachments -->
        <div>
            <label class="block text-sm font-bold text-[#F5B301] mb-2 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                </svg>
                Attachments
            </label>
            <div class="relative">
                <input type="file" wire:model="attachments" multiple class="w-full py-3 px-4 rounded-lg border-2 border-dashed border-[#F5B301]/40 bg-[#3B0A45] text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-[#F5B301] file:text-[#3B0A45] file:font-bold hover:file:bg-[#FFD54F] transition-all cursor-pointer">
            </div>
            <p class="text-xs text-[#D1C4E9] mt-2">Upload images, PDFs, or documents (max 10MB per file)</p>
            @error('attachments.*') <span class="text-red-400 text-sm mt-1 block flex items-center gap-1"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>{{ $message }}</span> @enderror
        </div>

        </div>

        <!-- Action Buttons -->
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 text-sm text-[#D1C4E9]">
                    <flux:icon.information-circle class="size-4" />
                    <span>{{ __('Fill in all required fields to submit your ticket') }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <flux:button as="a" :href="route('tickets.index')" variant="outline" icon="arrow-left">
                        {{ __('Cancel') }}
                    </flux:button>
                    <flux:button type="submit" variant="primary" icon="check">
                        {{ __('Submit Ticket') }}
                    </flux:button>
                </div>
            </div>
        </div>
    </form>
</div>
