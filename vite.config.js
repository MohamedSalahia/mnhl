import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
// import react from '@vitejs/plugin-react';
// import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/admin/app.js',
                'resources/css/admin/app.css',
            ],
            refresh: false
        }),
    ],
    server: {
        hmr: false,
        watch: {
            usePolling: true, // Enable polling
            ignored: ['**/node_modules/**', '**/.git/**'],
        },
    },
});