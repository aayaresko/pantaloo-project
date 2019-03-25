@extends('layouts.app')

@section('description')
    trans('casino.home_description')
@endsection

@section('keywords')
    trans('casino.home_keywords')
@endsection

@section('content')
    <ul class="sections-nav">
        <li data-menuanchor="block-2" class="active"><a href="#block-2">{{ trans('casino.blackjack') }}</a></li>
        <li data-menuanchor="block-3"><a href="#block-3">{{ trans('casino.roulette') }}</a></li>
        <li data-menuanchor="block-4"><a href="#block-4">{{ trans('casino.slots') }}</a></li>
    </ul>
    <div class="sections-container">
        <section class="section with-shadow welcome" style="background: url('media/images/bg/wellcome.jpg') center no-repeat; background-size: cover;">
            <div class="middle-shadow"></div>
            <div class="bitcoin-block">
                <span class="bitcoin-msg"><i class="bitcoin-icon"></i> {{ trans('casino.work_bitcoin') }}</span>
            </div>
            @if(Auth::guest())
            <div class="msg-popup">
			<span class="top-text">
				<span class="number-value">200%</span>
                {{ trans('casino.bonus') }}
            </span>
                <span class="middle-text">{!! trans('casino.after_first_deposit') !!}</span>
                <a href="#" class="registration-btn reg-btn">{{ trans('casino.registration') }}</a>
            </div>
            @endif
            <span class="side-name">{{ trans('casino.welcome') }}</span>
            <div class="main-title-block">
                <span class="category-title">{{ trans('casino.welcome') }}</span>
                <h1 class="game-name word-split">{{ trans('casino.casinobit') }}</h1>
                <div class="descr-block">
                    <span class="descr">{!! trans('casino.video_live_games') !!}</span>
                </div>
                <div class="main-btn-block">
                    <a href="{{ route('games', ['lang' => $currentLang]) }}" class="play-btn"><span class="btn-entry">{{ trans('casino.games') }}</span></a>
                </div>
            </div>
        </section>
        <section class="section with-shadow blackjack" style="background: url('media/images/bg/blackjack.jpg') center no-repeat; background-size: cover;">
            <div class="middle-shadow"></div>
            <div class="bitcoin-block">
                <span class="bitcoin-msg"><i class="bitcoin-icon"></i> {{ trans('casino.work_bitcoin') }}</span>
            </div>
            @if(Auth::guest())
                <div class="msg-popup">
			<span class="top-text">
				<span class="number-value">200%</span>
                {{ trans('casino.bonus') }}
            </span>
                    <span class="middle-text">{!! trans('casino.after_first_deposit') !!}</span>
                    <a href="#" class="registration-btn reg-btn">{{ trans('casino.registration') }}</a>
                </div>
            @endif
            <span class="side-name">{{ trans('casino.blackjack') }}</span>
            <div class="main-title-block">
                <span class="category-title">{{ trans('casino.live_games_second') }}</span>
                <h1 class="game-name word-split">{{ trans('casino.blackjack') }}</h1>
                <div class="descr-block">
                    <span class="descr">{!! trans('casino.video_live_games') !!}</span>
                </div>
                <div class="main-btn-block">
                    <a href="{{ route('games', ['type_id' => 10002, 'lang' => $currentLang]) }}" class="play-btn"><span class="btn-entry">{{ trans('casino.play_now') }}</span></a>
                </div>
            </div>
        </section>
        <section class="section with-shadow roulette" style="background: url('media/images/bg/roulette.jpg') center no-repeat; background-size: cover;">
            <div class="middle-shadow"></div>
            <div class="bitcoin-block">
                <span class="bitcoin-msg"><i class="bitcoin-icon"></i> {{ trans('casino.work_bitcoin') }}</span>
            </div>
            @if(Auth::guest())
                <div class="msg-popup">
			<span class="top-text">
				<span class="number-value">200%</span>
                {{ trans('casino.bonus') }}
            </span>
                    <span class="middle-text">{!! trans('casino.after_first_deposit') !!}</span>
                    <a href="#" class="registration-btn reg-btn">{{ trans('casino.registration') }}</a>
                </div>
            @endif
            <span class="side-name">{{ trans('casino.roulette') }}</span>
            <div class="main-title-block">
                <span class="category-title">{{ trans('casino.live_games_second') }}</span>
                <h1 class="game-name word-split">{{ trans('casino.roulette') }}</h1>
                <div class="descr-block">
                    <span class="descr">{!! trans('casino.video_live_games') !!}</span>
                </div>
                <div class="main-btn-block">
                    <a href="{{ route('games', ['type_id' => 10003, 'lang' => $currentLang]) }}" class="play-btn"><span class="btn-entry">{{ trans('casino.games') }}</span></a>
                </div>
            </div>
        </section>
        <section class="section with-shadow slots" style="background: url('media/images/bg/slots.jpg') center no-repeat; background-size: cover;">
            <div class="middle-shadow"></div>
            <div class="bitcoin-block">
                <span class="bitcoin-msg"><i class="bitcoin-icon"></i> {{ trans('casino.work_bitcoin') }}</span>
            </div>
            @if(Auth::guest())
                <div class="msg-popup">
			<span class="top-text">
				<span class="number-value">200%</span>
                {{ trans('casino.bonus') }}
            </span>
                    <span class="middle-text">{!! trans('casino.after_first_deposit') !!}</span>
                    <a href="#" class="registration-btn reg-btn">{{trans('casino.registration')}}</a>
                </div>
            @endif
            <span class="side-name">{{ trans('casino.slots') }}</span>
            <div class="main-title-block">
                <span class="category-title">{{ trans('casino.live_games_second') }}</span>
                <h1 class="game-name word-split">{{ trans('casino.slots') }}</h1>
                <div class="descr-block">
                    <span class="descr">{!! trans('casino.video_live_games') !!}</span>
                </div>
                <div class="main-btn-block">
                    <a href="{{ route('games', ['type_id' => 10001, 'lang' => $currentLang]) }}" class="play-btn"><span class="btn-entry">{{ trans('casino.games') }}</span></a>
                </div>
            </div>
        </section>
    </div>
    <div class="mobile-container">
        <section class="welcome-mob">
            <span class="side-name">{{ trans('casino.welcome') }}</span>
            <div class="main-title-block">
                <span class="category-title">{{ trans('casino.welcome') }}</span>
                <h1 class="game-name word-split">{{ trans('casino.casinobit') }} </h1>
                <div class="descr-block">
                    <span class="descr">{!! trans('casino.video_live_games') !!}</span>
                </div>
                <div class="main-btn-block">
                    <a href="{{ route('games', ['lang' => $currentLang]) }}" class="play-btn"><span class="btn-entry">{{ trans('casino.games') }}</span></a>
                </div>
            </div>
            <div class="games-listing-block">
                <ul class="games-listing">
                    @include('footer_links')
                </ul>
            </div>
            {{--<div class="bitcoin-block">--}}
                {{--<span class="bitcoin-msg"><i class="bitcoin-icon"></i> {{ trans('casino.work_bitcoin') }}</span>--}}
            {{--</div>--}}
            <div class="nav-block">
                <div class="dots-block"></div>
            </div>
        </section>
        <!-- <div class="games-block">
            <div class="games-slider">
                <div class="game-entry" style="background: url('media/images/bg/blackjack.jpg') center no-repeat; background-size: cover;">
                    <div class="main-title-block">
                        <span class="category-title">{{translate('Live Games')}}</span>
                        <h2 class="game-name word-split">{{translate('BlackJack')}}</h2>
                        <div class="main-btn-block">
                            {{--<a href="{{route('casino', [])}}" class="play-btn"><span class="btn-entry">{{translate('Play Now')}}</span></a>--}}
                        </div>
                    </div>
                </div>
                <div class="game-entry" style="background: url('media/images/bg/roulette.jpg') center no-repeat; background-size: cover;">
                    <div class="main-title-block">
                        <span class="category-title">{{translate('Live Games')}}</span>
                        <h2 class="game-name word-split">{{translate('Roulette')}}</h2>
                        <div class="main-btn-block">
                            {{--<a href="{{route('casino')}}" class="play-btn"><span class="btn-entry">{{translate('Games')}}</span></a>--}}
                        </div>
                    </div>
                </div>
                <div class="game-entry" style="background: url('media/images/bg/slots.jpg') center no-repeat; background-size: cover;">
                    <div class="main-title-block">
                        <span class="category-title">{{translate('Live Games')}}</span>
                        <h2 class="game-name word-split">{{translate('Slots')}}</h2>
                        <div class="main-btn-block">
                            {{--<a href="{{route('slots')}}" class="play-btn"><span class="btn-entry">{{translate('Games')}}</span></a>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
        <!-- <div class="free-games-block">
		<span class="top-text">
			<span class="number-value">5</span>
            {{translate('free games')}}
		</span>
            <span class="middle-text">{{translate('after registration')}}</span>
            <a href="#" class="registration-btn reg-btn"><span class="text">{{translate('Registration')}}</span></a>
        </div> -->
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
            <p>© All rights reserved</p>
        </div>
    </footer>
@endsection
