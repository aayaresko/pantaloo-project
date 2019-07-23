let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

// mix.js('resources/js/app.js', 'public/js')
//    .sass('resources/sass/app.scss', 'public/css');


//libs css
mix.styles([
   'public/css/bootstrap/css/bootstrap.min.css',
   'public/vendors/animate/animate.css',
   'public/vendors/fullPage/jquery.fullPage.css',
   'public/css/select2.min.css',
   'public/vendors/magnific-popup/magnific-popup.css',
   'public/css/datatables.css',
   'public/css/countrySelect.css',
], 'public/css/libs.min.css');

//main css
mix.styles('public/css/main.css','public/css/main.min.css')


mix.scripts([
   'public/vendors/jquery/jquery-3.0.0.min.js',
   'public/vendors/fullPage/scrolloverflow.min.js',
   'public/vendors/fullPage/jquery.fullPage.min.js',
   'public/assets/js/select2.min.js',
   'public/vendors/magnific-popup/jquery.magnific-popup.min.js'
], 'public/js/libs.min.js');

mix.babel([
   'public/vendors/main.js',
], 'public/js/main.min.js');