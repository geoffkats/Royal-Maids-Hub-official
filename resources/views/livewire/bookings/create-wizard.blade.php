<div class="space-y-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-slate-900 dark:text-white mb-2">
            @if($isEditing)
                Edit Booking #{{ $booking->id }}
            @else
                Book Your Perfect Maid
            @endif
        </h1>
        <p class="text-lg text-slate-600 dark:text-slate-400">
            @if($isEditing)
                Update your booking details across all 4 sections
            @else
                Complete this 4-step form to find your ideal domestic worker
            @endif
        </p>
        </div>

        <!-- Progress Bar -->
        <div class="mb-8 bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                    Step {{ $currentStep }} of {{ $totalSteps }}
                </span>
                <span class="text-sm font-semibold text-indigo-600 dark:text-indigo-400">
                    {{ $this->progress_percentage }}% Complete
                </span>
            </div>
            
            <!-- Progress Bar -->
            <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-3 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 h-3 rounded-full transition-all duration-500 ease-out"
                     style="width: {{ $this->progress_percentage }}%"></div>
            </div>

            <!-- Step Indicators -->
            <div class="flex justify-between mt-6">
                <button type="button" 
                        wire:click="goToStep(1)"
                        class="flex flex-col items-center {{ $currentStep >= 1 ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-600' }}">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center mb-2 transition-all duration-200
                                {{ $currentStep > 1 ? 'bg-green-500 text-white' : ($currentStep === 1 ? 'bg-indigo-600 text-white' : 'bg-slate-200 dark:bg-slate-700') }}">
                        @if($currentStep > 1)
                            <x-flux::icon.check class="w-5 h-5" />
                        @else
                            <x-flux::icon.user class="w-5 h-5" />
                        @endif
                    </div>
                    <span class="text-xs font-medium text-center hidden sm:block">Contact</span>
                </button>

                <button type="button" 
                        wire:click="goToStep(2)"
                        class="flex flex-col items-center {{ $currentStep >= 2 ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-600' }}">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center mb-2 transition-all duration-200
                                {{ $currentStep > 2 ? 'bg-green-500 text-white' : ($currentStep === 2 ? 'bg-indigo-600 text-white' : 'bg-slate-200 dark:bg-slate-700') }}">
                        @if($currentStep > 2)
                            <x-flux::icon.check class="w-5 h-5" />
                        @else
                            <x-flux::icon.home class="w-5 h-5" />
                        @endif
                    </div>
                    <span class="text-xs font-medium text-center hidden sm:block">Home</span>
                </button>

                <button type="button" 
                        wire:click="goToStep(3)"
                        class="flex flex-col items-center {{ $currentStep >= 3 ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-600' }}">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center mb-2 transition-all duration-200
                                {{ $currentStep > 3 ? 'bg-green-500 text-white' : ($currentStep === 3 ? 'bg-indigo-600 text-white' : 'bg-slate-200 dark:bg-slate-700') }}">
                        @if($currentStep > 3)
                            <x-flux::icon.check class="w-5 h-5" />
                        @else
                            <x-flux::icon.user-group class="w-5 h-5" />
                        @endif
                    </div>
                    <span class="text-xs font-medium text-center hidden sm:block">Household</span>
                </button>

                <button type="button" 
                        wire:click="goToStep(4)"
                        class="flex flex-col items-center {{ $currentStep >= 4 ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-600' }}">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center mb-2 transition-all duration-200
                                {{ $currentStep === 4 ? 'bg-indigo-600 text-white' : 'bg-slate-200 dark:bg-slate-700' }}">
                        <x-flux::icon.briefcase class="w-5 h-5" />
                    </div>
                    <span class="text-xs font-medium text-center hidden sm:block">Job Role</span>
                </button>
            </div>
        </div>

        <!-- Success Message -->
        @if (session()->has('success'))
            <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-200 px-6 py-4 rounded-xl shadow-sm">
                <div class="flex items-center gap-3">
                    <x-flux::icon.check-circle class="w-5 h-5 text-green-600 dark:text-green-400" />
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <!-- Wizard Form -->
        <form wire:submit.prevent="submit">
            
            <!-- Step 1: Contact Information -->
            @if($currentStep === 1)
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden animate-fade-in">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-white flex items-center gap-3">
                            <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                                <x-flux::icon.user class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                            </div>
                            Your Contact Information
                        </h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Tell us how to reach you</p>
                    </div>
                    <div class="p-6">
                        @include('livewire.bookings.wizard-steps.step-1-contact')
                    </div>
                </div>
            @endif

            <!-- Step 2: Home & Environment -->
            @if($currentStep === 2)
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden animate-fade-in">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-white flex items-center gap-3">
                            <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                                <x-flux::icon.home class="w-5 h-5 text-green-600 dark:text-green-400" />
                            </div>
                            About Your Home & Environment
                        </h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Help us understand your living space</p>
                    </div>
                    <div class="p-6">
                        @include('livewire.bookings.wizard-steps.step-2-home')
                    </div>
                </div>
            @endif

            <!-- Step 3: Household Composition -->
            @if($currentStep === 3)
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden animate-fade-in">
                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-white flex items-center gap-3">
                            <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                                <x-flux::icon.user-group class="w-5 h-5 text-purple-600 dark:text-purple-400" />
                            </div>
                            Your Household Composition
                        </h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Tell us about your family</p>
                    </div>
                    <div class="p-6">
                        @include('livewire.bookings.wizard-steps.step-3-household')
                    </div>
                </div>
            @endif

            <!-- Step 4: Job Role & Expectations -->
            @if($currentStep === 4)
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden animate-fade-in">
                    <div class="bg-gradient-to-r from-orange-50 to-amber-50 dark:from-orange-900/20 dark:to-amber-900/20 px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-white flex items-center gap-3">
                            <div class="p-2 bg-orange-100 dark:bg-orange-900/30 rounded-lg">
                                <x-flux::icon.briefcase class="w-5 h-5 text-orange-600 dark:text-orange-400" />
                            </div>
                            The Job Role & Your Expectations
                        </h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Define the work requirements</p>
                    </div>
                    <div class="p-6">
                        @include('livewire.bookings.wizard-steps.step-4-expectations')
                    </div>
                </div>
            @endif

            <!-- Navigation Buttons -->
            <div class="mt-8 bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                <div class="flex flex-col sm:flex-row gap-4 justify-between">
                    <!-- Back Button -->
                    @if($currentStep > 1)
                        <button type="button" 
                                wire:click="previousStep"
                                class="inline-flex items-center justify-center px-6 py-3 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 font-semibold rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                            <x-flux::icon.arrow-left class="w-5 h-5 mr-2" />
                            Back
                        </button>
                    @else
                        <div></div>
                    @endif

                    <!-- Next/Submit Button -->
                    <div class="flex gap-3">
                        @if($currentStep < $totalSteps)
                            <button type="button" 
                                    wire:click="nextStep"
                                    wire:loading.attr="disabled"
                                    wire:target="nextStep"
                                    class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span wire:loading.remove wire:target="nextStep">Next Step</span>
                                <span wire:loading wire:target="nextStep">Validating...</span>
                                <x-flux::icon.arrow-right wire:loading.remove wire:target="nextStep" class="w-5 h-5 ml-2" />
                                <!-- Loading spinner for Next button -->
                                <svg wire:loading wire:target="nextStep" class="animate-spin ml-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </button>
                        @else
                            <button type="submit"
                                    wire:loading.attr="disabled"
                                    wire:target="submit"
                                    class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                                <!-- Loading Spinner -->
                                <svg wire:loading wire:target="submit" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                
                                <!-- Check Icon (hidden when loading) -->
                                <x-flux::icon.check wire:loading.remove wire:target="submit" class="w-5 h-5 mr-2" />
                                
                                <!-- Button Text -->
                                <span wire:loading.remove wire:target="submit">
                                    @if($isEditing)
                                        Update Booking
                                    @else
                                        Submit Booking Request
                                    @endif
                                </span>
                                <span wire:loading wire:target="submit">
                                    @if($isEditing)
                                        Updating...
                                    @else
                                        Creating Booking...
                                    @endif
                                </span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </form>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }
    </style>

    <!-- Validation Alert Modal -->
    <flux:modal name="validation-alert" wire:model="showValidationModal" class="max-w-md">
        <div class="space-y-4">
            <flux:heading size="lg" class="text-red-600 dark:text-red-400">
                <x-flux::icon.exclamation-triangle class="w-6 h-6 mr-2" />
                Required Information Missing
            </flux:heading>
            
            <p class="text-slate-700 dark:text-slate-300">
                Please fill in all required fields before proceeding to the next step.
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
