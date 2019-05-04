@extends('layouts.app')

@section('title')
    {{translate('Withdraw')}}
@endsection

@section('content')
    <div class="cabinet-block"
         style="background: #000 url('/media/images/bg/deposit-bg-dark.jpg') center no-repeat; background-size: cover;">
        <div class="cabinet-entry cabinetMod">
            <div class="main-content">
                <div class="page-heading">
                    <h1 class="page-title">{{ trans('casino.withdraw') }}</h1>
                </div>
                <div class="userBalanceWrap">
                    <i class="bitcoin-icon"></i>
                    <div class="userBalanceCol leftBorder">
                        <span class="userBalanceTxt">{{ trans('casino.balance') }}</span>
                        <p class="balancebox-getbalance">{{Auth::user()->getBalance()}} m{{strtoupper(Auth::user()->currency->title)}}</p>
                    </div>
                    <div class="userBalanceCol leftBorder">
                        <span class="userBalanceTxt">{{ trans('casino.real_balance') }}</span>
                        <p class="balancebox-getrealbalance">{{Auth::user()->getRealBalance()}} m{{strtoupper(Auth::user()->currency->title)}}</p>
                    </div>
                    
                    <div class="userBalanceCol">
                        <span class="userBalanceTxt">{{ trans('casino.bonus_balance') }}</span>
                        <p class="balancebox-getbonusbalance">{{Auth::user()->getBonusBalance()}} m{{strtoupper(Auth::user()->currency->title)}}</p>
                    </div>
                    <a class="add-credits-btn AddCreditBtn" href="{{route('deposit', ['lang' => $currentLang])}}"><span
                                        class="text">{{ trans('casino.add_credits') }}</span></a>
                </div>
                <div class="text-block-withdraw">
                    <p class="descr">{{ trans('casino.transfer_wallet_address') }}</p>
                    <p class="descr">{{ trans('casino.have_millibitcoins', ['availableBalance' => Auth::user()->getRealBalance()]) }}</p>
                </div>
                <div class="main-content-entry">
                    <div class="withdraw-entry">
                        <div class="middle-block">
                            <center>
                                @foreach($errors->all() as $error)
                                    {{$error}}<br>
                                @endforeach
                                <br><br>
                            </center>
                            <form action="" method="POST">               
                                <span class="text">{{ trans('casino.your_bitcoin_address') }}</span>
                                <input type="text" name="address">
                            
                            
                                <span class="text">{{ trans('casino.amount_mbtc') }}</span>
                                <input type="text" name="sum">
                                <button class="withdraw">{{ trans('casino.withdraw') }}</button>
                                
                                {{csrf_field()}}
                            </form>
                        </div>
                        @if(count($transactions) > 0)
                            <div class="btn-history-block">
                                <a href="#" class="open-history-link">{{ trans('casino.open_history') }}</a>
                            </div>
                        @endif
                        <div class="bottom-block">
                            @if(count($transactions) > 0) <h3
                                    class="title">{{ trans('casino.your_withdraws') }}</h3> @endif
                            @include('transactions')
                        </div>
                    </div>
                </div>
                <div class='tableTransactionsWrapper'>
                    <p class="descr">Your Withdraws</p>
                    <table id="transactionsTable" class="display">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Transaction ID</th>
                                <th>Status</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                    </table>
                    <button class="loadMoredataTableBtn">more</button>
                    <hr class="devider">
                </div>
            </div>
            @include('settings')
        </div>
    </div>
    </div>

    <footer class="footer footer-home">
        <div class="bitcoin-block">
            <span class="bitcoin-msg"><i class="bitcoin-icon"></i> We work only with bitcoin</span>
        </div>
        <div class="msg-block">
            <span class="msg">{{ trans('casino.do_you_want_to_play') }}</span>
        </div>
        <div class="games-listing-block">
            <ul class="games-listing">
                @include('footer_links')
            </ul>
        </div>
        <div class="footer-copyrights">
            <ul class="footerLinks">
                <li class="rightReservedTxt">© All rights reserved</li>
                <li><a href="{{$partnerPage}}" class="afiliate" target="_blank">{{ trans('casino.affiliates') }}</a></li>
                <li><a target="_blank" href="{{route('support', ['lang' => $currentLang])}}" class="support">{{ trans('casino.frq') }}</a></li>
                <li><a href="#reg-terms" class="reg-terms">{{ trans('casino.accept_the_terms_link') }}</a></li>
                <li><a href="#uls" class="usl-link">{{ trans('casino.terms') }}</a></li>
            </ul>
        </div>
        
    </footer>
     <div class="hidden">
        <div id="uls">
            {!! trans('casino.bonus.term') !!}
        </div>
    </div>

    <div class="hidden">
        <div id="reg-terms">
            {!! trans('casino.terms_conditions') !!}
        </div>
    </div>
@endsection