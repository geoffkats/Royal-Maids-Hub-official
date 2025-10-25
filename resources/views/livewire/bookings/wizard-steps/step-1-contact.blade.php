<!-- Step 1: Contact Information -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Full Name -->
    <div class="space-y-2">
        <label for="full_name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
            Full Name <span class="text-red-500">*</span>
        </label>
        <input type="text" 
               wire:model="full_name" 
               id="full_name" 
               class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-white transition-all duration-200 placeholder-slate-400 dark:placeholder-slate-500"
               placeholder="Enter your full name">
        @error('full_name') 
            <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Phone Number -->
    <div class="space-y-2">
        <label for="phone" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
            Phone Number (WhatsApp Preferred) <span class="text-red-500">*</span>
        </label>
        <input type="tel" 
               wire:model="phone" 
               id="phone" 
               class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-white transition-all duration-200 placeholder-slate-400 dark:placeholder-slate-500"
               placeholder="+256-7XX-XXX-XXX">
        @error('phone') 
            <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Email Address -->
    <div class="space-y-2 md:col-span-2">
        <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
            Email Address <span class="text-red-500">*</span>
        </label>
        <input type="email" 
               wire:model="email" 
               id="email" 
               class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-white transition-all duration-200 placeholder-slate-400 dark:placeholder-slate-500"
               placeholder="your.email@example.com">
        @error('email') 
            <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Country -->
    <div class="space-y-2">
        <label for="country" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
            Country <span class="text-red-500">*</span>
        </label>
        <input type="text" 
               wire:model="country" 
               id="country" 
               class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-white transition-all duration-200 placeholder-slate-400 dark:placeholder-slate-500"
               placeholder="Uganda">
        @error('country') 
            <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- City -->
    <div class="space-y-2">
        <label for="city" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
            City <span class="text-red-500">*</span>
        </label>
        <input type="text" 
               wire:model="city" 
               id="city" 
               class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-white transition-all duration-200 placeholder-slate-400 dark:placeholder-slate-500"
               placeholder="e.g., Kampala">
        @error('city') 
            <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Division -->
    <div class="space-y-2">
        <label for="division" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
            Division <span class="text-red-500">*</span>
        </label>
        <input type="text" 
               wire:model="division" 
               id="division" 
               class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-white transition-all duration-200 placeholder-slate-400 dark:placeholder-slate-500"
               placeholder="e.g., Makindye">
        @error('division') 
            <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Parish -->
    <div class="space-y-2">
        <label for="parish" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
            Parish <span class="text-red-500">*</span>
        </label>
        <input type="text" 
               wire:model="parish" 
               id="parish" 
               class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-white transition-all duration-200 placeholder-slate-400 dark:placeholder-slate-500"
               placeholder="e.g., Bukoto">
        @error('parish') 
            <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- National ID/Passport Upload -->
    <div class="space-y-2 md:col-span-2">
        <label for="national_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
            National ID / Passport (Optional)
        </label>
        <div class="flex items-start gap-4">
            <!-- Preview -->
            @if ($national_id)
                <div class="flex-shrink-0">
                    <div class="relative">
                        @if(in_array($national_id->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                            <img src="{{ $national_id->temporaryUrl() }}" 
                                 class="w-32 h-32 object-cover rounded-xl border-2 border-blue-200 dark:border-blue-700 shadow-lg"
                                 alt="ID Preview">
                        @else
                            <div class="w-32 h-32 rounded-xl border-2 border-blue-200 dark:border-blue-700 shadow-lg bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center">
                                <x-flux::icon.document class="w-12 h-12 text-blue-600 dark:text-blue-400" />
                            </div>
                        @endif
                        <button type="button" 
                                wire:click="$set('national_id', null)"
                                class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1.5 shadow-lg transition-colors">
                            <x-flux::icon.x-mark class="w-4 h-4" />
                        </button>
                    </div>
                    <p class="text-xs text-center text-slate-500 dark:text-slate-400 mt-2">
                        {{ number_format($national_id->getSize() / 1024, 2) }} KB
                    </p>
                </div>
            @endif
            
            <!-- Upload Input -->
            <div class="flex-1">
                <input type="file" 
                       wire:model="national_id" 
                       id="national_id" 
                       accept="application/pdf,image/*" 
                       class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-white transition-all duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900/20 dark:file:text-blue-300">
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                    Max file size: 2MB • Formats: PDF, JPG, PNG, GIF, WebP
                </p>
                <div wire:loading wire:target="national_id" class="mt-2">
                    <div class="flex items-center gap-2 text-blue-600 dark:text-blue-400 text-sm">
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Uploading...
                    </div>
                </div>
            </div>
        </div>
        @error('national_id') 
            <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Validation Alert Modal -->
    <flux:modal name="validation-alert" wire:model="showValidationModal" class="max-w-md">
        <div class="space-y-4">
            <flux:heading size="lg" class="text-red-600 dark:text-red-400">
                <x-flux::icon.exclamation-triangle class="w-6 h-6 mr-2" />
                Required Information Missing
            </flux:heading>
            
            <p class="text-slate-700 dark:text-slate-300">
                Please fill in all required contact information before proceeding.
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
