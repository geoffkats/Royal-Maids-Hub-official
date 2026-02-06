<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">{{ __('Contract Templates') }}</h1>
            <p class="text-slate-600 dark:text-slate-400 mt-1">{{ __('Use pre-configured templates to quickly create contracts') }}</p>
        </div>
        <a href="{{ route('contracts.create') }}" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
            {{ __('Create Contract') }}
        </a>
    </div>

    {{-- Statistics --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-4 border-l-4 border-slate-400 dark:border-slate-600">
            <p class="text-xs text-slate-600 dark:text-slate-400 uppercase font-semibold">{{ __('Total Templates') }}</p>
            <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ $stats['total_templates'] }}</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-4 border-l-4 border-blue-400">
            <p class="text-xs text-blue-600 dark:text-blue-300 uppercase font-semibold">{{ __('Full-Time') }}</p>
            <p class="text-2xl font-bold text-blue-600 dark:text-blue-300 mt-1">{{ $stats['full_time_contracts'] }}</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-4 border-l-4 border-green-400">
            <p class="text-xs text-green-600 dark:text-green-300 uppercase font-semibold">{{ __('Part-Time') }}</p>
            <p class="text-2xl font-bold text-green-600 dark:text-green-300 mt-1">{{ $stats['part_time_contracts'] }}</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-4 border-l-4 border-purple-400">
            <p class="text-xs text-purple-600 dark:text-purple-300 uppercase font-semibold">{{ __('Live-In') }}</p>
            <p class="text-2xl font-bold text-purple-600 dark:text-purple-300 mt-1">{{ $stats['live_in_contracts'] }}</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-4 border-l-4 border-orange-400">
            <p class="text-xs text-orange-600 dark:text-orange-300 uppercase font-semibold">{{ __('Live-Out') }}</p>
            <p class="text-2xl font-bold text-orange-600 dark:text-orange-300 mt-1">{{ $stats['live_out_contracts'] }}</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-lg shadow p-4 border-l-4 border-amber-400">
            <p class="text-xs text-amber-600 dark:text-amber-300 uppercase font-semibold">{{ __('Seasonal') }}</p>
            <p class="text-2xl font-bold text-amber-600 dark:text-amber-300 mt-1">{{ $stats['seasonal_contracts'] }}</p>
        </div>
    </div>

    {{-- Templates Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($templates as $key => $template)
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow hover:shadow-lg transition border border-slate-200 dark:border-slate-700 overflow-hidden">
                {{-- Header --}}
                <div class="p-6 border-b border-slate-200 dark:border-slate-700"
                     @switch($template['type'])
                         @case('full-time')
                            style="background: linear-gradient(135deg, #3b82f6, #1e40af);"
                             @break
                         @case('part-time')
                            style="background: linear-gradient(135deg, #10b981, #047857);"
                             @break
                         @case('live-in')
                            style="background: linear-gradient(135deg, #a855f7, #7e22ce);"
                             @break
                         @case('live-out')
                            style="background: linear-gradient(135deg, #f97316, #c2410c);"
                             @break
                         @case('seasonal')
                            style="background: linear-gradient(135deg, #f59e0b, #d97706);"
                             @break
                     @endswitch
                >
                    <h3 class="text-xl font-bold text-white">{{ $template['name'] }}</h3>
                    <p class="text-sm text-white/80 mt-2">{{ $template['description'] }}</p>
                </div>

                {{-- Details --}}
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600 dark:text-slate-400">{{ __('Duration') }}</span>
                        <span class="font-semibold text-slate-900 dark:text-white">{{ $template['duration_days'] }} {{ __('days') }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600 dark:text-slate-400">{{ __('Work Days/Week') }}</span>
                        <span class="font-semibold text-slate-900 dark:text-white">{{ $template['working_days_per_week'] }}</span>
                    </div>

                    <div class="pt-4 border-t border-slate-200 dark:border-slate-700">
                        <p class="text-xs text-slate-600 dark:text-slate-400 mb-3">{{ __('Total Expected Work Days') }}</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white">
                            {{ ceil(($template['duration_days'] / 7) * $template['working_days_per_week']) }}
                        </p>
                    </div>
                </div>

                {{-- Action --}}
                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-700/50 border-t border-slate-200 dark:border-slate-700">
                    <a href="{{ route('contracts.create', ['template' => $key]) }}"
                       class="block text-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                        {{ __('Use This Template') }}
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Template Guide --}}
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-4">{{ __('Template Guidelines') }}</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-blue-800 dark:text-blue-200">
            <div>
                <p class="font-semibold mb-2">{{ __('Full-Time Contracts') }}</p>
                <p class="text-blue-700 dark:text-blue-300">Best for dedicated maids working 6 days per week with annual contracts.</p>
            </div>

            <div>
                <p class="font-semibold mb-2">{{ __('Part-Time Contracts') }}</p>
                <p class="text-blue-700 dark:text-blue-300">Ideal for maids working specific days per week for shorter periods.</p>
            </div>

            <div>
                <p class="font-semibold mb-2">{{ __('Live-In Contracts') }}</p>
                <p class="text-blue-700 dark:text-blue-300">For maids residing at client\'s property with on-call availability.</p>
            </div>

            <div>
                <p class="font-semibold mb-2">{{ __('Live-Out Contracts') }}</p>
                <p class="text-blue-700 dark:text-blue-300">For day-shift maids living elsewhere with fixed daily hours.</p>
            </div>

            <div>
                <p class="font-semibold mb-2">{{ __('Seasonal Contracts') }}</p>
                <p class="text-blue-700 dark:text-blue-300">Best for temporary replacements or peak-season additional staffing.</p>
            </div>
        </div>
    </div>
</div>
