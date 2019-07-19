@foreach ($languages as $language)
    @if ($language == 'jp')
    <link rel="alternate" hreflang="ja" href="{{ LangAlternatePageUrl($language) }}"/>
    @elseif ($language == 'vn')
    <link rel="alternate" hreflang="vi" href="{{ LangAlternatePageUrl($language) }}"/>
    @else
    <link rel="alternate" hreflang="{{ $language }}" href="{{ LangAlternatePageUrl($language) }}"/>
    @endif
@endforeach
