<li class="slots"><a href="{{  route('games', ['type_name' => 'slots', 'lang' => $currentLang]) }}" class="slots">{{ trans('casino.type_slots') }}</a></li>
<li class="roulette"><a href="{{  route('games', ['type_name' => 'roulette', 'lang' => $currentLang]) }}" class="roulette">{{ trans('casino.type_roulette') }}</a></li>
<li class="bjack"><a href="{{  route('games', ['type_name' => 'blackjack', 'lang' => $currentLang]) }}" class="bjack">{{ trans('casino.type_blackjack') }}</a></li>
<li class="poker"><a href="{{  route('games', ['type_name' => 'poker', 'lang' => $currentLang]) }}" class="poker">{{ trans('casino.type_poker') }}</a></li>
<li class="baccarat"><a href="{{ route('games', ['type_name' => 'baccarat', 'lang' => $currentLang]) }}" class="baccarat">{{ trans('casino.type_baccarat') }}</a></li>
<li class="dice"><a href="{{  route('games', ['type_name' => 'dice', 'lang' => $currentLang]) }}" class="dice">{{ trans('casino.type_dice') }}</a></li>
<li class="bonumbers"><a href="{{ route('games', ['type_name' => 'bet_on_numbers', 'lang' => $currentLang]) }}" class="bonumbers">{{ trans('casino.type_bet_on_numbers')}}</a></li>
<li class="keno"><a href="{{ route('games', ['type_name' => 'keno', 'lang' => $currentLang]) }}" class="keno">{{ trans('casino.type_keno') }}</a></li>

