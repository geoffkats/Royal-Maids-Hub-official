<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">{{ __('Admin Dashboard') }}</flux:heading>
            <flux:subheading class="mt-1">{{ __('Comprehensive overview of your system') }}</flux:subheading>
        </div>
        <div class="flex gap-3">
            <flux:button as="a" href="{{ route('reports') }}" variant="primary" icon="chart-bar" wire:navigate>
                {{ __('View Detailed Reports') }}
            </flux:button>
        </div>
    </div>

    <flux:separator variant="subtle" />

    {{-- KEY PERFORMANCE INDICATORS --}}
    <div>
        <h2 class="mb-4 text-lg font-semibold text-white">{{ __('Key Performance Indicators') }}</h2>
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            
        {{-- Total Users --}}
            <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                        <p class="text-sm font-medium text-[#D1C4E9]">{{ __('Total Users') }}</p>
                        <p class="mt-2 text-3xl font-bold text-white">{{ number_format($totalUsers) }}</p>
                        <p class="mt-1 text-xs text-[#D1C4E9]">{{ number_format($verifiedUsers) }} {{ __('verified') }}</p>
                </div>
                    <div class="rounded-full bg-[#F5B301] p-3">
                        <flux:icon.users class="size-8 text-[#3B0A45]" />
                </div>
            </div>
        </div>

        {{-- Total Maids --}}
            <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                        <p class="text-sm font-medium text-[#D1C4E9]">{{ __('Total Maids') }}</p>
                        <p class="mt-2 text-3xl font-bold text-white">{{ number_format($totalMaids) }}</p>
                        <p class="mt-1 text-xs text-[#D1C4E9]">{{ number_format($availableMaids) }} {{ __('available') }}</p>
                </div>
                    <div class="rounded-full bg-[#4CAF50] p-3">
                        <flux:icon.user-group class="size-8 text-white" />
                </div>
            </div>
        </div>

            {{-- Total Bookings --}}
            <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                        <p class="text-sm font-medium text-[#D1C4E9]">{{ __('Total Bookings') }}</p>
                        <p class="mt-2 text-3xl font-bold text-white">{{ number_format($totalBookings) }}</p>
                        <p class="mt-1 text-xs text-[#D1C4E9]">{{ number_format($activeBookings) }} {{ __('active') }}</p>
                </div>
                    <div class="rounded-full bg-[#B9A0DC] p-3">
                        <flux:icon.calendar class="size-8 text-[#3B0A45]" />
                </div>
            </div>
        </div>

            {{-- Total Evaluations --}}
            <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                        <p class="text-sm font-medium text-[#D1C4E9]">{{ __('Evaluations') }}</p>
                        <p class="mt-2 text-3xl font-bold text-white">{{ number_format($totalEvaluations) }}</p>
                        <p class="mt-1 text-xs text-[#D1C4E9]">{{ __('Avg:') }} {{ number_format($averageRating ?? 0, 1) }}/5.0</p>
                    </div>
                    <div class="rounded-full bg-[#FFC107] p-3">
                        <flux:icon.star class="size-8 text-[#3B0A45]" />
                </div>
                </div>
            </div>

            {{-- Support Tickets --}}
            <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                        <p class="text-sm font-medium text-[#D1C4E9]">{{ __('Support Tickets') }}</p>
                        <p class="mt-2 text-3xl font-bold text-white">{{ number_format($totalTickets) }}</p>
                        <p class="mt-1 text-xs text-[#D1C4E9]">{{ number_format($openTickets) }} {{ __('open') }}</p>
                </div>
                    <div class="rounded-full bg-[#FF9800] p-3">
                        <flux:icon.ticket class="size-8 text-white" />
                </div>
            </div>
        </div>

        </div>
    </div>

    {{-- ADVANCED KPIs & BUSINESS METRICS --}}
    <div>
        <h2 class="mb-4 text-lg font-semibold text-white">{{ __('Advanced Business Metrics') }}</h2>
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-5">
            
            {{-- Deployment Success Rate --}}
            <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-[#D1C4E9]">{{ __('Deployment Rate') }}</p>
                        <p class="mt-2 text-3xl font-bold text-white">{{ number_format($deploymentSuccessRate, 1) }}%</p>
                        <p class="mt-1 text-xs text-[#D1C4E9]">{{ number_format($deployedMaids) }} {{ __('deployed') }}</p>
                    </div>
                    <div class="rounded-full bg-[#4CAF50] p-3">
                        <flux:icon.check-circle class="size-6 text-white" />
                    </div>
                </div>
            </div>

            {{-- Training Success Rate --}}
            <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-[#D1C4E9]">{{ __('Training Success') }}</p>
                        <p class="mt-2 text-3xl font-bold text-white">{{ number_format($trainingSuccessRate, 1) }}%</p>
                        <p class="mt-1 text-xs text-[#D1C4E9]">{{ number_format($approvedEvaluations) }} {{ __('approved') }}</p>
                    </div>
                    <div class="rounded-full bg-[#64B5F6] p-3">
                        <flux:icon.academic-cap class="size-6 text-white" />
                    </div>
                </div>
            </div>

            {{-- Client Retention Rate --}}
            <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-[#D1C4E9]">{{ __('Client Retention') }}</p>
                        <p class="mt-2 text-3xl font-bold text-white">{{ number_format($clientRetentionRate, 1) }}%</p>
                        <p class="mt-1 text-xs text-[#D1C4E9]">{{ __('Repeat bookings') }}</p>
                    </div>
                    <div class="rounded-full bg-[#F5B301] p-3">
                        <flux:icon.heart class="size-6 text-[#3B0A45]" />
                    </div>
                </div>
            </div>

            {{-- Booking Conversion Rate --}}
            <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-[#D1C4E9]">{{ __('Conversion Rate') }}</p>
                        <p class="mt-2 text-3xl font-bold text-white">{{ number_format($bookingConversionRate, 1) }}%</p>
                        <p class="mt-1 text-xs text-[#D1C4E9]">{{ __('Pending → Active') }}</p>
                    </div>
                    <div class="rounded-full bg-[#4CAF50] p-3">
                        <flux:icon.arrow-trending-up class="size-6 text-white" />
                    </div>
                </div>
            </div>

            {{-- Maid Turnover Rate --}}
            <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-[#D1C4E9]">{{ __('Turnover Rate') }}</p>
                        <p class="mt-2 text-3xl font-bold text-white">{{ number_format($maidTurnoverRate, 1) }}%</p>
                        <p class="mt-1 text-xs text-[#D1C4E9]">{{ __('Absconded/Terminated') }}</p>
                    </div>
                    <div class="rounded-full bg-[#E53935] p-3">
                        <flux:icon.exclamation-triangle class="size-6 text-white" />
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    {{-- REVENUE & PACKAGE INSIGHTS --}}
    <div>
        <h2 class="mb-4 text-lg font-semibold text-white">{{ __('Revenue & Package Performance') }}</h2>
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            
            {{-- Total Revenue --}}
            <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
                <div>
                    <p class="text-sm font-medium text-[#D1C4E9]">{{ __('Total Revenue') }}</p>
                    <p class="mt-2 text-2xl font-bold text-white">{{ number_format($totalRevenue, 0) }} UGX</p>
                    <div class="mt-3 space-y-1">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-[#D1C4E9]">{{ __('Avg per booking:') }}</span>
                            <span class="font-semibold text-[#F5B301]">{{ number_format($averageBookingValue, 0) }} UGX</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-[#D1C4E9]">{{ __('Avg family size:') }}</span>
                            <span class="font-semibold text-[#F5B301]">{{ number_format($averageFamilySize, 1) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Silver Package --}}
            <div class="rounded-lg border-2 border-[#A8A9AD] bg-[#512B58] p-6 shadow-lg">
                <div class="flex items-center gap-2 mb-2">
                    <flux:icon.check-circle class="size-5 text-[#A8A9AD]" />
                    <p class="text-sm font-bold text-white">{{ __('Silver Standard') }}</p>
                </div>
                <p class="text-2xl font-bold text-white">{{ number_format($silverRevenue, 0) }} UGX</p>
                <p class="mt-1 text-xs text-[#D1C4E9]">{{ number_format($silverBookingCount) }} {{ __('bookings') }}</p>
            </div>

            {{-- Gold Package --}}
            <div class="rounded-lg border-2 border-[#F5B301] bg-[#512B58] p-6 shadow-lg">
                <div class="flex items-center gap-2 mb-2">
                    <flux:icon.star class="size-5 text-[#F5B301]" />
                    <p class="text-sm font-bold text-white">{{ __('Gold Premium') }}</p>
                </div>
                <p class="text-2xl font-bold text-white">{{ number_format($goldRevenue, 0) }} UGX</p>
                <p class="mt-1 text-xs text-[#D1C4E9]">{{ number_format($goldBookingCount) }} {{ __('bookings') }}</p>
            </div>

            {{-- Platinum Package --}}
            <div class="rounded-lg border-2 border-[#B9A0DC] bg-[#512B58] p-6 shadow-lg">
                <div class="flex items-center gap-2 mb-2">
                    <flux:icon.sparkles class="size-5 text-[#B9A0DC]" />
                    <p class="text-sm font-bold text-white">{{ __('Platinum Elite') }}</p>
                </div>
                <p class="text-2xl font-bold text-white">{{ number_format($platinumRevenue, 0) }} UGX</p>
                <p class="mt-1 text-xs text-[#D1C4E9]">{{ number_format($platinumBookingCount) }} {{ __('bookings') }}</p>
            </div>
            
        </div>
    </div>

    {{-- DEPLOYMENT INSIGHTS --}}
    <div>
        <h2 class="mb-4 text-lg font-semibold text-white">{{ __('Deployment Insights') }}</h2>
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            
            {{-- Active Deployments --}}
            <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-[#D1C4E9]">{{ __('Active Deployments') }}</p>
                        <p class="mt-2 text-3xl font-bold text-white">{{ number_format($activeDeployments) }}</p>
                    </div>
                    <div class="rounded-full bg-[#4CAF50] p-3">
                        <flux:icon.map-pin class="size-6 text-white" />
                    </div>
                </div>
            </div>

            {{-- Completed Deployments --}}
            <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-[#D1C4E9]">{{ __('Completed') }}</p>
                        <p class="mt-2 text-3xl font-bold text-white">{{ number_format($completedDeployments) }}</p>
                    </div>
                    <div class="rounded-full bg-[#64B5F6] p-3">
                        <flux:icon.check-badge class="size-6 text-white" />
                    </div>
                </div>
            </div>

            {{-- Terminated Deployments --}}
            <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-[#D1C4E9]">{{ __('Terminated') }}</p>
                        <p class="mt-2 text-3xl font-bold text-white">{{ number_format($terminatedDeployments) }}</p>
                    </div>
                    <div class="rounded-full bg-[#E53935] p-3">
                        <flux:icon.x-circle class="size-6 text-white" />
                    </div>
                </div>
            </div>

            {{-- Average Deployment Duration --}}
            <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-[#D1C4E9]">{{ __('Avg Duration') }}</p>
                        <p class="mt-2 text-3xl font-bold text-white">{{ number_format($avgDeploymentDuration) }}</p>
                        <p class="mt-1 text-xs text-[#D1C4E9]">{{ __('days') }}</p>
                    </div>
                    <div class="rounded-full bg-[#B9A0DC] p-3">
                        <flux:icon.clock class="size-6 text-white" />
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    {{-- CHARTS SECTION --}}
    <div class="grid gap-6 lg:grid-cols-2">
        
        {{-- Maid Status Breakdown Chart --}}
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <h3 class="mb-4 text-base font-semibold text-white">{{ __('Maid Status Distribution') }}</h3>
            <div style="position: relative; height: 300px;">
                <canvas id="maidStatusChart"></canvas>
                </div>
                </div>

        {{-- Booking Status Breakdown Chart --}}
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <h3 class="mb-4 text-base font-semibold text-white">{{ __('Booking Status Distribution') }}</h3>
            <div style="position: relative; height: 300px;">
                <canvas id="bookingStatusChart"></canvas>
            </div>
        </div>

        {{-- Evaluation Trends --}}
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <h3 class="mb-4 text-base font-semibold text-white">{{ __('Evaluation Trends (6 Months)') }}</h3>
            <div style="position: relative; height: 300px;">
                <canvas id="evaluationTrendsChart"></canvas>
                </div>
                </div>

        {{-- Maid Role Distribution Chart --}}
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <h3 class="mb-4 text-base font-semibold text-white">{{ __('Maid Role Distribution') }}</h3>
            <div style="position: relative; height: 300px;">
                <canvas id="roleDistributionChart"></canvas>
            </div>
        </div>

    </div>

    {{-- DETAILED STATISTICS --}}
    <div class="grid gap-6 lg:grid-cols-3">
        
        {{-- Client Statistics --}}
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <h3 class="mb-4 flex items-center gap-2 text-base font-semibold text-white">
                <flux:icon.briefcase class="size-5 text-[#F5B301]" />
                {{ __('Client Statistics') }}
            </h3>
            <div class="space-y-3">
            <div class="flex items-center justify-between">
                    <span class="text-sm text-[#D1C4E9]">{{ __('Total Clients') }}</span>
                    <span class="font-semibold text-white">{{ number_format($totalClients) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-[#D1C4E9]">{{ __('Active') }}</span>
                    <flux:badge color="green" size="sm">{{ number_format($activeClients) }}</flux:badge>
                </div>
            <div class="flex items-center justify-between">
                    <span class="text-sm text-[#D1C4E9]">{{ __('Pending') }}</span>
                    <flux:badge color="yellow" size="sm">{{ number_format($pendingClients) }}</flux:badge>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-[#D1C4E9]">{{ __('Expired') }}</span>
                    <flux:badge color="red" size="sm">{{ number_format($expiredClients) }}</flux:badge>
                </div>
            </div>
        </div>

        {{-- Training Statistics --}}
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <h3 class="mb-4 flex items-center gap-2 text-base font-semibold text-white">
                <flux:icon.academic-cap class="size-5 text-[#F5B301]" />
                {{ __('Training Statistics') }}
            </h3>
            <div class="space-y-3">
            <div class="flex items-center justify-between">
                    <span class="text-sm text-[#D1C4E9]">{{ __('Total Programs') }}</span>
                    <span class="font-semibold text-white">{{ number_format($totalPrograms) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-[#D1C4E9]">{{ __('Active Programs') }}</span>
                    <flux:badge color="blue" size="sm">{{ number_format($activePrograms) }}</flux:badge>
                </div>
            <div class="flex items-center justify-between">
                    <span class="text-sm text-[#D1C4E9]">{{ __('Completed') }}</span>
                    <flux:badge color="green" size="sm">{{ number_format($completedPrograms) }}</flux:badge>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-[#D1C4E9]">{{ __('Trainers') }}</span>
                    <span class="font-semibold text-white">{{ number_format($totalTrainers) }}</span>
                </div>
            </div>
        </div>

        {{-- Evaluation Statistics --}}
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <h3 class="mb-4 flex items-center gap-2 text-base font-semibold text-white">
                <flux:icon.clipboard-document-check class="size-5 text-[#F5B301]" />
                {{ __('Evaluation Statistics') }}
            </h3>
            <div class="space-y-3">
            <div class="flex items-center justify-between">
                    <span class="text-sm text-[#D1C4E9]">{{ __('Total Evaluations') }}</span>
                    <span class="font-semibold text-white">{{ number_format($totalEvaluations) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-[#D1C4E9]">{{ __('Approved') }}</span>
                    <flux:badge color="green" size="sm">{{ number_format($approvedEvaluations) }}</flux:badge>
                </div>
            <div class="flex items-center justify-between">
                    <span class="text-sm text-[#D1C4E9]">{{ __('Pending') }}</span>
                    <flux:badge color="yellow" size="sm">{{ number_format($pendingEvaluations) }}</flux:badge>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-[#D1C4E9]">{{ __('Average Rating') }}</span>
                    <flux:badge color="blue" size="sm">{{ number_format($averageRating ?? 0, 1) }}/5.0</flux:badge>
                </div>
            </div>
        </div>

    </div>

    {{-- TOP PERFORMERS & RECENT ACTIVITY --}}
    <div class="grid gap-6 lg:grid-cols-2">
        
        {{-- Top Rated Maids --}}
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <h3 class="mb-4 flex items-center gap-2 text-base font-semibold text-white">
                <flux:icon.trophy class="size-5 text-[#F5B301]" />
                {{ __('Top Rated Maids') }}
            </h3>
            <div class="space-y-3">
                @forelse ($topRatedMaids as $item)
                    <div class="flex items-center justify-between rounded-lg bg-[#3B0A45]/50 p-3 border border-[#F5B301]/20">
                        <div>
                            <p class="font-medium text-white">
                                {{ $item->maid?->first_name }} {{ $item->maid?->last_name }}
                            </p>
                            <p class="text-xs text-[#D1C4E9]">{{ $item->eval_count }} {{ __('evaluations') }}</p>
                        </div>
                        <flux:badge :color="$item->avg_rating >= 4.5 ? 'green' : ($item->avg_rating >= 4.0 ? 'blue' : 'yellow')" size="sm">
                            {{ number_format($item->avg_rating, 1) }}/5.0
                        </flux:badge>
                    </div>
                @empty
                    <p class="py-4 text-center text-sm text-[#D1C4E9]">{{ __('No evaluation data available yet.') }}</p>
                @endforelse
            </div>
        </div>

        {{-- Recent Evaluations --}}
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <h3 class="mb-4 flex items-center gap-2 text-base font-semibold text-white">
                <flux:icon.clock class="size-5 text-[#64B5F6]" />
                {{ __('Recent Evaluations') }}
            </h3>
            <div class="space-y-3">
                @forelse ($recentEvaluations as $evaluation)
                    <div class="flex items-center justify-between rounded-lg bg-[#3B0A45]/50 p-3 border border-[#F5B301]/20">
                        <div>
                            <p class="font-medium text-white">
                                {{ $evaluation->maid?->first_name }} {{ $evaluation->maid?->last_name }}
                            </p>
                            <p class="text-xs text-[#D1C4E9]">
                                {{ __('by') }} {{ $evaluation->trainer?->user?->name }} • {{ $evaluation->evaluation_date?->diffForHumans() }}
                            </p>
                        </div>
                        <flux:badge :color="$evaluation->getScoreBadgeColor($evaluation->overall_rating ?? 0)" size="sm">
                            {{ number_format($evaluation->overall_rating ?? 0, 1) }}
                        </flux:badge>
                    </div>
                @empty
                    <p class="py-4 text-center text-sm text-[#D1C4E9]">{{ __('No recent evaluations.') }}</p>
                @endforelse
            </div>
        </div>

    </div>

    {{-- RECENT BOOKINGS --}}
    <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
        <h3 class="mb-4 flex items-center gap-2 text-base font-semibold text-white">
            <flux:icon.document-text class="size-5 text-[#B9A0DC]" />
            {{ __('Recent Bookings') }}
        </h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#F5B301]/20">
                <thead class="bg-[#3B0A45]/50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-[#F5B301]">{{ __('Client') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-[#F5B301]">{{ __('Maid') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-[#F5B301]">{{ __('Start Date') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-[#F5B301]">{{ __('Package') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-[#F5B301]">{{ __('Status') }}</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-[#F5B301]">{{ __('Price') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#F5B301]/10">
                    @forelse ($recentBookings as $booking)
                        <tr class="hover:bg-[#3B0A45]/30">
                            <td class="whitespace-nowrap px-4 py-3 text-sm">
                                @if($booking->client)
                                    <a href="{{ route('clients.show', $booking->client->id) }}" 
                                       class="font-medium text-[#64B5F6] hover:text-[#F5B301]"
                                       wire:navigate>
                                        {{ $booking->client->user?->name ?? $booking->client->contact_person ?? $booking->full_name ?? 'Unknown Client' }}
                                    </a>
                                @else
                                    <span class="text-[#D1C4E9]">{{ $booking->full_name ?? 'Unknown Client' }}</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-sm">
                                @if($booking->maid)
                                    <a href="{{ route('maids.show', $booking->maid->id) }}" 
                                       class="font-medium text-[#64B5F6] hover:text-[#F5B301]"
                                       wire:navigate>
                                        {{ $booking->maid->first_name }} {{ $booking->maid->last_name }}
                                    </a>
                                @else
                                    <span class="text-[#D1C4E9]">Not assigned</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-sm text-white">
                                {{ $booking->start_date?->format('M d, Y') ?? 'Not set' }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-sm">
                                @if($booking->package)
                                    <flux:badge :color="match($booking->package->name) {
                                        'Silver' => 'zinc',
                                        'Gold' => 'yellow',
                                        'Platinum' => 'purple',
                                        default => 'gray'
                                    }" size="sm">
                                        {{ $booking->package->name }}
                                    </flux:badge>
                                @else
                                    <span class="text-[#D1C4E9] text-xs">{{ __('No package') }}</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-sm">
                                <flux:badge :color="match($booking->status) {
                                    'active' => 'blue',
                                    'completed' => 'green',
                                    'cancelled' => 'red',
                                    default => 'yellow'
                                }" size="sm">
                                    {{ ucfirst($booking->status) }}
                                </flux:badge>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-sm font-medium text-white text-right">
                                {{ number_format($booking->calculated_price ?? 0, 0) }} <span class="text-xs text-[#D1C4E9]">UGX</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-sm text-[#D1C4E9]">{{ __('No recent bookings.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Chart.js Integration --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('livewire:navigated', function() {
    // Destroy any existing charts first
    Chart.helpers.each(Chart.instances, function(instance) {
        instance.destroy();
    });

    // Royal Maids Brand Colors
    const brandColors = {
        primary: '#3B0A45',        // Royal Purple
        secondary: '#512B58',      // Deep Violet
        accent: '#F5B301',         // Gold
        success: '#4CAF50',        // Emerald Green
        warning: '#FFC107',        // Amber
        error: '#E53935',          // Soft Red
        info: '#64B5F6',           // Sky Blue
        silver: '#A8A9AD',          // Silver
        goldPackage: '#FFD700',    // Gold Package
        platinum: '#B9A0DC',       // Platinum
        textPrimary: '#FFFFFF',    // White
        textSecondary: '#D1C4E9'   // Light Gray
    };

    // Common chart options with brand theming
    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    color: brandColors.textPrimary,
                    padding: 10,
                    font: {
                        size: 11,
                        family: 'Instrument Sans, ui-sans-serif, system-ui, sans-serif'
                    }
                }
            },
            tooltip: {
                backgroundColor: brandColors.secondary,
                titleColor: brandColors.textPrimary,
                bodyColor: brandColors.textSecondary,
                borderColor: brandColors.accent,
                borderWidth: 1,
                cornerRadius: 8
            }
        },
        scales: {
            x: {
                ticks: {
                    color: brandColors.textSecondary
                },
                grid: {
                    color: brandColors.accent + '20'
                }
            },
            y: {
                ticks: {
                    color: brandColors.textSecondary
                },
                grid: {
                    color: brandColors.accent + '20'
                },
                beginAtZero: true
            }
        }
    };

    // Maid Status Chart
    const maidStatusCtx = document.getElementById('maidStatusChart');
    if (maidStatusCtx) {
        new Chart(maidStatusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Available', 'In Training', 'Booked', 'Deployed', 'Absconded', 'Terminated', 'On Leave'],
                datasets: [{
                    data: @json(array_values($maidStatusBreakdown)),
                    backgroundColor: [
                        brandColors.success,      // Available - Green
                        brandColors.info,        // In Training - Blue
                        brandColors.platinum,    // Booked - Purple
                        brandColors.accent,      // Deployed - Gold
                        brandColors.error,       // Absconded - Red
                        brandColors.silver,      // Terminated - Silver
                        brandColors.warning      // On Leave - Amber
                    ],
                    borderWidth: 2,
                    borderColor: brandColors.textPrimary,
                    hoverOffset: 4
                }]
            },
            options: chartOptions
        });
    }

    // Booking Status Chart
    const bookingStatusCtx = document.getElementById('bookingStatusChart');
    if (bookingStatusCtx) {
        new Chart(bookingStatusCtx, {
            type: 'pie',
            data: {
                labels: ['Pending', 'Active', 'Completed', 'Cancelled'],
                datasets: [{
                    data: @json(array_values($bookingStatusBreakdown)),
                    backgroundColor: [
                        brandColors.warning,     // Pending - Amber
                        brandColors.info,       // Active - Blue
                        brandColors.success,    // Completed - Green
                        brandColors.error       // Cancelled - Red
                    ],
                    borderWidth: 2,
                    borderColor: brandColors.textPrimary,
                    hoverOffset: 4
                }]
            },
            options: chartOptions
        });
    }

    // Evaluation Trends Chart
    const evaluationTrendsCtx = document.getElementById('evaluationTrendsChart');
    if (evaluationTrendsCtx) {
        new Chart(evaluationTrendsCtx, {
            type: 'line',
            data: {
                labels: @json($evaluationTrends->pluck('month')->toArray()),
                datasets: [{
                    label: 'Count',
                    data: @json($evaluationTrends->pluck('count')->toArray()),
                    borderColor: brandColors.accent,
                    backgroundColor: brandColors.accent + '20',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: brandColors.accent,
                    pointBorderColor: brandColors.textPrimary,
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }, {
                    label: 'Avg Rating',
                    data: @json($evaluationTrends->pluck('avg_rating')->toArray()),
                    borderColor: brandColors.platinum,
                    backgroundColor: brandColors.platinum + '20',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: brandColors.platinum,
                    pointBorderColor: brandColors.textPrimary,
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    yAxisID: 'y1'
                }]
            },
            options: {
                ...chartOptions,
                scales: {
                    y: {
                        beginAtZero: true,
                        position: 'left',
                        ticks: {
                            color: brandColors.textSecondary
                        },
                        grid: {
                            color: brandColors.accent + '20'
                        }
                    },
                    y1: {
                        beginAtZero: true,
                        max: 5,
                        position: 'right',
                        ticks: {
                            color: brandColors.textSecondary
                        },
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                }
            }
        });
    }

    // Role Distribution Chart
    const roleDistributionCtx = document.getElementById('roleDistributionChart');
    if (roleDistributionCtx) {
        new Chart(roleDistributionCtx, {
            type: 'bar',
            data: {
                labels: ['Housekeeper', 'House Manager', 'Nanny', 'Chef', 'Elderly Care', 'Nakawere Care'],
                datasets: [{
                    label: 'Maids by Role',
                    data: @json(array_values($roleDistribution)),
                    backgroundColor: [
                        brandColors.info,        // Housekeeper - Blue
                        brandColors.success,    // House Manager - Green
                        brandColors.platinum,    // Nanny - Purple
                        brandColors.warning,     // Chef - Amber
                        brandColors.accent,      // Elderly Care - Gold
                        brandColors.silver      // Nakawere Care - Silver
                    ],
                    borderWidth: 2,
                    borderColor: brandColors.textPrimary,
                    borderRadius: 4,
                    borderSkipped: false
                }]
            },
            options: {
                ...chartOptions,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: brandColors.textSecondary
                        },
                        grid: {
                            color: brandColors.accent + '20'
                        }
                    },
                    x: {
                        ticks: {
                            color: brandColors.textSecondary
                        },
                        grid: {
                            color: brandColors.accent + '20'
                        }
                    }
                }
            }
        });
    }
});
</script>
