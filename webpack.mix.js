const mix = require('laravel-mix');

mix.sass('resources/css/app.scss', 'public/css')
   .sourceMaps();


mix.js('resources/js/app.js', 'public/js')
   .sourceMaps();