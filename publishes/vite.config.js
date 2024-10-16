import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
                'resources/js/web.js',
                'resources/js/auth.js',
                'resources/js/onboarding.js',

                'resources/css/app.css', 
                'resources/css/pdf.css',
                'resources/css/web.css', 
                'resources/css/auth.css', 
                'resources/css/onboarding.css', 
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
