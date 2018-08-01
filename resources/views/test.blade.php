@extends('layouts.app')

@section('content')
    <div class="video-popup">
        <div class="video-container">
            <div class="game-entry">
                <img src="media/images/game.jpg" alt="game">
            </div>
            <div class="left-info">
                <h2 class="region">Eastern Europe</h2>
                <span class="game-name">BlackJack</span>
            </div>
            <div class="right-nav">
                <a href="" class="exit-btn"><span class="text">Exit</span></a>
            </div>
            <div class="bottom-nav">
                <a href="#" class="choose-other-link exit-btn">Choice other game</a>
            </div>
        </div>
    </div>
    <div class="slots-block desk top-shadow">
        <div class="bg" style="background: url('media/images/bg/slots.jpg') center no-repeat; background-size: cover;"></div>
        <span class="side-title">{{translate('Slots')}}</span>
        <div class="block-heading">
            <h1 class="page-title">{{translate('Slots')}}</h1>
            <span class="subtitle">{{translate('What you want to live play?')}}</span>
        </div>
        <div class="block-filter clearfix">
            <form action="#" method="post">
                <div class="pull-left">
                    <select class="js-example-basic-single" name="type_of_game" id="type_of_game">
                        <option value="">Select Type of Game</option>
                        <option value="type_1">Type 1</option>
                        <option value="type_2">Type 2</option>
                        <option value="type_3">Type 3</option>
                        <option value="type_4">Type 4</option>
                        <option value="type_5">Type 5</option>
                    </select>
                    <select class="js-example-basic-single" name="filter_provider" id="filter_provider">
                        <option value="">Filter by Game Provider</option>
                        <option value="provider_1">Provider 1</option>
                        <option value="provider_2">Provider 2</option>
                        <option value="provider_3">Provider 3</option>
                        <option value="provider_4">Provider 4</option>
                        <option value="provider_5">Provider 5</option>
                    </select>
                </div>
                <div class="pull-right">
                    <div class="input-search">
                        <input type="text" name="search" placeholder="Search game" />
                        <input type="submit" value="" />
                    </div>
                </div>
            </form>
        </div>
        <div class="block-container">
            <div class="games-entry">
                @foreach($slots as $slot)
                    <div class="single-game" data-slot_id="{{$slot->id}}">
                        <a href="#" class="open_game">
                            <div class="game-preview" style="background: url('{{$slot->image}}')center no-repeat"></div>
                        </a>
                        <a href="#" class="open_game"><span class="title">{{$slot->name}}</span></a>
                    </div>
                @endforeach

            </div>

        </div>
        <div class="btn-block">
            <a href="#" class="live-btn">{{translate('Live Games')}}</a>
        </div>

        {{$slots->render()}}
    </div>
    <div class="slots-block mobile top-shadow">
        <div class="bg" style="background: url('media/images/bg/slots.jpg') center no-repeat; background-size: cover;"></div>
        <div class="block-heading">
            <h1 class="page-title">{{translate('Slots')}}</h1>
            <span class="subtitle">{{translate('What you want to live play?')}}</span>
        </div>

        <div class="btn-block">
            <a href="#" class="live-btn">{{translate('Live Games')}}</a>
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
                    <select class="js-example-basic-single" name="type_of_game" id="type_of_game">
                        <option value="">Select Type of Game</option>
                        <option value="type_1">Type 1</option>
                        <option value="type_2">Type 2</option>
                        <option value="type_3">Type 3</option>
                        <option value="type_4">Type 4</option>
                        <option value="type_5">Type 5</option>
                    </select>
                    <select class="js-example-basic-single" name="filter_provider" id="filter_provider">
                        <option value="">Filter by Game Provider</option>
                        <option value="provider_1">Provider 1</option>
                        <option value="provider_2">Provider 2</option>
                        <option value="provider_3">Provider 3</option>
                        <option value="provider_4">Provider 4</option>
                        <option value="provider_5">Provider 5</option>
                    </select>
                </div>
                <div class="pull-right">
                    <div class="input-search">
                        <input type="text" name="search" placeholder="Search game" />
                        <input type="submit" value="" />
                    </div>
                </div>
            </form>
        </div>
        <div class="block-container">
            <div class="games-entry">
                @foreach($slots as $slot)
                    <div class="single-game">
                        <a href="{{route('slot', $slot)}}">
                            <div class="game-preview" style="background: url('{{$slot->image}}')center no-repeat"></div>
                        </a>
                        <a href="{{route('slot', $slot)}}"><span class="title">{{$slot->name}}</span></a>
                    </div>
                @endforeach
            </div>
        </div>

        {{$slots->render()}}

    </div>

    <footer class="footer dark">
        <div class="games-listing-block">
            <ul class="games-listing">
                @include('footer_links')
            </ul>
        </div>
    </footer>
@endsection
