const mix = require('laravel-mix');

mix.js('resources/js/web.js', 'public/js/web.js');

// tailwindcss
mix.postCss('resources/css/app.css', 'public/css', [
    require('postcss-import')({ path: ['vendor/jiannius'] }),
    require('tailwindcss'),
])

mix.webpackConfig(require('./webpack.config'));

if (mix.inProduction()) {
    mix.version();
}