<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ MetaTag::get('title') }}</title>
    {!! MetaTag::tag('description') !!}

    <!-- Bootstrap -->
    <link href="/css/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Icons -->
    <link href="/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- Main styles -->
    <link href="/vendors/animate/animate.css" rel="stylesheet">
    <link href="/vendors/fullPage/jquery.fullPage.css" rel="stylesheet">
    <link href="/css/select2.min.css" rel="stylesheet">
    <link href="/vendors/magnific-popup/magnific-popup.css?v=1.0.1" rel="stylesheet">
    <link href="/assets/css/languages.css?v=0.0.17" rel="stylesheet">
    <link href="/css/new.css?v={{ time() }}" rel="stylesheet">
    <link href="/css/main.css?v={{ time() }}" rel="stylesheet">

    <link rel="canonical" href="{{ \Illuminate\Support\Facades\Request::url() }}"/>

    @include('_rel_alternate', ['languages' => $languages])

    
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#8932ff">
    <meta name="msapplication-TileColor" content="#8932ff">
    <meta name="application-name" content="Casinobit">
    <meta name="theme-color" content="#ffffff">

    <!-- Google Tag Manager -->
    <script>(function (w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start':
                    new Date().getTime(), event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-5MGSS83');</script>
    <!-- End Google Tag Manager -->
</head>
<body {!! Cookie::get('testmode') ? 'style="border:#cccc00 dashed"' : '' !!}>

<!-- Google Tag Manager (noscript) -->
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5MGSS83"
            height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->

<div id="preloader" class="preloader-block"><span class="spin"></span></div>
<div class="preloaderCommon" style="display: none"></div>
<script>

    var CasinoTranslate = {
        buttons: {
            play: "{{ trans('casino.play') }}",
            demo: "{{ trans('casino.free_demo') }}"
        }
    };

    function cached(url) {
        var test = document.createElement("img");
        test.src = url;
        return test.complete || test.width + test.height > 0;
    }

    function preloader() {
        if (!cached('/images/pixel.png')) {
            var el = document.getElementById('preloader');
            el.style.display = 'block';
            window.onload = function () {
                el.style.opacity = '0';
                var int = setTimeout(function () {
                    if (el.style.opacity == 0) {
                        clearTimeout(int);
                        el.style.display = 'none';
                    }
                }, 1000);
            };
        }
    }

    preloader();
</script>

<!-- header start -->
<header class="header @if(Auth::check()) usr @endif">
    <div class="header-left">
        <div class="logo-block">       
            <a @if(Route::currentRouteName() != 'main') href="/{{ app()->getLocale() }}" @endif class="logoPc">
                <img src="/media/images/casinobit_logo_white_empty.svg" alt="logo">
                <span class="svgWrap">
                    <svg id="anim" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                         viewBox="0 0 1250 110" enable-background="new 0 0 1250 110" xml:space="preserve">
                    <path fill="#fff" d="M398.7,39.9c-2.6,0-5.1,0.4-7.5,1.2c8-8.9,7.2-22.6-1.7-30.6s-22.6-7.2-30.6,1.7c-7.4,8.2-7.4,20.7,0,29
                        c-2.4-0.8-5-1.2-7.5-1.2c-13.5,0-24.5,10.9-24.5,24.4s10.9,24.5,24.4,24.5c9.6,0,18.4-5.6,22.3-14.3c-3.7,13.8-14.3,24.8-28,28.8
                        l1.5,1.7H403l1.5-1.7c-13.7-4.1-24.3-15-28-28.8c5.6,12.3,20.1,17.7,32.4,12.1c12.3-5.6,17.7-20.1,12.1-32.4
                        C417,45.5,408.3,39.9,398.7,39.9z"/>
                    <path fill="#fff" d="M875,5c-3.1,8.1-10.5,18.8-20.9,29.1C843.8,44.5,833.1,51.9,825,55c8.1,3.1,18.8,10.5,29.1,20.9
                        C864.5,86.2,871.9,97,875,105c3.1-8.1,10.5-18.8,20.9-29.1C906.2,65.5,917,58.1,925,55c-8.1-3.1-18.8-10.5-29.1-20.9
                        C885.5,23.8,878.1,13.1,875,5z"/>
                    <path fill="#fff" d="M1147.7,52.4c6.2-3.4,9.4-8.6,9.4-15.6c0-10.7-5.9-18.2-19.5-19.9V5h-12.4v11.3c-2,0-2.9,0-9.5,0V5h-12.4
                        v11.3h-13.1v77.4h13.1V105h12.4V93.7h9.5V105h12.4V93.3c14.7-1.4,22.2-10,22.2-22.8C1159.8,61.3,1156.1,55.4,1147.7,52.4z
                         M1133.9,40.5c0,8-7.2,6.7-19.6,6.7V33.8C1128.2,33.8,1133.9,33.1,1133.9,40.5z M1114.3,75.6V61.3c13.7,0,22.1-1.3,22.1,7.1
                        C1136.4,77.1,1127.8,75.6,1114.3,75.6L1114.3,75.6z"/>
                    <path fill="#fff" d="M625,22.8c-4.9-23.7-44.4-25-53.4,3.3C557.3,70.7,615.2,84.9,625,105c9.8-20.1,67.7-34.3,53.5-78.9
                        C669.4-2.1,629.9-0.8,625,22.8z"/>
                    <path fill="#fff" d="M125,5c-8.2,17-57.2,29-45.2,66.7c7.1,22.3,36.7,22.8,44,6.5c-3.1,12.2-12.3,21.8-24.4,25.4l1.3,1.4h48.5
                        l1.3-1.5c-12-3.6-21.3-13.2-24.4-25.4c7.3,16.4,36.9,15.8,44-6.5C182.2,34,133.3,22,125,5z"/>
                    </svg>
                </span>    
            </a>         
        </div>
    </div>
    <div class="navigation-container">
        @if(!Auth::check())

            <ul class="langbox floated">
                <li><a href="#"><img src="{{ asset('assets/images/languages/' . app()->getLocale() . '.png') }}"
                                     alt="{{ app()->getLocale() }}"/> <span>{{ app()->getLocale() }}</span></a></li>
                <ul class="langbox-dropdown">
                    @foreach ($languages as $language)
                        @if(app()->getLocale() == $language) @continue @endif
                        <li>

                            <a href="{{ LangAlternatePageUrl($language) }}"
                               class="{{ (app()->getLocale() == $language) ? "active" : '' }}">
                                <img src="{{ asset("assets/images/languages/$language.png") }}" alt="{{ $language }}"/>
                                <span>{{ $language }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </ul>

        @endif

        {{--<div class="language-block floated">--}}
        {{--<ul class="language-listing">--}}
        {{--@foreach ($languages as $language)--}}
        {{--@if($currentLang != $language)--}}
        {{--<li>--}}
        {{--<a href="{{ url("/language/$language") }}">{{strtoupper($language)}}</a>--}}
        {{--</li>--}}
        {{--@endif--}}
        {{--@endforeach--}}
        {{--</ul>--}}
        {{--</div>--}}

        <nav class="navigation hidden-xs floated{{ !Auth::check() ? ' navigation-uncheck' : '' }}">
            <ul class="navigation-list">
                @include('page_links', ['is_main' => 1])
                @if(Auth::check())
                    @if(Auth::user()->role == 1)
                        <li><a href="/affiliates">{{ trans('casino.admin') }}</a></li>
                    @endif

                    @can('accessUserAdminPublic')
                        <li><a href="/admin">{{ trans('casino.admin') }}</a></li>
                    @endcan

                    @if(Auth::user()->free_spins > 0)
                        <li><a href="/slots#free_spins">Free spins</a></li>
                    @endif
                @endif
            </ul>
        </nav>

        @if(Auth::check())
            <div class="promo-actions-block floated">
                <a href="{{route('deposit', ['lang' => $currentLang])}}"
                   class="promo-action-btn">{{ trans('casino.deposit') }}</a>
            </div>
        @endif
    </div>
    <div class="header-right-part">
        <div class="login-block floated">
            <a href="#" class="login-btn"><span class="text">{{ trans('casino.login') }}</span></a>
        </div>
        <div class="login-block reg-modified floated">
            <a href="#" class="reg-btn"><span class="text">{{ trans('casino.registration') }}</span></a>
        </div>
        @if(!Auth::check())
            <div class="login-block login-block-reg floated">
                <a href="#" class="login-btn"><span class="text">SIGN IN</span></a>
            </div>
            <div class="login-block login-block-reg floated">
                <a href="#" class="reg-btn"><span class="text">SIGN UP</span></a>
            </div>
        @endif
        <div class="registration-block floated">
            <a href="#" class="reg-btn"><span class="text">{{ trans('casino.registration') }}</span></a>
        </div>
        @php
            $user = Auth::user();
            $emailUser = null;
            if (isset($user->email)) {
                $emailUser = $user->email;
            }
        @endphp
        @if(Auth::check())
            <div class="usr-block">

                <a href="{{route('deposit', ['lang' => $currentLang])}}" class="usr-block-icon"></a>

                <div class="wlc-usr">

                    <ul class="balancebox floated">
                        <li class="clearfix">
                            <a href="{{route('deposit', ['lang' => $currentLang])}}" class="usr-add-balance"></a>
                            <div class="balancebox-title">
                                <span>{{ trans('casino.balance') }}</span>
                                <p class="balancebox-getbalance">{{Auth::user()->getBalance()}}
                                    m{{strtoupper(Auth::user()->currency->title)}}</p>
                            </div>
                        </li>
                        <ul class="balancebox-dropdown">
                            <li>
                                <div class="balancebox-dropdown-title">
                                    <span>{{ trans('casino.real_balance') }}</span>
                                    <p class="balancebox-getrealbalance">{{Auth::user()->getRealBalance()}}
                                        m{{strtoupper(Auth::user()->currency->title)}}</p>
                                </div>
                            </li>
                            <li>
                                <div class="balancebox-dropdown-title">
                                    <span>{{ trans('casino.bonus_balance') }}</span>
                                    <p class="balancebox-getbonusbalance">{{Auth::user()->getBonusBalance()}}
                                        m{{strtoupper(Auth::user()->currency->title)}}</p>
                                </div>
                            </li>
                        </ul>
                    </ul>

                </div>

                {{--<div class="wlc-usr">--}}
                {{--<span class="welcome-msg">{{ trans('casino.balance') }}: <b><span class="deposit-value">{{Auth::user()->getBalance()}}</span></b> m{{Auth::user()->currency->title}} <span class="free_spins_balance" @if(Auth::user()->free_spins == 0) style="display: none;" @endif>+ <b class="spins_sum">{{Auth::user()->free_spins}}</b> spins</span></span>--}}
                {{--<a href="{{route('deposit', ['lang' => $currentLang])}}" class="usr-name">{{Auth::user()->email}}</a>--}}
                {{--</div>--}}

                <ul class="langbox floated">
                    <li><a href="#"><img src="{{ asset('assets/images/languages/' . app()->getLocale() . '.png') }}"
                                         alt="{{ app()->getLocale() }}"/> <span>{{ app()->getLocale() }}</span></a></li>
                    <ul class="langbox-dropdown">
                        @foreach ($languages as $language)
                            @if(app()->getLocale() == $language) @continue @endif
                            <li>
                                <a href="{{ LangAlternatePageUrl($language) }}"
                                   class="{{ (app()->getLocale() == $language) ? "active" : '' }}">
                                    <img src="{{ asset("assets/images/languages/$language.png") }}"
                                         alt="{{ $language }}"/> <span>{{ $language }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </ul>

                <a href="{{url('/logout')}}" class="logout-btn"></a>
            </div>
        @endif
        <div class="menu-button-block floated">
            <a href="#" class="menu-btn"><span></span></a>
        </div>
    </div>
</header>

@include('popup')
@include('errors')
@include('popup_fixed')

<div class="mobile-menu">
    <div class="popup-entry">
        <div class="mobTopWrap"></div>
        <div class="games-listing-block mobGameBlock">{{--{{ trans('casino.gambling_card_games') }}--}}
            <ul class="games-listing">
                @include('footer_links')
            </ul>
        </div>

        <nav class="navigation mobNav">
            <ul class="navigation-list">
                @if(Auth::check())
                    <li class="order-deposite"><a href="{{route('deposit', ['lang' => $currentLang])}}"
                                                  class="deposite">{{ trans('casino.deposit') }}</a></li>
                    <li class="order-withdraw"><a href="{{route('withdraw', ['lang' => $currentLang])}}"
                                                  class="withdraw">{{ trans('casino.withdraw') }}</a></li>
                    <li class="order-bonus"><a href="{{route('bonus', ['lang' => $currentLang])}}"
                                               class="bonus">{{ trans('casino.get_bonus') }}</a></li>
                    <li class="order-setting"><a href="{{route('settings', ['lang' => $currentLang])}}"
                                                 class="setting">{{ trans('casino.settings') }}</a></li>
                @endif
                @include('page_links', ['is_main' => 1])
            </ul>
        </nav>

        <ul class="langbox">
            <li><a href="#"><img src="{{ asset('assets/images/languages/' . app()->getLocale() . '.png') }}"
                                 alt="{{ app()->getLocale() }}"/> <span>{{ app()->getLocale() }}</span></a></li>
            <ul class="langbox-dropdown">
                @foreach ($languages as $language)
                    @if(app()->getLocale() == $language) @continue @endif
                    <li>
                        <a href="{{ LangAlternatePageUrl($language) }}"
                           class="{{ (app()->getLocale() == $language) ? "active" : '' }}">
                            <img src="{{ asset("assets/images/languages/$language.png") }}" alt="{{ $language }}"/>
                            <span>{{ $language }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </ul>

        @if(Auth::check())

            <a href="{{url('/logout')}}" class="logout-btn">{{ trans('logout') }}</a>

        @endif
    </div>
</div>
<div class="reg-popup">
    
        
        <!-- <div class="popup-container"> -->
            
                

            @if ($registrationStatus === 1)
            <div class="regPopUpWrapper">
            <div class="regPopUpBgTop"></div>
            <button class="close-icon">×</button>
                <div class="popup-entry">
                    <div class="popup-heading">
                        <h2 class="popup-title word-split">{{ trans('casino.registration') }}</h2>

                        {{--@if ($registrationStatus === 1)--}}
                        {{--<h5 class="popup-title">Due to high demand we are experiencing technical difficulties.--}}
                        {{--Registration are temporary disabled. Sorry for the inconvenience.</h5>--}}
                        {{--@else--}}
                        {{--<h5 class="popup-title">REGISTRATIONS ARE NOT AVAILABLE IN YOUR REGION.</h5>--}}
                        {{--@endif--}}

                    </div>
                    <div class="popup-form">
                        <form id="registr" action="/register" method="POST">
                            {{csrf_field()}}
                            <input type="hidden" name="password_confirmation" value="">
                            <input type="hidden" name="name" value="no_name">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label>{{ trans('casino.email_address') }} <span>*</span></label>
                                    <input type="email" class="email-input" name="email" required tabindex="1" title="{{ trans('casino.input_title') }}">
                                    <!-- <p class="errorMessage">Email required</p> -->
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    
                                    <label>{{ trans('casino.password') }} <span>*</span></label>
                                    <div class="pasInputWrapper">
                                        <input type="password" class="pass-input" name="password" required tabindex="2" title="{{ trans('casino.input_title') }}">
                                        <button type="button" class="showPasBtn" title="See password"><i class="fa fa-eye"></i></button>
                                    </div>
                                    <p class="errorMessage registrError"></p>
                                </div>
                            </div>
                            <input type="radio" name="currency" id="currency-btc" value="1" checked hidden/>

                            {{--<div class="row">--}}
                            {{--<div class="col-sm-12">--}}
                            {{--<div class="block-thumbnail block-thumbnail-radio">--}}
                            {{--<label for="currency-btc"><input type="radio" name="currency" id="currency-btc" value="1" checked />{{translate('BTC')}}</label>--}}
                            {{--<label for="currency-usd"><input type="radio" name="currency" id="currency-usd" value="2" />{{translate('USD')}}</label>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--</div>--}}

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="block-thumbnail">
                                        <input type="checkbox" name="agree" id="agree" required tabindex="3">
                                        <label for="agree" class="termLabel">
                                   
                                            {{--fix in future this--}}
                                            @if(app()->getLocale() === 'jp')
                                           
                                                <a href="#reg-terms"
                                                class="reg-terms">{{ trans('casino.accept_the_terms_link') }}</a> {{ trans('casino.accept_the_terms_text') }}
                                          
                                            @else
                                           
                                                {{ trans('casino.accept_the_terms_text') }} <a href="#reg-terms"
                                                                                            class="reg-terms">{{ trans('casino.accept_the_terms_link') }}</a>  {{ trans('casino.years_old') }}
                                         
                                            @endif
                                        
                                        </label>                             
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="send-btn-block">
                                        {{--<button class="send-btn"><span class="btn-entry">Get Notified</span></button>--}}
                                        <button class="send-btn regBtn" tabindex="4">
                                            <!-- <span class="btn-entry"></span> -->
                                            {{ trans('casino.registration') }}
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                <div class="popUpFooter">
                    <span>{{ trans('casino.have_account') }}<a href="#" class="login-btn">{{ trans('casino.enter_account') }}</a></span>
                </div>
            </div>
             @else
             <div class="regPopUpWrapper notAllowedCountry">
                <div class="regPopUpBgTop"></div>
                <button class="close-icon">×</button>
                <div class="popup-entry">
                    <div class="icon"></div>
                    <h5 class="popup-title">{{ trans('casino.not_allowed_title')}}</h5>
                    <p class="subTitle">{{ trans('casino.not_allowed_subtitle')}}</p>
                </div>
                <div class="popUpFooter">
                    <span>{{ trans('casino.affiliate_info') }}<a href="mailto:affiliates@casinobit.io">affiliates@casinobit.io</a></span>
                </div>
            </div>
             @endif

            
       
        <!-- </div> -->
   
</div>
<div class="log-popup">
    <div class="regPopUpWrapper">
        <div class="regPopUpBgTop"></div>
        <button class="close-icon">×</button>
        <!-- <div class="popup-container"> -->
            <div class="popup-entry">
                <div class="popup-heading">
                    <h2 class="popup-title word-split">{{ trans('casino.login') }}</h2>
                </div>
                <div class="popup-form">
                    <form id="login" action="/login" method="POST">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-sm-12">
                            <label>{{ trans('casino.email_address') }} <span>*</span></label>
                                <input type="email" name="email" class="email-input" required tabindex="5" title="{{ trans('casino.input_title') }}">
                                <!-- <p class="errorMessage loginErrorEmail"></p> -->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                    
                                    <label >{{ trans('casino.password') }} <span>*</span></label>
                                    <div class="pasInputWrapper">
                                        <input type="password" name="password" class="pass-input" required tabindex="6" title="{{ trans('casino.input_title') }}">
                                        <button type="button" class="showPasBtn" title="See password"><i class="fa fa-eye"></i></button>

                                        <a href="#"
                                        class="forget-link">{{ trans('casino.i_am_forget') }}</a>
                                        <p class="errorMessage loginError"></p>
                                    </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="block-thumbnail">
                                <input type="checkbox" name="remember" id="remember" tabindex="7">
                                <label for="remember" class="remem">{{ trans('casino.remember_me') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="send-btn-block">
                                    <button class="send-btn loginBtn" tabindex="8"><span class="btn-entry">{{trans('casino.enter_now')}}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <!-- </div> -->
    
    <div class="popUpFooter">
        <span>{{ trans('casino.dont_have_account') }}<a href="{{ url('/register') }}"
                                        class="account-btn">{{ trans('casino.create_account') }}</a></span>
    </div>
    </div>
</div>

<div class="reset-popup">
    <div class='regPopUpWrapper'>
        <div class="regPopUpBgTop"></div>
        <div class="popup-entry">
            <div class="popup-heading">
                <h2 class="popup-title">{{translate('Reset password')}}</h2>
            </div> 
            <button class="close-icon">×</button>
            <p class='popup-form-subtitle'>
                        {{ trans('casino.reset_hint') }}
                    </p>
            <div class='popup-form'>
                <form method="POST" action="{{ url("/{$currentLang}/password/email") }}">
                    {{csrf_field()}}          
                        <div class="row">
                            <div class="col-xs-12">
                                <label>{{translate('E-mail')}} <span>*</span></label>  
                                <input type="text" name="email" tabindex="9">
                            </div>
                        </div>
                        <div class="send-btn-block">
                            <button class="update-btn resetBtn" tabindex="10">{{translate('RESTORE')}}</button>
                        </div>
                </form>
                </div>
        </div>
    <div class="popUpFooter">
        <span>{{ trans('casino.dont_have_account') }}<a href="{{ url('/register') }}"
                                            class="account-btn">{{ trans('casino.create_account') }}</a></span>
    </div>
    </div>
</div>


<div class="simple-popup">
    <div class="popup-entry">
        <span class="side-title">New deposit</span>
        <a href="#" class="close-icon"></a>
        <div class="popup-heading">
            <h2 class="popup-title">We got your deposit</h2>
            <span class="subtitle">Success</span>
        </div>
        <div class="popup-text-block">
            <p class="text">Sum: <span class="deposit-sum"></span></p>
        </div>
    </div>
</div>
<div class="overlayMenu"></div>
<script src="/vendors/jquery/jquery-3.0.0.min.js"></script>
<script src="/vendors/jquery-ui/jquery-ui.js"></script>
<script src="/vendors/fullPage/scrolloverflow.min.js"></script>
<script src="/vendors/fullPage/jquery.fullPage.min.js"></script>
<script src="/vendors/lettering/jquery.lettering.js"></script>
<script src="/vendors/owl-carousel/owl.carousel.min.js"></script>
<script src="/assets/js/select2.min.js"></script>
<script src="/vendors/main.js?v={{ time() }}"></script>
<script src="/assets/js/helper.js"></script>
<script src="/vendors/magnific-popup/jquery.magnific-popup.min.js"></script>
<script src="/vendors/new.js?v=1.0.1"></script>


@yield('content')
<div class="shadow-container"></div>
<!-- footer start -->

<div class="hidden">
    <div id="reg-terms">
        {!! trans('casino.terms_conditions') !!}
    </div>
</div>

<script>
            @if(Session::has('auth'))
    var show_auth = true;
            @else
    var show_auth = false;
            @endif

    var iso_code = '{{ session('iso_code') }}';

    if (show_auth) {
        $('.log-popup').addClass('active');
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.langbox > li').on('click', function (e) {
        e.stopPropagation();
        e.preventDefault();
        $('.langbox-dropdown').toggleClass('is-open');
        $('a', this).toggleClass('is-open');
    });
    $('.balancebox > li').on('click', function (e) {
        if ($(e.target).attr('class') == 'usr-add-balance') return true;
        e.stopPropagation();
        e.preventDefault();
        $('.balancebox-dropdown').toggleClass('is-open');
        $('a', this).toggleClass('is-open');
    });
    $(document).click(function (e) {
        $('.langbox')
            .not($('.langbox').has($(e.target)))
            .find('.langbox-dropdown, a')
            .removeClass('is-open');
    });
    $(document).click(function (e) {
        $('.balancebox')
            .not($('.balancebox').has($(e.target)))
            .find('.balancebox-dropdown, a')
            .removeClass('is-open');
    });

    var auth = @if(Auth::check()) true
    @else false @endif;
    var is_mobile = @if(\App\Slots\Casino::IsMobile()) true
    @else false @endif;


    $("#registr").submit(function (e) {
        $('#registr input[name="password_confirmation"]').val($('#registr input[name="password"]').val());

        return true;
        /*
        var url = '/register'; // the script where you handle the form input.

        $.ajax({
            type: "POST",
            url: url,
            data: {
                email: $('#registr input[name="email"]').val(),
                password: $('#registr input[name="password"]').val(),
                password_confirmation: $('#registr input[name="password"]').val(),
                name: 'no_name'
            }, // serializes the form's elements.
            dateType: 'json',
            success: function(data)
            {
                window.location.href = '';
            },
            error: function (data) {
                //alert(data);
            }
        });

        e.preventDefault(); // avoid to execute the actual submit of the form.
        */
    });

    /*
    $("#login").submit(function(e) {

        var url = '/login'; // the script where you handle the form input.

        $.ajax({
            type: "POST",
            url: url,
            data: {
                email: $('#login input[name="email"]').val(),
                password: $('#login input[name="password"]').val(),
            }, // serializes the form's elements.
            dateType: 'json',
            success: function(data)
            {
                window.location.href = '';
            },
            error: function (data) {
                //alert(data);
            }
        });

        e.preventDefault(); // avoid to execute the actual submit of the form.
    });
    */

    if (auth) {
        setBalance();
        userActive();
    }

    function userActive() {
        let timeOut = 1000 * 60 * 10;
        $.ajax({
            type: "GET",
            url: `/ajax/userActive`,
            dateType: 'json',
            success: function (data) {
                setTimeout(userActive, timeOut);
            },
            error: function (data) {
                //alert(data);
            }
        });
    }

    function setBalance() {
        let email = '{{ $emailUser }}';

        $.ajax({
            type: "GET",
            url: `/ajax/balance/${email}`, // serializes the form's elements.
            dateType: 'json',
            success: function (data) {
                if (data.success == true) {
                    //console.log(data);
                    $('span.deposit-value').html(data.balance);
                    $('span.value').html(data.balance);

                    if (data.free_spins > 0) {
                        $('span.free_spins_balance').show();
                        $('b.spins_sum').html(data.free_spins);
                    } else {
                        $('span.free_spins_balance').hide();
                    }

                    if (data.deposit) {

                        let event = new CustomEvent('depositEvent', {
                            detail: {
                                id: data.depositId,
                                revenu: Math.round(data.deposit),
                                comment: data.depositComment
                            }
                        });
                        window.dispatchEvent(event);

                        window.dataLayer.push({
                            event: 'eec.purchase', //через название ивента мне нужно будет сделать событие  в ГТМ.
                            ecommerce: {
                                purchase: {
                                    actionField: {
                                        id: data.depositId, //в этом поле должно отправляться случайно сгенерированое число - нужно обязательно, без него ГТМ и ГА не распознают екомерс событие
                                        revenue: Math.round(data.deposit) //в это поле должно передаваться значение суммы, на какую пополнен депозит  - это нужно, что бы мы могли видеть отчёты
                                    },
                                    products: [{
                                        name: 'deposit' //ещё одно обязательное поле, что бы ГА понимала название нашего продукта.
                                    }]
                                }
                            }
                        });

                        //ga('gtm1.send', 'event', 'Money', 'Deoposite', 'Sum', Math.round(data.deposit));

                        $('.deposit-sum').html('<b>' + data.deposit + '</b> @if(Auth::check()) m{{Auth::user()->currency->title}} @else mBtc @endif');
                        $('.simple-popup').addClass('active');
                        $('.simple-popup .popup-entry').addClass('active');
                        //alert('We got deposit from you ' + data.deposit);
                    }

                    if (data.balance_info) {
                        $(".balancebox-getbalance").html(data.balance_info.balance);
                        $(".balancebox-getrealbalance").html(data.balance_info.real_balance);
                        $(".balancebox-getbonusbalance").html(data.balance_info.bonus_balance);
                    }

                    setTimeout(setBalance, 1000);
                } else {
                    setTimeout(setBalance, 5000);
                }
            },
            error: function (data) {
                //alert(data);
            }
        });
    }

    //off
    // $(document).on('click', 'a.open_game', function () {
    //     if(auth) {
    //         $.ajax({
    //             type: "GET",
    //             url: '/ajax/start/' + $(this).parent().parent().parent().parent().parent().data('slot_id'), // serializes the form's elements.
    //             dateType: 'json',
    //             success: function (data) {
    //                 if(is_mobile)
    //                     if(data.url) location.href = data.url;
    //
    //                 var sizes = '';
    //
    //                 if(data.category == 6)
    //                 {
    //                     sizes = 'width="960" height="620"';
    //                     $('.video-popup').addClass('popup-casino');
    //                 }
    //                 else
    //                 {
    //                     sizes = 'width="100%" height="100%"';
    //                     $('.video-popup').addClass('popup-slot');
    //                 }
    //
    //                 if (data.url) html = '<iframe ' + sizes + ' allowtransparency="true" allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true" allowFullScreen="true" frameborder="0" scrolling="no"  src="' + data.url + '"></iframe>';
    //                 else html = data.object;
    //
    //                 $('.video-popup .game-entry').html(html);
    //             },
    //             error: function (data) {
    //                 //alert(data);
    //             }
    //         });
    //
    //         $('.video-popup').addClass('active');
    //         $('header.header').addClass('active');
    //     }
    //     else
    //     {
    //         $('.log-popup').addClass('active');
    //     }
    //
    //     return false;
    // });
    //
    // $(document).on('click', '.games-block__button_play-fun', function () {
    //     var url = $(this).attr('href');
    //
    //     if(url != '')
    //     {
    //         var html = '<iframe width="960" height="620" allowtransparency="true" frameborder="0" scrolling="no" allowfullscreen="true"  webkitallowfullscreen="true" mozallowfullscreen="true" allowFullScreen="true" src="' + url + '"></iframe>';
    //
    //         $('.video-popup .game-entry').html(html);
    //         $('.video-popup').addClass('active');
    //     }
    //
    //     return false;
    // });
    //
    // $(document).on('click', 'a.open_casino', function () {
    //     if(iso_code == 'US' || $(this).parents('.disabled_casino').length > 0) return false;
    //
    //     //if(auth) {
    //         var url = $(this).attr('href');
    //
    //         if(url != '')
    //         {
    //             var html = '<iframe width="960" height="620" allowtransparency="true" frameborder="0" scrolling="no" allowfullscreen="true"  webkitallowfullscreen="true" mozallowfullscreen="true" allowFullScreen="true" src="' + url + '"></iframe>';
    //
    //             $('.video-popup .game-entry').html(html);
    //
    //             $('.video-popup').addClass('active popup-casino');
    //             $('header.header').addClass('active');
    //         }
    //         /*
    //     }
    //     else
    //     {
    //         $('.log-popup').addClass('active');
    //     }
    //     */
    //
    //     return false;
    // });
    //end off

    $('ul.cabinet-menu-listing li a').each(function () {

        if (this.href.match(window.location.pathname)) {
            $(this).parent().addClass('active');
        }
    });

    $('ul.side-nav-listing li a').each(function () {

        if (this.href.match(window.location.pathname)) {
            $(this).parent().addClass('active');
        }
    });
</script>

<!--Start of Zendesk Chat Script-->
<!--<script type="text/javascript">
    window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
            d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
    _.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute("charset","utf-8");
        $.src="https://v2.zopim.com/?3Uz7HY4kPnZEl6HFfOFTTG4WJyFhF6c9";z.t=+new Date;$.
                type="text/javascript";e.parentNode.insertBefore($,e)})(document,"script");
</script>-->
<!--End of Zendesk Chat Script-->

@yield('js')

<style>
    .rounded {
        border-radius: 20%;
    }
</style>

<img src="/images/pixel.png" alt="" style="display: none"/>


{{--<!-- Yandex.Metrika counter -->--}}
{{--<script type="text/javascript">--}}
{{--(function (d, w, c) {--}}
{{--(w[c] = w[c] || []).push(function() {--}}
{{--try {--}}
{{--w.yaCounter43829254 = new Ya.Metrika({--}}
{{--id:43829254,--}}
{{--clickmap:true,--}}
{{--trackLinks:true,--}}
{{--accurateTrackBounce:true--}}
{{--});--}}
{{--} catch(e) { }--}}
{{--});--}}

{{--var n = d.getElementsByTagName("script")[0],--}}
{{--s = d.createElement("script"),--}}
{{--f = function () { n.parentNode.insertBefore(s, n); };--}}
{{--s.type = "text/javascript";--}}
{{--s.async = true;--}}
{{--s.src = "https://mc.yandex.ru/metrika/watch.js";--}}

{{--if (w.opera == "[object Opera]") {--}}
{{--d.addEventListener("DOMContentLoaded", f, false);--}}
{{--} else { f(); }--}}
{{--})(document, window, "yandex_metrika_callbacks");--}}
{{--</script>--}}
{{--<noscript><div><img src="https://mc.yandex.ru/watch/43829254" style="position:absolute; left:-9999px;" alt="" /></div></noscript>--}}
{{--<!-- /Yandex.Metrika counter -->--}}

{{--<script>(function(d,t,u,s,e){e=d.getElementsByTagName(t)[0];s=d.createElement(t);s.src=u;s.async=1;e.parentNode.insertBefore(s,e);})(document,'script','//chat.casinobit.io/php/app.php?widget-init.js&_lang={{ app()->getLocale() }}');</script>--}}
{{--<script id="_agile_min_js" async type="text/javascript" src="https://d1gwclp1pmzk26.cloudfront.net/agile/agile-cloud.js"> </script>--}}
{{--<script type="text/javascript" >--}}
{{--var Agile_API = Agile_API || {}; Agile_API.on_after_load = function(){--}}
{{--_agile.set_account('c1cbkc93a03lmrmt73cd0295b7', 'up-tech', false);--}}
{{--_agile.track_page_view();--}}
{{--_agile_execute_web_rules();};--}}
{{--</script>--}}
<!-- Start of uptechsupport Zendesk Widget script -->
{{--<script id="ze-snippet" src="https://static.zdassets.com/ekr/snippet.js?key=f1d091fa-2264-45db-b730-50874aca28ee"> </script>--}}
<!-- End of uptechsupport Zendesk Widget script -->
{{--<script type="text/javascript">var $zoho=$zoho || {};$zoho.salesiq = $zoho.salesiq || {widgetcode:"20d401dd0bef4c4a89b7d31699a0965e3f436766499f2fd628d7b7b221e5dda9", values:{},ready:function(){}};var d=document;s=d.createElement("script");s.type="text/javascript";s.id="zsiqscript";s.defer=true;s.src="https://salesiq.zoho.com/widget";t=d.getElementsByTagName("script")[0];t.parentNode.insertBefore(s,t);</script>--}}
{{--<!-- Start of uptechsupport Zendesk Widget script -->--}}
{{--<script id="ze-snippet" src="https://static.zdassets.com/ekr/snippet.js?key=f1d091fa-2264-45db-b730-50874aca28ee"> </script>--}}
{{--<!-- End of uptechsupport Zendesk Widget script -->--}}

<script>
    @php
    $intercomConfig = \Helpers\IntercomHelper::getIntercomConfig();
    @endphp

    @if (is_null($user))
        window.intercomSettings = {
        app_id: "{{ $intercomConfig->appId }}"
    };
    @else
            @php
                $hmac = hash_hmac('sha256', $user->email, $intercomConfig->key );
            @endphp

        window.intercomSettings = {
        app_id: "{{ $intercomConfig->appId }}",
        user_hash: '{{ $hmac }}', // HMAC using SHA-256
        email: "{{ $user->email }}", // Email address
    };
    @endif
</script>
<script>(function () {
        var w = window;
        var ic = w.Intercom;
        if (typeof ic === "function") {
            ic('reattach_activator');
            ic('update', w.intercomSettings);
        } else {
            var d = document;
            var i = function () {
                i.c(arguments);
            };
            i.q = [];
            i.c = function (args) {
                i.q.push(args);
            };
            w.Intercom = i;
            var l = function () {
                var s = d.createElement('script');
                s.type = 'text/javascript';
                s.async = true;
                s.src = 'https://widget.intercom.io/widget/{{ $intercomConfig->appId }}';
                var x = d.getElementsByTagName('script')[0];
                x.parentNode.insertBefore(s, x);
            };
            if (w.attachEvent) {
                w.attachEvent('onload', l);
            } else {
                w.addEventListener('load', l, false);
            }
        }
    })();</script>

<script>
    document.jsBridge = {!!  app(\App\Providers\JsBridge\JsBridge::class)  !!}
    $(document).ready(function () {

        let intercomUpdate = function () {
            $.ajax({
                type: "GET",
                url: '/intercom/update',
                data: {},
                dateType: 'json',
                success: function (data) {
                    //console.log(data);
                },
                error: function (data) {
                }
            });
        };

        //events
        Intercom('onShow', function () {
            intercomUpdate();
        });

        Intercom('onUnreadCountChange', function () {
            intercomUpdate();
        });
    });
</script>

</body>
</html>