<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', trans('casino.title'))</title>

    <meta name="description" content="@yield('description', '')">
    <meta name="keywords" content="@yield('keywords', '')">

    <!-- Bootstrap -->
    <link href="/css/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Icons -->
    <link href="/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- Main styles -->
    <link href="/vendors/animate/animate.css" rel="stylesheet">
    <link href="/vendors/fullPage/jquery.fullPage.css" rel="stylesheet">
    <link href="/css/select2.min.css" rel="stylesheet">
    <link href="/vendors/magnific-popup/magnific-popup.css?v=1.0.1" rel="stylesheet">
    <link href="/assets/css/languages.css?v=0.0.14" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="/css/countrySelect.css">
    <link href="/css/new.css?v=1.0.5" rel="stylesheet">
    <link href="/css/main.css?v={{ time() }}" rel="stylesheet">
    <link rel="canonical" href="#" />

    <link rel="icon" href="/favicon.png">

  
  

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-5MGSS83');</script>
    <!-- End Google Tag Manager -->
</head>
<body>

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5MGSS83"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<div id="preloader" class="preloader-block"><span class="spin"></span></div>
<div class="preloaderCommon" style="display: none"></div>
<script>

    var CasinoTranslate = {
        buttons : {
            play : "{{ trans('casino.play') }}",
            demo : "{{ trans('casino.free_demo') }}"
        }
    };

    function cached(url) {
        var test = document.createElement("img");
        test.src = url;
        return test.complete || test.width+test.height > 0;
    }

    function preloader() {
        if(!cached('/images/pixel.png')) {
            var el = document.getElementById('preloader');
            el.style.display = 'block';
            window.onload = function() {
                el.style.opacity = '0';
                var int = setTimeout(function () {
                    if(el.style.opacity == 0) {
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
           @if(Route::currentRouteName() == 'main')
                <a class="logoPc"><img src="/media/images/logo.png" alt="logo"></a>
                 <a class="logoMob"><img src="/media/images/logoSmall.png" alt="logo"></a>
            @else
                <a href="/" class="logoPc"><img src="/media/images/logo.png" alt="logo"></a>
                <a href="/" class="logoMob"><img src="/media/images/logoSmall.png" alt="logo"></a>
            @endif
        </div>
    </div>
    <div class="navigation-container">

        @if(!Auth::check())

            <ul class="langbox floated">
                <li><a href="#"><img src="{{ asset('assets/images/languages/' . app()->getLocale() . '.png') }}" alt="{{ app()->getLocale() }}" /> <span>{{ app()->getLocale() }}</span></a></li>
                <ul class="langbox-dropdown">
                    @foreach ($languages as $language)
                        @if(app()->getLocale() == $language) @continue @endif
                        <li>
                            <a href="{{ url("/language/$language") }}" class="{{ (app()->getLocale() == $language) ? "active" : '' }}">
                                <img src="{{ asset("assets/images/languages/$language.png") }}" alt="{{ $language }}" /> <span>{{ $language }}</span>
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
                <a href="{{route('deposit', ['lang' => $currentLang])}}" class="promo-action-btn">{{ trans('casino.deposit') }}</a>
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
                                <p class="balancebox-getbalance">{{Auth::user()->getBalance()}} m{{strtoupper(Auth::user()->currency->title)}}</p>
                            </div>
                        </li>
                        <ul class="balancebox-dropdown">
                            <li>
                                <div class="balancebox-dropdown-title">
                                    <span>{{ trans('casino.real_balance') }}</span>
                                    <p class="balancebox-getrealbalance">{{Auth::user()->getRealBalance()}} m{{strtoupper(Auth::user()->currency->title)}}</p>
                                </div>
                            </li>
                            <li>
                                <div class="balancebox-dropdown-title">
                                    <span>{{ trans('casino.bonus_balance') }}</span>
                                    <p class="balancebox-getbonusbalance">{{Auth::user()->getBonusBalance()}} m{{strtoupper(Auth::user()->currency->title)}}</p>
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
                    <li><a href="#"><img src="{{ asset('assets/images/languages/' . app()->getLocale() . '.png') }}" alt="{{ app()->getLocale() }}" /> <span>{{ app()->getLocale() }}</span></a></li>
                    <ul class="langbox-dropdown">
                        @foreach ($languages as $language)
                            @if(app()->getLocale() == $language) @continue @endif
                            <li>
                                <a href="{{ url("/language/$language") }}" class="{{ (app()->getLocale() == $language) ? "active" : '' }}">
                                    <img src="{{ asset("assets/images/languages/$language.png") }}" alt="{{ $language }}" /> <span>{{ $language }}</span>
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
        <div class="mobTopWrap">       
            
            <div class="logo-container">
                <a href="/" class="logo"><img src="/media/images/logoSmall.png" alt="logo"></a>
            </div>
            @if(Auth::guest())
            <div class="auth-block-mobile">
                <div class="login-block floated">
                    <a href="{{url('/login')}}" class="login-btn"><span class="text">{{ trans('casino.login') }}</span></a>
                </div>
                <div class="registration-block floated">
                    <a href="{{url('/registr')}}" class="reg-btn"><span class="text">{{ trans('casino.registration') }}</span></a>
                </div>
            </div>
            @endif
            @if(Auth::check())


                    <div class="wlc-usr">

                        <ul class="balancebox floated">
                            <li class="clearfix">
                                <a href="{{route('deposit', ['lang' => $currentLang])}}" class="usr-add-balance"></a>
                                <div class="balancebox-title">
                                    <span>{{ trans('casino.balance') }}</span>
                                    <p class="balancebox-getbalance">{{Auth::user()->getBalance()}} m{{strtoupper(Auth::user()->currency->title)}}</p>
                                </div>
                            </li>
                            <ul class="balancebox-dropdown">
                                <li>
                                    <div class="balancebox-dropdown-title">
                                        <span>Real Balance</span>
                                        <p class="balancebox-getrealbalance">{{Auth::user()->getRealBalance()}} m{{strtoupper(Auth::user()->currency->title)}}</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="balancebox-dropdown-title">
                                        <span>Bonus Balance</span>
                                        <p class="balancebox-getbonusbalance">{{Auth::user()->getBonusBalance()}} m{{strtoupper(Auth::user()->currency->title)}}</p>
                                    </div>
                                </li>
                            </ul>
                        </ul>

                    </div>      

            @endif
            <a href="#" class="close-icon"></a>
        </div>

       
         
        <div class="games-listing-block mobGameBlock">{{--{{ trans('casino.gambling_card_games') }}--}}
            <ul class="games-listing">
                @include('footer_links')
            </ul>
        </div>
        
        <nav class="navigation mobNav">
            <ul class="navigation-list">
                @if(Auth::check())
                <li class="order-deposite"><a href="{{route('deposit', ['lang' => $currentLang])}}" class="deposite">{{ trans('casino.deposit') }}</a></li>
                <li class="order-withdraw"><a href="{{route('withdraw', ['lang' => $currentLang])}}" class="withdraw">{{ trans('casino.withdraw') }}</a></li>
                <li class="order-bonus"><a href="{{route('bonus', ['lang' => $currentLang])}}" class="bonus">{{ trans('casino.get_bonus') }}</a></li>
                <li class="order-setting"><a href="{{route('settings', ['lang' => $currentLang])}}" class="setting">{{ trans('casino.settings') }}</a></li>
                @endif
                @include('page_links', ['is_main' => 1])
            </ul>
        </nav>
       
        <ul class="langbox">
            <li><a href="#"><img src="{{ asset('assets/images/languages/' . app()->getLocale() . '.png') }}" alt="{{ app()->getLocale() }}" /> <span>{{ app()->getLocale() }}</span></a></li>
            <ul class="langbox-dropdown">
                @foreach ($languages as $language)
                    @if(app()->getLocale() == $language) @continue @endif
                    <li>
                        <a href="{{ url("/language/$language") }}" class="{{ (app()->getLocale() == $language) ? "active" : '' }}">
                            <img src="{{ asset("assets/images/languages/$language.png") }}" alt="{{ $language }}" /> <span>{{ $language }}</span>
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
    <a href="#" class="close-icon"></a>
    <div class="popup-container">
        <div class="popup-entry">
            <div class="popup-heading">
                <h2 class="popup-title word-split">{{ trans('casino.registration') }}</h2>
            </div>
            <div class="popup-form">
                <form id="registr" action="/register" method="POST">
                    {{csrf_field()}}
                    <input type="hidden" name="password_confirmation" value="">
                    <input type="hidden" name="name" value="no_name">
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="email" class="email-input red" placeholder="{{ trans('casino.email_address') }}" name="email">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="password" class="pass-input red" placeholder="{{ trans('casino.password') }}" name="password">
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
                                <label for="agree"><input type="checkbox" name="agree" id="agree">
                                    {{--fix in future this--}}
                                    @if(app()->getLocale() === 'jp')
                                        <a href="#reg-terms" class="reg-terms">{{ trans('casino.accept_the_terms_link') }}</a> {{ trans('casino.accept_the_terms_text') }}
                                    @else
                                        {{ trans('casino.accept_the_terms_text') }} <a href="#reg-terms" class="reg-terms">{{ trans('casino.accept_the_terms_link') }}</a>
                                    @endif
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="send-btn-block">
                                <button class="send-btn"><span class="btn-entry">{{ trans('casino.registration') }}</span></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="log-popup">
    <a href="#" class="close-icon"></a>
    <div class="popup-container">
        <div class="popup-entry">
            <div class="popup-heading">
                <h2 class="popup-title word-split">{{ trans('casino.login') }}</h2>
            </div>
            <div class="popup-form">
                <form id="login" action="/login" method="POST">
                    {{csrf_field()}}
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="email" name="email" class="email-input blue" placeholder="{{ trans('casino.email_address') }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="password" name="password" class="pass-input blue" placeholder="{{ trans('casino.password') }}">
                            <a href="{{ url("/{$currentLang}/password/reset") }}" class="forget-link">{{ trans('casino.i_am_forget') }}</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="block-thumbnail">
                                <label for="remember"><input type="checkbox" name="remember" id="remember">{{ trans('casino.remember_me') }}</label>
                                <div class="btn-block">
                                    <a href="{{ url('/register') }}" class="account-btn">{{ trans('casino.have_not_account') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="send-btn-block">
                                <button class="send-btn"><span class="btn-entry">{{trans('casino.enter_now')}}</span></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
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
<script src="/vendors/countrySelect.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>   
<script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-pageLoadMore/1.0.0/js/dataTables.pageLoadMore.min.js"></script>
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

    if(show_auth)
    {
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
        if($(e.target).attr('class') == 'usr-add-balance') return true;
        e.stopPropagation();
        e.preventDefault();
        $('.balancebox-dropdown').toggleClass('is-open');
        $('a', this).toggleClass('is-open');
    });
    $(document).click(function(e) {
        $('.langbox')
            .not($('.langbox').has($(e.target)))
            .find('.langbox-dropdown, a')
            .removeClass('is-open');
    });
    $(document).click(function(e) {
        $('.balancebox')
            .not($('.balancebox').has($(e.target)))
            .find('.balancebox-dropdown, a')
            .removeClass('is-open');
    });

    var auth = @if(Auth::check()) true @else false @endif;
    var is_mobile = @if(\App\Slots\Casino::IsMobile()) true @else false @endif;


    $("#registr").submit(function(e) {
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

    if(auth)
    {
        setBalance();
        userActive();
    }

    function userActive() {
        let timeOut = 1000 * 60 * 10;
        $.ajax({
            type: "GET",
            url: `/ajax/userActive`,
            dateType: 'json',
            success: function(data)
            {
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
            success: function(data)
            {
                $('span.deposit-value').html(data.balance);
                $('span.value').html(data.balance);

                if(data.free_spins > 0)
                {
                    $('span.free_spins_balance').show();
                    $('b.spins_sum').html(data.free_spins);
                }
                else
                {
                    $('span.free_spins_balance').hide();
                }

                if(data.deposit)
                {
                    ga('send', 'event', 'Money', 'Deoposite', 'Sum', Math.round(data.deposit));
                    $('.deposit-sum').html('<b>' + data.deposit + '</b> @if(Auth::check()) m{{Auth::user()->currency->title}} @else mBtc @endif');
                    $('.simple-popup').addClass('active');
                    $('.simple-popup .popup-entry').addClass('active');
                    //alert('We got deposit from you ' + data.deposit);
                }

                if(data.balance_info)
                {
                    $(".balancebox-getbalance").html(data.balance_info.balance);
                    $(".balancebox-getrealbalance").html(data.balance_info.real_balance);
                    $(".balancebox-getbonusbalance").html(data.balance_info.bonus_balance);
                }

                setTimeout(setBalance, 1000);
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

        if (this.href.match(window.location.pathname))
        {
            $(this).parent().addClass('active');
        }
    });

    $('ul.side-nav-listing li a').each(function () {

        if (this.href.match(window.location.pathname))
        {
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
    .rounded
    {
        border-radius: 20%;
    }
</style>

<img src="/images/pixel.png" alt="" style="display: none" />



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
<!-- Start of uptechsupport Zendesk Widget script -->
<script id="ze-snippet" src="https://static.zdassets.com/ekr/snippet.js?key=f1d091fa-2264-45db-b730-50874aca28ee"> </script>
<!-- End of uptechsupport Zendesk Widget script -->
</body>
</html>