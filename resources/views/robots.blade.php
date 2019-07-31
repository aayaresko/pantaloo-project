@if ($currentHost != $defaultHost)
    User-agent: *
    Disallow: /
@else
    User-agent: *
    Disallow: *?*
    Allow: *.css
    Allow: *.js

    Sitemap: {{ $mainUrl }}/sitemap.xml

@endif
