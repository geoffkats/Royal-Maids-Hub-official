<div class="space-y-6">
    {{-- Trainer Dashboard Header --}}
    <div class="bg-gradient-to-r from-[#3B0A45] to-[#512B58] rounded-lg shadow-lg p-6 text-white border border-[#F5B301]/30">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">{{ __('Trainer Dashboard') }}</h1>
                <p class="text-[#D1C4E9]">{{ __('Train & Develop Maids for Excellence') }}</p>
                <p class="text-sm text-[#D1C4E9]/80 mt-1">
                    {{ __('Welcome back,') }} {{ $trainer->full_name ?? auth()->user()->name }}
                    @if(!$trainer->specialization)
                        <span class="text-[#F5B301]">•</span>
                        <a href="{{ route('trainers.edit', $trainer) }}" class="text-[#F5B301] hover:underline">
                            {{ __('Complete your profile') }}
                        </a>
                    @endif
                </p>
            </div>
            <div class="text-right">
                <div class="w-16 h-16 bg-[#F5B301] rounded-full flex items-center justify-center">
                    <flux:icon.academic-cap class="w-8 h-8 text-[#3B0A45]" />
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- My Trainees --}}
        <div class="bg-[#512B58] rounded-lg shadow-lg p-6 border border-[#F5B301]/30">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-[#D1C4E9]">{{ __('My Trainees') }}</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ number_format($myTrainees) }}</p>
                    <p class="text-xs text-[#D1C4E9]/70 mt-1">{{ __('Active maids under training') }}</p>
                </div>
                <div class="bg-[#F5B301] p-3 rounded-full">
                    <flux:icon.user-group class="w-8 h-8 text-[#3B0A45]" />
                </div>
            </div>
        </div>

        {{-- Active Training Sessions --}}
        <div class="bg-[#512B58] rounded-lg shadow-lg p-6 border border-[#F5B301]/30">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-[#D1C4E9]">{{ __('Active Sessions') }}</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ number_format($activeSessions) }}</p>
                    <p class="text-xs text-[#D1C4E9]/70 mt-1">{{ __('Currently in progress') }}</p>
                </div>
                <div class="bg-[#F5B301] p-3 rounded-full">
                    <flux:icon.calendar-days class="w-8 h-8 text-[#3B0A45]" />
                </div>
            </div>
        </div>

        {{-- Completed Trainings --}}
        <div class="bg-[#512B58] rounded-lg shadow-lg p-6 border border-[#F5B301]/30">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-[#D1C4E9]">{{ __('Completed') }}</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ number_format($completedTrainings) }}</p>
                    <p class="text-xs text-[#D1C4E9]/70 mt-1">{{ __('Training sessions finished') }}</p>
                </div>
                <div class="bg-[#F5B301] p-3 rounded-full">
                    <flux:icon.check-circle class="w-8 h-8 text-[#3B0A45]" />
                </div>
            </div>
        </div>

        {{-- Average Rating --}}
        <div class="bg-[#512B58] rounded-lg shadow-lg p-6 border border-[#F5B301]/30">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-[#D1C4E9]">{{ __('Avg. Rating') }}</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ number_format($averageRating, 1) }}/5</p>
                    <p class="text-xs text-[#D1C4E9]/70 mt-1">{{ __('From evaluations') }}</p>
                </div>
                <div class="bg-[#F5B301] p-3 rounded-full">
                    <flux:icon.star class="w-8 h-8 text-[#3B0A45]" />
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- My Training Programs --}}
        <div class="lg:col-span-2 bg-[#512B58] rounded-lg shadow-lg p-6 border border-[#F5B301]/30">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-white">{{ __('My Training Programs') }}</h2>
                <a href="{{ route('programs.index') }}" class="text-[#F5B301] hover:text-[#F5B301]/80 text-sm font-medium">
                    {{ __('View All') }} →
                </a>
            </div>
            
            @if($activePrograms->count() > 0)
                <div class="space-y-4">
                    @foreach($activePrograms as $program)
                        <div class="bg-[#3B0A45]/50 rounded-lg p-4 border border-[#F5B301]/20">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h3 class="font-medium text-white">{{ $program->program_type ?? 'Training Program' }}</h3>
                                    <p class="text-sm text-[#D1C4E9]/70 mt-1">{{ __('Maid:') }} {{ $program->maid->full_name ?? '—' }}</p>
                                    <div class="flex items-center gap-4 mt-2">
                                        <span class="text-xs text-[#D1C4E9]/60">
                                            {{ __('Hours:') }} {{ $program->hours_completed ?? 0 }} / {{ $program->hours_required ?? '—' }}
                                        </span>
                                        <span class="text-xs text-[#D1C4E9]/60">
                                            {{ __('Starts:') }} {{ optional($program->start_date)->format('M j, Y') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-[#F5B301]/20 text-[#F5B301]">
                                        {{ ucfirst($program->status ?? 'active') }}
                                    </span>
                                </div>
                            </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <flux:icon.academic-cap class="w-16 h-16 mx-auto text-[#D1C4E9]/30" />
                    <p class="mt-4 text-[#D1C4E9]/60">{{ __('No active training programs assigned') }}</p>
                </div>
            @endif
        </div>

        {{-- Upcoming Sessions --}}
        <div class="bg-[#512B58] rounded-lg shadow-lg p-6 border border-[#F5B301]/30">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-white">{{ __('Upcoming Sessions') }}</h2>
                <a href="{{ route('bookings.index') }}" class="text-[#F5B301] hover:text-[#F5B301]/80 text-sm font-medium">
                    {{ __('View All') }} →
                </a>
            </div>
            
            @if($upcomingSessions->count() > 0)
                <div class="space-y-3">
                    @foreach($upcomingSessions as $session)
                        <div class="bg-[#3B0A45]/50 rounded-lg p-3 border border-[#F5B301]/20">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h4 class="font-medium text-white text-sm">{{ $session->maid->full_name ?? 'Maid' }}</h4>
                                    <p class="text-xs text-[#D1C4E9]/70">{{ $session->program_type ?? 'Training' }}</p>
                                    <p class="text-xs text-[#F5B301] mt-1">
                                        {{ optional($session->start_date)->format('M j, Y') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-[#F5B301]/20 text-[#F5B301]">
                                        {{ ucfirst($session->status ?? 'scheduled') }}
                                    </span>
                                </div>
                            </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <flux:icon.calendar-days class="w-16 h-16 mx-auto text-[#D1C4E9]/30" />
                    <p class="mt-4 text-[#D1C4E9]/60">{{ __('No upcoming sessions') }}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Recent Evaluations & Support --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Recent Evaluations --}}
        <div class="bg-[#512B58] rounded-lg shadow-lg p-6 border border-[#F5B301]/30">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-white">{{ __('Recent Evaluations') }}</h2>
                <a href="{{ route('evaluations.index') }}" class="text-[#F5B301] hover:text-[#F5B301]/80 text-sm font-medium">
                    {{ __('View All') }} →
                </a>
            </div>
            
            @if($recentEvaluations->count() > 0)
                <div class="space-y-4">
                    @foreach($recentEvaluations as $evaluation)
                        <div class="bg-[#3B0A45]/50 rounded-lg p-4 border border-[#F5B301]/20">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h4 class="font-medium text-white">{{ $evaluation->maid->full_name ?? 'Maid' }}</h4>
                                    <p class="text-sm text-[#D1C4E9]/70">{{ $evaluation->program->program_type ?? 'Program' }}</p>
                                    <div class="flex items-center gap-2 mt-2">
                                        <div class="flex items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                <flux:icon.star class="w-4 h-4 {{ $i <= round($evaluation->overall_rating ?? 0) ? 'text-[#F5B301]' : 'text-[#D1C4E9]/30' }}" />
                                            @endfor
                                        </div>
                                        <span class="text-xs text-[#D1C4E9]/60">{{ $evaluation->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-lg font-bold text-[#F5B301]">{{ number_format($evaluation->overall_rating ?? 0, 1) }}/5</span>
                                </div>
                            </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <flux:icon.star class="w-16 h-16 mx-auto text-[#D1C4E9]/30" />
                    <p class="mt-4 text-[#D1C4E9]/60">{{ __('No recent evaluations') }}</p>
            </div>
            @endif
        </div>

        {{-- Support & Tickets --}}
        <div class="bg-[#512B58] rounded-lg shadow-lg p-6 border border-[#F5B301]/30">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-white">{{ __('Support & Tickets') }}</h2>
                <a href="{{ route('tickets.index') }}" class="text-[#F5B301] hover:text-[#F5B301]/80 text-sm font-medium">
                    {{ __('View All') }} →
                </a>
            </div>
            
            <div class="space-y-4">
                {{-- My Tickets Count --}}
                <div class="bg-[#3B0A45]/50 rounded-lg p-4 border border-[#F5B301]/20">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-[#F5B301] rounded-lg flex items-center justify-center">
                                <flux:icon.ticket class="w-5 h-5 text-[#3B0A45]" />
                            </div>
                            <div>
                                <h4 class="font-medium text-white">{{ __('My Support Tickets') }}</h4>
                                <p class="text-sm text-[#D1C4E9]/70">{{ __('Issues and requests') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-2xl font-bold text-[#F5B301]">{{ $myTickets }}</span>
                            <p class="text-xs text-[#D1C4E9]/60">{{ __('Open tickets') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="space-y-2">
                    <a href="{{ route('tickets.create') }}" class="block w-full bg-[#F5B301] hover:bg-[#F5B301]/80 text-[#3B0A45] font-medium py-3 px-4 rounded-lg text-center transition-colors">
                        {{ __('Create Support Ticket') }}
                    </a>
                    <a href="{{ route('tickets.inbox') }}" class="block w-full bg-[#3B0A45]/50 hover:bg-[#3B0A45]/70 text-white font-medium py-3 px-4 rounded-lg text-center transition-colors border border-[#F5B301]/30">
                        {{ __('View My Inbox') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
