@if ($host != 'casinobit.io')
    User-agent: *
    Disallow: /
@else
    User-agent: *
    Disallow: */logout
    Disallow: */deposit
    Disallow: */bonus
    Disallow: */withdraw
    Disallow: */settings
    Disallow: */password/reset
    Disallow: /login
    Disallow: *?*
    Allow: *.css
    Allow: *.js

    Sitemap: https://casinobit.io/sitemap.xml
@endif
