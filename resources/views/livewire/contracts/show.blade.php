<div class="space-y-6">
    {{-- Soft Delete Notice --}}
    @if($contract->trashed())
        <div class="p-4 bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-800 rounded-lg">
            <p class="text-yellow-800 dark:text-yellow-200">{{ __('This contract has been deleted. Click "Restore Contract" to undelete it.') }}</p>
        </div>
    @endif

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">{{ __('Contract Details') }}</h1>
            <p class="text-slate-600 dark:text-slate-400 mt-1">{{ __('View and manage maid contract information.') }}</p>
        </div>
        <div class="flex gap-3">
            @if($contract->trashed())
                <button wire:click="restore" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    {{ __('Restore Contract') }}
                </button>
            @else
                <a href="{{ route('contracts.edit', $contract) }}" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                    {{ __('Edit Contract') }}
                </a>
                <button
                    wire:click="emailContract"
                    wire:loading.attr="disabled"
                    wire:target="emailContract"
                    class="px-6 py-3 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 disabled:opacity-60 font-semibold rounded-lg transition"
                >
                    <span wire:loading.remove wire:target="emailContract">{{ __('Email Contract') }}</span>
                    <span wire:loading wire:target="emailContract">{{ __('Sending...') }}</span>
                </button>
            @endif
            <a href="{{ route('contracts.index') }}" class="px-6 py-3 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 font-semibold rounded-lg transition">
                {{ __('Back to Contracts') }}
            </a>
        </div>
    </div>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
            <p class="text-green-800 dark:text-green-200">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Contract Details --}}
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow border border-slate-200 dark:border-slate-700 p-6">
        <h3 class="text-xl font-semibold text-slate-900 dark:text-white mb-6">{{ __('Contract Information') }}</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div>
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Maid') }}</p>
                <p class="font-medium text-slate-900 dark:text-white mt-1">
                    @php
                        $prefix = auth()->user()->role === 'trainer' ? 'trainer.' : '';
                    @endphp
                    <a href="{{ route($prefix . 'maids.show', $contract->maid) }}" class="text-blue-600 hover:text-blue-700 dark:text-blue-400">
                        {{ $contract->maid->full_name }}
                    </a>
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Client') }}</p>
                <p class="font-medium text-slate-900 dark:text-white mt-1">
                    @php
                        $client = $contract->getClient();
                        $clientName = $client?->company_name ?: $client?->contact_person;
                    @endphp
                    @if($client)
                        <a href="{{ route('clients.show', $client) }}" class="text-blue-600 hover:text-blue-700 dark:text-blue-400">
                            {{ $clientName }}
                        </a>
                    @else
                        <span class="text-slate-500 dark:text-slate-400">{{ __('No active client') }}</span>
                    @endif
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Maid Code') }}</p>
                <p class="font-medium text-slate-900 dark:text-white mt-1">{{ $contract->maid->maid_code }}</p>
            </div>

            <div>
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Contract Start Date') }}</p>
                <p class="font-medium text-slate-900 dark:text-white mt-1">{{ $contract->contract_start_date?->format('M d, Y') ?? __('N/A') }}</p>
            </div>

            <div>
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Contract End Date') }}</p>
                <p class="font-medium text-slate-900 dark:text-white mt-1">{{ $contract->contract_end_date?->format('M d, Y') ?? __('N/A') }}</p>
            </div>

            <div>
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Contract Type') }}</p>
                <p class="font-medium text-slate-900 dark:text-white mt-1">{{ $contract->contract_type ?? __('N/A') }}</p>
            </div>

            <div>
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Days Worked') }}</p>
                <p class="font-medium text-green-600 dark:text-green-400 mt-1">{{ $contract->worked_days ?? 0 }} {{ __('days') }}</p>
            </div>

            <div>
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Days Pending') }}</p>
                <p class="font-medium text-blue-600 dark:text-blue-400 mt-1">{{ $contract->pending_days ?? 0 }} {{ __('days') }}</p>
            </div>

            <div>
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Total Contract Days') }}</p>
                <p class="font-medium text-slate-900 dark:text-white mt-1">{{ ($contract->worked_days ?? 0) + ($contract->pending_days ?? 0) }} {{ __('days') }}</p>
            </div>
        </div>

        @if($contract->notes)
            <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-700">
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Notes') }}</p>
                <p class="text-slate-900 dark:text-white mt-2">{{ $contract->notes }}</p>
            </div>
        @endif

        @if(!empty($contract->contract_documents))
            <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-700">
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Contract Documents') }}</p>
                <ul class="mt-2 space-y-1">
                    @foreach ($contract->contract_documents as $document)
                        <li>
                            <a
                                href="{{ \Storage::url($document) }}"
                                target="_blank"
                                class="text-blue-600 hover:text-blue-700 dark:text-blue-400"
                            >
                                {{ basename($document) }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

    </div>

    {{-- Financial Information --}}
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow border border-slate-200 dark:border-slate-700 p-6">
        @can('viewSensitiveFinancials')
            <h3 class="text-xl font-semibold text-slate-900 dark:text-white mb-6">{{ __('Financial Information') }}</h3>

            @php
                $salaryInfo = $contract->getSalaryInfo();
            @endphp

            @if($salaryInfo && isset($salaryInfo['maid_salary']))
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                    <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                        <p class="text-xs text-blue-600 dark:text-blue-300 uppercase font-semibold">{{ __('Maid Salary') }}</p>
                        <p class="text-lg font-bold text-blue-900 dark:text-blue-100 mt-1">
                            {{ $salaryInfo['currency'] ?? 'AED' }} {{ number_format($salaryInfo['maid_salary'] ?? 0, 2) }}
                        </p>
                    </div>

                    <div class="p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg border border-purple-200 dark:border-purple-800">
                        <p class="text-xs text-purple-600 dark:text-purple-300 uppercase font-semibold">{{ __('Client Payment') }}</p>
                        <p class="text-lg font-bold text-purple-900 dark:text-purple-100 mt-1">
                            {{ $salaryInfo['currency'] ?? 'AED' }} {{ number_format($salaryInfo['client_payment'] ?? 0, 2) }}
                        </p>
                    </div>

                    <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
                        <p class="text-xs text-green-600 dark:text-green-300 uppercase font-semibold">{{ __('Service Paid') }}</p>
                        <p class="text-lg font-bold text-green-900 dark:text-green-100 mt-1">
                            {{ $salaryInfo['currency'] ?? 'AED' }} {{ number_format($salaryInfo['service_paid'] ?? 0, 2) }}
                        </p>
                    </div>

                    <div class="p-4 bg-amber-50 dark:bg-amber-900/20 rounded-lg border border-amber-200 dark:border-amber-800">
                        <p class="text-xs text-amber-600 dark:text-amber-300 uppercase font-semibold">{{ __('Payment Status') }}</p>
                        <p class="text-sm font-semibold text-amber-900 dark:text-amber-100 mt-1">
                            @php
                                $status = $salaryInfo['payment_status'] ?? 'pending';
                                $statusClass = match($status) {
                                    'paid' => 'text-green-700 dark:text-green-300 bg-green-100 dark:bg-green-900/30',
                                    'partial' => 'text-amber-700 dark:text-amber-300 bg-amber-100 dark:bg-amber-900/30',
                                    'pending' => 'text-red-700 dark:text-red-300 bg-red-100 dark:bg-red-900/30',
                                    default => 'text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-900/30'
                                };
                            @endphp
                            <span class="inline-block px-2 py-1 rounded {{ $statusClass }}">
                                {{ ucfirst($status) }}
                            </span>
                        </p>
                    </div>

                    <div class="p-4 bg-slate-50 dark:bg-slate-700 rounded-lg border border-slate-200 dark:border-slate-600">
                        <p class="text-xs text-slate-600 dark:text-slate-300 uppercase font-semibold">{{ __('Profit/Loss') }}</p>
                        @php
                            $profit = ($salaryInfo['client_payment'] ?? 0) - ($salaryInfo['maid_salary'] ?? 0);
                            $profitClass = $profit >= 0 ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300';
                        @endphp
                        <p class="text-lg font-bold {{ $profitClass }} mt-1">
                            {{ $salaryInfo['currency'] ?? 'AED' }} {{ number_format($profit, 2) }}
                        </p>
                    </div>
                </div>
            @else
                <div class="p-4 bg-slate-50 dark:bg-slate-700 rounded-lg border border-slate-200 dark:border-slate-600 text-center">
                    <p class="text-slate-600 dark:text-slate-400">{{ __('No financial information available. Ensure there is an active deployment for this contract.') }}</p>
                </div>
            @endif
        @else
            <div class="p-4 bg-slate-50 dark:bg-slate-700 rounded-lg border border-slate-200 dark:border-slate-600 text-center">
                <p class="text-slate-600 dark:text-slate-400">{{ __('You do not have permission to view financial information.') }}</p>
            </div>
        @endcan
    </div>

    {{-- Contract Footer --}}
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow border border-slate-200 dark:border-slate-700 p-6">
        <button wire:click="recalculate" class="px-4 py-2 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-lg transition">
            {{ __('Recalculate Days') }}
        </button>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
            {{ __('Click to manually recalculate worked and pending days based on current date.') }}
        </p>
    </div>

    {{-- Audit Trail --}}
    <livewire:components.audit-trail :model="$contract" />
</div>
