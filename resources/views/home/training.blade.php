@extends('components.layouts.simple')

@section('title', 'Training Programs - Royal Maids Hub')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#512B58] via-[#3B0A45] to-[#2D1B69]">
    <section class="relative py-16 lg:py-24 overflow-hidden"
             style="background-image: url('{{ asset('storage/web-site-images/hero.jpg') }}'); background-size: cover; background-position: center; background-attachment: fixed;">
        <div class="absolute inset-0 bg-gradient-to-r from-[#512B58]/85 to-[#3B0A45]/85"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center text-white">
                <h1 class="text-4xl lg:text-5xl font-bold mb-4">Training Programs</h1>
                <p class="text-lg text-[#D1C4E9]">Discover how we prepare our maids with practical skills and quality standards.</p>
            </div>
        </div>
    </section>

    <section class="py-16">
        <div class="container mx-auto px-4">
            <!-- Programs Overview -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white/10 backdrop-blur-sm border border-[#F5B301]/30 rounded-xl p-6 text-white">
                    <h3 class="text-xl font-semibold mb-2">Housekeeping</h3>
                    <ul class="text-[#D1C4E9] text-sm list-disc list-inside space-y-1">
                        <li>Deep cleaning standards (kitchens, baths, floors)</li>
                        <li>Laundry care and fabric handling</li>
                        <li>Organization and decluttering</li>
                        <li>Hygiene and disinfection protocols</li>
                    </ul>
                </div>
                <div class="bg-white/10 backdrop-blur-sm border border-[#F5B301]/30 rounded-xl p-6 text-white">
                    <h3 class="text-xl font-semibold mb-2">Childcare Basics</h3>
                    <ul class="text-[#D1C4E9] text-sm list-disc list-inside space-y-1">
                        <li>Daily routines and positive engagement</li>
                        <li>Age-appropriate safety practices</li>
                        <li>Feeding, nap time and play</li>
                        <li>Emergency response basics</li>
                    </ul>
                </div>
                <div class="bg-white/10 backdrop-blur-sm border border-[#F5B301]/30 rounded-xl p-6 text-white">
                    <h3 class="text-xl font-semibold mb-2">Cooking & Nutrition</h3>
                    <ul class="text-[#D1C4E9] text-sm list-disc list-inside space-y-1">
                        <li>Meal planning and budgeting</li>
                        <li>Safe food storage and handling</li>
                        <li>Local and international menus</li>
                        <li>Dietary needs and allergies</li>
                    </ul>
                </div>
            </div>

            <!-- Extended Programs -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                <div class="bg-white/10 backdrop-blur-sm border border-[#F5B301]/30 rounded-xl p-6 text-white">
                    <h3 class="text-xl font-semibold mb-2">Elderly Care</h3>
                    <ul class="text-[#D1C4E9] text-sm list-disc list-inside space-y-1">
                        <li>Mobility support and companionship</li>
                        <li>Medication reminders</li>
                        <li>Nutrition and hydration monitoring</li>
                        <li>Household safety checks</li>
                    </ul>
                </div>
                <div class="bg-white/10 backdrop-blur-sm border border-[#F5B301]/30 rounded-xl p-6 text-white">
                    <h3 class="text-xl font-semibold mb-2">Pet-Friendly Homes</h3>
                    <ul class="text-[#D1C4E9] text-sm list-disc list-inside space-y-1">
                        <li>Basic pet care and hygiene</li>
                        <li>Cleaning with pet-safe products</li>
                        <li>Managing shedding and odors</li>
                        <li>Pet-safety in the home</li>
                    </ul>
                </div>
                <div class="bg-white/10 backdrop-blur-sm border border-[#F5B301]/30 rounded-xl p-6 text-white">
                    <h3 class="text-xl font-semibold mb-2">Customer Service</h3>
                    <ul class="text-[#D1C4E9] text-sm list-disc list-inside space-y-1">
                        <li>Professional communication</li>
                        <li>Confidentiality and trust</li>
                        <li>Time management and reporting</li>
                        <li>Handling feedback</li>
                    </ul>
                </div>
            </div>

            <!-- Learning Model -->
            <div class="mt-10 grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white/10 backdrop-blur-sm border border-[#F5B301]/30 rounded-2xl p-6 text-white">
                    <h3 class="text-2xl font-bold mb-3">How Training Works</h3>
                    <ol class="text-[#D1C4E9] text-sm list-decimal list-inside space-y-2">
                        <li>Assessment: baseline skills and placement level</li>
                        <li>Modules: hands-on practice with trainer supervision</li>
                        <li>Evaluation: checklists, demos and scenario tests</li>
                        <li>Certification: completion badge and placement readiness</li>
                    </ol>
                </div>
                <div class="bg-white/10 backdrop-blur-sm border border-[#F5B301]/30 rounded-2xl p-6 text-white">
                    <h3 class="text-2xl font-bold mb-3">Certification</h3>
                    <p class="text-[#D1C4E9] text-sm">Graduates receive a Royal Maids certificate and profile badge indicating modules completed and trainer feedback highlights.</p>
                </div>
            </div>

            <!-- CTA -->
            <div class="mt-10 text-center">
                <a href="{{ route('booking.public') }}" class="inline-flex items-center bg-gradient-to-r from-[#F5B301] to-[#FFD700] text-[#512B58] font-bold px-6 py-3 rounded-xl hover:from-[#FFD700] hover:to-[#F5B301] transition">Start Booking</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    @include('components.home.footer')
</div>
@endsection


