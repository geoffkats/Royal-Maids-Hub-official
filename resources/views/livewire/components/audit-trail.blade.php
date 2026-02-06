<div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-700">
    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">{{ __('Audit Trail') }}</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @if($created_by_name)
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m0 0h6m0-6h-6m0 0h-6"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-slate-600 dark:text-slate-400">{{ __('Created by') }}</p>
                    <p class="font-semibold text-slate-900 dark:text-white">{{ $created_by_name }}</p>
                    @if($created_at)
                        <p class="text-xs text-slate-500 dark:text-slate-500">{{ $created_at }}</p>
                    @endif
                </div>
            </div>
        @endif

        @if($updated_by_name && $updated_by_name !== $created_by_name)
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-slate-600 dark:text-slate-400">{{ __('Last updated by') }}</p>
                    <p class="font-semibold text-slate-900 dark:text-white">{{ $updated_by_name }}</p>
                    @if($updated_at)
                        <p class="text-xs text-slate-500 dark:text-slate-500">{{ $updated_at }}</p>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
