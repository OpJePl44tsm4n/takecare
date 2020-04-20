let mix = require('laravel-mix');

mix.js('./assets/src/js/main.js', './assets/dist/js/main.min.js')
    .sass('./assets/src/js/main.scss', './assets/dist/styles/main.min.css')
    .copyDirectory('./assets/src/img', './assets/dist/img')
    .copyDirectory('./assets/src/fonts', './assets/dist/fonts');
