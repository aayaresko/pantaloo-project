@extends('layouts.app')

@section('title')
    Games
@endsection

@section('content')

    <div class="video-popup">
        <div class="video-container">
            <div class="game-entry colorGame" id="fs_section_img">
                {{--<img src="media/images/logo.png" alt="game">--}}
                <div class="gameLoadingWrapper">
                    <h1 class="gameLoading">
                        <span class="let1">l</span>
                        <span class="let2">o</span>
                        <span class="let3">a</span>
                        <span class="let4">d</span>
                        <span class="let5">i</span>
                        <span class="let6">n</span>
                        <span class="let7">g</span>
                    </h1>
                </div>
            </div>
            <div class="left-info">
                <h2 class="region"><span class="tittlePage">{{ ucfirst($title) }}</span></h2>
                <span class="game-name"><span class="tittlePage">{{ ucfirst($title) }}</span></span>
            </div>
            <div class="right-nav">
                <div class="expand-game"><img src="images/expand.png" alt=""></div>
                <a href="" class="exit-btn"><span class="text">Exit</span></a>
            </div>
            <div class="bottom-nav">
                <a href="#" class="choose-other-link exit-btn">Choose other game</a>
            </div>
        </div>
    </div>

    <div class="slots-block desk top-shadow">
        <div class="bg mainBackGround"></div>
        <span class="side-title"><span class="tittlePage">{{ ucfirst($title) }}</span></span>
        <div class="block-heading">
            <h1 class="page-title"><span class="tittlePage">{{ ucfirst($title) }}</span></h1>
            <span class="subtitle">{{ trans('casino.choose_your_game') }}</span>
        </div>

        <div class="block-filter clearfix">
            <form action="#" method="post">
                <div class="pull-left">
                    <select class="js-example-basic-single type_of_game" name="type_of_game">

                        <option value="0" selected>{{ trans('casino.all') }}</option>
                        @foreach($gamesTypes as $gamesType)
                            @php
                                $codeLangType = 'casino.type_' . str_replace(' ', '_', $gamesType->name);
                                if (Lang::has($codeLangType)) {
                                    $nameType = trans($codeLangType);
                                } else {
                                    $nameType = $gamesType->name;
                                }
                            $nameType = mb_convert_case($nameType, MB_CASE_TITLE);
                            $gamesType->nameType = $nameType;
                            @endphp
                            <option value="{{ $gamesType->id }}">{{ $nameType }}</option>
                        @endforeach
                    </select>
                    <select class="js-example-basic-single filter_provider" name="filter_provider">
                        <option value="0" selected>{{ trans('casino.all') }}</option>
                        @foreach($gamesCategories as $gamesCategory)
                            <option value="{{ $gamesCategory->id }}">{{ mb_convert_case($gamesCategory->name, MB_CASE_TITLE) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="pull-right">
                    <div class="input-search">
                        <input type="text" name="search" placeholder="{{ trans('casino.search_game') }}"/>
                        <input type="submit" value=""/>
                    </div>
                </div>
            </form>
        </div>

        <div class="insertGames">
        </div>
    </div>


    <div class="slots-block mobile top-shadow">
        <div class="bg"
             style="background: url('media/images/bg/slots.jpg') center no-repeat; background-size: cover;"></div>
        <div class="block-heading">
            <h1 class="page-title"><span class="tittlePage">{{ ucfirst($title) }}</span></h1>
            <span class="subtitle">{{ trans('casino.choose_your_game') }}</span>
        </div>
        <div class="btn-block">
            <a href="#" class="live-btn">Live Games</a>
        </div>
        <div class="games-listing-block hidden-mob">
            <ul class="games-listing">
                <li><a href="#" class="bjack">BlackJack</a></li>
                <li><a href="#" class="roulette">Roulette</a></li>
                <li><a href="#" class="baccarat">Baccarat</a></li>
                <li><a href="#" class="bonumbers">Bet On Numbers</a></li>
                <li><a href="#" class="keno">Keno</a></li>
            </ul>
        </div>

        <div class="block-filter clearfix">
            <form action="#" method="post">
                <div class="pull-left">
                    <select class="js-example-basic-single type_of_game" name="type_of_game">
                        <option value="0" selected>All</option>
                        @foreach($gamesTypes as $gamesType)
                            <option value="{{ $gamesType->id }}">{{ $gamesType->nameType }}</option>
                        @endforeach
                    </select>
                    <select class="js-example-basic-single filter_provider" name="filter_provider">
                        <option value="0" selected>{{ trans('casino.all') }}</option>
                        @foreach($gamesCategories as $gamesCategory)
                            <option value="{{ $gamesCategory->id }}">{{ mb_convert_case($gamesCategory->name, MB_CASE_TITLE) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="pull-right">
                    <div class="input-search">
                        <input type="text" name="search" placeholder="Search game"/>
                        <input type="submit" value=""/>
                    </div>
                </div>
            </form>
        </div>

        <div class="insertGamesMobile">

        </div>
    </div>

    <footer class="footer footer-static">
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

@section('js')
    <script>
        let dummy = "{{ $dummyPicture }}";
        let defaultTitle = "{{ $titleDefault }}";
    </script>
    <script src="/assets/js/pages/integratedGames.js?v={{ time() }}"></script>
@endsection