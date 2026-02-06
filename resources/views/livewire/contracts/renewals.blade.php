<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">{{ __('Contract Renewals') }}</h1>
            <p class="text-slate-600 dark:text-slate-400 mt-1">{{ __('Manage contract renewals and track expiration dates') }}</p>
        </div>
        <a href="{{ route('contracts.index') }}" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
            {{ __('View All Contracts') }}
        </a>
    </div>

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-red-50 dark:bg-red-900/20 rounded-lg shadow p-6 border border-red-200 dark:border-red-800">
            <p class="text-xs text-red-600 dark:text-red-300 uppercase font-semibold">{{ __('Expiring (30 Days)') }}</p>
            <p class="text-3xl font-bold text-red-600 dark:text-red-300 mt-2">{{ $stats['expiring_30_days'] }}</p>
            <p class="text-sm text-red-700 dark:text-red-400 mt-2">{{ __('Contracts ending soon') }}</p>
        </div>

        <div class="bg-amber-50 dark:bg-amber-900/20 rounded-lg shadow p-6 border border-amber-200 dark:border-amber-800">
            <p class="text-xs text-amber-600 dark:text-amber-300 uppercase font-semibold">{{ __('Expiring (90 Days)') }}</p>
            <p class="text-3xl font-bold text-amber-600 dark:text-amber-300 mt-2">{{ $stats['expiring_90_days'] }}</p>
            <p class="text-sm text-amber-700 dark:text-amber-400 mt-2">{{ __('Within next quarter') }}</p>
        </div>

        <div class="bg-orange-50 dark:bg-orange-900/20 rounded-lg shadow p-6 border border-orange-200 dark:border-orange-800">
            <p class="text-xs text-orange-600 dark:text-orange-300 uppercase font-semibold">{{ __('Recently Expired') }}</p>
            <p class="text-3xl font-bold text-orange-600 dark:text-orange-300 mt-2">{{ $stats['recently_expired'] }}</p>
            <p class="text-sm text-orange-700 dark:text-orange-400 mt-2">{{ __('Last 30 days') }}</p>
        </div>

        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg shadow p-6 border border-green-200 dark:border-green-800">
            <p class="text-xs text-green-600 dark:text-green-300 uppercase font-semibold">{{ __('Renewal Rate') }}</p>
            <p class="text-3xl font-bold text-green-600 dark:text-green-300 mt-2">{{ $stats['renewal_rate'] }}%</p>
            <p class="text-sm text-green-700 dark:text-green-400 mt-2">{{ __('Completion rate') }}</p>
        </div>
    </div>

    {{-- Filter Tabs --}}
    <div class="flex gap-2 border-b border-slate-200 dark:border-slate-700">
        <button 
            wire:click="$set('filter', 'expiring')"
            @class([
                'px-4 py-3 font-semibold border-b-2 transition',
                'border-blue-600 text-blue-600 dark:text-blue-400' => $filter === 'expiring',
                'border-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' => $filter !== 'expiring',
            ])
        >
            <span class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ __('Expiring') }}
            </span>
        </button>

        <button 
            wire:click="$set('filter', 'expired')"
            @class([
                'px-4 py-3 font-semibold border-b-2 transition',
                'border-orange-600 text-orange-600 dark:text-orange-400' => $filter === 'expired',
                'border-transparent text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200' => $filter !== 'expired',
            ])
        >
            <span class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ __('Recently Expired') }}
            </span>
        </button>
    </div>

    {{-- Contracts Table --}}
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 dark:bg-slate-700 border-b border-slate-200 dark:border-slate-600">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900 dark:text-white">{{ __('Maid') }}</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900 dark:text-white">{{ __('Client') }}</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-slate-900 dark:text-white">{{ __('Contract Type') }}</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-slate-900 dark:text-white">{{ __('End Date') }}</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-slate-900 dark:text-white">{{ __('Days Left') }}</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-slate-900 dark:text-white">{{ __('Status') }}</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-slate-900 dark:text-white">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contracts as $contract)
                        <tr class="border-b border-slate-200 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-semibold text-slate-900 dark:text-white">{{ $contract->maid->full_name }}</p>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">{{ $contract->maid->maid_code }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $client = $contract->getClient();
                                    $clientName = $client?->company_name ?: $client?->contact_person;
                                @endphp
                                @if($client)
                                    <p class="font-semibold text-slate-900 dark:text-white">{{ $clientName }}</p>
                                @else
                                    <span class="text-slate-500 dark:text-slate-400">{{ __('No active client') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-200 rounded-full text-sm font-medium">
                                    {{ ucfirst($contract->contract_type ?? 'N/A') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <p class="font-semibold text-slate-900 dark:text-white">{{ $contract->contract_end_date?->format('M d, Y') }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $daysLeft = $contract->getDaysUntilExpiry();
                                    $daysColor = $daysLeft > 30 ? 'text-green-600' : ($daysLeft > 0 ? 'text-amber-600' : 'text-red-600');
                                @endphp
                                <p class="font-bold {{ $daysColor }} dark:text-inherit">{{ max(0, $daysLeft) }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $isExpired = $contract->isExpired();
                                    $statusBadge = $isExpired 
                                        ? 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300' 
                                        : 'bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-300';
                                @endphp
                                <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold {{ $statusBadge }}">
                                    {{ $isExpired ? __('Expired') : __('Expiring') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button 
                                    wire:click="renewContract({{ $contract->id }})"
                                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition text-sm">
                                    {{ __('Renew') }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-600 dark:text-slate-400">
                                @if($filter === 'expiring')
                                    {{ __('No contracts expiring in the next 90 days') }}
                                @else
                                    {{ __('No recently expired contracts') }}
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($contracts->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
                {{ $contracts->links() }}
            </div>
        @endif
    </div>

    {{-- Renewal Tips --}}
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-3">{{ __('Renewal Tips') }}</h3>
        <ul class="space-y-2 text-sm text-blue-800 dark:text-blue-200">
            <li class="flex items-start gap-2">
                <svg class="w-5 h-5 mt-0.5 flex-shrink-0 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span>{{ __('Contact maids at least 30 days before contract expiration') }}</span>
            </li>
            <li class="flex items-start gap-2">
                <svg class="w-5 h-5 mt-0.5 flex-shrink-0 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span>{{ __('Discuss renewal terms and salary adjustments with both maid and client') }}</span>
            </li>
            <li class="flex items-start gap-2">
                <svg class="w-5 h-5 mt-0.5 flex-shrink-0 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span>{{ __('Update contract terms, salary, and benefits if applicable') }}</span>
            </li>
            <li class="flex items-start gap-2">
                <svg class="w-5 h-5 mt-0.5 flex-shrink-0 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span>{{ __('Reset working days and pending days upon renewal') }}</span>
            </li>
        </ul>
    </div>
</div>
