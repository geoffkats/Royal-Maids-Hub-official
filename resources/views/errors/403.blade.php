@php
    $title = __('403 Forbidden');
    $code = '403';
    $headline = __('Access denied');
    $message = __('You do not have permission to view this page.');
    $suggestion = __('If you believe you should have access, contact an administrator to update your permissions.');
    $statusLabel = __('Permission Required');
@endphp

@include('errors.partials.base', compact(
    'title',
    'code',
    'headline',
    'message',
    'suggestion',
    'statusLabel'
))
