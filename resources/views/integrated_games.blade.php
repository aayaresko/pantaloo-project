@extends('layouts.app')

@php
if ($title == 'games') {
    $gameRoomTitle = 'casino.games';
} else {
    $gameRoomTitle = 'casino.type_' . str_replace(' ', '_', $title);
}
@endphp

@section('title', trans($gameRoomTitle))


@section('description')
{{ trans('casino.play') }} {{trans($gameRoomTitle)}} {{ trans('casino.game_room_description') }}
@endsection

@section('content')

    <div class="video-popup">
        <div class="video-container">
            <div class="game-entry colorGame" id="fs_section_img">
                {{--<img src="media/images/logo.png" alt="game">--}}
                <div class="gameLoadingWrapper">
                    <p class="gameLoading">
                        <span class="let1">l</span>
                        <span class="let2">o</span>
                        <span class="let3">a</span>
                        <span class="let4">d</span>
                        <span class="let5">i</span>
                        <span class="let6">n</span>
                        <span class="let7">g</span>
                    </p>
                </div>
            </div>          
            <div class="right-nav">
                <div class="expand-game"><img src="/images/expand.png" alt=""></div>
                <a href="" class="exit-btn"><span class="text">Exit</span></a>
            </div>
            <div class="bottom-nav">
                <a href="#" class="choose-other-link exit-btn">Choose other game</a>
            </div>
        </div>
    </div>

    <div class="slots-block top-shadow">
        <div class="bg mainBackGround"></div>
        <div class="block-heading">
            <h1 class="page-title"><span class="tittlePage">{{ trans($gameRoomTitle) }}</span></h1>
            <span class="subtitle">{{ trans('casino.choose_your_game') }}</span>
        </div>

        <div class="block-filter clearfix">
            <form action="#" method="post" id="gamesFiterForm">
                
                    <select class="js-example-basic-single type_of_game" name="type_of_game">
                        @if ($freeSpins === 1)
                            <option class="getFreeSpins" value="free_spins" >{{trans('casino.free_spin_games')}}</option>
                        @endif
                        <option value="0" selected>{{ trans('casino.all_categories') }}</option>
                        @foreach($gamesTypes as $gamesType)
                            @php
                                $codeLangType = 'casino.type_' . str_replace(' ', '_', $gamesType->name);
                                if (Lang::has($codeLangType)) {
                                    $nameType = trans($codeLangType);
                                } else {
                                    $nameType = $gamesType->name;
                                }                                                            
                                $def_name = str_replace(' ', '-', $gamesType->default_name);                                                            
                            $nameType = mb_convert_case($nameType, MB_CASE_TITLE);
                            $gamesType->nameType = $nameType;
                            @endphp
                            <option  data-link="{{$def_name}}" value="{{ $gamesType->id }}">{{ $nameType }}</option>
                        @endforeach
                    </select>
                    <select class="js-example-basic-single filter_provider" name="filter_provider">
                        <option value="0" selected>{{ trans('casino.all_providers') }}</option>
                        @foreach($gamesCategories as $gamesCategory)
                            <option value="{{ $gamesCategory->id }}">{{ mb_convert_case($gamesCategory->name, MB_CASE_TITLE) }}</option>
                        @endforeach
                    </select>
                   <div class="input-search">
                       <input type="text" name="search" placeholder="{{ trans('casino.search_game') }}"/>
                       <input type="submit" value=""/>
                   </div>
                   @if ($freeSpins === 1)
                      <div class="block-bonus-buttons" style="display: inline-block">
                          <a href="#" class="getFreeSpins"><span>{{trans('casino.free_spin_games')}}</span></a>
                      </div>
                   @endif
                  
                
            </form>
        </div>

        <div class="insertGames">
            <div class="block-container">
                <div class="noGamesFound">
                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAADTUlEQVRYhc2YXYhNURTHf/cYXjz4SPKieKCcUuMrTjQkcpKPByIlHkb5TCnmjo9HxKhBMsYkiQdfJUS2KBT2eBAPbEkyD5IXIZ5kXA/bvc7dd59z97mHufN/22vttc/vnrv23mudXKFQwEVSqEnADKAJGAeMBH4BH4HXwAOgG1BOC/5REPqJ/lw1QCnUGqAVmOD4zCfAfuC6y+RqgA0JYLOBLmC8I1hR04FraNBm4GXK+DJ5MXB54H4NcFFNB14AqzOsUfkGpVDtwDbL3ALwEBDAY3TuDQBGo/NyPjDVEncOGAEcqQWwLAelULuAfZZ5F4GdQei/Mx1SlO2JRuAQMM+yxirggml0zkEpVGCBKwDLg9C/krjKXz1Hv8lNwHHDdx79D7x3XAsoz0HbrpuSAi6qDmCxxe60s6PyAKRQzeg8iWphEPrP0rOVdAPYbNgmod+wszwplAe0GfaLQejfygBXVAf6uInqQJoFPPSvGh6x9QLrsnGVqdkYTwaGuAZ7QGjY7geh/z0rVUQvgR7Dtsw12APmGrZLGYFsOmOMnfPQo/K2eJSVxiJzzTGugR4wLDL+ib4h/rW+GONBroHWu7g/yQM+R8YNwKj/8JyhxviHa6AHvDFsMzPjVMpcs8c10APuGrYVWWksWmuM77gGesBtw9YkhRqcGemvJgJjDZvz/e6hK5BoHg4ETmfnKqnLGD+lclfHygtCvxdoMewrpFALspIBW9CNVlStaRbwAILQPwV8MnxCCtVYOxtLgWOG7QKVOZ+o6Dm4yOJ/JoVakhIMYD1w1WJPVaxCBDAI/W5gj2XONSnUWSmUS9s5Dd2zdMb4twMH0wBW9MVSqKPA1pj594DL6G7tK5BDH8LTgJXYmyab2oA81Ni4S6F2A3sdH1ar2oB8NUDrXRyE/j5gDvAqA8AVoD3B34LD3+3y6WMjsBF94LpIAofRqQC6xM8nzG8LQj/WXxWw9FShfPTRMQv94ah423wDPqDz8ybw1hJeM6QzYJyMxj1JNUH2ZT3YSnLOtUihKvx9XbCmhqxHRZ0Ksl4lvzNk7AfMPlCxqonbOC1SqFy9m6Zqb3JHvQEJQj8J8kTdASEW8mQQ+pv6BSCUIA+hC+fOIPQ3APwG1srnNRTffbMAAAAASUVORK5CYII=" alt="">
                    <br>
                    <p class="noGames">No games found</p>
                    <p>Try to change search parameters or</p>
                    <a href="{{ route('games', ['lang' => $currentLang]) }}" id="resetGames">reset filter</a>
                </div>
                <div class="games-entry">
                    <!-- games insert here by js -->
                </div>
            </div>

        </div>
        <div class="paginationGame">
           <button class="moreGames">load more</button>
        </div>
       
    </div>


   

     @include('footer_main')
@endsection

@section('js')
    <script>
        let dummy = "{{ $dummyPicture }}";
        let defaultTitle = "{{ trans("casino.{$titleDefault}") }}";
    </script>
    <script src="/assets/js/pages/integratedGames.js?v={{ time() }}"></script>
@endsection