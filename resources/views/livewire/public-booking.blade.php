<div class="min-h-screen bg-gradient-to-br from-[#512B58] via-[#3B0A45] to-[#2D1B69]">
    <!-- Header -->
    <section class="relative py-16 lg:py-24 overflow-hidden"
             style="background-image: url('{{ asset('storage/web-site-images/hero.jpg') }}'); background-size: cover; background-position: center; background-attachment: fixed;">
        <div class="absolute inset-0 bg-gradient-to-r from-[#512B58]/85 to-[#3B0A45]/85"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-5xl mx-auto text-center">
                <h1 class="text-4xl lg:text-6xl font-bold text-white mb-4">Book Your Perfect Maid</h1>
                <p class="text-lg lg:text-xl text-[#D1C4E9] max-w-3xl mx-auto">
                    Complete this 4-step form to find your ideal domestic worker
                </p>
            </div>
        </div>
    </section>

    <!-- Booking Wizard Section -->
    <section class="py-20 bg-gradient-to-br from-[#512B58] via-[#3B0A45] to-[#2D1B69]">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                
                <form wire:submit.prevent="submitBooking">
                <!-- Progress Steps -->
                <div class="mb-12">
                    <div class="flex items-center justify-center space-x-4">
                        <!-- Step 1 -->
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold text-lg {{ $currentStep >= 1 ? 'bg-gradient-to-r from-[#F5B301] to-[#FFD700] text-[#512B58]' : 'bg-white/20 border-2 border-white/30' }}">
                                1
                            </div>
                            <span class="ml-3 text-white font-['Roboto_Condensed'] tracking-wide {{ $currentStep >= 1 ? 'text-[#F5B301]' : 'text-white/70' }}">Contact Info</span>
                        </div>
                        <div class="w-16 h-0.5 {{ $currentStep >= 2 ? 'bg-gradient-to-r from-[#F5B301] to-[#FFD700]' : 'bg-white/20' }}"></div>
                        <!-- Step 2 -->
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold text-lg {{ $currentStep >= 2 ? 'bg-gradient-to-r from-[#F5B301] to-[#FFD700] text-[#512B58]' : 'bg-white/20 border-2 border-white/30' }}">
                                2
                            </div>
                            <span class="ml-3 text-white font-['Roboto_Condensed'] tracking-wide {{ $currentStep >= 2 ? 'text-[#F5B301]' : 'text-white/70' }}">Home Details</span>
                        </div>
                        <div class="w-16 h-0.5 {{ $currentStep >= 3 ? 'bg-gradient-to-r from-[#F5B301] to-[#FFD700]' : 'bg-white/20' }}"></div>
                        <!-- Step 3 -->
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold text-lg {{ $currentStep >= 3 ? 'bg-gradient-to-r from-[#F5B301] to-[#FFD700] text-[#512B58]' : 'bg-white/20 border-2 border-white/30' }}">
                                3
                            </div>
                            <span class="ml-3 text-white font-['Roboto_Condensed'] tracking-wide {{ $currentStep >= 3 ? 'text-[#F5B301]' : 'text-white/70' }}">Household</span>
                        </div>
                        <div class="w-16 h-0.5 {{ $currentStep >= 4 ? 'bg-gradient-to-r from-[#F5B301] to-[#FFD700]' : 'bg-white/20' }}"></div>
                        <!-- Step 4 -->
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold text-lg {{ $currentStep >= 4 ? 'bg-gradient-to-r from-[#F5B301] to-[#FFD700] text-[#512B58]' : 'bg-white/20 border-2 border-white/30' }}">
                                4
                            </div>
                            <span class="ml-3 text-white font-['Roboto_Condensed'] tracking-wide {{ $currentStep >= 4 ? 'text-[#F5B301]' : 'text-white/70' }}">Expectations</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white/10 backdrop-blur-md rounded-2xl border border-[#F5B301]/30 shadow-2xl p-8">
                    @if($maid_id)
                        <div class="mb-6 p-4 rounded-xl bg-white/10 border border-[#F5B301]/30 text-white flex items-center justify-between">
                            <div class="text-sm">You are booking for Maid ID: <span class="font-semibold">{{ $maid_id }}</span></div>
                            <button type="button" wire:click="$set('maid_id','')" class="text-xs px-3 py-1 rounded-lg bg-white/20 hover:bg-white/30">Change</button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="mb-6 rounded-xl bg-red-500/10 border border-red-500/30 text-red-200 p-4">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if(session('error_detail'))
                        <div class="mb-6 rounded-xl bg-red-500/10 border border-red-500/30 text-red-200 p-4">
                            {{ session('error_detail') }}
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="mb-6 rounded-xl bg-red-500/10 border border-red-500/30 text-red-200 p-4">
                            <ul class="list-disc list-inside text-sm">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <!-- Step Wizards Logic -->
                    @if($currentStep == 1)
                        <div class="text-center mb-8">
                            <div class="w-16 h-16 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <h2 class="text-3xl font-bold text-white mb-2 font-['Roboto_Condensed'] tracking-wide">Contact Information</h2>
                            <p class="text-[#D1C4E9] font-['Roboto_Condensed'] tracking-wide">Tell us about yourself so we can reach you</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="full_name" class="block text-sm font-semibold text-white font-['Roboto_Condensed'] tracking-wide">
                                    Full Name <span class="text-[#F5B301]">*</span>
                                </label>
                                <input type="text" 
                                       wire:model="full_name" name="full_name"
                                       id="full_name" 
                                       class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300"
                                       placeholder="Enter your full name">
                                @error('full_name') 
                                    <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="phone" class="block text-sm font-semibold text-white font-['Roboto_Condensed'] tracking-wide">
                                    Phone Number (WhatsApp Preferred) <span class="text-[#F5B301]">*</span>
                                </label>
                                <input type="tel" 
                                       wire:model="phone" name="phone"
                                       id="phone" 
                                       class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300"
                                       placeholder="+256-7XX-XXX-XXX">
                                @error('phone') 
                                    <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="space-y-2 md:col-span-2">
                                <label for="email" class="block text-sm font-semibold text-white font-['Roboto_Condensed'] tracking-wide">
                                    Email Address <span class="text-[#F5B301]">*</span>
                                </label>
                                <input type="email" 
                                       wire:model="email" name="email"
                                       id="email" 
                                       class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300"
                                       placeholder="your.email@example.com">
                                @error('email') 
                                    <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="country" class="block text-sm font-semibold text-white font-['Roboto_Condensed'] tracking-wide">
                                    Country <span class="text-[#F5B301]">*</span>
                                </label>
                                <input type="text" 
                                       wire:model="country" name="country"
                                       id="country" 
                                       class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300"
                                       placeholder="Uganda">
                                @error('country') 
                                    <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="city" class="block text-sm font-semibold text-white font-['Roboto_Condensed'] tracking-wide">
                                    City <span class="text-[#F5B301]">*</span>
                                </label>
                                <input type="text" 
                                       wire:model="city" name="city"
                                       id="city" 
                                       class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300"
                                       placeholder="e.g., Kampala">
                                @error('city') 
                                    <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="division" class="block text-sm font-semibold text-white font-['Roboto_Condensed'] tracking-wide">
                                    Division <span class="text-[#F5B301]">*</span>
                                </label>
                                <input type="text" 
                                       wire:model="division" name="division"
                                       id="division" 
                                       class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300"
                                       placeholder="e.g., Makindye">
                                @error('division') 
                                    <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="parish" class="block text-sm font-semibold text-white font-['Roboto_Condensed'] tracking-wide">
                                    Parish <span class="text-[#F5B301]">*</span>
                                </label>
                                <input type="text" 
                                       wire:model="parish" name="parish"
                                       id="parish" 
                                       class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300"
                                       placeholder="e.g., Bukoto">
                                @error('parish') 
                                    <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="space-y-2 md:col-span-2">
                                <label for="national_id" class="block text-sm font-semibold text-white font-['Roboto_Condensed'] tracking-wide">
                                    National ID / Passport <span class="text-[#F5B301]">*</span>
                                </label>
                                <div class="flex items-start gap-4">
                                    @if ($national_id)
                                        <div class="flex-shrink-0">
                                            <div class="relative">
                                                @if(in_array($national_id->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                                    <img src="{{ $national_id->temporaryUrl() }}" 
                                                         class="w-32 h-32 object-cover rounded-xl border-2 border-[#F5B301]/30 shadow-lg"
                                                         alt="ID Preview">
                                                @else
                                                    <div class="w-32 h-32 rounded-xl border-2 border-[#F5B301]/30 shadow-lg bg-white/10 flex items-center justify-center">
                                                        <svg class="w-12 h-12 text-[#F5B301]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                                <button type="button" 
                                                        wire:click="$set('national_id', null)"
                                                        class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1.5 shadow-lg transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                </button>
                                            </div>
                                            <p class="text-xs text-center text-[#D1C4E9]/60 mt-2">
                                                {{ number_format($national_id->getSize() / 1024, 2) }} KB
                                            </p>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <input type="file" 
                                               wire:model="national_id" name="national_id"
                                               id="national_id" 
                                               accept="application/pdf,image/*" 
                                               class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white transition-all duration-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#F5B301] file:text-[#512B58] hover:file:bg-[#FFD700]">
                                        <p class="text-xs text-[#D1C4E9]/60 mt-2">
                                            Max file size: 2MB • Formats: PDF, JPG, PNG, GIF, WebP
                                        </p>
                                        <div wire:loading wire:target="national_id" class="mt-2">
                                            <div class="flex items-center gap-2 text-[#F5B301] text-sm">
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
                                    <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    @endif

                    @if($currentStep == 2)
                        <div class="text-center mb-8">
                            <div class="w-16 h-16 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                            </div>
                            <h2 class="text-3xl font-bold text-white mb-2 font-['Roboto_Condensed'] tracking-wide">Home Details</h2>
                            <p class="text-[#D1C4E9] font-['Roboto_Condensed'] tracking-wide">Tell us about your home and living situation</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="village" class="block text-sm font-semibold text-white font-['Roboto_Condensed'] tracking-wide">
                                    Village <span class="text-[#F5B301]">*</span>
                                </label>
                                <input type="text" 
                                       wire:model="village" name="village"
                                       id="village" 
                                       class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300"
                                       placeholder="e.g., Ntinda">
                                @error('village') 
                                    <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="house_type" class="block text-sm font-semibold text-white font-['Roboto_Condensed'] tracking-wide">
                                    House Type <span class="text-[#F5B301]">*</span>
                                </label>
                                <select wire:model="house_type" name="house_type"
                                        id="house_type" 
                                        class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white transition-all duration-300">
                                    <option value="" class="bg-[#512B58] text-white">Select house type</option>
                                    <option value="Apartment" class="bg-[#512B58] text-white">Apartment</option>
                                    <option value="House" class="bg-[#512B58] text-white">House</option>
                                    <option value="Townhouse" class="bg-[#512B58] text-white">Townhouse</option>
                                    <option value="Villa" class="bg-[#512B58] text-white">Villa</option>
                                    <option value="Other" class="bg-[#512B58] text-white">Other</option>
                                </select>
                                @error('house_type') 
                                    <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="number_of_rooms" class="block text-sm font-semibold text-white font-['Roboto_Condensed'] tracking-wide">
                                    Number of Rooms <span class="text-[#F5B301]">*</span>
                                </label>
                                <input type="number" 
                                       wire:model="number_of_rooms" name="number_of_rooms"
                                       id="number_of_rooms" 
                                       min="1" 
                                       class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300"
                                       placeholder="e.g., 3">
                                @error('number_of_rooms') 
                                    <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="bedrooms" class="block text-sm font-semibold text-white font-['Roboto_Condensed'] tracking-wide">
                                    How many bedrooms are in your home? <span class="text-[#F5B301]">*</span>
                                </label>
                                <input type="number" 
                                       wire:model="bedrooms" name="bedrooms"
                                       id="bedrooms" 
                                       min="0" 
                                       class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300"
                                       placeholder="e.g., 3">
                                @error('bedrooms') 
                                    <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="bathrooms" class="block text-sm font-semibold text-white font-['Roboto_Condensed'] tracking-wide">
                                    How many bathrooms/toilets are in your home? <span class="text-[#F5B301]">*</span>
                                </label>
                                <input type="number" 
                                       wire:model="bathrooms" name="bathrooms"
                                       id="bathrooms" 
                                       min="0" 
                                       class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300"
                                       placeholder="e.g., 2">
                                @error('bathrooms') 
                                    <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="space-y-2 md:col-span-2">
                                <label class="block text-sm font-semibold text-white font-['Roboto_Condensed'] tracking-wide mb-3">
                                    Are there outdoor responsibilities? (Check all that apply)
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
                                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
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
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="space-y-2 md:col-span-2">
                                <label class="block text-sm font-semibold text-white font-['Roboto_Condensed'] tracking-wide mb-3">
                                    Which appliances will the domestic worker use? (Check all that apply)
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
                                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
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
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="space-y-2 md:col-span-2">
                                <label for="special_requirements" class="block text-sm font-semibold text-white font-['Roboto_Condensed'] tracking-wide">
                                    Special Requirements (Optional)
                                </label>
                                <textarea wire:model="special_requirements" name="special_requirements"
                                          id="special_requirements" 
                                          rows="3" 
                                          class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300"
                                          placeholder="Any special requirements for your maid..."></textarea>
                                @error('special_requirements') 
                                    <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    @endif

                    @if($currentStep == 3)
                        <div class="text-center mb-8">
                            <div class="w-16 h-16 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <h2 class="text-3xl font-bold text-white mb-2 font-['Roboto_Condensed'] tracking-wide">Household Information</h2>
                            <p class="text-[#D1C4E9] font-['Roboto_Condensed'] tracking-wide">Tell us about your family and household needs</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="family_size" class="block text-sm font-semibold text-white font-['Roboto_Condensed'] tracking-wide">
                                    Family Size <span class="text-[#F5B301]">*</span>
                                </label>
                                <select wire:model="family_size" name="family_size"
                                        id="family_size" 
                                        class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white transition-all duration-300">
                                    <option value="" class="bg-[#512B58] text-white">Select family size</option>
                                    <option value="1-2" class="bg-[#512B58] text-white">1-2 people</option>
                                    <option value="3-4" class="bg-[#512B58] text-white">3-4 people</option>
                                    <option value="5-6" class="bg-[#512B58] text-white">5-6 people</option>
                                    <option value="7+" class="bg-[#512B58] text-white">7+ people</option>
                                </select>
                                @error('family_size') 
                                    <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="children_count" class="block text-sm font-semibold text-white font-['Roboto_Condensed'] tracking-wide">
                                    Number of Children
                                </label>
                                <input type="number" 
                                       wire:model="children_count" name="children_count"
                                       id="children_count" 
                                       min="0" 
                                       class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300"
                                       placeholder="0">
                                @error('children_count') 
                                    <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="elderly_count" class="block text-sm font-semibold text-white font-['Roboto_Condensed'] tracking-wide">
                                    Number of Elderly (65+)
                                </label>
                                <input type="number" 
                                       wire:model="elderly_count" name="elderly_count"
                                       id="elderly_count" 
                                       min="0" 
                                       class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300"
                                       placeholder="0">
                                @error('elderly_count') 
                                    <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="pets" class="block text-sm font-semibold text-white font-['Roboto_Condensed'] tracking-wide">
                                    Pets
                                </label>
                                <select wire:model="pets" name="pets"
                                        id="pets" 
                                        class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white transition-all duration-300">
                                    <option value="none" class="bg-[#512B58] text-white">No pets</option>
                                    <option value="dogs" class="bg-[#512B58] text-white">Dogs</option>
                                    <option value="cats" class="bg-[#512B58] text-white">Cats</option>
                                    <option value="both" class="bg-[#512B58] text-white">Dogs & Cats</option>
                                    <option value="other" class="bg-[#512B58] text-white">Other pets</option>
                                </select>
                                @error('pets') 
                                    <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="space-y-2 md:col-span-2">
                                <label for="special_needs" class="block text-sm font-semibold text-white font-['Roboto_Condensed'] tracking-wide">
                                    Special Needs or Requirements (Optional)
                                </label>
                                <textarea wire:model="special_needs" name="special_needs"
                                          id="special_needs" 
                                          rows="3" 
                                          class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300"
                                          placeholder="Any special needs or requirements..."></textarea>
                                @error('special_needs') 
                                    <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    @endif

                    @if($currentStep == 4)
                        <div class="text-center mb-8">
                            <div class="w-16 h-16 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h2 class="text-3xl font-bold text-white mb-2 font-['Roboto_Condensed'] tracking-wide">Service Expectations</h2>
                            <p class="text-[#D1C4E9] font-['Roboto_Condensed'] tracking-wide">Choose your service package and preferences</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2 md:col-span-2">
                                <label class="block text-sm font-semibold text-white font-['Roboto_Condensed'] tracking-wide mb-3">
                                    Select Service Package <span class="text-[#F5B301]">*</span>
                                </label>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    @foreach($packages as $package)
                                        <label class="relative flex flex-col p-5 border-2 rounded-xl cursor-pointer transition-all duration-200
                                                     {{ $package_id == $package->id ? 'border-[#F5B301] bg-[#F5B301]/10 shadow-lg' : 'border-white/20 hover:border-[#F5B301]/50' }}">
                                            <input type="radio" 
                                                   wire:model.live="package_id" name="package_id"
                                                   value="{{ $package->id }}" 
                                                   class="sr-only">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-lg font-bold {{ $package_id == $package->id ? 'text-[#F5B301]' : 'text-white' }}">
                                                    {{ $package->name }}
                                                </span>
                                                @if($package_id == $package->id)
                                                    <svg class="w-6 h-6 text-[#F5B301]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                @endif
                                            </div>
                                            <p class="text-xs text-[#D1C4E9]/80">
                                                {{ Str::limit($package->description, 80) }}
                                            </p>
                                        </label>
                                    @endforeach
                                </div>
                                @error('package_id') 
                                    <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="space-y-2 md:col-span-2">
                                <label class="block text-sm font-semibold text-white font-['Roboto_Condensed'] tracking-wide mb-3">
                                    Service Mode <span class="text-[#F5B301]">*</span>
                                </label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach(['Live-in', 'Live-out'] as $mode)
                                        <label class="relative flex items-center justify-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-200
                                                     {{ $service_mode === $mode ? 'border-[#F5B301] bg-[#F5B301]/10' : 'border-white/20 hover:border-[#F5B301]/50' }}">
                                            <input type="radio" 
                                                   wire:model.live="service_mode" name="service_mode"
                                                   value="{{ $mode }}" 
                                                   class="sr-only">
                                            <span class="text-sm font-medium {{ $service_mode === $mode ? 'text-[#F5B301]' : 'text-white' }}">
                                                {{ $mode }}
                                            </span>
                                            @if($service_mode === $mode)
                                                <svg class="w-5 h-5 text-[#F5B301] absolute top-2 right-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            @endif
                                        </label>
                                    @endforeach
                                </div>
                                @error('service_mode') 
                                    <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="space-y-2 md:col-span-2">
                                <label class="block text-sm font-semibold text-white font-['Roboto_Condensed'] tracking-wide mb-3">
                                    Work Days <span class="text-[#F5B301]">*</span>
                                </label>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday', 'All Days'] as $day)
                                        <label class="relative flex items-center p-3 border-2 rounded-xl cursor-pointer transition-all duration-200
                                                     {{ in_array($day, $work_days) ? 'border-[#F5B301] bg-[#F5B301]/10' : 'border-white/20 hover:border-[#F5B301]/50' }}">
                                            <input type="checkbox" 
                                                   wire:model.live="work_days" name="work_days[]"
                                                   value="{{ $day }}" 
                                                   class="sr-only">
                                            <div class="flex items-center gap-2 w-full">
                                                <div class="w-5 h-5 rounded border-2 flex items-center justify-center transition-all
                                                          {{ in_array($day, $work_days) ? 'border-[#F5B301] bg-[#F5B301]' : 'border-white/40' }}">
                                                    @if(in_array($day, $work_days))
                                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
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
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="start_date" class="block text-sm font-semibold text-white font-['Roboto_Condensed'] tracking-wide">
                                    Preferred Start Date <span class="text-[#F5B301]">*</span>
                                </label>
                                <input type="date" 
                                       wire:model="start_date" name="start_date"
                                       id="start_date" 
                                       min="{{ date('Y-m-d') }}" 
                                       class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white transition-all duration-300">
                                @error('start_date') 
                                    <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="end_date" class="block text-sm font-semibold text-white font-['Roboto_Condensed'] tracking-wide">
                                    Preferred End Date (Optional)
                                </label>
                                <input type="date" 
                                       wire:model="end_date" name="end_date"
                                       id="end_date" 
                                       min="{{ date('Y-m-d') }}" 
                                       class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white transition-all duration-300">
                                @error('end_date') 
                                    <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="additional_requirements" class="block text-sm font-semibold text-white font-['Roboto_Condensed'] tracking-wide">
                                    Additional Requirements (Optional)
                                </label>
                                <textarea wire:model="additional_requirements" name="additional_requirements"
                                          id="additional_requirements" 
                                          rows="3" 
                                          class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300"
                                          placeholder="Any additional requirements..."></textarea>
                                @error('additional_requirements') 
                                    <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Working Hours (Optional) -->
                            <div class="space-y-2 md:col-span-2">
                                <label for="working_hours" class="block text-sm font-semibold text-white font-['Roboto_Condensed'] tracking-wide">
                                    Working Hours (Optional)
                                </label>
                                <input type="text"
                                       wire:model="working_hours"
                                       id="working_hours"
                                       placeholder="e.g., 8:00 AM - 5:00 PM"
                                       class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300" />
                                @error('working_hours')
                                    <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Responsibilities (Check all that apply) -->
                            <div class="space-y-2 md:col-span-2">
                                <label class="block text-sm font-semibold text-white font-['Roboto_Condensed'] tracking-wide mb-3">
                                    What are the main responsibilities? (Check all that apply)
                                </label>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                    @foreach(['General House Cleaning', 'Laundry and Ironing', 'Cooking', 'Childcare / Nanny duties', 'Grocery Shopping / Running Errands', 'Elderly Care'] as $task)
                                        <label class="relative flex items-center p-3 border-2 rounded-xl cursor-pointer transition-all duration-200 {{ in_array($task, $responsibilities) ? 'border-[#F5B301] bg-[#F5B301]/10' : 'border-white/20 hover:border-[#F5B301]/50' }}">
                                            <input type="checkbox" wire:model.live="responsibilities" name="responsibilities[]" value="{{ $task }}" class="sr-only">
                                            <div class="flex items-center gap-2 w-full">
                                                <div class="w-5 h-5 rounded border-2 flex items-center justify-center transition-all {{ in_array($task, $responsibilities) ? 'border-[#F5B301] bg-[#F5B301]' : 'border-white/40' }}">
                                                    @if(in_array($task, $responsibilities))
                                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                    @endif
                                                </div>
                                                <span class="text-sm font-medium {{ in_array($task, $responsibilities) ? 'text-[#F5B301]' : 'text-white' }}">{{ $task }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                @error('responsibilities')
                                    <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Cuisine Type (if cooking is required) -->
                            @if(in_array('Cooking', $responsibilities))
                            <div class="space-y-2 md:col-span-2">
                                <label class="block text-sm font-semibold text-white font-['Roboto_Condensed'] tracking-wide mb-3">
                                    If cooking is required, what kind of cuisine?
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
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            @endif

                            <!-- Atmosphere -->
                            <div class="space-y-2 md:col-span-2">
                                <label class="block text-sm font-semibold text-white font-['Roboto_Condensed'] tracking-wide mb-3">
                                    How would you describe your household\'s atmosphere?
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
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Manage Tasks -->
                            <div class="space-y-2 md:col-span-2">
                                <label class="block text-sm font-semibold text-white font-['Roboto_Condensed'] tracking-wide mb-3">
                                    How do you prefer to manage tasks?
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
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            

                            <!-- Unspoken Rules (Optional) -->
                            <div class="space-y-2 md:col-span-2">
                                <label for="unspoken_rules" class="block text-sm font-semibold text-white font-['Roboto_Condensed'] tracking-wide">
                                    Unspoken Rules (Optional)
                                </label>
                                <textarea wire:model="unspoken_rules" id="unspoken_rules" rows="3" class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300" placeholder="Any house rules or expectations..."></textarea>
                                @error('unspoken_rules')
                                    <div class="flex items-center gap-2 text-red-400 text-sm mt-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    @endif

                    <!-- Privacy Note -->
                    @if($currentStep == $totalSteps)
                    <div class="mt-6 text-center text-sm text-[#D1C4E9]">
                        By submitting, you agree to our
                        <a href="{{ route('privacy.public') }}" class="text-[#F5D06A] underline hover:no-underline">Privacy Policy</a>.
                    </div>
                    @endif

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between items-center mt-12 pt-8 border-t border-white/20">
                        @if($currentStep > 1)
                            <button type="button"
                                    wire:click="previousStep"
                                    class="flex items-center gap-2 px-6 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl text-white font-semibold hover:bg-white/20 transition-all duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                                Previous
                            </button>
                        @else
                            <div></div>
                        @endif

                        @if($currentStep < $totalSteps)
                            <button type="button"
                                    wire:click="nextStep"
                                    wire:loading.attr="disabled"
                                    class="flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-[#F5B301] to-[#FFD700] text-[#512B58] font-bold rounded-xl hover:from-[#FFD700] hover:to-[#F5B301] transition-all duration-300 shadow-lg hover:shadow-xl">
                                Next Step
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        @else
                            <button type="submit"
                                    wire:click="submitBooking"
                                    wire:loading.attr="disabled"
                                    class="flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-[#F5B301] to-[#FFD700] text-[#512B58] font-bold rounded-xl hover:from-[#FFD700] hover:to-[#F5B301] transition-all duration-300 shadow-lg hover:shadow-xl">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Submit Booking Request
                                <span wire:loading wire:target="submitBooking" class="ml-2 text-xs opacity-80">Submitting...</span>
                            </button>
                        @endif
                    </div>
                </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Success Message Section -->
    @if($showSuccessMessage || session('booking_submitted'))
        <section class="py-20 bg-gradient-to-br from-[#512B58] via-[#3B0A45] to-[#2D1B69]">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto text-center">
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl border border-[#F5B301]/30 shadow-2xl p-12">
                        <!-- Success Icon -->
                        <div class="w-24 h-24 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full flex items-center justify-center mx-auto mb-8">
                            <svg class="w-12 h-12 text-[#512B58]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <!-- Success Message -->
                        <h2 class="text-4xl font-bold text-white mb-4 font-['Roboto_Condensed'] tracking-wide">
                            Booking Request Submitted Successfully!
                        </h2>
                        <p class="text-xl text-[#D1C4E9] mb-8 font-['Roboto_Condensed'] tracking-wide">
                            Thank you for choosing Royal Maids Hub. We'll contact you within 24 hours to discuss your requirements.
                        </p>
                        <!-- What Happens Next -->
                        <div class="bg-white/5 rounded-xl p-6 mb-8">
                            <h3 class="text-2xl font-bold text-white mb-6 font-['Roboto_Condensed'] tracking-wide">
                                What Happens Next?
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="text-center">
                                    <div class="w-16 h-16 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full flex items-center justify-center mx-auto mb-4">
                                        <span class="text-2xl font-bold text-[#512B58]">1</span>
                                    </div>
                                    <h4 class="text-lg font-semibold text-white mb-2 font-['Roboto_Condensed'] tracking-wide">Review</h4>
                                    <p class="text-[#D1C4E9] text-sm">We'll review your requirements and match you with suitable maids</p>
                                </div>
                                <div class="text-center">
                                    <div class="w-16 h-16 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full flex items-center justify-center mx-auto mb-4">
                                        <span class="text-2xl font-bold text-[#512B58]">2</span>
                                    </div>
                                    <h4 class="text-lg font-semibold text-white mb-2 font-['Roboto_Condensed'] tracking-wide">Contact</h4>
                                    <p class="text-[#D1C4E9] text-sm">We'll call you within 24 hours to discuss your needs</p>
                                </div>
                                <div class="text-center">
                                    <div class="w-16 h-16 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full flex items-center justify-center mx-auto mb-4">
                                        <span class="text-2xl font-bold text-[#512B58]">3</span>
                                    </div>
                                    <h4 class="text-lg font-semibold text-white mb-2 font-['Roboto_Condensed'] tracking-wide">Match</h4>
                                    <p class="text-[#D1C4E9] text-sm">We'll arrange interviews with pre-screened maids</p>
                                </div>
                            </div>
                        </div>
                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="{{ route('home') }}"
                               class="px-8 py-3 bg-white/10 border border-[#F5B301]/30 rounded-xl text-white font-semibold hover:bg-white/20 transition-all duration-300">
                                Return Home
                            </a>
                            <a href="{{ route('maids.public') }}"
                               class="px-8 py-3 bg-gradient-to-r from-[#F5B301] to-[#FFD700] text-[#512B58] font-bold rounded-xl hover:from-[#FFD700] hover:to-[#F5B301] transition-all duration-300">
                                View Our Maids
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Submission Success Modal -->
    @if($showSuccessMessage)
        <div class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="absolute inset-0 bg-black/60"></div>
            <div class="relative z-10 w-full max-w-lg mx-auto bg-white rounded-2xl shadow-2xl p-8 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-br from-[#F5B301] to-[#FFD700] flex items-center justify-center">
                    <svg class="w-10 h-10 text-[#512B58]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-[#2D1B69] mb-2">Booking Submitted</h3>
                <p class="text-neutral-600 mb-6">Thank you! We received your request. We'll contact you within 24 hours.</p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ route('home') }}" class="px-6 py-3 rounded-xl bg-neutral-100 hover:bg-neutral-200 text-neutral-800 font-semibold transition">Go Home</a>
                    <a href="{{ route('maids.public') }}" class="px-6 py-3 rounded-xl bg-gradient-to-r from-[#F5B301] to-[#FFD700] text-[#512B58] font-bold hover:from-[#FFD700] hover:to-[#F5B301] transition">Browse Maids</a>
                </div>
            </div>
        </div>
    @endif
</div>
