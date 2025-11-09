@extends('components.layouts.simple')

@section('title', 'Contact Us - Royal Maids Hub')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#512B58] via-[#3B0A45] to-[#2D1B69]">
    <!-- Header -->
    <section class="relative py-16 lg:py-24 overflow-hidden"
             style="background-image: url('{{ asset('storage/web-site-images/hero.jpg') }}'); background-size: cover; background-position: center; background-attachment: fixed;">
        <div class="absolute inset-0 bg-gradient-to-r from-[#512B58]/85 to-[#3B0A45]/85"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center text-white">
                <h1 class="text-4xl lg:text-5xl font-bold mb-4">Get in Touch</h1>
                <p class="text-lg text-[#D1C4E9]">Have questions or need help? Send us a message and we’ll respond promptly.</p>
            </div>
        </div>
    </section>

    <!-- Contact Form -->
    <section class="py-16">
        <div class="container mx-auto px-4 max-w-4xl">
            <div class="bg-white/10 backdrop-blur-sm border border-[#F5B301]/30 rounded-2xl shadow-2xl p-6 sm:p-10">
                @livewire('contact-form')
            </div>
        </div>
    </section>

    <!-- Footer -->
    @include('components.home.footer')
</div>
@endsection


