<div class="block-container">
    <div class="games-entry">
        @foreach($gameList as $game)
            <div class="single-game ng-scope" data-slot_id="{{$game->id}}">
                <div class="games-block ng-scope">
                    <div class="preloaderPictureGame">
                        <span class="games-block__item ng-scope">
                            <img class="games-block__image show-animated" src="{{$game->image_filled}}" onerror="handleImage(this);"/>
                        </span>
                    </div>
                    <div class="games-block__wrap ng-scope">
                        <div class="games-block__action">
                            <div class="games-block__buttons is-full">
                                <a href="/integratedGameLink/provider/{{ $game->provider_id }}/game/{{ $game->id }}"
                                   class="open_game games-block__button games-block__button_play-real ng-binding">Play</a>
                            </div>
                        </div>
                        <span class="games-block__name ng-binding">{{$game->name}}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="paginationGame">
    {{$gameList->render()}}
</div>