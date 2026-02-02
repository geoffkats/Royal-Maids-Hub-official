@php
    $settings = \App\Models\CompanySetting::current();
    $companyName = $settings->company_name ?? config('app.name', 'Laravel');
    
    // If $title is set and doesn't already contain the company name, append it
    if (isset($title)) {
        $pageTitle = $title;
        // Only append company name if it's not already in the title
        if (!str_contains($title, $companyName)) {
            $pageTitle = $title . ' - ' . $companyName;
        }
    } else {
        // Use meta_title or company_name as fallback
        $pageTitle = $settings->meta_title ?? $companyName;
    }
@endphp

<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $pageTitle }}</title>

<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- SEO Meta Tags -->
@if($settings->meta_description)
    <meta name="description" content="{{ $settings->meta_description }}">
@endif

@if($settings->meta_keywords)
    <meta name="keywords" content="{{ $settings->meta_keywords }}">
@endif

@if($settings->meta_author)
    <meta name="author" content="{{ $settings->meta_author }}">
@endif

@if($settings->meta_robots)
    <meta name="robots" content="{{ $settings->meta_robots }}">
@endif

<!-- Open Graph / Facebook -->
<meta property="og:type" content="{{ $settings->og_type ?? 'website' }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="{{ $settings->og_title ?? $title ?? config('app.name') }}">
@if($settings->og_description)
    <meta property="og:description" content="{{ $settings->og_description }}">
@endif
@if($settings->og_image_url)
    <meta property="og:image" content="{{ $settings->og_image_url }}">
@endif

<!-- Twitter Card -->
<meta name="twitter:card" content="{{ $settings->twitter_card ?? 'summary_large_image' }}">
<meta name="twitter:url" content="{{ url()->current() }}">
<meta name="twitter:title" content="{{ $settings->og_title ?? $title ?? config('app.name') }}">
@if($settings->og_description)
    <meta name="twitter:description" content="{{ $settings->og_description }}">
@endif
@if($settings->og_image_url)
    <meta name="twitter:image" content="{{ $settings->og_image_url }}">
@endif
@if($settings->twitter_site)
    <meta name="twitter:site" content="{{ $settings->twitter_site }}">
@endif
@if($settings->twitter_creator)
    <meta name="twitter:creator" content="{{ $settings->twitter_creator }}">
@endif

<!-- Google Site Verification -->
@if($settings->google_site_verification)
    <meta name="google-site-verification" content="{{ str_replace('google-site-verification=', '', $settings->google_site_verification) }}">
@endif

<!-- Favicon -->
@if($settings->favicon_url)
    <link rel="icon" href="{{ $settings->favicon_url }}" sizes="any">
@else
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
@endif
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance

<!-- Google Analytics -->
@if($settings->google_analytics_id)
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $settings->google_analytics_id }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $settings->google_analytics_id }}');
    </script>
@endif

<!-- Google Tag Manager -->
@if($settings->google_tag_manager_id)
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','{{ $settings->google_tag_manager_id }}');</script>
@endif

<!-- Facebook Pixel -->
@if($settings->facebook_pixel_id)
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '{{ $settings->facebook_pixel_id }}');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
        src="https://www.facebook.com/tr?id={{ $settings->facebook_pixel_id }}&ev=PageView&noscript=1"
    /></noscript>
@endif

<!-- Custom Head Scripts -->
@if($settings->head_scripts)
    {!! $settings->head_scripts !!}
@endif
