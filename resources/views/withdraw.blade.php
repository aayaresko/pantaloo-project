@extends('layouts.app')

@section('title')
    {{translate('Withdraw')}}
@endsection

@section('content')
    <div class="cabinet-block"
         style="background: #000 url('/media/images/bg/deposit-bg.png') center no-repeat; background-size: cover;">
        <div class="cabinet-entry">
            <div class="main-content">
                <div class="credits-block">
                    <i class="bitcoin-icon"></i>
                    <span class="balance"><span
                                class="value">{{Auth::user()->getBalance()}}</span> {{ trans('casino.credits') }}</span>
                    <a class="add-credits-btn" href="{{route('deposit', ['lang' => $currentLang])}}"><span
                                class="text">{{ trans('casino.add_credits') }}</span></a>
                </div>
                <div class="page-heading">
                    <h1 class="page-title">{{ trans('casino.withdraw') }}</h1>
                    <p class="sub-text">{{ trans('casino.transfer_wallet_address') }}</p>
                    <p class="sub-text">{{ trans('casino.have_millibitcoins', ['availableBalance' => Auth::user()->getRealBalance()]) }}</p>
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
                                <table>
                                    <tbody>
                                    <tr>
                                        <td><span class="text">{{ trans('casino.your_bitcoin_address') }}</span></td>
                                        <td><input type="text" name="address"
                                                   placeholder="{{ trans('casino.enter_here') }}"></td>
                                    </tr>
                                    <tr>
                                        <td><span class="text">{{ trans('casino.amount_mbtc') }}</span></td>
                                        <td><input type="text" name="sum"
                                                   placeholder="{{ trans('casino.enter_here') }}"></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <button class="withdraw">{{ trans('casino.withdraw') }}</button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>

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
            </div>
            @include('settings')
        </div>
    </div>
    </div>

    <footer class="footer footer-static">
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
            <p>© All rights reserved</p>
        </div>
    </footer>
@endsection