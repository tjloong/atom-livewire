const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js/app.js')
mix.js('resources/js/web.js', 'public/js/web.js')

// ckeditor5
mix.copy('vendor/jiannius/atom-livewire/ckeditor5/build', 'public/js/ckeditor5')

// css
mix.postCss('resources/css/app.css', 'public/css', [
    require('postcss-import')({ path: ['vendor/jiannius'] }),
    require('tailwindcss'),
])

mix.webpackConfig(require('./webpack.config'));

if (mix.inProduction()) {
    mix.version();
}