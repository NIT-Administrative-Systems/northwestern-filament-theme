#!/usr/bin/env node
// @ts-check

/**
 * Concatenates individual CSS source files into dist/theme.css,
 * and generates dist/tailwind-tokens.css from variables.css.
 *
 * @typedef {{ name: string, varName: string }} TokenVar
 * @typedef {{ path: string, size: number, gzipSize: number, rawSize?: number, changed: boolean, meta?: string }} BuildResult
 * @typedef {{ group: string, test: (name: string) => boolean, rename?: (name: string) => string }} TokenGroupRule
 */

import { existsSync, readFileSync, writeFileSync, mkdirSync } from 'node:fs';
import { gzipSync } from 'node:zlib';
import { transform } from 'lightningcss';
import { colors } from 'consola/utils';

const config = {
  srcDir: 'resources/css',
  distDir: 'dist',
  banner: 'Northwestern Filament Theme',
  fileBanner: '/* Northwestern Filament Theme — auto-generated, do not edit */',
  tokenBanner: [
    '/* Northwestern Filament Theme — Tailwind v4 design tokens */',
    '/* Auto-generated — do not edit */',
  ],
};

/** @type {string[]} Source file names (without extension), concatenated in order. */
const SOURCE_FILES = [
  'variables',
  'typography',
  'layout',
  'buttons',
  'forms',
  'tables',
  'navigation',
  'modals',
  'dropdowns',
  'badges',
  'notifications',
  'widgets',
  'sections',
  'pagination',
  'components',
  'utilities',
];

/** @type {TokenGroupRule[]} Rules that classify CSS variables into token groups. */
const TOKEN_GROUP_RULES = [
  { group: 'Purple scale', test: (name) => /^purple-\d+$/.test(name) },
  { group: 'Grays', test: (name) => name.startsWith('black-') },
  { group: 'Dark brand colors', test: (name) => name.startsWith('dark-') },
  {
    group: 'Semantic colors',
    test: (name) => ['color-success', 'color-info', 'color-warning', 'color-danger'].includes(name),
    rename: (name) => name.replace('color-', ''),
  },
  {
    group: 'Brand colors',
    test: (name) => ['green', 'teal', 'blue', 'yellow', 'gold', 'orange'].includes(name),
  },
];

const c = colors;
const checkOnly = process.argv.includes('--check');

/** @param {number} bytes */
function formatSize(bytes) {
  const kb = bytes / 1024;
  return kb >= 1 ? `${kb.toFixed(1)} kB` : `${bytes} B`;
}

/**
 * @param {string} path
 * @param {string} newContent
 */
function fileChanged(path, newContent) {
  if (!existsSync(path)) return true;
  return readFileSync(path, 'utf8') !== newContent;
}

/**
 * Reads and concatenates all source CSS files, then minifies with lightningcss.
 * @returns {{ raw: string, minified: string }}
 */
function buildThemeCss() {
  const raw = SOURCE_FILES.map((f) => readFileSync(`${config.srcDir}/${f}.css`, 'utf8')).join('\n\n');
  const { code } = transform({
    filename: 'theme.css',
    code: Buffer.from(raw),
    minify: true,
  });
  const minified = `${config.fileBanner}\n` + code.toString();
  return { raw, minified };
}

/**
 * Parses the :root block from variables.css and extracts CSS custom properties.
 * @param {string} css — The raw contents of variables.css.
 * @returns {{ name: string, varName: string }[]}
 */
function parseRootVariables(css) {
  const rootMatch = css.match(/:root\s*\{([^}]+)}/s);
  if (!rootMatch) {
    console.error(c.red('\n  Could not find :root block in variables.css\n'));
    process.exit(1);
  }

  /** @type {{ name: string, varName: string }[]} */
  const vars = [];
  const pattern = /--nu-([\w-]+):\s*([^;]+);/g;
  let match;
  while ((match = pattern.exec(rootMatch[1])) !== null) {
    vars.push({ name: match[1], varName: `--nu-${match[1]}` });
  }
  return vars;
}

/**
 * Groups parsed CSS variables into named token groups using TOKEN_GROUP_RULES.
 * @param {{ name: string, varName: string }[]} vars
 * @returns {Map<string, TokenVar[]>}
 */
function groupTokens(vars) {
  /** @type {Map<string, TokenVar[]>} */
  const groups = new Map(TOKEN_GROUP_RULES.map((r) => [r.group, []]));

  for (const { name, varName } of vars) {
    const rule = TOKEN_GROUP_RULES.find((r) => r.test(name));
    if (rule) {
      const tokenName = rule.rename ? rule.rename(name) : name;
      groups.get(rule.group).push({ name: tokenName, varName });
    }
  }
  return groups;
}

