<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-[#3B0A45] dark:bg-[#3B0A45]">
        <flux:sidebar sticky stashable class="border-e border-[#F5B301]/20 bg-gradient-royal dark:bg-gradient-royal">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <!-- Royal Maids Brand Header -->
            <div class="me-5 flex items-center space-x-3 rtl:space-x-reverse mb-6">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-gold">
                    <flux:icon.home-modern class="size-6 text-[#3B0A45]" />
                </div>
                <div class="flex flex-col">
                    <span class="text-lg font-bold text-white">Royal Maids</span>
                    <span class="text-xs text-[#D1C4E9]">Management Hub</span>
                </div>
            </div>

            @php
                $user = auth()->user();
                $role = $user?->role ?? 'client';
                $is = fn(string $name) => request()->routeIs($name);
                $href = function (string $name, string $fallback = '#') {
                    return \Illuminate\Support\Facades\Route::has($name) ? route($name) : $fallback;
                };
            @endphp

            <!-- Main Navigation -->
            <flux:navlist variant="outline">
                <!-- Dashboard -->
                <flux:navlist.item 
                    icon="home" 
                    :href="route('dashboard')" 
                    :current="request()->routeIs('dashboard')" 
                    wire:navigate
                    class="mb-2"
                >
                    {{ __('Dashboard') }}
                </flux:navlist.item>

                    @if ($role === 'admin')
                    <!-- Admin Navigation -->
                    <flux:navlist.group :heading="__('Management')" class="mb-4">
                        <flux:navlist.item icon="users" :href="$href('maids.index')" :current="$is('maids.*')">
                            {{ __('Maids') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="academic-cap" :href="$href('trainers.index')" :current="$is('trainers.*')">
                            {{ __('Trainers') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="user-group" :href="$href('clients.index')" :current="$is('clients.*')">
                            {{ __('Clients') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="calendar-days" :href="$href('bookings.index')" :current="$is('bookings.*')">
                            {{ __('Bookings') }}
                        </flux:navlist.item>
                    </flux:navlist.group>

                    <flux:navlist.group :heading="__('Training & Development')" class="mb-4">
                        <flux:navlist.item icon="clipboard-document-list" :href="$href('programs.index')" :current="$is('programs.*')">
                            {{ __('Training Programs') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="clipboard-document-check" :href="$href('evaluations.index')" :current="$is('evaluations.*')">
                            {{ __('Evaluations') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="map-pin" :href="$href('deployments.index')" :current="$is('deployments.*')">
                            {{ __('Deployments') }}
                        </flux:navlist.item>
                    </flux:navlist.group>

                    <flux:navlist.group :heading="__('Analytics & Reports')" class="mb-4">
                        <flux:navlist.item icon="chart-bar" :href="$href('reports.index')" :current="$is('reports.index')">
                            {{ __('Reports') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="chart-pie" :href="$href('reports.kpi-dashboard')" :current="$is('reports.kpi-dashboard')">
                            {{ __('KPI Dashboard') }}
                        </flux:navlist.item>
                    </flux:navlist.group>


                    <flux:navlist.group :heading="__('Support & Tickets')" class="mb-4">
                        <flux:navlist.item icon="lifebuoy" :href="$href('tickets.index')" :current="$is('tickets.index')">
                            {{ __('All Tickets') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="inbox" :href="$href('tickets.inbox')" :current="$is('tickets.inbox')">
                            {{ __('My Inbox') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="chart-bar" :href="$href('tickets.analytics')" :current="$is('tickets.analytics')">
                            {{ __('Ticket Analytics') }}
                        </flux:navlist.item>
                    </flux:navlist.group>

                    <flux:navlist.group :heading="__('Business')" class="mb-4">
                        <flux:navlist.item icon="folder" :href="$href('packages.index')" :current="$is('packages.*')">
                            {{ __('Packages') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="cog-6-tooth" :href="route('profile.edit')" wire:navigate>
                            {{ __('Settings') }}
                        </flux:navlist.item>
                    </flux:navlist.group>

                    @elseif ($role === 'trainer')
                    <!-- Trainer Navigation -->
                    <flux:navlist.group :heading="__('Training Management')" class="mb-4">
                        <flux:navlist.item icon="users" :href="$href('trainees.index')" :current="$is('trainees.*')">
                            {{ __('My Trainees') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="clipboard-document-list" :href="$href('programs.index')" :current="$is('programs.*')">
                            {{ __('Training Programs') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="calendar-days" :href="$href('schedule.index')" :current="$is('schedule.*')">
                            {{ __('Schedule') }}
                        </flux:navlist.item>
                    </flux:navlist.group>

                    <flux:navlist.group :heading="__('Evaluations')" class="mb-4">
                        <flux:navlist.item icon="clipboard-document-check" :href="$href('evaluations.index')" :current="$is('evaluations.*')">
                            {{ __('Evaluations') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="map-pin" :href="$href('deployments.index')" :current="$is('deployments.*')">
                            {{ __('Deployments') }}
                        </flux:navlist.item>
                    </flux:navlist.group>

                    <flux:navlist.group :heading="__('Reports')" class="mb-4">
                        <flux:navlist.item icon="chart-bar" :href="$href('reports.index')" :current="$is('reports.index')">
                            {{ __('Reports') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="chart-pie" :href="$href('reports.kpi-dashboard')" :current="$is('reports.kpi-dashboard')">
                            {{ __('KPI Dashboard') }}
                        </flux:navlist.item>
                    </flux:navlist.group>

                    @else
                    <!-- Client Navigation -->
                    <flux:navlist.group :heading="__('Bookings')" class="mb-4">
                        <flux:navlist.item icon="plus-circle" :href="$href('bookings.create')" :current="$is('bookings.create')">
                            {{ __('New Booking') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="calendar-days" :href="$href('client.bookings.index')" :current="$is('client.bookings.*')">
                            {{ __('My Bookings') }}
                        </flux:navlist.item>
                    </flux:navlist.group>

                    <flux:navlist.group :heading="__('Browse & Search')" class="mb-4">
                        <flux:navlist.item icon="magnifying-glass" :href="$href('maids.browse')" :current="$is('maids.browse')">
                            {{ __('Browse Maids') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="heart" :href="$href('favorites.index')" :current="$is('favorites.*')">
                            {{ __('Favorites') }}
                        </flux:navlist.item>
                    </flux:navlist.group>

                    <flux:navlist.group :heading="__('Account')" class="mb-4">
                        <flux:navlist.item icon="credit-card" :href="$href('subscriptions.index')" :current="$is('subscriptions.*')">
                            {{ __('Subscription') }}
                        </flux:navlist.item>
                        <flux:navlist.item icon="question-mark-circle" :href="$href('support.index')" :current="$is('support.*')">
                            {{ __('Support') }}
                        </flux:navlist.item>
                    </flux:navlist.group>
                    @endif
            </flux:navlist>

            <flux:spacer />

            <!-- Royal Maids Footer Info -->
            <div class="mb-4 rounded-lg bg-[#512B58]/50 p-3 border border-[#F5B301]/20">
                <div class="flex items-center gap-2 mb-2">
                    <flux:icon.shield-check class="size-4 text-[#F5B301]" />
                    <span class="text-xs font-medium text-white">{{ __('Premium Service') }}</span>
                </div>
                <p class="text-xs text-[#D1C4E9] leading-relaxed">
                    {{ __('Professional maid services with comprehensive training and quality assurance.') }}
                </p>
            </div>

            <!-- Desktop User Menu -->
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon="user"
                    icon:trailing="chevrons-up-down"
                    data-test="sidebar-menu-button"
                />

                <flux:menu class="w-[240px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-3 px-1 py-2 text-start text-sm">
                                <span class="relative flex h-10 w-10 shrink-0 overflow-hidden rounded-lg">
                                    <span class="flex h-full w-full items-center justify-center rounded-lg bg-gradient-gold text-[#3B0A45] font-bold">
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold text-white">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs text-[#D1C4E9]">{{ auth()->user()->email }}</span>
                                    <span class="truncate text-xs text-[#F5B301] font-medium">{{ ucfirst($role) }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog-6-tooth" wire:navigate>
                            {{ __('Account Settings') }}
                        </flux:menu.item>
                        <flux:menu.item icon="bell" href="#notifications">
                            {{ __('Notifications') }}
                        </flux:menu.item>
                        <flux:menu.item icon="question-mark-circle" href="#help">
                            {{ __('Help & Support') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full text-red-400 hover:text-red-300" data-test="logout-button">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden bg-[#3B0A45] border-b border-[#F5B301]/20">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <!-- Mobile Brand -->
            <div class="flex items-center space-x-2">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-gold">
                    <flux:icon.home-modern class="size-5 text-[#3B0A45]" />
                </div>
                <span class="text-lg font-bold text-white">Royal Maids</span>
            </div>

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon="user"
                    icon-trailing="chevron-down"
                />

                <flux:menu class="w-[240px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-3 px-1 py-2 text-start text-sm">
                                <span class="relative flex h-10 w-10 shrink-0 overflow-hidden rounded-lg">
                                    <span class="flex h-full w-full items-center justify-center rounded-lg bg-gradient-gold text-[#3B0A45] font-bold">
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold text-white">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs text-gray-400">{{ auth()->user()->email }}</span>
                                    <span class="truncate text-xs text-[#F5B301] font-medium">{{ ucfirst($role) }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog-6-tooth" wire:navigate>
                            {{ __('Account Settings') }}
                        </flux:menu.item>
                        <flux:menu.item icon="bell" href="#notifications">
                            {{ __('Notifications') }}
                        </flux:menu.item>
                        <flux:menu.item icon="question-mark-circle" href="#help">
                            {{ __('Help & Support') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full text-red-400 hover:text-red-300" data-test="logout-button">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
        
        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </body>
</html>
