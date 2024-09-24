import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'; // Import plugin Vue untuk Vite
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue(),
    ],
    resolve: {
        alias: {
          // Alias untuk memastikan Vue menggunakan versi yang mendukung runtime template compilation
          'vue': 'vue/dist/vue.esm-bundler.js',
          '@': path.resolve(__dirname, 'resources/js'), // Alias untuk src folder
        },
    },
});
