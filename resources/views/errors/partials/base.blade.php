<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-gradient-to-br from-[#3B0A45] via-[#512B58] to-[#2D1B69] text-white">
    <div class="relative min-h-screen">
        <div class="absolute inset-0 opacity-30" style="background-image: radial-gradient(circle at 15% 15%, rgba(245, 179, 1, 0.25), transparent 45%), radial-gradient(circle at 85% 30%, rgba(209, 196, 233, 0.2), transparent 45%);"></div>
        <div class="relative z-10 mx-auto flex min-h-screen max-w-3xl items-center px-6 py-12">
            <div class="w-full rounded-2xl border border-[#F5B301]/30 bg-[#512B58]/90 p-8 shadow-2xl backdrop-blur">
                <div class="flex flex-col gap-6 md:flex-row md:items-start md:justify-between">
                    <div class="flex items-start gap-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-[#F5B301]/20">
                            <span class="text-lg font-semibold text-[#F5B301]">{{ $code }}</span>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-white">{{ $headline }}</h1>
                            <p class="mt-1 text-sm text-[#D1C4E9]">{{ $message }}</p>
                        </div>
                    </div>
                    @if (!empty($statusLabel))
                        <div class="rounded-full border border-white/10 bg-white/10 px-4 py-2 text-xs uppercase tracking-wide text-[#D1C4E9]">
                            {{ $statusLabel }}
                        </div>
                    @endif
                </div>

                @if (!empty($suggestion))
                    <p class="mt-6 text-sm text-[#D1C4E9]">{{ $suggestion }}</p>
                @endif

                <div class="mt-6 grid gap-3 text-sm text-[#D1C4E9]">
                    <div class="rounded-lg border border-white/10 bg-white/5 p-4">
                        <p class="text-sm font-semibold text-white">{{ __('What you can do next') }}</p>
                        <ul class="mt-2 space-y-1">
                            <li>{{ __('Check the address or navigate from the dashboard.') }}</li>
                            <li>{{ __('Return to the previous page if you got here by mistake.') }}</li>
                            <li>{{ __('Contact support if you need assistance.') }}</li>
                        </ul>
                    </div>
                </div>

                <div class="mt-6 flex flex-wrap gap-3">
                    @if (!empty($primaryActionUrl) && !empty($primaryActionLabel))
                        <a href="{{ $primaryActionUrl }}" class="rounded-lg bg-[#F5B301] px-5 py-2 text-sm font-semibold text-[#3B0A45] transition hover:bg-[#F5B301]/90">
                            {{ $primaryActionLabel }}
                        </a>
                    @endif
                    <a href="{{ route('home') }}" class="rounded-lg border border-[#F5B301]/40 px-5 py-2 text-sm font-semibold text-[#F5B301] transition hover:bg-[#F5B301]/10">
                        {{ __('Go to Home') }}
                    </a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="rounded-lg border border-white/20 px-5 py-2 text-sm font-semibold text-white transition hover:bg-white/10">
                            {{ __('Open Dashboard') }}
                        </a>
                    @endauth
                    <a href="{{ url()->previous() }}" class="rounded-lg border border-white/20 px-5 py-2 text-sm font-semibold text-white transition hover:bg-white/10">
                        {{ __('Go Back') }}
                    </a>
                    <a href="{{ route('contact.public') }}" class="rounded-lg border border-white/20 px-5 py-2 text-sm font-semibold text-white transition hover:bg-white/10">
                        {{ __('Contact Support') }}
                    </a>
                </div>

                <div class="mt-6 text-xs text-[#D1C4E9]/80">
                    <span>{{ __('Reference') }}: {{ $code }}</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
