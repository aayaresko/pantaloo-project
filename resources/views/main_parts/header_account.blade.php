<div class="userBalanceWrap">
    <i class="bitcoin-icon"></i>
    <div class="userBalanceCol leftBorder">
        <span class="userBalanceTxt">{{ trans('casino.balance') }}</span>
        <p class="balancebox-getbalance">{{ $user->getBalance() }}
            {{ $currencyCode }}</p>
    </div>
    <div class="userBalanceCol leftBorder">
        <span class="userBalanceTxt">{{ trans('casino.real_balance') }}</span>
        <p class="balancebox-getrealbalance">{{ $user->getRealBalance() }}
            {{ $currencyCode }}</p>
    </div>

    <div class="userBalanceCol">
        <span class="userBalanceTxt">{{ trans('casino.bonus_balance') }}</span>
        <p class="balancebox-getbonusbalance">{{ $user->getBonusBalance() }}
            {{ $currencyCode }}</p>
    </div>

    <a class="add-credits-btn AddCreditBtn" href="{{route('deposit', ['lang' => $currentLang])}}">
        <span class="text">{{ trans('casino.add_credits') }}</span></a>
</div>