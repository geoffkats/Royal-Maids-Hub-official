<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <flux:heading size="xl">{{ __('KPI Dashboard') }}</flux:heading>
            <flux:subheading class="mt-1">{{ __('Comprehensive analytics and performance metrics') }}</flux:subheading>
        </div>
    </div>

    <flux:separator variant="subtle" />

    <!-- Filter Section -->
    <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
        <div class="flex items-center gap-2 mb-4">
            <flux:icon.funnel class="size-5 text-[#F5B301]" />
            <h3 class="text-lg font-semibold text-white">{{ __('Dashboard Filters & Period Selection') }}</h3>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
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

            <!-- Metric Type Filter -->
            <div>
                <flux:select wire:model.live="metricType" :label="__('Metric Focus')" class="filter-select">
                    <option value="all">{{ __('All Metrics') }}</option>
                    <option value="bookings">{{ __('Bookings Focus') }}</option>
                    <option value="revenue">{{ __('Revenue Focus') }}</option>
                    <option value="training">{{ __('Training Focus') }}</option>
                    <option value="deployments">{{ __('Deployments Focus') }}</option>
                </flux:select>
            </div>

            <!-- Comparison Period -->
            <div>
                <flux:select wire:model.live="comparisonPeriod" :label="__('Compare With')" class="filter-select">
                    <option value="previous">{{ __('Previous Period') }}</option>
                    <option value="last_year">{{ __('Last Year') }}</option>
                    <option value="none">{{ __('No Comparison') }}</option>
                </flux:select>
            </div>
        </div>

        <!-- Filter Actions -->
        <div class="flex items-center justify-between mt-6 pt-4 border-t border-[#F5B301]/20">
            <div class="flex items-center gap-2 text-sm text-[#D1C4E9]">
                <flux:icon.information-circle class="size-4" />
                <span>{{ __('Showing KPIs for') }} {{ $selectedPeriod }}</span>
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
                    wire:click="togglePackageDetails" 
                    variant="outline" 
                    size="sm"
                    icon="chart-pie"
                >
                    {{ $showPackageDetails ? __('Hide Details') : __('Show Details') }}
                </flux:button>
                
                <flux:button 
                    wire:click="exportReport" 
                    variant="primary" 
                    size="sm"
                    icon="arrow-down-tray"
                >
                    {{ __('Export') }}
                </flux:button>
            </div>
        </div>
    </div>

    <!-- Key Metrics Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Bookings -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-6 border border-blue-200 dark:border-blue-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-600 dark:text-blue-400">{{ __('Total Bookings') }}</p>
                    <p class="text-3xl font-bold text-blue-900 dark:text-blue-100">{{ number_format($totalBookings) }}</p>
                    <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                        <span class="inline-flex items-center">
                            <x-flux::icon.arrow-trending-up class="w-4 h-4 mr-1" />
                            {{ $bookingGrowthRate }}% {{ __('vs last period') }}
                        </span>
                    </p>
                </div>
                <div class="p-3 bg-blue-500 rounded-lg">
                    <x-flux::icon.calendar class="w-6 h-6 text-white" />
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl p-6 border border-green-200 dark:border-green-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-green-600 dark:text-green-400">{{ __('Total Revenue') }}</p>
                    <p class="text-3xl font-bold text-green-900 dark:text-green-100">{{ number_format($totalRevenue) }} UGX</p>
                    <p class="text-xs text-green-600 dark:text-green-400 mt-1">
                        <span class="inline-flex items-center">
                            <x-flux::icon.arrow-trending-up class="w-4 h-4 mr-1" />
                            {{ $revenueGrowthRate }}% {{ __('growth') }}
                        </span>
                    </p>
                </div>
                <div class="p-3 bg-green-500 rounded-lg">
                    <x-flux::icon.currency-dollar class="w-6 h-6 text-white" />
                </div>
            </div>
        </div>

        <!-- Training Completion -->
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-xl p-6 border border-purple-200 dark:border-purple-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-purple-600 dark:text-purple-400">{{ __('Training Completion') }}</p>
                    <p class="text-3xl font-bold text-purple-900 dark:text-purple-100">{{ $trainingCompletionRate }}%</p>
                    <p class="text-xs text-purple-600 dark:text-purple-400 mt-1">
                        <span class="inline-flex items-center">
                            <x-flux::icon.academic-cap class="w-4 h-4 mr-1" />
                            {{ $totalEvaluations }} {{ __('evaluations') }}
                        </span>
                    </p>
                </div>
                <div class="p-3 bg-purple-500 rounded-lg">
                    <x-flux::icon.academic-cap class="w-6 h-6 text-white" />
                </div>
            </div>
        </div>

        <!-- Maid Deployment -->
        <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 rounded-xl p-6 border border-orange-200 dark:border-orange-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-orange-600 dark:text-orange-400">{{ __('Maid Deployment') }}</p>
                    <p class="text-3xl font-bold text-orange-900 dark:text-orange-100">{{ $maidDeploymentRate }}%</p>
                    <p class="text-xs text-orange-600 dark:text-orange-400 mt-1">
                        <span class="inline-flex items-center">
                            <x-flux::icon.user-group class="w-4 h-4 mr-1" />
                            {{ $totalMaids }} {{ __('total maids') }}
                        </span>
                    </p>
                </div>
                <div class="p-3 bg-orange-500 rounded-lg">
                    <x-flux::icon.user-group class="w-6 h-6 text-white" />
                </div>
            </div>
        </div>
    </div>

    <!-- Package Performance Metrics -->
    @if($showPackageDetails)
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
        <div class="flex items-center justify-between mb-6">
            <flux:heading size="lg">{{ __('Package Performance Analysis') }}</flux:heading>
            <x-flux::icon.chart-pie class="w-5 h-5 text-slate-400" />
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Silver Package -->
            <div class="bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-700 dark:to-slate-600 rounded-xl p-6 border border-slate-200 dark:border-slate-600">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-slate-500 rounded-lg flex items-center justify-center">
                            <x-flux::icon.check-circle class="w-6 h-6 text-white" />
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 dark:text-white">Silver Package</h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400">Standard Tier</p>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $silverBookings ?? 0 }}</div>
                            <div class="text-xs text-slate-600 dark:text-slate-400">Bookings</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($silverRevenue ?? 0) }}</div>
                            <div class="text-xs text-slate-600 dark:text-slate-400">Revenue (UGX)</div>
                        </div>
                </div>
            </div>

            <!-- Gold Package -->
            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 rounded-xl p-6 border border-yellow-200 dark:border-yellow-700">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center">
                            <x-flux::icon.star class="w-6 h-6 text-white" />
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 dark:text-white">Gold Package</h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400">Premium Tier</p>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $goldBookings ?? 0 }}</div>
                            <div class="text-xs text-slate-600 dark:text-slate-400">Bookings</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($goldRevenue ?? 0) }}</div>
                            <div class="text-xs text-slate-600 dark:text-slate-400">Revenue (UGX)</div>
                        </div>
                </div>
            </div>

            <!-- Platinum Package -->
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-xl p-6 border border-purple-200 dark:border-purple-700">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                            <x-flux::icon.bolt class="w-6 h-6 text-white" />
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 dark:text-white">Platinum Package</h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400">Elite Tier</p>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $platinumBookings ?? 0 }}</div>
                            <div class="text-xs text-slate-600 dark:text-slate-400">Bookings</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($platinumRevenue ?? 0) }}</div>
                            <div class="text-xs text-slate-600 dark:text-slate-400">Revenue (UGX)</div>
                        </div>
                </div>
            </div>
        </div>

        <!-- Package Distribution Chart -->
        <div class="bg-slate-50 dark:bg-slate-700 rounded-lg p-6">
            <h4 class="font-semibold text-slate-900 dark:text-white mb-4">{{ __('Package Distribution') }}</h4>
            <div class="h-64">
                <canvas id="packageChart"></canvas>
            </div>
        </div>
    </div>
    @endif

    <!-- Detailed KPI Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Booking Performance -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <flux:heading size="lg">{{ __('Booking Performance') }}</flux:heading>
                <x-flux::icon.calendar class="w-5 h-5 text-slate-400" />
            </div>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Completion Rate') }}</span>
                    <span class="text-lg font-bold text-slate-900 dark:text-white">{{ $bookingCompletionRate }}%</span>
                </div>
                <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                    <div class="bg-blue-500 h-2 rounded-full transition-all duration-500" style="width: {{ min($bookingCompletionRate, 100) }}%"></div>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Average Value') }}</span>
                    <span class="text-lg font-bold text-slate-900 dark:text-white">{{ number_format($averageBookingValue) }} UGX</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Client Satisfaction') }}</span>
                    <span class="text-lg font-bold text-slate-900 dark:text-white">{{ $clientSatisfactionScore }}%</span>
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
                    <span class="text-lg font-bold text-slate-900 dark:text-white">{{ $trainingCompletionRate }}%</span>
                </div>
                <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                    <div class="bg-purple-500 h-2 rounded-full transition-all duration-500" style="width: {{ min($trainingCompletionRate, 100) }}%"></div>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Average KPI Score') }}</span>
                    <span class="text-lg font-bold text-slate-900 dark:text-white">{{ $averageKpiScore }}%</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Trainer Efficiency') }}</span>
                    <span class="text-lg font-bold text-slate-900 dark:text-white">{{ $trainerEfficiency }}%</span>
                </div>
            </div>
        </div>

        <!-- Maid Performance -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <flux:heading size="lg">{{ __('Maid Performance') }}</flux:heading>
                <x-flux::icon.user-group class="w-5 h-5 text-slate-400" />
            </div>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Deployment Rate') }}</span>
                    <span class="text-lg font-bold text-slate-900 dark:text-white">{{ $maidDeploymentRate }}%</span>
                </div>
                <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                    <div class="bg-orange-500 h-2 rounded-full transition-all duration-500" style="width: {{ min($maidDeploymentRate, 100) }}%"></div>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Retention Rate') }}</span>
                    <span class="text-lg font-bold text-slate-900 dark:text-white">{{ $maidRetentionRate }}%</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Average Rating') }}</span>
                    <span class="text-lg font-bold text-slate-900 dark:text-white">{{ $averageMaidRating }}/5</span>
                </div>
            </div>
        </div>

        <!-- Financial Performance -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <flux:heading size="lg">{{ __('Financial Performance') }}</flux:heading>
                <x-flux::icon.currency-dollar class="w-5 h-5 text-slate-400" />
            </div>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Total Revenue') }}</span>
                    <span class="text-lg font-bold text-slate-900 dark:text-white">{{ number_format($totalRevenue) }} UGX</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Avg Revenue/Booking') }}</span>
                    <span class="text-lg font-bold text-slate-900 dark:text-white">{{ number_format($averageRevenuePerBooking) }} UGX</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ __('Profit Margin') }}</span>
                    <span class="text-lg font-bold text-slate-900 dark:text-white">{{ $profitMargin }}%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Performance Trends Chart -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <flux:heading size="lg">{{ __('Performance Trends') }}</flux:heading>
                <x-flux::icon.chart-bar class="w-5 h-5 text-slate-400" />
            </div>
            
            <div class="h-64">
                <canvas id="performanceChart"></canvas>
            </div>
        </div>

        <!-- KPI Distribution -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <flux:heading size="lg">{{ __('KPI Distribution') }}</flux:heading>
                <x-flux::icon.chart-pie class="w-5 h-5 text-slate-400" />
            </div>
            
            <div class="h-64">
                <canvas id="kpiChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Performers -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Top Trainers -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <flux:heading size="lg">{{ __('Top Trainers') }}</flux:heading>
                <div class="flex items-center gap-2">
                    <x-flux::icon.academic-cap class="w-5 h-5 text-slate-400" />
                    <a href="{{ route('trainers.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
                        {{ __('View All') }} →
                    </a>
                </div>
            </div>
            
            <div class="space-y-4">
                @forelse($topPerformers['trainers'] as $index => $trainer)
                    <a href="{{ route('trainers.show', $trainer->id) }}" class="block">
                        <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-700 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-600 transition-colors cursor-pointer group">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <p class="font-medium text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400">{{ $trainer->name }}</p>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ $trainer->evaluations_count }} evaluations</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <flux:badge color="purple">{{ $trainer->evaluations_count }}</flux:badge>
                                <x-flux::icon.arrow-right class="w-4 h-4 text-slate-400 group-hover:text-blue-500" />
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-6 text-slate-500 dark:text-slate-400">
                        <x-flux::icon.academic-cap class="w-8 h-8 mx-auto mb-2 text-slate-300" />
                        <p>{{ __('No trainers found') }}</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Top Maids -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <flux:heading size="lg">{{ __('Top Maids') }}</flux:heading>
                <div class="flex items-center gap-2">
                    <x-flux::icon.user-group class="w-5 h-5 text-slate-400" />
                    <a href="{{ route('maids.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
                        {{ __('View All') }} →
                    </a>
                </div>
            </div>
            
            <div class="space-y-4">
                @forelse($topPerformers['maids'] as $index => $maid)
                    <a href="{{ route('maids.show', $maid->id) }}" class="block">
                        <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-700 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-600 transition-colors cursor-pointer group">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <p class="font-medium text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400">{{ $maid->full_name }}</p>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ ucfirst($maid->status) }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <flux:badge :color="match($maid->status) {
                                    'deployed' => 'green',
                                    'available' => 'blue',
                                    'in-training' => 'yellow',
                                    default => 'gray'
                                }">{{ ucfirst($maid->status) }}</flux:badge>
                                <x-flux::icon.arrow-right class="w-4 h-4 text-slate-400 group-hover:text-blue-500" />
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-6 text-slate-500 dark:text-slate-400">
                        <x-flux::icon.user-group class="w-8 h-8 mx-auto mb-2 text-slate-300" />
                        <p>{{ __('No maids found') }}</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Top Clients -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <flux:heading size="lg">{{ __('Top Clients') }}</flux:heading>
                <div class="flex items-center gap-2">
                    <x-flux::icon.users class="w-5 h-5 text-slate-400" />
                    <a href="{{ route('clients.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
                        {{ __('View All') }} →
                    </a>
                </div>
            </div>
            
            <div class="space-y-4">
                @forelse($topPerformers['clients'] as $index => $client)
                    <a href="{{ route('clients.show', $client->id) }}" class="block">
                        <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-700 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-600 transition-colors cursor-pointer group">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <p class="font-medium text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400">{{ $client->contact_person }}</p>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ $client->bookings_count }} bookings</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <flux:badge color="green">{{ $client->bookings_count }}</flux:badge>
                                <x-flux::icon.arrow-right class="w-4 h-4 text-slate-400 group-hover:text-blue-500" />
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-6 text-slate-500 dark:text-slate-400">
                        <x-flux::icon.users class="w-8 h-8 mx-auto mb-2 text-slate-300" />
                        <p>{{ __('No clients found') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
        <div class="flex items-center justify-between mb-6">
            <flux:heading size="lg">{{ __('Recent Activity') }}</flux:heading>
            <x-flux::icon.clock class="w-5 h-5 text-slate-400" />
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Recent Bookings -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-semibold text-slate-900 dark:text-white">{{ __('Recent Bookings') }}</h4>
                    <a href="{{ route('bookings.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
                        {{ __('View All') }} →
                    </a>
                </div>
                <div class="space-y-3">
                    @forelse($recentActivity['bookings'] as $booking)
                        <a href="{{ route('bookings.show', $booking->id) }}" class="block">
                            <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-700 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-600 transition-colors cursor-pointer group">
                                <div>
                                    <p class="font-medium text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400">#{{ $booking->id }}</p>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ $booking->client?->contact_person ?? 'Unknown Client' }}</p>
                                    <p class="text-xs text-slate-400 dark:text-slate-500">{{ $booking->created_at?->format('M d, Y') ?? 'No date' }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <flux:badge :color="match($booking->status) {
                                        'completed' => 'green',
                                        'active' => 'blue',
                                        'confirmed' => 'yellow',
                                        'pending' => 'gray',
                                        default => 'gray'
                                    }">{{ ucfirst($booking->status) }}</flux:badge>
                                    <x-flux::icon.arrow-right class="w-4 h-4 text-slate-400 group-hover:text-blue-500" />
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-6 text-slate-500 dark:text-slate-400">
                            <x-flux::icon.document-text class="w-8 h-8 mx-auto mb-2 text-slate-300" />
                            <p>{{ __('No recent bookings') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Evaluations -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-semibold text-slate-900 dark:text-white">{{ __('Recent Evaluations') }}</h4>
                    <a href="{{ route('evaluations.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
                        {{ __('View All') }} →
                    </a>
                </div>
                <div class="space-y-3">
                    @forelse($recentActivity['evaluations'] as $evaluation)
                        <a href="{{ route('evaluations.show', $evaluation->id) }}" class="block">
                            <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-700 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-600 transition-colors cursor-pointer group">
                                <div>
                                    <p class="font-medium text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400">{{ $evaluation->maid?->full_name ?? 'Unknown Maid' }}</p>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ $evaluation->trainer?->name ?? 'Unknown Trainer' }}</p>
                                    <p class="text-xs text-slate-400 dark:text-slate-500">{{ $evaluation->created_at?->format('M d, Y') ?? 'No date' }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <flux:badge :color="match($evaluation->status) {
                                        'approved' => 'green',
                                        'pending' => 'yellow',
                                        default => 'gray'
                                    }">{{ ucfirst($evaluation->status) }}</flux:badge>
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

            <!-- Recent Deployments -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-semibold text-slate-900 dark:text-white">{{ __('Recent Deployments') }}</h4>
                    <a href="{{ route('deployments.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
                        {{ __('View All') }} →
                    </a>
                </div>
                <div class="space-y-3">
                    @forelse($recentActivity['deployments'] as $deployment)
                        <a href="{{ route('deployments.show', $deployment->id) }}" class="block">
                            <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-700 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-600 transition-colors cursor-pointer group">
                                <div>
                                    <p class="font-medium text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400">{{ $deployment->maid?->full_name ?? 'Unknown Maid' }}</p>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ $deployment->client?->contact_person ?? 'Unknown Client' }}</p>
                                    <p class="text-xs text-slate-400 dark:text-slate-500">{{ $deployment->created_at?->format('M d, Y') ?? 'No date' }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <flux:badge color="green">{{ __('Deployed') }}</flux:badge>
                                    <x-flux::icon.arrow-right class="w-4 h-4 text-slate-400 group-hover:text-blue-500" />
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-6 text-slate-500 dark:text-slate-400">
                            <x-flux::icon.cube class="w-8 h-8 mx-auto mb-2 text-slate-300" />
                            <p>{{ __('No recent deployments') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check if Chart.js is loaded
        if (typeof Chart === 'undefined') {
            console.error('Chart.js not loaded!');
            return;
        }
        
        console.log('Chart.js loaded successfully, version:', Chart.version);

        // Royal Maids Brand Colors
        const brandColors = {
            primary: '#3B0A45',        // Royal Purple
            secondary: '#512B58',      // Deep Violet
            accent: '#F5B301',         // Gold
            success: '#4CAF50',        // Emerald Green
            warning: '#FFC107',        // Amber
            error: '#E53935',          // Soft Red
            info: '#64B5F6',           // Sky Blue
            silver: '#A8A9AD',         // Silver
            goldPackage: '#FFD700',    // Gold Package
            platinum: '#B9A0DC',       // Platinum
            textPrimary: '#FFFFFF',    // White
            textSecondary: '#D1C4E9'   // Light Gray
        };

        // Performance Trends Chart
        const performanceCanvas = document.getElementById('performanceChart');
        if (!performanceCanvas) {
            console.error('Performance chart canvas not found!');
            return;
        }
        const performanceCtx = performanceCanvas.getContext('2d');
        new Chart(performanceCtx, {
            type: 'line',
            data: {
                labels: @json($chartData['months']),
                datasets: [{
                    label: 'Bookings',
                    data: @json($chartData['bookings']),
                    borderColor: brandColors.accent,
                    backgroundColor: brandColors.accent + '20',
                    tension: 0.4,
                    pointBackgroundColor: brandColors.accent,
                    pointBorderColor: brandColors.textPrimary,
                    pointBorderWidth: 2
                }, {
                    label: 'Revenue (000s)',
                    data: @json(array_map(function($val) { return $val / 1000; }, $chartData['revenue'])),
                    borderColor: brandColors.success,
                    backgroundColor: brandColors.success + '20',
                    tension: 0.4,
                    pointBackgroundColor: brandColors.success,
                    pointBorderColor: brandColors.textPrimary,
                    pointBorderWidth: 2
                }, {
                    label: 'Training',
                    data: @json($chartData['training']),
                    borderColor: brandColors.platinum,
                    backgroundColor: brandColors.platinum + '20',
                    tension: 0.4,
                    pointBackgroundColor: brandColors.platinum,
                    pointBorderColor: brandColors.textPrimary,
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            color: brandColors.textPrimary,
                            font: {
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
            }
        });

        // KPI Distribution Chart
        const kpiCanvas = document.getElementById('kpiChart');
        if (!kpiCanvas) {
            console.error('KPI chart canvas not found!');
            return;
        }
        const kpiCtx = kpiCanvas.getContext('2d');
        new Chart(kpiCtx, {
            type: 'doughnut',
            data: {
                labels: ['Bookings', 'Training', 'Deployments', 'Revenue'],
                datasets: [{
                    data: [{{ $totalBookings }}, {{ $totalEvaluations }}, {{ $totalDeployments }}, {{ round($totalRevenue / 100000) }}],
                    backgroundColor: [
                        brandColors.accent,        // Gold for Bookings
                        brandColors.platinum,     // Platinum for Training
                        brandColors.warning,      // Amber for Deployments
                        brandColors.success       // Green for Revenue
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
                            color: brandColors.textPrimary,
                            font: {
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
                        cornerRadius: 8,
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

        // Package Distribution Chart - Initialize when package details are shown
        function initializePackageChart() {
            const packageCanvas = document.getElementById('packageChart');
            console.log('Initializing package chart, canvas found:', !!packageCanvas);
            if (packageCanvas) {
                const packageCtx = packageCanvas.getContext('2d');
                console.log('Package chart data:', [{{ $silverBookings ?? 0 }}, {{ $goldBookings ?? 0 }}, {{ $platinumBookings ?? 0 }}]);
                new Chart(packageCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Silver', 'Gold', 'Platinum'],
                        datasets: [{
                            data: [{{ $silverBookings ?? 0 }}, {{ $goldBookings ?? 0 }}, {{ $platinumBookings ?? 0 }}],
                            backgroundColor: [
                                brandColors.silver,        // Silver
                                brandColors.accent,       // Gold
                                brandColors.platinum      // Platinum
                            ],
                            borderWidth: 2,
                            borderColor: brandColors.textPrimary,
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
                                    color: brandColors.textPrimary,
                                    font: {
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
                                cornerRadius: 8,
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
        }
        
        // Initialize chart if package details are shown
        @if($showPackageDetails)
        initializePackageChart();
        @endif
    });

    // Handle file downloads
    window.addEventListener('download-file', event => {
        const { content, filename, mimeType } = event.detail;
        
        // Create blob from base64 content
        const byteCharacters = atob(content);
        const byteNumbers = new Array(byteCharacters.length);
        for (let i = 0; i < byteCharacters.length; i++) {
            byteNumbers[i] = byteCharacters.charCodeAt(i);
        }
        const byteArray = new Uint8Array(byteNumbers);
        const blob = new Blob([byteArray], { type: mimeType });
        
        // Create download link
        const url = window.URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = filename;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        window.URL.revokeObjectURL(url);
    });

    // Listen for Livewire updates to reinitialize charts
    document.addEventListener('livewire:navigated', function() {
        setTimeout(initializePackageChart, 100);
    });
    
    // Listen for Livewire updates
    document.addEventListener('livewire:updated', function() {
        setTimeout(initializePackageChart, 100);
    });
    </script>

    <!-- Export Modal -->
    <flux:modal name="export-modal" wire:model="showExportModal" class="max-w-md">
        <div class="space-y-4">
            <flux:heading size="lg" class="text-blue-600 dark:text-blue-400">
                <x-flux::icon.arrow-down-tray class="w-6 h-6 mr-2" />
                {{ __('Export KPI Report') }}
            </flux:heading>
            
            <p class="text-slate-700 dark:text-slate-300">
                Choose the format for your KPI dashboard report export.
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

