@foreach ($languages as $language)
    <link rel="alternate" hreflang="{{ $language }}" href="{{ LangAlternatePageUrl($language) }}"/>
@endforeach
