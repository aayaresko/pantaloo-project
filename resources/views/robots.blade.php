@if ($host != 'casinobit.io')
    User-agent: *
    Disallow: /
@else
    User-agent: *
    Disallow: *?*
    Allow: *.css
    Allow: *.js

    Sitemap: https://casinobit.io/sitemap.xml

@endif
