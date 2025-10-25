<div class="space-y-8">
    <!-- Header -->
    <div class="bg-gradient-to-r from-[#3B0A45] to-[#512B58] rounded-xl border border-[#F5B301]/30 shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" class="text-white">Ticket Analytics Dashboard</flux:heading>
                <flux:subheading class="text-[#D1C4E9] mt-2">Comprehensive insights into support ticket performance</flux:subheading>
            </div>
            <div class="flex items-center gap-4">
                <flux:icon.chart-bar class="w-8 h-8 text-[#F5B301]" />
                <div class="text-right">
                    <div class="text-sm text-[#D1C4E9]">Last Updated</div>
                    <div class="text-white font-semibold">{{ now()->format('M d, Y H:i') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Cards Row 1 -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Tickets -->
        <div class="bg-[#512B58] rounded-xl border border-[#F5B301]/30 shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-[#D1C4E9] text-sm font-medium">Total Tickets</div>
                    <div class="text-3xl font-bold text-white mt-2">{{ $totalTickets }}</div>
                    <div class="text-xs text-[#D1C4E9]/70 mt-1">All time</div>
                </div>
                <div class="w-12 h-12 bg-[#3B0A45] rounded-lg flex items-center justify-center">
                    <flux:icon.ticket class="w-6 h-6 text-[#F5B301]" />
                </div>
            </div>
        </div>

        <!-- Open Tickets -->
        <div class="bg-[#512B58] rounded-xl border border-[#F5B301]/30 shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-[#D1C4E9] text-sm font-medium">Open Tickets</div>
                    <div class="text-3xl font-bold text-white mt-2">{{ $openTickets }}</div>
                    <div class="text-xs text-[#D1C4E9]/70 mt-1">Currently active</div>
                </div>
                <div class="w-12 h-12 bg-[#3B0A45] rounded-lg flex items-center justify-center">
                    <flux:icon.information-circle class="w-6 h-6 text-[#F5B301]" />
                </div>
            </div>
        </div>

        <!-- SLA Breached -->
        <div class="bg-[#512B58] rounded-xl border border-[#F5B301]/30 shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-[#D1C4E9] text-sm font-medium">SLA Breached</div>
                    <div class="text-3xl font-bold text-red-400 mt-2">{{ $slaBreached }}</div>
                    <div class="text-xs text-[#D1C4E9]/70 mt-1">Requires attention</div>
                </div>
                <div class="w-12 h-12 bg-red-900/30 rounded-lg flex items-center justify-center">
                    <flux:icon.clock class="w-6 h-6 text-red-400" />
                </div>
            </div>
        </div>

        <!-- Avg Response Time -->
        <div class="bg-[#512B58] rounded-xl border border-[#F5B301]/30 shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-[#D1C4E9] text-sm font-medium">Avg Response Time</div>
                    <div class="text-3xl font-bold text-white mt-2">{{ $avgResponseTime }}</div>
                    <div class="text-xs text-[#D1C4E9]/70 mt-1">Hours to first response</div>
                </div>
                <div class="w-12 h-12 bg-[#3B0A45] rounded-lg flex items-center justify-center">
                    <flux:icon.clock class="w-6 h-6 text-[#F5B301]" />
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Cards Row 2 -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Resolution Rate -->
        <div class="bg-[#512B58] rounded-xl border border-[#F5B301]/30 shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-[#D1C4E9] text-sm font-medium">Resolution Rate</div>
                    <div class="text-3xl font-bold text-green-400 mt-2">{{ $resolutionRate }}%</div>
                    <div class="text-xs text-[#D1C4E9]/70 mt-1">Tickets resolved</div>
                </div>
                <div class="w-12 h-12 bg-green-900/30 rounded-lg flex items-center justify-center">
                    <flux:icon.check-circle class="w-6 h-6 text-green-400" />
                </div>
            </div>
        </div>

        <!-- Customer Satisfaction -->
        <div class="bg-[#512B58] rounded-xl border border-[#F5B301]/30 shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-[#D1C4E9] text-sm font-medium">Satisfaction</div>
                    <div class="text-3xl font-bold text-yellow-400 mt-2">{{ $satisfactionRating }}/5</div>
                    <div class="text-xs text-[#D1C4E9]/70 mt-1">Average rating</div>
                </div>
                <div class="w-12 h-12 bg-yellow-900/30 rounded-lg flex items-center justify-center">
                    <flux:icon.star class="w-6 h-6 text-yellow-400" />
                </div>
            </div>
        </div>

        <!-- Tier Performance -->
        <div class="bg-[#512B58] rounded-xl border border-[#F5B301]/30 shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-[#D1C4E9] text-sm font-medium">Platinum Tickets</div>
                    <div class="text-3xl font-bold text-purple-400 mt-2">{{ $platinumTickets }}</div>
                    <div class="text-xs text-[#D1C4E9]/70 mt-1">VIP clients</div>
                </div>
                <div class="w-12 h-12 bg-purple-900/30 rounded-lg flex items-center justify-center">
                    <flux:icon.star class="w-6 h-6 text-purple-400" />
                </div>
            </div>
        </div>

        <!-- Staff Performance -->
        <div class="bg-[#512B58] rounded-xl border border-[#F5B301]/30 shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-[#D1C4E9] text-sm font-medium">Active Staff</div>
                    <div class="text-3xl font-bold text-blue-400 mt-2">{{ $activeStaff }}</div>
                    <div class="text-xs text-[#D1C4E9]/70 mt-1">Handling tickets</div>
                </div>
                <div class="w-12 h-12 bg-blue-900/30 rounded-lg flex items-center justify-center">
                    <flux:icon.users class="w-6 h-6 text-blue-400" />
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Ticket Volume Trend -->
        <div class="bg-[#512B58] rounded-xl border border-[#F5B301]/30 shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <flux:heading size="lg" class="text-white">Ticket Volume Trend</flux:heading>
                <div class="flex gap-2">
                    <button class="px-3 py-1 bg-[#3B0A45] text-[#D1C4E9] rounded-lg text-sm">7D</button>
                    <button class="px-3 py-1 bg-[#F5B301] text-[#3B0A45] rounded-lg text-sm font-medium">30D</button>
                    <button class="px-3 py-1 bg-[#3B0A45] text-[#D1C4E9] rounded-lg text-sm">90D</button>
                </div>
            </div>
            <div class="h-64">
                <canvas id="ticketVolumeChart"></canvas>
            </div>
        </div>

        <!-- Status Distribution -->
        <div class="bg-[#512B58] rounded-xl border border-[#F5B301]/30 shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <flux:heading size="lg" class="text-white">Status Distribution</flux:heading>
                <flux:icon.chart-pie class="w-6 h-6 text-[#F5B301]" />
            </div>
            <div class="h-64">
                <canvas id="statusDistributionChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Performance Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Priority Breakdown -->
        <div class="bg-[#512B58] rounded-xl border border-[#F5B301]/30 shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <flux:heading size="lg" class="text-white">Priority Breakdown</flux:heading>
                <flux:icon.chart-bar class="w-6 h-6 text-[#F5B301]" />
            </div>
            <div class="h-64">
                <canvas id="priorityChart"></canvas>
            </div>
        </div>

        <!-- Response Time by Tier -->
        <div class="bg-[#512B58] rounded-xl border border-[#F5B301]/30 shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <flux:heading size="lg" class="text-white">Response Time by Tier</flux:heading>
                <flux:icon.clock class="w-6 h-6 text-[#F5B301]" />
            </div>
            <div class="h-64">
                <canvas id="tierPerformanceChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity & Alerts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Tickets -->
        <div class="bg-[#512B58] rounded-xl border border-[#F5B301]/30 shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <flux:heading size="lg" class="text-white">Recent Tickets</flux:heading>
                <a href="{{ route('tickets.index') }}" class="text-[#F5B301] hover:text-[#F5B301]/80 text-sm font-medium">View All</a>
            </div>
            <div class="space-y-4">
                @forelse($recentTickets as $ticket)
                <div class="flex items-center justify-between p-4 bg-[#3B0A45]/50 rounded-lg border border-[#F5B301]/20">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 bg-{{ $ticket->getPriorityColor() }}-500 rounded-full"></div>
                        <div>
                            <div class="text-white font-medium">{{ $ticket->ticket_number }}</div>
                            <div class="text-[#D1C4E9] text-sm">{{ Str::limit($ticket->subject, 40) }}</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-[#D1C4E9] text-sm">{{ $ticket->created_at->diffForHumans() }}</div>
                        <div class="text-xs text-[#D1C4E9]/70">{{ ucfirst($ticket->status) }}</div>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <flux:icon.ticket class="w-12 h-12 text-[#D1C4E9]/50 mx-auto mb-4" />
                    <div class="text-[#D1C4E9]">No recent tickets</div>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Staff Performance -->
        <div class="bg-[#512B58] rounded-xl border border-[#F5B301]/30 shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <flux:heading size="lg" class="text-white">Staff Performance</flux:heading>
                <flux:icon.users class="w-6 h-6 text-[#F5B301]" />
            </div>
            <div class="space-y-4">
                @forelse($staffPerformance as $staff)
                <div class="flex items-center justify-between p-4 bg-[#3B0A45]/50 rounded-lg border border-[#F5B301]/20">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-[#F5B301] rounded-full flex items-center justify-center">
                            <span class="text-[#3B0A45] font-bold text-sm">{{ substr($staff->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <div class="text-white font-medium">{{ $staff->name }}</div>
                            <div class="text-[#D1C4E9] text-sm">{{ $staff->tickets_count }} tickets</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-[#D1C4E9] text-sm">{{ $staff->avg_resolution_time }}h avg</div>
                        <div class="text-xs text-[#D1C4E9]/70">{{ $staff->satisfaction_rating }}/5 rating</div>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <flux:icon.users class="w-12 h-12 text-[#D1C4E9]/50 mx-auto mb-4" />
                    <div class="text-[#D1C4E9]">No staff data available</div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Brand colors for charts
    const brandColors = {
        primary: '#3B0A45',
        secondary: '#512B58',
        accent: '#F5B301',
        success: '#10B981',
        warning: '#F59E0B',
        error: '#EF4444',
        info: '#3B82F6',
        purple: '#8B5CF6',
        gold: '#F5B301',
        silver: '#9CA3AF'
    };

    // Common chart options
    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                labels: {
                    color: '#D1C4E9',
                    font: {
                        size: 12
                    }
                }
            },
            tooltip: {
                backgroundColor: '#3B0A45',
                titleColor: '#F5B301',
                bodyColor: '#D1C4E9',
                borderColor: '#F5B301',
                borderWidth: 1
            }
        },
        scales: {
            x: {
                ticks: { color: '#D1C4E9' },
                grid: { color: '#F5B301/20' }
            },
            y: {
                ticks: { color: '#D1C4E9' },
                grid: { color: '#F5B301/20' }
            }
        }
    };

    // Ticket Volume Trend Chart
    const volumeCanvas = document.getElementById('ticketVolumeChart');
    if (volumeCanvas) {
        const volumeCtx = volumeCanvas.getContext('2d');
        new Chart(volumeCtx, {
        type: 'line',
        data: {
            labels: @json($volumeLabels),
            datasets: [{
                label: 'Tickets Created',
                data: @json($volumeData),
                borderColor: brandColors.accent,
                backgroundColor: brandColors.accent + '20',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            ...chartOptions,
            plugins: {
                ...chartOptions.plugins,
                legend: {
                    display: false
                }
            }
        }
        });
    }

    // Status Distribution Chart
    const statusCanvas = document.getElementById('statusDistributionChart');
    if (statusCanvas) {
        const statusCtx = statusCanvas.getContext('2d');
        new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: @json($statusLabels),
            datasets: [{
                data: @json($statusData),
                backgroundColor: [
                    brandColors.info,
                    brandColors.warning,
                    brandColors.success,
                    brandColors.error,
                    brandColors.purple
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#D1C4E9',
                        padding: 20,
                        usePointStyle: true
                    }
                },
                tooltip: {
                    backgroundColor: '#3B0A45',
                    titleColor: '#F5B301',
                    bodyColor: '#D1C4E9',
                    borderColor: '#F5B301',
                    borderWidth: 1
                }
            }
        }
        });
    }

    // Priority Chart
    const priorityCanvas = document.getElementById('priorityChart');
    if (priorityCanvas) {
        const priorityCtx = priorityCanvas.getContext('2d');
        new Chart(priorityCtx, {
        type: 'bar',
        data: {
            labels: @json($priorityLabels),
            datasets: [{
                label: 'Tickets',
                data: @json($priorityData),
                backgroundColor: [
                    brandColors.error,
                    brandColors.warning,
                    brandColors.accent,
                    brandColors.info,
                    brandColors.silver
                ],
                borderColor: brandColors.accent,
                borderWidth: 1
            }]
        },
        options: chartOptions
        });
    }

    // Tier Performance Chart
    const tierCtx = document.getElementById('tierPerformanceChart').getContext('2d');
    new Chart(tierCtx, {
        type: 'bar',
        data: {
            labels: ['Silver', 'Gold', 'Platinum'],
            datasets: [{
                label: 'Avg Response Time (hours)',
                data: @json($tierResponseTimes),
                backgroundColor: [
                    brandColors.silver,
                    brandColors.accent,
                    brandColors.purple
                ],
                borderColor: brandColors.accent,
                borderWidth: 1
            }]
        },
        options: chartOptions
    });
});
</script>