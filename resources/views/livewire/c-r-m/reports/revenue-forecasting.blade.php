<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">{{ __('Revenue Forecasting Report') }}</flux:heading>
            <flux:subheading class="mt-1">
                {{ __('Project future revenue using weighted pipeline and confidence scenarios') }}
            </flux:subheading>
        </div>
        <div class="flex gap-3">
            <flux:button as="a" href="{{ route('crm.opportunities.index') }}" variant="ghost" icon="arrow-left" wire:navigate>
                {{ __('Back to Pipeline') }}
            </flux:button>
        </div>
    </div>

    <flux:separator variant="subtle" />

    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        <flux:select wire:model.live="forecastPeriod" label="{{ __('Forecast Period') }}">
            <option value="quarter">{{ __('Next Quarter') }}</option>
            <option value="year">{{ __('Next 12 Months') }}</option>
        </flux:select>

        <flux:select wire:model.live="confidenceLevel" label="{{ __('Confidence Level') }}">
            <option value="low">{{ __('Low (Conservative)') }}</option>
            <option value="medium">{{ __('Medium (Realistic)') }}</option>
            <option value="high">{{ __('High (Optimistic)') }}</option>
        </flux:select>
    </div>

    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <p class="text-sm font-medium text-[#D1C4E9]">{{ __('Total Pipeline') }}</p>
            <p class="mt-2 text-3xl font-bold text-white">@currency($totalPipelineValue)</p>
        </div>
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <p class="text-sm font-medium text-[#D1C4E9]">{{ __('Weighted Pipeline') }}</p>
            <p class="mt-2 text-3xl font-bold text-white">@currency($weightedPipelineValue)</p>
        </div>
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <p class="text-sm font-medium text-[#D1C4E9]">{{ __('Expected Revenue') }}</p>
            <p class="mt-2 text-3xl font-bold text-white">@currency($expectedRevenue)</p>
        </div>
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <p class="text-sm font-medium text-[#D1C4E9]">{{ __('Scenarios') }}</p>
            <p class="mt-2 text-sm font-medium text-white">{{ __('Best Case:') }} @currency($bestCase)</p>
            <p class="mt-1 text-xs text-[#D1C4E9]">{{ __('Worst Case:') }} @currency($worstCase)</p>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <h3 class="mb-4 flex items-center gap-2 text-base font-semibold text-white">
                <flux:icon.funnel class="size-5 text-[#B9A0DC]" />
                {{ __('Revenue by Stage') }}
            </h3>
            <div class="space-y-3">
                @forelse ($revenueByStage as $row)
                    <div class="flex items-center justify-between rounded-lg bg-[#3B0A45]/50 p-3 border border-[#F5B301]/20">
                        <div>
                            <p class="font-medium text-white">{{ ucfirst($row['stage']) }}</p>
                            <p class="text-xs text-[#D1C4E9]">{{ number_format($row['count']) }} {{ __('deals') }} • {{ __('Avg Prob:') }} {{ number_format($row['avg_probability'], 1) }}%</p>
                        </div>
                        <div class="text-right">
                            <p class="text-white font-semibold">@currency($row['total_value'])</p>
                            <p class="text-xs text-[#D1C4E9]">{{ __('Weighted:') }} @currency($row['weighted_value'])</p>
                        </div>
                    </div>
                @empty
                    <p class="py-4 text-center text-sm text-[#D1C4E9]">{{ __('No stage data available.') }}</p>
                @endforelse
            </div>
        </div>

        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <h3 class="mb-4 flex items-center gap-2 text-base font-semibold text-white">
                <flux:icon.calendar class="size-5 text-[#64B5F6]" />
                {{ __('Forecast by Month') }}
            </h3>
            <div class="space-y-3">
                @forelse ($revenueByMonth as $row)
                    <div class="flex items-center justify-between rounded-lg bg-[#3B0A45]/50 p-3 border border-[#F5B301]/20">
                        <div>
                            <p class="font-medium text-white">{{ $row['month'] }}</p>
                            <p class="text-xs text-[#D1C4E9]">{{ number_format($row['count']) }} {{ __('deals') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-white font-semibold">@currency($row['total_value'])</p>
                            <p class="text-xs text-[#D1C4E9]">{{ __('Weighted:') }} @currency($row['weighted_value'])</p>
                        </div>
                    </div>
                @empty
                    <p class="py-4 text-center text-sm text-[#D1C4E9]">{{ __('No monthly data available.') }}</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <h3 class="mb-4 flex items-center gap-2 text-base font-semibold text-white">
                <flux:icon.star class="size-5 text-[#F5B301]" />
                {{ __('Top Deals') }}
            </h3>
            <div class="space-y-3">
                @forelse ($topDeals as $deal)
                    <div class="flex items-center justify-between rounded-lg bg-[#3B0A45]/50 p-3 border border-[#F5B301]/20">
                        <div class="min-w-0">
                            <p class="font-medium text-white truncate">{{ $deal['title'] }}</p>
                            <p class="text-xs text-[#D1C4E9] truncate">{{ $deal['owner_name'] }} • {{ __('Prob:') }} {{ $deal['probability'] }}% • {{ $deal['expected_close_date']?->format('M j, Y') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-white font-semibold">@currency($deal['amount'])</p>
                            <p class="text-xs text-[#D1C4E9]">{{ __('Weighted:') }} @currency($deal['weighted_value'])</p>
                        </div>
                    </div>
                @empty
                    <p class="py-4 text-center text-sm text-[#D1C4E9]">{{ __('No top deal data available.') }}</p>
                @endforelse
            </div>
        </div>

        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <h3 class="mb-4 flex items-center gap-2 text-base font-semibold text-white">
                <flux:icon.exclamation-triangle class="size-5 text-[#EF5350]" />
                {{ __('Risk Assessment') }}
            </h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between rounded-lg bg-[#3B0A45]/50 p-3 border border-[#F5B301]/20">
                    <span class="text-sm text-[#D1C4E9]">{{ __('High Risk (< 30%)') }}</span>
                    <flux:badge color="red" size="sm">{{ number_format($riskAssessment['high_risk']) }}</flux:badge>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-[#3B0A45]/50 p-3 border border-[#F5B301]/20">
                    <span class="text-sm text-[#D1C4E9]">{{ __('Medium Risk (30-69%)') }}</span>
                    <flux:badge color="yellow" size="sm">{{ number_format($riskAssessment['medium_risk']) }}</flux:badge>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-[#3B0A45]/50 p-3 border border-[#F5B301]/20">
                    <span class="text-sm text-[#D1C4E9]">{{ __('Low Risk (70% +)') }}</span>
                    <flux:badge color="green" size="sm">{{ number_format($riskAssessment['low_risk']) }}</flux:badge>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-[#3B0A45]/50 p-3 border border-[#F5B301]/20">
                    <span class="text-sm text-[#D1C4E9]">{{ __('No Close Date') }}</span>
                    <flux:badge color="zinc" size="sm">{{ number_format($riskAssessment['no_close_date']) }}</flux:badge>
                </div>
            </div>
        </div>
    </div>

</div>
