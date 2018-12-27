@extends('layouts.app')

@section('description')
    Добро пожаловать в Casinobit, где Вы найдете лучшие игры биткоин и сможете сыграть в онлайн режиме. Casinobit - большой мир игровых систем. Здесь Вы найдете лучшие игры биткоин и сможете сыграть в онлайн режиме. Русское лото биткоин - это лотерея для всех кто хочет испытать удачу.
@endsection

@section('keywords')
    биткоин игры, лото биткоин
@endsection

@section('content')
    <ul class="sections-nav">
        <li data-menuanchor="block-2" class="active"><a href="#block-2">{{translate('Blackjack')}}</a></li>
        <li data-menuanchor="block-3"><a href="#block-3">{{translate('Roulette')}}</a></li>
        <li data-menuanchor="block-4"><a href="#block-4">{{translate('Slots')}}</a></li>
    </ul>
    <div class="sections-container">
        <section class="section with-shadow welcome" style="background: url('media/images/bg/wellcome.jpg') center no-repeat; background-size: cover;">
            <div class="middle-shadow"></div>
            <div class="bitcoin-block">
                <span class="bitcoin-msg"><i class="bitcoin-icon"></i> {{translate('We work only with bitcoin')}}</span>
            </div>
            @if(Auth::guest())
            <div class="msg-popup">
			<span class="top-text">
				<span class="number-value">200%</span>
                {{translate('bonus')}}
            </span>
                <span class="middle-text">{!! translate('after first deposit') !!}</span>
                <a href="#" class="registration-btn reg-btn">{{translate('Registration')}}</a>
            </div>
            @endif
            <span class="side-name">{{translate('Welcome')}}</span>
            <div class="main-title-block">
                <span class="category-title">{{translate('Welcome')}}</span>
                <h1 class="game-name word-split">{{translate('Casinobit')}}</h1>
                <div class="descr-block">
                    <span class="descr">{!! translate('video live games') !!}</span>
                </div>
                <div class="main-btn-block">
                    <a href="{{route('casino')}}" class="play-btn"><span class="btn-entry">{{translate('Games')}}</span></a>
                </div>
            </div>
        </section>
        <section class="section with-shadow blackjack" style="background: url('media/images/bg/blackjack.jpg') center no-repeat; background-size: cover;">
            <div class="middle-shadow"></div>
            <div class="bitcoin-block">
                <span class="bitcoin-msg"><i class="bitcoin-icon"></i> {{translate('We work only with bitcoin')}}</span>
            </div>
            @if(Auth::guest())
                <div class="msg-popup">
			<span class="top-text">
				<span class="number-value">200%</span>
                {{translate('bonus')}}
            </span>
                    <span class="middle-text">{!! translate('after first deposit') !!}</span>
                    <a href="#" class="registration-btn reg-btn">{{translate('Registration')}}</a>
                </div>
            @endif
            <span class="side-name">{{translate('BlackJack')}}</span>
            <div class="main-title-block">
                <span class="category-title">{{translate('Live Games')}}</span>
                <h1 class="game-name word-split">{{translate('BlackJack')}}</h1>
                <div class="descr-block">
                    <span class="descr">{!! translate('video live games') !!}</span>
                </div>
                <div class="main-btn-block">
                    <a href="{{route('blackjack')}}" class="play-btn"><span class="btn-entry">{{translate('Play Now')}}</span></a>
                </div>
            </div>
        </section>
        <section class="section with-shadow roulette" style="background: url('media/images/bg/roulette.jpg') center no-repeat; background-size: cover;">
            <div class="middle-shadow"></div>
            <div class="bitcoin-block">
                <span class="bitcoin-msg"><i class="bitcoin-icon"></i> {{translate('We work only with bitcoin')}}</span>
            </div>
            @if(Auth::guest())
                <div class="msg-popup">
			<span class="top-text">
				<span class="number-value">200%</span>
                {{translate('bonus')}}
            </span>
                    <span class="middle-text">{!! translate('after first deposit') !!}</span>
                    <a href="#" class="registration-btn reg-btn">{{translate('Registration')}}</a>
                </div>
            @endif
            <span class="side-name">{{translate('Roulette')}}</span>
            <div class="main-title-block">
                <span class="category-title">{{translate('Live Games')}}</span>
                <h1 class="game-name word-split">{{translate('Roulette')}}</h1>
                <div class="descr-block">
                    <span class="descr">{!! translate('video live games') !!}</span>
                </div>
                <div class="main-btn-block">
                    <a href="{{route('roulette')}}" class="play-btn"><span class="btn-entry">{{translate('Games')}}</span></a>
                </div>
            </div>
        </section>
        <section class="section with-shadow slots" style="background: url('media/images/bg/slots.jpg') center no-repeat; background-size: cover;">
            <div class="middle-shadow"></div>
            <div class="bitcoin-block">
                <span class="bitcoin-msg"><i class="bitcoin-icon"></i> {{translate('We work only with bitcoin')}}</span>
            </div>
            @if(Auth::guest())
                <div class="msg-popup">
			<span class="top-text">
				<span class="number-value">200%</span>
                {{translate('bonus')}}
            </span>
                    <span class="middle-text">{!! translate('after first deposit') !!}</span>
                    <a href="#" class="registration-btn reg-btn">{{translate('Registration')}}</a>
                </div>
            @endif
            <span class="side-name">{{translate('Slots')}}</span>
            <div class="main-title-block">
                <span class="category-title">{{translate('Live Games')}}</span>
                <h1 class="game-name word-split">{{translate('Slots')}}</h1>
                <div class="descr-block">
                    <span class="descr">{!! translate('video live games') !!}</span>
                </div>
                <div class="main-btn-block">
                    <a href="{{route('slots')}}" class="play-btn"><span class="btn-entry">{{translate('Games')}}</span></a>
                </div>
            </div>
        </section>
    </div>
    <div class="mobile-container">
        <section class="welcome-mob">
            <span class="side-name">{{translate('Welcome')}}</span>
            <div class="main-title-block">
                <span class="category-title">{{translate('Welcome')}}</span>
                <h1 class="game-name word-split">{{translate('Casinobit')}} </h1>
                <div class="descr-block">
                    <span class="descr">{!! translate('video live games') !!}</span>
                </div>
                <div class="main-btn-block">
                    <a href="{{route('casino')}}" class="play-btn"><span class="btn-entry">{{translate('Games')}}</span></a>
                </div>
            </div>
            <div class="games-listing-block">
                <ul class="games-listing">
                    @include('footer_links')
                </ul>
            </div>
            <div class="bitcoin-block">
                <span class="bitcoin-msg"><i class="bitcoin-icon"></i> {{translate('We work only with bitcoin')}}</span>
            </div>
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
                            <a href="{{route('casino')}}" class="play-btn"><span class="btn-entry">{{translate('Play Now')}}</span></a>
                        </div>
                    </div>
                </div>
                <div class="game-entry" style="background: url('media/images/bg/roulette.jpg') center no-repeat; background-size: cover;">
                    <div class="main-title-block">
                        <span class="category-title">{{translate('Live Games')}}</span>
                        <h2 class="game-name word-split">{{translate('Roulette')}}</h2>
                        <div class="main-btn-block">
                            <a href="{{route('casino')}}" class="play-btn"><span class="btn-entry">{{translate('Games')}}</span></a>
                        </div>
                    </div>
                </div>
                <div class="game-entry" style="background: url('media/images/bg/slots.jpg') center no-repeat; background-size: cover;">
                    <div class="main-title-block">
                        <span class="category-title">{{translate('Live Games')}}</span>
                        <h2 class="game-name word-split">{{translate('Slots')}}</h2>
                        <div class="main-btn-block">
                            <a href="{{route('slots')}}" class="play-btn"><span class="btn-entry">{{translate('Games')}}</span></a>
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
    <footer class="footer">
        <div class="msg-block">
            <span class="msg">{{translate('What you want to play?')}}</span>
        </div>
        <div class="games-listing-block">
            <ul class="games-listing">
                @include('footer_links')
            </ul>
        </div>
    </footer>
@endsection
