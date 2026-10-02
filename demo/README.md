# Demo App

Minimal Laravel + Filament app for visual regression testing of the Northwestern Filament Theme with [Percy](https://percy.io).

## Setup

```bash
cd demo
composer install
pnpm install
npx playwright install chromium
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan filament:assets
pnpm build
```

## Running locally

```bash
# Browse the showcase page
php artisan serve
# → http://127.0.0.1:8000/theme-showcase

# Run Playwright tests (no Percy — just validates pages render)
npx playwright test

# Run with Percy snapshots
PERCY_TOKEN=<your-token> pnpm exec percy exec --allowed-hostname common.northwestern.edu -- pnpm exec playwright test
```

## Snapshots

Each run captures these snapshots in Chrome and Firefox at 1440px:

| Snapshot               | Page                         |
| ---------------------- | ---------------------------- |
| Theme Showcase [light] | Full page                    |
| Theme Showcase [dark]  | Full page                    |
| Footer [light]         | Footer only                  |
| Footer [dark]          | Footer only                  |
| Login [light]          | Simple page, with the footer |
| Login [dark]           | Simple page, with the footer |
