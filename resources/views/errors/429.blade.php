@php
    $title = __('429 Too Many Requests');
    $code = '429';
    $headline = __('Slow down for a moment');
    $message = __('You have sent too many requests in a short time.');
    $suggestion = __('Please wait a moment, then try again.');
    $statusLabel = __('Rate Limited');
@endphp

@include('errors.partials.base', compact(
    'title',
    'code',
    'headline',
    'message',
    'suggestion',
    'statusLabel'
))
