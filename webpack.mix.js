const mix = require('laravel-mix');

mix.js('resources/js/like.js', 'public/js')
   .css('resources/css/app.css', 'public/css')
   .sass('resources/sass/app.scss', 'public/css')
   .styles('public/css/like.css', 'public/css/like.css');
