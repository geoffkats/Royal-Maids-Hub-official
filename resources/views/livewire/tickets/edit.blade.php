<div class="space-y-6">
    @php
        $prefix = auth()->user()->role === 'trainer' ? 'trainer.' : '';
    @endphp
    <!-- Header Section -->
    <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
        <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
            <div class="flex items-start gap-4">
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-[#F5B301] to-[#FFD700] text-2xl font-bold text-[#3B0A45] shadow-lg">
                    <flux:icon.ticket class="h-8 w-8" />
                </div>
                <div>
                    <flux:heading size="xl" class="text-white">Edit Ticket</flux:heading>
                    <flux:subheading class="text-[#D1C4E9]">{{ $ticket->ticket_number }}</flux:subheading>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <flux:button as="a" :href="route($prefix . 'tickets.show', $ticket)" variant="outline" icon="eye">
                    {{ __('View Ticket') }}
                </flux:button>
                <flux:button as="a" :href="route($prefix . 'tickets.index')" variant="outline" icon="arrow-left">
                    {{ __('Back to Tickets') }}
                </flux:button>
            </div>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="rounded-lg border border-[#4CAF50]/30 bg-[#4CAF50]/10 p-4">
            <div class="flex items-center gap-2 text-[#4CAF50]">
                <flux:icon.check-circle class="h-5 w-5" />
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <form wire:submit.prevent="update" enctype="multipart/form-data" class="space-y-8">
        <!-- Ticket Information Section -->
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <flux:heading size="lg" class="text-white mb-4">Ticket Information</flux:heading>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <flux:input 
                        wire:model="subject" 
                        label="Subject" 
                        placeholder="Enter ticket subject"
                        class="bg-[#3B0A45] border-[#F5B301]/30 text-white"
                    />
                    @error('subject') <flux:error>{{ $message }}</flux:error> @enderror
                </div>

                <div>
                    <flux:select wire:model="type" label="Ticket Type">
                        <option value="client_issue">Client Issue</option>
                        <option value="maid_support">Maid Support</option>
                        <option value="deployment_issue">Deployment Issue</option>
                        <option value="billing">Billing</option>
                        <option value="training">Training</option>
                        <option value="operations">Operations</option>
                        <option value="general">General</option>
                    </flux:select>
                    @error('type') <flux:error>{{ $message }}</flux:error> @enderror
                </div>

                <div>
                    <flux:select wire:model="category" label="Category">
                        <option value="">Select a category...</option>
                        
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
                    </flux:select>
                    @error('category') <flux:error>{{ $message }}</flux:error> @enderror
                </div>

                <div>
                    <flux:input 
                        wire:model="subcategory" 
                        label="Subcategory" 
                        placeholder="e.g., Cleaning Quality, Late Payment"
                        class="bg-[#3B0A45] border-[#F5B301]/30 text-white"
                    />
                    @error('subcategory') <flux:error>{{ $message }}</flux:error> @enderror
                </div>

                <div>
                    <flux:select wire:model="priority" label="Priority">
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                        <option value="critical">Critical</option>
                    </flux:select>
                    @error('priority') <flux:error>{{ $message }}</flux:error> @enderror
                </div>

                <div>
                    <flux:select wire:model="status" label="Status">
                        <option value="open">Open</option>
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="on_hold">On Hold</option>
                        <option value="resolved">Resolved</option>
                        <option value="closed">Closed</option>
                        <option value="cancelled">Cancelled</option>
                    </flux:select>
                    @error('status') <flux:error>{{ $message }}</flux:error> @enderror
                </div>
            </div>

            <div class="mt-6">
                <flux:textarea 
                    wire:model="description" 
                    label="Description" 
                    placeholder="Describe the issue in detail..."
                    rows="4"
                    class="bg-[#3B0A45] border-[#F5B301]/30 text-white"
                />
                @error('description') <flux:error>{{ $message }}</flux:error> @enderror
            </div>
        </div>

        <!-- Assignment & Department Section -->
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <flux:heading size="lg" class="text-white mb-4">Assignment & Department</flux:heading>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <flux:select wire:model="assigned_to" label="Assign to Staff">
                        <option value="">Unassigned</option>
                        @foreach($admins as $admin)
                            <option value="{{ $admin->id }}">{{ $admin->name }} ({{ ucfirst($admin->role) }})</option>
                        @endforeach
                    </flux:select>
                    @error('assigned_to') <flux:error>{{ $message }}</flux:error> @enderror
                </div>

                <div>
                    <flux:select wire:model="department" label="Department">
                        <option value="">Select Department</option>
                        <option value="customer_service">Customer Service</option>
                        <option value="operations">Operations</option>
                        <option value="finance">Finance</option>
                        <option value="hr">Human Resources</option>
                        <option value="training">Training</option>
                        <option value="technical">Technical</option>
                    </flux:select>
                    @error('department') <flux:error>{{ $message }}</flux:error> @enderror
                </div>

                <div>
                    <flux:input 
                        wire:model="due_date" 
                        type="date"
                        label="Due Date" 
                        class="bg-[#3B0A45] border-[#F5B301]/30 text-white"
                    />
                    @error('due_date') <flux:error>{{ $message }}</flux:error> @enderror
                </div>
            </div>
        </div>

        <!-- Related Entities Section -->
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <flux:heading size="lg" class="text-white mb-4">Related Entities</flux:heading>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <flux:select wire:model="related_client_id" label="Related Client">
                        <option value="">No Client</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->contact_person }}</option>
                        @endforeach
                    </flux:select>
                    @error('related_client_id') <flux:error>{{ $message }}</flux:error> @enderror
                </div>

                <div>
                    <flux:select wire:model="related_maid_id" label="Related Maid">
                        <option value="">No Maid</option>
                        @foreach($maids as $maid)
                            <option value="{{ $maid->id }}">{{ $maid->first_name }} {{ $maid->last_name }}</option>
                        @endforeach
                    </flux:select>
                    @error('related_maid_id') <flux:error>{{ $message }}</flux:error> @enderror
                </div>

                <div>
                    <flux:select wire:model="related_booking_id" label="Related Booking">
                        <option value="">No Booking</option>
                        @foreach($bookings as $booking)
                            <option value="{{ $booking->id }}">
                                #{{ $booking->id }} - {{ $booking->client->contact_person ?? 'Unknown Client' }}
                            </option>
                        @endforeach
                    </flux:select>
                    @error('related_booking_id') <flux:error>{{ $message }}</flux:error> @enderror
                </div>
            </div>
        </div>

        <!-- Location Section -->
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <flux:heading size="lg" class="text-white mb-4">Location Information</flux:heading>
            
            <div>
                <flux:textarea 
                    wire:model="location_address" 
                    label="Location Address" 
                    placeholder="Enter the location address if applicable..."
                    rows="2"
                    class="bg-[#3B0A45] border-[#F5B301]/30 text-white"
                />
                @error('location_address') <flux:error>{{ $message }}</flux:error> @enderror
            </div>
        </div>

        <!-- Resolution Notes Section -->
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <flux:heading size="lg" class="text-white mb-4">Resolution Information</flux:heading>
            
            <div>
                <flux:textarea 
                    wire:model="resolution_notes" 
                    label="Resolution Notes" 
                    placeholder="Enter resolution details if the ticket is resolved..."
                    rows="3"
                    class="bg-[#3B0A45] border-[#F5B301]/30 text-white"
                />
                @error('resolution_notes') <flux:error>{{ $message }}</flux:error> @enderror
            </div>
        </div>

        <!-- Attachments Section -->
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <flux:heading size="lg" class="text-white mb-4">Attachments</flux:heading>
            
            <div>
                <input 
                    type="file" 
                    wire:model="attachments" 
                    multiple 
                    class="w-full rounded border border-[#F5B301]/30 bg-[#3B0A45] text-white p-3"
                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif,.txt"
                />
                <p class="text-[#D1C4E9] text-sm mt-2">You can upload multiple files. Maximum file size: 10MB per file.</p>
                @error('attachments.*') <flux:error>{{ $message }}</flux:error> @enderror
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end gap-4">
            <flux:button as="a" :href="route($prefix . 'tickets.show', $ticket)" variant="outline">
                {{ __('Cancel') }}
            </flux:button>
            <flux:button type="submit" variant="primary">
                {{ __('Update Ticket') }}
            </flux:button>
        </div>
    </form>
</div>