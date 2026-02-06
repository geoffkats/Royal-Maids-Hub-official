@php
    $title = __('419 Page Expired');
    $code = '419';
    $headline = __('Session expired');
    $message = __('Your session has expired. Please refresh and try again.');
    $suggestion = __('Logging in again will restore your session and keep your work secure.');
    $statusLabel = __('Session Timeout');
    $primaryActionUrl = route('login');
    $primaryActionLabel = __('Sign In');
@endphp

@include('errors.partials.base', compact(
    'title',
    'code',
    'headline',
    'message',
    'suggestion',
    'statusLabel',
    'primaryActionUrl',
    'primaryActionLabel'
))
