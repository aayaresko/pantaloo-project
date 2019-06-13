@extends('layouts.app')

@section('title', trans('DepositEvent'))



@section('content')
    <div class="cabinet-block"
         style="background: #000 url('/media/images/bg/deposit-bg-light.jpg') center no-repeat; background-size: cover;">
        <div class="cabinet-entry cabinetMod">
            <div class="main-content">
                <div class="page-heading">
                    <h1 class="page-title">{{ mb_convert_case(trans('casino.deposit'), MB_CASE_UPPER) }}</h1>
                    <!-- <span class="sub-text">{{ trans('casino.bonus_deposit') }}</span> -->
                    <!-- <a href="{{ route('bonus', ['lang' => $currentLang]) }}" class="bonuses-btn">{{ trans('casino.open_bonus') }}</a> -->
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
                <div class="main-content-entry">
                    <div class="deposit-entry">
                       
                        <div class="left-content">
                            <div class="text-block">
                                <p class="descr">{{ trans('casino.minimum_deposit') }}</p>
                                <p class="descr">(1 BTC = 1000 mBTC)</p>
                            </div>
                            <p class="descrTxt">{{ trans('casino.send_your_bitcoins') }}</p>
                            <div class="generated-key-wrapper">
                                <input type="text" class="generated-key" value="{{ $bitcoin_address }}">
                                
                                <button id="btnKey" class="generated-key-btn">{{ trans('casino.deposit_copy') }}</button>
                                <div class="copied">{{ trans('casino.deposit_copied') }}</div>
                            </div>
                        </div>
                        <div class="qr-code">
                            <img class="rounded"
                                 src="https://chart.googleapis.com/chart?chs=201x204&cht=qr&chl={{ $bitcoin_address }}&choe=UTF-8"
                                 alt="qr">
                        </div>
                    </div>
                    <div class="withdraw-entry" style="display: none;">
                        <div class="bottom-block">
                            @if(count($transactions) > 0) <h3 class="title">{{translate('Your Deposits')}}</h3> @endif
                            @include('transactions')
                        </div>

                    </div>
                </div>
                <div class='tableTransactionsWrapper'>
                    <p class="descr">{{translate('Your Deposits')}}</p>
                    <table id="transactionsTable" class="display">
                        <thead>
                            <tr>
                                <th>{{translate('casino.deposit_data')}}</th>
                                <th>{{translate('casino.transaction_id')}}</th>
                                <th>{{translate('casino.transaction_status')}}</th>
                                <th>{{translate('casino.transaction_amount')}}</th>
                            </tr>
                        </thead>
                    </table>
                    <button class="loadMoredataTableBtn">{{translate('casino.transaction_more')}}</button>
                    <hr class="devider">
                </div>
            </div>
            @include('settings')
        </div>
    </div>
    </div>

     @include('footer_main')
@endsection

@section('js')
    <script>
        function getTransactions() {
            $.ajax({
                type: "GET",
                url: '/ajax/transactions/' + $('table.withdraws').data('max_id') + '/new', // serializes the form's elements.
                dateType: 'json',
                success: function (data) {
                    if (data.length > 0) {
                        $('table.withdraws').data('max_id', data[0].id);

                        for (i = data.length - 1; i >= 0; i = i - 1) {
                            $('table.withdraws tbody').prepend('<tr id="txid_' + data[i].id + '"><td>' + data[i].date + '</td><td>' + data[i].id + '</td><td>' + data[i].status + '</td><td>' + data[i].amount + '</td></tr>');
                        }
                    }
                    setTimeout(getTransactions, 1000);
                },
                error: function (data) {
                    //alert(data);
                }
            });
        }

        function updateTransactions() {
            $.ajax({
                type: "GET",
                url: '/ajax/transactions/all', // serializes the form's elements.
                dateType: 'json',
                success: function (data) {
                    if (data.length > 0) {
                        for (i = 0; i < data.length; i = i + 1) {
                            $('#txid_' + data[i].id).html('<td>' + data[i].date + '</td><td>' + data[i].id + '</td><td>' + data[i].status + '</td><td>' + data[i].amount + '</td>');
                        }
                    }

                    setTimeout(updateTransactions, 5000);
                },
                error: function (data) {
                    //alert(data);
                }
            });
        }

        getTransactions();
        updateTransactions();
    </script>
@endsection
