<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <flux:heading size="xl">{{ __('Trainer Performance Dashboard') }}</flux:heading>
            <flux:subheading class="mt-1">{{ __('Comprehensive evaluation analytics and training performance metrics') }}</flux:subheading>
        </div>
    </div>

    <flux:separator variant="subtle" />

    <!-- Filter Section -->
    <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
        <div class="flex items-center gap-2 mb-4">
            <flux:icon.funnel class="size-5 text-[#F5B301]" />
            <h3 class="text-lg font-semibold text-white">{{ __('Dashboard Filters & Period Selection') }}</h3>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Period Selection -->
            <div>
                <flux:select wire:model.live="selectedPeriod" :label="__('Time Period')" class="filter-select">
                    <option value="week">{{ __('This Week') }}</option>
                    <option value="month">{{ __('This Month') }}</option>
                    <option value="quarter">{{ __('This Quarter') }}</option>
                    <option value="year">{{ __('This Year') }}</option>
                    <option value="all">{{ __('All Time') }}</option>
                </flux:select>
            </div>
            
            <!-- Custom Days -->
            <div>
                <flux:input 
                    wire:model.live="dateRange" 
                    type="number" 
                    :label="__('Custom Days')"
                    class="filter-input"
                    placeholder="30"
                />
            </div>

            <!-- Trainee Filter -->
            <div>
                <flux:select wire:model.live="selectedMaid" :label="__('Filter by Trainee')" class="filter-select">
                    <option value="">{{ __('All Trainees') }}</option>
                    @foreach($this->maids as $maid)
                        <option value="{{ $maid->id }}">{{ $maid->full_name }}</option>
                    @endforeach
                </flux:select>
            </div>

            <!-- Status Filter -->
            <div>
                <flux:select wire:model.live="statusFilter" :label="__('Evaluation Status')" class="filter-select">
                    <option value="all">{{ __('All Status') }}</option>
                    <option value="pending">{{ __('Pending') }}</option>
                    <option value="approved">{{ __('Approved') }}</option>
                    <option value="rejected">{{ __('Rejected') }}</option>
                </flux:select>
            </div>

            <!-- Actions -->
            <div class="flex items-end gap-2">
                <flux:button 
                    wire:click="resetFilters" 
                    variant="outline" 
                    size="sm"
                    icon="arrow-path"
                    class="flex-1"
                >
                    {{ __('Reset') }}
                </flux:button>
            </div>
        </div>

        <!-- Filter Actions -->
        <div class="flex items-center justify-between mt-6 pt-4 border-t border-[#F5B301]/20">
            <div class="flex items-center gap-2 text-sm text-[#D1C4E9]">
                <flux:icon.information-circle class="size-4" />
                <span>{{ __('Showing metrics for') }} {{ $selectedPeriod }}</span>
            </div>
            
            <div class="flex items-center gap-2">
                <flux:button 
                    wire:click="exportReport" 
                    variant="primary" 
                    size="sm"
                    icon="arrow-down-tray"
                >
                    {{ __('Export Report') }}
                </flux:button>
            </div>
        </div>
    </div>

    <!-- Key Metrics Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Evaluations -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-6 border border-blue-200 dark:border-blue-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-600 dark:text-blue-400">{{ __('Total Evaluations') }}</p>
                    <p class="text-3xl font-bold text-blue-900 dark:text-blue-100">{{ number_format($totalEvaluations) }}</p>
                    <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                        <span class="inline-flex items-center">
                            <x-flux::icon.check-circle class="w-4 h-4 mr-1" />
                            {{ $approvedEvaluations }} {{ __('approved') }}
                        </span>
                    </p>
                </div>
                <div class="p-3 bg-blue-500 rounded-lg">
                    <x-flux::icon.clipboard-document-check class="w-6 h-6 text-white" />
                </div>
            </div>
        </div>

        <!-- Average Rating -->
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-xl p-6 border border-purple-200 dark:border-purple-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-purple-600 dark:text-purple-400">{{ __('Average Rating') }}</p>
                    <p class="text-3xl font-bold text-purple-900 dark:text-purple-100">{{ number_format($averageOverallRating, 1) }}/5</p>
                    <p class="text-xs text-purple-600 dark:text-purple-400 mt-1">
                        <span class="inline-flex items-center">
                            <x-flux::icon.star class="w-4 h-4 mr-1" />
                            {{ __('Overall performance') }}
                        </span>
                    </p>
                </div>
                <div class="p-3 bg-purple-500 rounded-lg">
                    <x-flux::icon.star class="w-6 h-6 text-white" />
                </div>
            </div>
        </div>

        <!-- Training Programs -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl p-6 border border-green-200 dark:border-green-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-green-600 dark:text-green-400">{{ __('Training Programs') }}</p>
                    <p class="text-3xl font-bold text-green-900 dark:text-green-100">{{ number_format($totalTrainingPrograms) }}</p>
                    <p class="text-xs text-green-600 dark:text-green-400 mt-1">
                        <span class="inline-flex items-center">
                            <x-flux::icon.academic-cap class="w-4 h-4 mr-1" />
                            {{ $completedPrograms }} {{ __('completed') }}
                        </span>
                    </p>
                </div>
                <div class="p-3 bg-green-500 rounded-lg">
                    <x-flux::icon.academic-cap class="w-6 h-6 text-white" />
                </div>
            </div>
        </div>

        <!-- Total Trainees -->
        <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 rounded-xl p-6 border border-orange-200 dark:border-orange-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-orange-600 dark:text-orange-400">{{ __('Total Trainees') }}</p>
                    <p class="text-3xl font-bold text-orange-900 dark:text-orange-100">{{ number_format($totalTrainees) }}</p>
                    <p class="text-xs text-orange-600 dark:text-orange-400 mt-1">
                        <span class="inline-flex items-center">
                            <x-flux::icon.user-group class="w-4 h-4 mr-1" />
                            {{ number_format($totalHours) }} {{ __('hours trained') }}
                        </span>
                    </p>
                </div>
                <div class="p-3 bg-orange-500 rounded-lg">
                    <x-flux::icon.user-group class="w-6 h-6 text-white" />
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Evaluation Performance -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <flux:heading size="lg">{{ __('Evaluation Performance') }}</flux:heading>
                <x-flux::icon.clipboard-document-check class="w-5 h-5 text-slate-400" />
            </div>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Completion Rate') }}</span>
                    <span class="text-lg font-bold text-slate-900 dark:text-white">{{ $evaluationCompletionRate }}%</span>
                </div>
                <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                    <div class="bg-blue-500 h-2 rounded-full transition-all duration-500" style="width: {{ min($evaluationCompletionRate, 100) }}%"></div>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Approval Rate') }}</span>
                    <span class="text-lg font-bold text-slate-900 dark:text-white">{{ $approvalRate }}%</span>
                </div>
                <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                    <div class="bg-green-500 h-2 rounded-full transition-all duration-500" style="width: {{ min($approvalRate, 100) }}%"></div>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Pending Evaluations') }}</span>
                    <span class="text-lg font-bold text-slate-900 dark:text-white">{{ $pendingEvaluations }}</span>
                </div>
            </div>
        </div>

        <!-- Training Performance -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <flux:heading size="lg">{{ __('Training Performance') }}</flux:heading>
                <x-flux::icon.academic-cap class="w-5 h-5 text-slate-400" />
            </div>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Completion Rate') }}</span>
                    <span class="text-lg font-bold text-slate-900 dark:text-white">{{ $programCompletionRate }}%</span>
                </div>
                <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                    <div class="bg-purple-500 h-2 rounded-full transition-all duration-500" style="width: {{ min($programCompletionRate, 100) }}%"></div>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Active Programs') }}</span>
                    <span class="text-lg font-bold text-slate-900 dark:text-white">{{ $activePrograms }}</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Scheduled Programs') }}</span>
                    <span class="text-lg font-bold text-slate-900 dark:text-white">{{ $scheduledPrograms }}</span>
                </div>
            </div>
        </div>

        <!-- Score Breakdown -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <flux:heading size="lg">{{ __('Score Breakdown') }}</flux:heading>
                <x-flux::icon.chart-bar class="w-5 h-5 text-slate-400" />
            </div>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Personality Average') }}</span>
                    <span class="text-lg font-bold text-slate-900 dark:text-white">{{ number_format($personalityAverage, 1) }}/5</span>
                </div>
                <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                    <div class="bg-blue-500 h-2 rounded-full transition-all duration-500" style="width: {{ ($personalityAverage / 5) * 100 }}%"></div>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Behavior Average') }}</span>
                    <span class="text-lg font-bold text-slate-900 dark:text-white">{{ number_format($behaviorAverage, 1) }}/5</span>
                </div>
                <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                    <div class="bg-green-500 h-2 rounded-full transition-all duration-500" style="width: {{ ($behaviorAverage / 5) * 100 }}%"></div>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Performance Average') }}</span>
                    <span class="text-lg font-bold text-slate-900 dark:text-white">{{ number_format($performanceAverage, 1) }}/5</span>
                </div>
                <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                    <div class="bg-purple-500 h-2 rounded-full transition-all duration-500" style="width: {{ ($performanceAverage / 5) * 100 }}%"></div>
                </div>
            </div>
        </div>

        <!-- Training Efficiency -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <flux:heading size="lg">{{ __('Training Efficiency') }}</flux:heading>
                <x-flux::icon.bolt class="w-5 h-5 text-slate-400" />
            </div>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Efficiency Rate') }}</span>
                    <span class="text-lg font-bold text-slate-900 dark:text-white">{{ $trainingEfficiency }}%</span>
                </div>
                <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                    <div class="bg-orange-500 h-2 rounded-full transition-all duration-500" style="width: {{ min($trainingEfficiency, 100) }}%"></div>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Total Hours') }}</span>
                    <span class="text-lg font-bold text-slate-900 dark:text-white">{{ number_format($totalHours) }}</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Programs Completed') }}</span>
                    <span class="text-lg font-bold text-slate-900 dark:text-white">{{ $completedPrograms }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Performance Trends Chart -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <flux:heading size="lg">{{ __('Evaluation Trends') }}</flux:heading>
                <x-flux::icon.chart-bar class="w-5 h-5 text-slate-400" />
            </div>
            
            <div class="h-64">
                <canvas id="evaluationTrendsChart"></canvas>
            </div>
        </div>

        <!-- Status Distribution Chart -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <flux:heading size="lg">{{ __('Status Distribution') }}</flux:heading>
                <x-flux::icon.chart-pie class="w-5 h-5 text-slate-400" />
            </div>
            
            <div class="h-64">
                <canvas id="statusDistributionChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Performers -->
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
        <div class="flex items-center justify-between mb-6">
            <flux:heading size="lg">{{ __('Top Performing Trainees') }}</flux:heading>
            <div class="flex items-center gap-2">
                <x-flux::icon.star class="w-5 h-5 text-slate-400" />
                <a href="{{ route('evaluations.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
                    {{ __('View All') }} →
                </a>
            </div>
        </div>
        
        <div class="space-y-4">
            @forelse($topPerformers as $index => $evaluation)
                <a href="{{ route('evaluations.show', $evaluation->id) }}" class="block">
                    <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-700 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-600 transition-colors cursor-pointer group">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <p class="font-medium text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400">
                                    {{ $evaluation->maid?->full_name ?? 'Unknown Trainee' }}
                                </p>
                                <p class="text-sm text-slate-500 dark:text-slate-400">{{ $evaluation->evaluation_date?->format('M d, Y') ?? 'No date' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex">
                                @for($i = 1; $i <= 5; $i++)
                                    <x-flux::icon.star class="w-4 h-4 {{ $i <= $evaluation->overall_rating ? 'text-[#F5B301]' : 'text-slate-300' }}" />
                                @endfor
                            </div>
                            <span class="text-lg font-bold text-slate-900 dark:text-white">{{ number_format($evaluation->overall_rating, 1) }}</span>
                            <x-flux::icon.arrow-right class="w-4 h-4 text-slate-400 group-hover:text-blue-500" />
                        </div>
                    </div>
                </a>
            @empty
                <div class="text-center py-6 text-slate-500 dark:text-slate-400">
                    <x-flux::icon.star class="w-8 h-8 mx-auto mb-2 text-slate-300" />
                    <p>{{ __('No evaluations found') }}</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Evaluations -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <flux:heading size="lg">{{ __('Recent Evaluations') }}</flux:heading>
                <div class="flex items-center gap-2">
                    <x-flux::icon.clock class="w-5 h-5 text-slate-400" />
                    <a href="{{ route('evaluations.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
                        {{ __('View All') }} →
                    </a>
                </div>
            </div>
            
            <div class="space-y-3">
                @forelse($recentEvaluations as $evaluation)
                    <a href="{{ route('evaluations.show', $evaluation->id) }}" class="block">
                        <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-700 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-600 transition-colors cursor-pointer group">
                            <div>
                                <p class="font-medium text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400">
                                    {{ $evaluation->maid?->full_name ?? 'Unknown Trainee' }}
                                </p>
                                <p class="text-sm text-slate-500 dark:text-slate-400">{{ $evaluation->evaluation_date?->format('M d, Y') ?? 'No date' }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <flux:badge :color="match($evaluation->status) {
                                    'approved' => 'green',
                                    'pending' => 'yellow',
                                    'rejected' => 'red',
                                    default => 'gray'
                                }">{{ ucfirst($evaluation->status) }}</flux:badge>
                                <span class="text-sm font-bold text-slate-900 dark:text-white">{{ number_format($evaluation->overall_rating, 1) }}</span>
                                <x-flux::icon.arrow-right class="w-4 h-4 text-slate-400 group-hover:text-blue-500" />
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-6 text-slate-500 dark:text-slate-400">
                        <x-flux::icon.clipboard-document-check class="w-8 h-8 mx-auto mb-2 text-slate-300" />
                        <p>{{ __('No recent evaluations') }}</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Training Programs -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <flux:heading size="lg">{{ __('Recent Training Programs') }}</flux:heading>
                <div class="flex items-center gap-2">
                    <x-flux::icon.academic-cap class="w-5 h-5 text-slate-400" />
                    <a href="{{ route('programs.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
                        {{ __('View All') }} →
                    </a>
                </div>
            </div>
            
            <div class="space-y-3">
                @forelse($recentPrograms as $program)
                    <a href="{{ route('programs.show', $program->id) }}" class="block">
                        <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-700 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-600 transition-colors cursor-pointer group">
                            <div>
                                <p class="font-medium text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400">
                                    {{ $program->maid?->full_name ?? 'Unknown Trainee' }}
                                </p>
                                <p class="text-sm text-slate-500 dark:text-slate-400">{{ $program->start_date?->format('M d, Y') ?? 'No date' }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <flux:badge :color="match($program->status) {
                                    'completed' => 'green',
                                    'in-progress' => 'blue',
                                    'scheduled' => 'yellow',
                                    'cancelled' => 'red',
                                    default => 'gray'
                                }">{{ ucfirst($program->status) }}</flux:badge>
                                <x-flux::icon.arrow-right class="w-4 h-4 text-slate-400 group-hover:text-blue-500" />
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-6 text-slate-500 dark:text-slate-400">
                        <x-flux::icon.academic-cap class="w-8 h-8 mx-auto mb-2 text-slate-300" />
                        <p>{{ __('No recent training programs') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Chart.js Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Chart === 'undefined') {
            console.error('Chart.js not loaded!');
            return;
        }
        
        console.log('Chart.js loaded successfully, version:', Chart.version);

        // Royal Maids Brand Colors
        const brandColors = {
            primary: '#3B0A45',
            secondary: '#512B58',
            accent: '#F5B301',
            success: '#4CAF50',
            warning: '#FFC107',
            error: '#E53935',
            info: '#64B5F6',
            textPrimary: '#FFFFFF',
            textSecondary: '#D1C4E9'
        };

        // Evaluation Trends Chart
        const trendsCanvas = document.getElementById('evaluationTrendsChart');
        if (trendsCanvas) {
            const trendsCtx = trendsCanvas.getContext('2d');
            new Chart(trendsCtx, {
                type: 'line',
                data: {
                    labels: @json($chartData['months']),
                    datasets: [{
                        label: 'Evaluations',
                        data: @json($chartData['evaluations']),
                        borderColor: brandColors.accent,
                        backgroundColor: brandColors.accent + '20',
                        tension: 0.4,
                        yAxisID: 'y'
                    }, {
                        label: 'Average Rating',
                        data: @json($chartData['ratings']),
                        borderColor: brandColors.info,
                        backgroundColor: brandColors.info + '20',
                        tension: 0.4,
                        yAxisID: 'y1'
                    }, {
                        label: 'Training Programs',
                        data: @json($chartData['programs']),
                        borderColor: brandColors.success,
                        backgroundColor: brandColors.success + '20',
                        tension: 0.4,
                        yAxisID: 'y'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                color: brandColors.textPrimary
                            }
                        },
                        tooltip: {
                            backgroundColor: brandColors.secondary,
                            titleColor: brandColors.textPrimary,
                            bodyColor: brandColors.textSecondary,
                            borderColor: brandColors.accent,
                            borderWidth: 1
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
                            type: 'linear',
                            display: true,
                            position: 'left',
                            ticks: {
                                color: brandColors.textSecondary
                            },
                            grid: {
                                color: brandColors.accent + '20'
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
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

        // Status Distribution Chart
        const statusCanvas = document.getElementById('statusDistributionChart');
        if (statusCanvas) {
            const statusCtx = statusCanvas.getContext('2d');
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'Approved', 'Rejected'],
                    datasets: [{
                        data: [
                            {{ $evaluationStatusDistribution['pending'] }},
                            {{ $evaluationStatusDistribution['approved'] }},
                            {{ $evaluationStatusDistribution['rejected'] }}
                        ],
                        backgroundColor: [
                            brandColors.warning,
                            brandColors.success,
                            brandColors.error
                        ],
                        borderColor: brandColors.textPrimary,
                        borderWidth: 2,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: brandColors.textPrimary
                            }
                        },
                        tooltip: {
                            backgroundColor: brandColors.secondary,
                            titleColor: brandColors.textPrimary,
                            bodyColor: brandColors.textSecondary,
                            borderColor: brandColors.accent,
                            borderWidth: 1,
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                                    return `${context.label}: ${context.parsed} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }
    });

    // Listen for Livewire updates
    document.addEventListener('livewire:navigated', function() {
        setTimeout(() => {
            location.reload();
        }, 100);
    });
    
    document.addEventListener('livewire:updated', function() {
        setTimeout(() => {
            location.reload();
        }, 100);
    });
    </script>

    <!-- Export Modal -->
    <flux:modal name="export-modal" wire:model="showExportModal" class="max-w-md">
        <div class="space-y-4">
            <flux:heading size="lg" class="text-blue-600 dark:text-blue-400">
                <x-flux::icon.arrow-down-tray class="w-6 h-6 mr-2" />
                {{ __('Export Trainer Report') }}
            </flux:heading>
            
            <p class="text-slate-700 dark:text-slate-300">
                Choose the format for your trainer performance report export.
            </p>
            
            <div class="space-y-3">
                <flux:radio wire:model="exportFormat" value="pdf" class="flex items-center gap-3">
                    <x-flux::icon.document-text class="w-5 h-5 text-red-500" />
                    <div>
                        <div class="font-medium">PDF Report</div>
                        <div class="text-sm text-slate-500">Professional formatted report with charts and tables</div>
                    </div>
                </flux:radio>
                
                <flux:radio wire:model="exportFormat" value="csv" class="flex items-center gap-3">
                    <x-flux::icon.table-cells class="w-5 h-5 text-green-500" />
                    <div>
                        <div class="font-medium">CSV Data</div>
                        <div class="text-sm text-slate-500">Raw data for spreadsheet analysis</div>
                    </div>
                </flux:radio>
            </div>
            
            <div class="flex items-center justify-end gap-3">
                <flux:button variant="outline" wire:click="$set('showExportModal', false)">
                    {{ __('Cancel') }}
                </flux:button>
                <flux:button variant="primary" wire:click="generateExport">
                    {{ __('Generate Export') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
