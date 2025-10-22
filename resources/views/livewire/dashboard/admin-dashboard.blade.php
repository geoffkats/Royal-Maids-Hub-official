<x-layouts.app :title="__('Admin Dashboard')">
<div class="space-y-6">
    {{-- Admin Dashboard Header --}}
    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-lg shadow-lg p-6 text-white">
        <h1 class="text-3xl font-bold mb-2">Admin Dashboard</h1>
        <p class="text-purple-100">Manage Royal Maids Hub Platform</p>
    </div>

    {{-- Quick Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Total Users --}}
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6 border border-neutral-200 dark:border-neutral-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Total Users</p>
                    <p class="text-3xl font-bold text-neutral-900 dark:text-white mt-2">---</p>
                </div>
                <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-full">
                    <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Active Maids --}}
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6 border border-neutral-200 dark:border-neutral-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Active Maids</p>
                    <p class="text-3xl font-bold text-neutral-900 dark:text-white mt-2">---</p>
                </div>
                <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
                    <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total Bookings --}}
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6 border border-neutral-200 dark:border-neutral-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Total Bookings</p>
                    <p class="text-3xl font-bold text-neutral-900 dark:text-white mt-2">---</p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Revenue --}}
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6 border border-neutral-200 dark:border-neutral-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Revenue</p>
                    <p class="text-3xl font-bold text-neutral-900 dark:text-white mt-2">---</p>
                </div>
                <div class="bg-yellow-100 dark:bg-yellow-900 p-3 rounded-full">
                    <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Platform Management --}}
        <div class="lg:col-span-2 bg-white dark:bg-neutral-800 rounded-lg shadow p-6 border border-neutral-200 dark:border-neutral-700">
            <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Platform Management</h2>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-4 bg-neutral-50 dark:bg-neutral-900 rounded-lg">
                    <span class="text-neutral-700 dark:text-neutral-300">User Management</span>
                    <span class="text-neutral-500 dark:text-neutral-400 text-sm">Coming soon</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-neutral-50 dark:bg-neutral-900 rounded-lg">
                    <span class="text-neutral-700 dark:text-neutral-300">Maid Management</span>
                    <span class="text-neutral-500 dark:text-neutral-400 text-sm">Coming soon</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-neutral-50 dark:bg-neutral-900 rounded-lg">
                    <span class="text-neutral-700 dark:text-neutral-300">Booking Management</span>
                    <span class="text-neutral-500 dark:text-neutral-400 text-sm">Coming soon</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-neutral-50 dark:bg-neutral-900 rounded-lg">
                    <span class="text-neutral-700 dark:text-neutral-300">Subscription Packages</span>
                    <span class="text-neutral-500 dark:text-neutral-400 text-sm">Coming soon</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-neutral-50 dark:bg-neutral-900 rounded-lg">
                    <span class="text-neutral-700 dark:text-neutral-300">Reports & Analytics</span>
                    <span class="text-neutral-500 dark:text-neutral-400 text-sm">Coming soon</span>
                </div>
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6 border border-neutral-200 dark:border-neutral-700">
            <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Recent Activity</h2>
            <div class="space-y-4">
                <div class="text-center py-8">
                    <svg class="w-16 h-16 mx-auto text-neutral-300 dark:text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="mt-4 text-neutral-500 dark:text-neutral-400">No recent activity</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Services Overview --}}
    <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6 border border-neutral-200 dark:border-neutral-700">
        <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">RMH Services Overview</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-4 bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-lg">
                <h3 class="font-semibold text-purple-900 dark:text-purple-300">Maid Services</h3>
                <p class="text-sm text-purple-700 dark:text-purple-400 mt-2">Laundry, Cleaning, Cooking, Pet Care</p>
            </div>
            <div class="p-4 bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-lg">
                <h3 class="font-semibold text-blue-900 dark:text-blue-300">Home Managers</h3>
                <p class="text-sm text-blue-700 dark:text-blue-400 mt-2">Full Property Management</p>
            </div>
            <div class="p-4 bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-lg">
                <h3 class="font-semibold text-green-900 dark:text-green-300">Care Services</h3>
                <p class="text-sm text-green-700 dark:text-green-400 mt-2">Bedside Nursing, Elderly Care, Nanny</p>
            </div>
            <div class="p-4 bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 rounded-lg">
                <h3 class="font-semibold text-orange-900 dark:text-orange-300">Temporary Staff</h3>
                <p class="text-sm text-orange-700 dark:text-orange-400 mt-2">Short-term & Flexible Solutions</p>
            </div>
        </div>
    </div>
</div>
</x-layouts.app>
