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

                            @if($activeBonus)
                                    <div class="item activated">
                                        <div class="single-bonus">
                                            <div class="itemWrapper">
                                                <h3 class="title">{{trans($activeBonus->name)}}</h3>
                                                <p class="text">{{trans($activeBonus->descr)}}</p>
                                                <div class="activeWrapper">
                                                        <div class="icon avail"></div>
                                                        <h3 class="title">activated</h3>
                                                        <p class="text">Bonus wager: {{ $activeBonus->bonusStatistics['bonusWager']['real'] .
                                                         ' / ' . $activeBonus->bonusStatistics['bonusWager']['necessary'] }} {{ $currencyCode }}
                                                        </p>
                                                        @if ($activeBonus->id == 1)
                                                                <p class="text">Deposit wager:  {{ $activeBonus->bonusStatistics['depositWager']['real'] .
                                                                 ' / ' . $activeBonus->bonusStatistics['depositWager']['necessary'] }} {{ config('app.currencyCode') }}</p>
                                                        @endif
                                                </div>
                                            </div>
                                            <div class="wrapperBottom">
                                                <div class="btnWrap">
                                                    <a href="{{ route('bonus.cancel') }}" class="push-button canceledBtn">
                                                        <i class="fa fa-plus"></i>
                                                        {{ trans('casino.cancel') }}
                                                    </a>
                                                </div>
                                                <a href="#reg-terms" class="reg-terms">{{ trans('casino.accept_the_terms_link') }}</a>
                                                <!-- <a href="#" class="reg-terms showGameLink">Show games</a> -->
                                            </div>

                                            <!-- <a href="{{route('bonus.cancel')}}" class="push-button">{{ trans('casino.cancel') }}</a> -->
                                        </div>
                                    </div>
                                    @else
               
                            
                                    @foreach($bonusForView as $bonus)

                                    <div class="item">
                                        <div class="single-bonus">
                                            <div class="itemWrapper">
                                                <h3 class="title">{{translate($bonus->name)}}</h3>
                                                <p class="text">{{translate($bonus->descr)}}</p>
                                                <div class="activeWrapper">
                                                    <div class="icon avail">

                                                    </div>   
                                                    <h3 class="title">Activated</h3>
                                                    <p class="text">Wagered sun: 0 mBTC Percent: 0%</p>
                                                </div>
                                                <div class="unavailableWrapper">
                                                    <div class="icon unavail">

                                                    </div> 
                                                    <h3 class="title">{{ trans('casino.bonus_unavailable') }}</h3>
                                                </div>
                                            </div>
                                            <div class="wrapperBottom">
                                                <div class="btnWrap">
                                                    <a href="{{route('bonus.activate', $bonus)}}" class="push-button activatedBtn"><i class="fa fa-check"></i>{{trans('casino.activate')}}</a>
                                                    <a href="{{route('bonus.activate', $bonus)}}" class="push-button canceledBtn"><i class="fa fa-plus"></i>Cancel</a>
                                                </div>
                                                <p class="unavailInfo">Просрочен!  <button id="popUpBonus"><span class="infoTxt">info</span></button></p>
                                                <a href="#reg-terms" class="reg-terms">{{ trans('casino.accept_the_terms_link') }}</a>
                                                <!-- <a href="#" class="reg-terms showGameLink">Show games</a> -->
                                            </div>
                                            <div class="popUpBonusUnavail">
                                                <h3>Unavailable <span class="popUpHideBtn"></span></h3>
                                                <p>Бонус просрочен. По условиям вы не можете использовать его для запуска игр и получения бесплатных новых бонусов.
                                                По условиям вы не можете использовать его для запуска игр и получения бесплатных новых бонусов.
                                                </p>
                                            </div>
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