import { resolve } from 'node:path';
import { build, type PluginOption } from 'vite';

export function additionalAssetsPlugin(rootDir: string): PluginOption {
    // Guard against infinite recursion: each build() call below triggers
    // closeBundle again, so we early-return after the first invocation.
    let hasBuilt = false;

    return {
        name: 'nu-package-assets',
        async closeBundle() {
            if (hasBuilt) {
                return;
            }

            hasBuilt = true;

            // To add a new JS asset, duplicate the block below and update
            // the entry path, name, and fileName.
            //
            // await build({
            //     configFile: false,
            //     build: {
            //         outDir: 'dist',
            //         emptyOutDir: false,
            //         sourcemap: true,
            //         cssMinify: 'lightningcss',
            //         lib: {
            //             entry: resolve(rootDir, 'resources/js/my-feature.ts'),
            //             formats: ['iife'],
            //             name: 'NorthwesternMyFeature',
            //             fileName: () => 'my-feature.js',
            //         },
            //         minify: false,
            //     },
            // });
            //
            // Add a second build() call with minify: 'esbuild' and
            // fileName: () => 'my-feature.min.js' for a minified copy.
        },
    };
}
