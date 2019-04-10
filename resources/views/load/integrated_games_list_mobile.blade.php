<div class="block-container">
    <div class="games-entry">
        @foreach($gameList as $game)
            <div class="single-game" onclick="enterFullscreen('fs_section_img')">
                <a class="open_game" href="/integratedGameLink/provider/{{ $game->provider_id }}/game/{{ $game->id }}">
                    <div class="game-preview"
                         style="background: url('{{ $game->image . '?v=' . time() }}')center no-repeat">
                    </div>
                </a>
                <a class="open_game"
                   href="/integratedGameLink/provider/{{ $game->provider_id }}/game/{{ $game->id }}">
                   <span class="title">{{$game->name}}</span>
                   <span class="games-block__name__category">{{ ucfirst($game->category) }}</span>
                    </a>
            </div>
        @endforeach
    </div>
</div>

<div class="paginationGame">
    {{$gameList->render()}}
</div>