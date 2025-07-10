const mix = require('laravel-mix');

mix.css('resources/css/sidebar.css', 'public/css')
   .js('resources/js/app.js', 'public/js')
   .sourceMaps();
