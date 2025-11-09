<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen antialiased">
        <!-- Enhanced Background with Hero Image -->
        <div class="relative min-h-screen overflow-hidden">
            <!-- Background Image -->
            <div class="absolute inset-0">
                <img 
                    src="{{ asset('storage/web-site-images/hero.jpg') }}" 
                    alt="Royal Maids Hub Background" 
                    class="w-full h-full object-cover"
                />
            </div>
            
            <!-- Overlay Gradient -->
            <div class="absolute inset-0 bg-gradient-to-br from-[#512B58]/90 via-[#3B0A45]/85 to-[#2D1B69]/90"></div>
            
            <!-- Animated Background Elements -->
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute top-20 left-10 w-40 h-40 bg-[#F5B301]/10 rounded-full blur-3xl animate-pulse"></div>
                <div class="absolute top-40 right-20 w-60 h-60 bg-[#FFD700]/10 rounded-full blur-3xl animate-bounce"></div>
                <div class="absolute bottom-20 left-1/3 w-48 h-48 bg-[#F5B301]/10 rounded-full blur-3xl animate-pulse"></div>
                <div class="absolute -top-10 right-1/3 w-32 h-32 bg-purple-500/10 rounded-full blur-2xl"></div>
            </div>
            
            <!-- Main Content Container -->
            <div class="relative z-10 flex min-h-screen flex-col items-center justify-center gap-6 p-6 md:p-10">
                <!-- Logo and Brand -->
                <div class="w-full max-w-md mb-4">
                    <a href="{{ route('home') }}" class="flex flex-col items-center gap-3 font-medium group" wire:navigate>
                        <div class="relative">
                            <div class="absolute inset-0 bg-gradient-to-r  rounded-full blur-lg opacity-75 group-hover:opacity-100 transition-opacity"></div>
                            <div class="relative flex h-20 w-20 md:h-24 md:w-24 items-center justify-center rounded-full bg-dark p-2  group-hover:scale-110 ">
                                <img hadow-2xl
                                    src="{{ asset('storage/web-site-images/Royal_Maids_Hubjpg-31-removebg-preview.png') }}" 
                                    alt="Royal Maids Hub Logo" 
                                    class="w-full h-full object-contain"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='block'"
                                >
                                <!-- Fallback SVG if image fails to load -->
                                <svg class="w-12 h-12 text-[#512B58] hidden" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            </div>
                        </div>
                        <span class="sr-only">{{ config('app.name', 'Royal Maids Hub') }}</span>
                    </a>
                </div>

                <!-- Card Container -->
                <div class="w-full max-w-md">
                    <div class="rounded-3xl bg-white/10 backdrop-blur-xl border border-white/20 shadow-2xl overflow-hidden">
                        <!-- Decorative Header -->
                        <div class="bg-gradient-to-r from-[#F5B301] to-[#FFD700] h-2"></div>
                        
                        <!-- Form Content -->
                        <div class="p-8 md:p-10">
                            {{ $slot }}
                        </div>
                    </div>
                </div>

                <!-- Footer Links -->
                <div class="w-full max-w-md mt-4">
                    <div class="text-center text-white/80 text-sm space-y-2">
                        <p>&copy; {{ date('Y') }} Royal Maids Hub. All rights reserved.</p>
                        <div class="flex justify-center gap-4">
                            <a href="{{ route('home') }}" class="hover:text-[#F5B301] transition-colors">Home</a>
                            <span class="text-white/40">•</span>
                            <a href="{{ route('privacy.public') }}" class="hover:text-[#F5B301] transition-colors">Privacy</a>
                            <span class="text-white/40">•</span>
                            <a href="{{ route('contact.public') }}" class="hover:text-[#F5B301] transition-colors">Contact</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Nav Scrollable Fix & Form Styles -->
        <style>
            @media (max-width: 640px) {
                #mobile-menu {
                    max-height: calc(100vh - 80px);
                    overflow-y: auto;
                }
            }

            /* Ensure Flux inputs are visible on dark background */
            .backdrop-blur-xl [data-flux-field] {
                --input-bg: white;
                --input-border: rgba(229, 231, 235, 0.8);
                --input-text: #1f2937;
            }

            .backdrop-blur-xl [data-flux-button] {
                background: linear-gradient(135deg, #F5B301, #FFD700);
                color: #512B58;
                font-weight: 600;
                transition: all 0.3s ease;
            }

            .backdrop-blur-xl [data-flux-button]:hover {
                background: linear-gradient(135deg, #FFD700, #F5B301);
                transform: translateY(-2px);
                box-shadow: 0 10px 20px rgba(245, 179, 1, 0.3);
            }

            .backdrop-blur-xl [data-flux-checkbox] label {
                color: white;
            }

            .backdrop-blur-xl [data-flux-link] {
                color: #F5B301;
                transition: all 0.2s ease;
            }

            .backdrop-blur-xl [data-flux-link]:hover {
                color: #FFD700;
                text-decoration: underline;
            }

            .backdrop-blur-xl [data-flux-status] {
                background: rgba(255, 255, 255, 0.9);
                color: #1f2937;
                border: 1px solid rgba(255, 255, 255, 0.3);
            }
        </style>

        @fluxScripts
    </body>
</html>
