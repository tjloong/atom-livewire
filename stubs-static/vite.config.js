import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/pdf.css', 
                'resources/css/app.css', 
                'resources/js/web.js',
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
