<div class="block-container">
    <div class="games-entry">
        <div style="width: 100px; height: 100px;"></div>
        @foreach($gameList as $game)
            <div class="single-game ng-scope" data-slot_id="{{$game->id}}">
                <div class="games-block ng-scope">
                    <span class="games-block__item ng-scope">
                        <img class="games-block__image show-animated" src="{{$game->image_preview}}"/>
                    </span>
                    <div class="games-block__wrap ng-scope">
                        <div class="games-block__action">
                            <div class="games-block__buttons is-full">
                                <a href="{{route('integratedGame', $game)}}"
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

{{$gameList->render()}}