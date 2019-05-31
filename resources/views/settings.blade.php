<div class="cabinet-sidebar">
   
    <ul class="cabinet-menu-listing">
        <li><a href="" class="account">My account</a></li>
        <li><a href="{{route('deposit', ['lang' => $currentLang])}}" class="deposite">{{ trans('casino.deposit') }}</a></li>
        <li><a href="{{route('withdraw', ['lang' => $currentLang])}}" class="withdraw">{{ trans('casino.withdraw') }}</a></li>
        <li><a href="{{route('bonus', ['lang' => $currentLang])}}" class="bonus">{{ trans('casino.get_bonus') }}</a></li>
        <li><a href="{{route('settings', ['lang' => $currentLang])}}" class="setting">{{ trans('casino.settings') }}</a></li>
    </ul>

    <script>

    </script>
</div>