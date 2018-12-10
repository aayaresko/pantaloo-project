@extends('layouts.app')

@section('title')
    Integrated Games
@endsection

@section('content')
    <div class="video-popup">
        <div class="video-container">
            <div class="game-entry colorGame">
                {{--<img src="media/images/logo.png" alt="game">--}}
                <div class="gameLoadingWrapper">
                    <h1 class = "gameLoading">
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
                <h2 class="region">Eastern Europe</h2>
                <span class="game-name">BlackJack</span>
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
        <div class="bg mainBackGround" ></div>
        <span class="side-title">Slots</span>
        <div class="block-heading">
            <h1 class="page-title">Slots</h1>
            <span class="subtitle">What you want to live play?</span>
        </div>

        <div class="block-filter clearfix">
            <form action="#" method="post">
                <div class="pull-left">
                    <select class="js-example-basic-single" name="type_of_game" id="type_of_game">
                        <option value="0" selected>All</option>
                        @foreach($gamesTypes as $gamesType)
                            <option value="{{ $gamesType->id }}">{{ $gamesType->name }}</option>
                        @endforeach
                    </select>
                    <select class="js-example-basic-single" name="filter_provider" id="filter_provider">
                        <option value="0" selected>All</option>
                        @foreach($gamesCategories as $gamesCategory)
                            <option value="{{ $gamesCategory->id }}">{{ $gamesCategory->name }}</option>
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

        <div class="insertGames">
        </div>
    </div>


    <div class="slots-block mobile top-shadow">
        <div class="bg"
             style="background: url('media/images/bg/slots.jpg') center no-repeat; background-size: cover;"></div>
        <div class="block-heading">
            <h1 class="page-title">Slots</h1>
            <span class="subtitle">What you want to live play?</span>
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
                    <select class="js-example-basic-single" name="type_of_game_mobile" id="type_of_game_mobile">
                        <option value="0" selected>All</option>
                        @foreach($gamesTypes as $gamesType)
                            <option value="{{ $gamesType->id }}">{{ $gamesType->name }}</option>
                        @endforeach
                    </select>
                    <select class="js-example-basic-single" name="filter_provider_mobile" id="filter_provider_mobile">
                        <option value="0" selected>All</option>
                        @foreach($gamesCategories as $gamesCategory)
                            <option value="{{ $gamesCategory->id }}">{{ $gamesCategory->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="pull-right">
                    <div class="input-search">
                        <input type="text" name="search_mobile" placeholder="Search game"/>
                        <input type="submit" value=""/>
                    </div>
                </div>
            </form>
        </div>

        <div class = "insertGamesMobile">

        </div>
    </div>

    <footer class="footer dark">
        <div class="games-listing-block">
            <ul class="games-listing">
                @include('footer_links')
            </ul>
        </div>
    </footer>
@endsection

@section('js')
    <script>
        let dummy = "{{ $dummyPicture }}";
    </script>
    <script src="/assets/js/pages/integratedGames.js?v={{ time() }}"></script>
@endsection