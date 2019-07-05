@extends('layouts.app')

@section('title', trans('casino.bonus'))

@section('content')
    <div class="cabinet-block"
         style="background: #000 url('/media/images/bg/deposit-bg-light.jpg') center no-repeat; background-size: cover;">
        <div class="cabinet-entry">
            <div class="main-content">
                <div class="page-heading">
                    <h1 class="page-title">{{trans('casino.get_bonus')}}</h1>
                </div>

                @include('main_parts.header_account')

                <div class="main-content-entry">
                    <div class="bonus-entry">
                        <div class="middle-block">
                            <div class="nav-block"></div>
                            <div class="bonuses-listing">
                                @foreach($bonusForView as $bonus)

                                    @php
                                        $bonusStatus = '';

                                        if ($bonus->notAvailable === false) {
                                            $bonusStatus = 'unavailable';
                                        }

                                        if (!is_null($bonus->activeBonus)) {
                                            $bonusStatus = 'activated';
                                        }

                                    @endphp
                                    <div class="item {{ $bonusStatus }}">
                                        <div class="single-bonus">
                                            <div class="itemWrapper">
                                                <h3 class="title">{{translate($bonus->name)}}</h3>
                                                <p class="text">{{translate($bonus->descr)}}</p>

                                                <div class="activeWrapper">
                                                    <div class="icon avail">
                                                    </div>
                                                    @if (!is_null($bonus->activeBonus))
                                                        <h3 class="title">activated</h3>
                                                        <p class="text">Bonus wager:
                                                            {{ $bonus->bonusStatistics['bonusWager']['real'] .
                                                                 ' / ' .
                                                                  $bonus->bonusStatistics['bonusWager']['necessary'] }}
                                                            {{ $currencyCode }}
                                                        </p>
                                                        @if ($bonus->id == 1)
                                                            <p class="text">Deposit wager:
                                                                {{ $bonus->bonusStatistics['depositWager']['real'] .
                                                                         ' / ' . $bonus->bonusStatistics['depositWager']['necessary'] }}
                                                                {{ config('app.currencyCode') }}</p>
                                                        @endif
                                                    @endif
                                                </div>

                                                <div class="unavailableWrapper">
                                                    <div class="icon unavail">

                                                    </div>
                                                    <h3 class="title">{{ trans('casino.bonus_unavailable') }}</h3>
                                                </div>
                                            </div>

                                            <div class="wrapperBottom">
                                                <div class="btnWrap">
                                                    <a href="{{ route('bonus.activate', $bonus) }}"
                                                       class="push-button activatedBtn bonusAction">
                                                        <i class="fa fa-check"></i>{{ trans('casino.activate') }}</a>

                                                    <a href="{{ route('bonus.cancel') }}"
                                                       class="push-button canceledBtn bonusAction">
                                                        <i class="fa fa-plus"></i>Cancel</a>
                                                </div>
                                                <p class="unavailInfo">Expired!
                                                    <button id="popUpBonus">
                                                        <span class="infoTxt">info</span></button>
                                                </p>
                                                <a href="#uls" class="usl-link">{{ trans('casino.terms') }}</a>
                                            </div>

                                            <div class="popUpBonusUnavail">
                                                <h3>Unavailable <span class="popUpHideBtn"></span></h3>
                                                <p>Bonus expired. Under the terms, you can not use it to run games and get free new bonuses.
                                                    Under the terms, you can not use it to run games and get free new bonuses.
                                                </p>
                                            </div>

                                        </div>
                                    </div>
                                @endforeach
                                    <form id = 'sendBonusAction' action='#' method='post'
                                          style="display: none">
                                        {{csrf_field()}}
                                    </form>
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
            $('body').on('click', '.bonusAction', function (e) {
                e.preventDefault();
                //set url
                let action = this.href;
                $('#sendBonusAction').attr('action', action).submit();
            });
        }

        bonusAct();

    </script>
@endsection