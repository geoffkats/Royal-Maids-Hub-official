<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <flux:heading size="xl" class="text-white">{{ __('Company Settings') }}</flux:heading>
        <flux:subheading>{{ __('Manage your company information, branding, SEO, and integrations') }}</flux:subheading>
    </div>

    <!-- Success Message -->
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
            <div class="flex items-center gap-2 text-green-700 dark:text-green-300">
                <x-flux::icon.check-circle class="w-5 h-5" />
                <span class="font-medium">{{ session('message') }}</span>
            </div>
        </div>
    @endif

    <form wire:submit="save">
        <!-- Tabs -->
        <div class="mb-6 border-b border-neutral-200 dark:border-neutral-700">
            <nav class="flex space-x-8" aria-label="Tabs">
                <button type="button" wire:click="setActiveTab('general')" 
                    class="{{ $activeTab === 'general' ? 'border-[#F5B301] text-[#F5B301]' : 'border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <x-flux::icon.building-office class="w-5 h-5 inline-block mr-2" />
                    General
                </button>
                <button type="button" wire:click="setActiveTab('branding')" 
                    class="{{ $activeTab === 'branding' ? 'border-[#F5B301] text-[#F5B301]' : 'border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <x-flux::icon.photo class="w-5 h-5 inline-block mr-2" />
                    Branding
                </button>
                <button type="button" wire:click="setActiveTab('seo')" 
                    class="{{ $activeTab === 'seo' ? 'border-[#F5B301] text-[#F5B301]' : 'border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <x-flux::icon.magnifying-glass class="w-5 h-5 inline-block mr-2" />
                    SEO & Meta Tags
                </button>
                <button type="button" wire:click="setActiveTab('scripts')" 
                    class="{{ $activeTab === 'scripts' ? 'border-[#F5B301] text-[#F5B301]' : 'border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <x-flux::icon.code-bracket class="w-5 h-5 inline-block mr-2" />
                    Scripts & Analytics
                </button>
                <button type="button" wire:click="setActiveTab('social')" 
                    class="{{ $activeTab === 'social' ? 'border-[#F5B301] text-[#F5B301]' : 'border-transparent text-neutral-500 hover:text-neutral-700 hover:border-neutral-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <x-flux::icon.share class="w-5 h-5 inline-block mr-2" />
                    Social Media
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="space-y-6">
            <!-- General Tab -->
            @if($activeTab === 'general')
            <div class="space-y-4 rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
                <flux:heading size="lg" class="text-white mb-4">{{ __('Company Information') }}</flux:heading>
                
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <flux:input wire:model="company_name" :label="__('Company Name')" required />
                        @error('company_name') <flux:text color="red" class="text-sm">{{ $message }}</flux:text> @enderror
                    </div>

                    <div>
                        <flux:input wire:model="company_email" :label="__('Company Email')" type="email" />
                        @error('company_email') <flux:text color="red" class="text-sm">{{ $message }}</flux:text> @enderror
                    </div>

                    <div>
                        <flux:input wire:model="company_phone" :label="__('Company Phone')" />
                    </div>

                    <div>
                        <flux:input wire:model="support_email" :label="__('Support Email')" type="email" />
                    </div>

                    <div class="md:col-span-2">
                        <flux:textarea wire:model="company_address" :label="__('Company Address')" rows="3" />
                    </div>

                    <div class="md:col-span-2">
                        <flux:textarea wire:model="business_hours" :label="__('Business Hours')" rows="3" 
                            placeholder="Mon-Fri: 8:00 AM - 6:00 PM&#10;Sat: 9:00 AM - 4:00 PM&#10;Sun: Closed" />
                    </div>
                </div>
            </div>
            @endif

            <!-- Branding Tab -->
            @if($activeTab === 'branding')
            <div class="space-y-4 rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
                <flux:heading size="lg" class="text-white mb-4">{{ __('Branding & Images') }}</flux:heading>
                
                <div class="grid gap-6 md:grid-cols-2">
                    <!-- Logo -->
                    <div>
                        <label class="block text-sm font-medium text-[#D1C4E9] mb-2">{{ __('Logo (Light Mode)') }}</label>
                        @if($current_logo)
                            <div class="mb-2">
                                <img src="{{ Storage::url($current_logo) }}" alt="Current Logo" class="h-16 object-contain bg-white p-2 rounded">
                            </div>
                        @endif
                        <input type="file" wire:model="logo" accept="image/*" 
                            class="w-full rounded-lg border border-[#F5B301]/30 bg-[#3B0A45] text-white px-3 py-2 focus:border-[#F5B301] focus:ring-2 focus:ring-[#F5B301]/20">
                        @error('logo') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                        <p class="text-xs text-[#D1C4E9]/70 mt-1">Recommended: PNG with transparent background, max 2MB</p>
                    </div>

                    <!-- Dark Logo -->
                    <div>
                        <label class="block text-sm font-medium text-[#D1C4E9] mb-2">{{ __('Logo (Dark Mode)') }}</label>
                        @if($current_logo_dark)
                            <div class="mb-2">
                                <img src="{{ Storage::url($current_logo_dark) }}" alt="Current Dark Logo" class="h-16 object-contain bg-gray-900 p-2 rounded">
                            </div>
                        @endif
                        <input type="file" wire:model="logo_dark" accept="image/*" 
                            class="w-full rounded-lg border border-[#F5B301]/30 bg-[#3B0A45] text-white px-3 py-2 focus:border-[#F5B301] focus:ring-2 focus:ring-[#F5B301]/20">
                        @error('logo_dark') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                        <p class="text-xs text-[#D1C4E9]/70 mt-1">Optional: Logo for dark backgrounds</p>
                    </div>

                    <!-- Favicon -->
                    <div>
                        <label class="block text-sm font-medium text-[#D1C4E9] mb-2">{{ __('Favicon') }}</label>
                        @if($current_favicon)
                            <div class="mb-2">
                                <img src="{{ Storage::url($current_favicon) }}" alt="Current Favicon" class="h-8 w-8 object-contain bg-white p-1 rounded">
                            </div>
                        @endif
                        <input type="file" wire:model="favicon" accept="image/*" 
                            class="w-full rounded-lg border border-[#F5B301]/30 bg-[#3B0A45] text-white px-3 py-2 focus:border-[#F5B301] focus:ring-2 focus:ring-[#F5B301]/20">
                        @error('favicon') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                        <p class="text-xs text-[#D1C4E9]/70 mt-1">Recommended: 32x32px or 64x64px PNG/ICO, max 1MB</p>
                    </div>

                    <!-- OG Image -->
                    <div>
                        <label class="block text-sm font-medium text-[#D1C4E9] mb-2">{{ __('Social Share Image (OG Image)') }}</label>
                        @if($current_og_image)
                            <div class="mb-2">
                                <img src="{{ Storage::url($current_og_image) }}" alt="Current OG Image" class="h-24 object-contain bg-white p-2 rounded">
                            </div>
                        @endif
                        <input type="file" wire:model="og_image" accept="image/*" 
                            class="w-full rounded-lg border border-[#F5B301]/30 bg-[#3B0A45] text-white px-3 py-2 focus:border-[#F5B301] focus:ring-2 focus:ring-[#F5B301]/20">
                        @error('og_image') <span class="text-red-400 text-sm mt-1 block">{{ $message }}</span> @enderror
                        <p class="text-xs text-[#D1C4E9]/70 mt-1">Recommended: 1200x630px, max 2MB</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- SEO Tab -->
            @if($activeTab === 'seo')
            <div class="space-y-6">
                <!-- Basic SEO -->
                <div class="space-y-4 rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
                    <flux:heading size="lg" class="text-white mb-4">{{ __('SEO Meta Tags') }}</flux:heading>
                    
                    <div class="space-y-4">
                        <div>
                            <flux:input wire:model="meta_title" :label="__('Meta Title')" 
                                placeholder="Royal Maids Hub - Professional Maid Services" />
                            <p class="text-xs text-[#D1C4E9]/70 mt-1">Recommended: 50-60 characters</p>
                        </div>

                        <div>
                            <flux:textarea wire:model="meta_description" :label="__('Meta Description')" rows="3" 
                                placeholder="Professional maid services with trained staff..." />
                            <p class="text-xs text-[#D1C4E9]/70 mt-1">Recommended: 150-160 characters</p>
                        </div>

                        <div>
                            <flux:input wire:model="meta_keywords" :label="__('Meta Keywords')" 
                                placeholder="maid service, cleaning, housekeeping" />
                            <p class="text-xs text-[#D1C4E9]/70 mt-1">Comma-separated keywords</p>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2">
                            <flux:input wire:model="meta_author" :label="__('Meta Author')" />
                            
                            <div>
                                <label class="block text-sm font-medium text-[#D1C4E9] mb-2">{{ __('Meta Robots') }}</label>
                                <select wire:model="meta_robots" 
                                    class="w-full rounded-lg border border-[#F5B301]/30 bg-[#3B0A45] text-white px-3 py-2 focus:border-[#F5B301] focus:ring-2 focus:ring-[#F5B301]/20">
                                    <option value="index, follow">Index, Follow</option>
                                    <option value="noindex, follow">No Index, Follow</option>
                                    <option value="index, nofollow">Index, No Follow</option>
                                    <option value="noindex, nofollow">No Index, No Follow</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Open Graph -->
                <div class="space-y-4 rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
                    <flux:heading size="lg" class="text-white mb-4">{{ __('Open Graph (Facebook, LinkedIn)') }}</flux:heading>
                    
                    <div class="space-y-4">
                        <flux:input wire:model="og_title" :label="__('OG Title')" />
                        <flux:textarea wire:model="og_description" :label="__('OG Description')" rows="2" />
                        
                        <div>
                            <label class="block text-sm font-medium text-[#D1C4E9] mb-2">{{ __('OG Type') }}</label>
                            <select wire:model="og_type" 
                                class="w-full rounded-lg border border-[#F5B301]/30 bg-[#3B0A45] text-white px-3 py-2 focus:border-[#F5B301] focus:ring-2 focus:ring-[#F5B301]/20">
                                <option value="website">Website</option>
                                <option value="article">Article</option>
                                <option value="business.business">Business</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Twitter Card -->
                <div class="space-y-4 rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
                    <flux:heading size="lg" class="text-white mb-4">{{ __('Twitter Card') }}</flux:heading>
                    
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-[#D1C4E9] mb-2">{{ __('Card Type') }}</label>
                            <select wire:model="twitter_card" 
                                class="w-full rounded-lg border border-[#F5B301]/30 bg-[#3B0A45] text-white px-3 py-2 focus:border-[#F5B301] focus:ring-2 focus:ring-[#F5B301]/20">
                                <option value="summary">Summary</option>
                                <option value="summary_large_image">Summary Large Image</option>
                            </select>
                        </div>
                        <flux:input wire:model="twitter_site" :label="__('Twitter Site')" placeholder="@royalmaids" />
                        <flux:input wire:model="twitter_creator" :label="__('Twitter Creator')" placeholder="@creator" />
                    </div>
                </div>

                <!-- Google Services -->
                <div class="space-y-4 rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
                    <flux:heading size="lg" class="text-white mb-4">{{ __('Google Services') }}</flux:heading>
                    
                    <div class="space-y-4">
                        <div>
                            <flux:input wire:model="google_site_verification" :label="__('Google Site Verification')" 
                                placeholder="google-site-verification=xxxxx" />
                            <p class="text-xs text-[#D1C4E9]/70 mt-1">From Google Search Console</p>
                        </div>

                        <div>
                            <flux:textarea wire:model="google_search_console" :label="__('Google Search Console Code')" rows="3" 
                                placeholder="Paste your verification meta tag or code here" />
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Scripts Tab -->
            @if($activeTab === 'scripts')
            <div class="space-y-6">
                <!-- Analytics IDs -->
                <div class="space-y-4 rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
                    <flux:heading size="lg" class="text-white mb-4">{{ __('Analytics & Tracking IDs') }}</flux:heading>
                    
                    <div class="grid gap-4 md:grid-cols-3">
                        <div>
                            <flux:input wire:model="google_analytics_id" :label="__('Google Analytics ID')" 
                                placeholder="G-XXXXXXXXXX or UA-XXXXXXXXX-X" />
                            <p class="text-xs text-[#D1C4E9]/70 mt-1">GA4 or Universal Analytics</p>
                        </div>

                        <div>
                            <flux:input wire:model="google_tag_manager_id" :label="__('Google Tag Manager ID')" 
                                placeholder="GTM-XXXXXXX" />
                            <p class="text-xs text-[#D1C4E9]/70 mt-1">From Tag Manager</p>
                        </div>

                        <div>
                            <flux:input wire:model="facebook_pixel_id" :label="__('Facebook Pixel ID')" 
                                placeholder="1234567890" />
                            <p class="text-xs text-[#D1C4E9]/70 mt-1">From Meta Business</p>
                        </div>
                    </div>
                </div>

                <!-- Custom Scripts -->
                <div class="space-y-4 rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
                    <flux:heading size="lg" class="text-white mb-4">{{ __('Custom Scripts') }}</flux:heading>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-[#D1C4E9] mb-2">{{ __('Head Scripts') }}</label>
                            <textarea wire:model="head_scripts" rows="6" 
                                class="w-full rounded-lg border border-[#F5B301]/30 bg-[#3B0A45] text-white px-3 py-2 focus:border-[#F5B301] focus:ring-2 focus:ring-[#F5B301]/20 font-mono text-sm"
                                placeholder="<script>&#10;  // Your custom scripts here&#10;</script>"></textarea>
                            <p class="text-xs text-[#D1C4E9]/70 mt-1">Scripts to be added in the &lt;head&gt; section</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-[#D1C4E9] mb-2">{{ __('Body Scripts (After opening <body>)') }}</label>
                            <textarea wire:model="body_scripts" rows="6" 
                                class="w-full rounded-lg border border-[#F5B301]/30 bg-[#3B0A45] text-white px-3 py-2 focus:border-[#F5B301] focus:ring-2 focus:ring-[#F5B301]/20 font-mono text-sm"
                                placeholder="<script>&#10;  // Scripts after body tag&#10;</script>"></textarea>
                            <p class="text-xs text-[#D1C4E9]/70 mt-1">Scripts to be added right after &lt;body&gt; tag</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-[#D1C4E9] mb-2">{{ __('Footer Scripts (Before closing </body>)') }}</label>
                            <textarea wire:model="footer_scripts" rows="6" 
                                class="w-full rounded-lg border border-[#F5B301]/30 bg-[#3B0A45] text-white px-3 py-2 focus:border-[#F5B301] focus:ring-2 focus:ring-[#F5B301]/20 font-mono text-sm"
                                placeholder="<script>&#10;  // Scripts before closing body tag&#10;</script>"></textarea>
                            <p class="text-xs text-[#D1C4E9]/70 mt-1">Scripts to be added before &lt;/body&gt; tag</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Social Media Tab -->
            @if($activeTab === 'social')
            <div class="space-y-4 rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
                <flux:heading size="lg" class="text-white mb-4">{{ __('Social Media Links') }}</flux:heading>
                
                <div class="grid gap-4 md:grid-cols-2">
                    <flux:input wire:model="facebook_url" :label="__('Facebook URL')" 
                        placeholder="https://facebook.com/royalmaids" />
                    
                    <flux:input wire:model="twitter_url" :label="__('Twitter URL')" 
                        placeholder="https://twitter.com/royalmaids" />
                    
                    <flux:input wire:model="instagram_url" :label="__('Instagram URL')" 
                        placeholder="https://instagram.com/royalmaids" />
                    
                    <flux:input wire:model="linkedin_url" :label="__('LinkedIn URL')" 
                        placeholder="https://linkedin.com/company/royalmaids" />
                    
                    <flux:input wire:model="youtube_url" :label="__('YouTube URL')" 
                        placeholder="https://youtube.com/@royalmaids" />
                </div>
            </div>
            @endif
        </div>

        <!-- Save Button -->
        <div class="mt-6 flex justify-end">
            <flux:button type="submit" variant="primary">
                <x-flux::icon.check class="w-5 h-5 mr-2" />
                {{ __('Save Settings') }}
            </flux:button>
        </div>
    </form>
</div>
