<div class="cabinet-sidebar">
    @if(!isset($not_credit))
    <div class="credits-block">
        <i class="bitcoin-icon"></i>
        <span class="balance"><span class="value">{{Auth::user()->getBalance()}}</span> {{translate('credits')}}</span>
        <a class="add-credits-btn"><span class="text">{{translate('Add Credits')}}</span></a>
    </div>
    @endif
    <ul class="cabinet-menu-listing">
        <li><a href="{{route('deposit')}}" class="deposite">{{translate('Deposit')}}</a></li>
        <li><a href="{{route('withdraw')}}" class="withdraw">{{translate('Withdraw')}}</a></li>
        <li><a href="{{route('bonus')}}" class="bonus">{{translate('Get Bonus')}}</a></li>
        <li><a href="{{route('settings')}}" class="setting">{{translate('Settings')}}</a></li>
    </ul>

    <script>

    </script>
</div>