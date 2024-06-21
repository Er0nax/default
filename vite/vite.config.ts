import {defineConfig} from 'vite'
import {resolve} from 'path';
import {fileURLToPath, URL} from "node:url";
import checker from 'vite-plugin-checker'

export default defineConfig({
    plugins: [
        checker({
            // e.g. use TypeScript check
            typescript: true,
        }),
    ],
    resolve: {
        alias: {
            // @ts-ignore
            '@': fileURLToPath(new URL('./src', import.meta.url)),
        }
    },
    optimizeDeps: {
        force: true
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