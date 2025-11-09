<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">{{ __('Lead Funnel Report') }}</flux:heading>
            <flux:subheading class="mt-1">
                {{ __('Analyze conversion rates and lead progression through sales stages') }}
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

        <flux:select wire:model.live="selectedSource" label="{{ __('Lead Source') }}">
            <option value="all">{{ __('All Sources') }}</option>
            @foreach ($sources as $source)
                <option value="{{ $source->id }}">{{ $source->name }}</option>
            @endforeach
        </flux:select>
    </div>

    {{-- Key Metrics --}}
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-[#D1C4E9]">{{ __('Total Leads') }}</p>
                    <p class="mt-2 text-3xl font-bold text-white">{{ number_format($funnelData['total']) }}</p>
                    <p class="mt-1 text-xs text-[#D1C4E9]">{{ __('in selected period') }}</p>
                </div>
                <div class="rounded-full bg-[#64B5F6] p-3">
                    <flux:icon.users class="size-8 text-white" />
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-[#D1C4E9]">{{ __('Converted') }}</p>
                    <p class="mt-2 text-3xl font-bold text-white">{{ number_format($funnelData['converted']) }}</p>
                    <p class="mt-1 text-xs text-[#D1C4E9]">{{ $conversionRates['overall_conversion'] }}% {{ __('rate') }}</p>
                </div>
                <div class="rounded-full bg-[#4CAF50] p-3">
                    <flux:icon.check-circle class="size-8 text-white" />
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-[#D1C4E9]">{{ __('In Progress') }}</p>
                    <p class="mt-2 text-3xl font-bold text-white">{{ number_format($funnelData['working'] + $funnelData['qualified']) }}</p>
                    <p class="mt-1 text-xs text-[#D1C4E9]">{{ __('working + qualified') }}</p>
                </div>
                <div class="rounded-full bg-[#FFC107] p-3">
                    <flux:icon.arrow-trending-up class="size-8 text-white" />
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-[#D1C4E9]">{{ __('Disqualified') }}</p>
                    <p class="mt-2 text-3xl font-bold text-white">{{ number_format($funnelData['disqualified']) }}</p>
                    <p class="mt-1 text-xs text-[#D1C4E9]">{{ $funnelData['total'] > 0 ? round(($funnelData['disqualified'] / $funnelData['total']) * 100, 1) : 0 }}% {{ __('of total') }}</p>
                </div>
                <div class="rounded-full bg-[#EF5350] p-3">
                    <flux:icon.x-circle class="size-8 text-white" />
                </div>
            </div>
        </div>

    </div>

    {{-- Funnel Visualization & Conversion Rates --}}
    <div class="grid gap-6 lg:grid-cols-2">
        
        {{-- Funnel Stages --}}
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <h3 class="mb-6 flex items-center gap-2 text-lg font-semibold text-white">
                <flux:icon.funnel class="size-5 text-[#F5B301]" />
                {{ __('Sales Funnel Stages') }}
            </h3>
            
            <div class="space-y-4">
                {{-- New --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-white">{{ __('New') }}</span>
                        <span class="text-sm text-[#D1C4E9]">{{ number_format($funnelData['new']) }}</span>
                    </div>
                    <div class="h-12 rounded-lg bg-[#3B0A45] overflow-hidden">
                        <div 
                            class="h-full bg-gradient-to-r from-[#64B5F6] to-[#42A5F5] flex items-center justify-center text-white font-semibold"
                            style="width: 100%"
                        >
                            100%
                        </div>
                    </div>
                </div>

                {{-- Working --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-white">{{ __('Working') }}</span>
                        <span class="text-sm text-[#D1C4E9]">{{ number_format($funnelData['working']) }}</span>
                    </div>
                    <div class="h-12 rounded-lg bg-[#3B0A45] overflow-hidden">
                        <div 
                            class="h-full bg-gradient-to-r from-[#FFC107] to-[#FFB300] flex items-center justify-center text-white font-semibold"
                            style="width: {{ $funnelData['new'] > 0 ? ($funnelData['working'] / $funnelData['new']) * 100 : 0 }}%"
                        >
                            @if($funnelData['new'] > 0)
                                {{ round(($funnelData['working'] / $funnelData['new']) * 100, 1) }}%
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Qualified --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-white">{{ __('Qualified') }}</span>
                        <span class="text-sm text-[#D1C4E9]">{{ number_format($funnelData['qualified']) }}</span>
                    </div>
                    <div class="h-12 rounded-lg bg-[#3B0A45] overflow-hidden">
                        <div 
                            class="h-full bg-gradient-to-r from-[#B9A0DC] to-[#9575CD] flex items-center justify-center text-white font-semibold"
                            style="width: {{ $funnelData['new'] > 0 ? ($funnelData['qualified'] / $funnelData['new']) * 100 : 0 }}%"
                        >
                            @if($funnelData['new'] > 0)
                                {{ round(($funnelData['qualified'] / $funnelData['new']) * 100, 1) }}%
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Converted --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-white">{{ __('Converted') }}</span>
                        <span class="text-sm text-[#D1C4E9]">{{ number_format($funnelData['converted']) }}</span>
                    </div>
                    <div class="h-12 rounded-lg bg-[#3B0A45] overflow-hidden">
                        <div 
                            class="h-full bg-gradient-to-r from-[#4CAF50] to-[#388E3C] flex items-center justify-center text-white font-semibold"
                            style="width: {{ $funnelData['new'] > 0 ? ($funnelData['converted'] / $funnelData['new']) * 100 : 0 }}%"
                        >
                            @if($funnelData['new'] > 0)
                                {{ round(($funnelData['converted'] / $funnelData['new']) * 100, 1) }}%
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Conversion Rates --}}
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <h3 class="mb-6 flex items-center gap-2 text-lg font-semibold text-white">
                <flux:icon.arrow-trending-up class="size-5 text-[#4CAF50]" />
                {{ __('Stage Conversion Rates') }}
            </h3>
            
            <div class="space-y-4">
                <div class="rounded-lg bg-[#3B0A45]/50 p-4 border border-[#F5B301]/20">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-[#D1C4E9]">{{ __('New → Working') }}</span>
                        <flux:badge color="blue" size="sm">{{ $conversionRates['new_to_working'] }}%</flux:badge>
                    </div>
                    <div class="h-2 rounded-full bg-[#3B0A45]">
                        <div 
                            class="h-full rounded-full bg-[#64B5F6]"
                            style="width: {{ $conversionRates['new_to_working'] }}%"
                        ></div>
                    </div>
                </div>

                <div class="rounded-lg bg-[#3B0A45]/50 p-4 border border-[#F5B301]/20">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-[#D1C4E9]">{{ __('Working → Qualified') }}</span>
                        <flux:badge color="yellow" size="sm">{{ $conversionRates['working_to_qualified'] }}%</flux:badge>
                    </div>
                    <div class="h-2 rounded-full bg-[#3B0A45]">
                        <div 
                            class="h-full rounded-full bg-[#FFC107]"
                            style="width: {{ $conversionRates['working_to_qualified'] }}%"
                        ></div>
                    </div>
                </div>

                <div class="rounded-lg bg-[#3B0A45]/50 p-4 border border-[#F5B301]/20">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-[#D1C4E9]">{{ __('Qualified → Converted') }}</span>
                        <flux:badge color="purple" size="sm">{{ $conversionRates['qualified_to_converted'] }}%</flux:badge>
                    </div>
                    <div class="h-2 rounded-full bg-[#3B0A45]">
                        <div 
                            class="h-full rounded-full bg-[#B9A0DC]"
                            style="width: {{ $conversionRates['qualified_to_converted'] }}%"
                        ></div>
                    </div>
                </div>

                <div class="rounded-lg bg-gradient-to-r from-[#4CAF50]/20 to-[#388E3C]/20 p-4 border-2 border-[#4CAF50]">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-semibold text-white">{{ __('Overall Conversion') }}</span>
                        <flux:badge color="green" size="lg">{{ $conversionRates['overall_conversion'] }}%</flux:badge>
                    </div>
                    <div class="h-3 rounded-full bg-[#3B0A45]">
                        <div 
                            class="h-full rounded-full bg-gradient-to-r from-[#4CAF50] to-[#388E3C]"
                            style="width: {{ $conversionRates['overall_conversion'] }}%"
                        ></div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Source Performance & Time in Stage --}}
    <div class="grid gap-6 lg:grid-cols-2">
        
        {{-- Performance by Source --}}
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <h3 class="mb-4 flex items-center gap-2 text-base font-semibold text-white">
                <flux:icon.chart-bar class="size-5 text-[#F5B301]" />
                {{ __('Performance by Lead Source') }}
            </h3>
            <div class="space-y-3">
                @forelse ($sourceBreakdown as $source)
                    <div class="flex items-center justify-between rounded-lg bg-[#3B0A45]/50 p-3 border border-[#F5B301]/20">
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-white">{{ ucfirst($source->source ?? 'Unknown') }}</p>
                            <p class="text-xs text-[#D1C4E9]">
                                {{ number_format($source->total) }} {{ __('leads') }} • 
                                {{ number_format($source->converted) }} {{ __('converted') }}
                            </p>
                        </div>
                        <flux:badge 
                            :color="$source->conversion_rate >= 20 ? 'green' : ($source->conversion_rate >= 10 ? 'yellow' : 'red')" 
                            size="sm"
                        >
                            {{ $source->conversion_rate }}%
                        </flux:badge>
                    </div>
                @empty
                    <p class="py-4 text-center text-sm text-[#D1C4E9]">{{ __('No source data available.') }}</p>
                @endforelse
            </div>
        </div>

        {{-- Average Time in Stage --}}
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <h3 class="mb-4 flex items-center gap-2 text-base font-semibold text-white">
                <flux:icon.clock class="size-5 text-[#64B5F6]" />
                {{ __('Average Time in Stage') }}
            </h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between rounded-lg bg-[#3B0A45]/50 p-3 border border-[#F5B301]/20">
                    <span class="text-sm text-[#D1C4E9]">{{ __('New Stage') }}</span>
                    <span class="font-semibold text-white">{{ number_format($avgTimeInStage['new'], 1) }} {{ __('days') }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-[#3B0A45]/50 p-3 border border-[#F5B301]/20">
                    <span class="text-sm text-[#D1C4E9]">{{ __('Working Stage') }}</span>
                    <span class="font-semibold text-white">{{ number_format($avgTimeInStage['working'], 1) }} {{ __('days') }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-[#3B0A45]/50 p-3 border border-[#F5B301]/20">
                    <span class="text-sm text-[#D1C4E9]">{{ __('Qualified Stage') }}</span>
                    <span class="font-semibold text-white">{{ number_format($avgTimeInStage['qualified'], 1) }} {{ __('days') }}</span>
                </div>
                <div class="mt-4 pt-3 border-t border-[#F5B301]/20">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-white">{{ __('Total Cycle Time') }}</span>
                        <flux:badge color="blue" size="sm">
                            {{ number_format(array_sum($avgTimeInStage), 1) }} {{ __('days') }}
                        </flux:badge>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
