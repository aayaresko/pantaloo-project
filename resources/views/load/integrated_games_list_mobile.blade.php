      @foreach($gameList as $key => $game)
            <div @if ($key == 0) class="single-game firstGame" @else class="single-game" class="single-game" @endif onclick="enterFullscreen('fs_section_img')">
                <a class="open_game" href="/integratedGameLink/provider/{{ $game->provider_id }}/game/{{ $game->id }}"
                   data-name="{{ $game->name }}" data-category="{{ $game->category }}">
                    <div class="game-preview"
                         style="background: url('{{ $game->image . '?v=' . time() }}')center no-repeat">
                    </div>
                </a>
                <a class="open_game"
                   href="/integratedGameLink/provider/{{ $game->provider_id }}/game/{{ $game->id }}"
                   data-name="{{ $game->name }}" data-category="{{ $game->category }}">
                   <span class="title">{{$game->name}}</span>
                   <span class="games-block__name__category">{{ ucfirst($game->category) }}</span>
                    </a>
            </div>
        @endforeach