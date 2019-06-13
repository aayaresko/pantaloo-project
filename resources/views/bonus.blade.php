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
                                @if($activeBonus)
                                    <div class="item">
                                        <div class="single-bonus">
                                            <h3 class="title">{{trans($activeBonus->name)}}</h3>
                                            <p class="text">{{trans($activeBonus->descr)}}</p>

                                            <p class="text">Bonus wager:
                                                {{ $activeBonus->bonusStatistics['bonusWager']['real'] . ' / ' . $activeBonus->bonusStatistics['bonusWager']['necessary'] }}
                                                {{ config('app.currencyCode') }}</p>

                                            @if ($activeBonus->id == 1)

                                                <p class="text">Deposit wager:
                                                    {{ $activeBonus->bonusStatistics['depositWager']['real'] . ' / ' . $activeBonus->bonusStatistics['depositWager']['necessary'] }}
                                                    {{ config('app.currencyCode') }}</p>

                                            @endif

                                            <a href="{{route('bonus.cancel')}}"
                                               class="push-button">{{ trans('casino.cancel') }}</a>
                                        </div>
                                    </div>
                                @else
                                    @foreach($bonusForView as $bonus)
                                        <div class="item">
                                            <div class="single-bonus">
                                                <h3 class="title">{{translate($bonus->name)}}</h3>
                                                <p class="text">{{translate($bonus->descr)}}</p>
                                                <a href="{{route('bonus.activate', $bonus)}}"
                                                   class="push-button bonusActive">{{trans('casino.activate')}}</a>

                                                <form action='{{route('bonus.activate', $bonus)}}' method='post'
                                                      style="display: none">
                                                    {{csrf_field()}}
                                                </form>
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

@section('js')
    <script>

        function bonusAct() {
            //send form method post
            $('body').on('click', '.bonusActive', function (e) {
                e.preventDefault();
                let form = $(this).next();
                form.submit();
            });
        }

        bonusAct();

    </script>
@endsection