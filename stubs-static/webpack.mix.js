const mix = require('laravel-mix');

mix.js('resources/js/web/main.js', 'public/js/web.js');

// tailwindcss
mix.postCss('resources/css/app.css', 'public/css', [
    require('postcss-import'),
    require('tailwindcss'),
])

mix.webpackConfig(require('./webpack.config'));

if (mix.inProduction()) {
    mix.version();
}