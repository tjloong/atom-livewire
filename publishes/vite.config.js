import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/base.css', 
                // 'resources/css/app.css', 
                // 'resources/css/web.css', 
                // 'resources/css/auth.css', 
                // 'resources/css/onboarding.css', 
                // 'resources/css/pdf.css', 
                'resources/js/base.js',
                // 'resources/js/app.js',
                // 'resources/js/web.js',
                // 'resources/js/auth.js',
                // 'resources/js/onboarding.js',
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
