@extends('layouts.app')

@section('title')
    {{ trans('casino.bonus') }}
@endsection

@section('content')
    <div class="cabinet-block" style="background: #000 url('media/images/bg/deposit-bg.png') center no-repeat; background-size: cover;">
        <div class="cabinet-entry">
            <div class="main-content">
                <div class="credits-block">
                    <i class="bitcoin-icon"></i>
                    <span class="balance"><span class="value">{{Auth::user()->getBalance()}}</span> {{trans('casino.credits') }}</span>
                    <a class="add-credits-btn" href="{{route('deposit')}}"><span class="text">{{trans('casino.add_credits')}}</span></a>
                </div>
                <div class="page-heading">
                    <h1 class="page-title">{{trans('casino.get_bonus')}}</h1>
                    <p class="sub-text">{{trans('casino.bonus_deposit')}}</p>
                </div>
                <div class="main-content-entry">
                    <div class="bonus-entry">
                        <div class="middle-block">
                            <div class="nav-block"></div>
                            <div class="bonuses-listing">
                                @if($active_bonus)
                                    <div class="item">
                                        <div class="single-bonus">
                                            <h3 class="title">{{trans($active_bonus->bonus->name)}}</h3>
                                            <p class="text">{{trans($active_bonus->bonus->descr)}}</p>
                                            <p class="text">Percent: {{$bonus_obj->getPercent()}} %</p>
                                            <p class="text">Wagered sum: {{$bonus_obj->getPlayedSum()}} mBtc</p>
                                            <a href="{{route('bonus.cancel')}}" class="push-button">{{ trans('casino.cancel') }}</a>
                                        </div>
                                    </div>
                                @else
                                    @foreach($bonuses as $bonus)
                                    <div class="item">
                                        <div class="single-bonus">
                                            <h3 class="title">{{translate($bonus->name)}}</h3>
                                            <p class="text">{{translate($bonus->descr)}}</p>
                                            <a href="{{route('bonus.activate', $bonus)}}" class="push-button">{{trans('casino.activate')}}</a>
                                        </div>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('settings')
        </div>
    </div>
@endsection