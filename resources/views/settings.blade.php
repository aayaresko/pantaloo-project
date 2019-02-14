<div class="cabinet-sidebar">
    @if(!isset($not_credit))
        <div class="credits-block">
            <i class="bitcoin-icon"></i>
            <span class="balance"><span
                        class="value">{{ Auth::user()->getBalance() }}</span> {{ trans('casino.credits') }}</span>
            <a class="add-credits-btn"><span class="text">{{ trans('casino.add_credits') }}</span></a>
        </div>
    @endif
    <ul class="cabinet-menu-listing">
        <li><a href="{{route('deposit', ['lang' => $currentLang])}}" class="deposite">{{ trans('casino.deposit') }}</a></li>
        <li><a href="{{route('withdraw', ['lang' => $currentLang])}}" class="withdraw">{{ trans('casino.withdraw') }}</a></li>
        <li><a href="{{route('bonus', ['lang' => $currentLang])}}" class="bonus">{{ trans('casino.get_bonus') }}</a></li>
        <li><a href="{{route('settings', ['lang' => $currentLang])}}" class="setting">{{ trans('casino.settings') }}</a></li>
    </ul>

    <script>

    </script>
</div>