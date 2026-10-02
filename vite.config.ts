import { dirname, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';
import { defineConfig } from 'vite';
import { additionalAssetsPlugin } from './scripts/vite/plugins/additionalAssets';
import { northwesternTokensPlugin } from './scripts/vite/plugins/northwesternTokens';

const __filename = fileURLToPath(import.meta.url);
const rootDir = dirname(__filename);

export default defineConfig({
    plugins: [northwesternTokensPlugin(rootDir), additionalAssetsPlugin(rootDir)],
    build: {
        outDir: 'dist',
        emptyOutDir: true,
        sourcemap: true,
        cssMinify: 'lightningcss',
        rolldownOptions: {
            input: resolve(rootDir, 'resources/css/theme.css'),
            output: {
                assetFileNames: '[name][extname]',
            },
        },
        minify: false,
    },
});
