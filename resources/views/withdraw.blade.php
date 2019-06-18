@extends('layouts.app')

@section('title', trans('Withdraw'))

@section('content')
    <div class="cabinet-block"
         style="background: #000 url('/media/images/bg/deposit-bg-light.jpg') center no-repeat; background-size: cover;">

        <div class="cabinet-entry cabinetMod">
            <div class="main-content">
                <div class="page-heading">
                    <h1 class="page-title">{{ trans('casino.withdraw') }}</h1>
                </div>

                @include('main_parts.header_account')

                <div class="text-block-withdraw">
                    <p class="descr">{{ trans('casino.transfer_wallet_address') }}</p>
                    <p class="descr">
                        {{ trans('casino.have_millibitcoins',
                     ['availableBalance' => $user->getRealBalance()]) }}
                    </p>
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

                    </div>
                </div>

                <div class='tableTransactionsWrapper'>
                    <p class="descr">{{ trans('casino.your_withdraws') }}</p>
                    <table id="transactionsTable" class="display">
                        <thead>
                            <tr>
                                <th>{{ trans('casino.deposit_data') }}</th>
                                <th>{{ trans('casino.transaction_id') }}</th>
                                <th>{{ trans('casino.transaction_status') }}</th>
                                <th>{{ trans('casino.transaction_amount') }}</th>
                            </tr>
                        </thead>
                    </table>
                    <button class="loadMoredataTableBtn">{{ trans('casino.transaction_more') }}</button>
                    <hr class="devider">
                </div>

            </div>
            @include('settings')
        </div>
    </div>

    @include('footer_main')
@endsection