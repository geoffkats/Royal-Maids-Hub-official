<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">{{ __('Contract Reports') }}</h1>
            <p class="text-slate-600 dark:text-slate-400 mt-1">{{ __('View contract statistics and analytics.') }}</p>
        </div>
        <a href="{{ route('contracts.index') }}" class="px-6 py-3 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-semibold rounded-lg transition">
            {{ __('Back to Contracts') }}
        </a>
    </div>

    {{-- Period Filter --}}
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow border border-slate-200 dark:border-slate-700 p-4">
        <div class="flex gap-2 flex-wrap">
            <button wire:click="$set('period', 'all')" class="px-4 py-2 {{ $period === 'all' ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300' }} rounded-lg transition">
                {{ __('All Time') }}
            </button>
            <button wire:click="$set('period', 'month')" class="px-4 py-2 {{ $period === 'month' ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300' }} rounded-lg transition">
                {{ __('This Month') }}
            </button>
            <button wire:click="$set('period', 'quarter')" class="px-4 py-2 {{ $period === 'quarter' ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300' }} rounded-lg transition">
                {{ __('This Quarter') }}
            </button>
            <button wire:click="$set('period', 'year')" class="px-4 py-2 {{ $period === 'year' ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300' }} rounded-lg transition">
                {{ __('This Year') }}
            </button>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow border border-slate-200 dark:border-slate-700 p-6">
            <p class="text-sm text-slate-600 dark:text-slate-400">{{ __('Total Contracts') }}</p>
            <p class="text-3xl font-bold text-slate-900 dark:text-white mt-2">{{ $stats['total_contracts'] }}</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-lg shadow border border-slate-200 dark:border-slate-700 p-6">
            <p class="text-sm text-slate-600 dark:text-slate-400">{{ __('Active Contracts') }}</p>
            <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-2">{{ $stats['active_contracts'] }}</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-lg shadow border border-slate-200 dark:border-slate-700 p-6">
            <p class="text-sm text-slate-600 dark:text-slate-400">{{ __('Expiring Soon') }}</p>
            <p class="text-3xl font-bold text-amber-600 dark:text-amber-400 mt-2">{{ $stats['expiring_soon'] }}</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-lg shadow border border-slate-200 dark:border-slate-700 p-6">
            <p class="text-sm text-slate-600 dark:text-slate-400">{{ __('Avg Completion') }}</p>
            <p class="text-3xl font-bold text-blue-600 dark:text-blue-400 mt-2">{{ $stats['avg_completion'] }}%</p>
        </div>
    </div>

    {{-- Additional Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow border border-slate-200 dark:border-slate-700 p-6">
            <p class="text-sm text-slate-600 dark:text-slate-400">{{ __('Expired Contracts') }}</p>
            <p class="text-2xl font-bold text-red-600 dark:text-red-400 mt-2">{{ $stats['expired_contracts'] }}</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-lg shadow border border-slate-200 dark:border-slate-700 p-6">
            <p class="text-sm text-slate-600 dark:text-slate-400">{{ __('Terminated Contracts') }}</p>
            <p class="text-2xl font-bold text-slate-900 dark:text-white mt-2">{{ $stats['terminated_contracts'] }}</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-lg shadow border border-slate-200 dark:border-slate-700 p-6">
            <p class="text-sm text-slate-600 dark:text-slate-400">{{ __('Total Worked Days') }}</p>
            <p class="text-2xl font-bold text-slate-900 dark:text-white mt-2">{{ $stats['total_worked_days'] }}</p>
        </div>
    </div>

    {{-- Contracts by Type --}}
    @if($contracts_by_type->count() > 0)
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow border border-slate-200 dark:border-slate-700 p-6">
            <h3 class="text-xl font-semibold text-slate-900 dark:text-white mb-4">{{ __('Contracts by Type') }}</h3>
            <div class="space-y-3">
                @foreach($contracts_by_type as $type)
                    <div class="flex items-center justify-between">
                        <span class="text-slate-700 dark:text-slate-300">{{ ucfirst($type->contract_type) }}</span>
                        <div class="flex items-center gap-3 flex-1 ml-4">
                            <div class="flex-1 bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($type->count / $stats['total_contracts'] * 100) }}%"></div>
                            </div>
                            <span class="font-semibold text-slate-900 dark:text-white w-12 text-right">{{ $type->count }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Expiring Soon Alert --}}
    @if($expiring_contracts->count() > 0)
        <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-6">
            <h3 class="text-xl font-semibold text-amber-900 dark:text-amber-100 mb-4">
                ⚠️ {{ __('Contracts Expiring Soon') }} ({{ $expiring_contracts->count() }})
            </h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-amber-200 dark:border-amber-800">
                            <th class="text-left py-3 px-4 text-sm font-semibold text-amber-900 dark:text-amber-100">{{ __('Maid') }}</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-amber-900 dark:text-amber-100">{{ __('End Date') }}</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-amber-900 dark:text-amber-100">{{ __('Days Left') }}</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-amber-900 dark:text-amber-100">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expiring_contracts as $contract)
                            <tr class="border-b border-amber-100 dark:border-amber-900">
                                <td class="py-3 px-4">
                                    @php
                                        $prefix = auth()->user()->role === 'trainer' ? 'trainer.' : '';
                                    @endphp
                                    <a href="{{ route($prefix . 'maids.show', $contract->maid) }}" class="text-amber-600 dark:text-amber-400 hover:underline">
                                        {{ $contract->maid->full_name }}
                                    </a>
                                </td>
                                <td class="py-3 px-4 text-amber-900 dark:text-amber-100">{{ $contract->contract_end_date->format('M d, Y') }}</td>
                                <td class="py-3 px-4 font-semibold text-amber-900 dark:text-amber-100">{{ $contract->getDaysUntilExpiry() }}</td>
                                <td class="py-3 px-4">
                                    <a href="{{ route('contracts.show', $contract) }}" class="text-amber-600 dark:text-amber-400 hover:underline text-sm">
                                        {{ __('View Contract') }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- Recent Contracts --}}
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow border border-slate-200 dark:border-slate-700 p-6">
        <h3 class="text-xl font-semibold text-slate-900 dark:text-white mb-4">{{ __('Recent Contracts') }}</h3>
        <div class="space-y-3">
            @forelse($recent_contracts as $contract)
                <div class="flex items-center justify-between p-3 border border-slate-200 dark:border-slate-700 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                    <div>
                        <p class="font-semibold text-slate-900 dark:text-white">{{ $contract->maid->full_name }}</p>
                        <p class="text-sm text-slate-600 dark:text-slate-400">
                            {{ $contract->contract_start_date->format('M d, Y') }} 
                            @if($contract->contract_end_date)
                                → {{ $contract->contract_end_date->format('M d, Y') }}
                            @else
                                → {{ __('No end date') }}
                            @endif
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                            @if($contract->contract_status === 'active') bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300
                            @elseif($contract->contract_status === 'expired') bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300
                            @else bg-slate-100 text-slate-800 dark:bg-slate-700/20 dark:text-slate-300
                            @endif">
                            {{ ucfirst($contract->contract_status) }}
                        </span>
                    </div>
                </div>
            @empty
                <p class="text-slate-600 dark:text-slate-400 text-center py-6">{{ __('No recent contracts') }}</p>
            @endforelse
        </div>
    </div>
</div>
