@extends('components.layouts.simple')

@section('title', 'Premium Domestic Services')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#512B58] via-[#3B0A45] to-[#2D1B69]">
    <!-- Hero Section -->
    <section class="relative py-20 lg:py-32 overflow-hidden" 
             style="background-image: url('/storage/web-site-images/hero.jpg'); background-size: cover; background-position: center; background-attachment: fixed;">
        <div class="absolute inset-0 bg-gradient-to-r from-[#512B58]/85 to-[#3B0A45]/85"></div>
        
        <!-- Floating Elements -->
        <div class="absolute top-20 left-10 w-20 h-20 bg-[#F5B301]/20 rounded-full animate-pulse"></div>
        <div class="absolute top-40 right-20 w-16 h-16 bg-[#FFD700]/30 rounded-full animate-bounce"></div>
        <div class="absolute bottom-20 left-20 w-12 h-12 bg-[#F5B301]/25 rounded-full animate-pulse"></div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-6xl mx-auto text-center">
                <!-- Logo -->
                <div class="mb-8">
                    <div class="w-24 h-24 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full mx-auto flex items-center justify-center mb-6 shadow-2xl animate-pulse">
                        <svg class="w-12 h-12 text-white font-['Roboto_Condensed'] tracking-wide" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <h1 class="text-6xl lg:text-8xl font-bold text-white mb-4">
                        Royal <span class="text-[#F5B301]">Maids</span> Hub
                    </h1>
                    <div class="w-32 h-1 bg-gradient-to-r from-[#F5B301] to-[#FFD700] mx-auto rounded-full"></div>
                </div>
                
                <!-- Hero Content -->
                <div>
                    <h2 class="text-3xl lg:text-5xl font-semibold text-[#D1C4E9] mb-6 leading-tight">
                        Transform Your Home with 
                        <span class="text-[#F5B301] font-bold">Royal-Quality</span> Service
                    </h2>
                    <p class="text-xl lg:text-2xl text-[#D1C4E9] mb-8 max-w-4xl mx-auto leading-relaxed">
                        Experience the luxury of having professionally trained, trustworthy domestic staff who treat your home like their own. 
                        <span class="text-[#F5B301] font-semibold">Join 500+ satisfied families</span> who've discovered the Royal Maids difference.
                    </p>
                </div>
                
                <!-- Trust Indicators -->
                <div class="mb-12">
                    <div class="flex flex-wrap justify-center items-center gap-8 text-[#D1C4E9]">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#F5B301]" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="font-medium">Fully Vetted & Trained</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#F5B301]" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 15l-3-3h6l-3 3z"/>
                            </svg>
                            <span class="font-medium">24/7 Support</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#F5B301]" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            <span class="font-medium">Insured & Bonded</span>
                        </div>
                    </div>
                </div>
                
                <!-- Key Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-12 max-w-4xl mx-auto">
                    <div class="bg-white/10 backdrop-blur-sm border border-[#F5B301]/30 hover:bg-white/15 rounded-xl p-6 hover:bg-white/20 transition-all duration-300 hover:scale-105">
                        <div class="text-4xl font-bold text-[#F5B301] mb-2">500+</div>
                        <div class="text-sm text-[#D1C4E9] font-medium">Happy Families</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm border border-[#F5B301]/30 hover:bg-white/15 rounded-xl p-6 hover:bg-white/20 transition-all duration-300 hover:scale-105">
                        <div class="text-4xl font-bold text-[#F5B301] mb-2">200+</div>
                        <div class="text-sm text-[#D1C4E9] font-medium">Expert Maids</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm border border-[#F5B301]/30 hover:bg-white/15 rounded-xl p-6 hover:bg-white/20 transition-all duration-300 hover:scale-105">
                        <div class="text-4xl font-bold text-[#F5B301] mb-2">5+</div>
                        <div class="text-sm text-[#D1C4E9] font-medium">Years Excellence</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm border border-[#F5B301]/30 hover:bg-white/15 rounded-xl p-6 hover:bg-white/20 transition-all duration-300 hover:scale-105">
                        <div class="text-4xl font-bold text-[#F5B301] mb-2">24/7</div>
                        <div class="text-sm text-[#D1C4E9] font-medium">Always Available</div>
                    </div>
                </div>
                
                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-12">
                    <a href="{{ route('booking.public') }}" 
                       class="group bg-gradient-to-r from-[#F5B301] to-[#FFD700] text-white font-['Roboto_Condensed'] tracking-wide px-8 py-4 rounded-xl font-bold text-lg hover:from-[#FFD700] hover:to-[#F5B301] transition-all duration-300 shadow-2xl hover:shadow-[#F5B301]/50 hover:scale-105 flex items-center gap-3">
                        <svg class="w-6 h-6 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Book Your Perfect Maid Now</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                    <a href="{{ route('maids.public') }}" 
                       class="group bg-white/20 backdrop-blur-sm text-white px-8 py-4 rounded-xl font-bold text-lg hover:bg-white/30 transition-all duration-300 border border-white/30 hover:scale-105 flex items-center gap-3">
                        <svg class="w-6 h-6 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span>Meet Our Amazing Maids</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                </div>
                
                <!-- Social Proof -->
                <div class="mt-12">
                    <p class="text-[#D1C4E9] text-sm mb-4">Trusted by families across Uganda</p>
                    <div class="flex justify-center items-center gap-8 opacity-70">
                        <div class="text-[#F5B301] font-semibold">★★★★★</div>
                        <div class="text-[#D1C4E9]">4.9/5 Rating</div>
                        <div class="text-[#D1C4E9]">•</div>
                        <div class="text-[#D1C4E9]">500+ Reviews</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="py-20 bg-gradient-to-br from-[#512B58] to-[#3B0A45]">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl lg:text-5xl font-bold text-white mb-6">SERVICES</h2>
                <h3 class="text-2xl lg:text-3xl font-semibold text-[#D1C4E9] mb-4">We Provide Service For You</h3>
                <p class="text-lg text-white/80 max-w-3xl mx-auto leading-relaxed">
                    Comprehensive domestic services tailored to meet your family's unique needs. 
                    From daily household management to specialized medical care, we provide royal-quality service.
                </p>
            </div>
            
            <!-- Services Grid - 4x2 Layout -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
                <!-- Professional Maidservants -->
                <div class="group bg-white/95 backdrop-blur-sm border-2 border-[#F5B301]/30 rounded-2xl p-6 hover:shadow-2xl hover:border-[#F5B301]/60 transition-all duration-500 hover:-translate-y-2 relative overflow-hidden">
                    <!-- Royal Crown Decoration -->
                    <div class="absolute top-3 right-3 w-6 h-6 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full flex items-center justify-center opacity-20 group-hover:opacity-40 transition-opacity duration-300">
                        <svg class="w-3 h-3 text-white font-['Roboto_Condensed'] tracking-wide" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    
                    <div class="w-16 h-16 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-3 transition-all duration-500 shadow-lg">
                        <svg class="w-8 h-8 text-white font-['Roboto_Condensed'] tracking-wide" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                </div>
                
                    <h3 class="text-lg font-bold text-[#512B58] font-['Roboto_Condensed'] tracking-wide mb-3">Maidservants</h3>
                    <p class="text-[#512B58]/80 font-['Roboto_Condensed'] tracking-wide leading-relaxed mb-4 text-sm">Professional domestic services including laundry, ironing, cleaning, grocery shopping, cooking, and pet care.</p>
                    
                    <a href="{{ route('booking.public') }}" 
                       class="w-full bg-gradient-to-r from-[#F5B301] to-[#FFD700] text-[#512B58] font-['Roboto_Condensed'] tracking-wide py-2 px-4 rounded-lg text-sm font-bold hover:from-[#FFD700] hover:to-[#F5B301] transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 text-center block">
                        Book Now
                    </a>
                </div>
                
                <!-- Home Managers -->
                <div class="group bg-white/95 backdrop-blur-sm border-2 border-[#F5B301]/30 rounded-2xl p-6 hover:shadow-2xl hover:border-[#F5B301]/60 transition-all duration-500 hover:-translate-y-2 relative overflow-hidden">
                    <!-- Royal Crown Decoration -->
                    <div class="absolute top-3 right-3 w-6 h-6 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full flex items-center justify-center opacity-20 group-hover:opacity-40 transition-opacity duration-300">
                        <svg class="w-3 h-3 text-white font-['Roboto_Condensed'] tracking-wide" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    
                    <div class="w-16 h-16 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-3 transition-all duration-500 shadow-lg">
                        <svg class="w-8 h-8 text-white font-['Roboto_Condensed'] tracking-wide" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                </div>
                
                    <h3 class="text-lg font-bold text-[#512B58] font-['Roboto_Condensed'] tracking-wide mb-3">Home Managers</h3>
                    <p class="text-[#512B58]/80 font-['Roboto_Condensed'] tracking-wide leading-relaxed mb-4 text-sm">Professionally trained experts in efficient and thorough residential property management.</p>
                    
                    <a href="{{ route('booking.public') }}" 
                       class="w-full bg-gradient-to-r from-[#F5B301] to-[#FFD700] text-[#512B58] font-['Roboto_Condensed'] tracking-wide py-2 px-4 rounded-lg text-sm font-bold hover:from-[#FFD700] hover:to-[#F5B301] transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 text-center block">
                        Book Now
                    </a>
                </div>
                
                <!-- Bedside Nurses -->
                <div class="group bg-white/95 backdrop-blur-sm border-2 border-[#F5B301]/30 rounded-2xl p-6 hover:shadow-2xl hover:border-[#F5B301]/60 transition-all duration-500 hover:-translate-y-2 relative overflow-hidden">
                    <!-- Royal Crown Decoration -->
                    <div class="absolute top-3 right-3 w-6 h-6 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full flex items-center justify-center opacity-20 group-hover:opacity-40 transition-opacity duration-300">
                        <svg class="w-3 h-3 text-white font-['Roboto_Condensed'] tracking-wide" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    
                    <div class="w-16 h-16 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-3 transition-all duration-500 shadow-lg">
                        <svg class="w-8 h-8 text-white font-['Roboto_Condensed'] tracking-wide" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                </div>
                
                    <h3 class="text-lg font-bold text-[#512B58] font-['Roboto_Condensed'] tracking-wide mb-3">Bedside Nurses</h3>
                    <p class="text-[#512B58]/80 font-['Roboto_Condensed'] tracking-wide leading-relaxed mb-4 text-sm">Professional nursing care for patients in the comfort of their homes or hospital settings.</p>
                    
                    <a href="{{ route('booking.public') }}" 
                       class="w-full bg-gradient-to-r from-[#F5B301] to-[#FFD700] text-[#512B58] font-['Roboto_Condensed'] tracking-wide py-2 px-4 rounded-lg text-sm font-bold hover:from-[#FFD700] hover:to-[#F5B301] transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 text-center block">
                        Book Now
                    </a>
                </div>
                
                <!-- Fast Response -->
                <div class="group bg-white/95 backdrop-blur-sm border-2 border-[#F5B301]/30 rounded-2xl p-6 hover:shadow-2xl hover:border-[#F5B301]/60 transition-all duration-500 hover:-translate-y-2 relative overflow-hidden">
                    <!-- Royal Crown Decoration -->
                    <div class="absolute top-3 right-3 w-6 h-6 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full flex items-center justify-center opacity-20 group-hover:opacity-40 transition-opacity duration-300">
                        <svg class="w-3 h-3 text-white font-['Roboto_Condensed'] tracking-wide" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    
                    <div class="w-16 h-16 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-3 transition-all duration-500 shadow-lg">
                        <svg class="w-8 h-8 text-white font-['Roboto_Condensed'] tracking-wide" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                </div>
                
                    <h3 class="text-lg font-bold text-[#512B58] font-['Roboto_Condensed'] tracking-wide mb-3">Fast Response</h3>
                    <p class="text-[#512B58]/80 font-['Roboto_Condensed'] tracking-wide leading-relaxed mb-4 text-sm">Quick and efficient response services for any domestic emergencies or incidents.</p>
                    
                    <a href="{{ route('booking.public') }}" 
                       class="w-full bg-gradient-to-r from-[#F5B301] to-[#FFD700] text-[#512B58] font-['Roboto_Condensed'] tracking-wide py-2 px-4 rounded-lg text-sm font-bold hover:from-[#FFD700] hover:to-[#F5B301] transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 text-center block">
                        Book Now
                    </a>
                </div>
                
                <!-- Elderly Caretakers -->
                <div class="group bg-white/95 backdrop-blur-sm border-2 border-[#F5B301]/30 rounded-2xl p-6 hover:shadow-2xl hover:border-[#F5B301]/60 transition-all duration-500 hover:-translate-y-2 relative overflow-hidden">
                    <!-- Royal Crown Decoration -->
                    <div class="absolute top-3 right-3 w-6 h-6 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full flex items-center justify-center opacity-20 group-hover:opacity-40 transition-opacity duration-300">
                        <svg class="w-3 h-3 text-white font-['Roboto_Condensed'] tracking-wide" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    
                    <div class="w-16 h-16 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-3 transition-all duration-500 shadow-lg">
                        <svg class="w-8 h-8 text-white font-['Roboto_Condensed'] tracking-wide" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                </div>
                
                    <h3 class="text-lg font-bold text-[#512B58] font-['Roboto_Condensed'] tracking-wide mb-3">Elderly Caretakers</h3>
                    <p class="text-[#512B58]/80 font-['Roboto_Condensed'] tracking-wide leading-relaxed mb-4 text-sm">Specialized care services for elderly individuals, ensuring their comfort and well-being.</p>
                    
                    <a href="{{ route('booking.public') }}" 
                       class="w-full bg-gradient-to-r from-[#F5B301] to-[#FFD700] text-[#512B58] font-['Roboto_Condensed'] tracking-wide py-2 px-4 rounded-lg text-sm font-bold hover:from-[#FFD700] hover:to-[#F5B301] transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 text-center block">
                        Book Now
                    </a>
                </div>
                
                <!-- Temporary Maids -->
                <div class="group bg-white/95 backdrop-blur-sm border-2 border-[#F5B301]/30 rounded-2xl p-6 hover:shadow-2xl hover:border-[#F5B301]/60 transition-all duration-500 hover:-translate-y-2 relative overflow-hidden">
                    <!-- Royal Crown Decoration -->
                    <div class="absolute top-3 right-3 w-6 h-6 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full flex items-center justify-center opacity-20 group-hover:opacity-40 transition-opacity duration-300">
                        <svg class="w-3 h-3 text-white font-['Roboto_Condensed'] tracking-wide" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    
                    <div class="w-16 h-16 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-3 transition-all duration-500 shadow-lg">
                        <svg class="w-8 h-8 text-white font-['Roboto_Condensed'] tracking-wide" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                </div>
                
                    <h3 class="text-lg font-bold text-[#512B58] font-['Roboto_Condensed'] tracking-wide mb-3">Temporary Maids</h3>
                    <p class="text-[#512B58]/80 font-['Roboto_Condensed'] tracking-wide leading-relaxed mb-4 text-sm">Professional temporary domestic services for short-term assistance and specific needs.</p>
                    
                    <a href="{{ route('booking.public') }}" 
                       class="w-full bg-gradient-to-r from-[#F5B301] to-[#FFD700] text-[#512B58] font-['Roboto_Condensed'] tracking-wide py-2 px-4 rounded-lg text-sm font-bold hover:from-[#FFD700] hover:to-[#F5B301] transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 text-center block">
                        Book Now
                    </a>
                </div>
                
                <!-- Stay-out Maids -->
                <div class="group bg-white/95 backdrop-blur-sm border-2 border-[#F5B301]/30 rounded-2xl p-6 hover:shadow-2xl hover:border-[#F5B301]/60 transition-all duration-500 hover:-translate-y-2 relative overflow-hidden">
                    <!-- Royal Crown Decoration -->
                    <div class="absolute top-3 right-3 w-6 h-6 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full flex items-center justify-center opacity-20 group-hover:opacity-40 transition-opacity duration-300">
                        <svg class="w-3 h-3 text-white font-['Roboto_Condensed'] tracking-wide" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    
                    <div class="w-16 h-16 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-3 transition-all duration-500 shadow-lg">
                        <svg class="w-8 h-8 text-white font-['Roboto_Condensed'] tracking-wide" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    
                    <h3 class="text-lg font-bold text-[#512B58] font-['Roboto_Condensed'] tracking-wide mb-3">Stay-out Maids</h3>
                    <p class="text-[#512B58]/80 font-['Roboto_Condensed'] tracking-wide leading-relaxed mb-4 text-sm">Daily domestic services from 7 AM to 7 PM, perfect for regular household maintenance.</p>
                    
                    <a href="{{ route('booking.public') }}" 
                       class="w-full bg-gradient-to-r from-[#F5B301] to-[#FFD700] text-[#512B58] font-['Roboto_Condensed'] tracking-wide py-2 px-4 rounded-lg text-sm font-bold hover:from-[#FFD700] hover:to-[#F5B301] transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 text-center block">
                        Book Now
                    </a>
                </div>
                
                <!-- Nanny Services -->
                <div class="group bg-white/95 backdrop-blur-sm border-2 border-[#F5B301]/30 rounded-2xl p-6 hover:shadow-2xl hover:border-[#F5B301]/60 transition-all duration-500 hover:-translate-y-2 relative overflow-hidden">
                    <!-- Royal Crown Decoration -->
                    <div class="absolute top-3 right-3 w-6 h-6 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full flex items-center justify-center opacity-20 group-hover:opacity-40 transition-opacity duration-300">
                        <svg class="w-3 h-3 text-white font-['Roboto_Condensed'] tracking-wide" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    
                    <div class="w-16 h-16 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 group-hover:rotate-3 transition-all duration-500 shadow-lg">
                        <svg class="w-8 h-8 text-white font-['Roboto_Condensed'] tracking-wide" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    
                    <h3 class="text-lg font-bold text-[#512B58] font-['Roboto_Condensed'] tracking-wide mb-3">Nanny Services</h3>
                    <p class="text-[#512B58]/80 font-['Roboto_Condensed'] tracking-wide leading-relaxed mb-4 text-sm">Specialized care for infants and young children from 0 to 5 years of age.</p>
                    
                    <a href="{{ route('booking.public') }}" 
                       class="w-full bg-gradient-to-r from-[#F5B301] to-[#FFD700] text-[#512B58] font-['Roboto_Condensed'] tracking-wide py-2 px-4 rounded-lg text-sm font-bold hover:from-[#FFD700] hover:to-[#F5B301] transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 text-center block">
                        Book Now
                    </a>
                </div>
                </div>
                
            <!-- Featured Service Highlight -->
            <div class="bg-gradient-to-r from-[#512B58] to-[#3B0A45] rounded-2xl p-8 lg:p-12 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-[#F5B301]/10 rounded-full -translate-y-32 translate-x-32"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-[#F5B301]/10 rounded-full translate-y-24 -translate-x-24"></div>
                
                <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                    <div>
                        <h3 class="text-3xl lg:text-4xl font-bold mb-4">Professional Maidservants</h3>
                        <p class="text-lg text-white/90 leading-relaxed mb-6">
                            Our most popular service featuring highly trained domestic professionals who provide comprehensive 
                            household management with royal-quality standards. From daily cleaning to meal preparation, 
                            our maidservants ensure your home runs smoothly.
                        </p>
                      
                        <a href="{{ route('booking.public') }}" 
                           class="inline-flex items-center bg-[#F5B301] text-white font-['Roboto_Condensed'] tracking-wide px-8 py-4 rounded-xl font-bold text-lg hover:bg-[#FFD700] transition-all duration-300 shadow-lg hover:shadow-xl">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                            LEARN MORE
                        </a>
                    </div>
                    
                    <div class="relative">
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20">
                            <div class="text-center">
                                <div class="w-24 h-24 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full mx-auto flex items-center justify-center mb-4">
                                    <svg class="w-12 h-12 text-white font-['Roboto_Condensed'] tracking-wide" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <h4 class="text-xl font-bold text-white mb-2">Royal-Quality Service</h4>
                                <p class="text-white/80 text-sm">Professional training, background checks, and ongoing support ensure the highest standards of domestic service.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Book A Maid in 4 Easy Steps -->
    <section class="py-20 bg-gradient-to-br from-[#512B58] to-[#3B0A45] relative overflow-hidden">
        <!-- Background Decorations -->
        <div class="absolute top-0 left-0 w-72 h-72 bg-[#F5B301]/5 rounded-full -translate-x-36 -translate-y-36"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-[#FFD700]/5 rounded-full translate-x-48 translate-y-48"></div>
        <div class="absolute top-1/2 left-1/4 w-32 h-32 bg-[#F5B301]/10 rounded-full animate-pulse"></div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-4xl lg:text-6xl font-bold text-white mb-6 font-['Roboto_Condensed'] tracking-wide">
                    Book A Maid in <span class="text-[#F5B301]">4 Easy Steps</span>
                </h2>
                <p class="text-xl lg:text-2xl text-[#D1C4E9] max-w-4xl mx-auto leading-relaxed">
                    Simple, transparent process to get the perfect domestic help for your family. 
                    Our streamlined booking system ensures you get matched with the ideal professional.
                </p>
            </div>
            
            <!-- Steps Grid with Enhanced Design -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
                <!-- Step 1 -->
                <div class="group relative">
                    <!-- Connection Line -->
                    <div class="hidden lg:block absolute top-8 left-full w-full h-0.5 bg-gradient-to-r from-[#F5B301]/50 to-transparent z-0"></div>
                    
                    <div class="relative z-10 bg-white/10 backdrop-blur-sm border border-[#F5B301]/30 hover:bg-white/15 rounded-2xl p-8 border border-[#F5B301]/30 hover:bg-white/15 hover:border-[#F5B301]/50 transition-all duration-500 hover:-translate-y-2 group-hover:shadow-2xl">
                        <!-- Step Number -->
                        <div class="w-20 h-20 bg-gradient-to-br from-[#F5B301] to-[#FFD700] text-white font-['Roboto_Condensed'] tracking-wide rounded-2xl flex items-center justify-center mx-auto mb-6 text-3xl font-bold shadow-xl group-hover:scale-110 group-hover:rotate-3 transition-all duration-500 font-['Roboto_Condensed']">
                        1
                    </div>
                        
                        <!-- Step Icon -->
                        <div class="w-16 h-16 bg-[#F5B301]/20 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:bg-[#F5B301]/30 transition-colors duration-300">
                            <svg class="w-8 h-8 text-[#F5B301]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                </div>
                
                        <h3 class="text-xl font-bold text-white mb-3 font-['Roboto_Condensed'] tracking-wide">Fill the Booking Form</h3>
                        <p class="text-[#D1C4E9] leading-relaxed">Takes about 1-2 minutes to fill out. Upload a copy of National ID (front part) or Passport.</p>
                        
                        <!-- Step Features -->
                        <div class="mt-4 space-y-2">
                            <div class="flex items-center text-sm text-[#D1C4E9]/90">
                                <div class="w-1.5 h-1.5 bg-[#F5B301] rounded-full mr-2"></div>
                                <span>Quick 1-2 minute form</span>
                            </div>
                            <div class="flex items-center text-sm text-[#D1C4E9]/90">
                                <div class="w-1.5 h-1.5 bg-[#F5B301] rounded-full mr-2"></div>
                                <span>ID or Passport upload</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Step 2 -->
                <div class="group relative">
                    <!-- Connection Line -->
                    <div class="hidden lg:block absolute top-8 left-full w-full h-0.5 bg-gradient-to-r from-[#F5B301]/50 to-transparent z-0"></div>
                    
                    <div class="relative z-10 bg-white/10 backdrop-blur-sm border border-[#F5B301]/30 hover:bg-white/15 rounded-2xl p-8 border border-[#F5B301]/30 hover:bg-white/15 hover:border-[#F5B301]/50 transition-all duration-500 hover:-translate-y-2 group-hover:shadow-2xl">
                        <!-- Step Number -->
                        <div class="w-20 h-20 bg-gradient-to-br from-[#F5B301] to-[#FFD700] text-white font-['Roboto_Condensed'] tracking-wide rounded-2xl flex items-center justify-center mx-auto mb-6 text-3xl font-bold shadow-xl group-hover:scale-110 group-hover:rotate-3 transition-all duration-500 font-['Roboto_Condensed']">
                        2
                    </div>
                        
                        <!-- Step Icon -->
                        <div class="w-16 h-16 bg-[#F5B301]/20 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:bg-[#F5B301]/30 transition-colors duration-300">
                            <svg class="w-8 h-8 text-[#F5B301]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                </div>
                
                        <h3 class="text-xl font-bold text-white mb-3 font-['Roboto_Condensed'] tracking-wide">Pay Service Fee</h3>
                        <p class="text-[#D1C4E9] leading-relaxed">250,000 UGX paid via Bank or Mobile Money to start processing your order.</p>
                        
                        <!-- Step Features -->
                        <div class="mt-4 space-y-2">
                            <div class="flex items-center text-sm text-[#D1C4E9]/90">
                                <div class="w-1.5 h-1.5 bg-[#F5B301] rounded-full mr-2"></div>
                                <span>250,000 UGX fee</span>
                            </div>
                            <div class="flex items-center text-sm text-[#D1C4E9]/90">
                                <div class="w-1.5 h-1.5 bg-[#F5B301] rounded-full mr-2"></div>
                                <span>Bank or Mobile Money</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Step 3 -->
                <div class="group relative">
                    <!-- Connection Line -->
                    <div class="hidden lg:block absolute top-8 left-full w-full h-0.5 bg-gradient-to-r from-[#F5B301]/50 to-transparent z-0"></div>
                    
                    <div class="relative z-10 bg-white/10 backdrop-blur-sm border border-[#F5B301]/30 hover:bg-white/15 rounded-2xl p-8 border border-[#F5B301]/30 hover:bg-white/15 hover:border-[#F5B301]/50 transition-all duration-500 hover:-translate-y-2 group-hover:shadow-2xl">
                        <!-- Step Number -->
                        <div class="w-20 h-20 bg-gradient-to-br from-[#F5B301] to-[#FFD700] text-white font-['Roboto_Condensed'] tracking-wide rounded-2xl flex items-center justify-center mx-auto mb-6 text-3xl font-bold shadow-xl group-hover:scale-110 group-hover:rotate-3 transition-all duration-500 font-['Roboto_Condensed']">
                        3
                    </div>
                        
                        <!-- Step Icon -->
                        <div class="w-16 h-16 bg-[#F5B301]/20 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:bg-[#F5B301]/30 transition-colors duration-300">
                            <svg class="w-8 h-8 text-[#F5B301]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                </div>
                
                        <h3 class="text-xl font-bold text-white mb-3 font-['Roboto_Condensed'] tracking-wide">Matching Process</h3>
                        <p class="text-[#D1C4E9] leading-relaxed">Our matching staff will call you for extra details to ensure the perfect match from our pool of domestic workers.</p>
                        
                        <!-- Step Features -->
                        <div class="mt-4 space-y-2">
                            <div class="flex items-center text-sm text-[#D1C4E9]/90">
                                <div class="w-1.5 h-1.5 bg-[#F5B301] rounded-full mr-2"></div>
                                <span>Personal consultation call</span>
                            </div>
                            <div class="flex items-center text-sm text-[#D1C4E9]/90">
                                <div class="w-1.5 h-1.5 bg-[#F5B301] rounded-full mr-2"></div>
                                <span>Perfect match guarantee</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Step 4 -->
                <div class="group relative">
                    <div class="relative z-10 bg-white/10 backdrop-blur-sm border border-[#F5B301]/30 hover:bg-white/15 rounded-2xl p-8 border border-[#F5B301]/30 hover:bg-white/15 hover:border-[#F5B301]/50 transition-all duration-500 hover:-translate-y-2 group-hover:shadow-2xl">
                        <!-- Step Number -->
                        <div class="w-20 h-20 bg-gradient-to-br from-[#F5B301] to-[#FFD700] text-white font-['Roboto_Condensed'] tracking-wide rounded-2xl flex items-center justify-center mx-auto mb-6 text-3xl font-bold shadow-xl group-hover:scale-110 group-hover:rotate-3 transition-all duration-500 font-['Roboto_Condensed']">
                        4
                    </div>
                        
                        <!-- Step Icon -->
                        <div class="w-16 h-16 bg-[#F5B301]/20 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:bg-[#F5B301]/30 transition-colors duration-300">
                            <svg class="w-8 h-8 text-[#F5B301]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        
                        <h3 class="text-xl font-bold text-white mb-3 font-['Roboto_Condensed'] tracking-wide">Deployment & Contract</h3>
                        <p class="text-[#D1C4E9] leading-relaxed">On the agreed date, we bring you the domestic worker, sign the contract, and deploy our trained and trusted staff.</p>
                        
                        <!-- Step Features -->
                        <div class="mt-4 space-y-2">
                            <div class="flex items-center text-sm text-[#D1C4E9]/90">
                                <div class="w-1.5 h-1.5 bg-[#F5B301] rounded-full mr-2"></div>
                                <span>Contract signing</span>
                            </div>
                            <div class="flex items-center text-sm text-[#D1C4E9]/90">
                                <div class="w-1.5 h-1.5 bg-[#F5B301] rounded-full mr-2"></div>
                                <span>Trained staff deployment</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Enhanced CTA Button -->
            <div class="text-center">
                <a href="{{ route('booking.public') }}" 
                   class="group inline-flex items-center bg-gradient-to-r from-[#F5B301] to-[#FFD700] text-white font-['Roboto_Condensed'] tracking-wide px-12 py-6 rounded-2xl text-xl font-bold hover:from-[#FFD700] hover:to-[#F5B301] transition-all duration-500 shadow-2xl hover:shadow-3xl transform hover:-translate-y-2 hover:scale-105 font-['Roboto_Condensed'] tracking-wide">
                    <svg class="w-6 h-6 mr-3 group-hover:scale-110 transition-transform duration-300" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    Start Booking Process
                    <svg class="w-5 h-5 ml-3 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                
                <!-- Trust Indicators -->
                <div class="mt-8 flex flex-wrap justify-center items-center gap-8 text-[#D1C4E9]/90">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#F5B301]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-['Roboto_Condensed'] font-semibold">100% Secure</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#F5B301]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-['Roboto_Condensed'] font-semibold">Quick Process</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#F5B301]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        <span class="font-['Roboto_Condensed'] font-semibold">Satisfaction Guaranteed</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Packages Section -->
    <section id="packages" class="py-20 bg-white relative overflow-hidden" style="background-image: url('{{ asset('storage/web-site-images/hero.jpg') }}'); background-size: cover; background-position: center; background-attachment: fixed;">
        <!-- Background Overlay -->
        <div class="absolute inset-0 bg-gradient-to-br from-[#F5B301]/20 to-[#FFD700]/20"></div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-[#512B58] font-['Roboto_Condensed'] tracking-wide mb-4">Our Service Packages</h2>
                <p class="text-xl text-[#512B58]/80 font-['Roboto_Condensed'] tracking-wide max-w-4xl mx-auto leading-relaxed">
                    Choose the perfect package for your family's needs. Each package includes comprehensive training, 
                    backup support, and quality assurance to ensure royal-quality service.
                </p>
            </div>
            
            @if($packages->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($packages as $package)
                        <div class="relative bg-white/80 backdrop-blur-md rounded-2xl shadow-xl border border-[#F5B301]/30 hover:bg-white/15 overflow-hidden {{ $package->name === 'Gold' ? 'ring-2 ring-[#F5B301] scale-105' : '' }} hover:shadow-2xl hover:bg-white/90 transition-all duration-500 group">
                            @if($package->name === 'Gold')
                                <div class="absolute top-0 right-0 bg-gradient-to-r from-[#F5B301]/90 to-[#FFD700]/90 backdrop-blur-sm text-white font-['Roboto_Condensed'] tracking-wide px-4 py-2 text-sm font-semibold rounded-bl-lg shadow-lg">
                                    Most Popular
                                </div>
                            @endif
                            
                            <div class="p-8">
                                <div class="text-center mb-6">
                                    <div class="w-16 h-16 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full flex items-center justify-center mx-auto mb-4">
                                        @if($package->name === 'Silver')
                                            <svg class="w-8 h-8 text-white font-['Roboto_Condensed'] tracking-wide" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 1l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 1z"/>
                                        </svg>
                                        @elseif($package->name === 'Gold')
                                            <svg class="w-8 h-8 text-white font-['Roboto_Condensed'] tracking-wide" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                            </svg>
                                        @else
                                            <svg class="w-8 h-8 text-white font-['Roboto_Condensed'] tracking-wide" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                        @endif
                                    </div>
                                    <h3 class="text-2xl font-bold text-[#512B58] font-['Roboto_Condensed'] tracking-wide mb-2">{{ $package->name }} {{ $package->tier }}</h3>
                                    <div class="text-4xl font-bold text-[#F5B301] mb-2">{{ number_format($package->base_price) }} UGX/month</div>
                                    <p class="text-[#512B58]/80 font-['Roboto_Condensed'] tracking-wide">Base family size: {{ $package->base_family_size }} members</p>
                                </div>
                                
                                <div class="mb-6">
                                    <h4 class="font-semibold text-[#512B58] font-['Roboto_Condensed'] tracking-wide mb-3 flex items-center">
                                        <svg class="w-4 h-4 text-[#F5B301] mr-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Package Features:
                                    </h4>
                                    <ul class="space-y-2">
                                        @if($package->features)
                                            @foreach($package->features as $feature)
                                                <li class="flex items-center text-[#512B58]/80 font-['Roboto_Condensed'] tracking-wide">
                                                    <svg class="w-4 h-4 text-[#F5B301] mr-2" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    {{ $feature }}
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                                
                                <div class="mb-6">
                                    <h4 class="font-semibold text-[#512B58] font-['Roboto_Condensed'] tracking-wide mb-3 flex items-center">
                                        <svg class="w-4 h-4 text-[#F5B301] mr-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                        Training Program ({{ $package->training_weeks }} weeks):
                                    </h4>
                                    <ul class="space-y-2">
                                        @if($package->training_includes)
                                            @foreach($package->training_includes as $training)
                                                <li class="flex items-center text-[#512B58]/80 font-['Roboto_Condensed'] tracking-wide">
                                                    <svg class="w-4 h-4 text-[#F5B301] mr-2" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                    </svg>
                                                    {{ $training }}
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                                
                                <div class="mb-6 bg-gradient-to-r from-[#F5B301]/10 to-[#FFD700]/10 backdrop-blur-sm rounded-lg p-4 border border-[#F5B301]/20">
                                    <h4 class="font-semibold text-[#512B58] font-['Roboto_Condensed'] tracking-wide mb-2">Package Benefits:</h4>
                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        <div class="flex items-center text-[#512B58]/80">
                                            <svg class="w-4 h-4 text-[#F5B301] mr-2" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $package->backup_days_per_year }} backup days/year
                                        </div>
                                        <div class="flex items-center text-[#512B58]/80">
                                            <svg class="w-4 h-4 text-[#F5B301] mr-2" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                            </svg>
                                            {{ $package->free_replacements }} free replacements
                                        </div>
                                        <div class="flex items-center text-[#512B58]/80">
                                            <svg class="w-4 h-4 text-[#F5B301] mr-2" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $package->evaluations_per_year }} evaluations/year
                                        </div>
                                        <div class="flex items-center text-[#512B58]/80">
                                            <svg class="w-4 h-4 text-[#F5B301] mr-2" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                            </svg>
                                            24/7 support
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="text-center">
                                    <p class="text-sm text-[#512B58]/70 font-['Roboto_Condensed'] tracking-wide mb-4">
                                        Additional members: {{ number_format($package->additional_member_cost) }} UGX/month each
                                    </p>
                                    <a href="{{ route('booking.public') }}" 
                                       class="w-full bg-gradient-to-r from-[#F5B301]/90 to-[#FFD700]/90 backdrop-blur-sm text-[#512B58] font-['Roboto_Condensed'] tracking-wide py-3 px-6 rounded-lg font-semibold hover:from-[#FFD700]/90 hover:to-[#F5B301]/90 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                        Choose {{ $package->name }} Package
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="bg-white/80 backdrop-blur-md rounded-lg p-8 max-w-md mx-auto border border-[#F5B301]/30 hover:bg-white/15 shadow-xl">
                        <svg class="w-16 h-16 text-[#F5B301] mx-auto mb-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        <p class="text-[#512B58] font-['Roboto_Condensed'] tracking-wide text-lg">No packages available at the moment. Please check back later.</p>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- About Royal Maids Section -->
    <section class="py-20 bg-gradient-to-br from-[#512B58] to-[#3B0A45]">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                    <h2 class="text-3xl lg:text-4xl font-bold text-white mb-4">About Royal Maids Hub</h2>
                    <p class="text-xl text-[#D1C4E9] max-w-4xl mx-auto">
                        Uganda's premier domestic service provider, bringing royal-quality care to your home
                    </p>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <h3 class="text-2xl font-bold text-white mb-6">Our Mission</h3>
                        <p class="text-[#D1C4E9] text-lg mb-6 leading-relaxed">
                            At Royal Maids Hub, we believe every family deserves royal-quality domestic service. 
                            We provide professionally trained maidservants, home managers, bedside nurses, and 
                            specialized caregivers who bring excellence, reliability, and compassion to your home.
                        </p>
                        
                        <h3 class="text-2xl font-bold text-white mb-6">What Makes Us Different</h3>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="w-8 h-8 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full flex items-center justify-center mr-4 mt-1">
                                    <svg class="w-4 h-4 text-white font-['Roboto_Condensed'] tracking-wide" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-white font-semibold mb-1">Comprehensive Training Program</h4>
                                    <p class="text-[#D1C4E9]">4-8 week intensive training covering domestic skills, safety protocols, and specialized care</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="w-8 h-8 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full flex items-center justify-center mr-4 mt-1">
                                    <svg class="w-4 h-4 text-white font-['Roboto_Condensed'] tracking-wide" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-white font-semibold mb-1">Quality Assurance</h4>
                                    <p class="text-[#D1C4E9]">Regular evaluations, background checks, and continuous monitoring for service excellence</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="w-8 h-8 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full flex items-center justify-center mr-4 mt-1">
                                    <svg class="w-4 h-4 text-white font-['Roboto_Condensed'] tracking-wide" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-white font-semibold mb-1">24/7 Support</h4>
                                    <p class="text-[#D1C4E9]">Round-the-clock support, emergency backup services, and immediate replacement options</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="w-8 h-8 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full flex items-center justify-center mr-4 mt-1">
                                    <svg class="w-4 h-4 text-white font-['Roboto_Condensed'] tracking-wide" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-white font-semibold mb-1">Specialized Services</h4>
                                    <p class="text-[#D1C4E9]">Medical care, elderly care, childcare, and emergency response services</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-[#F5B301]/30 hover:bg-white/15">
                        <h3 class="text-2xl font-bold text-white mb-6">Our Services Include</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white/10 rounded-lg p-4 text-center">
                                <svg class="w-8 h-8 text-[#F5B301] mx-auto mb-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <p class="text-white text-sm">Domestic Cleaning</p>
                            </div>
                            <div class="bg-white/10 rounded-lg p-4 text-center">
                                <svg class="w-8 h-8 text-[#F5B301] mx-auto mb-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <p class="text-white text-sm">Home Management</p>
                            </div>
                            <div class="bg-white/10 rounded-lg p-4 text-center">
                                <svg class="w-8 h-8 text-[#F5B301] mx-auto mb-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                                <p class="text-white text-sm">Medical Care</p>
                            </div>
                            <div class="bg-white/10 rounded-lg p-4 text-center">
                                <svg class="w-8 h-8 text-[#F5B301] mx-auto mb-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                <p class="text-white text-sm">Childcare</p>
                            </div>
                            <div class="bg-white/10 rounded-lg p-4 text-center">
                                <svg class="w-8 h-8 text-[#F5B301] mx-auto mb-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-white text-sm">Emergency Response</p>
                            </div>
                            <div class="bg-white/10 rounded-lg p-4 text-center">
                                <svg class="w-8 h-8 text-[#F5B301] mx-auto mb-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-white text-sm">Temporary Services</p>
                            </div>
                        </div>
                        
                        <div class="mt-8 text-center">
                            <a href="{{ route('booking.public') }}" 
                               class="bg-gradient-to-r from-[#F5B301] to-[#FFD700] text-white font-['Roboto_Condensed'] tracking-wide px-6 py-3 rounded-lg font-semibold hover:from-[#FFD700] hover:to-[#F5B301] transition-all duration-300 shadow-lg hover:shadow-xl">
                                Get Started Today
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <!-- Our Leadership Team Section -->
    <section class="py-20 bg-gradient-to-br from-[#512B58] to-[#3B0A45] relative overflow-hidden">
        <!-- Background Decorations -->
        <div class="absolute top-0 left-0 w-72 h-72 bg-[#F5B301]/5 rounded-full -translate-x-36 -translate-y-36"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-[#FFD700]/5 rounded-full translate-x-48 translate-y-48"></div>
        <div class="absolute top-1/2 left-1/4 w-32 h-32 bg-[#F5B301]/10 rounded-full animate-pulse"></div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-white mb-4 font-['Roboto_Condensed'] tracking-wide">Our Leadership Team</h2>
                <p class="text-xl text-[#D1C4E9] max-w-4xl mx-auto leading-relaxed">
                    Meet Our Expert Team. Our dedicated leadership team brings years of experience and expertise to ensure Royal Maids Hub delivers exceptional service.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Edward Ssempala -->
                <div class="bg-white/10 backdrop-blur-md rounded-2xl shadow-xl p-6 text-center border border-[#F5B301]/30 hover:bg-white/15 border border-[#F5B301]/30 hover:bg-white/15 hover:shadow-2xl transition-all duration-500 group">
                    <div class="w-24 h-24 rounded-full mx-auto mb-4 overflow-hidden border-4 border-[#F5B301]/50 group-hover:border-[#F5B301] transition-colors duration-300">
                        <img src="{{ asset('storage/web-site-images/team/edward.png') }}" alt="Edward Ssempala" class="w-full h-full object-cover">
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2 font-['Roboto_Condensed'] tracking-wide">Edward Ssempala</h3>
                    <p class="text-[#F5B301] font-medium mb-2">Managing Director</p>
                    <p class="text-[#D1C4E9]/90">Leading Royal Maids Hub with vision and strategic direction</p>
                </div>
                
                <!-- Alice Gladys Ssempala -->
                <div class="bg-white/10 backdrop-blur-md rounded-2xl shadow-xl p-6 text-center border border-[#F5B301]/30 hover:bg-white/15 border border-[#F5B301]/30 hover:bg-white/15 hover:shadow-2xl transition-all duration-500 group">
                    <div class="w-24 h-24 rounded-full mx-auto mb-4 overflow-hidden border-4 border-[#F5B301]/50 group-hover:border-[#F5B301] transition-colors duration-300">
                        <img src="{{ asset('storage/web-site-images/team/Pastor Alice Ssempala Photo RMH.jpg') }}" alt="Alice Gladys Ssempala" class="w-full h-full object-cover">
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2 font-['Roboto_Condensed'] tracking-wide">Alice Gladys Ssempala</h3>
                    <p class="text-[#F5B301] font-medium mb-2">Operations Manager</p>
                    <p class="text-[#D1C4E9]/90">Ensuring smooth operations and quality service delivery</p>
                </div>
                
                <!-- Faith Waiswa -->
                <div class="bg-white/10 backdrop-blur-md rounded-2xl shadow-xl p-6 text-center border border-[#F5B301]/30 hover:bg-white/15 border border-[#F5B301]/30 hover:bg-white/15 hover:shadow-2xl transition-all duration-500 group">
                    <div class="w-24 h-24 rounded-full mx-auto mb-4 overflow-hidden border-4 border-[#F5B301]/50 group-hover:border-[#F5B301] transition-colors duration-300">
                        <img src="{{ asset('storage/web-site-images/team/Pastor Faith Photo RMH.jpg') }}" alt="Faith Waiswa" class="w-full h-full object-cover">
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2 font-['Roboto_Condensed'] tracking-wide">Faith Waiswa</h3>
                    <p class="text-[#F5B301] font-medium mb-2">Counselor/Trainer</p>
                    <p class="text-[#D1C4E9]/90">Providing counseling and training support to our team</p>
                </div>
                
                <!-- Suzan Nyarwa -->
                <div class="bg-white/10 backdrop-blur-md rounded-2xl shadow-xl p-6 text-center border border-[#F5B301]/30 hover:bg-white/15 border border-[#F5B301]/30 hover:bg-white/15 hover:shadow-2xl transition-all duration-500 group">
                    <div class="w-24 h-24 rounded-full mx-auto mb-4 overflow-hidden border-4 border-[#F5B301]/50 group-hover:border-[#F5B301] transition-colors duration-300">
                        <img src="{{ asset('storage/web-site-images/team/Pastor Suzan Nyarwa Photo RMH.jpg') }}" alt="Suzan Nyarwa" class="w-full h-full object-cover">
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2 font-['Roboto_Condensed'] tracking-wide">Suzan Nyarwa</h3>
                    <p class="text-[#F5B301] font-medium mb-2">Lawyer & Trainer-child care</p>
                    <p class="text-[#D1C4E9]/90">Legal expertise and specialized child care training</p>
                </div>
                
                <!-- Patience Kirigoola -->
                <div class="bg-white/10 backdrop-blur-md rounded-2xl shadow-xl p-6 text-center border border-[#F5B301]/30 hover:bg-white/15 border border-[#F5B301]/30 hover:bg-white/15 hover:shadow-2xl transition-all duration-500 group">
                    <div class="w-24 h-24 rounded-full mx-auto mb-4 overflow-hidden border-4 border-[#F5B301]/50 group-hover:border-[#F5B301] transition-colors duration-300">
                        <img src="{{ asset('storage/web-site-images/team/Mrs Patience Kirigola Photo RMH.jpg') }}" alt="Patience Kirigoola" class="w-full h-full object-cover">
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2 font-['Roboto_Condensed'] tracking-wide">Patience Kirigoola</h3>
                    <p class="text-[#F5B301] font-medium mb-2">Curriculum Development & Social Media</p>
                    <p class="text-[#D1C4E9]/90">Developing training curricula and managing our digital presence</p>
                </div>
                
                <!-- Diana Musunga -->
                <div class="bg-white/10 backdrop-blur-md rounded-2xl shadow-xl p-6 text-center border border-[#F5B301]/30 hover:bg-white/15 border border-[#F5B301]/30 hover:bg-white/15 hover:shadow-2xl transition-all duration-500 group">
                    <div class="w-24 h-24 rounded-full mx-auto mb-4 overflow-hidden border-4 border-[#F5B301]/50 group-hover:border-[#F5B301] transition-colors duration-300">
                        <img src="{{ asset('storage/web-site-images/team/diana.jpeg') }}" alt="Diana Musunga" class="w-full h-full object-cover">
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2 font-['Roboto_Condensed'] tracking-wide">Diana Musunga</h3>
                    <p class="text-[#F5B301] font-medium mb-2">Trainer-Cleaning & Maintenance</p>
                    <p class="text-[#D1C4E9]/90">Specialized training in cleaning and household maintenance</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Customer Reviews Section -->
    <section class="py-20 bg-gradient-to-br from-[#512B58] to-[#3B0A45] relative overflow-hidden">
        <!-- Background Decorations -->
        <div class="absolute top-0 left-0 w-72 h-72 bg-[#F5B301]/5 rounded-full -translate-x-36 -translate-y-36"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-[#FFD700]/5 rounded-full translate-x-48 translate-y-48"></div>
        <div class="absolute top-1/2 left-1/4 w-32 h-32 bg-[#F5B301]/10 rounded-full animate-pulse"></div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-white mb-4 font-['Roboto_Condensed'] tracking-wide">What Our Clients Say</h2>
                <p class="text-xl text-[#D1C4E9] max-w-4xl mx-auto leading-relaxed">
                    Don't just take our word for it. Here's what our satisfied customers have to say about our services.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Review 1 -->
                <div class="bg-white/10 backdrop-blur-md rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border border-[#F5B301]/30 hover:bg-white/15 border border-[#F5B301]/30 hover:bg-white/15">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full flex items-center justify-center mr-4">
                            <span class="text-white font-['Roboto_Condensed'] tracking-wide font-bold text-lg">F</span>
                        </div>
                <div>
                            <h4 class="font-semibold text-white font-['Roboto_Condensed'] tracking-wide">Fortunate Ekyasimire</h4>
                        <div class="flex items-center">
                                <div class="flex text-[#F5B301]">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                                </div>
                                <span class="text-sm text-[#D1C4E9]/90 ml-2">5 months ago</span>
                            </div>
                        </div>
                    </div>
                    <p class="text-[#D1C4E9]/90 leading-relaxed">
                        "The service was great!! The Nanny came really early and bonded well with the child. I definitely recommend this team!!!"
                    </p>
                    <div class="flex items-center mt-4">
                        <svg class="w-4 h-4 text-red-500 mr-1" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                        <span class="text-sm text-[#D1C4E9]/90">❤️ 2 likes</span>
                    </div>
                </div>

                <!-- Review 2 -->
                <div class="bg-white/10 backdrop-blur-md rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border border-[#F5B301]/30 hover:bg-white/15 border border-[#F5B301]/30 hover:bg-white/15">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full flex items-center justify-center mr-4">
                            <span class="text-white font-['Roboto_Condensed'] tracking-wide font-bold text-lg">N</span>
                            </div>
                            <div>
                            <h4 class="font-semibold text-white font-['Roboto_Condensed'] tracking-wide">Nakitende Rebecca</h4>
                            <div class="flex items-center">
                                <div class="flex text-[#F5B301]">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                </div>
                                <span class="text-sm text-[#D1C4E9]/90 ml-2">6 months ago</span>
                            </div>
                        </div>
                    </div>
                    <p class="text-[#D1C4E9]/90 leading-relaxed">
                        "I surely recommend Royal Maids Hub, such wonderful needed and timely services needed by everyone."
                    </p>
                    <div class="flex items-center mt-4">
                        <svg class="w-4 h-4 text-red-500 mr-1" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                        <span class="text-sm text-[#D1C4E9]/90">❤️ 2 likes</span>
                            </div>
                        </div>
                        
                <!-- Review 3 -->
                <div class="bg-white/10 backdrop-blur-md rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border border-[#F5B301]/30 hover:bg-white/15">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full flex items-center justify-center mr-4">
                            <span class="text-white font-['Roboto_Condensed'] tracking-wide font-bold text-lg">A</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-white font-['Roboto_Condensed'] tracking-wide">ainyo susan</h4>
                        <div class="flex items-center">
                                <div class="flex text-[#F5B301]">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                                </div>
                                <span class="text-sm text-[#D1C4E9]/90 ml-2">7 months ago</span>
                            </div>
                        </div>
                    </div>
                    <p class="text-[#D1C4E9]/90 leading-relaxed">
                        "Good experience. Thank you for the support during the festive season"
                    </p>
                    <div class="flex items-center mt-4">
                        <svg class="w-4 h-4 text-red-500 mr-1" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                        <span class="text-sm text-[#D1C4E9]/90">❤️ 2 likes</span>
                    </div>
                </div>

                <!-- Review 4 -->
                <div class="bg-white/10 backdrop-blur-md rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border border-[#F5B301]/30 hover:bg-white/15">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full flex items-center justify-center mr-4">
                            <span class="text-white font-['Roboto_Condensed'] tracking-wide font-bold text-lg">A</span>
                            </div>
                            <div>
                            <h4 class="font-semibold text-white font-['Roboto_Condensed'] tracking-wide">ALICE NAMUTEBI</h4>
                            <div class="flex items-center">
                                <div class="flex text-[#F5B301]">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                </div>
                                <span class="text-sm text-[#D1C4E9]/90 ml-2">6 months ago</span>
                            </div>
                        </div>
                    </div>
                    <p class="text-[#D1C4E9]/90 leading-relaxed">
                        "Convenient and professional services."
                    </p>
                    <div class="flex items-center mt-4">
                        <svg class="w-4 h-4 text-red-500 mr-1" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                        <span class="text-sm text-[#D1C4E9]/90">❤️ 1 like</span>
                            </div>
                        </div>
                        
                <!-- Review 5 -->
                <div class="bg-white/10 backdrop-blur-md rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 border border-[#F5B301]/30 hover:bg-white/15">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full flex items-center justify-center mr-4">
                            <span class="text-white font-['Roboto_Condensed'] tracking-wide font-bold text-lg">A</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-white font-['Roboto_Condensed'] tracking-wide">Alizik Wotali</h4>
                        <div class="flex items-center">
                                <div class="flex text-[#F5B301]">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                </div>
                                <span class="text-sm text-[#D1C4E9]/90 ml-2">8 months ago</span>
                            </div>
                        </div>
                    </div>
                    <p class="text-[#D1C4E9]/90 leading-relaxed">
                        "Excellent service and very professional team. Highly recommended!"
                    </p>
                    <div class="flex items-center mt-4">
                        <svg class="w-4 h-4 text-red-500 mr-1" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                        <span class="text-sm text-[#D1C4E9]/90">❤️ 3 likes</span>
                    </div>
                </div>

                <!-- Review 6 - Owner Response -->
                <div class="bg-gradient-to-br from-[#F5B301]/20 to-[#FFD700]/20 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-[#F5B301]/50">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-[#512B58] to-[#3B0A45] rounded-full flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-[#F5B301]" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            </div>
                            <div>
                            <h4 class="font-semibold text-white font-['Roboto_Condensed'] tracking-wide">Royal Maids Hub</h4>
                            <span class="text-sm text-[#D1C4E9]/90 bg-[#F5B301]/30 px-2 py-1 rounded-full">Owner Response</span>
                            </div>
                        </div>
                    <p class="text-[#D1C4E9]/90 leading-relaxed">
                        "Thank you, Deacon Becky. It was pleasure serving you at Virtuous Woman Conf at Serena."
                    </p>
                    <p class="text-[#D1C4E9]/90 leading-relaxed mt-2">
                        "We were honored to serve you."
                    </p>
                    </div>
                </div>
                
            <!-- Call to Action -->
            <div class="text-center mt-16">
                <h3 class="text-2xl font-bold text-white font-['Roboto_Condensed'] tracking-wide mb-4">Ready to Experience Our Services?</h3>
                <p class="text-lg text-[#D1C4E9]/90 mb-8">Join hundreds of satisfied customers who trust Royal Maids Hub</p>
                <a href="{{ route('booking.public') }}" 
                   class="inline-flex items-center bg-gradient-to-r from-[#F5B301] to-[#FFD700] text-white font-['Roboto_Condensed'] tracking-wide px-8 py-4 rounded-lg font-semibold hover:from-[#FFD700] hover:to-[#F5B301] transition-all duration-300 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Book Your Service Now
                </a>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-20 bg-gradient-to-br from-[#512B58] to-[#3B0A45]">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-white mb-4">Wipe Your Confusion</h2>
                <p class="text-xl text-[#D1C4E9] max-w-4xl mx-auto">
                    In other words, some FAQ's. Got questions? We've got answers about our Royal Maids services.
                </p>
            </div>
            
            <div class="max-w-6xl mx-auto">
                <!-- FAQ Tabs -->
                <div class="bg-white/10 backdrop-blur-sm rounded-xl border border-[#F5B301]/30 hover:bg-white/15 overflow-hidden">
                    <!-- Tab Navigation -->
                    <div class="border-b border-[#F5B301]/30">
                        <nav class="flex flex-wrap" role="tablist">
                            <button class="flex-1 px-6 py-4 text-sm font-medium text-[#D1C4E9] hover:text-white hover:bg-white/10 transition-all duration-300 border-b-2 border-transparent hover:border-[#F5B301]/50 focus:outline-none focus:border-[#F5B301] active" 
                                    onclick="showTab('general')" id="general-tab">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                General
                            </button>
                            <button class="flex-1 px-6 py-4 text-sm font-medium text-[#D1C4E9] hover:text-white hover:bg-white/10 transition-all duration-300 border-b-2 border-transparent hover:border-[#F5B301]/50 focus:outline-none focus:border-[#F5B301]" 
                                    onclick="showTab('booking')" id="booking-tab">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Booking
                            </button>
                            <button class="flex-1 px-6 py-4 text-sm font-medium text-[#D1C4E9] hover:text-white hover:bg-white/10 transition-all duration-300 border-b-2 border-transparent hover:border-[#F5B301]/50 focus:outline-none focus:border-[#F5B301]" 
                                    onclick="showTab('service')" id="service-tab">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                Service
                            </button>
                            <button class="flex-1 px-6 py-4 text-sm font-medium text-[#D1C4E9] hover:text-white hover:bg-white/10 transition-all duration-300 border-b-2 border-transparent hover:border-[#F5B301]/50 focus:outline-none focus:border-[#F5B301]" 
                                    onclick="showTab('pricing')" id="pricing-tab">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
                                Pricing
                            </button>
                        </nav>
                    </div>
                    
                    <!-- Tab Content -->
                    <div class="p-8">
                        <!-- General Tab -->
                        <div id="general-content" class="tab-content">
                            <div class="space-y-6">
                                <div class="border-l-4 border-[#F5B301] pl-6">
                                    <h3 class="text-xl font-bold text-[#F5B301] mb-3">How do I book a Royal Maid?</h3>
                                    <p class="text-[#D1C4E9] leading-relaxed">
                                        Click on the "Book Now" button and follow the simple steps. Fill out our quick form with your requirements, 
                                        and we'll match you with the perfect maid. You can also call us directly at +256 700 123 456 for immediate assistance.
                                    </p>
                                </div>
                                
                                <div class="border-l-4 border-[#F5B301] pl-6">
                                    <h3 class="text-xl font-bold text-[#F5B301] mb-3">Are all maids professionally trained?</h3>
                                    <p class="text-[#D1C4E9] leading-relaxed">
                                        Yes! Every Royal Maid is fully trained, insured, and background-checked to ensure top-quality service and peace of mind. 
                                        They undergo comprehensive training in our Royal Maids Academy, learning proper cleaning techniques, safety protocols, and customer service.
                                    </p>
                                </div>
                                
                                <div class="border-l-4 border-[#F5B301] pl-6">
                                    <h3 class="text-xl font-bold text-[#F5B301] mb-3">Do I need to provide cleaning supplies?</h3>
                                    <p class="text-[#D1C4E9] leading-relaxed">
                                        No! Our team comes fully equipped with high-quality, eco-friendly cleaning supplies to tackle your space. 
                                        We use professional-grade products that are safe for your family and pets. You just need to provide access to water and electricity.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Booking Tab -->
                        <div id="booking-content" class="tab-content hidden">
                            <div class="space-y-6">
                                <div class="border-l-4 border-[#F5B301] pl-6">
                                    <h3 class="text-xl font-bold text-[#F5B301] mb-3">How often do I need to book?</h3>
                                    <p class="text-[#D1C4E9] leading-relaxed">
                                        You can request recurring cleanings: once a week, biweekly, once a month, or at an interval of your choice. 
                                        Many of our clients prefer weekly services to maintain a consistently clean home.
                                    </p>
                                </div>
                                
                                <div class="border-l-4 border-[#F5B301] pl-6">
                                    <h3 class="text-xl font-bold text-[#F5B301] mb-3">Can I request the same maid every time?</h3>
                                    <p class="text-[#D1C4E9] leading-relaxed">
                                        Absolutely! We want your maid to be familiar with your home and needs. We encourage building long-term relationships with your maid. 
                                        Once you find someone you love, we'll do our best to ensure they're available for your recurring bookings.
                                    </p>
                                </div>
                                
                                <div class="border-l-4 border-[#F5B301] pl-6">
                                    <h3 class="text-xl font-bold text-[#F5B301] mb-3">How far in advance should I book?</h3>
                                    <p class="text-[#D1C4E9] leading-relaxed">
                                        We recommend booking at least 24-48 hours in advance to ensure availability. For recurring services, 
                                        we can set up automatic scheduling. For same-day service, please call us directly at +256 700 123 456.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Service Tab -->
                        <div id="service-content" class="tab-content hidden">
                            <div class="space-y-6">
                                <div class="border-l-4 border-[#F5B301] pl-6">
                                    <h3 class="text-xl font-bold text-[#F5B301] mb-3">Which property types do your maids clean?</h3>
                                    <p class="text-[#D1C4E9] leading-relaxed">
                                        We specialize in cleaning residential properties—homes, condos & apartments. We also offer specialized services 
                                        for offices and small businesses, vacation rentals, and even post-construction cleanup.
                                    </p>
                                </div>
                                
                                <div class="border-l-4 border-[#F5B301] pl-6">
                                    <h3 class="text-xl font-bold text-[#F5B301] mb-3">Can I customize my cleaning service?</h3>
                                    <p class="text-[#D1C4E9] leading-relaxed">
                                        Sure. When you book a maid, you will be prompted to submit specific instructions you have for the maid. 
                                        Whether you need deep cleaning, regular maintenance, or specialized services like laundry and organization, we can tailor our service to you.
                                    </p>
                                </div>
                                
                                <div class="border-l-4 border-[#F5B301] pl-6">
                                    <h3 class="text-xl font-bold text-[#F5B301] mb-3">What if I'm not satisfied with the service?</h3>
                                    <p class="text-[#D1C4E9] leading-relaxed">
                                        We guarantee your satisfaction or we'll make it right - no questions asked. If you're not completely happy with our service, 
                                        contact us within 24 hours and we'll return to re-clean the areas of concern at no additional cost.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pricing Tab -->
                        <div id="pricing-content" class="tab-content hidden">
                            <div class="space-y-6">
                                <div class="border-l-4 border-[#F5B301] pl-6">
                                    <h3 class="text-xl font-bold text-[#F5B301] mb-3">Do I get a discount for recurring services?</h3>
                                    <p class="text-[#D1C4E9] leading-relaxed">
                                        Yes, we offer a variety of discounts for your recurring services. The more frequently you book, 
                                        the more you save. Contact us for our current recurring service rates and special packages.
                                    </p>
                                </div>
                                
                                <div class="border-l-4 border-[#F5B301] pl-6">
                                    <h3 class="text-xl font-bold text-[#F5B301] mb-3">What payment methods do you accept?</h3>
                                    <p class="text-[#D1C4E9] leading-relaxed">
                                        We accept all major credit cards, mobile money (MTN, Airtel), bank transfers, and cash payments. 
                                        For recurring services, we can set up automatic billing for your convenience.
                                    </p>
                                </div>
                                
                                <div class="border-l-4 border-[#F5B301] pl-6">
                                    <h3 class="text-xl font-bold text-[#F5B301] mb-3">Are there any hidden fees?</h3>
                                    <p class="text-[#D1C4E9] leading-relaxed">
                                        No hidden fees! Our pricing is transparent and includes all cleaning supplies, equipment, and insurance. 
                                        The price you see is the price you pay. We'll provide a detailed quote before any service begins.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Contact CTA -->
                <div class="text-center mt-12">
                    <h3 class="text-2xl font-bold text-white mb-4">Have a question? Ask away</h3>
                    <p class="text-lg text-[#D1C4E9] mb-8">Our friendly team is here to help!</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                        <a href="tel:+256700123456" 
                           class="group bg-gradient-to-r from-[#F5B301] to-[#FFD700] text-white font-['Roboto_Condensed'] tracking-wide px-8 py-4 rounded-xl font-bold text-lg hover:from-[#FFD700] hover:to-[#F5B301] transition-all duration-300 shadow-2xl hover:shadow-[#F5B301]/50 hover:scale-105 flex items-center gap-3">
                            <svg class="w-6 h-6 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span>Call Us Now</span>
                        </a>
                        <a href="mailto:info@royalmaidshub.com" 
                           class="group bg-white/20 backdrop-blur-sm text-white px-8 py-4 rounded-xl font-bold text-lg hover:bg-white/30 transition-all duration-300 border border-white/30 hover:scale-105 flex items-center gap-3">
                            <svg class="w-6 h-6 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>Email Us</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- JavaScript for Tab Functionality -->
    <script>
        function showTab(tabName) {
            // Hide all tab contents
            const contents = document.querySelectorAll('.tab-content');
            contents.forEach(content => content.classList.add('hidden'));
            
            // Remove active class from all tabs
            const tabs = document.querySelectorAll('[role="tablist"] button');
            tabs.forEach(tab => {
                tab.classList.remove('active', 'border-[#F5B301]', 'text-white');
                tab.classList.add('text-[#D1C4E9]');
            });
            
            // Show selected tab content
            document.getElementById(tabName + '-content').classList.remove('hidden');
            
            // Add active class to selected tab
            const activeTab = document.getElementById(tabName + '-tab');
            activeTab.classList.add('active', 'border-[#F5B301]', 'text-white');
            activeTab.classList.remove('text-[#D1C4E9]');
        }
        
        // Initialize first tab as active
        document.addEventListener('DOMContentLoaded', function() {
            showTab('general');
        });
    </script>

    <!-- Service Areas Section -->
    <!-- Areas We Serve Section -->
    <section class="py-20 bg-gradient-to-br from-[#512B58] to-[#3B0A45] relative overflow-hidden" style="background-image: url('{{ asset('storage/web-site-images/hero.jpg') }}'); background-size: cover; background-position: center; background-attachment: fixed;">
        <!-- Background Overlay -->
        <div class="absolute inset-0 bg-gradient-to-br from-[#512B58]/90 to-[#3B0A45]/90"></div>
        
        <!-- Background Decorations -->
        <div class="absolute top-0 left-0 w-72 h-72 bg-[#F5B301]/5 rounded-full -translate-x-36 -translate-y-36"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-[#FFD700]/5 rounded-full translate-x-48 translate-y-48"></div>
        <div class="absolute top-1/2 left-1/4 w-32 h-32 bg-[#F5B301]/10 rounded-full animate-pulse"></div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-white mb-4 font-['Roboto_Condensed'] tracking-wide">Areas We Serve</h2>
                <p class="text-xl text-[#D1C4E9] max-w-4xl mx-auto leading-relaxed">
                    We serve all areas of the country
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Kampala -->
                <div class="bg-white/10 backdrop-blur-md rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 border border-[#F5B301]/30 hover:bg-white/15 text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full mx-auto flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-white font-['Roboto_Condensed'] tracking-wide" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2 font-['Roboto_Condensed'] tracking-wide">Kampala</h3>
                    <p class="text-[#D1C4E9]/90">Capital City</p>
                </div>
                
                <!-- Entebbe -->
                <div class="bg-white/10 backdrop-blur-md rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 border border-[#F5B301]/30 hover:bg-white/15 text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full mx-auto flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-white font-['Roboto_Condensed'] tracking-wide" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white font-['Roboto_Condensed'] tracking-wide mb-2">Entebbe</h3>
                    <p class="text-[#D1C4E9]/90">Airport City</p>
                </div>
                
                <!-- Jinja -->
                <div class="bg-white/10 backdrop-blur-md rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 border border-[#F5B301]/30 hover:bg-white/15 text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full mx-auto flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-white font-['Roboto_Condensed'] tracking-wide" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white font-['Roboto_Condensed'] tracking-wide mb-2">Jinja</h3>
                    <p class="text-[#D1C4E9]/90">Source of the Nile</p>
                </div>
                
                <!-- And Many More -->
                <div class="bg-white/10 backdrop-blur-md rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 border border-[#F5B301]/30 hover:bg-white/15 text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full mx-auto flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-white font-['Roboto_Condensed'] tracking-wide" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white font-['Roboto_Condensed'] tracking-wide mb-2">And Many More</h3>
                    <p class="text-[#D1C4E9]/90">All Areas Covered</p>
                </div>
            </div>
            
            <div class="text-center mt-12">
                <p class="text-lg text-[#D1C4E9]/90 mb-6 font-['Roboto_Condensed'] tracking-wide">Don't see your area? Contact us to discuss service availability!</p>
                <a href="{{ route('booking.public') }}" 
                   class="inline-flex items-center bg-gradient-to-r from-[#F5B301] to-[#FFD700] text-white font-['Roboto_Condensed'] tracking-wide px-8 py-4 rounded-xl font-semibold hover:from-[#FFD700] hover:to-[#F5B301] transition-all duration-300 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Check Service Availability
                </a>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="py-20 bg-gradient-to-br from-[#512B58] to-[#3B0A45]">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-white mb-4">Why Choose Royal Maids Hub?</h2>
                <p class="text-xl text-[#D1C4E9] max-w-4xl mx-auto">
                    We're not just another cleaning service. We're your trusted partners in creating a royal-quality home environment.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Spotless Reputation -->
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 border border-[#F5B301]/30 hover:bg-white/15 text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full mx-auto flex items-center justify-center mb-6">
                        <svg class="w-10 h-10 text-white font-['Roboto_Condensed'] tracking-wide" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-[#F5B301] mb-4">Spotless Reputation</h3>
                    <p class="text-[#D1C4E9] leading-relaxed">
                        With over 5 years of excellence and 500+ satisfied families, Royal Maids Hub has built a reputation 
                        for delivering consistent, high-quality service that exceeds expectations every time.
                    </p>
                </div>
                
                <!-- Fully Insured -->
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 border border-[#F5B301]/30 hover:bg-white/15 text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full mx-auto flex items-center justify-center mb-6">
                        <svg class="w-10 h-10 text-white font-['Roboto_Condensed'] tracking-wide" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 1l3 6h6l-5 4 2 6-6-3-6 3 2-6-5-4h6l3-6z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-[#F5B301] mb-4">Fully Insured</h3>
                    <p class="text-[#D1C4E9] leading-relaxed">
                        We are fully insured and bonded, ensuring complete protection for both you and our team. 
                        Your peace of mind is our priority, with comprehensive coverage for all services.
                    </p>
                </div>
                
                <!-- Satisfaction Guaranteed -->
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 border border-[#F5B301]/30 hover:bg-white/15 text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full mx-auto flex items-center justify-center mb-6">
                        <svg class="w-10 h-10 text-white font-['Roboto_Condensed'] tracking-wide" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-[#F5B301] mb-4">Satisfaction Guaranteed</h3>
                    <p class="text-[#D1C4E9] leading-relaxed">
                        At the heart of Royal Maids Hub lies an unwavering commitment to delivering exceptional service. 
                        We guarantee your satisfaction or we'll make it right - no questions asked.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Ready to Experience Royal-Quality Service Section -->
    <section class="py-20 bg-gradient-to-br from-[#F5B301] to-[#FFD700]">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl lg:text-5xl font-bold text-white font-['Roboto_Condensed'] tracking-wide mb-6">Ready to Experience Royal-Quality Service?</h2>
            <p class="text-xl text-white font-['Roboto_Condensed'] tracking-wide mb-8 max-w-3xl mx-auto leading-relaxed">
                Join hundreds of satisfied families who trust Royal Maids Hub for their domestic needs. 
                <span class="font-semibold">Book your perfect maid today</span> and transform your home into a royal sanctuary.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-8">
                <a href="{{ route('booking.public') }}" 
                   class="group bg-[#512B58] text-white px-10 py-5 rounded-xl font-bold text-xl hover:bg-[#3B0A45] transition-all duration-300 shadow-2xl hover:shadow-[#512B58]/50 hover:scale-105 flex items-center gap-3">
                    <svg class="w-7 h-7 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Book Your Perfect Maid Now</span>
                    <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
                <a href="{{ route('maids.public') }}" 
                   class="group bg-white/20 backdrop-blur-sm text-white font-['Roboto_Condensed'] tracking-wide px-10 py-5 rounded-xl font-bold text-xl hover:bg-white/30 transition-all duration-300 border border-[#512B58]/30 hover:scale-105 flex items-center gap-3">
                    <svg class="w-7 h-7 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span>Meet Our Amazing Maids</span>
                </a>
            </div>
            
            <!-- Trust Indicators -->
            <div class="flex flex-wrap justify-center items-center gap-8 text-white font-['Roboto_Condensed'] tracking-wide">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-semibold">500+ Happy Families</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    <span class="font-semibold">★★★★★ 4.9/5 Rating</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 1l3 6h6l-5 4 2 6-6-3-6 3 2-6-5-4h6l3-6z"/>
                    </svg>
                    <span class="font-semibold">Fully Insured & Bonded</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-20 bg-gradient-to-br from-[#512B58] to-[#3B0A45] relative overflow-hidden">
        <!-- Background Decorations -->
        <div class="absolute top-0 left-0 w-72 h-72 bg-[#F5B301]/5 rounded-full -translate-x-36 -translate-y-36"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-[#FFD700]/5 rounded-full translate-x-48 translate-y-48"></div>
        <div class="absolute top-1/2 left-1/4 w-32 h-32 bg-[#F5B301]/10 rounded-full animate-pulse"></div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-4xl lg:text-5xl font-bold text-white mb-6 font-['Roboto_Condensed'] tracking-wide">Get In Touch</h2>
                <p class="text-xl text-[#D1C4E9] max-w-4xl mx-auto leading-relaxed">
                    Ready to find the perfect domestic help for your family? Contact us today for a consultation.
                </p>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <!-- Left Side - Image -->
                <div class="order-2 lg:order-1">
                    <div class="relative">
                        <!-- Main Image -->
                        <div class="relative rounded-2xl overflow-hidden shadow-2xl">
                            <img src="{{ asset('storage/web-site-images/team.jpg') }}" 
                                 alt="Royal Maids Team" 
                                 class="w-full h-[500px] object-cover">
                            <!-- Image Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-[#512B58]/80 to-transparent"></div>
                            
                            <!-- Floating Stats -->
                            <div class="absolute bottom-6 left-6 right-6">
                                <div class="bg-white/20 backdrop-blur-md rounded-xl p-6 border border-[#F5B301]/30">
                                    <div class="grid grid-cols-2 gap-6 text-center">
                <div>
                                            <div class="text-3xl font-bold text-white font-['Roboto_Condensed'] tracking-wide">500+</div>
                                            <div class="text-sm text-[#D1C4E9] font-['Roboto_Condensed'] tracking-wide">Happy Families</div>
                                        </div>
                        <div>
                                            <div class="text-3xl font-bold text-white font-['Roboto_Condensed'] tracking-wide">5+</div>
                                            <div class="text-sm text-[#D1C4E9] font-['Roboto_Condensed'] tracking-wide">Years Experience</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Decorative Elements -->
                        <div class="absolute -top-4 -right-4 w-24 h-24 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full flex items-center justify-center shadow-lg">
                            <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        </div>
                        
                        <div class="absolute -bottom-4 -left-4 w-16 h-16 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                    </div>
                        </div>
                        
                <!-- Right Side - Contact Form -->
                <div class="order-1 lg:order-2">
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-8 shadow-xl border border-[#F5B301]/30">
                        <h3 class="text-2xl font-bold text-white mb-6 font-['Roboto_Condensed'] tracking-wide">Request a Consultation</h3>
                        <p class="text-[#D1C4E9] mb-8 leading-relaxed">
                            Fill out the form below and we'll get back to you within 24 hours to discuss your domestic service needs.
                        </p>
                        
                        @livewire('contact-form')
                        
                        <!-- Contact Info -->
                        <div class="mt-8 pt-6 border-t border-[#F5B301]/30">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-10 h-10 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full flex items-center justify-center mb-2">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                </div>
                                    <p class="text-sm text-[#D1C4E9] font-['Roboto_Condensed'] tracking-wide">+256 703 173206</p>
                        </div>
                        
                                <div class="flex flex-col items-center">
                                    <div class="w-10 h-10 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full flex items-center justify-center mb-2">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm text-[#D1C4E9] font-['Roboto_Condensed'] tracking-wide">info@royalmaidshub.com</p>
                        </div>
                        
                                <div class="flex flex-col items-center">
                                    <div class="w-10 h-10 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full flex items-center justify-center mb-2">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm text-[#D1C4E9] font-['Roboto_Condensed'] tracking-wide">Mpelerwe Mugalu Zone</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    @include('components.home.footer')

</div>
@endsection
