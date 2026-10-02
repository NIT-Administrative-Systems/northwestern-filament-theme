import { readFileSync } from 'node:fs';
import { createRequire } from 'node:module';
import type { PluginOption } from 'vite';

const tokensPackage = '@nu-appdev/northwestern-tokens';

function readPackageFile(rootDir: string, entry: string): string {
    const require = createRequire(`${rootDir}/`);

    return readFileSync(require.resolve(`${tokensPackage}/${entry}`), 'utf8');
}

function header(rootDir: string, description: string): string {
    const { version } = JSON.parse(readPackageFile(rootDir, 'package.json')) as { version: string };

    return [
        `/* Northwestern Filament Theme — ${description} */`,
        `/* Copied from ${tokensPackage} ${version} at build time. Do not edit. */`,
        '',
        '',
    ].join('\n');
}

/**
 * Emit the tokens package's standalone files next to theme.css.
 *
 * - tokens.css: the @font-face rules and the --nu-* custom properties, for
 *   pages that render without Filament (for example an error layout).
 * - tailwind-tokens.css: the Tailwind v4 @theme mapping. It needs the
 *   custom properties from theme.css or tokens.css.
 */
export function northwesternTokensPlugin(rootDir: string): PluginOption {
    return {
        name: 'nu-northwestern-tokens',
        generateBundle() {
            this.emitFile({
                fileName: 'tokens.css',
                source:
                    header(rootDir, 'Northwestern fonts and design tokens')
                    + readPackageFile(rootDir, 'fonts.css')
                    + '\n'
                    + readPackageFile(rootDir, 'tokens.css'),
                type: 'asset',
            });

            this.emitFile({
                fileName: 'tailwind-tokens.css',
                source: header(rootDir, 'Tailwind v4 design tokens') + readPackageFile(rootDir, 'tailwind.css'),
                type: 'asset',
            });
        },
    };
}
