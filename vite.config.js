import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/formulario.js',
                'resources/css/formulario.css',
                'resources/css/editar_eliminar.css',
                'resources/js/editar_eliminar.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});