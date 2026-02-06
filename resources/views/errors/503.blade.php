@php
    $title = __('503 Service Unavailable');
    $code = '503';
    $headline = __('We are performing maintenance');
    $message = __('The service is temporarily unavailable while we complete maintenance.');
    $suggestion = __('Please check back shortly.');
    $statusLabel = __('Maintenance');
@endphp

@include('errors.partials.base', compact(
    'title',
    'code',
    'headline',
    'message',
    'suggestion',
    'statusLabel'
))
