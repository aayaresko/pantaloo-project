@php
    $placeholder = '::lang::';
    $langPattern = implode('|', $languages);
    $relAlternateTemplate =  url(preg_replace("~^($langPattern)(?=/|$)~",$placeholder, \Illuminate\Support\Facades\Request::path()), [], \Helpers\GeneralHelper::isSecureProtocol());
@endphp
@if (strpos($relAlternateTemplate, $placeholder) !== false)
    @foreach ($languages as $language)
<link rel="alternate" hreflang="{{ $language }}" href="{{ str_replace($placeholder, $language, $relAlternateTemplate) }}"/>
    @endforeach
@endif