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

mix.webpackConfig({
    externals: {
        "jquery": "jQuery"
    }
});

mix.options({
    	processCssUrls: false
	})
	.js('./assets/src/js/main.js', './assets/dist/js/main.min.js')
    .js('./assets/src/js/mapbox-simple-init.js', './assets/dist/js/mapbox-simple-init.js')
	.js('./assets/src/js/wp-api.js', './assets/dist/js/wp-api.min.js')
    .sass('./assets/src/styles/main.scss', './assets/dist/styles/main.min.css')
    .copyDirectory('./assets/src/img', './assets/dist/img')
    .copyDirectory('./assets/src/fonts', './assets/dist/fonts');
