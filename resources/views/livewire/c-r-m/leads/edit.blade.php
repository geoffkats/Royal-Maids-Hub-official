<div class="space-y-6">
    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
            <div class="flex items-center gap-2 text-green-700 dark:text-green-300">
                <x-flux::icon.check-circle class="w-5 h-5" />
                <span class="font-medium">{{ session('message') }}</span>
            </div>
        </div>
    @endif

    <!-- Header -->
    <div class="bg-[#512B58] border border-[#F5B301]/30 rounded-xl p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold flex items-center gap-3">
                    <x-flux::icon.pencil class="w-8 h-8" />
                    {{ __('Edit Lead') }}
                </h1>
                <p class="text-indigo-100 mt-2">
                    {{ __('Update lead information') }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('crm.leads.show', $lead) }}"
                   class="inline-flex items-center gap-2 px-5 py-3 bg-white/20 backdrop-blur-sm border border-white/30 rounded-lg font-semibold text-white hover:bg-white/30 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <x-flux::icon.arrow-left class="w-5 h-5" />
                    {{ __('Back to Lead') }}
                </a>
            </div>
        </div>
    </div>

    <form wire:submit="update" class="space-y-6">
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
                        <option value="">Select package...</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}">{{ $package->name }}</option>
                        @endforeach
                    </flux:select>
                    <flux:error name="interested_package_id" />
                </flux:field>
            </div>

            {{-- Notes --}}
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm md:col-span-2">
                <flux:heading size="lg" class="mb-4">Notes</flux:heading>
                
                <flux:field>
                    <flux:label>Additional Notes</flux:label>
                    <flux:textarea wire:model="notes" rows="4" placeholder="Add any additional information about this lead..." />
                    <flux:error name="notes" />
                </flux:field>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <a href="{{ route('crm.leads.show', $lead) }}"
                   class="inline-flex items-center gap-2 px-5 py-3 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-lg font-semibold hover:bg-slate-200 dark:hover:bg-slate-600 transition-all duration-200">
                    <x-flux::icon.x-mark class="w-5 h-5" />
                    {{ __('Cancel') }}
                </a>
                
                <button type="submit"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-[#512B58] hover:bg-[#3B0A45] text-white rounded-lg font-semibold transition-all duration-200 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed">
                    <x-flux::icon.check class="w-5 h-5" wire:loading.remove />
                    <svg wire:loading class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove>{{ __('Update Lead') }}</span>
                    <span wire:loading>{{ __('Updating...') }}</span>
                </button>
            </div>
        </div>
    </form>
</div>
