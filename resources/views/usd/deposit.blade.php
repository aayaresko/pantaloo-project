@extends('layouts.app')

@section('title')
    {{translate('Deposit')}}
@endsection

@section('content')
    <div class="cabinet-block" style="background: #000 url('media/images/bg/deposit-bg-light.jpg') center no-repeat; background-size: cover;">
        <div class="cabinet-entry">
            <div class="main-content">
                <div class="credits-block">
                    <i class="bitcoin-icon"></i>
                    <span class="balance"><span class="value">{{Auth::user()->getBalance()}}</span> {{translate('credits')}}</span>
                    <a class="add-credits-btn" href="{{route('deposit')}}"><span class="text">{{translate('Add Credits')}}</span></a>
                </div>
                <div class="page-heading">
                    <h1 class="page-title">{{translate('Deposit')}}</h1>
                    <p class="sub-text">{{translate('Fund your account.')}}</p>
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
                            <form name="Pay" action="{{route('usd.depositDo')}}" method="POST" accept-charset="UTF-8">
                                {{ csrf_field() }}
                                <table>
                                    <tbody>
                                    <tr>
                                        <td><span class="text">{{translate('Amount USD')}}</span></td>
                                        <td><input type="text" name="amount" placeholder="{{translate('Enter here')}}"></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><button type="submit" class="withdraw">{{translate('Refill')}}</button></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @include('usd.settings')
        </div>
    </div>
    </div>
@endsection