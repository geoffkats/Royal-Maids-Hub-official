@extends('components.layouts.simple')

@section('title', 'Quality Assurance')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#512B58] via-[#3B0A45] to-[#2D1B69]">
    <section class="relative py-16 lg:py-24 overflow-hidden"
             style="background-image: url('{{ asset('storage/web-site-images/hero.jpg') }}'); background-size: cover; background-position: center; background-attachment: fixed;">
        <div class="absolute inset-0 bg-gradient-to-r from-[#512B58]/85 to-[#3B0A45]/85"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center text-white">
                <h1 class="text-4xl lg:text-5xl font-bold mb-4">Quality Assurance</h1>
                <p class="text-lg text-[#D1C4E9]">Our commitment to safety, professionalism and consistent service.</p>
            </div>
        </div>
    </section>

    <section class="py-16">
        <div class="container mx-auto px-4 space-y-10">
            <!-- Pillars -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white/10 backdrop-blur-sm border border-[#F5B301]/30 rounded-xl p-6 text-white">
                    <h3 class="text-xl font-semibold mb-2">Screening & Vetting</h3>
                    <ul class="text-[#D1C4E9] text-sm list-disc list-inside space-y-1">
                        <li>National ID verification and reference checks</li>
                        <li>Background review and work history validation</li>
                        <li>Initial interview and skills assessment</li>
                    </ul>
                </div>
                <div class="bg-white/10 backdrop-blur-sm border border-[#F5B301]/30 rounded-xl p-6 text-white">
                    <h3 class="text-xl font-semibold mb-2">Ongoing Evaluations</h3>
                    <ul class="text-[#D1C4E9] text-sm list-disc list-inside space-y-1">
                        <li>Trainer scorecards and periodic reviews</li>
                        <li>Client feedback loops and ratings</li>
                        <li>Remedial coaching and upskilling</li>
                    </ul>
                </div>
                <div class="bg-white/10 backdrop-blur-sm border border-[#F5B301]/30 rounded-xl p-6 text-white">
                    <h3 class="text-xl font-semibold mb-2">Customer Support</h3>
                    <ul class="text-[#D1C4E9] text-sm list-disc list-inside space-y-1">
                        <li>Dedicated support channel and SLAs</li>
                        <li>Issue logging, triage and resolution</li>
                        <li>Replacement policy where applicable</li>
                    </ul>
                </div>
            </div>

            <!-- Safety & Compliance -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white/10 backdrop-blur-sm border border-[#F5B301]/30 rounded-2xl p-6 text-white">
                    <h3 class="text-2xl font-bold mb-3">Health, Safety & Conduct</h3>
                    <ul class="text-[#D1C4E9] text-sm list-disc list-inside space-y-2">
                        <li>Hygiene standards, use of safe cleaning agents</li>
                        <li>Household safety checks and hazard awareness</li>
                        <li>Respectful conduct, confidentiality and professionalism</li>
                        <li>Clear escalation path for any incident</li>
                    </ul>
                </div>
                <div class="bg-white/10 backdrop-blur-sm border border-[#F5B301]/30 rounded-2xl p-6 text-white">
                    <h3 class="text-2xl font-bold mb-3">Data Protection & Privacy</h3>
                    <ul class="text-[#D1C4E9] text-sm list-disc list-inside space-y-2">
                        <li>Limited-purpose use of client data for bookings only</li>
                        <li>Secure storage for IDs and documents</li>
                        <li>Access controls and staff training</li>
                        <li>Compliance with applicable laws (UDPPA/GDPR/CPRA)</li>
                    </ul>
                    <p class="mt-3 text-sm">Read our <a href="{{ route('privacy.public') }}" class="text-[#F5D06A] underline">Privacy Policy</a>.</p>
                </div>
            </div>

            <!-- Audits & Guarantees -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="bg-white/10 backdrop-blur-sm border border-[#F5B301]/30 rounded-xl p-6 text-white">
                    <h3 class="text-xl font-semibold mb-2">Internal Audits</h3>
                    <p class="text-[#D1C4E9] text-sm">Periodic quality checks on training records, client feedback and deployment notes to ensure continuous improvement.</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm border border-[#F5B301]/30 rounded-xl p-6 text-white">
                    <h3 class="text-xl font-semibold mb-2">Service Guarantees</h3>
                    <p class="text-[#D1C4E9] text-sm">If expectations are not met, we will work with you to resolve issues, provide coaching or facilitate a replacement where policy permits.</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm border border-[#F5B301]/30 rounded-xl p-6 text-white">
                    <h3 class="text-xl font-semibold mb-2">Feedback First</h3>
                    <p class="text-[#D1C4E9] text-sm">Your feedback directly informs our coaching plans and helps us recognize excellent performance.</p>
                </div>
            </div>

            <!-- CTA -->
            <div class="text-center">
                <a href="{{ route('booking.public') }}" class="inline-flex items-center bg-gradient-to-r from-[#F5B301] to-[#FFD700] text-[#512B58] font-bold px-6 py-3 rounded-xl hover:from-[#FFD700] hover:to-[#F5B301] transition">Book a Maid</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    @include('components.home.footer')
</div>
@endsection


