import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue2';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue(), // Use Vue 2 plugin
    ],
    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },
});
