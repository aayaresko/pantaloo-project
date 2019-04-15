@php
//this code is very badly
$pages = \App\Page::orderBy('id');

if($is_main) $pages = $pages->where('is_main', 1);

if(\Illuminate\Support\Facades\Config::get('lang') == 'en')
{
    $pages = $pages->where('parent_id', 0);
}
else
{
    $pages = $pages->where('parent_id', '!=', 0);
}

$pages = $pages->get();
@endphp

@foreach($pages as $page)
    <li><a href="{{route('page', ['page_url' => $page->url, 'lang' => $currentLang])}}">{{$page->short_name}}</a></li>
@endforeach

<li class="order-support"><a href="{{route('support', ['lang' => $currentLang])}}" class="support">{{ trans('casino.frq') }}</a></li>
<li class="order-bonuses"><a href="{{route('bonus.promo', ['lang' => $currentLang])}}" class="bonuses">{{ trans('casino.bonuses') }}</a></li>
<li class="order-afiliate"><a href="{{$partnerPage}}" class="afiliate" target="_blank">{{ trans('casino.affiliates') }}</a></li>

