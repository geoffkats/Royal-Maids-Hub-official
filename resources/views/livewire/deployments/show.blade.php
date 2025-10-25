<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">{{ __('Deployment Details') }}</flux:heading>
            <flux:subheading class="mt-1">{{ __('View deployment information and status') }}</flux:subheading>
        </div>
        <div class="flex gap-3">
            <flux:button as="a" href="{{ route('deployments.index') }}" variant="outline" icon="arrow-left" wire:navigate>
                {{ __('Back to Deployments') }}
            </flux:button>
        </div>
    </div>

    <flux:separator variant="subtle" />

    {{-- Deployment Information --}}
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Maid Information --}}
            <div>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">{{ __('Maid Information') }}</h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Name') }}</label>
                        <p class="text-slate-900 dark:text-white">{{ $deployment->maid?->full_name ?? 'Not assigned' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Status') }}</label>
                        <p class="text-slate-900 dark:text-white">{{ ucfirst($deployment->maid?->status ?? 'Unknown') }}</p>
                    </div>
                </div>
            </div>

            {{-- Client Information --}}
            <div>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">{{ __('Client Information') }}</h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Contact Person') }}</label>
                        <p class="text-slate-900 dark:text-white">{{ $deployment->client?->contact_person ?? 'Not assigned' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Phone') }}</label>
                        <p class="text-slate-900 dark:text-white">{{ $deployment->client?->phone ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Deployment Details --}}
        <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-700">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">{{ __('Deployment Details') }}</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Deployment Date') }}</label>
                    <p class="text-slate-900 dark:text-white">{{ $deployment->deployment_date?->format('M d, Y') ?? 'Not set' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Status') }}</label>
                    <p class="text-slate-900 dark:text-white">{{ ucfirst($deployment->status ?? 'Unknown') }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Created') }}</label>
                    <p class="text-slate-900 dark:text-white">{{ $deployment->created_at?->format('M d, Y') ?? 'Unknown' }}</p>
                </div>
            </div>
        </div>

        @if($deployment->notes)
            <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-700">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">{{ __('Notes') }}</h3>
                <p class="text-slate-700 dark:text-slate-300">{{ $deployment->notes }}</p>
            </div>
        @endif
    </div>
</div>
