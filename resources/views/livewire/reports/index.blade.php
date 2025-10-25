<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <flux:heading size="xl">{{ __('Comprehensive Reports') }}</flux:heading>
            <flux:subheading class="mt-1">{{ __('Detailed analytics and insights') }}</flux:subheading>
        </div>
        <div class="flex gap-3">
            <flux:button as="a" href="{{ route('dashboard') }}" variant="ghost" icon="arrow-left" wire:navigate>
                {{ __('Back to Dashboard') }}
            </flux:button>
            <flux:button wire:click="exportPdf" variant="primary" icon="arrow-down-tray">
                <span wire:loading.remove wire:target="exportPdf">{{ __('Export PDF Report') }}</span>
                <span wire:loading wire:target="exportPdf">{{ __('Generating...') }}</span>
            </flux:button>
        </div>
    </div>

    <flux:separator variant="subtle" />

    {{-- Filter Section --}}
    <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
        <div class="flex items-center gap-2 mb-4">
            <flux:icon.funnel class="size-5 text-[#F5B301]" />
            <h3 class="text-lg font-semibold text-white">{{ __('Report Filters & Date Range') }}</h3>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Date From -->
            <div>
                <flux:input 
                    wire:model.live="dateFrom" 
                    type="date"
                    :label="__('From Date')"
                    class="filter-input"
                />
            </div>

            <!-- Date To -->
            <div>
                <flux:input 
                    wire:model.live="dateTo" 
                    type="date"
                    :label="__('To Date')"
                    class="filter-input"
                />
            </div>

            <!-- Report Type Filter -->
            <div>
                <flux:select wire:model.live="reportType" :label="__('Report Type')" class="filter-select">
                    <option value="all">{{ __('All Reports') }}</option>
                    <option value="evaluations">{{ __('Evaluations Only') }}</option>
                    <option value="bookings">{{ __('Bookings Only') }}</option>
                    <option value="maids">{{ __('Maids Only') }}</option>
                    <option value="training">{{ __('Training Only') }}</option>
                </flux:select>
            </div>

            <!-- Quick Date Ranges -->
            <div>
                <flux:select wire:model.live="quickRange" :label="__('Quick Range')" class="filter-select">
                    <option value="">{{ __('Custom Range') }}</option>
                    <option value="today">{{ __('Today') }}</option>
                    <option value="week">{{ __('This Week') }}</option>
                    <option value="month">{{ __('This Month') }}</option>
                    <option value="quarter">{{ __('This Quarter') }}</option>
                    <option value="year">{{ __('This Year') }}</option>
                </flux:select>
            </div>
        </div>

        <!-- Filter Actions -->
        <div class="flex items-center justify-between mt-6 pt-4 border-t border-[#F5B301]/20">
            <div class="flex items-center gap-2 text-sm text-[#D1C4E9]">
                <flux:icon.information-circle class="size-4" />
                <span>{{ __('Showing data from') }} {{ $dateFrom ?? 'start' }} {{ __('to') }} {{ $dateTo ?? 'today' }}</span>
            </div>
            
            <div class="flex items-center gap-2">
                <flux:button 
                    wire:click="resetFilters" 
                    variant="outline" 
                    size="sm"
                    icon="arrow-path"
                >
                    {{ __('Reset') }}
                </flux:button>
                
                <flux:button 
                    wire:click="exportPdf" 
                    variant="primary" 
                    size="sm"
                    icon="arrow-down-tray"
                >
                    <span wire:loading.remove wire:target="exportPdf">{{ __('Export PDF') }}</span>
                    <span wire:loading wire:target="exportPdf">{{ __('Generating...') }}</span>
                </flux:button>
            </div>
        </div>
    </div>

    {{-- EXECUTIVE SUMMARY --}}
    <div>
        <h2 class="mb-4 flex items-center gap-2 text-lg font-semibold text-white">
            <flux:icon.chart-bar class="size-6 text-[#F5B301]" />
            {{ __('Executive Summary') }}
        </h2>
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
            
            <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-4 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-[#D1C4E9]">{{ __('Total Users') }}</p>
                        <p class="mt-2 text-2xl font-bold text-white">{{ number_format($totalUsers) }}</p>
                    </div>
                    <div class="rounded-full bg-[#64B5F6] p-2">
                        <flux:icon.users class="size-5 text-white" />
                    </div>
                </div>
            </div>

            <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-4 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-[#D1C4E9]">{{ __('Total Maids') }}</p>
                        <p class="mt-2 text-2xl font-bold text-white">{{ number_format($totalMaids) }}</p>
                    </div>
                    <div class="rounded-full bg-[#4CAF50] p-2">
                        <flux:icon.user-group class="size-5 text-white" />
                    </div>
                </div>
            </div>

            <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-4 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-[#D1C4E9]">{{ __('Total Clients') }}</p>
                        <p class="mt-2 text-2xl font-bold text-white">{{ number_format($totalClients) }}</p>
                    </div>
                    <div class="rounded-full bg-[#B9A0DC] p-2">
                        <flux:icon.building-office class="size-5 text-white" />
                    </div>
                </div>
            </div>

            <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-4 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-[#D1C4E9]">{{ __('Total Bookings') }}</p>
                        <p class="mt-2 text-2xl font-bold text-white">{{ number_format($totalBookings) }}</p>
                    </div>
                    <div class="rounded-full bg-[#F5B301] p-2">
                        <flux:icon.calendar class="size-5 text-[#3B0A45]" />
                    </div>
                </div>
            </div>

            <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-4 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-[#D1C4E9]">{{ __('Evaluations') }}</p>
                        <p class="mt-2 text-2xl font-bold text-white">{{ number_format($totalEvaluations) }}</p>
                    </div>
                    <div class="rounded-full bg-[#FFC107] p-2">
                        <flux:icon.clipboard-document-check class="size-5 text-white" />
                    </div>
                </div>
            </div>

            <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-4 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-[#D1C4E9]">{{ __('Programs') }}</p>
                        <p class="mt-2 text-2xl font-bold text-white">{{ number_format($totalPrograms) }}</p>
                    </div>
                    <div class="rounded-full bg-[#64B5F6] p-2">
                        <flux:icon.academic-cap class="size-5 text-white" />
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- EVALUATION ANALYTICS --}}
    <div>
        <h2 class="mb-4 flex items-center gap-2 text-lg font-semibold text-white">
            <flux:icon.clipboard-document-check class="size-6 text-[#F5B301]" />
            {{ __('Evaluation Analytics') }}
        </h2>
        <div class="grid gap-6 lg:grid-cols-2">
            
            {{-- Evaluations by Status --}}
            <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
                <h3 class="mb-4 text-base font-semibold text-white">{{ __('Evaluations by Status') }}</h3>
                <div style="position: relative; height: 300px;">
                    <canvas id="evaluationStatusChart"></canvas>
                </div>
            </div>

            {{-- Evaluations by Rating --}}
            <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
                <h3 class="mb-4 text-base font-semibold text-white">{{ __('Evaluations by Rating') }}</h3>
                <div style="position: relative; height: 250px;">
                    <canvas id="evaluationRatingChart"></canvas>
                </div>
                <div class="mt-4 text-center">
                    <p class="text-sm text-[#D1C4E9]">{{ __('Average Rating') }}</p>
                    <p class="text-3xl font-bold text-[#F5B301]">{{ number_format($averageRating ?? 0, 2) }}/5.0</p>
                </div>
            </div>

            {{-- Monthly Trends --}}
            <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg lg:col-span-2">
                <h3 class="mb-4 text-base font-semibold text-white">{{ __('Monthly Evaluation Trends') }}</h3>
                <div style="position: relative; height: 300px;">
                    <canvas id="monthlyTrendsChart"></canvas>
                </div>
            </div>

        </div>
    </div>

    {{-- BOOKING ANALYTICS --}}
    <div>
        <h2 class="mb-4 flex items-center gap-2 text-lg font-semibold text-white">
            <flux:icon.calendar class="size-6 text-[#F5B301]" />
            {{ __('Booking Analytics') }}
        </h2>
        <div class="grid gap-6 lg:grid-cols-3">
            
            {{-- Total Revenue --}}
            <div class="rounded-lg border border-[#F5B301]/30 bg-gradient-to-br from-[#4CAF50] to-[#4CAF50]/80 p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-white/90">{{ __('Total Revenue') }}</p>
                        <p class="mt-2 text-3xl font-bold text-white">UGX {{ number_format($totalRevenue) }}</p>
                        <p class="mt-1 text-xs text-white/80">{{ __('Selected period') }}</p>
                    </div>
                    <div class="rounded-full bg-white/20 p-3">
                        <flux:icon.currency-dollar class="size-8 text-white" />
                    </div>
                </div>
            </div>

            {{-- Bookings by Status --}}
            <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg lg:col-span-2">
                <h3 class="mb-4 text-base font-semibold text-white">{{ __('Bookings by Status') }}</h3>
                <div style="position: relative; height: 300px;">
                    <canvas id="bookingStatusChart"></canvas>
                </div>
            </div>

        </div>
    </div>

    {{-- MAID ANALYTICS --}}
    <div>
        <h2 class="mb-4 flex items-center gap-2 text-lg font-semibold text-white">
            <flux:icon.user-group class="size-6 text-[#F5B301]" />
            {{ __('Maid Analytics') }}
        </h2>
        <div class="grid gap-6 lg:grid-cols-2">
            
            {{-- Maids by Status --}}
            <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
                <h3 class="mb-4 text-base font-semibold text-white">{{ __('Maids by Status') }}</h3>
                <div style="position: relative; height: 300px;">
                    <canvas id="maidStatusChart"></canvas>
                </div>
            </div>

            {{-- Maids by Role --}}
            <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
                <h3 class="mb-4 text-base font-semibold text-white">{{ __('Maids by Role') }}</h3>
                <div style="position: relative; height: 300px;">
                    <canvas id="maidRoleChart"></canvas>
                </div>
            </div>

        </div>
    </div>

    {{-- TRAINING ANALYTICS --}}
    <div>
        <h2 class="mb-4 flex items-center gap-2 text-lg font-semibold text-white">
            <flux:icon.academic-cap class="size-6 text-[#F5B301]" />
            {{ __('Training Analytics') }}
        </h2>
        <div class="grid gap-6 lg:grid-cols-2">
            
            {{-- Programs by Status --}}
            <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
                <h3 class="mb-4 text-base font-semibold text-white">{{ __('Programs by Status') }}</h3>
                <div style="position: relative; height: 300px;">
                    <canvas id="programStatusChart"></canvas>
                </div>
            </div>

            {{-- Programs by Type --}}
            <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
                <h3 class="mb-4 text-base font-semibold text-white">{{ __('Programs by Type') }}</h3>
                <div style="position: relative; height: 300px;">
                    <canvas id="programTypeChart"></canvas>
                </div>
            </div>

        </div>
    </div>

    {{-- TOP PERFORMERS --}}
    <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
        <h2 class="mb-4 flex items-center gap-2 text-lg font-semibold text-white">
            <flux:icon.trophy class="size-6 text-[#F5B301]" />
            {{ __('Top Rated Maids') }}
        </h2>
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
            @forelse ($topRatedMaids as $item)
                <div class="rounded-lg border border-[#F5B301]/30 bg-[#3B0A45] p-4 shadow-md">
                    <div class="text-center">
                        <p class="font-semibold text-white">
                            {{ $item->maid?->first_name }} {{ $item->maid?->last_name }}
                        </p>
                        <div class="mt-2">
                            <flux:badge :color="$item->avg_rating >= 4.5 ? 'green' : ($item->avg_rating >= 4.0 ? 'blue' : 'yellow')" size="lg">
                                {{ number_format($item->avg_rating, 2) }}/5.0
                            </flux:badge>
                        </div>
                        <p class="mt-1 text-xs text-[#D1C4E9]">{{ $item->eval_count }} {{ __('evaluations') }}</p>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-8 text-center text-sm text-[#D1C4E9]">
                    {{ __('No evaluation data available for the selected period.') }}
                </div>
            @endforelse
        </div>
    </div>

    {{-- RECENT ACTIVITY --}}
    <div class="grid gap-6 lg:grid-cols-2">
        
        {{-- Recent Evaluations --}}
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <h3 class="mb-4 text-base font-semibold text-white">{{ __('Recent Evaluations') }}</h3>
            <div class="space-y-2">
                @forelse ($recentEvaluations->take(8) as $evaluation)
                    <div class="flex items-center justify-between rounded-lg bg-[#3B0A45]/50 p-3 border border-[#F5B301]/20">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-white">
                                {{ $evaluation->maid?->first_name }} {{ $evaluation->maid?->last_name }}
                            </p>
                            <p class="text-xs text-[#D1C4E9]">
                                {{ __('by') }} {{ $evaluation->trainer?->user?->name }} • {{ $evaluation->evaluation_date?->format('M d, Y') }}
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

        {{-- Recent Bookings --}}
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <h3 class="mb-4 text-base font-semibold text-white">{{ __('Recent Bookings') }}</h3>
            <div class="space-y-2">
                @forelse ($recentBookings->take(8) as $booking)
                    <div class="flex items-center justify-between rounded-lg bg-[#3B0A45]/50 p-3 border border-[#F5B301]/20">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-white">
                                {{ $booking->client?->first_name }} {{ $booking->client?->last_name }}
                            </p>
                            <p class="text-xs text-[#D1C4E9]">
                                {{ $booking->maid?->first_name }} {{ $booking->maid?->last_name }} • {{ $booking->start_date?->format('M d, Y') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <flux:badge :color="match($booking->status) {
                                'active' => 'blue',
                                'completed' => 'green',
                                'cancelled' => 'red',
                                default => 'yellow'
                            }" size="sm">
                                {{ ucfirst($booking->status) }}
                            </flux:badge>
                            <p class="mt-1 text-xs font-medium text-white">
                                UGX {{ number_format($booking->amount ?? 0) }}
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="py-4 text-center text-sm text-[#D1C4E9]">{{ __('No recent bookings.') }}</p>
                @endforelse
            </div>
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
        primary: '#3B0A45',
        secondary: '#512B58',
        accent: '#F5B301',
        success: '#4CAF50',
        warning: '#FFC107',
        error: '#E53935',
        info: '#64B5F6',
        silver: '#A8A9AD',
        goldPackage: '#FFD700',
        platinum: '#B9A0DC',
        textPrimary: '#FFFFFF',
        textSecondary: '#D1C4E9'
    };

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

    // Evaluation Status Chart
    if (document.getElementById('evaluationStatusChart')) {
        new Chart(document.getElementById('evaluationStatusChart'), {
            type: 'doughnut',
            data: {
                labels: @json($evaluationsByStatusLabels),
                datasets: [{
                    data: @json(array_values($evaluationsByStatus)),
                    backgroundColor: [
                        brandColors.success,
                        brandColors.warning,
                        brandColors.error
                    ],
                    borderWidth: 2,
                    borderColor: brandColors.textPrimary,
                    hoverOffset: 4
                }]
            },
            options: chartOptions
        });
    }

    // Evaluation Rating Chart
    if (document.getElementById('evaluationRatingChart')) {
        new Chart(document.getElementById('evaluationRatingChart'), {
            type: 'bar',
            data: {
                labels: @json($evaluationsByRating->pluck('rating')->map(fn($r) => $r . ' Stars')->toArray()),
                datasets: [{
                    label: 'Count',
                    data: @json($evaluationsByRating->pluck('count')->toArray()),
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                ...chartOptions,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Monthly Trends Chart
    if (document.getElementById('monthlyTrendsChart')) {
        new Chart(document.getElementById('monthlyTrendsChart'), {
            type: 'line',
            data: {
                labels: @json($monthlyTrends->pluck('month')->toArray()),
                datasets: [{
                    label: 'Evaluation Count',
                    data: @json($monthlyTrends->pluck('count')->toArray()),
                    borderColor: 'rgba(59, 130, 246, 1)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Avg Rating',
                    data: @json($monthlyTrends->pluck('avg_rating')->toArray()),
                    borderColor: 'rgba(251, 146, 60, 1)',
                    backgroundColor: 'rgba(251, 146, 60, 0.1)',
                    tension: 0.4,
                    fill: true,
                    yAxisID: 'y1'
                }]
            },
            options: {
                ...chartOptions,
                scales: {
                    y: {
                        beginAtZero: true,
                        position: 'left'
                    },
                    y1: {
                        beginAtZero: true,
                        max: 5,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                }
            }
        });
    }

    // Booking Status Chart
    if (document.getElementById('bookingStatusChart')) {
        new Chart(document.getElementById('bookingStatusChart'), {
            type: 'pie',
            data: {
                labels: @json($bookingsByStatusLabels),
                datasets: [{
                    data: @json(array_values($bookingsByStatus)),
                    backgroundColor: [
                        'rgba(251, 191, 36, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(34, 197, 94, 0.8)',
                        'rgba(239, 68, 68, 0.8)'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: chartOptions
        });
    }

    // Maid Status Chart
    if (document.getElementById('maidStatusChart')) {
        new Chart(document.getElementById('maidStatusChart'), {
            type: 'doughnut',
            data: {
                labels: @json($maidsByStatusLabels),
                datasets: [{
                    data: @json(array_values($maidsByStatus)),
                    backgroundColor: [
                        'rgba(34, 197, 94, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(168, 85, 247, 0.8)',
                        'rgba(251, 146, 60, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(107, 114, 128, 0.8)',
                        'rgba(251, 191, 36, 0.8)'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: chartOptions
        });
    }

    // Maid Role Chart
    if (document.getElementById('maidRoleChart')) {
        new Chart(document.getElementById('maidRoleChart'), {
            type: 'bar',
            data: {
                labels: @json($maidsByRoleLabels),
                datasets: [{
                    label: 'Maids',
                    data: @json(array_values($maidsByRole)),
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(34, 197, 94, 0.8)',
                        'rgba(168, 85, 247, 0.8)',
                        'rgba(251, 146, 60, 0.8)',
                        'rgba(251, 191, 36, 0.8)',
                        'rgba(236, 72, 153, 0.8)'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                ...chartOptions,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Program Status Chart
    if (document.getElementById('programStatusChart')) {
        new Chart(document.getElementById('programStatusChart'), {
            type: 'pie',
            data: {
                labels: @json($programsByStatusLabels),
                datasets: [{
                    data: @json(array_values($programsByStatus)),
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(34, 197, 94, 0.8)',
                        'rgba(239, 68, 68, 0.8)'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: chartOptions
        });
    }

    // Program Type Chart
    if (document.getElementById('programTypeChart')) {
        new Chart(document.getElementById('programTypeChart'), {
            type: 'bar',
            data: {
                labels: @json($programsByTypeLabels),
                datasets: [{
                    label: 'Programs',
                    data: @json(array_values($programsByType)),
                    backgroundColor: 'rgba(168, 85, 247, 0.8)',
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                ...chartOptions,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
});
</script>

