import { fileURLToPath, URL } from 'node:url'

import { homedir } from 'os'
import { resolve } from 'path'

import fs from 'fs';

import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin';

const host = 'tc.loc';

// https://vitejs.dev/config/
export default defineConfig({
    css: {
        devSourcemap: true
    },
    plugins: [
        laravel({
            publicDirectory: 'resources/dist',
            buildDirectory: 'vendor/nocte/modeldiver',
            input: [
                'resources/js/main.js',
                'resources/css/main.css',
            ],

            refresh: true,
        }),
    ],
    build: {
        outDir: 'resources/dist',
        assetsDir: '.',
        write: true,
        minify: 'terser'
    },
    server: detectServerConfig(host),
    resolve: {
        alias: {
            '@': fileURLToPath(new URL('./resources/js', import.meta.url))
        },
        extensions: ['.js', '.ts', '.jsx', '.tsx', '.json', '.vue', '.mjs']
    },
})

function detectServerConfig(host) {

    let keyPath = resolve(homedir() + `/.config/valet/Certificates/${host}.key`)
    let certificatePath = resolve(homedir() + `/.config/valet/Certificates/${host}.crt`)

    if (!fs.existsSync(keyPath)) {
        return {}
    }

    if (!fs.existsSync(certificatePath)) {
        return {}
    }

    return {
        hmr: { host },
        host,
        https: {
            key: fs.readFileSync(keyPath),
            cert: fs.readFileSync(certificatePath),
        },
    }
}
