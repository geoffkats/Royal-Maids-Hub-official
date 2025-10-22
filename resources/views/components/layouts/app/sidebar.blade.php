<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>

            @php
                $user = auth()->user();
                $role = $user?->role ?? 'client';
                $is = fn(string $name) => request()->routeIs($name);
                $href = function (string $name, string $fallback = '#') {
                    return \Illuminate\Support\Facades\Route::has($name) ? route($name) : $fallback;
                };
            @endphp

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Platform')" class="grid">
                    <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>

                    @if ($role === 'admin')
                        <flux:navlist.item icon="users" :href="$href('maids.index')">{{ __('Maids') }}</flux:navlist.item>
                        <flux:navlist.item icon="user" :href="$href('trainers.index')">{{ __('Trainers') }}</flux:navlist.item>
                        <flux:navlist.item icon="users" :href="$href('clients.index')">{{ __('Clients') }}</flux:navlist.item>
                        <flux:navlist.item icon="calendar" :href="$href('bookings.index')">{{ __('Bookings') }}</flux:navlist.item>
                        <flux:navlist.item icon="chart-bar" :href="$href('reports.index')">{{ __('Reports') }}</flux:navlist.item>
                        <flux:navlist.item icon="archive" :href="$href('packages.index')">{{ __('Packages') }}</flux:navlist.item>
                        <flux:navlist.item icon="cog" :href="route('profile.edit')" wire:navigate>{{ __('Settings') }}</flux:navlist.item>
                    @elseif ($role === 'trainer')
                        <flux:navlist.item icon="users" :href="$href('trainees.index')">{{ __('My Trainees') }}</flux:navlist.item>
                        <flux:navlist.item icon="clipboard-check" :href="$href('programs.index')">{{ __('Training Programs') }}</flux:navlist.item>
                        <flux:navlist.item icon="calendar" :href="$href('schedule.index')">{{ __('Schedule') }}</flux:navlist.item>
                        <flux:navlist.item icon="clipboard" :href="$href('evaluations.index')">{{ __('Evaluations') }}</flux:navlist.item>
                        <flux:navlist.item icon="chart-bar" :href="$href('reports.index')">{{ __('Reports') }}</flux:navlist.item>
                    @else
                        <flux:navlist.item icon="plus-square" :href="$href('bookings.create')">{{ __('New Booking') }}</flux:navlist.item>
                        <flux:navlist.item icon="calendar" :href="$href('client.bookings.index')">{{ __('My Bookings') }}</flux:navlist.item>
                        <flux:navlist.item icon="search" :href="$href('maids.browse')">{{ __('Browse Maids') }}</flux:navlist.item>
                        <flux:navlist.item icon="credit-card" :href="$href('subscriptions.index')">{{ __('Subscription') }}</flux:navlist.item>
                        <flux:navlist.item icon="star" :href="$href('favorites.index')">{{ __('Favorites') }}</flux:navlist.item>
                        <flux:navlist.item icon="life-buoy" :href="$href('support.index')">{{ __('Support') }}</flux:navlist.item>
                    @endif
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />

            <flux:navlist variant="outline">
                <flux:navlist.item icon="folder" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                {{ __('Repository') }}
                </flux:navlist.item>

                <flux:navlist.item icon="book-open" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                {{ __('Documentation') }}
                </flux:navlist.item>
            </flux:navlist>

            <!-- Desktop User Menu -->
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
                    data-test="sidebar-menu-button"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
