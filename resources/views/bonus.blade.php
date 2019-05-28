@extends('layouts.app')

@section('title', trans('casino.bonus'))


@section('content')
    <div class="cabinet-block"
         style="background: #000 url('/media/images/bg/deposit-bg-light.jpg') center no-repeat; background-size: cover;">
        <div class="cabinet-entry">
            <div class="main-content">
                <div class="credits-block">
                    <i class="bitcoin-icon"></i>
                    <span class="balance"><span class="value">{{Auth::user()->getBalance()}}
                        </span> {{trans('casino.credits') }}</span>
                    <a class="add-credits-btn" href="{{route('deposit', ['lang' => $currentLang])}}">
                        <span class="text">{{trans('casino.add_credits')}}</span></a>
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
                                            @php
                                                $dataBonus = $active_bonus->data;

                                                $bonusWagerUser = isset($dataBonus['wagered_bonus_amount']) ? $dataBonus['wagered_bonus_amount'] : 0;
                                                $bonusWager = isset($dataBonus['wagered_sum']) ? $dataBonus['wagered_sum'] : 0;

                                                $depositWagerUser = isset($dataBonus['wagered_amount']) ? $dataBonus['wagered_amount'] : 0;

                                                if (isset($dataBonus['wagered_deposit']) and (int)$dataBonus['wagered_deposit'] === 1) {
                                                    $depositWager = isset($dataBonus['total_deposit']) ? $dataBonus['total_deposit'] : 0;
                                                } else {
                                                    $depositWager = 0;
                                                }

                                            @endphp
                                            <p class="text">Bonus Wager:
                                                {{ $bonusWagerUser . ' / ' . $bonusWager }} {{ config('app.currencyCode') }}</p>

                                            @if ($active_bonus->bonus_id == 1)

                                                <p class="text">Deposit Wager:
                                                    {{ $depositWagerUser . ' / ' . $depositWager }} {{ config('app.currencyCode') }}</p>

                                            @endif

                                            <a href="{{route('bonus.cancel')}}"
                                               class="push-button">{{ trans('casino.cancel') }}</a>
                                        </div>
                                    </div>
                                @else
                                    @foreach($bonuses as $bonus)
                                        <div class="item">
                                            <div class="single-bonus">
                                                <h3 class="title">{{translate($bonus->name)}}</h3>
                                                <p class="text">{{translate($bonus->descr)}}</p>
                                                <a href="{{route('bonus.activate', $bonus)}}"
                                                   class="push-button">{{trans('casino.activate')}}</a>
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

    @include('footer_main')
@endsection