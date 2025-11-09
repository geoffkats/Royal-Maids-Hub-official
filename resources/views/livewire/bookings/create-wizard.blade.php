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
                Update your booking details
            @else
                Complete this 4-step wizard to create a booking request
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
        
        <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-3 overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 h-3 rounded-full transition-all duration-500 ease-out"
                 style="width: {{ $this->progress_percentage }}%"></div>
        </div>
        
        <!-- Step Indicators -->
        <div class="flex justify-between mt-4">
            @for($i = 1; $i <= $totalSteps; $i++)
                <button type="button" 
                        wire:click="goToStep({{ $i }})"
                        class="flex items-center justify-center w-8 h-8 rounded-full text-sm font-semibold transition-all duration-200
                               {{ $i <= $currentStep ? 'bg-indigo-600 text-white' : 'bg-slate-200 dark:bg-slate-600 text-slate-600 dark:text-slate-400' }}
                               {{ $i <= $currentStep ? 'hover:bg-indigo-700' : 'hover:bg-slate-300 dark:hover:bg-slate-500' }}">
                    @if($i < $currentStep)
                        <x-flux::icon.check class="w-4 h-4" />
                    @else
                        {{ $i }}
                    @endif
                </button>
            @endfor
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

    <!-- Error Display -->
    @if(isset($error))
        <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 text-red-800 dark:text-red-200 px-6 py-4 rounded-xl shadow-sm">
            <div class="flex items-center gap-3">
                <x-flux::icon.exclamation-triangle class="w-5 h-5 text-red-600 dark:text-red-400" />
                <span class="font-medium">Error: {{ $error }}</span>
            </div>
        </div>
    @endif

    <!-- Wizard Form -->
    <form wire:submit.prevent="submit">
        <!-- Step 1: Contact Information -->
        @if($currentStep === 1)
            @include('livewire.bookings.wizard-steps.step-1-contact')
        @endif

        <!-- Step 2: Home & Environment -->
        @if($currentStep === 2)
            @include('livewire.bookings.wizard-steps.step-2-home')
        @endif

        <!-- Step 3: Household Composition -->
        @if($currentStep === 3)
            @include('livewire.bookings.wizard-steps.step-3-household')
        @endif

        <!-- Step 4: Job Role & Expectations -->
        @if($currentStep === 4)
            @include('livewire.bookings.wizard-steps.step-4-expectations')
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
                                class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            Next Step
                            <x-flux::icon.arrow-right wire:loading.remove wire:target="nextStep" class="w-5 h-5 ml-2" />
                            <x-flux::icon.arrow-path wire:loading wire:target="nextStep" class="w-5 h-5 ml-2 animate-spin" />
                        </button>
                    @else
                        <button type="submit"
                                class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <x-flux::icon.check wire:loading.remove wire:target="submit" class="w-5 h-5 mr-2" />
                            <x-flux::icon.arrow-path wire:loading wire:target="submit" class="w-5 h-5 mr-2 animate-spin" />
                            @if($isEditing)
                                Update Booking
                            @else
                                Submit Booking Request
                            @endif
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </form>

    <!-- Validation Alert Modal -->
    <flux:modal name="validation-alert" wire:model="showValidationModal" class="max-w-md">
        <div class="space-y-4">
            <flux:heading size="lg" class="text-red-600 dark:text-red-400">
                <x-flux::icon.exclamation-triangle class="w-6 h-6 mr-2" />
                Required Information Missing
            </flux:heading>
            
            <p class="text-slate-700 dark:text-slate-300">
                Please fill in all required fields before submitting the booking request.
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