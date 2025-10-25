<!-- Step 2: Home & Environment -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Home Type -->
    <div class="space-y-2 md:col-span-2">
        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">
            Home Type <span class="text-red-500">*</span>
        </label>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @foreach(['Apartment', 'Bungalow', 'Maisonette', 'Other'] as $type)
                <label class="relative flex items-center justify-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-200
                             {{ $home_type === $type ? 'border-green-500 bg-green-50 dark:bg-green-900/20' : 'border-slate-300 dark:border-slate-600 hover:border-green-300 dark:hover:border-green-700' }}">
                    <input type="radio" 
                           wire:model.live="home_type" 
                           value="{{ $type }}" 
                           class="sr-only">
                    <span class="text-sm font-medium {{ $home_type === $type ? 'text-green-700 dark:text-green-300' : 'text-slate-700 dark:text-slate-300' }}">
                        {{ $type }}
                    </span>
                    @if($home_type === $type)
                        <x-flux::icon.check-circle class="w-5 h-5 text-green-600 dark:text-green-400 absolute top-2 right-2" />
                    @endif
                </label>
            @endforeach
        </div>
        @error('home_type') 
            <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Number of Bedrooms -->
    <div class="space-y-2">
        <label for="bedrooms" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
            Number of Bedrooms <span class="text-red-500">*</span>
        </label>
        <input type="number" 
               wire:model="bedrooms" 
               id="bedrooms" 
               min="0"
               class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-slate-700 dark:text-white transition-all duration-200"
               placeholder="0">
        @error('bedrooms') 
            <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Number of Bathrooms -->
    <div class="space-y-2">
        <label for="bathrooms" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
            Number of Bathrooms <span class="text-red-500">*</span>
        </label>
        <input type="number" 
               wire:model="bathrooms" 
               id="bathrooms" 
               min="0"
               class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-slate-700 dark:text-white transition-all duration-200"
               placeholder="0">
        @error('bathrooms') 
            <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Outdoor Responsibilities -->
    <div class="space-y-2 md:col-span-2">
        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">
            Outdoor Responsibilities
        </label>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            @foreach(['Sweeping', 'Gardening', 'None'] as $responsibility)
                <label class="relative flex items-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-200
                             {{ in_array($responsibility, $outdoor_responsibilities) ? 'border-green-500 bg-green-50 dark:bg-green-900/20' : 'border-slate-300 dark:border-slate-600 hover:border-green-300 dark:hover:border-green-700' }}">
                    <input type="checkbox" 
                           wire:model.live="outdoor_responsibilities" 
                           value="{{ $responsibility }}" 
                           class="sr-only">
                    <div class="flex items-center gap-3 w-full">
                        <div class="w-5 h-5 rounded border-2 flex items-center justify-center transition-all
                                  {{ in_array($responsibility, $outdoor_responsibilities) ? 'border-green-600 bg-green-600' : 'border-slate-400 dark:border-slate-500' }}">
                            @if(in_array($responsibility, $outdoor_responsibilities))
                                <x-flux::icon.check class="w-3 h-3 text-white" />
                            @endif
                        </div>
                        <span class="text-sm font-medium {{ in_array($responsibility, $outdoor_responsibilities) ? 'text-green-700 dark:text-green-300' : 'text-slate-700 dark:text-slate-300' }}">
                            {{ $responsibility }}
                        </span>
                    </div>
                </label>
            @endforeach
        </div>
        @error('outdoor_responsibilities') 
            <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                {{ $message }}
            </div>
        @enderror
    </div>

    <!-- Appliances Available -->
    <div class="space-y-2 md:col-span-2">
        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">
            Appliances Available in Your Home
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
            @foreach(['Washing Machine', 'Microwave', 'Oven', 'Blender', 'Airfryer', 'Generator'] as $appliance)
                <label class="relative flex items-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-200
                             {{ in_array($appliance, $appliances) ? 'border-green-500 bg-green-50 dark:bg-green-900/20' : 'border-slate-300 dark:border-slate-600 hover:border-green-300 dark:hover:border-green-700' }}">
                    <input type="checkbox" 
                           wire:model.live="appliances" 
                           value="{{ $appliance }}" 
                           class="sr-only">
                    <div class="flex items-center gap-3 w-full">
                        <div class="w-5 h-5 rounded border-2 flex items-center justify-center transition-all
                                  {{ in_array($appliance, $appliances) ? 'border-green-600 bg-green-600' : 'border-slate-400 dark:border-slate-500' }}">
                            @if(in_array($appliance, $appliances))
                                <x-flux::icon.check class="w-3 h-3 text-white" />
                            @endif
                        </div>
                        <span class="text-sm font-medium {{ in_array($appliance, $appliances) ? 'text-green-700 dark:text-green-300' : 'text-slate-700 dark:text-slate-300' }}">
                            {{ $appliance }}
                        </span>
                    </div>
                </label>
            @endforeach
        </div>
        @error('appliances') 
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
                Please fill in all required home and environment information before proceeding.
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