/**
 * Generates the Tailwind v4 @theme CSS from grouped tokens.
 * @param {Map<string, TokenVar[]>} groups
 * @returns {{ content: string, tokenCount: number }}
 */
function generateTokensCss(groups) {
  const lines = [...config.tokenBanner, '', '@theme {'];

  let tokenCount = 0;
  for (const [groupName, vars] of groups) {
    if (vars.length === 0) continue;
    lines.push(`    /* ${groupName} */`);
    for (const { name, varName } of vars) {
      lines.push(`    --color-nu-${name}: var(${varName});`);
      tokenCount++;
    }
    lines.push('');
  }

  if (lines[lines.length - 1] === '') lines.pop();
  lines.push('}', '');

  return { content: lines.join('\n'), tokenCount };
}

for (const file of SOURCE_FILES) {
  const path = `${config.srcDir}/${file}.css`;
  if (!existsSync(path)) {
    console.error(c.red(`\n  Missing source file: ${path}\n`));
    process.exit(1);
  }
}

const start = performance.now();

if (!checkOnly) {
  mkdirSync(config.distDir, { recursive: true });
}

/** @type {BuildResult[]} */
const results = [];

const { raw, minified } = buildThemeCss();
const themePath = `${config.distDir}/theme.css`;
const themeChanged = fileChanged(themePath, minified);
if (!checkOnly) writeFileSync(themePath, minified);
results.push({
  path: themePath,
  size: minified.length,
  gzipSize: gzipSync(minified).length,
  rawSize: raw.length,
  changed: themeChanged,
});

const variablesCss = readFileSync(`${config.srcDir}/variables.css`, 'utf8');
const vars = parseRootVariables(variablesCss);
const groups = groupTokens(vars);
const { content: tokensContent, tokenCount } = generateTokensCss(groups);
const tokensPath = `${config.distDir}/tailwind-tokens.css`;
const tokensChanged = fileChanged(tokensPath, tokensContent);
if (!checkOnly) writeFileSync(tokensPath, tokensContent);
results.push({
  path: tokensPath,
  size: tokensContent.length,
  gzipSize: gzipSync(tokensContent).length,
  changed: tokensChanged,
  meta: `${tokenCount} tokens`,
});

if (results[0].size < 100) {
  console.error(c.red(`\n  ${themePath} is too small (${results[0].size}B) — check source files\n`));
  process.exit(1);
}

const elapsed = (performance.now() - start).toFixed(0);
const changedCount = results.filter((r) => r.changed).length;

console.log();
console.log(`  ${c.magenta(c.bold('NU'))} ${c.dim('❯')} ${c.bold(config.banner)}`);
console.log();
console.log(`  ${c.green('✓')} ${SOURCE_FILES.length} source files ${c.dim('→')} ${results.length} dist outputs`);
console.log(`  ${c.green('✓')} ${tokenCount} design tokens extracted`);
console.log();

const maxName = Math.max(...results.map((r) => r.path.split('/').pop().length));
const maxSize = Math.max(...results.map((r) => formatSize(r.size).length));

for (const { path, size, gzipSize, changed, meta } of results) {
  const dir = path.substring(0, path.lastIndexOf('/') + 1);
  const name = path.substring(path.lastIndexOf('/') + 1);
  const paddedName = name.padEnd(maxName);
  const paddedSize = formatSize(size).padStart(maxSize);
  const gzip = gzipSize ? `${c.dim('│')} gzip: ${formatSize(gzipSize)}` : '';
  const extra = meta ? `${c.dim('│')} ${meta}` : gzip;
  const icon = changed ? c.green('✓') : c.dim('✓');

  console.log(`  ${icon} ${c.dim(dir)}${c.cyan(paddedName)}  ${c.bold(paddedSize)}  ${extra}`);
}

console.log();
if (checkOnly && changedCount > 0) {
  console.log(`  ${c.red('✗')} ${c.red(`${changedCount} file(s) out of date`)} ${c.dim('—')} run ${c.cyan('pnpm build:css')} and commit`);
  console.log();
  process.exit(1);
} else if (changedCount > 0) {
  console.log(`  ${c.dim(`Built in ${c.bold(elapsed + 'ms')} — ${changedCount} file(s) updated`)}`);
} else {
  console.log(`  ${c.dim(`Built in ${c.bold(elapsed + 'ms')} — all files up to date`)}`);
}
console.log();
