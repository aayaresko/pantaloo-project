@extends('layouts.app')

@section('title')
    {{translate('Withdraw')}}
@endsection

@section('content')
<div class="cabinet-block" style="background: #000 url('media/images/bg/deposit-bg.png') center no-repeat; background-size: cover;">
    <div class="cabinet-entry">
        <div class="main-content">
            <div class="credits-block">
                <i class="bitcoin-icon"></i>
                <span class="balance"><span class="value">{{Auth::user()->getBalance()}}</span> {{translate('credits')}}</span>
                <a class="add-credits-btn" href="{{route('deposit')}}"><span class="text">{{translate('Add Credits')}}</span></a>
            </div>
            <div class="page-heading">
                <h1 class="page-title">{{translate('Withdraw')}}</h1>
                <p class="sub-text">{{translate('Transfer bitcoins to your wallet address.')}}</p>
                <p class="sub-text">{{translate('You have %s MilliBitCoins (mBTC) available.', [Auth::user()->getRealBalance()])}}</p>
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
                                    <td><span class="text">{{translate('Your Bitcoin address')}}</span></td>
                                    <td><input type="text" name="address" placeholder="{{translate('Enter here')}}"></td>
                                </tr>
                                <tr>
                                    <td><span class="text">{{translate('Amount mBTC')}}</span></td>
                                    <td><input type="text" name="sum" placeholder="{{translate('Enter here')}}"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><button class="withdraw">{{translate('Withdraw')}}</button></td>
                                </tr>
                                </tbody>
                            </table>

                            {{csrf_field()}}
                        </form>
                    </div>
                    @if(count($transactions) > 0)
                    <div class="btn-history-block">
                        <a href="#" class="open-history-link">{{translate('Open History')}}</a>
                    </div>
                    @endif
                    <div class="bottom-block">
                        @if(count($transactions) > 0) <h3 class="title">{{translate('Your Withdraws')}}</h3> @endif
                        @include('transactions')
                    </div>
                </div>
            </div>
        </div>
        @include('settings')
    </div>
</div>
</div>
@endsection