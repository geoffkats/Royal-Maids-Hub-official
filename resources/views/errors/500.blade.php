@php
    $title = __('500 Server Error');
    $code = '500';
    $headline = __('Something went wrong');
    $message = __('An unexpected error occurred while processing your request.');
    $suggestion = __('Please try again in a few minutes. If the issue persists, contact support.');
    $statusLabel = __('Server Error');
@endphp

@include('errors.partials.base', compact(
    'title',
    'code',
    'headline',
    'message',
    'suggestion',
    'statusLabel'
))
