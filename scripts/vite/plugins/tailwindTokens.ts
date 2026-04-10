import { readFileSync } from 'node:fs';
import { resolve } from 'node:path';
import type { PluginOption } from 'vite';

const tokenRules = [
    { group: 'Purple scale', test: (name: string) => /^purple-\d+$/.test(name) },
    { group: 'Grays', test: (name: string) => name.startsWith('black-') },
    { group: 'Dark brand colors', test: (name: string) => name.startsWith('dark-') },
    {
        group: 'Semantic colors',
        test: (name: string) => ['color-success', 'color-info', 'color-warning', 'color-danger'].includes(name),
        rename: (name: string) => name.replace('color-', ''),
    },
    {
        group: 'Brand colors',
        test: (name: string) => ['green', 'teal', 'blue', 'yellow', 'gold', 'orange'].includes(name),
    },
] as const;

function buildTailwindTokens(rootDir: string): string {
    const variablesCss = readFileSync(resolve(rootDir, 'resources/css/variables.css'), 'utf8');
    const rootMatch = variablesCss.match(/:root\s*\{([^}]+)}/s);

    if (! rootMatch) {
        throw new Error('Could not find :root block in resources/css/variables.css');
    }

    const vars: Array<{ name: string; varName: string }> = [];
    const pattern = /--nu-([\w-]+):\s*([^;]+);/g;
    let match: RegExpExecArray | null;

    while ((match = pattern.exec(rootMatch[1])) !== null) {
        vars.push({ name: match[1], varName: `--nu-${match[1]}` });
    }

    const groups = new Map<string, Array<{ name: string; varName: string }>>(
        tokenRules.map((rule) => [rule.group, []]),
    );

    for (const variable of vars) {
        for (const rule of tokenRules) {
            if (! rule.test(variable.name)) {
                continue;
            }

            groups.get(rule.group)?.push({
                name: 'rename' in rule ? rule.rename(variable.name) : variable.name,
                varName: variable.varName,
            });
        }
    }

    const lines = [
        '/* Northwestern Filament Theme — Tailwind v4 design tokens */',
        '/* Auto-generated — do not edit */',
        '',
        '@theme {',
    ];

    for (const [group, entries] of groups) {
        if (entries.length === 0) {
            continue;
        }

        lines.push(`    /* ${group} */`);

        for (const entry of entries) {
            lines.push(`    --color-nu-${entry.name}: var(${entry.varName});`);
        }

        lines.push('');
    }

    if (lines.at(-1) === '') {
        lines.pop();
    }

    lines.push('}', '');

    return lines.join('\n');
}

export function tailwindTokensPlugin(rootDir: string): PluginOption {
    return {
        name: 'nu-tailwind-tokens',
        generateBundle() {
            this.emitFile({
                fileName: 'tailwind-tokens.css',
                source: buildTailwindTokens(rootDir),
                type: 'asset',
            });
        },
    };
}
