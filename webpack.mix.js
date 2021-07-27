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

mix.combine('./node_modules/select2/dist/css/select2.min.css', 'public/node_modules/select2/dist/css/select2.min.css');
mix.combine('./node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css', 'public/node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css')
mix.combine('./node_modules/datatables.net-select-bs4/css/select.bootstrap4.min.css', 'public/node_modules/datatables.net-select-bs4/css/select.bootstrap4.min.css')

mix.combine('./node_modules/select2/dist/js/select2.full.min.js', 'public/node_modules/select2/dist/js/select2.full.min.js');
mix.combine('./node_modules/datatables/media/js/jquery.dataTables.min.js', 'public/node_modules/datatables/media/js/jquery.dataTables.min.js');
mix.combine('./node_modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js', 'public/node_modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js');
mix.combine('./node_modules/datatables.net-select-bs4/js/select.bootstrap4.min.js', 'public/node_modules/datatables.net-select-bs4/js/select.bootstrap4.min.js');
mix.combine('./node_modules/datatables.net-plugins/i18n/id.json', 'public/node_modules/datatables.net-plugins/i18n/id.json');
