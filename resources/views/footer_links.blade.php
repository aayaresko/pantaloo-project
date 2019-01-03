{{--<li><a href="{{route('slots')}}" class="slots">{{translate('Slots')}}</a></li>--}}
<li><a href="{{  route('games', ['type_id' => 1]) }}" class="slots">{{translate('Slots')}}</a></li>
<li><a href="{{  route('games', ['type_id' => 2]) }}" class="bjack">{{translate('BlackJack')}}</a></li>
<li><a href="{{  route('games', ['type_id' => 3]) }}" class="roulette">{{translate('Roulette')}}</a></li>
<li><a href="{{ route('games', ['type_id' => 4]) }}" class="baccarat">{{translate('Baccarat')}}</a></li>
<li><a href="{{ route('games', ['type_id' => 5]) }}" class="bonumbers">{{translate('Bet On Numbers')}}</a></li>
<li><a href="{{ route('games', ['type_id' => 6]) }}" class="keno">{{translate('Keno')}}</a></li>
<li><a href="{{  route('games', ['type_id' => 7]) }}" class="poker">{{translate('Poker')}}</a></li>