import {defineConfig} from 'vite'
import {resolve} from 'path';
import {fileURLToPath, URL} from "node:url";

export default defineConfig({
    resolve: {
        alias: {
            // @ts-ignore
            '@': fileURLToPath(new URL('./src', import.meta.url)),
            '~bootstrap': resolve(__dirname, 'node_modules/bootstrap'),
        }
    },
    build: {
        outDir: resolve(__dirname, '../web/assets/js'),
        emptyOutDir: true,
        lib: {
            entry: resolve(__dirname, 'src/main.ts'),
            name: 'Site',
            formats: ['umd'],
            fileName: 'site',
        },
    },
})