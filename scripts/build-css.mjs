#!/usr/bin/env node

/**
 * Concatenates individual CSS source files into dist/theme.css,
 * and generates dist/tailwind-tokens.css from variables.css.
 */

import { existsSync, readFileSync, writeFileSync, mkdirSync, statSync } from 'fs';
import { transform } from 'lightningcss';
import { colors, box } from 'consola/utils';

const c = colors;
const checkOnly = process.argv.includes('--check');

function formatSize(bytes) {
  const kb = bytes / 1024;
  return kb >= 1 ? `${kb.toFixed(1)} kB` : `${bytes} B`;
}

function fileChanged(path, newContent) {
  if (!existsSync(path)) return true;
  return readFileSync(path, 'utf8') !== newContent;
}

const files = [
  'variables',
  'typography',
  'layout',
  'buttons',
  'forms',
  'tables',
  'navigation',
  'components',
  'utilities',
];

for (const file of files) {
  const path = `resources/css/${file}.css`;
  if (!existsSync(path)) {
    console.error(c.red(`\n  Missing source file: ${path}\n`));
    process.exit(1);
  }
}

const start = performance.now();

if (!checkOnly) {
  mkdirSync('dist', { recursive: true });
}

const results = [];

// --- dist/theme.css ---
const css = files.map((f) => readFileSync(`resources/css/${f}.css`, 'utf8')).join('\n\n');
const { code } = transform({
  filename: 'theme.css',
  code: Buffer.from(css),
  minify: true,
});
const themeContent = `/* Northwestern Filament Theme — auto-generated, do not edit */\n` + code.toString();
const themeChanged = fileChanged('dist/theme.css', themeContent);
if (!checkOnly) writeFileSync('dist/theme.css', themeContent);
results.push({ path: 'dist/theme.css', size: themeContent.length, rawSize: css.length, changed: themeChanged });

// --- dist/tailwind-tokens.css ---
const variablesCss = readFileSync('resources/css/variables.css', 'utf8');

const rootMatch = variablesCss.match(/:root\s*\{([^}]+)\}/s);
if (!rootMatch) {
  console.error(c.red('\n  Could not find :root block in variables.css\n'));
  process.exit(1);
}

const rootBlock = rootMatch[1];
const varPattern = /--nu-([\w-]+):\s*([^;]+);/g;
let match;

const groups = {
  'Purple scale': [],
  'Grays': [],
  'Brand colors': [],
  'Dark brand colors': [],
  'Semantic colors': [],
};

while ((match = varPattern.exec(rootBlock)) !== null) {
  const name = match[1];
  const varName = `--nu-${name}`;

  if (/^purple-\d+$/.test(name)) {
    groups['Purple scale'].push({ name, varName });
  } else if (name.startsWith('black-')) {
    groups['Grays'].push({ name, varName });
  } else if (name.startsWith('dark-')) {
    groups['Dark brand colors'].push({ name, varName });
  } else if (['color-success', 'color-info', 'color-warning', 'color-danger'].includes(name)) {
    const shortName = name.replace('color-', '');
    groups['Semantic colors'].push({ name: shortName, varName });
  } else if (['green', 'teal', 'blue', 'yellow', 'gold', 'orange'].includes(name)) {
    groups['Brand colors'].push({ name, varName });
  }
}

const tokenLines = [
  '/* Northwestern Filament Theme — Tailwind v4 design tokens */',
  '/* Auto-generated — do not edit */',
  '',
  '@theme {',
];

let tokenCount = 0;
for (const [groupName, vars] of Object.entries(groups)) {
  if (vars.length === 0) continue;
  tokenLines.push(`    /* ${groupName} */`);
  for (const { name, varName } of vars) {
    tokenLines.push(`    --color-nu-${name}: var(${varName});`);
    tokenCount++;
  }
  tokenLines.push('');
}

if (tokenLines[tokenLines.length - 1] === '') tokenLines.pop();
tokenLines.push('}');
tokenLines.push('');

const tokensContent = tokenLines.join('\n');
const tokensChanged = fileChanged('dist/tailwind-tokens.css', tokensContent);
if (!checkOnly) writeFileSync('dist/tailwind-tokens.css', tokensContent);
results.push({
  path: 'dist/tailwind-tokens.css',
  size: tokensContent.length,
  changed: tokensChanged,
});

if (results[0].size < 100) {
  console.error(c.red(`\n  dist/theme.css is too small (${results[0].size}B) — check source files\n`));
  process.exit(1);
}

const elapsed = (performance.now() - start).toFixed(0);
const changedCount = results.filter((r) => r.changed).length;

console.log();
console.log(
  box(
    `${c.bold(`${files.length}`)} source files ${c.dim('→')} ${c.bold(`${results.length}`)} dist files  ${c.dim('·')}  ${c.bold(`${tokenCount}`)} design tokens`,
    {
      title: `${c.magenta(c.bold('Northwestern'))} Filament Theme`,
      style: { borderColor: 'magenta', padding: 1 },
    },
  ),
);

const maxPath = Math.max(...results.map((r) => r.path.length));
const maxSize = Math.max(...results.map((r) => formatSize(r.size).length));

for (const { path, size, rawSize, changed } of results) {
  const icon = changed ? c.green('●') : c.dim('○');
  const label = changed ? c.green('updated') : c.dim('unchanged');
  const paddedPath = path.padEnd(maxPath);
  const paddedSize = formatSize(size).padStart(maxSize);
  const minified = rawSize ? `  ${c.dim(`(${formatSize(rawSize)} → ${formatSize(size)})`)}` : '';

  console.log(`  ${icon} ${c.cyan(paddedPath)}  ${c.dim(paddedSize)}  ${label}${minified}`);
}

console.log();
if (checkOnly && changedCount > 0) {
  console.log(`  ${c.red(`${changedCount} file(s) out of date`)} ${c.dim(`— run ${c.cyan('pnpm build:css')} and commit the result`)}`);
  console.log();
  process.exit(1);
} else if (changedCount > 0) {
  console.log(`  ${c.yellow(`${changedCount} file(s) updated`)} ${c.dim(`in ${elapsed}ms`)}`);
} else {
  console.log(`  ${c.green('All files up to date')} ${c.dim(`in ${elapsed}ms`)}`);
}
console.log();
