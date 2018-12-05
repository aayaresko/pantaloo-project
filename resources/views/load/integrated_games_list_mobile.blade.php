<div class="block-container">
    <div class="games-entry">
        @foreach($gameList as $game)
            <div class="single-game">
                <a href="/integratedGameLink/provider/{{ $game->provider_id }}/game/{{ $game->id }}">
                    <div class="game-preview" style="background: url('{{$game->image_filled}}')center no-repeat"></div>
                </a>
                <a href="/integratedGameLink/provider/{{ $game->provider_id }}/game/{{ $game->id }}"><span class="title">{{$game->name}}</span></a>
            </div>
        @endforeach
    </div>
</div>

<div class="paginationGame">
    {{$gameList->render()}}
</div>