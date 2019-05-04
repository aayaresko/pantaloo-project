@extends('layouts.app')

@section('title')
    {{translate('DepositEvent')}}
@endsection

@section('content')
    <div class="cabinet-block" style="background: #000 url('/media/images/bg/deposit-bg-light.jpg') center no-repeat; background-size: cover;">
        <div class="cabinet-entry">
            <div class="main-content">
                <div class="credits-block">
                    <i class="bitcoin-icon"></i>
                    <span class="balance"><span class="value">{{Auth::user()->getBalance()}}</span> {{translate('credits')}}</span>
                    <a class="add-credits-btn" href="{{route('deposit')}}"><span class="text">{{translate('Add Credits')}}</span></a>
                </div>
                <div class="page-heading">
                    <h1 class="page-title">{{translate('Thank you')}}</h1>
                    <p class="sub-text">{{translate('Your account has been credited successfully')}}</p>
                </div>
            </div>
            @include('usd.settings')
        </div>
    </div>
    </div>
@endsection