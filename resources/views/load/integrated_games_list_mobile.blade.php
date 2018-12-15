<div class="block-container">
    <div class="games-entry">
        @foreach($gameList as $game)
            <div class="single-game">
                @php
                    $image = is_null($game->our_image) ? $game->image_filled : $game->our_image;
                    if (!is_null($game->our_name)) {
                        $name = $game->our_name;
                    } else {
                        $name = $game->name;
                    }
                @endphp
                <a class="open_game" href="/integratedGameLink/provider/{{ $image }}/game/{{ $game->id }}">
                    <div class="game-preview" style="background: url('{{$game->image_filled}}')center no-repeat"></div>
                </a>
                <a class="open_game"
                   href="/integratedGameLink/provider/{{ $game->provider_id }}/game/{{ $game->id }}"><span
                            class="title">{{ $name }}</span></a>
            </div>
        @endforeach
    </div>
</div>

<div class="paginationGame">
    {{$gameList->render()}}
</div>