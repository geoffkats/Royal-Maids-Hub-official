<x-layouts.app :title="__('Trainer Dashboard')">
<div class="space-y-6">
    {{-- Trainer Dashboard Header --}}
    <div class="bg-gradient-to-r from-blue-600 to-cyan-600 rounded-lg shadow-lg p-6 text-white">
        <h1 class="text-3xl font-bold mb-2">Trainer Dashboard</h1>
        <p class="text-blue-100">Train & Develop Maids for Excellence</p>
    </div>

    {{-- Quick Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- My Trainees --}}
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6 border border-neutral-200 dark:border-neutral-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">My Trainees</p>
                    <p class="text-3xl font-bold text-neutral-900 dark:text-white mt-2">---</p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Active Training Sessions --}}
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6 border border-neutral-200 dark:border-neutral-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Active Sessions</p>
                    <p class="text-3xl font-bold text-neutral-900 dark:text-white mt-2">---</p>
                </div>
                <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
                    <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Completed Trainings --}}
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6 border border-neutral-200 dark:border-neutral-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Completed</p>
                    <p class="text-3xl font-bold text-neutral-900 dark:text-white mt-2">---</p>
                </div>
                <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-full">
                    <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Average Rating --}}
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6 border border-neutral-200 dark:border-neutral-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Avg. Rating</p>
                    <p class="text-3xl font-bold text-neutral-900 dark:text-white mt-2">---</p>
                </div>
                <div class="bg-yellow-100 dark:bg-yellow-900 p-3 rounded-full">
                    <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Training Programs --}}
        <div class="lg:col-span-2 bg-white dark:bg-neutral-800 rounded-lg shadow p-6 border border-neutral-200 dark:border-neutral-700">
            <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Training Programs</h2>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-4 bg-neutral-50 dark:bg-neutral-900 rounded-lg">
                    <div>
                        <h3 class="font-medium text-neutral-900 dark:text-white">Basic Housekeeping</h3>
                        <p class="text-sm text-neutral-500 dark:text-neutral-400">Cleaning, Laundry, Ironing</p>
                    </div>
                    <span class="text-neutral-500 dark:text-neutral-400 text-sm">Coming soon</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-neutral-50 dark:bg-neutral-900 rounded-lg">
                    <div>
                        <h3 class="font-medium text-neutral-900 dark:text-white">Culinary Skills</h3>
                        <p class="text-sm text-neutral-500 dark:text-neutral-400">Cooking & Meal Preparation</p>
                    </div>
                    <span class="text-neutral-500 dark:text-neutral-400 text-sm">Coming soon</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-neutral-50 dark:bg-neutral-900 rounded-lg">
                    <div>
                        <h3 class="font-medium text-neutral-900 dark:text-white">Child Care</h3>
                        <p class="text-sm text-neutral-500 dark:text-neutral-400">Nanny Services (0-5 years)</p>
                    </div>
                    <span class="text-neutral-500 dark:text-neutral-400 text-sm">Coming soon</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-neutral-50 dark:bg-neutral-900 rounded-lg">
                    <div>
                        <h3 class="font-medium text-neutral-900 dark:text-white">Elderly Care</h3>
                        <p class="text-sm text-neutral-500 dark:text-neutral-400">Bedside Nursing & Caretaking</p>
                    </div>
                    <span class="text-neutral-500 dark:text-neutral-400 text-sm">Coming soon</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-neutral-50 dark:bg-neutral-900 rounded-lg">
                    <div>
                        <h3 class="font-medium text-neutral-900 dark:text-white">Professional Development</h3>
                        <p class="text-sm text-neutral-500 dark:text-neutral-400">Customer Service & Communication</p>
                    </div>
                    <span class="text-neutral-500 dark:text-neutral-400 text-sm">Coming soon</span>
                </div>
            </div>
        </div>

        {{-- Upcoming Schedule --}}
        <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6 border border-neutral-200 dark:border-neutral-700">
            <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Upcoming Schedule</h2>
            <div class="space-y-4">
                <div class="text-center py-8">
                    <svg class="w-16 h-16 mx-auto text-neutral-300 dark:text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="mt-4 text-neutral-500 dark:text-neutral-400">No scheduled sessions</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Trainee Progress Overview --}}
    <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6 border border-neutral-200 dark:border-neutral-700">
        <h2 class="text-xl font-semibold text-neutral-900 dark:text-white mb-4">Trainee Progress Overview</h2>
        <div class="text-center py-12">
            <svg class="w-20 h-20 mx-auto text-neutral-300 dark:text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            <p class="mt-4 text-neutral-500 dark:text-neutral-400">Trainee progress tracking will appear here</p>
        </div>
    </div>
</div>
</x-layouts.app>
