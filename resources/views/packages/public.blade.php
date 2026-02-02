@extends('components.layouts.simple')

@section('title', 'Our Packages')

@section('content')
<div class="min-h-screen bg-white">
    <!-- Header -->
    <section class="bg-gradient-to-br from-blue-50 to-indigo-100 py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4">Our Packages</h1>
                <p class="text-xl text-gray-600">
                    Choose the perfect package for your family's domestic service needs
                </p>
            </div>
        </div>
    </section>

    <!-- Packages Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            @if($packages->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($packages as $package)
                        <div class="relative bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden {{ $package->name === 'Gold' ? 'ring-2 ring-blue-500 scale-105' : '' }}">
                            @if($package->name === 'Gold')
                                <div class="absolute top-0 right-0 bg-blue-600 text-white px-4 py-2 text-sm font-semibold">
                                    Most Popular
                                </div>
                            @endif
                            
                            <div class="p-8">
                                <div class="text-center mb-6">
                                    <div class="w-16 h-16 bg-{{ $package->badge_color }}-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-{{ $package->badge_color }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $package->name }}</h3>
                                    <div class="text-4xl font-bold text-blue-600 mb-2">{{ $package->formatted_price }}</div>
                                    <p class="text-gray-600">Base family size: {{ $package->base_family_size }} members</p>
                                </div>
                                
                                <div class="mb-6">
                                    <h4 class="font-semibold text-gray-900 mb-3">Features:</h4>
                                    <ul class="space-y-2">
                                        @if($package->features)
                                            @foreach($package->features as $feature)
                                                <li class="flex items-center text-gray-600">
                                                    <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    {{ $feature }}
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                                
                                <div class="mb-6">
                                    <h4 class="font-semibold text-gray-900 mb-3">Training Includes:</h4>
                                    <ul class="space-y-2">
                                        @if($package->training_includes)
                                            @foreach($package->training_includes as $training)
                                                <li class="flex items-center text-gray-600">
                                                    <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                    </svg>
                                                    {{ $training }}
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                                
                                <div class="text-center">
                                    <p class="text-sm text-gray-500 mb-4">
                                        Additional members: {{ number_format($package->additional_member_cost) }} UGX/month each
                                    </p>
                                    <a href="{{ route('booking.public') }}" 
                                       class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                                        Choose {{ $package->name }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-600 text-lg">No packages available at the moment. Please check back later.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Ready to Get Started?</h2>
                <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                    Book your perfect domestic service package today and experience the Royal Maids Hub difference.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('booking.public') }}" 
                       class="bg-blue-600 text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-blue-700 transition-colors">
                        Book A Maid Now
                    </a>
                    <a href="{{ route('home') }}" 
                       class="border-2 border-blue-600 text-blue-600 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-blue-600 hover:text-white transition-colors">
                        Back to Home
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
