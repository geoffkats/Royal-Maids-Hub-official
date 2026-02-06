@php
    $maidName = $booking->maid?->full_name ?? __('Not assigned');
    $packageName = $booking->package?->name ?? __('Not specified');
@endphp

<p>{{ __('Hello') }},</p>

<p>{{ __('Thank you for choosing Royal Maids Hub. Please share your feedback using the link below:') }}</p>

<p><a href="{{ $url }}">{{ __('Complete Client Evaluation') }}</a></p>

<p>{{ __('Booking ID') }}: #{{ $booking->id }}</p>
<p>{{ __('Maid') }}: {{ $maidName }}</p>
<p>{{ __('Package') }}: {{ $packageName }}</p>

<p>{{ __('This link may expire, so please submit your feedback as soon as possible.') }}</p>

<p>{{ __('Thank you for your time!') }}</p>
