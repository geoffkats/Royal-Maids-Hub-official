@php
    $title = __('401 Unauthorized');
    $code = '401';
    $headline = __('Please sign in');
    $message = __('You need to be signed in to access this page.');
    $suggestion = __('Sign in and try again. If your session expired, logging in will restore access.');
    $statusLabel = __('Authentication Required');
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
