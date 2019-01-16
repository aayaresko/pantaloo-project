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
        <li><a href="{{route('deposit')}}" class="deposite">{{ trans('casino.deposit') }}</a></li>
        <li><a href="{{route('withdraw')}}" class="withdraw">{{ trans('casino.withdraw') }}</a></li>
        <li><a href="{{route('bonus')}}" class="bonus">{{ trans('casino.get_bonus') }}</a></li>
        <li><a href="{{route('settings')}}" class="setting">{{ trans('casino.settings') }}</a></li>
    </ul>

    <script>

    </script>
</div>