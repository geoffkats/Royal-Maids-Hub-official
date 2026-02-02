@extends('components.layouts.simple')

@section('title', 'Our Maids')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#512B58] via-[#3B0A45] to-[#2D1B69]">
    <!-- Header -->
    <section class="relative py-16 lg:py-24 overflow-hidden"
             style="background-image: url('{{ asset('storage/web-site-images/hero.jpg') }}'); background-size: cover; background-position: center; background-attachment: fixed;">
        <div class="absolute inset-0 bg-gradient-to-r from-[#512B58]/85 to-[#3B0A45]/85"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-5xl mx-auto text-center">
                <h1 class="text-4xl lg:text-6xl font-bold text-white mb-4">Meet Our Maids</h1>
                <p class="text-lg lg:text-xl text-[#D1C4E9] max-w-3xl mx-auto">
                    Browse professionally trained and vetted domestic workers. Below are those currently available and those in our training program.
                </p>
                <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-6 max-w-2xl mx-auto">
                    <div class="bg-white/10 backdrop-blur-sm border border-[#F5B301]/30 rounded-xl p-6 text-white">
                        <div class="text-sm text-[#D1C4E9]">Available</div>
                        <div class="text-3xl font-bold text-[#F5B301]">{{ $availableCount ?? ($availableMaids->count() ?? 0) }}</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm border border-[#F5B301]/30 rounded-xl p-6 text-white">
                        <div class="text-sm text-[#D1C4E9]">In Training</div>
                        <div class="text-3xl font-bold text-[#F5B301]">{{ $inTrainingCount ?? ($inTrainingMaids->count() ?? 0) }}</div>
                    </div>
                </div>
                <div class="mt-8 flex items-center justify-center gap-4">
                    <a href="{{ route('booking.public') }}" class="bg-gradient-to-r from-[#F5B301] to-[#FFD700] text-white px-6 py-3 rounded-xl font-semibold hover:from-[#FFD700] hover:to-[#F5B301] transition-all shadow-lg">Book Now</a>
                    <a href="{{ route('home') }}" class="bg-white/20 backdrop-blur-sm text-white px-6 py-3 rounded-xl font-semibold hover:bg-white/30 border border-white/30 transition-all">Back to Home</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Available Maids -->
    <section class="py-16 bg-gradient-to-br from-[#512B58] to-[#3B0A45]">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl lg:text-3xl font-bold text-white">Available Maids</h2>
                <span class="text-[#D1C4E9]">Showing {{ ($availableMaids->count() ?? 0) }} maids</span>
            </div>

            @if(($availableMaids->count() ?? 0) > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($availableMaids as $maid)
                        <div class="group bg-white/90 rounded-2xl border border-[#F5B301]/30 shadow-lg hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                            <div class="p-6">
                                <div class="flex items-center gap-4">
                                    <div class="h-14 w-14 rounded-full overflow-hidden bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center">
                                        @if($maid->profile_image)
                                            <img src="{{ Storage::url($maid->profile_image) }}" class="h-full w-full object-cover" alt="{{ $maid->full_name }}">
                                        @else
                                            <span class="text-white text-lg font-bold">{{ strtoupper(substr($maid->first_name,0,1)) }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="text-lg font-semibold text-[#512B58]">{{ $maid->full_name }}</div>
                                        <div class="text-sm text-[#512B58]/70">{{ ucfirst(str_replace('_',' ', $maid->role ?? 'maid')) }}</div>
                                    </div>
                                </div>

                                <div class="mt-4 flex flex-wrap gap-2">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Available</span>
                                    @if($maid->work_status)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-neutral-100 text-neutral-800">{{ ucfirst($maid->work_status) }}</span>
                                    @endif
                                </div>

                                <div class="mt-4 grid grid-cols-2 gap-3 text-sm text-[#512B58]/80">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-[#F5B301]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                        <span>{{ ucfirst($maid->education_level ?? 'P.7') }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-[#F5B301]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span>{{ optional($maid->date_of_arrival)->format('M d, Y') ?: '—' }}</span>
                                    </div>
                                </div>

                                <div class="mt-6">
                                    <a href="{{ route('booking.public', ['maid_id' => $maid->id]) }}" class="inline-flex items-center justify-center w-full bg-gradient-to-r from-[#F5B301] to-[#FFD700] text-white px-4 py-2.5 rounded-lg font-semibold hover:from-[#FFD700] hover:to-[#F5B301] transition-all shadow-md">Book {{ $maid->first_name }}</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white/10 backdrop-blur-sm border border-[#F5B301]/30 rounded-xl p-8 text-center text-[#D1C4E9]">No available maids at the moment. Please check back soon.</div>
            @endif
        </div>
    </section>

    <!-- In Training -->
    <section class="py-16 bg-gradient-to-br from-[#512B58] to-[#3B0A45] border-t border-[#F5B301]/20">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl lg:text-3xl font-bold text-white">In Training</h2>
                <span class="text-[#D1C4E9]">Showing {{ ($inTrainingMaids->count() ?? 0) }} trainees</span>
            </div>

            @if(($inTrainingMaids->count() ?? 0) > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($inTrainingMaids as $maid)
                        <div class="group bg-white/90 rounded-2xl border border-[#F5B301]/30 shadow-lg hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                            <div class="p-6">
                                <div class="flex items-center gap-4">
                                    <div class="h-14 w-14 rounded-full overflow-hidden bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center">
                                        @if($maid->profile_image)
                                            <img src="{{ Storage::url($maid->profile_image) }}" class="h-full w-full object-cover" alt="{{ $maid->full_name }}">
                                        @else
                                            <span class="text-white text-lg font-bold">{{ strtoupper(substr($maid->first_name,0,1)) }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="text-lg font-semibold text-[#512B58]">{{ $maid->full_name }}</div>
                                        <div class="text-sm text-[#512B58]/70">{{ ucfirst(str_replace('_',' ', $maid->role ?? 'maid')) }}</div>
                                    </div>
                                </div>

                                <div class="mt-4 flex flex-wrap gap-2">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">In Training</span>
                                    @if($maid->work_status)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-neutral-100 text-neutral-800">{{ ucfirst($maid->work_status) }}</span>
                                    @endif
                                </div>

                                <div class="mt-4 grid grid-cols-2 gap-3 text-sm text-[#512B58]/80">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-[#F5B301]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                        <span>{{ ucfirst($maid->education_level ?? 'P.7') }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-[#F5B301]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span>{{ optional($maid->date_of_arrival)->format('M d, Y') ?: '—' }}</span>
                                    </div>
                                </div>

                                <div class="mt-6">
                                    <a href="{{ route('booking.public', ['maid_id' => $maid->id]) }}" class="inline-flex items-center justify-center w-full bg-white text-[#512B58] border border-[#F5B301]/40 px-4 py-2.5 rounded-lg font-semibold hover:bg-[#F5B301]/10 transition-all shadow-sm">Pre-book {{ $maid->first_name }}</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white/10 backdrop-blur-sm border border-[#F5B301]/30 rounded-xl p-8 text-center text-[#D1C4E9]">No trainees listed at the moment.</div>
            @endif
        </div>
    </section>

    <!-- CTA -->
    <section class="py-16 bg-gradient-to-br from-[#F5B301] to-[#FFD700]">
        <div class="container mx-auto px-4 text-center">
            <h3 class="text-3xl lg:text-4xl font-bold text-white mb-4">Found a match?</h3>
            <p class="text-white/95 mb-6 max-w-2xl mx-auto">Complete a quick booking request and our team will confirm availability and next steps.</p>
            <a href="{{ route('booking.public') }}" class="inline-flex items-center bg-[#512B58] text-white px-8 py-4 rounded-xl font-semibold hover:bg-[#3B0A45] transition-all shadow-xl">Start Booking</a>
        </div>
    </section>

    <!-- Footer -->
    @include('components.home.footer')
</div>
@endsection
