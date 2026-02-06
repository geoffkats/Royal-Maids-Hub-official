<div>
    @can('viewSensitiveFinancials')
        @php
            $thisMonthRetainedFee = $thisMonthData->total_client_payment
                - $thisMonthData->total_maid_salary
                + $thisMonthData->total_service_paid;
            $thisYearRetainedFee = $thisYearData->total_client_payment
                - $thisYearData->total_maid_salary
                + $thisYearData->total_service_paid;
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
            <!-- This Month Maid Salary -->
            <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-[#D1C4E9]">This Month Salary</p>
                        <p class="mt-2 text-3xl font-bold text-white">
                            {{ number_format($thisMonthData->total_maid_salary, 0) }}
                        </p>
                        <p class="mt-1 text-xs text-[#D1C4E9]">{{ $thisMonthData->total_deployments }} deployments</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#F5B301]/20">
                        <svg class="h-6 w-6 text-[#F5B301]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- This Month Client Payment -->
            <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-[#D1C4E9]">This Month Received</p>
                        <p class="mt-2 text-3xl font-bold text-[#F5B301]">
                            {{ number_format($thisMonthData->total_client_payment, 0) }}
                        </p>
                        <p class="mt-1 text-xs text-[#D1C4E9]">From clients</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#B9A0DC]/25">
                        <svg class="h-6 w-6 text-[#B9A0DC]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- This Month Service Fee -->
            <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-[#D1C4E9]">This Month Service Fee</p>
                        <p class="mt-2 text-3xl font-bold text-[#FF9800]">
                            {{ number_format($thisMonthData->total_service_paid, 0) }}
                        </p>
                        <p class="mt-1 text-xs text-[#D1C4E9]">Collected fees</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#F5B301]/20">
                        <svg class="h-6 w-6 text-[#F5B301]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- This Month Retained Fee -->
            <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-[#D1C4E9]">This Month Retained Fee</p>
                        <p class="mt-2 text-3xl font-bold {{ $thisMonthRetainedFee >= 0 ? 'text-[#4CAF50]' : 'text-[#E53935]' }}">
                            {{ number_format($thisMonthRetainedFee, 0) }}
                        </p>
                        <p class="mt-1 text-xs text-[#D1C4E9]">Client salary minus maid salary plus service fee</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#4CAF50]/15">
                        <svg class="h-6 w-6 text-[#4CAF50]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h6l3 3 7-7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Outstanding Payments -->
            <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-[#D1C4E9]">Outstanding</p>
                        <p class="mt-2 text-3xl font-bold text-[#E53935]">
                            {{ number_format($outstandingDeployments->total_outstanding, 0) }}
                        </p>
                        <p class="mt-1 text-xs text-[#D1C4E9]">Pending payment</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#E53935]/20">
                        <svg class="h-6 w-6 text-[#E53935]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 4v2M12 3a9 9 0 110 18 9 9 0 010-18z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Year Summary -->
        <div class="mt-8 rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <h3 class="mb-4 text-lg font-semibold text-white">This Year Summary</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <p class="text-sm font-medium text-[#D1C4E9]">Total Maid Salary</p>
                    <p class="mt-2 text-2xl font-bold text-white">
                        {{ number_format($thisYearData->total_maid_salary, 0) }}
                    </p>
                </div>
                <div class="text-center border-l border-r border-[#F5B301]/20">
                    <p class="text-sm font-medium text-[#D1C4E9]">Total Revenue</p>
                    <p class="mt-2 text-2xl font-bold text-[#F5B301]">
                        {{ number_format($thisYearData->total_client_payment, 0) }}
                    </p>
                </div>
                <div class="text-center">
                    <p class="text-sm font-medium text-[#D1C4E9]">Retained Fee</p>
                    <p class="mt-2 text-2xl font-bold 
                        @if($thisYearRetainedFee >= 0) 
                            text-[#4CAF50]
                        @else 
                            text-[#E53935]
                        @endif">
                        {{ number_format($thisYearRetainedFee, 0) }}
                    </p>
                </div>
            </div>
        </div>
    @else
        <div class="rounded-lg border border-red-500/30 bg-red-500/10 p-4 text-sm text-red-700 dark:text-red-200">
            {{ __('You do not have permission to view financial summaries.') }}
        </div>
    @endcan
</div>
