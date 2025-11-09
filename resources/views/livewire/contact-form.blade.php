<div>
    <!-- Success Message -->
    @if($showSuccessMessage)
        <div class="mb-6 p-4 bg-green-500/20 border border-green-500/30 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="text-green-400 font-medium">Thank you for your inquiry! We'll get back to you within 24 hours.</p>
            </div>
        </div>
    @endif

    <!-- Error Message -->
    @if($showErrorMessage)
        <div class="mb-6 p-4 bg-red-500/20 border border-red-500/30 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <p class="text-red-400 font-medium">{{ $errorMessage }}</p>
            </div>
        </div>
    @endif

    <form wire:submit.prevent="submit" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-medium text-[#D1C4E9] mb-2 font-['Roboto_Condensed'] tracking-wide">Full Name *</label>
                <input type="text" id="name" wire:model="name" required
                       class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 hover:bg-white/15 rounded-lg focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300 @error('name') border-red-500 @enderror"
                       placeholder="Your full name">
                @error('name') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label for="phone" class="block text-sm font-medium text-[#D1C4E9] mb-2 font-['Roboto_Condensed'] tracking-wide">Phone Number *</label>
                <input type="tel" id="phone" wire:model="phone" required
                       class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 hover:bg-white/15 rounded-lg focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300 @error('phone') border-red-500 @enderror"
                       placeholder="+256 703 173206">
                @error('phone') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
        
        <div>
            <label for="email" class="block text-sm font-medium text-[#D1C4E9] mb-2 font-['Roboto_Condensed'] tracking-wide">Email Address *</label>
            <input type="email" id="email" wire:model="email" required
                   class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 hover:bg-white/15 rounded-lg focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300 @error('email') border-red-500 @enderror"
                   placeholder="your.email@example.com">
            @error('email') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        
        <div>
            <label for="service" class="block text-sm font-medium text-[#D1C4E9] mb-2 font-['Roboto_Condensed'] tracking-wide">Service Interest *</label>
            <select id="service" wire:model="service" required
                    class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 hover:bg-white/15 rounded-lg focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white transition-all duration-300 @error('service') border-red-500 @enderror">
                <option value="" class="bg-[#512B58] text-white">Select a service</option>
                <option value="maidservant" class="bg-[#512B58] text-white">Professional Maidservant</option>
                <option value="home-manager" class="bg-[#512B58] text-white">Home Manager</option>
                <option value="bedside-nurse" class="bg-[#512B58] text-white">Bedside Nurse</option>
                <option value="elderly-care" class="bg-[#512B58] text-white">Elderly Caretaker</option>
                <option value="nanny" class="bg-[#512B58] text-white">Nanny Services</option>
                <option value="temporary" class="bg-[#512B58] text-white">Temporary Maid</option>
                <option value="stay-out" class="bg-[#512B58] text-white">Stay-out Maid</option>
                <option value="fast-response" class="bg-[#512B58] text-white">Fast Response Service</option>
            </select>
            @error('service') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        
        <div>
            <label for="family-size" class="block text-sm font-medium text-[#D1C4E9] mb-2 font-['Roboto_Condensed'] tracking-wide">Family Size</label>
            <select id="family-size" wire:model="familySize"
                    class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 hover:bg-white/15 rounded-lg focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white transition-all duration-300">
                <option value="" class="bg-[#512B58] text-white">Select family size</option>
                <option value="1-2" class="bg-[#512B58] text-white">1-2 members</option>
                <option value="3-4" class="bg-[#512B58] text-white">3-4 members</option>
                <option value="5-6" class="bg-[#512B58] text-white">5-6 members</option>
                <option value="7+" class="bg-[#512B58] text-white">7+ members</option>
            </select>
        </div>
        
        <div>
            <label for="message" class="block text-sm font-medium text-[#D1C4E9] mb-2 font-['Roboto_Condensed'] tracking-wide">Message</label>
            <textarea id="message" wire:model="message" rows="4"
                      class="w-full px-4 py-3 bg-white/10 border border-[#F5B301]/30 hover:bg-white/15 rounded-lg focus:ring-2 focus:ring-[#F5B301] focus:border-transparent text-white placeholder-[#D1C4E9]/60 transition-all duration-300 resize-none @error('message') border-red-500 @enderror"
                      placeholder="Tell us about your specific needs, preferred schedule, and any special requirements..."></textarea>
            @error('message') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
</div>

        <div class="flex items-start gap-3">
            <input type="checkbox" id="privacy" wire:model="privacy" required
                   class="mt-1 w-4 h-4 text-[#F5B301] bg-white/10 border-[#F5B301]/30 rounded focus:ring-[#F5B301] focus:ring-2 @error('privacy') border-red-500 @enderror">
            <label for="privacy" class="text-sm text-[#D1C4E9] leading-relaxed">
                I agree to the <a href="{{ route('privacy.public') }}" class="text-[#F5B301] hover:text-[#FFD700] transition-colors">Privacy Policy</a> and consent to being contacted by Royal Maids Hub regarding my inquiry.
            </label>
        </div>
        @error('privacy') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        
        <button type="submit" 
                @disabled($isSubmitting)
                class="w-full bg-gradient-to-r from-[#F5B301] to-[#FFD700] text-white font-['Roboto_Condensed'] tracking-wide py-4 px-6 rounded-lg font-bold hover:from-[#FFD700] hover:to-[#F5B301] transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex items-center justify-center gap-3 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
            @if($isSubmitting)
                <svg class="animate-spin w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Sending Request...
            @else
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
                Send Request
            @endif
        </button>
    </form>
</div>