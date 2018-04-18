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
mix.options({
	extractVueStyles: false
});
mix.js('resources/assets/js/main.js', 'public/js')
   .sass('resources/assets/sass/main.scss', 'public/css')
   .sass('node_modules/bootstrap/scss/bootstrap-reboot.scss', 'public/css')
   .sass('node_modules/bootstrap/scss/bootstrap-grid.scss', 'public/css')
   .copy('node_modules/animate.css/animate.min.css', 'public/css')
   .extract(['vue', 'vue2-google-maps', /*'lodash', 'popper.js', 'axios'*/]);
