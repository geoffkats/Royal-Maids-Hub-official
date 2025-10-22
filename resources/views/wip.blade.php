<x-layouts.app :title="__('Work In Progress')">
    <div class="p-6">
        <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 p-6">
            <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">This page is under construction</h1>
            <p class="mt-2 text-neutral-600 dark:text-neutral-400">
                The feature for <span class="font-semibold">{{ request()->route()->getName() }}</span> is being built.
            </p>
            <p class="mt-4 text-neutral-600 dark:text-neutral-400">
                You can use the sidebar to navigate to other available sections.
            </p>
        </div>
    </div>
</x-layouts.app>
