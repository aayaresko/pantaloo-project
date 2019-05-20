@php

$segment = \Illuminate\Support\Facades\Request::segment(1);
$languages = \Illuminate\Support\Facades\Config::get('getListLanguage');
$lang = in_array($segment, $languages) ? $segment : 'en';
$user = \Illuminate\Support\Facades\Request::user();
//dump($user->email);

@endphp

<div class="content" style="width:100%;height:100%;background: url(/images/bp.jpg);opacity:0.8;">

    @if(app()->bound('sentry') && app('sentry')->getLastEventId())
        <script src="https://browser.sentry-cdn.com/5.2.1/bundle.min.js" crossorigin="anonymous"></script>
    <script>
        window.sentryEmbedCallback = function(embed){
            embed.close = function(){
                console.log('close pls!')
                if (window.sentrySubmitFlag){
                    this.element.parentNode.removeChild(this.element);
                }
            };
            console.log(embed);
        }
    </script>
        <script>
            Sentry.init({ dsn: '{{ config('sentry.dsn','') }}' });
            var dialog = function() {
                Sentry.showReportDialog({
                    eventId: '{{ app('sentry')->getLastEventId() }}',
                    lang: '{{ $lang }}',
                    user: {
                        email: '{{ $user->email }}'
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
