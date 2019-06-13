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
                <div class="userBalanceWrap">
                    <i class="bitcoin-icon"></i>
                    <div class="userBalanceCol leftBorder">
                        <span class="userBalanceTxt">{{ trans('casino.balance') }}</span>
                        <p class="balancebox-getbalance">{{Auth::user()->getBalance()}} m{{strtoupper(Auth::user()->currency->title)}}</p>
                    </div>
                    <div class="userBalanceCol leftBorder">
                        <span class="userBalanceTxt">{{ trans('casino.real_balance') }}</span>
                        <p class="balancebox-getrealbalance">{{Auth::user()->getRealBalance()}} m{{strtoupper(Auth::user()->currency->title)}}</p>
                    </div>
                    
                    <div class="userBalanceCol">
                        <span class="userBalanceTxt">{{ trans('casino.bonus_balance') }}</span>
                        <p class="balancebox-getbonusbalance">{{Auth::user()->getBonusBalance()}} m{{strtoupper(Auth::user()->currency->title)}}</p>
                    </div>
                    <a class="add-credits-btn AddCreditBtn" href="{{route('deposit', ['lang' => $currentLang])}}"><span
                                        class="text">{{ trans('casino.add_credits') }}</span></a>
                </div>
                <div class="main-content-entry">
                    <div class="bonus-entry">
                        <div class="middle-block">
                            <div class="nav-block"></div>
                            <div class="bonuses-listing">
                                
                            @if($active_bonus)
                                    <div class="item activated">
                                        <div class="single-bonus">
                                            <div class="itemWrapper">
                                                <h3 class="title">{{trans($active_bonus->bonus->name)}}</h3>
                                                <p class="text">{{trans($active_bonus->bonus->descr)}}</p>
                                                <div class="activeWrapper">
                                                        <div class="icon avail">

                                                        </div>   
                                                        @if ($active_bonus->bonus_id != 1)
                                                            <h3 class="title">activated</h3>
                                                            <p class="text">Wagered sum: {{$bonus_obj->getPlayedSum()}} mBtc</p>
                                                            <p class="text">Percent: {{$bonus_obj->getPercent()}} %</p>
                                                        @endif
                                                </div>
                                            </div>
                                            <div class="wrapperBottom">
                                                <div class="btnWrap">
                                                    <a href="{{route('bonus.cancel')}}" class="push-button canceledBtn"><i class="fa fa-plus"></i> {{ trans('casino.cancel') }}</a>
                                                </div>
                                                <a href="#reg-terms" class="reg-terms">{{ trans('casino.accept_the_terms_link') }}</a>
                                                <!-- <a href="#" class="reg-terms showGameLink">Show games</a> -->
                                            </div>
                                            
                                                
                                            <!-- <a href="{{route('bonus.cancel')}}" class="push-button">{{ trans('casino.cancel') }}</a> -->
                                        </div>
                                    </div>
                                    @else
               
                            
                                    @foreach($bonuses as $bonus)

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

   

    <footer class="footer footer-home">
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
            <ul class="footerLinks">
                <li class="rightReservedTxt">© All rights reserved</li>
                <li><a href="{{$partnerPage}}" class="afiliate" target="_blank">{{ trans('casino.affiliates') }}</a></li>
                <li><a target="_blank" href="{{route('support', ['lang' => $currentLang])}}" class="support">{{ trans('casino.frq') }}</a></li>
                <li><a href="#reg-terms" class="reg-terms">{{ trans('casino.accept_the_terms_link') }}</a></li>
                <li><a href="#uls" class="usl-link">{{ trans('casino.terms') }}</a></li>
            </ul>
        </div>
        
    </footer>
     <div class="hidden">
        <div id="uls">
            {!! trans('casino.bonus.term') !!}
        </div>
    </div>

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