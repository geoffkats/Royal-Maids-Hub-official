<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">{{ __('Sales Performance Report') }}</flux:heading>
            <flux:subheading class="mt-1">
                {{ __('Track win rates, revenue, deal velocity, and sales rep performance') }}
            </flux:subheading>
        </div>
        <div class="flex gap-3">
            <flux:button as="a" href="{{ route('crm.opportunities.index') }}" variant="ghost" icon="arrow-left" wire:navigate>
                {{ __('Back to Pipeline') }}
            </flux:button>
        </div>
    </div>

    <flux:separator variant="subtle" />

    {{-- Filters --}}
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        <flux:select wire:model.live="dateRange" label="{{ __('Date Range') }}">
            <option value="7">{{ __('Last 7 Days') }}</option>
            <option value="30">{{ __('Last 30 Days') }}</option>
            <option value="60">{{ __('Last 60 Days') }}</option>
            <option value="90">{{ __('Last 90 Days') }}</option>
            <option value="180">{{ __('Last 6 Months') }}</option>
            <option value="365">{{ __('Last Year') }}</option>
        </flux:select>

        <flux:select wire:model.live="selectedOwner" label="{{ __('Sales Rep') }}">
            <option value="all">{{ __('All Sales Reps') }}</option>
            @foreach ($owners as $owner)
                <option value="{{ $owner->id }}">{{ $owner->name }}</option>
            @endforeach
        </flux:select>
    </div>

    {{-- Key Performance Metrics --}}
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-[#D1C4E9]">{{ __('Total Revenue') }}</p>
                    <p class="mt-2 text-3xl font-bold text-white">@currency($totalRevenue)</p>
                    <p class="mt-1 text-xs text-[#D1C4E9]">{{ __('in selected period') }}</p>
                </div>
                <div class="rounded-full bg-[#4CAF50] p-3">
                    <flux:icon.currency-dollar class="size-8 text-white" />
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-[#D1C4E9]">{{ __('Avg Deal Size') }}</p>
                    <p class="mt-2 text-3xl font-bold text-white">@currency($avgDealSize)</p>
                    <p class="mt-1 text-xs text-[#D1C4E9]">{{ __('per won deal') }}</p>
                </div>
                <div class="rounded-full bg-[#64B5F6] p-3">
                    <flux:icon.calculator class="size-8 text-white" />
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-[#D1C4E9]">{{ __('Win Rate') }}</p>
                    <p class="mt-2 text-3xl font-bold text-white">{{ $winRate }}%</p>
                    <p class="mt-1 text-xs text-[#D1C4E9]">{{ __('of closed deals') }}</p>
                </div>
                <div class="rounded-full bg-[#4CAF50] p-3">
                    <flux:icon.trophy class="size-8 text-white" />
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-[#D1C4E9]">{{ __('Sales Velocity') }}</p>
                    <p class="mt-2 text-3xl font-bold text-white">{{ number_format($salesVelocity, 1) }}</p>
                    <p class="mt-1 text-xs text-[#D1C4E9]">{{ __('days to close') }}</p>
                </div>
                <div class="rounded-full bg-[#FFC107] p-3">
                    <flux:icon.clock class="size-8 text-white" />
                </div>
            </div>
        </div>

    </div>

    {{-- Win/Loss Analysis --}}
    <div class="grid gap-6 lg:grid-cols-2">
        
        {{-- Win Rate Breakdown --}}
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <h3 class="mb-6 flex items-center gap-2 text-lg font-semibold text-white">
                <flux:icon.chart-pie class="size-5 text-[#F5B301]" />
                {{ __('Win/Loss Breakdown') }}
            </h3>
            
            <div class="space-y-4">
                {{-- Win Rate --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-white">{{ __('Won Deals') }}</span>
                        <flux:badge color="green" size="sm">{{ $winRate }}%</flux:badge>
                    </div>
                    <div class="h-8 rounded-lg bg-[#3B0A45] overflow-hidden">
                        <div 
                            class="h-full bg-gradient-to-r from-[#4CAF50] to-[#388E3C] flex items-center justify-center text-white text-sm font-semibold"
                            style="width: {{ $winRate }}%"
                        >
                            @if($winRate > 15)
                                {{ $winRate }}%
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Loss Rate --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-white">{{ __('Lost Deals') }}</span>
                        <flux:badge color="red" size="sm">{{ $lossRate }}%</flux:badge>
                    </div>
                    <div class="h-8 rounded-lg bg-[#3B0A45] overflow-hidden">
                        <div 
                            class="h-full bg-gradient-to-r from-[#EF5350] to-[#D32F2F] flex items-center justify-center text-white text-sm font-semibold"
                            style="width: {{ $lossRate }}%"
                        >
                            @if($lossRate > 15)
                                {{ $lossRate }}%
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Benchmark Indicator --}}
                <div class="mt-6 pt-4 border-t border-[#F5B301]/20">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-[#D1C4E9]">{{ __('Industry Benchmark') }}</span>
                        <span class="text-xs text-[#D1C4E9]">{{ __('30-40% win rate') }}</span>
                    </div>
                    <p class="mt-2 text-xs text-[#D1C4E9]">
                        @if($winRate >= 40)
                            <span class="text-[#4CAF50]">✓ {{ __('Excellent - Above industry average') }}</span>
                        @elseif($winRate >= 30)
                            <span class="text-[#FFC107]">✓ {{ __('Good - Within industry range') }}</span>
                        @else
                            <span class="text-[#EF5350]">! {{ __('Below benchmark - Review sales process') }}</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        {{-- Revenue Distribution --}}
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <h3 class="mb-4 flex items-center gap-2 text-base font-semibold text-white">
                <flux:icon.chart-bar class="size-5 text-[#4CAF50]" />
                {{ __('Revenue by Sales Rep') }}
            </h3>
            <div class="space-y-3">
                @forelse ($revenueByOwner as $item)
                    <div class="flex items-center gap-3">
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-medium text-white truncate">{{ $item->owner_name }}</span>
                                <span class="text-xs text-[#D1C4E9] ml-2">@currency($item->revenue)</span>
                            </div>
                            <div class="h-6 rounded-lg bg-[#3B0A45] overflow-hidden">
                                <div 
                                    class="h-full bg-gradient-to-r from-[#4CAF50] to-[#388E3C] flex items-center justify-center text-white text-xs font-semibold"
                                    style="width: {{ $item->percentage }}%"
                                >
                                    @if($item->percentage > 8)
                                        {{ $item->percentage }}%
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="py-4 text-center text-sm text-[#D1C4E9]">{{ __('No revenue data available.') }}</p>
                @endforelse
            </div>
        </div>

    </div>

    {{-- Sales Leaderboard --}}
    <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
        <h3 class="mb-4 flex items-center gap-2 text-lg font-semibold text-white">
            <flux:icon.trophy class="size-5 text-[#F5B301]" />
            {{ __('Sales Leaderboard') }}
        </h3>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-[#F5B301]/20">
                        <th class="pb-3 text-left text-sm font-semibold text-[#D1C4E9]">{{ __('Rank') }}</th>
                        <th class="pb-3 text-left text-sm font-semibold text-[#D1C4E9]">{{ __('Sales Rep') }}</th>
                        <th class="pb-3 text-right text-sm font-semibold text-[#D1C4E9]">{{ __('Total Revenue') }}</th>
                        <th class="pb-3 text-right text-sm font-semibold text-[#D1C4E9]">{{ __('Deals Won') }}</th>
                        <th class="pb-3 text-right text-sm font-semibold text-[#D1C4E9]">{{ __('Avg Deal Size') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($topPerformers as $index => $performer)
                        <tr class="border-b border-[#F5B301]/10 hover:bg-[#3B0A45]/30">
                            <td class="py-3">
                                @if($index === 0)
                                    <flux:badge color="yellow" size="sm">🥇 #1</flux:badge>
                                @elseif($index === 1)
                                    <flux:badge color="zinc" size="sm">🥈 #2</flux:badge>
                                @elseif($index === 2)
                                    <flux:badge color="zinc" size="sm">🥉 #3</flux:badge>
                                @else
                                    <span class="text-[#D1C4E9] text-sm">#{{ $index + 1 }}</span>
                                @endif
                            </td>
                            <td class="py-3">
                                <span class="text-white font-medium">{{ $performer->owner_name }}</span>
                            </td>
                            <td class="py-3 text-right">
                                <span class="text-white font-semibold">@currency($performer->total_revenue)</span>
                            </td>
                            <td class="py-3 text-right">
                                <flux:badge color="blue" size="sm">{{ $performer->deals_won }}</flux:badge>
                            </td>
                            <td class="py-3 text-right">
                                <span class="text-[#D1C4E9]">@currency($performer->avg_deal_size)</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-sm text-[#D1C4E9]">
                                {{ __('No sales performance data available for the selected period.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pipeline Status & Monthly Trends --}}
    <div class="grid gap-6 lg:grid-cols-2">
        
        {{-- Current Pipeline by Stage --}}
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <h3 class="mb-4 flex items-center gap-2 text-base font-semibold text-white">
                <flux:icon.funnel class="size-5 text-[#B9A0DC]" />
                {{ __('Current Pipeline by Stage') }}
            </h3>
            <div class="space-y-3">
                @forelse ($dealsByStage as $stage)
                    <div class="flex items-center justify-between rounded-lg bg-[#3B0A45]/50 p-3 border border-[#F5B301]/20">
                        <div>
                            <p class="font-medium text-white">{{ ucfirst($stage->stage) }}</p>
                            <p class="text-xs text-[#D1C4E9]">{{ number_format($stage->count) }} {{ __('opportunities') }}</p>
                        </div>
                        <span class="text-white font-semibold">@currency($stage->total_value)</span>
                    </div>
                @empty
                    <p class="py-4 text-center text-sm text-[#D1C4E9]">{{ __('No active pipeline deals.') }}</p>
                @endforelse
            </div>
        </div>

        {{-- Monthly Revenue Trends --}}
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <h3 class="mb-4 flex items-center gap-2 text-base font-semibold text-white">
                <flux:icon.chart-bar class="size-5 text-[#64B5F6]" />
                {{ __('Monthly Revenue Trends') }}
            </h3>
            <div class="space-y-3">
                @forelse ($monthlyTrends as $month)
                    <div class="flex items-center justify-between rounded-lg bg-[#3B0A45]/50 p-3 border border-[#F5B301]/20">
                        <div>
                            <p class="font-medium text-white">{{ $month->month_name }}</p>
                            <p class="text-xs text-[#D1C4E9]">{{ number_format($month->deals) }} {{ __('deals closed') }}</p>
                        </div>
                        <span class="text-white font-semibold">@currency($month->revenue)</span>
                    </div>
                @empty
                    <p class="py-4 text-center text-sm text-[#D1C4E9]">{{ __('No monthly trend data available.') }}</p>
                @endforelse
            </div>
        </div>

    </div>

</div>
