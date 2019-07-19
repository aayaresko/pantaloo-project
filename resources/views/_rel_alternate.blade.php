@foreach ($languages as $language)

    @if (array_key_exists($language, $currentLangCodes))
        <link rel="alternate" hreflang="{{ $currentLangCodes[$language] }}" href="{{ LangAlternatePageUrl($language) }}"/>
    @else
        <link rel="alternate" hreflang="{{ $language }}" href="{{ LangAlternatePageUrl($language) }}"/>
    @endif

@endforeach
