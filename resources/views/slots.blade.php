@extends('layouts.app')

@section('title')
    {{translate('Slots')}}
@endsection

@section('description')
    {{ $meta['description'] }}
@endsection

@section('keywords')
    {{ $meta['keywords'] }}
@endsection

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
                <a href="#" class="choose-other-link exit-btn">Choose other game</a>
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
                        <option value="">All</option>
                        @foreach(\App\Type::all() as $type)
                            <option value="{{$type->id}}">{{$type->name}}</option>
                        @endforeach
                    </select>
                    <select class="js-example-basic-single" name="filter_provider" id="filter_provider">
                        <option value="" selected="">All</option>
                        @foreach(\App\Category::all() as $category)
                            <option value="{{$category->id}}">{{$category->name}}</option>
                        @endforeach
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
                <div class="single-game ng-scope" data-slot_id="{{$slot->id}}">
                    <div class="games-block ng-scope">
                        <span class="games-block__item ng-scope">
                            <img class="games-block__image show-animated" src="{{$slot->image}}" />
                        </span>
                        <div class="games-block__wrap ng-scope">
                            <div class="games-block__action">
                                <div class="games-block__buttons is-full">
                                    <a href="#" class="open_game games-block__button games-block__button_play-real ng-binding">{{ translate('Play') }}</a>
                                    @if($slot->demo_url)
                                    <a href="{{ $slot->demo_url }}" class="games-block__button games-block__button_play-fun ng-binding">{{ translate('Free demo') }}</a>
                                    @endif
                                </div>
                            </div>
                            <span class="games-block__name ng-binding">{{$slot->display_name}}</span>
                        </div>
                    </div>
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
                    <select class="js-example-basic-single" name="type_of_game_mobile" id="type_of_game_mobile">
                        <option value="">All</option>
                        @foreach(\App\Type::all() as $type)
                            <option value="{{$type->id}}">{{$type->name}}</option>
                        @endforeach
                    </select>
                    <select class="js-example-basic-single" name="filter_provider_mobile" id="filter_provider_mobile">
                        <option value="" selected="">All</option>
                        @foreach(\App\Category::all() as $category)
                            <option value="{{$category->id}}">{{$category->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="pull-right">
                    <div class="input-search">
                        <input type="text" name="search_mobile" placeholder="Search game" />
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
                        <a href="{{route('slot', $slot)}}"><span class="title">{{$slot->display_name}}</span></a>
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

@section('js')
    <script>
        var free_game = 0;
        var free_spins = @if(Auth::check()) {{Auth::user()->free_spins }} @else 0 @endif;
        var type = window.location.hash.substr(1);

        if(type == 'free_spins' && free_spins > 0) {
            $(document).ready(function () {
                free_game = 1;
                $('.video-popup').addClass('popup-slot');
                var html = '<iframe width="100%" height="100%" allowtransparency="true" allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true" allowFullScreen="true" frameborder="0" scrolling="no"  src="/free"></iframe>';
                $('.video-popup .game-entry').html(html);
                $('.video-popup').addClass('active');
                $('header.header').addClass('active');

                stopFreeGame();
            });
        }

        function stopFreeGame() {
            $.ajax({
                type: "GET",
                url: '/free/stop', // serializes the form's elements.
                dateType: 'json',
                success: function(data)
                {
                    if(data.stop)
                    {
                        setTimeout(function () {
                            $('.video-popup iframe').attr('src', '/slot/1');
                        }, 3000);
                    }
                    else setTimeout(stopFreeGame, 1000);
                },
                error: function (data) {
                    //alert(data);
                }
            });
        }
    </script>
@endsection