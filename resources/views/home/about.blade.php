@extends('components.layouts.simple')

@section('title', 'About Us')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#512B58] via-[#3B0A45] to-[#2D1B69]">
    <!-- Hero Section with Enhanced Design -->
    <section class="relative py-20 lg:py-32 overflow-hidden"
             style="background-image: url('{{ asset('storage/web-site-images/hero.jpg') }}'); background-size: cover; background-position: center; background-attachment: fixed;">
        <div class="absolute inset-0 bg-gradient-to-r from-[#512B58]/90 to-[#3B0A45]/90"></div>
        
        <!-- Floating Elements -->
        <div class="absolute top-20 left-10 w-20 h-20 bg-[#F5B301]/20 rounded-full animate-pulse"></div>
        <div class="absolute top-40 right-20 w-16 h-16 bg-[#FFD700]/30 rounded-full animate-bounce"></div>
        <div class="absolute bottom-20 left-20 w-12 h-12 bg-[#F5B301]/25 rounded-full animate-pulse"></div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-6xl mx-auto text-center">
                <!-- Badge -->
                <div class="mb-8">
                    <span class="inline-flex items-center gap-2 px-6 py-3 rounded-full text-sm font-semibold tracking-wider bg-gradient-to-r from-[#F5B301] to-[#FFD700] text-[#512B58] shadow-lg">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                        Established Since 2019
                    </span>
                </div>
                
                <!-- Main Heading -->
                <h1 class="text-5xl lg:text-7xl font-extrabold leading-tight mb-6">
                    Raising the 
                    <span class="bg-gradient-to-r from-[#F5B301] to-[#FFD700] bg-clip-text text-transparent">
                        Standard
                    </span> 
                    of Domestic Service
                </h1>
                
                <p class="mt-4 text-[#D1C4E9] text-xl lg:text-2xl max-w-3xl mx-auto leading-relaxed">
                    From careful vetting to ongoing training and responsive support, we make premium household help accessible and reliable.
                </p>
                
                <!-- Enhanced Stats -->
                <div class="mt-16 grid grid-cols-2 md:grid-cols-4 gap-6 max-w-5xl mx-auto">
                    <div class="group bg-white/10 backdrop-blur-md border border-[#F5B301]/30 rounded-2xl p-6 text-center hover:bg-white/15 transition-all duration-300 hover:scale-105">
                        <div class="w-16 h-16 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                            <svg class="w-8 h-8 text-[#512B58]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div class="text-4xl font-bold text-white mb-2">500+</div>
                        <div class="text-sm text-[#D1C4E9]">Happy Families</div>
                    </div>
                    
                    <div class="group bg-white/10 backdrop-blur-md border border-[#F5B301]/30 rounded-2xl p-6 text-center hover:bg-white/15 transition-all duration-300 hover:scale-105">
                        <div class="w-16 h-16 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                            <svg class="w-8 h-8 text-[#512B58]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="text-4xl font-bold text-white mb-2">200+</div>
                        <div class="text-sm text-[#D1C4E9]">Active Maids</div>
                    </div>
                    
                    <div class="group bg-white/10 backdrop-blur-md border border-[#F5B301]/30 rounded-2xl p-6 text-center hover:bg-white/15 transition-all duration-300 hover:scale-105">
                        <div class="w-16 h-16 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                            <svg class="w-8 h-8 text-[#512B58]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div class="text-4xl font-bold text-white mb-2">12+</div>
                        <div class="text-sm text-[#D1C4E9]">Training Modules</div>
                    </div>
                    
                    <div class="group bg-white/10 backdrop-blur-md border border-[#F5B301]/30 rounded-2xl p-6 text-center hover:bg-white/15 transition-all duration-300 hover:scale-105">
                        <div class="w-16 h-16 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                            <svg class="w-8 h-8 text-[#512B58]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="text-4xl font-bold text-white mb-2">24h</div>
                        <div class="text-sm text-[#D1C4E9]">Response Time</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission, Values, Vision Section -->
    <section class="py-20 bg-gradient-to-br from-[#3B0A45] to-[#2D1B69]">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold bg-gradient-to-r from-[#F5B301] to-[#FFD700] text-[#512B58] mb-4">
                    Who We Are
                </span>
                <h2 class="text-4xl lg:text-5xl font-bold text-white mb-4">Our Foundation</h2>
                <p class="text-xl text-[#D1C4E9] max-w-3xl mx-auto">Built on trust, excellence, and unwavering commitment to your family's well-being</p>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <!-- Mission -->
                <div class="relative group">
                    <div class="absolute inset-0 bg-gradient-to-r from-[#F5B301] to-[#FFD700] rounded-3xl blur-xl opacity-20 group-hover:opacity-30 transition-opacity"></div>
                    <div class="relative bg-white/10 backdrop-blur-md border border-[#F5B301]/30 rounded-3xl p-8 h-full hover:border-[#F5B301] transition-all duration-300">
                        <div class="w-14 h-14 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                            <svg class="w-7 h-7 text-[#512B58]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold mb-4 text-white">Our Mission</h2>
                        <p class="text-[#D1C4E9] leading-relaxed">To elevate household support through well-trained, trustworthy domestic staff and an experience that is reliable, safe and respectful for every client and worker.</p>
                    </div>
                </div>
                
                <!-- Values -->
                <div class="relative group">
                    <div class="absolute inset-0 bg-gradient-to-r from-[#F5B301] to-[#FFD700] rounded-3xl blur-xl opacity-20 group-hover:opacity-30 transition-opacity"></div>
                    <div class="relative bg-white/10 backdrop-blur-md border border-[#F5B301]/30 rounded-3xl p-8 h-full hover:border-[#F5B301] transition-all duration-300">
                        <div class="w-14 h-14 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                            <svg class="w-7 h-7 text-[#512B58]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold mb-4 text-white">Our Values</h3>
                        <ul class="text-[#D1C4E9] space-y-3">
                            <li class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-[#F5B301] flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Integrity & Respect</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-[#F5B301] flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Safety & Quality</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-[#F5B301] flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Training & Growth</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-[#F5B301] flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Transparency & Fairness</span>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- Vision -->
                <div class="relative group">
                    <div class="absolute inset-0 bg-gradient-to-r from-[#F5B301] to-[#FFD700] rounded-3xl blur-xl opacity-20 group-hover:opacity-30 transition-opacity"></div>
                    <div class="relative bg-white/10 backdrop-blur-md border border-[#F5B301]/30 rounded-3xl p-8 h-full hover:border-[#F5B301] transition-all duration-300">
                        <div class="w-14 h-14 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                            <svg class="w-7 h-7 text-[#512B58]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold mb-4 text-white">Our Vision</h3>
                        <p class="text-[#D1C4E9] leading-relaxed">To become Uganda's most trusted and respected domestic service provider, setting the gold standard for quality, professionalism, and client satisfaction.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- What We Do Section -->
    <section class="py-20">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold bg-gradient-to-r from-[#F5B301] to-[#FFD700] text-[#512B58] mb-4">
                    Our Services
                </span>
                <h2 class="text-4xl lg:text-5xl font-bold text-white mb-4">What We Offer</h2>
                <p class="text-xl text-[#D1C4E9] max-w-3xl mx-auto">Comprehensive solutions for all your domestic staffing needs</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <div class="bg-white/10 backdrop-blur-md border border-[#F5B301]/30 rounded-2xl p-8 hover:bg-white/15 transition-all duration-300 hover:scale-105 group">
                    <div class="w-16 h-16 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-lg">
                        <svg class="w-8 h-8 text-[#512B58]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-white">Domestic Staffing</h3>
                    <p class="text-[#D1C4E9] leading-relaxed">Placement of maids, nannies, home managers and specialized caregivers with verified credentials and experience.</p>
                </div>
                
                <div class="bg-white/10 backdrop-blur-md border border-[#F5B301]/30 rounded-2xl p-8 hover:bg-white/15 transition-all duration-300 hover:scale-105 group">
                    <div class="w-16 h-16 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-lg">
                        <svg class="w-8 h-8 text-[#512B58]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-white">Training Programs</h3>
                    <p class="text-[#D1C4E9] leading-relaxed">Practical modules in housekeeping, childcare, nutrition, safety and customer care to ensure excellence.</p>
                </div>
                
                <div class="bg-white/10 backdrop-blur-md border border-[#F5B301]/30 rounded-2xl p-8 hover:bg-white/15 transition-all duration-300 hover:scale-105 group">
                    <div class="w-16 h-16 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-lg">
                        <svg class="w-8 h-8 text-[#512B58]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-white">Quality Assurance</h3>
                    <p class="text-[#D1C4E9] leading-relaxed">Screening, ongoing evaluations and responsive support with service guarantees you can trust.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Enhanced Placement Process Timeline -->
    <section class="py-20 bg-gradient-to-br from-[#3B0A45] to-[#2D1B69]">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold bg-gradient-to-r from-[#F5B301] to-[#FFD700] text-[#512B58] mb-4">
                    How It Works
                </span>
                <h2 class="text-4xl lg:text-5xl font-bold text-white mb-4">Our Placement Process</h2>
                <p class="text-xl text-[#D1C4E9] max-w-3xl mx-auto">Simple, transparent steps to find your perfect match</p>
            </div>
            
            <div class="max-w-5xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="relative group">
                        <div class="bg-white/10 backdrop-blur-md border border-[#F5B301]/30 rounded-2xl p-8 text-center hover:bg-white/15 transition-all duration-300 hover:scale-105">
                            <div class="w-20 h-20 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full flex items-center justify-center mx-auto mb-6 text-3xl font-bold text-[#512B58] shadow-lg">1</div>
                            <h3 class="text-lg font-bold mb-3 text-white">Tell Us Your Needs</h3>
                            <p class="text-sm text-[#D1C4E9]">Complete our simple booking form with your requirements and preferences.</p>
                        </div>
                    </div>
                    
                    <div class="relative group">
                        <div class="bg-white/10 backdrop-blur-md border border-[#F5B301]/30 rounded-2xl p-8 text-center hover:bg-white/15 transition-all duration-300 hover:scale-105">
                            <div class="w-20 h-20 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full flex items-center justify-center mx-auto mb-6 text-3xl font-bold text-[#512B58] shadow-lg">2</div>
                            <h3 class="text-lg font-bold mb-3 text-white">We Shortlist</h3>
                            <p class="text-sm text-[#D1C4E9]">Our team reviews candidates and presents the best matches for you.</p>
                        </div>
                    </div>
                    
                    <div class="relative group">
                        <div class="bg-white/10 backdrop-blur-md border border-[#F5B301]/30 rounded-2xl p-8 text-center hover:bg-white/15 transition-all duration-300 hover:scale-105">
                            <div class="w-20 h-20 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full flex items-center justify-center mx-auto mb-6 text-3xl font-bold text-[#512B58] shadow-lg">3</div>
                            <h3 class="text-lg font-bold mb-3 text-white">Interview & Select</h3>
                            <p class="text-sm text-[#D1C4E9]">Meet potential maids and choose the one that fits your family best.</p>
                        </div>
                    </div>
                    
                    <div class="relative group">
                        <div class="bg-white/10 backdrop-blur-md border border-[#F5B301]/30 rounded-2xl p-8 text-center hover:bg-white/15 transition-all duration-300 hover:scale-105">
                            <div class="w-20 h-20 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full flex items-center justify-center mx-auto mb-6 text-3xl font-bold text-[#512B58] shadow-lg">4</div>
                            <h3 class="text-lg font-bold mb-3 text-white">Onboard & Enjoy</h3>
                            <p class="text-sm text-[#D1C4E9]">We handle onboarding and provide ongoing support every step.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Enhanced Testimonials -->
    <section class="py-20">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold bg-gradient-to-r from-[#F5B301] to-[#FFD700] text-[#512B58] mb-4">
                    Client Reviews
                </span>
                <h2 class="text-4xl lg:text-5xl font-bold text-white mb-4">What Our Clients Say</h2>
                <p class="text-xl text-[#D1C4E9] max-w-3xl mx-auto">Real experiences from families who trust us</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <div class="bg-white/10 backdrop-blur-md border border-[#F5B301]/30 rounded-2xl p-8 hover:bg-white/15 transition-all duration-300">
                    <div class="flex items-center gap-1 mb-4">
                        <svg class="w-5 h-5 text-[#F5B301]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        <svg class="w-5 h-5 text-[#F5B301]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        <svg class="w-5 h-5 text-[#F5B301]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        <svg class="w-5 h-5 text-[#F5B301]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        <svg class="w-5 h-5 text-[#F5B301]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <p class="text-[#D1C4E9] mb-4 italic leading-relaxed">"Professional, timely and caring service. Our live-out maid is fantastic and has become like family to us."</p>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full flex items-center justify-center">
                            <span class="text-[#512B58] font-bold">SJ</span>
                        </div>
                        <div>
                            <p class="font-semibold text-white">Sarah J.</p>
                            <p class="text-sm text-[#D1C4E9]">Ntinda, Kampala</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white/10 backdrop-blur-md border border-[#F5B301]/30 rounded-2xl p-8 hover:bg-white/15 transition-all duration-300">
                    <div class="flex items-center gap-1 mb-4">
                        <svg class="w-5 h-5 text-[#F5B301]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        <svg class="w-5 h-5 text-[#F5B301]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        <svg class="w-5 h-5 text-[#F5B301]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        <svg class="w-5 h-5 text-[#F5B301]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        <svg class="w-5 h-5 text-[#F5B301]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <p class="text-[#D1C4E9] mb-4 italic leading-relaxed">"Clear process and great support when we needed a replacement. The team really cares about finding the right fit."</p>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full flex items-center justify-center">
                            <span class="text-[#512B58] font-bold">MK</span>
                        </div>
                        <div>
                            <p class="font-semibold text-white">Michael K.</p>
                            <p class="text-sm text-[#D1C4E9]">Kololo, Kampala</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white/10 backdrop-blur-md border border-[#F5B301]/30 rounded-2xl p-8 hover:bg-white/15 transition-all duration-300">
                    <div class="flex items-center gap-1 mb-4">
                        <svg class="w-5 h-5 text-[#F5B301]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        <svg class="w-5 h-5 text-[#F5B301]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        <svg class="w-5 h-5 text-[#F5B301]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        <svg class="w-5 h-5 text-[#F5B301]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        <svg class="w-5 h-5 text-[#F5B301]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <p class="text-[#D1C4E9] mb-4 italic leading-relaxed">"Training shines through in the day-to-day work. Our maid is professional, proactive, and a joy to have around."</p>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full flex items-center justify-center">
                            <span class="text-[#512B58] font-bold">AB</span>
                        </div>
                        <div>
                            <p class="font-semibold text-white">Aminah B.</p>
                            <p class="text-sm text-[#D1C4E9]">Najjera, Kampala</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Enhanced CTA Section -->
    <section class="py-20 bg-gradient-to-br from-[#3B0A45] to-[#2D1B69]">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto bg-gradient-to-r from-[#F5B301] to-[#FFD700] rounded-3xl p-12 text-center relative overflow-hidden shadow-2xl">
                <!-- Decorative Elements -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                
                <div class="relative z-10">
                    <h2 class="text-4xl lg:text-5xl font-bold text-[#512B58] mb-6">Ready to Transform Your Home?</h2>
                    <p class="text-lg text-[#512B58]/90 mb-8 max-w-2xl mx-auto">Join thousands of satisfied families who trust Royal Maids Hub for their domestic service needs.</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('booking.public') }}" class="inline-flex items-center justify-center gap-2 bg-[#512B58] text-white font-bold px-8 py-4 rounded-xl hover:bg-[#3B0A45] transition-all shadow-lg hover:scale-105">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Book a Maid Now
                        </a>
                        <a href="{{ route('maids.public') }}" class="inline-flex items-center justify-center gap-2 bg-white text-[#512B58] font-bold px-8 py-4 rounded-xl hover:bg-[#512B58]/5 transition-all border-2 border-[#512B58] hover:scale-105">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            View Our Maids
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    @include('components.home.footer')
</div>
@endsection


