@php
    $maidName = $booking->maid?->full_name ?? __('Not assigned');
    $packageName = $booking->package?->name ?? __('Not specified');
@endphp

<p>{{ __('Hello') }} {{ $booking->client?->contact_person ?? '' }},</p>

<p>{{ __('Thank you for choosing Royal Maids Hub. Please share your feedback using the link below:') }}</p>

<p style="margin: 20px 0;">
    <a href="{{ $url }}" style="background-color: #512B58; color: #ffffff; padding: 10px 16px; text-decoration: none; border-radius: 6px; font-weight: 700; display: inline-block;">
        {{ __('Complete Client Evaluation') }}
    </a>
</p>

<p style="background-color: #f9fafb; padding: 12px; border-radius: 6px; color: #1f2937;">
    <strong>{{ __('Booking ID') }}:</strong> #{{ $booking->id }}<br />
    <strong>{{ __('Maid') }}:</strong> {{ $maidName }}<br />
    <strong>{{ __('Package') }}:</strong> {{ $packageName }}
</p>

<p>{{ __('This link may expire, so please submit your feedback as soon as possible.') }}</p>

<p>{{ __('Thank you for your time!') }}</p>

<p style="font-size: 12px; color: #6b7280;">&copy; {{ date('Y') }} Royal Maids Hub</p>
