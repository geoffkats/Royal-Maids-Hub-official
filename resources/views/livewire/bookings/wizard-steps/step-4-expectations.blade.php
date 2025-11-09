<!-- Step 4: Job Expectations -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Service Tier -->
    <div class="space-y-2 md:col-span-2">
        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">
            Select Service Tier <span class="text-red-500">*</span>
        </label>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach(['Silver' => '300,000 UGX/month', 'Gold' => '500,000 UGX/month', 'Platinum' => '800,000 UGX/month'] as $tier => $price)
                <label class="relative flex flex-col p-5 border-2 rounded-xl cursor-pointer transition-all duration-200
                             {{ $service_tier === $tier ? 'border-amber-500 bg-amber-50 dark:bg-amber-900/20 shadow-lg' : 'border-slate-300 dark:border-slate-600 hover:border-amber-300 dark:hover:border-amber-700' }}">
                    <input type="radio" 
                           wire:model.live="service_tier" 
                           value="{{ $tier }}" 
                           class="sr-only">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-lg font-bold {{ $service_tier === $tier ? 'text-amber-700 dark:text-amber-300' : 'text-slate-700 dark:text-slate-300' }}">
                            {{ $tier }}
                        </span>
                        @if($service_tier === $tier)
                            <x-flux::icon.check-circle class="w-6 h-6 text-amber-600 dark:text-amber-400" />
                        @endif
                    </div>
                    <span class="text-sm text-slate-600 dark:text-slate-400">
                        {{ $price }}
                    </span>
                </label>
            @endforeach
        </div>
        @error('service_tier') 
            <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Service Mode -->
    <div class="space-y-2 md:col-span-2">
        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">
            Service Mode <span class="text-red-500">*</span>
        </label>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            @foreach(['Live-in', 'Live-out'] as $mode)
                <label class="relative flex items-center justify-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-200
                             {{ $service_mode === $mode ? 'border-amber-500 bg-amber-50 dark:bg-amber-900/20' : 'border-slate-300 dark:border-slate-600 hover:border-amber-300 dark:hover:border-amber-700' }}">
                    <input type="radio" 
                           wire:model.live="service_mode" 
                           value="{{ $mode }}" 
                           class="sr-only">
                    <span class="text-sm font-medium {{ $service_mode === $mode ? 'text-amber-700 dark:text-amber-300' : 'text-slate-700 dark:text-slate-300' }}">
                        {{ $mode }}
                    </span>
                    @if($service_mode === $mode)
                        <x-flux::icon.check-circle class="w-5 h-5 text-amber-600 dark:text-amber-400 absolute top-2 right-2" />
                    @endif
                </label>
            @endforeach
        </div>
        @error('service_mode') 
            <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Work Days -->
    <div class="space-y-2 md:col-span-2">
        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">
            Work Days <span class="text-red-500">*</span>
        </label>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday', 'All Days'] as $day)
                <label class="relative flex items-center p-3 border-2 rounded-xl cursor-pointer transition-all duration-200
                             {{ in_array($day, $work_days) ? 'border-amber-500 bg-amber-50 dark:bg-amber-900/20' : 'border-slate-300 dark:border-slate-600 hover:border-amber-300 dark:hover:border-amber-700' }}">
                    <input type="checkbox" 
                           wire:model.live="work_days" 
                           value="{{ $day }}" 
                           class="sr-only">
                    <div class="flex items-center gap-2 w-full">
                        <div class="w-5 h-5 rounded border-2 flex items-center justify-center transition-all
                                  {{ in_array($day, $work_days) ? 'border-amber-600 bg-amber-600' : 'border-slate-400 dark:border-slate-500' }}">
                            @if(in_array($day, $work_days))
                                <x-flux::icon.check class="w-3 h-3 text-white" />
                            @endif
                        </div>
                        <span class="text-sm font-medium {{ in_array($day, $work_days) ? 'text-amber-700 dark:text-amber-300' : 'text-slate-700 dark:text-slate-300' }}">
                            {{ $day }}
                        </span>
                    </div>
                </label>
            @endforeach
        </div>
        @error('work_days') 
            <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Working Hours -->
    <div class="space-y-2 md:col-span-2">
        <label for="working_hours" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
            Working Hours <span class="text-red-500">*</span>
        </label>
        <input type="text" 
               wire:model="working_hours" 
               id="working_hours" 
               class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 dark:bg-slate-700 dark:text-white transition-all duration-200"
               placeholder="e.g., 8:00 AM - 5:00 PM">
        @error('working_hours') 
            <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Responsibilities -->
    <div class="space-y-2 md:col-span-2">
        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">
            Responsibilities <span class="text-red-500">*</span>
        </label>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            @foreach(['Cleaning', 'Cooking', 'Babysitting', 'Laundry', 'Shopping', 'Other'] as $responsibility)
                <label class="relative flex items-center p-3 border-2 rounded-xl cursor-pointer transition-all duration-200
                             {{ in_array($responsibility, $responsibilities) ? 'border-amber-500 bg-amber-50 dark:bg-amber-900/20' : 'border-slate-300 dark:border-slate-600 hover:border-amber-300 dark:hover:border-amber-700' }}">
                    <input type="checkbox" 
                           wire:model.live="responsibilities" 
                           value="{{ $responsibility }}" 
                           class="sr-only">
                    <div class="flex items-center gap-2 w-full">
                        <div class="w-5 h-5 rounded border-2 flex items-center justify-center transition-all
                                  {{ in_array($responsibility, $responsibilities) ? 'border-amber-600 bg-amber-600' : 'border-slate-400 dark:border-slate-500' }}">
                            @if(in_array($responsibility, $responsibilities))
                                <x-flux::icon.check class="w-3 h-3 text-white" />
                            @endif
                        </div>
                        <span class="text-sm font-medium {{ in_array($responsibility, $responsibilities) ? 'text-amber-700 dark:text-amber-300' : 'text-slate-700 dark:text-slate-300' }}">
                            {{ $responsibility }}
                        </span>
                    </div>
                </label>
            @endforeach
        </div>
        @error('responsibilities') 
            <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Home Atmosphere -->
    <div class="space-y-2">
        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">
            Home Atmosphere <span class="text-red-500">*</span>
        </label>
        <div class="grid grid-cols-1 gap-3">
            @foreach(['Quiet', 'Active', 'Balanced'] as $atmos)
                <label class="relative flex items-center justify-center p-3 border-2 rounded-xl cursor-pointer transition-all duration-200
                             {{ $atmosphere === $atmos ? 'border-amber-500 bg-amber-50 dark:bg-amber-900/20' : 'border-slate-300 dark:border-slate-600 hover:border-amber-300 dark:hover:border-amber-700' }}">
                    <input type="radio" 
                           wire:model.live="atmosphere" 
                           value="{{ $atmos }}" 
                           class="sr-only">
                    <span class="text-sm font-medium {{ $atmosphere === $atmos ? 'text-amber-700 dark:text-amber-300' : 'text-slate-700 dark:text-slate-300' }}">
                        {{ $atmos }}
                    </span>
                    @if($atmosphere === $atmos)
                        <x-flux::icon.check-circle class="w-5 h-5 text-amber-600 dark:text-amber-400 absolute top-2 right-2" />
                    @endif
                </label>
            @endforeach
        </div>
        @error('atmosphere') 
            <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                {{ $message }}
            </div>
        @enderror
    </div>

    

    <!-- Unspoken Rules -->
    <div class="space-y-2 md:col-span-2">
        <label for="unspoken_rules" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
            Unspoken Rules / Cultural Expectations
        </label>
        <textarea wire:model="unspoken_rules" 
                  id="unspoken_rules" 
                  rows="3"
                  class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 dark:bg-slate-700 dark:text-white transition-all duration-200"
                  placeholder="Any house rules or expectations the maid should know..."></textarea>
        @error('unspoken_rules') 
            <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Additional Notes -->
    <div class="space-y-2 md:col-span-2">
        <label for="anything_else" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
            Anything Else We Should Know?
        </label>
        <textarea wire:model="anything_else" 
                  id="anything_else" 
                  rows="3"
                  class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 dark:bg-slate-700 dark:text-white transition-all duration-200"
                  placeholder="Any additional information or special requirements..."></textarea>
        @error('anything_else') 
            <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Start Date -->
    <div class="space-y-2">
        <label for="start_date" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
            Preferred Start Date <span class="text-red-500">*</span>
        </label>
        <input type="date" 
               wire:model="start_date" 
               id="start_date" 
               class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 dark:bg-slate-700 dark:text-white transition-all duration-200">
        @error('start_date') 
            <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- End Date -->
    <div class="space-y-2">
        <label for="end_date" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
            Preferred End Date
        </label>
        <input type="date" 
               wire:model="end_date" 
               id="end_date" 
               class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 dark:bg-slate-700 dark:text-white transition-all duration-200">
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
            Leave blank for ongoing service
        </p>
        @error('end_date') 
            <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                {{ $message }}
            </div>
        @enderror
    </div>

    @if($isEditing && auth()->user()->role === 'admin')
        {{-- Admin Section (Only visible when editing) --}}
        <div class="md:col-span-2 mt-8 pt-8 border-t-4 border-slate-300 dark:border-slate-600">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                <x-flux::icon.cog-6-tooth class="w-6 h-6 text-slate-600 dark:text-slate-400" />
                Admin Management
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Maid Assignment -->
                <div class="space-y-2">
                    <label for="maid_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Assign Maid
                    </label>
                    <select wire:model="maid_id" 
                            id="maid_id"
                            class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 dark:bg-slate-700 dark:text-white transition-all duration-200">
                        <option value="">No maid assigned</option>
                        @foreach($maids as $maid)
                            <option value="{{ $maid->id }}">
                                {{ $maid->full_name }} - {{ ucfirst($maid->status) }}
                            </option>
                        @endforeach
                    </select>
                    @error('maid_id') 
                        <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                            <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Booking Status -->
                <div class="space-y-2">
                    <label for="status" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Booking Status <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="status" 
                            id="status"
                            class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 dark:bg-slate-700 dark:text-white transition-all duration-200">
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="active">Active</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                    @error('status') 
                        <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                            <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Admin Notes -->
                <div class="space-y-2 md:col-span-2">
                    <label for="notes" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Admin Notes
                    </label>
                    <textarea wire:model="notes" 
                              id="notes" 
                              rows="3"
                              class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 dark:bg-slate-700 dark:text-white transition-all duration-200"
                              placeholder="Internal notes about this booking..."></textarea>
                    @error('notes') 
                        <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                            <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>
    @endif

    <!-- Validation Alert Modal -->
    <flux:modal name="validation-alert" wire:model="showValidationModal" class="max-w-md">
        <div class="space-y-4">
            <flux:heading size="lg" class="text-red-600 dark:text-red-400">
                <x-flux::icon.exclamation-triangle class="w-6 h-6 mr-2" />
                Required Information Missing
            </flux:heading>
            
            <p class="text-slate-700 dark:text-slate-300">
                Please fill in all required job expectations and preferences before proceeding.
            </p>
            
            @if($errors->any())
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    <h4 class="font-semibold text-red-800 dark:text-red-200 mb-2">Missing Required Fields:</h4>
                    <ul class="text-sm text-red-700 dark:text-red-300 space-y-1">
                        @foreach($errors->all() as $error)
                            <li class="flex items-center">
                                <x-flux::icon.x-mark class="w-4 h-4 mr-2 flex-shrink-0" />
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <div class="flex items-center justify-end gap-3">
                <flux:button variant="primary" wire:click="closeValidationModal">
                    I Understand
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
