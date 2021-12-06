const mix = require('laravel-mix');

mix.js('resources/js/app/main.js', 'public/js/app.js');
mix.js('resources/js/web/main.js', 'public/js/web.js');

// ckeditor5
mix.copy('resources/js/ckeditor5', 'public/js/ckeditor5');

// tailwindcss
mix.postCss('resources/css/app.css', 'public/css', [
    require('postcss-import'),
    require('tailwindcss'),
])

mix.webpackConfig(require('./webpack.config'));

if (mix.inProduction()) {
    mix.version();
}