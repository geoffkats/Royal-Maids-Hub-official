<div class="space-y-6">
    @php
        $prefix = auth()->user()->role === 'trainer' ? 'trainer.' : '';
    @endphp

    <!-- Header Section -->
    <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
        <div class="flex items-center gap-3">
            <div class="rounded-full bg-[#F5B301] p-2">
                <flux:icon.user-plus class="size-6 text-[#3B0A45]" />
            </div>
            <div>
                <flux:heading size="xl" class="text-white">{{ __('Add New Maid') }}</flux:heading>
                <flux:subheading class="text-[#D1C4E9]">{{ __('Complete the registration form to add a new domestic worker') }}</flux:subheading>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if (session()->has('message'))
        <div class="rounded-lg border border-[#4CAF50]/30 bg-[#4CAF50]/10 p-4 shadow-lg">
            <div class="flex items-center gap-3">
                <flux:icon.check-circle class="size-5 text-[#4CAF50]" />
                <span class="font-medium text-white">{{ session('message') }}</span>
            </div>
        </div>
    @endif

    <!-- Main Form -->
    <form wire:submit.prevent="save" class="space-y-8">
        <!-- Personal Information Section -->
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <div class="flex items-center gap-2 mb-4">
                <flux:icon.user class="size-5 text-[#F5B301]" />
                <flux:heading size="md" class="text-white">{{ __('Personal Information') }}</flux:heading>
            </div>
            <p class="text-sm text-[#D1C4E9] mb-6">{{ __('Basic personal details and contact information') }}</p>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="first_name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                First Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model="first_name" id="first_name" 
                                   class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-white transition-all duration-200 placeholder-slate-400 dark:placeholder-slate-500">
                            @error('first_name') 
                                <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                                    <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="last_name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Last Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model="last_name" id="last_name" 
                                   class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-white transition-all duration-200 placeholder-slate-400 dark:placeholder-slate-500">
                            @error('last_name') 
                                <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                                    <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="phone" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Primary Phone <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model="phone" id="phone" 
                                   class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-white transition-all duration-200 placeholder-slate-400 dark:placeholder-slate-500">
                            @error('phone') 
                                <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                                    <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="mobile_number_2" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Secondary Phone
                            </label>
                            <input type="text" wire:model="mobile_number_2" id="mobile_number_2" 
                                   class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-white transition-all duration-200 placeholder-slate-400 dark:placeholder-slate-500">
                            @error('mobile_number_2') 
                                <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                                    <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="date_of_birth" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Date of Birth <span class="text-red-500">*</span>
                            </label>
                            <input type="date" wire:model="date_of_birth" id="date_of_birth" 
                                   class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-white transition-all duration-200">
                            @error('date_of_birth') 
                                <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                                    <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="date_of_arrival" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Date of Arrival <span class="text-red-500">*</span>
                            </label>
                            <input type="date" wire:model="date_of_arrival" id="date_of_arrival" 
                                   class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-white transition-all duration-200">
                            @error('date_of_arrival') 
                                <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                                    <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="nationality" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Nationality <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model="nationality" id="nationality" 
                                   class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-white transition-all duration-200 placeholder-slate-400 dark:placeholder-slate-500">
                            @error('nationality') 
                                <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                                    <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="nin_number" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                National ID Number <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model="nin_number" id="nin_number" 
                                   class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-white transition-all duration-200 placeholder-slate-400 dark:placeholder-slate-500">
                            @error('nin_number') 
                                <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                                    <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="marital_status" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Marital Status <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="marital_status" id="marital_status" 
                                    class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-white transition-all duration-200">
                                    @foreach($marital_statuses as $status)
                                        <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
                            @error('marital_status') 
                                <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                                    <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="number_of_children" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Number of Children <span class="text-red-500">*</span>
                            </label>
                            <input type="number" wire:model="number_of_children" id="number_of_children" min="0" 
                                   class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-slate-700 dark:text-white transition-all duration-200">
                            @error('number_of_children') 
                                <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                                    <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Location Details Section -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-xl font-semibold text-slate-900 dark:text-white flex items-center gap-3">
                        <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                            <x-flux::icon.map-pin class="w-5 h-5 text-green-600 dark:text-green-400" />
                        </div>
                        Location Details
                    </h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Geographic and administrative information</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="tribe" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Tribe <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model="tribe" id="tribe" list="tribe-options" 
                                   class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-slate-700 dark:text-white transition-all duration-200 placeholder-slate-400 dark:placeholder-slate-500"
                                   placeholder="Type to search tribes...">
                            <datalist id="tribe-options">
                                @foreach($tribes as $tribe)
                                    <option value="{{ $tribe }}">
                                @endforeach
                            </datalist>
                            @error('tribe') 
                                <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                                    <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="village" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Village <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model="village" id="village" 
                                   class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-slate-700 dark:text-white transition-all duration-200 placeholder-slate-400 dark:placeholder-slate-500">
                            @error('village') 
                                <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                                    <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="district" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                District <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model="district" id="district" list="district-options" 
                                   class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-slate-700 dark:text-white transition-all duration-200 placeholder-slate-400 dark:placeholder-slate-500"
                                   placeholder="Type to search districts...">
                            <datalist id="district-options">
                                @foreach($districts as $district)
                                    <option value="{{ $district }}">
                                @endforeach
                            </datalist>
                            @error('district') 
                                <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                                    <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="lc1_chairperson" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                LC1 Chairperson Details <span class="text-red-500">*</span>
                            </label>
                            <textarea wire:model="lc1_chairperson" id="lc1_chairperson" rows="3" 
                                      class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-slate-700 dark:text-white transition-all duration-200 placeholder-slate-400 dark:placeholder-slate-500 resize-none"></textarea>
                            @error('lc1_chairperson') 
                                <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                                    <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Family Information Section -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-xl font-semibold text-slate-900 dark:text-white flex items-center gap-3">
                        <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                            <x-flux::icon.user-group class="w-5 h-5 text-purple-600 dark:text-purple-400" />
                        </div>
                        Family Information
                    </h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Family contact details and information</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="mother_name_phone" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Mother's Name & Phone <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model="mother_name_phone" id="mother_name_phone" placeholder="Name - Phone Number" 
                                   class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-slate-700 dark:text-white transition-all duration-200 placeholder-slate-400 dark:placeholder-slate-500">
                            @error('mother_name_phone') 
                                <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                                    <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="father_name_phone" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Father's Name & Phone <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model="father_name_phone" id="father_name_phone" placeholder="Name - Phone Number" 
                                   class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-slate-700 dark:text-white transition-all duration-200 placeholder-slate-400 dark:placeholder-slate-500">
                            @error('father_name_phone') 
                                <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                                    <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Education & Experience Section -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="bg-gradient-to-r from-orange-50 to-amber-50 dark:from-orange-900/20 dark:to-amber-900/20 px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-xl font-semibold text-slate-900 dark:text-white flex items-center gap-3">
                        <div class="p-2 bg-orange-100 dark:bg-orange-900/30 rounded-lg">
                            <x-flux::icon.academic-cap class="w-5 h-5 text-orange-600 dark:text-orange-400" />
                        </div>
                        Education & Experience
                    </h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Educational background and work experience</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="education_level" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Education Level <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="education_level" id="education_level" 
                                    class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-slate-700 dark:text-white transition-all duration-200">
                                    @foreach($education_levels as $level)
                                        <option value="{{ $level }}">{{ $level }}</option>
                                    @endforeach
                                </select>
                            @error('education_level') 
                                <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                                    <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="experience_years" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Years of Experience <span class="text-red-500">*</span>
                            </label>
                            <input type="number" wire:model="experience_years" id="experience_years" min="0" 
                                   class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-slate-700 dark:text-white transition-all duration-200">
                            @error('experience_years') 
                                <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                                    <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="mother_tongue" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Mother Tongue <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model="mother_tongue" id="mother_tongue" 
                                   class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-slate-700 dark:text-white transition-all duration-200 placeholder-slate-400 dark:placeholder-slate-500">
                            @error('mother_tongue') 
                                <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                                    <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="english_proficiency" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                English Proficiency (1-10) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" wire:model="english_proficiency" id="english_proficiency" min="1" max="10" 
                                   class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-slate-700 dark:text-white transition-all duration-200">
                            @error('english_proficiency') 
                                <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                                    <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label for="previous_work" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Previous Work Experience
                            </label>
                            <textarea wire:model="previous_work" id="previous_work" rows="3" 
                                      class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-slate-700 dark:text-white transition-all duration-200 placeholder-slate-400 dark:placeholder-slate-500 resize-none"></textarea>
                            @error('previous_work') 
                                <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                                    <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Professional Information Section -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="bg-gradient-to-r from-cyan-50 to-blue-50 dark:from-cyan-900/20 dark:to-blue-900/20 px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-xl font-semibold text-slate-900 dark:text-white flex items-center gap-3">
                        <div class="p-2 bg-cyan-100 dark:bg-cyan-900/30 rounded-lg">
                            <x-flux::icon.briefcase class="w-5 h-5 text-cyan-600 dark:text-cyan-400" />
                        </div>
                        Professional Information
                    </h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Work role, status, and professional details</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="role" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Role <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="role" id="role" 
                                    class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 dark:bg-slate-700 dark:text-white transition-all duration-200">
                                    @foreach($roles as $role)
                                        <option value="{{ $role }}">{{ ucwords(str_replace('_', ' ', $role)) }}</option>
                                    @endforeach
                                </select>
                            @error('role') 
                                <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                                    <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="status" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Primary Status <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="status" id="status" 
                                    class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 dark:bg-slate-700 dark:text-white transition-all duration-200">
                                    @foreach($statuses as $status)
                                        <option value="{{ $status }}">{{ ucwords(str_replace('-', ' ', $status)) }}</option>
                                    @endforeach
                                </select>
                            @error('status') 
                                <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                                    <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="secondary_status" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Secondary Status <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="secondary_status" id="secondary_status" 
                                    class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 dark:bg-slate-700 dark:text-white transition-all duration-200">
                                    @foreach($statuses as $status)
                                        <option value="{{ $status }}">{{ ucwords(str_replace('-', ' ', $status)) }}</option>
                                    @endforeach
                                </select>
                            @error('secondary_status') 
                                <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                                    <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="work_status" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Work Status <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="work_status" id="work_status" 
                                    class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 dark:bg-slate-700 dark:text-white transition-all duration-200">
                                    @foreach($work_statuses as $status)
                                        <option value="{{ $status }}">{{ ucwords(str_replace('-', ' ', $status)) }}</option>
                                    @endforeach
                                </select>
                            @error('work_status') 
                                <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                                    <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Medical Information Section -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="bg-gradient-to-r from-red-50 to-rose-50 dark:from-red-900/20 dark:to-rose-900/20 px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-xl font-semibold text-slate-900 dark:text-white flex items-center gap-3">
                        <div class="p-2 bg-red-100 dark:bg-red-900/30 rounded-lg">
                            <x-flux::icon.heart class="w-5 h-5 text-red-600 dark:text-red-400" />
                        </div>
                        Medical Information
                    </h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Health records and medical test results</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="hepatitis_b_result" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Hepatitis B Test Result
                            </label>
                            <select wire:model="hepatitis_b_result" id="hepatitis_b_result" 
                                    class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-slate-700 dark:text-white transition-all duration-200">
                                <option value="">-- Select Result --</option>
                                <option value="positive">Positive</option>
                                <option value="negative">Negative</option>
                                <option value="pending">Pending</option>
                                <option value="not_tested">Not Tested</option>
                            </select>
                            @error('hepatitis_b_result') 
                                <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                                    <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="hepatitis_b_date" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Hepatitis B Test Date
                            </label>
                            <input type="date" wire:model="hepatitis_b_date" id="hepatitis_b_date" 
                                   class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-slate-700 dark:text-white transition-all duration-200">
                            @error('hepatitis_b_date') 
                                <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                                    <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="hiv_result" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                HIV Test Result
                            </label>
                            <select wire:model="hiv_result" id="hiv_result" 
                                    class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-slate-700 dark:text-white transition-all duration-200">
                                <option value="">-- Select Result --</option>
                                <option value="positive">Positive</option>
                                <option value="negative">Negative</option>
                                <option value="pending">Pending</option>
                                <option value="not_tested">Not Tested</option>
                            </select>
                            @error('hiv_result') 
                                <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                                    <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="hiv_date" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                HIV Test Date
                            </label>
                            <input type="date" wire:model="hiv_date" id="hiv_date" 
                                   class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-slate-700 dark:text-white transition-all duration-200">
                            @error('hiv_date') 
                                <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                                    <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="urine_hcg_result" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Urine HCG Test Result
                            </label>
                            <select wire:model="urine_hcg_result" id="urine_hcg_result" 
                                    class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-slate-700 dark:text-white transition-all duration-200">
                                <option value="">-- Select Result --</option>
                                <option value="positive">Positive</option>
                                <option value="negative">Negative</option>
                                <option value="pending">Pending</option>
                                <option value="not_tested">Not Tested</option>
                            </select>
                            @error('urine_hcg_result') 
                                <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                                    <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="urine_hcg_date" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Urine HCG Test Date
                            </label>
                            <input type="date" wire:model="urine_hcg_date" id="urine_hcg_date" 
                                   class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-slate-700 dark:text-white transition-all duration-200">
                            @error('urine_hcg_date') 
                                <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                                    <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label for="medical_notes" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Medical Notes
                            </label>
                            <textarea wire:model="medical_notes" id="medical_notes" rows="3" 
                                      class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-slate-700 dark:text-white transition-all duration-200 placeholder-slate-400 dark:placeholder-slate-500 resize-none"></textarea>
                            @error('medical_notes') 
                                <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                                    <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- File Uploads Section -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="bg-gradient-to-r from-violet-50 to-purple-50 dark:from-violet-900/20 dark:to-purple-900/20 px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-xl font-semibold text-slate-900 dark:text-white flex items-center gap-3">
                        <div class="p-2 bg-violet-100 dark:bg-violet-900/30 rounded-lg">
                            <x-flux::icon.document-arrow-up class="w-5 h-5 text-violet-600 dark:text-violet-400" />
                        </div>
                        File Uploads
                    </h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Upload profile image and supporting documents</p>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label for="profile_image" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Profile Image
                            </label>
                            <div class="flex items-start gap-4">
                                <!-- Preview -->
                                @if ($profile_image)
                                    <div class="flex-shrink-0">
                                        <div class="relative">
                                            <img src="{{ $profile_image->temporaryUrl() }}" 
                                                 class="w-32 h-32 object-cover rounded-xl border-2 border-violet-200 dark:border-violet-700 shadow-lg"
                                                 alt="Profile Preview">
                                            <button type="button" 
                                                    wire:click="$set('profile_image', null)"
                                                    class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1.5 shadow-lg transition-colors">
                                                <x-flux::icon.x-mark class="w-4 h-4" />
                                            </button>
                                        </div>
                                        <p class="text-xs text-center text-slate-500 dark:text-slate-400 mt-2">
                                            {{ number_format($profile_image->getSize() / 1024, 2) }} KB
                                        </p>
                                    </div>
                                @endif
                                
                                <!-- Upload Input -->
                                <div class="flex-1">
                                    <input type="file" wire:model="profile_image" id="profile_image" accept="image/*" 
                                           class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-slate-700 dark:text-white transition-all duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100 dark:file:bg-violet-900/20 dark:file:text-violet-300">
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                                        Max file size: 2MB • Formats: JPG, PNG, GIF, WebP
                                    </p>
                                    <div wire:loading wire:target="profile_image" class="mt-2">
                                        <div class="flex items-center gap-2 text-violet-600 dark:text-violet-400 text-sm">
                                            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Uploading...
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @error('profile_image') 
                                <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                                    <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="additional_documents" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                Additional Documents
                            </label>
                            <input type="file" wire:model="additional_documents" id="additional_documents" multiple 
                                   class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-slate-700 dark:text-white transition-all duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100 dark:file:bg-violet-900/20 dark:file:text-violet-300">
                            @error('additional_documents.*') 
                                <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                                    <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="id_scans" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                                ID Scans
                            </label>
                            <input type="file" wire:model="id_scans" id="id_scans" multiple 
                                   class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-violet-500 focus:border-violet-500 dark:bg-slate-700 dark:text-white transition-all duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100 dark:file:bg-violet-900/20 dark:file:text-violet-300">
                            @error('id_scans.*') 
                                <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                                    <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Notes Section -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="bg-gradient-to-r from-slate-50 to-gray-50 dark:from-slate-900/20 dark:to-gray-900/20 px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-xl font-semibold text-slate-900 dark:text-white flex items-center gap-3">
                        <div class="p-2 bg-slate-100 dark:bg-slate-900/30 rounded-lg">
                            <x-flux::icon.document-text class="w-5 h-5 text-slate-600 dark:text-slate-400" />
                        </div>
                        Additional Information
                    </h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Additional notes and comments</p>
                </div>
                <div class="p-6">
                    <div class="space-y-2">
                        <label for="additional_notes" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                            Additional Notes
                        </label>
                        <textarea wire:model="additional_notes" id="additional_notes" rows="4" 
                                  class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm focus:ring-2 focus:ring-slate-500 focus:border-slate-500 dark:bg-slate-700 dark:text-white transition-all duration-200 placeholder-slate-400 dark:placeholder-slate-500 resize-none"></textarea>
                        @error('additional_notes') 
                            <div class="flex items-center gap-2 text-red-600 text-sm mt-1">
                                <x-flux::icon.exclamation-triangle class="w-4 h-4" />
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 p-6">
                <div class="flex flex-col sm:flex-row gap-4 justify-end">
                    <a href="{{ route($prefix . 'maids.index') }}" 
                       class="inline-flex items-center justify-center px-6 py-3 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 font-semibold rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                        <x-flux::icon.x-mark class="w-5 h-5 mr-2" />
                            Cancel
                        </a>
                    <button type="submit" 
                            class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <x-flux::icon.check class="w-5 h-5 mr-2" />
                            Create Maid
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Validation Alert Modal -->
        @if($showValidationModal ?? false)
            <div wire:ignore>
                <flux:modal name="validation-alert" wire:model="showValidationModal" class="max-w-md">
                    <div class="space-y-4">
                        <flux:heading size="lg" class="text-red-600 dark:text-red-400">
                            <x-flux::icon.exclamation-triangle class="w-6 h-6 mr-2" />
                            Required Information Missing
                        </flux:heading>
                        
                        <p class="text-slate-700 dark:text-slate-300">
                            Please fill in all required fields before creating the maid profile.
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
        @endif
    </div>