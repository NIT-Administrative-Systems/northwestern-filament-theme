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
php artisan migrate --seed
php artisan filament:assets
pnpm build
```

## Running locally

```bash
# Browse the showcase page. The sign-in page fills in the seeded
# demo account (demo@northwestern.edu, password "password").
php artisan serve
# → http://127.0.0.1:8000/theme-showcase

# Run Playwright tests (no Percy — just validates pages render)
npx playwright test

# Run with Percy snapshots
PERCY_TOKEN=<your-token> pnpm exec percy exec --allowed-hostname common.northwestern.edu -- pnpm exec playwright test
```

## Snapshots

Each run captures these snapshots in Chrome and Firefox, at 1440px unless the table says otherwise:

| Snapshot               | Page                                                                 | Widths                    |
| ---------------------- | -------------------------------------------------------------------- | ------------------------- |
| Theme Showcase [light] | Full page                                                            | 1440                      |
| Theme Showcase [dark]  | Full page                                                            | 1440                      |
| Footer [light]         | Footer only                                                          | 1440                      |
| Footer [dark]          | Footer only                                                          | 1440                      |
| Top Bar [light]        | Top bar with a long application name, search, Help and the user menu | 320, 384, 768, 1024, 1440 |
| Top Bar [dark]         | Top bar with a long application name, search, Help and the user menu | 320, 384, 768, 1024, 1440 |
| People [light]         | Table with a long role badge in a wrapped column                     | 375, 1440                 |
| People [dark]          | Table with a long role badge in a wrapped column                     | 375, 1440                 |
| Person [light]         | Infolist with a long role badge in a wrapped entry                   | 375, 1440                 |
| Person [dark]          | Infolist with a long role badge in a wrapped entry                   | 375, 1440                 |
| Login [light]          | Simple page, with the footer                                         | 375, 1440                 |
| Login [dark]           | Simple page, with the footer                                         | 375, 1440                 |

The demo runs as `APP_ENV=staging`, so the top bar shows the environment badge, "Environment: Staging" from 1280px and "Staging" below.

Playwright also checks, without Percy, that the top bar fits at each of the top bar's widths: nothing past the right edge, and no control over the wordmark or its neighbor.
