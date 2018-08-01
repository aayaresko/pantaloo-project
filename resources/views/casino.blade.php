@extends('layouts.app')

@section('title')
    {{translate($name)}}
@endsection

@section('description')
    {{ $description }}
@endsection

@section('keywords')
    {{ $keywords }}
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
    <div class="live-block top-shadow">
        <div class="bg" style="background: url('media/images/bg/blackjack.jpg') center no-repeat; background-size: cover;"></div>
        <div class="block-heading">
            <h1 class="page-title">{{translate($name)}}</h1>
            <span class="subtitle">{{translate('What you want to live play?')}}</span>
        </div>
        <div class="map-block">
            @if($latin)
            <div class="game-item{{ isset($iso_code) && $iso_code == 'US' ? ' disabled_casino' : '' }}" id="item-1">
                <a href="{{ $latin }}" class="open_casino">
                    <div class="preview">
                        <img src="media/images/live-games/1.png" alt="img">
                    </div>
                </a>
                <h3 class="region">{{translate('LATIN')}}</h3>
                <span class="category">{{translate($name)}}</span>
            </div>
            @endif
            @if($baltic)
            <div class="game-item{{ isset($iso_code) && $iso_code == 'US' ? ' disabled_casino' : '' }}" id="item-2">
                <a href="{{ $baltic }}" class="open_casino">
                    <div class="preview">
                        <img src="media/images/live-games/2.png" alt="img">
                    </div>
                </a>
                <h3 class="region">{{translate('Baltic')}}</h3>
                <span class="category">{{translate($name)}}</span>
            </div>
            @endif

            @if($europe)
            <div class="game-item sm top{{ isset($iso_code) && $iso_code == 'US' ? ' disabled_casino' : '' }}" id="item-3">
                <h3 class="region">{{translate('Europe')}}</h3>
                <span class="category">{{translate($name)}}</span>
                <a href="{{ $europe }}" class="open_casino">
                    <div class="preview">
                        <img src="media/images/live-games/3.png" alt="img">
                    </div>
                </a>
            </div>
            @endif
            @if($est_europe)
            <div class="game-item{{ isset($iso_code) && $iso_code == 'US' ? ' disabled_casino' : '' }}" id="item-4">
                <a href="{{ $est_europe }}" class="open_casino">
                    <div class="preview">
                        <img src="media/images/live-games/4.png" alt="img">
                    </div>
                </a>
                <h3 class="region">{{translate('Eastern Europe')}}</h3>
                <span class="category">{{translate($name)}}</span>
            </div>
            @endif
            @if($usa)
            <div class="game-item" id="item-6">
                <a href="{{ $usa }}" class="open_casino">
                    <div class="preview">
                        <img src="media/images/live-games/3.png" alt="img">
                    </div>
                </a>
                <h3 class="region">{{translate('USA')}}</h3>
                <span class="category">{{translate($name)}}</span>
            </div>
            @endif
        </div>
        <div class="btn-block">
            <a href="#" class="live-btn">Live Games</a>
        </div>
    </div>
    <div class="live-block mobile top-shadow">
        <div class="bg"></div>
        <div class="block-heading">
            <h1 class="page-title">{{translate($name)}}</h1>
            <span class="subtitle">{{translate('What you want to live play?')}}</span>
        </div>
        <div class="btn-block">
            <a href="{{route('slots')}}" class="slots-btn">{{translate('Slots')}}</a>
            <a href="#" class="live-btn">Live Games</a>
        </div>
        <div class="games-listing-block hidden-mob">
            <ul class="games-listing">
                @include('footer_links')
            </ul>
        </div>
        <div class="live-listing-block bjack">
            <span class="side-name">{{translate('BLACKJACK')}}</span>
            <ul class="live-listing">
                @if($latin)
                <li>
                    <div class="preview">
                        <a href="{{ $latin }}">
                            <img src="media/images/live-games/1.png" alt="image">
                        </a>
                    </div>
                    <div class="text-container">
                        <h2 class="region">{{translate('LATIN')}}</h2>
                        <span class="category">{{translate($name)}}</span>
                    </div>
                    <a href="{{ $latin }}" class="show-modal-btn"><i class="fa fa-chevron-right" aria-hidden="true"></i></a>
                </li>
                @endif

                @if($baltic)
                <li>
                    <div class="preview">
                        <a href="{{ $baltic }}">
                            <img src="media/images/live-games/2.png" alt="image">
                        </a>
                    </div>
                    <div class="text-container">
                        <h2 class="region">{{translate('Baltic')}}</h2>
                        <span class="category">{{translate($name)}}</span>
                    </div>
                    <a href="{{ $baltic }}" class="show-modal-btn"><i class="fa fa-chevron-right" aria-hidden="true"></i></a>
                </li>
                @endif
                @if($europe)
                <li>
                    <div class="preview">
                        <a href="{{ $europe }}">
                            <img src="media/images/live-games/3.png" alt="image">
                        </a>
                    </div>
                    <div class="text-container">
                        <h2 class="region">{{translate('Europe')}}</h2>
                        <span class="category">{{translate($name)}}</span>
                    </div>
                    <a href="{{ $europe }}" class="show-modal-btn"><i class="fa fa-chevron-right" aria-hidden="true"></i></a>
                </li>
                @endif

                @if($est_europe)
                <li>
                    <div class="preview">
                        <a href="{{ $est_europe }}">
                            <img src="media/images/live-games/4.png" alt="image">
                        </a>
                    </div>
                    <div class="text-container">
                        <h2 class="region">{{translate('Eastern Europe')}}</h2>
                        <span class="category">{{translate($name)}}</span>
                    </div>
                    <a href="{{ $est_europe }}" class="show-modal-btn"><i class="fa fa-chevron-right" aria-hidden="true"></i></a>
                </li>
                @endif
            </ul>
        </div>
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
    $(document).ready(function () {

        if(is_mobile)
        {
            console.log('mobile');

            if($('ul.live-listing li').length == 1)
            {
                var href = $('ul.live-listing li a:eq(0)').attr('href');

                window.location = href;
            }
        }
        else
        {
            console.log('desctop');

            if($('div.game-item').length == 1)
            {
                $('div.game-item a').click();
            }
        }
    });

</script>
@endsection