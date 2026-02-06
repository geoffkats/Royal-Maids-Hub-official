<div class="min-h-screen bg-gradient-to-br from-[#512B58] via-[#3B0A45] to-[#2D1B69] py-8">
    @php
        $prefix = auth()->user()->role === 'trainer' ? 'trainer.' : '';
    @endphp
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-white mb-2">{{ __('Edit Booking #') . $booking->id }}</h1>
                    <p class="text-lg text-[#D1C4E9]">{{ __('Update comprehensive booking details') }}</p>
                </div>
                <flux:button as="a" :href="route($prefix . 'bookings.show', $booking)" variant="ghost" icon="arrow-left" class="text-white border-white/30 hover:bg-white/10">
                    {{ __('Back') }}
                </flux:button>
            </div>

            <form wire:submit="update" class="space-y-6">
                {{-- Section 1: Contact Information --}}
                <div class="bg-white/10 backdrop-blur-md rounded-2xl border border-[#F5B301]/30 shadow-2xl p-8">
                    <div class="mb-6 flex items-center gap-3 border-b border-[#F5B301]/30 pb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-xl flex items-center justify-center">
                            <flux:icon.user class="size-6 text-[#512B58]" />
                        </div>
                        <h2 class="text-2xl font-bold text-white">{{ __('Contact Information') }}</h2>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label for="full_name" class="block text-sm font-semibold text-white">
                                {{ __('Full Name') }} <span class="text-[#F5B301]">*</span>
                            </label>
                            <input type="text" 
                                   wire:model="full_name" 
                                   id="full_name"
                                   class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300"
                                   placeholder="{{ __('Enter your full name') }}">
                            @error('full_name') 
                                <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                    <flux:icon.exclamation-circle class="size-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="phone" class="block text-sm font-semibold text-white">
                                {{ __('Phone Number') }} <span class="text-[#F5B301]">*</span>
                            </label>
                            <input type="tel" 
                                   wire:model="phone" 
                                   id="phone"
                                   class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300"
                                   placeholder="+256-7XX-XXX-XXX">
                            @error('phone') 
                                <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                    <flux:icon.exclamation-circle class="size-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2 md:col-span-2">
                            <label for="email" class="block text-sm font-semibold text-white">
                                {{ __('Email') }} <span class="text-[#F5B301]">*</span>
                            </label>
                            <input type="email" 
                                   wire:model="email" 
                                   id="email"
                                   class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300"
                                   placeholder="your.email@example.com">
                            @error('email') 
                                <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                    <flux:icon.exclamation-circle class="size-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="country" class="block text-sm font-semibold text-white">
                                {{ __('Country') }} <span class="text-[#F5B301]">*</span>
                            </label>
                            <input type="text" 
                                   wire:model="country" 
                                   id="country"
                                   class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300"
                                   placeholder="Uganda">
                            @error('country') 
                                <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                    <flux:icon.exclamation-circle class="size-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="city" class="block text-sm font-semibold text-white">
                                {{ __('City') }} <span class="text-[#F5B301]">*</span>
                            </label>
                            <input type="text" 
                                   wire:model="city" 
                                   id="city"
                                   class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300"
                                   placeholder="e.g., Kampala">
                            @error('city') 
                                <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                    <flux:icon.exclamation-circle class="size-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="division" class="block text-sm font-semibold text-white">
                                {{ __('Division') }} <span class="text-[#F5B301]">*</span>
                            </label>
                            <input type="text" 
                                   wire:model="division" 
                                   id="division"
                                   class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300"
                                   placeholder="e.g., Makindye">
                            @error('division') 
                                <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                    <flux:icon.exclamation-circle class="size-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="parish" class="block text-sm font-semibold text-white">
                                {{ __('Parish') }} <span class="text-[#F5B301]">*</span>
                            </label>
                            <input type="text" 
                                   wire:model="parish" 
                                   id="parish"
                                   class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300"
                                   placeholder="e.g., Bukoto">
                            @error('parish') 
                                <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                    <flux:icon.exclamation-circle class="size-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        @can('updateSensitiveIdentity')
                            <div class="space-y-2">
                                <label for="identity_type" class="block text-sm font-semibold text-white">
                                    {{ __('Identity Type') }}
                                </label>
                                <select wire:model="identity_type"
                                        id="identity_type"
                                        class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white transition-all duration-300">
                                    <option value="" class="bg-[#512B58] text-white">{{ __('Select identity type') }}</option>
                                    <option value="nin" class="bg-[#512B58] text-white">{{ __('NIN') }}</option>
                                    <option value="passport" class="bg-[#512B58] text-white">{{ __('Passport') }}</option>
                                </select>
                                @error('identity_type') 
                                    <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                        <flux:icon.exclamation-circle class="size-4" />
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="identity_number" class="block text-sm font-semibold text-white">
                                    {{ __('Identity Number') }}
                                </label>
                                <input type="text" 
                                       wire:model="identity_number" 
                                       id="identity_number"
                                       class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300"
                                       placeholder="{{ __('Enter identity number') }}">
                                @error('identity_number') 
                                    <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                        <flux:icon.exclamation-circle class="size-4" />
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="space-y-2 md:col-span-2">
                                <label for="national_id" class="block text-sm font-semibold text-white">
                                    {{ __('National ID / Passport') }}
                                </label>
                                @if($existing_national_id_path)
                                    <div class="mt-2 mb-3 flex items-center gap-3 rounded-xl border border-[#F5B301]/30 bg-white/5 p-3">
                                        <flux:icon.document-text class="size-5 text-[#F5B301]" />
                                        <span class="text-sm text-white">{{ __('Current document on file') }}</span>
                                        <a href="{{ \Storage::url($existing_national_id_path) }}" target="_blank" class="ml-auto text-sm text-[#F5B301] hover:text-[#FFD700] underline">
                                            {{ __('View') }}
                                        </a>
                                    </div>
                                @endif
                                <input type="file" 
                                       wire:model="national_id" 
                                       id="national_id"
                                       accept=".pdf,.jpg,.jpeg,.png"
                                   class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white transition-all duration-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#F5B301] file:text-[#512B58] hover:file:bg-[#FFD700]">
                            <p class="mt-1 text-xs text-[#D1C4E9]/60">{{ __('Upload new file to replace existing (PDF, JPG, PNG - Max 2MB)') }}</p>
                            @error('national_id') 
                                <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                    <flux:icon.exclamation-circle class="size-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        @endcan
                    </div>
                </div>

                {{-- Section 2: Home Details --}}
                <div class="bg-white/10 backdrop-blur-md rounded-2xl border border-[#F5B301]/30 shadow-2xl p-8">
                    <div class="mb-6 flex items-center gap-3 border-b border-[#F5B301]/30 pb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-xl flex items-center justify-center">
                            <flux:icon.home class="size-6 text-[#512B58]" />
                        </div>
                        <h2 class="text-2xl font-bold text-white">{{ __('Home Details') }}</h2>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label for="village" class="block text-sm font-semibold text-white">
                                {{ __('Village') }} <span class="text-[#F5B301]">*</span>
                            </label>
                            <input type="text" 
                                   wire:model="village" 
                                   id="village"
                                   class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300"
                                   placeholder="e.g., Ntinda">
                            @error('village') 
                                <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                    <flux:icon.exclamation-circle class="size-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="house_type" class="block text-sm font-semibold text-white">
                                {{ __('House Type') }} <span class="text-[#F5B301]">*</span>
                            </label>
                            <select wire:model="house_type" 
                                    id="house_type"
                                    class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white transition-all duration-300">
                                <option value="" class="bg-[#512B58] text-white">{{ __('Select house type') }}</option>
                                <option value="Apartment" class="bg-[#512B58] text-white">{{ __('Apartment') }}</option>
                                <option value="House" class="bg-[#512B58] text-white">{{ __('House') }}</option>
                                <option value="Townhouse" class="bg-[#512B58] text-white">{{ __('Townhouse') }}</option>
                                <option value="Villa" class="bg-[#512B58] text-white">{{ __('Villa') }}</option>
                                <option value="Other" class="bg-[#512B58] text-white">{{ __('Other') }}</option>
                            </select>
                            @error('house_type') 
                                <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                    <flux:icon.exclamation-circle class="size-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="number_of_rooms" class="block text-sm font-semibold text-white">
                                {{ __('Number of Rooms') }} <span class="text-[#F5B301]">*</span>
                            </label>
                            <input type="number" 
                                   wire:model="number_of_rooms" 
                                   id="number_of_rooms"
                                   min="1"
                                   class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300"
                                   placeholder="e.g., 3">
                            @error('number_of_rooms') 
                                <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                    <flux:icon.exclamation-circle class="size-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="bedrooms" class="block text-sm font-semibold text-white">
                                {{ __('How many bedrooms are in your home?') }} <span class="text-[#F5B301]">*</span>
                            </label>
                            <input type="number" 
                                   wire:model="bedrooms" 
                                   id="bedrooms"
                                   min="0"
                                   class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300"
                                   placeholder="e.g., 3">
                            @error('bedrooms') 
                                <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                    <flux:icon.exclamation-circle class="size-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="bathrooms" class="block text-sm font-semibold text-white">
                                {{ __('How many bathrooms/toilets are in your home?') }} <span class="text-[#F5B301]">*</span>
                            </label>
                            <input type="number" 
                                   wire:model="bathrooms" 
                                   id="bathrooms"
                                   min="0"
                                   class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300"
                                   placeholder="e.g., 2">
                            @error('bathrooms') 
                                <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                    <flux:icon.exclamation-circle class="size-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2 md:col-span-2">
                            <label class="block text-sm font-semibold text-white mb-3">
                                {{ __('Are there outdoor responsibilities? (Check all that apply)') }}
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                @foreach(['Sweeping the compound', 'Light gardening / Watering plants', 'No outdoor responsibilities'] as $responsibility)
                                    <label class="relative flex items-center p-3 border-2 rounded-xl cursor-pointer transition-all duration-200 {{ in_array($responsibility, $outdoor_responsibilities) ? 'border-[#F5B301] bg-[#F5B301]/10' : 'border-white/20 hover:border-[#F5B301]/50' }}">
                                        <input type="checkbox" 
                                               wire:model.live="outdoor_responsibilities" 
                                               name="outdoor_responsibilities[]"
                                               value="{{ $responsibility }}" 
                                               class="sr-only">
                                        <div class="flex items-center gap-2 w-full">
                                            <div class="w-5 h-5 rounded border-2 flex items-center justify-center transition-all {{ in_array($responsibility, $outdoor_responsibilities) ? 'border-[#F5B301] bg-[#F5B301]' : 'border-white/40' }}">
                                                @if(in_array($responsibility, $outdoor_responsibilities))
                                                    <flux:icon.check class="size-3 text-white" />
                                                @endif
                                            </div>
                                            <span class="text-sm font-medium {{ in_array($responsibility, $outdoor_responsibilities) ? 'text-[#F5B301]' : 'text-white' }}">
                                                {{ $responsibility }}
                                            </span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('outdoor_responsibilities')
                                <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                    <flux:icon.exclamation-circle class="size-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2 md:col-span-2">
                            <label class="block text-sm font-semibold text-white mb-3">
                                {{ __('Which appliances will the domestic worker use? (Check all that apply)') }}
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                @foreach(['Automatic Washing Machine', 'Semi-automatic Washing Machine', 'Microwave', 'Electric or Gas Oven', 'Blender', 'Airfryer', 'Standby Generator', 'None of the above / Handwashing only'] as $appliance)
                                    <label class="relative flex items-center p-3 border-2 rounded-xl cursor-pointer transition-all duration-200 {{ in_array($appliance, $appliances) ? 'border-[#F5B301] bg-[#F5B301]/10' : 'border-white/20 hover:border-[#F5B301]/50' }}">
                                        <input type="checkbox" 
                                               wire:model.live="appliances" 
                                               name="appliances[]"
                                               value="{{ $appliance }}" 
                                               class="sr-only">
                                        <div class="flex items-center gap-2 w-full">
                                            <div class="w-5 h-5 rounded border-2 flex items-center justify-center transition-all {{ in_array($appliance, $appliances) ? 'border-[#F5B301] bg-[#F5B301]' : 'border-white/40' }}">
                                                @if(in_array($appliance, $appliances))
                                                    <flux:icon.check class="size-3 text-white" />
                                                @endif
                                            </div>
                                            <span class="text-sm font-medium {{ in_array($appliance, $appliances) ? 'text-[#F5B301]' : 'text-white' }}">
                                                {{ $appliance }}
                                            </span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('appliances')
                                <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                    <flux:icon.exclamation-circle class="size-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2 md:col-span-2">
                            <label for="special_requirements" class="block text-sm font-semibold text-white">
                                {{ __('Special Requirements (Optional)') }}
                            </label>
                            <textarea wire:model="special_requirements" 
                                      id="special_requirements"
                                      rows="3" 
                                      class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300"
                                      placeholder="{{ __('Any special requirements for your maid...') }}"></textarea>
                            @error('special_requirements') 
                                <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                    <flux:icon.exclamation-circle class="size-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Section 3: Household Information --}}
                <div class="bg-white/10 backdrop-blur-md rounded-2xl border border-[#F5B301]/30 shadow-2xl p-8">
                    <div class="mb-6 flex items-center gap-3 border-b border-[#F5B301]/30 pb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-xl flex items-center justify-center">
                            <flux:icon.user-group class="size-6 text-[#512B58]" />
                        </div>
                        <h2 class="text-2xl font-bold text-white">{{ __('Household Information') }}</h2>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label for="family_size" class="block text-sm font-semibold text-white">
                                {{ __('Family Size') }} <span class="text-[#F5B301]">*</span>
                            </label>
                            <select wire:model="family_size" 
                                    id="family_size"
                                    class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white transition-all duration-300">
                                <option value="" class="bg-[#512B58] text-white">{{ __('Select family size') }}</option>
                                <option value="1-2" class="bg-[#512B58] text-white">{{ __('1-2 people') }}</option>
                                <option value="3-4" class="bg-[#512B58] text-white">{{ __('3-4 people') }}</option>
                                <option value="5-6" class="bg-[#512B58] text-white">{{ __('5-6 people') }}</option>
                                <option value="7+" class="bg-[#512B58] text-white">{{ __('7+ people') }}</option>
                            </select>
                            @error('family_size') 
                                <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                    <flux:icon.exclamation-circle class="size-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="children_count" class="block text-sm font-semibold text-white">
                                {{ __('Number of Children') }}
                            </label>
                            <input type="number" 
                                   wire:model="children_count" 
                                   id="children_count"
                                   min="0"
                                   class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300"
                                   placeholder="0">
                            @error('children_count') 
                                <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                    <flux:icon.exclamation-circle class="size-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="elderly_count" class="block text-sm font-semibold text-white">
                                {{ __('Number of Elderly (65+)') }}
                            </label>
                            <input type="number" 
                                   wire:model="elderly_count" 
                                   id="elderly_count"
                                   min="0"
                                   class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300"
                                   placeholder="0">
                            @error('elderly_count') 
                                <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                    <flux:icon.exclamation-circle class="size-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="pets" class="block text-sm font-semibold text-white">
                                {{ __('Pets') }} <span class="text-[#F5B301]">*</span>
                            </label>
                            <select wire:model="pets" 
                                    id="pets"
                                    class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white transition-all duration-300">
                                <option value="none" class="bg-[#512B58] text-white">{{ __('No pets') }}</option>
                                <option value="dogs" class="bg-[#512B58] text-white">{{ __('Dogs') }}</option>
                                <option value="cats" class="bg-[#512B58] text-white">{{ __('Cats') }}</option>
                                <option value="both" class="bg-[#512B58] text-white">{{ __('Dogs & Cats') }}</option>
                                <option value="other" class="bg-[#512B58] text-white">{{ __('Other pets') }}</option>
                            </select>
                            @error('pets') 
                                <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                    <flux:icon.exclamation-circle class="size-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2 md:col-span-2">
                            <label for="special_needs" class="block text-sm font-semibold text-white">
                                {{ __('Special Needs or Requirements (Optional)') }}
                            </label>
                            <textarea wire:model="special_needs" 
                                      id="special_needs"
                                      rows="3" 
                                      class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300"
                                      placeholder="{{ __('Any special needs or requirements...') }}"></textarea>
                            @error('special_needs') 
                                <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                    <flux:icon.exclamation-circle class="size-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Section 4: Service Expectations --}}
                <div class="bg-white/10 backdrop-blur-md rounded-2xl border border-[#F5B301]/30 shadow-2xl p-8">
                    <div class="mb-6 flex items-center gap-3 border-b border-[#F5B301]/30 pb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-xl flex items-center justify-center">
                            <flux:icon.clipboard-document-list class="size-6 text-[#512B58]" />
                        </div>
                        <h2 class="text-2xl font-bold text-white">{{ __('Service Expectations') }}</h2>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        {{-- Package Selection --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-white mb-3">
                                {{ __('Select Service Package') }} <span class="text-[#F5B301]">*</span>
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                @foreach($packages as $package)
                                    <label class="relative flex flex-col p-5 border-2 rounded-xl cursor-pointer transition-all duration-200
                                                 {{ $package_id == $package->id ? 'border-[#F5B301] bg-[#F5B301]/10 shadow-lg' : 'border-white/20 hover:border-[#F5B301]/50' }}">
                                        <input type="radio" 
                                               wire:model.live="package_id" 
                                               name="package_id"
                                               value="{{ $package->id }}" 
                                               class="sr-only">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-lg font-bold {{ $package_id == $package->id ? 'text-[#F5B301]' : 'text-white' }}">
                                                {{ $package->name }}
                                            </span>
                                            @if($package_id == $package->id)
                                                <flux:icon.check-circle class="size-6 text-[#F5B301]" />
                                            @endif
                                        </div>
                                        <p class="text-xs text-[#D1C4E9]/80">
                                            {{ Str::limit($package->description ?? '', 80) }}
                                        </p>
                                    </label>
                                @endforeach
                            </div>
                            @error('package_id') 
                                <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                    <flux:icon.exclamation-circle class="size-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Service Mode --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-white mb-3">
                                {{ __('Service Mode') }} <span class="text-[#F5B301]">*</span>
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach(['Live-in', 'Live-out'] as $mode)
                                    <label class="relative flex items-center justify-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-200
                                                 {{ $service_mode === $mode ? 'border-[#F5B301] bg-[#F5B301]/10' : 'border-white/20 hover:border-[#F5B301]/50' }}">
                                        <input type="radio" 
                                               wire:model.live="service_mode" 
                                               name="service_mode"
                                               value="{{ $mode }}" 
                                               class="sr-only">
                                        <span class="text-sm font-medium {{ $service_mode === $mode ? 'text-[#F5B301]' : 'text-white' }}">
                                            {{ $mode }}
                                        </span>
                                        @if($service_mode === $mode)
                                            <flux:icon.check-circle class="size-5 text-[#F5B301] absolute top-2 right-2" />
                                        @endif
                                    </label>
                                @endforeach
                            </div>
                            @error('service_mode') 
                                <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                    <flux:icon.exclamation-circle class="size-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Work Days --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-white mb-3">
                                {{ __('Work Days') }} <span class="text-[#F5B301]">*</span>
                            </label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday', 'All Days'] as $day)
                                    <label class="relative flex items-center p-3 border-2 rounded-xl cursor-pointer transition-all duration-200
                                                 {{ in_array($day, $work_days) ? 'border-[#F5B301] bg-[#F5B301]/10' : 'border-white/20 hover:border-[#F5B301]/50' }}">
                                        <input type="checkbox" 
                                               wire:model.live="work_days" 
                                               name="work_days[]"
                                               value="{{ $day }}" 
                                               class="sr-only">
                                        <div class="flex items-center gap-2 w-full">
                                            <div class="w-5 h-5 rounded border-2 flex items-center justify-center transition-all
                                                      {{ in_array($day, $work_days) ? 'border-[#F5B301] bg-[#F5B301]' : 'border-white/40' }}">
                                                @if(in_array($day, $work_days))
                                                    <flux:icon.check class="size-3 text-white" />
                                                @endif
                                            </div>
                                            <span class="text-sm font-medium {{ in_array($day, $work_days) ? 'text-[#F5B301]' : 'text-white' }}">
                                                {{ $day }}
                                            </span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('work_days') 
                                <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                    <flux:icon.exclamation-circle class="size-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Start Date --}}
                        <div class="space-y-2">
                            <label for="start_date" class="block text-sm font-semibold text-white">
                                {{ __('Preferred Start Date') }} <span class="text-[#F5B301]">*</span>
                            </label>
                            <input type="date" 
                                   wire:model="start_date" 
                                   id="start_date"
                                   min="{{ date('Y-m-d') }}"
                                   class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white transition-all duration-300">
                            @error('start_date') 
                                <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                    <flux:icon.exclamation-circle class="size-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- End Date --}}
                        <div class="space-y-2">
                            <label for="end_date" class="block text-sm font-semibold text-white">
                                {{ __('Preferred End Date (Optional)') }}
                            </label>
                            <input type="date" 
                                   wire:model="end_date" 
                                   id="end_date"
                                   min="{{ date('Y-m-d') }}"
                                   class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white transition-all duration-300">
                            @error('end_date') 
                                <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                    <flux:icon.exclamation-circle class="size-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Working Hours --}}
                        <div class="space-y-2">
                            <label for="working_hours" class="block text-sm font-semibold text-white">
                                {{ __('Working Hours (Optional)') }}
                            </label>
                            <input type="text"
                                   wire:model="working_hours"
                                   id="working_hours"
                                   placeholder="e.g., 8:00 AM - 5:00 PM"
                                   class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300" />
                            @error('working_hours')
                                <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                    <flux:icon.exclamation-circle class="size-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Responsibilities --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-white mb-3">
                                {{ __('What are the main responsibilities? (Check all that apply)') }}
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                @foreach(['General House Cleaning', 'Laundry and Ironing', 'Cooking', 'Childcare / Nanny duties', 'Grocery Shopping / Running Errands', 'Elderly Care'] as $task)
                                    <label class="relative flex items-center p-3 border-2 rounded-xl cursor-pointer transition-all duration-200 
                                                 {{ in_array($task, $responsibilities) ? 'border-[#F5B301] bg-[#F5B301]/10' : 'border-white/20 hover:border-[#F5B301]/50' }}">
                                        <input type="checkbox" wire:model.live="responsibilities" name="responsibilities[]" value="{{ $task }}" class="sr-only">
                                        <div class="flex items-center gap-2 w-full">
                                            <div class="w-5 h-5 rounded border-2 flex items-center justify-center transition-all {{ in_array($task, $responsibilities) ? 'border-[#F5B301] bg-[#F5B301]' : 'border-white/40' }}">
                                                @if(in_array($task, $responsibilities))
                                                    <flux:icon.check class="size-3 text-white" />
                                                @endif
                                            </div>
                                            <span class="text-sm font-medium {{ in_array($task, $responsibilities) ? 'text-[#F5B301]' : 'text-white' }}">{{ $task }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('responsibilities')
                                <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                    <flux:icon.exclamation-circle class="size-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Cuisine Type (if cooking is required) --}}
                        @if(in_array('Cooking', $responsibilities))
                        <div class="space-y-2 md:col-span-2">
                            <label class="block text-sm font-semibold text-white mb-3">
                                {{ __('If cooking is required, what kind of cuisine?') }}
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <label class="relative flex items-center justify-center p-3 border-2 rounded-xl cursor-pointer transition-all duration-200 {{ $cuisine_type === 'Mainly local Ugandan dishes' ? 'border-[#F5B301] bg-[#F5B301]/10' : 'border-white/20 hover:border-[#F5B301]/50' }}">
                                    <input type="radio" wire:model.live="cuisine_type" name="cuisine_type" value="Mainly local Ugandan dishes" class="sr-only">
                                    <span class="text-sm font-medium {{ $cuisine_type === 'Mainly local Ugandan dishes' ? 'text-[#F5B301]' : 'text-white' }}">Mainly local Ugandan dishes</span>
                                </label>
                                <label class="relative flex items-center justify-center p-3 border-2 rounded-xl cursor-pointer transition-all duration-200 {{ $cuisine_type === 'A mix of local and continental dishes' ? 'border-[#F5B301] bg-[#F5B301]/10' : 'border-white/20 hover:border-[#F5B301]/50' }}">
                                    <input type="radio" wire:model.live="cuisine_type" name="cuisine_type" value="A mix of local and continental dishes" class="sr-only">
                                    <span class="text-sm font-medium {{ $cuisine_type === 'A mix of local and continental dishes' ? 'text-[#F5B301]' : 'text-white' }}">A mix of local and continental dishes</span>
                                </label>
                                <label class="relative flex items-center justify-center p-3 border-2 rounded-xl cursor-pointer transition-all duration-200 {{ $cuisine_type === 'Other' ? 'border-[#F5B301] bg-[#F5B301]/10' : 'border-white/20 hover:border-[#F5B301]/50' }}">
                                    <input type="radio" wire:model.live="cuisine_type" name="cuisine_type" value="Other" class="sr-only">
                                    <span class="text-sm font-medium {{ $cuisine_type === 'Other' ? 'text-[#F5B301]' : 'text-white' }}">Other</span>
                                </label>
                            </div>
                            @if($cuisine_type === 'Other')
                            <div class="mt-3">
                                <input type="text" 
                                       wire:model="cuisine_other" 
                                       name="cuisine_other"
                                       id="cuisine_other"
                                       placeholder="Please specify"
                                       class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300">
                            </div>
                            @endif
                            @error('cuisine_type')
                                <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                    <flux:icon.exclamation-circle class="size-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        @endif

                        {{-- Atmosphere --}}
                        <div class="space-y-2 md:col-span-2">
                            <label class="block text-sm font-semibold text-white mb-3">
                                {{ __('How would you describe your household\'s atmosphere?') }}
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <label class="relative flex items-center justify-center p-3 border-2 rounded-xl cursor-pointer transition-all duration-200 {{ $atmosphere === 'Quiet and calm' ? 'border-[#F5B301] bg-[#F5B301]/10' : 'border-white/20 hover:border-[#F5B301]/50' }}">
                                    <input type="radio" wire:model.live="atmosphere" name="atmosphere" value="Quiet and calm" class="sr-only">
                                    <span class="text-sm font-medium {{ $atmosphere === 'Quiet and calm' ? 'text-[#F5B301]' : 'text-white' }}">Quiet and calm</span>
                                </label>
                                <label class="relative flex items-center justify-center p-3 border-2 rounded-xl cursor-pointer transition-all duration-200 {{ $atmosphere === 'Busy and fast-paced' ? 'border-[#F5B301] bg-[#F5B301]/10' : 'border-white/20 hover:border-[#F5B301]/50' }}">
                                    <input type="radio" wire:model.live="atmosphere" name="atmosphere" value="Busy and fast-paced" class="sr-only">
                                    <span class="text-sm font-medium {{ $atmosphere === 'Busy and fast-paced' ? 'text-[#F5B301]' : 'text-white' }}">Busy and fast-paced</span>
                                </label>
                            </div>
                            @error('atmosphere')
                                <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                    <flux:icon.exclamation-circle class="size-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Manage Tasks --}}
                        <div class="space-y-2 md:col-span-2">
                            <label class="block text-sm font-semibold text-white mb-3">
                                {{ __('How do you prefer to manage tasks?') }}
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <label class="relative flex items-center p-3 border-2 rounded-xl cursor-pointer transition-all duration-200 {{ $manage_tasks === 'I give verbal instructions each day.' ? 'border-[#F5B301] bg-[#F5B301]/10' : 'border-white/20 hover:border-[#F5B301]/50' }}">
                                    <input type="radio" wire:model.live="manage_tasks" name="manage_tasks" value="I give verbal instructions each day." class="sr-only">
                                    <span class="text-sm font-medium {{ $manage_tasks === 'I give verbal instructions each day.' ? 'text-[#F5B301]' : 'text-white' }}">I give verbal instructions each day.</span>
                                </label>
                                <label class="relative flex items-center p-3 border-2 rounded-xl cursor-pointer transition-all duration-200 {{ $manage_tasks === 'I provide a written list of duties.' ? 'border-[#F5B301] bg-[#F5B301]/10' : 'border-white/20 hover:border-[#F5B301]/50' }}">
                                    <input type="radio" wire:model.live="manage_tasks" name="manage_tasks" value="I provide a written list of duties." class="sr-only">
                                    <span class="text-sm font-medium {{ $manage_tasks === 'I provide a written list of duties.' ? 'text-[#F5B301]' : 'text-white' }}">I provide a written list of duties.</span>
                                </label>
                                <label class="relative flex items-center p-3 border-2 rounded-xl cursor-pointer transition-all duration-200 {{ $manage_tasks === 'I expect the worker to be experienced and take initiative.' ? 'border-[#F5B301] bg-[#F5B301]/10' : 'border-white/20 hover:border-[#F5B301]/50' }}">
                                    <input type="radio" wire:model.live="manage_tasks" name="manage_tasks" value="I expect the worker to be experienced and take initiative." class="sr-only">
                                    <span class="text-sm font-medium {{ $manage_tasks === 'I expect the worker to be experienced and take initiative.' ? 'text-[#F5B301]' : 'text-white' }}">I expect the worker to be experienced and take initiative.</span>
                                </label>
                            </div>
                            @error('manage_tasks')
                                <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                    <flux:icon.exclamation-circle class="size-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Unspoken Rules --}}
                        <div class="space-y-2 md:col-span-2">
                            <label for="unspoken_rules" class="block text-sm font-semibold text-white">
                                {{ __('Unspoken Rules (Optional)') }}
                            </label>
                            <textarea wire:model="unspoken_rules" 
                                      id="unspoken_rules" 
                                      rows="3" 
                                      class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300" 
                                      placeholder="{{ __('Any house rules or expectations...') }}"></textarea>
                            @error('unspoken_rules')
                                <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                    <flux:icon.exclamation-circle class="size-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Additional Requirements --}}
                        <div class="space-y-2 md:col-span-2">
                            <label for="additional_requirements" class="block text-sm font-semibold text-white">
                                {{ __('Additional Requirements (Optional)') }}
                            </label>
                            <textarea wire:model="additional_requirements" 
                                      id="additional_requirements" 
                                      rows="3" 
                                      class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300" 
                                      placeholder="{{ __('Any additional requirements...') }}"></textarea>
                            @error('additional_requirements') 
                                <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                    <flux:icon.exclamation-circle class="size-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Section 5: Booking Management (Admin) --}}
                <div class="bg-white/10 backdrop-blur-md rounded-2xl border border-[#F5B301]/30 shadow-2xl p-8">
                    <div class="mb-6 flex items-center gap-3 border-b border-[#F5B301]/30 pb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-xl flex items-center justify-center">
                            <flux:icon.cog-6-tooth class="size-6 text-[#512B58]" />
                        </div>
                        <h2 class="text-2xl font-bold text-white">{{ __('Booking Management') }}</h2>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label for="maid_id" class="block text-sm font-semibold text-white">
                                {{ __('Assigned Maid') }}
                            </label>
                            <select wire:model="maid_id" 
                                    id="maid_id"
                                    class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white transition-all duration-300">
                                <option value="" class="bg-[#512B58] text-white">{{ __('No maid assigned') }}</option>
                                @foreach($maids as $maid)
                                    <option value="{{ $maid->id }}" class="bg-[#512B58] text-white">
                                        {{ $maid->full_name }} - {{ ucfirst($maid->status) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('maid_id') 
                                <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                    <flux:icon.exclamation-circle class="size-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="status" class="block text-sm font-semibold text-white">
                                {{ __('Status') }} <span class="text-[#F5B301]">*</span>
                            </label>
                            <select wire:model="status" 
                                    id="status"
                                    class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white transition-all duration-300">
                                <option value="pending" class="bg-[#512B58] text-white">{{ __('Pending') }}</option>
                                <option value="confirmed" class="bg-[#512B58] text-white">{{ __('Confirmed') }}</option>
                                <option value="active" class="bg-[#512B58] text-white">{{ __('Active') }}</option>
                                <option value="completed" class="bg-[#512B58] text-white">{{ __('Completed') }}</option>
                                <option value="cancelled" class="bg-[#512B58] text-white">{{ __('Cancelled') }}</option>
                            </select>
                            @error('status') 
                                <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                    <flux:icon.exclamation-circle class="size-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="space-y-2 md:col-span-2">
                            <label for="notes" class="block text-sm font-semibold text-white">
                                {{ __('Admin Notes') }}
                            </label>
                            <textarea wire:model="notes" 
                                      id="notes"
                                      rows="3" 
                                      class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300" 
                                      placeholder="{{ __('Internal notes...') }}"></textarea>
                            @error('notes') 
                                <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                    <flux:icon.exclamation-circle class="size-4" />
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-end gap-3 pt-6">
                    <flux:button as="a" 
                                 :href="route($prefix . 'bookings.show', $booking)" 
                                 variant="outline"
                                 class="border-white/30 text-white hover:bg-white/10">
                        {{ __('Cancel') }}
                    </flux:button>
                    <button type="submit" 
                            wire:loading.attr="disabled"
                            class="flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-[#F5B301] to-[#FFD700] text-[#512B58] font-bold rounded-xl hover:from-[#FFD700] hover:to-[#F5B301] transition-all duration-300 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="update">{{ __('Update Booking') }}</span>
                        <span wire:loading wire:target="update" class="flex items-center gap-2">
                            <flux:icon.arrow-path class="size-4 animate-spin" />
                            {{ __('Updating...') }}
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
