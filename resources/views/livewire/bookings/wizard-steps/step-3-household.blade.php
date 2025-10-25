<!-- Step 3: Household Composition -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Number of Adults -->
    <div class="space-y-2">
        <label for="adults" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
            Number of Adults <span class="text-red-500">*</span>
        </label>
        <input type="number" 
               wire:model="adults" 
               id="adults" 
               min="0"
               class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-slate-700 dark:text-white transition-all duration-200"
               placeholder="0">
        @error('adults') 
            <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Has Children -->
    <div class="space-y-2">
        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">
            Do you have children? <span class="text-red-500">*</span>
        </label>
        <div class="flex gap-3">
            @foreach(['Yes', 'No'] as $option)
                <label class="relative flex-1 flex items-center justify-center p-3 border-2 rounded-xl cursor-pointer transition-all duration-200
                             {{ $has_children === $option ? 'border-purple-500 bg-purple-50 dark:bg-purple-900/20' : 'border-slate-300 dark:border-slate-600 hover:border-purple-300 dark:hover:border-purple-700' }}">
                    <input type="radio" 
                           wire:model.live="has_children" 
                           value="{{ $option }}" 
                           class="sr-only">
                    <span class="text-sm font-medium {{ $has_children === $option ? 'text-purple-700 dark:text-purple-300' : 'text-slate-700 dark:text-slate-300' }}">
                        {{ $option }}
                    </span>
                    @if($has_children === $option)
                        <x-flux::icon.check-circle class="w-5 h-5 text-purple-600 dark:text-purple-400 absolute top-2 right-2" />
                    @endif
                </label>
            @endforeach
        </div>
        @error('has_children') 
            <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Children Ages (conditional) -->
    @if($has_children === 'Yes')
        <div class="space-y-2 md:col-span-2">
            <label for="children_ages" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                Children Ages <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   wire:model="children_ages" 
                   id="children_ages" 
                   class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-slate-700 dark:text-white transition-all duration-200"
                   placeholder="e.g., 3, 7, 12">
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                Please list the ages separated by commas
            </p>
            @error('children_ages') 
                <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                    <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                    {{ $message }}
                </div>
            @enderror
        </div>
    @endif

    <!-- Has Elderly -->
    <div class="space-y-2">
        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">
            Any elderly in the home? <span class="text-red-500">*</span>
        </label>
        <div class="flex gap-3">
            @foreach(['Yes', 'No'] as $option)
                <label class="relative flex-1 flex items-center justify-center p-3 border-2 rounded-xl cursor-pointer transition-all duration-200
                             {{ $has_elderly === $option ? 'border-purple-500 bg-purple-50 dark:bg-purple-900/20' : 'border-slate-300 dark:border-slate-600 hover:border-purple-300 dark:hover:border-purple-700' }}">
                    <input type="radio" 
                           wire:model.live="has_elderly" 
                           value="{{ $option }}" 
                           class="sr-only">
                    <span class="text-sm font-medium {{ $has_elderly === $option ? 'text-purple-700 dark:text-purple-300' : 'text-slate-700 dark:text-slate-300' }}">
                        {{ $option }}
                    </span>
                    @if($has_elderly === $option)
                        <x-flux::icon.check-circle class="w-5 h-5 text-purple-600 dark:text-purple-400 absolute top-2 right-2" />
                    @endif
                </label>
            @endforeach
        </div>
        @error('has_elderly') 
            <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Pets -->
    <div class="space-y-2">
        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">
            Do you have pets? <span class="text-red-500">*</span>
        </label>
        <div class="flex gap-3">
            @foreach(['Yes', 'No'] as $option)
                <label class="relative flex-1 flex items-center justify-center p-3 border-2 rounded-xl cursor-pointer transition-all duration-200
                             {{ $pets === $option ? 'border-purple-500 bg-purple-50 dark:bg-purple-900/20' : 'border-slate-300 dark:border-slate-600 hover:border-purple-300 dark:hover:border-purple-700' }}">
                    <input type="radio" 
                           wire:model.live="pets" 
                           value="{{ $option }}" 
                           class="sr-only">
                    <span class="text-sm font-medium {{ $pets === $option ? 'text-purple-700 dark:text-purple-300' : 'text-slate-700 dark:text-slate-300' }}">
                        {{ $option }}
                    </span>
                    @if($pets === $option)
                        <x-flux::icon.check-circle class="w-5 h-5 text-purple-600 dark:text-purple-400 absolute top-2 right-2" />
                    @endif
                </label>
            @endforeach
        </div>
        @error('pets') 
            <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Pet Kind (conditional) -->
    @if($pets === 'Yes')
        <div class="space-y-2 md:col-span-2">
            <label for="pet_kind" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                What kind of pets? <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   wire:model="pet_kind" 
                   id="pet_kind" 
                   class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-slate-700 dark:text-white transition-all duration-200"
                   placeholder="e.g., 2 dogs, 1 cat">
            @error('pet_kind') 
                <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                    <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                    {{ $message }}
                </div>
            @enderror
        </div>
    @endif

    <!-- Language Preference -->
    <div class="space-y-2">
        <label for="language" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
            Preferred Language <span class="text-red-500">*</span>
        </label>
        <select wire:model.live="language" 
                id="language" 
                class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-slate-700 dark:text-white transition-all duration-200">
            <option value="">Select language...</option>
            <option value="English">English</option>
            <option value="Luganda">Luganda</option>
            <option value="Swahili">Swahili</option>
            <option value="Other">Other</option>
        </select>
        @error('language') 
            <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Other Language (conditional) -->
    @if($language === 'Other')
        <div class="space-y-2">
            <label for="language_other" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                Please specify language <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   wire:model="language_other" 
                   id="language_other" 
                   class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-slate-700 dark:text-white transition-all duration-200"
                   placeholder="Enter language">
            @error('language_other') 
                <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                    <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                    {{ $message }}
                </div>
            @enderror
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
                Please fill in all required household composition information before proceeding.
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
