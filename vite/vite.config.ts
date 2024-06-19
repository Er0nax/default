import {defineConfig} from 'vite'
import {resolve} from 'path';
import {fileURLToPath, URL} from "node:url";
import checker from "vite-plugin-checker";
import basicSsl from '@vitejs/plugin-basic-ssl';

export default defineConfig({
    plugins: [
        // in order to add vue... add the vue Plugin
        // vue(),
        checker({
            typescript: true,
            eslint: {
                // no vue
                lintCommand: 'eslint --ext .js,.ts src'
                // with vue
                //lintCommand: 'eslint --ext .js,.vue,.ts src'
            },
            overlay: true
        }),
        basicSsl(),
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