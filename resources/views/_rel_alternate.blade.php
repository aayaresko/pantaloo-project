@php
    $langPattern = implode('|', $languages);

    $relAlternateTemplate =  url(preg_replace("~^($langPattern)/~",'/::lang::/', \Illuminate\Support\Facades\Request::path()), [], \Helpers\GeneralHelper::isSecureProtocol());
@endphp
@foreach ($languages as $language)
    <link rel="alternate" hreflang="{{ $language }}" href="{{ str_replace('::lang::', $language, $relAlternateTemplate)  }}"/>
@endforeach