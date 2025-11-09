<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
    
    <!-- Additional Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    
    @livewireStyles
</head>
<body class="font-sans antialiased">
    @php
        $settings = \App\Models\CompanySetting::current();
    @endphp
    
    <!-- Custom Body Scripts -->
    @if($settings->body_scripts)
        {!! $settings->body_scripts !!}
    @endif
    
    <!-- Google Tag Manager (noscript) -->
    @if($settings->google_tag_manager_id)
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $settings->google_tag_manager_id }}"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    @endif
    <!-- Navigation Header -->
    <nav class="relative bg-gradient-to-r from-[#512B58]/95 to-[#3B0A45]/95 backdrop-blur-sm shadow-lg sticky top-0 z-50" 
         style="background-image: url('/storage/web-site-images/hero.jpg'); background-size: cover; background-position: center; background-blend-mode: overlay;">
        <!-- Overlay for better text readability -->
        <div class="absolute inset-0 bg-gradient-to-r from-[#512B58]/90 to-[#3B0A45]/90"></div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3">
                        @if($settings->logo_url)
                            <img src="{{ $settings->logo_url }}" alt="{{ $settings->company_name }}" class="h-12 w-auto object-contain">
                        @else
                            <div class="w-12 h-12 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full flex items-center justify-center shadow-lg">
                                <svg class="w-8 h-8 text-[#512B58]" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            </div>
                        @endif
                        <div class="text-white">
                            <h1 class="text-2xl font-bold">{{ $settings->company_name }}</h1>
                            <p class="text-sm text-[#D1C4E9]">Premium Domestic Services</p>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden lg:flex items-center space-x-8">
                    <!-- Main Navigation -->
                    <div class="flex items-center space-x-6">
                        <a href="{{ route('home') }}" class="text-white hover:text-[#F5B301] transition-colors duration-200 font-medium flex items-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span>Home</span>
                        </a>
                        
                        <!-- Services Dropdown -->
                        <div class="relative group">
                            <button class="text-white hover:text-[#F5B301] transition-colors duration-200 font-medium flex items-center space-x-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                <span>Services</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div class="absolute top-full left-0 mt-2 w-64 bg-white/95 backdrop-blur-sm rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300">
                                <div class="py-2">
                                    <a href="{{ route('maids.public') }}" class="block px-4 py-2 text-gray-800 hover:bg-[#F5B301]/20 hover:text-[#512B58] transition-colors duration-200">Our Maids</a>
                                    <a href="{{ route('packages.public') }}" class="block px-4 py-2 text-gray-800 hover:bg-[#F5B301]/20 hover:text-[#512B58] transition-colors duration-200">Service Packages</a>
                                    <a href="{{ route('training.public') }}" class="block px-4 py-2 text-gray-800 hover:bg-[#F5B301]/20 hover:text-[#512B58] transition-colors duration-200">Training Programs</a>
                                    <a href="{{ route('quality.public') }}" class="block px-4 py-2 text-gray-800 hover:bg-[#F5B301]/20 hover:text-[#512B58] transition-colors duration-200">Quality Assurance</a>
                                </div>
                            </div>
                        </div>
                        
                        <a href="#packages" class="text-white hover:text-[#F5B301] transition-colors duration-200 font-medium flex items-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            <span>Packages</span>
                        </a>
                        
                      
                        
                        <a href="{{ route('about.public') }}" class="text-white hover:text-[#F5B301] transition-colors duration-200 font-medium flex items-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <span>About</span>
                        </a>

                        <!-- quality assurance -->
                         <a href="{{ route('quality.public') }}" class="text-white hover:text-[#F5B301] transition-colors duration-200 font-medium flex items-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <span>Quality Assurance</span>
                        </a>

                        
                        
                        <a href="{{ route('contact.public') }}" class="text-white hover:text-[#F5B301] transition-colors duration-200 font-medium flex items-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>Contact</span>
                        </a>
                    </div>
                    
                    <!-- Right Side Actions -->
                    <div class="flex items-center space-x-4">
                        @auth
                            <!-- User Menu -->
                            <div class="relative group">
                                <button class="flex items-center space-x-2 text-white hover:text-[#F5B301] transition-colors duration-200">
                                    <div class="w-8 h-8 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full flex items-center justify-center">
                                        <span class="text-[#512B58] font-semibold text-sm">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                    </div>
                                    <span class="font-medium">{{ auth()->user()->name }}</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div class="absolute top-full right-0 mt-2 w-48 bg-white/95 backdrop-blur-sm rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300">
                                    <div class="py-2">
                                        <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-gray-800 hover:bg-[#F5B301]/20 hover:text-[#512B58] transition-colors duration-200">Dashboard</a>
                                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-800 hover:bg-[#F5B301]/20 hover:text-[#512B58] transition-colors duration-200">Profile</a>
                                        <a href="{{ route('bookings.index') }}" class="block px-4 py-2 text-gray-800 hover:bg-[#F5B301]/20 hover:text-[#512B58] transition-colors duration-200">My Bookings</a>
                                        <div class="border-t border-gray-200 my-1"></div>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-gray-800 hover:bg-[#F5B301]/20 hover:text-[#512B58] transition-colors duration-200">
                                                Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Login/Register Buttons -->
                            <a href="{{ route('login') }}" 
                               class="text-white hover:text-[#F5B301] transition-colors duration-200 font-medium flex items-center space-x-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                </svg>
                                <span>Login</span>
                            </a>
                            
                         
                        @endauth
                        
                        <!-- CTA Button -->
                        <a href="{{ route('booking.public') }}" 
                           class="bg-gradient-to-r from-[#F5B301] to-[#FFD700] text-[#512B58] px-6 py-2 rounded-lg font-semibold hover:from-[#FFD700] hover:to-[#F5B301] transition-all duration-300 shadow-lg hover:shadow-xl flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>Book Now</span>
                        </a>
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="lg:hidden">
                    <button type="button" id="mobile-menu-button" class="text-white hover:text-[#F5B301] focus:outline-none focus:text-[#F5B301] transition-colors duration-200">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation Menu -->
            <div id="mobile-menu" class="lg:hidden hidden">
                <div class="px-2 pt-2 pb-3 space-y-1 bg-white/95 backdrop-blur-sm rounded-lg mt-2 shadow-xl">
                    <a href="{{ route('home') }}" class="block px-3 py-2 text-gray-800 hover:text-[#512B58] hover:bg-[#F5B301]/20 rounded-md transition-colors duration-200 font-medium">Home</a>
                    <a href="{{ route('about.public') }}" class="block px-3 py-2 text-gray-800 hover:text-[#512B58] hover:bg-[#F5B301]/20 rounded-md transition-colors duration-200 font-medium">About</a>
                    <a href="{{ route('maids.public') }}" class="block px-3 py-2 text-gray-800 hover:text-[#512B58] hover:bg-[#F5B301]/20 rounded-md transition-colors duration-200 font-medium">Our Maids</a>
                    <a href="{{ route('packages.public') }}" class="block px-3 py-2 text-gray-800 hover:text-[#512B58] hover:bg-[#F5B301]/20 rounded-md transition-colors duration-200 font-medium">Packages</a>
                    <a href="{{ route('training.public') }}" class="block px-3 py-2 text-gray-800 hover:text-[#512B58] hover:bg-[#F5B301]/20 rounded-md transition-colors duration-200 font-medium">Training Programs</a>
                    <a href="{{ route('quality.public') }}" class="block px-3 py-2 text-gray-800 hover:text-[#512B58] hover:bg-[#F5B301]/20 rounded-md transition-colors duration-200 font-medium">Quality Assurance</a>
                    <a href="{{ route('contact.public') }}" class="block px-3 py-2 text-gray-800 hover:text-[#512B58] hover:bg-[#F5B301]/20 rounded-md transition-colors duration-200 font-medium">Contact</a>
                    <a href="{{ route('booking.public') }}" class="block px-3 py-2 text-gray-800 hover:text-[#512B58] hover:bg-[#F5B301]/20 rounded-md transition-colors duration-200 font-medium">Book Now</a>
                    
                    <div class="border-t border-gray-200 my-2"></div>
                    
                    @auth
                        <div class="px-3 py-2">
                            <div class="flex items-center space-x-3 mb-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-[#F5B301] to-[#FFD700] rounded-full flex items-center justify-center">
                                    <span class="text-[#512B58] font-semibold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                                    <p class="text-sm text-gray-600">{{ auth()->user()->email }}</p>
                                </div>
                            </div>
                            <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-gray-800 hover:text-[#512B58] hover:bg-[#F5B301]/20 rounded-md transition-colors duration-200">Dashboard</a>
                            <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-gray-800 hover:text-[#512B58] hover:bg-[#F5B301]/20 rounded-md transition-colors duration-200">Profile</a>
                            <a href="{{ route('bookings.index') }}" class="block px-3 py-2 text-gray-800 hover:text-[#512B58] hover:bg-[#F5B301]/20 rounded-md transition-colors duration-200">My Bookings</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-3 py-2 text-gray-800 hover:text-[#512B58] hover:bg-[#F5B301]/20 rounded-md transition-colors duration-200">
                                    Logout
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="px-3 py-2 space-y-2">
                            <a href="{{ route('login') }}" 
                               class="block w-full text-center bg-gradient-to-r from-[#512B58] to-[#3B0A45] text-white px-4 py-2 rounded-lg font-semibold hover:from-[#3B0A45] hover:to-[#512B58] transition-all duration-300">
                                Login
                            </a>
                            <a href="{{ route('booking.public') }}" 
                               class="block w-full text-center bg-gradient-to-r from-[#F5B301] to-[#FFD700] text-[#512B58] px-4 py-2 rounded-lg font-semibold hover:from-[#FFD700] hover:to-[#F5B301] transition-all duration-300">
                                Book Now
                            </a>
                        </div>
                    @endauth
                    
                    <div class="px-3 py-2">
                        <a href="{{ route('booking.public') }}" 
                           class="block w-full bg-gradient-to-r from-[#F5B301] to-[#FFD700] text-[#512B58] px-4 py-3 rounded-lg font-semibold text-center hover:from-[#FFD700] hover:to-[#F5B301] transition-all duration-300 shadow-lg">
                            Book Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main>
        {{ $slot ?? '' }}
        @hasSection('content')
            @yield('content')
        @endif
    </main>

    <!-- WhatsApp Floating Button & Back to Top -->
    <div class="fixed bottom-4 right-4 md:bottom-6 md:right-6 z-50 flex flex-col gap-3 md:gap-4">
        <!-- WhatsApp Button -->
        <a 
            href="https://wa.me/256784581166?text=Hello! I'm interested in learning more about Royal Maids Hub services."
            target="_blank"
            rel="noopener noreferrer"
            class="group relative bg-green-500 hover:bg-green-600 text-white rounded-full p-3 md:p-4 shadow-2xl hover:shadow-green-500/50 transition-all duration-300 hover:scale-110 animate-bounce"
            aria-label="Contact us on WhatsApp"
        >
            <!-- WhatsApp Icon -->
            <svg class="w-6 h-6 md:w-7 md:h-7" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
            
            <!-- Pulse Animation -->
            <span class="absolute top-0 left-0 w-full h-full bg-green-500 rounded-full animate-ping opacity-20"></span>
            
            <!-- Tooltip -->
            <div class="absolute right-full mr-3 top-1/2 -translate-y-1/2 bg-gray-900 text-white px-3 py-2 rounded-lg text-sm whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                Chat with us on WhatsApp
                <div class="absolute left-full top-1/2 -translate-y-1/2 border-8 border-transparent border-l-gray-900"></div>
            </div>
        </a>

        <!-- Back to Top Button -->
        <button 
            id="back-to-top"
            class="bg-gradient-to-r from-[#F5B301] to-[#FFD700] hover:from-[#FFD700] hover:to-[#F5B301] text-[#512B58] rounded-full p-3 md:p-4 shadow-2xl hover:shadow-[#F5B301]/50 transition-all duration-300 opacity-0 invisible hover:scale-110"
            aria-label="Back to top"
        >
            <svg class="w-6 h-6 md:w-7 md:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
            </svg>
        </button>
    </div>

    <!-- Mobile Menu JavaScript & Scroll to Top -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            const backToTopButton = document.getElementById('back-to-top');
            
            // Mobile menu toggle
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
            });

            // Close mobile menu when clicking on a link
            const mobileLinks = mobileMenu.querySelectorAll('a');
            mobileLinks.forEach(link => {
                link.addEventListener('click', function() {
                    mobileMenu.classList.add('hidden');
                });
            });

            // Close mobile menu when clicking outside
            document.addEventListener('click', function(event) {
                if (!mobileMenuButton.contains(event.target) && !mobileMenu.contains(event.target)) {
                    mobileMenu.classList.add('hidden');
                }
            });

            // Back to Top Button
            function toggleBackToTop() {
                if (window.scrollY > 300) {
                    backToTopButton.classList.remove('opacity-0', 'invisible');
                    backToTopButton.classList.add('opacity-100', 'visible');
                } else {
                    backToTopButton.classList.add('opacity-0', 'invisible');
                    backToTopButton.classList.remove('opacity-100', 'visible');
                }
            }

            // Show/hide button on scroll
            window.addEventListener('scroll', toggleBackToTop);

            // Smooth scroll to top
            backToTopButton.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        });
    </script>
    @livewireScripts
    @stack('scripts')
    
    <!-- Custom Footer Scripts -->
    @if($settings->footer_scripts)
        {!! $settings->footer_scripts !!}
    @endif
</body>
</html>
