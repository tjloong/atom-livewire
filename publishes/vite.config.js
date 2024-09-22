import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // base
                'resources/js/base.js',
                'resources/css/base.css',

                // app portal
                // 'resources/js/app.js',
                // 'resources/css/app.css', 
                
                // auth
                // 'resources/js/auth.js',
                // 'resources/css/auth.css', 

                // web
                // 'resources/js/web.js',
                // 'resources/css/web.css', 

                // onboarding
                // 'resources/js/onboarding.js',
                // 'resources/css/onboarding.css', 

                // pdf
                // 'resources/css/pdf.css', 
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@atom': '/vendor/jiannius/atom-livewire',
        },
    },
});
