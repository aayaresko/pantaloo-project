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
    <li><a href="{{route('page', $page->url)}}">{{$page->short_name}}</a></li>
@endforeach

<li><a href="{{route('support')}}">{{ trans('casino.support') }}</a></li>
<li><a href="{{route('bonus.promo')}}">{{ trans('casino.bonuses') }}</a></li>
<li><a href="{{$partnerPage}}" target="_blank">{{ trans('casino.affiliates') }}</a></li>

