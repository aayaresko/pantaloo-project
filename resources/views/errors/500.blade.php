@php

    //$request = \Illuminate\Support\Facades\Request();
    $segment = \Illuminate\Support\Facades\Request::segment(1);
    $languages = \Illuminate\Support\Facades\Config::get('getListLanguage');
    $lang = in_array($segment, $languages) ? $segment : \Illuminate\Support\Facades\Request::getPreferredLanguage($languages);
    $user = \Illuminate\Support\Facades\Auth::check() ? \Illuminate\Support\Facades\Request::user() : null;
    $email = !is_null($user) ? $user->email : '';

@endphp
<style>
    body {
        margin: 0px;
    }
    .close, .powered-by {
        display: none;
    }

    .btn {
        float: right !important;
        margin-right: 0px !important;
    }
</style>
<div class="content" style="width:100%;height:100%;background: url(/images/bp.jpg);opacity:0.8;">

    @if(app()->bound('sentry') && app('sentry')->getLastEventId())
        <script src="https://browser.sentry-cdn.com/5.2.1/bundle.min.js" crossorigin="anonymous"></script>
        <script>
            window.originSentry = {};
            window.sentryEmbedCallback = function (embed) {
                window.originSentry.close = embed.close.bind(embed);
                embed.close = function () {
                    console.log('close pls!')
                    //window.originSentry.close();
                    //if (window.sentrySubmitFlag) {
                    //this.element.parentNode.removeChild(this.element);
                    //}
                };
                console.log(embed);
            }
        </script>
        <script>
            Sentry.init({dsn: '{{ config('sentry.dsn','') }}'});
            var dialog = function () {
                Sentry.showReportDialog({
                    eventId: '{{ app('sentry')->getLastEventId() }}',
                    lang: '{{ $lang }}',
                    user: {
                        email: '{{ $email }}'
                    },
                    onLoad: function () {
                        console.log('Load!');
                        window.sentrySubmitFlag = false;
                    }
                });
            };
            dialog();
        </script>
    @endif
</div>
