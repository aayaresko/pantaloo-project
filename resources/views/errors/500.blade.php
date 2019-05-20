@php

$segment = \Illuminate\Support\Facades\Request::segment(1);
$languages = \Illuminate\Support\Facades\Config::get('getListLanguage');
$lang = in_array($segment, $languages) ? $segment : 'en';

@endphp

<div class="content">
    <div class="title">Something went wrong.</div>

    @if(app()->bound('sentry') && app('sentry')->getLastEventId())
        <div class="subtitle">Error ID: </div>
        <script src="https://browser.sentry-cdn.com/5.2.1/bundle.min.js" crossorigin="anonymous"></script>
        <script>
            Sentry.init({ dsn: '{{ config('sentry.dsn','') }}' });
            Sentry.showReportDialog({
                eventId: '{{ app('sentry')->getLastEventId() }}',
                lang: '{{ $lang }}'
            });
        </script>
    @endif
</div>
