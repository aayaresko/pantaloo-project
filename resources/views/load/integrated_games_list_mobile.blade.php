<div class="block-container">
    <div class="games-entry">
        @foreach($gameList as $game)
            <div class="single-game">
                <a href="{{route('integratedGame', $game)}}">
                    <div class="game-preview" style="background: url('{{$game->image_preview}}')center no-repeat"></div>
                </a>
                <a href="{{route('integratedGame', $game)}}"><span class="title">{{$game->name}}</span></a>
            </div>
        @endforeach
    </div>
</div>

{{$gameList->render()}}