const mix = require('laravel-mix');

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

mix.styles([
   'resources/assets/plantilla/css/font-awesome.min.css',
   'resources/assets/plantilla/css/simple-line-icons.min.css',
   'resources/assets/plantilla/css/bootstrap.min.css',
   'resources/assets/plantilla/css/ruang-admin.min.css'
], 'public/css/plantilla.css')

.scripts([
   'resources/assets/plantilla/js/jquery.min.js',
   'resources/assets/plantilla/js/jquery.easing.min.js',
   'resources/assets/plantilla/js/bootstrap.bundle.min.js',
   'resources/assets/plantilla/js/ruang-admin.min.js',
   'resources/assets/plantilla/js/sweetalert2.js',
], 'public/js/plantilla.js')

.js(['resources/js/app.js'], 'public/js/app.js')
.sass('resources/sass/app.scss', 'public/css');;