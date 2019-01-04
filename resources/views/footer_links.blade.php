{{--<li><a href="{{route('slots')}}" class="slots">{{translate('Slots')}}</a></li>--}}
<li><a href="{{  route('games', ['type_id' => 10001]) }}" class="slots">{{translate('Slots')}}</a></li>
<li><a href="{{  route('games', ['type_id' => 10002]) }}" class="bjack">{{translate('BlackJack')}}</a></li>
<li><a href="{{  route('games', ['type_id' => 10003]) }}" class="roulette">{{translate('Roulette')}}</a></li>
<li><a href="{{ route('games', ['type_id' => 10004]) }}" class="baccarat">{{translate('Baccarat')}}</a></li>
<li><a href="{{ route('games', ['type_id' => 10005]) }}" class="bonumbers">{{translate('Bet On Numbers')}}</a></li>
<li><a href="{{ route('games', ['type_id' => 10006]) }}" class="keno">{{translate('Keno')}}</a></li>
<li><a href="{{  route('games', ['type_id' => 10007]) }}" class="poker">{{translate('Poker')}}</a></li>