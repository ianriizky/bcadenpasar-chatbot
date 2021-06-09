const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

// mix.postCss('resources/css/app.css', 'public/css', [
//     require('postcss-import'),
//     require('tailwindcss'),
//     require('autoprefixer'),
// ]);

// mix.js('resources/js/app.js', 'public/js');

mix.sass('resources/sass/stisla/style.scss', 'public/css/stisla');
mix.sass('resources/sass/stisla/components.scss', 'public/css/stisla');
