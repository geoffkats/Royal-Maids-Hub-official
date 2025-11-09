<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">{{ __('Activity Metrics Report') }}</flux:heading>
            <flux:subheading class="mt-1">
                {{ __('Monitor response times, SLA compliance, and activity performance across the team') }}
            </flux:subheading>
        </div>
        <div class="flex gap-3">
            <flux:button as="a" href="{{ route('crm.opportunities.index') }}" variant="ghost" icon="arrow-left" wire:navigate>
                {{ __('Back to Pipeline') }}
            </flux:button>
        </div>
    </div>

    <flux:separator variant="subtle" />

    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        <flux:select wire:model.live="dateRange" label="{{ __('Date Range') }}">
            <option value="7">{{ __('Last 7 Days') }}</option>
            <option value="30">{{ __('Last 30 Days') }}</option>
            <option value="60">{{ __('Last 60 Days') }}</option>
            <option value="90">{{ __('Last 90 Days') }}</option>
        </flux:select>

        <flux:select wire:model.live="selectedOwner" label="{{ __('Owner') }}">
            <option value="all">{{ __('All Owners') }}</option>
            @foreach ($owners as $owner)
                <option value="{{ $owner->id }}">{{ $owner->name }}</option>
            @endforeach
        </flux:select>

        <flux:select wire:model.live="selectedType" label="{{ __('Activity Type') }}">
            <option value="all">{{ __('All Types') }}</option>
            @foreach ($activityTypes as $type)
                <option value="{{ $type }}">{{ ucfirst($type) }}</option>
            @endforeach
        </flux:select>
    </div>

    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <p class="text-sm font-medium text-[#D1C4E9]">{{ __('Total Activities') }}</p>
            <p class="mt-2 text-3xl font-bold text-white">{{ number_format($totalActivities) }}</p>
        </div>
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <p class="text-sm font-medium text-[#D1C4E9]">{{ __('Completed') }}</p>
            <p class="mt-2 text-3xl font-bold text-white">{{ number_format($completedActivities) }}</p>
            <p class="mt-1 text-xs text-[#D1C4E9]">{{ $completionRate }}% {{ __('completion') }}</p>
        </div>
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <p class="text-sm font-medium text-[#D1C4E9]">{{ __('Pending') }}</p>
            <p class="mt-2 text-3xl font-bold text-white">{{ number_format($pendingActivities) }}</p>
        </div>
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <p class="text-sm font-medium text-[#D1C4E9]">{{ __('Overdue') }}</p>
            <p class="mt-2 text-3xl font-bold text-{{ $overdueActivities > 0 ? 'red-400' : 'white' }}">{{ number_format($overdueActivities) }}</p>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <h3 class="mb-4 flex items-center gap-2 text-base font-semibold text-white">
                <flux:icon.chart-bar class="size-5 text-[#F5B301]" />
                {{ __('Activities by Type') }}
            </h3>
            <div class="space-y-3">
                @forelse ($activitiesByType as $item)
                    <div class="flex items-center justify-between rounded-lg bg-[#3B0A45]/50 p-3 border border-[#F5B301]/20">
                        <div>
                            <p class="font-medium text-white">{{ ucfirst($item->type ?? 'unknown') }}</p>
                            <p class="text-xs text-[#D1C4E9]">{{ number_format($item->total) }} {{ __('total') }} • {{ number_format($item->completed) }} {{ __('completed') }}</p>
                        </div>
                        <flux:badge :color="$item->completion_rate >= 70 ? 'green' : ($item->completion_rate >= 40 ? 'yellow' : 'red')" size="sm">
                            {{ $item->completion_rate }}%
                        </flux:badge>
                    </div>
                @empty
                    <p class="py-4 text-center text-sm text-[#D1C4E9]">{{ __('No activity data available.') }}</p>
                @endforelse
            </div>
        </div>

        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <h3 class="mb-4 flex items-center gap-2 text-base font-semibold text-white">
                <flux:icon.clock class="size-5 text-[#64B5F6]" />
                {{ __('SLA Compliance & Response Time') }}
            </h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between rounded-lg bg-[#3B0A45]/50 p-3 border border-[#F5B301]/20">
                    <span class="text-sm text-[#D1C4E9]">{{ __('Within SLA') }}</span>
                    <flux:badge color="green" size="sm">{{ number_format($withinSLA) }}</flux:badge>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-[#3B0A45]/50 p-3 border border-[#F5B301]/20">
                    <span class="text-sm text-[#D1C4E9]">{{ __('Breached SLA') }}</span>
                    <flux:badge color="red" size="sm">{{ number_format($breachedSLA) }}</flux:badge>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-[#3B0A45]/50 p-3 border border-[#F5B301]/20">
                    <span class="text-sm text-[#D1C4E9]">{{ __('SLA Compliance Rate') }}</span>
                    <flux:badge :color="$slaComplianceRate >= 80 ? 'green' : ($slaComplianceRate >= 60 ? 'yellow' : 'red')" size="sm">{{ $slaComplianceRate }}%</flux:badge>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-[#3B0A45]/50 p-3 border border-[#F5B301]/20">
                    <span class="text-sm text-[#D1C4E9]">{{ __('Average Response Time') }}</span>
                    <span class="font-semibold text-white">{{ number_format($avgResponseTime, 1) }} {{ __('hours') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <h3 class="mb-4 flex items-center gap-2 text-base font-semibold text-white">
                <flux:icon.user-group class="size-5 text-[#B9A0DC]" />
                {{ __('Activities by Owner') }}
            </h3>
            <div class="space-y-3">
                @forelse ($activitiesByOwner as $item)
                    <div class="flex items-center justify-between rounded-lg bg-[#3B0A45]/50 p-3 border border-[#F5B301]/20">
                        <div>
                            <p class="font-medium text-white">{{ $item->owner_name }}</p>
                            <p class="text-xs text-[#D1C4E9]">{{ number_format($item->total) }} {{ __('total') }}</p>
                        </div>
                        <flux:badge :color="$item->completion_rate >= 70 ? 'green' : ($item->completion_rate >= 40 ? 'yellow' : 'red')" size="sm">
                            {{ $item->completion_rate }}%
                        </flux:badge>
                    </div>
                @empty
                    <p class="py-4 text-center text-sm text-[#D1C4E9]">{{ __('No owner data available.') }}</p>
                @endforelse
            </div>
        </div>

        <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
            <h3 class="mb-4 flex items-center gap-2 text-base font-semibold text-white">
                <flux:icon.bolt class="size-5 text-[#EF5350]" />
                {{ __('Overdue by Owner') }}
            </h3>
            <div class="space-y-3">
                @forelse ($overdueByOwner as $item)
                    <div class="flex items-center justify-between rounded-lg bg-[#3B0A45]/50 p-3 border border-[#F5B301]/20">
                        <div>
                            <p class="font-medium text-white">{{ $item->owner_name }}</p>
                        </div>
                        <flux:badge color="red" size="sm">{{ number_format($item->overdue_count) }}</flux:badge>
                    </div>
                @empty
                    <p class="py-4 text-center text-sm text-[#D1C4E9]">{{ __('No overdue data available.') }}</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="rounded-lg border border-[#F5B301]/30 bg-[#512B58] p-6 shadow-lg">
        <h3 class="mb-4 flex items-center gap-2 text-base font-semibold text-white">
            <flux:icon.sparkles class="size-5 text-[#64B5F6]" />
            {{ __('7-Day Completion Trend') }}
        </h3>
        <div class="grid gap-3 md:grid-cols-7">
            @forelse ($completionTrend as $day)
                <div class="rounded-lg bg-[#3B0A45]/50 p-3 border border-[#F5B301]/20 text-center">
                    <p class="text-xs text-[#D1C4E9]">{{ $day->day_name }}</p>
                    <p class="mt-2 text-xl font-bold text-white">{{ number_format($day->count) }}</p>
                </div>
            @empty
                <p class="py-4 text-center text-sm text-[#D1C4E9]">{{ __('No trend data available.') }}</p>
            @endforelse
        </div>
    </div>

</div>
