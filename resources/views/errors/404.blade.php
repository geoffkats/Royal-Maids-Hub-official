@php
    $title = __('404 Not Found');
    $code = '404';
    $headline = __('We could not find that page');
    $message = __('The page you are looking for may have been moved or removed.');
    $suggestion = __('Check the link for typos or head back to a familiar section.');
    $statusLabel = __('Missing Page');
@endphp

@include('errors.partials.base', compact(
    'title',
    'code',
    'headline',
    'message',
    'suggestion',
    'statusLabel'
))
