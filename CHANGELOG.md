# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [2.4.0] - 2026-03-23

### Added

- Percy visual regression testing with a minimal demo Laravel app and Playwright (30 snapshots across light/dark mode)
- CSS watch mode via `pnpm build:css:watch` for live-reloading during development
- `rel="noopener"` on external footer links and `aria-label` on social icon links

### Changed

- CSS architecture modularized into focused files (`badges`, `dropdowns`, `modals`, `notifications`, `pagination`, `sections`, `widgets`) with shared `variables.css` design tokens
- `NorthwesternTheme` internals extracted into `HasFooter`, `HasEnvironmentIndicator`, and `HasImpersonationBanner` traits
- Double-load detection warning now includes an actionable code example

## [2.3.0] - 2026-03-19

### Added

- Prettier with Blade, XML, and package.json plugins for consistent formatting across all file types
- Stylelint property ordering via `stylelint-config-recess-order`
- CSS minification in dist build using LightningCSS
- `composer check` (read-only) and `composer fix` (auto-fix) commands covering PHP and frontend tooling
- Prettier check step in CI workflow

### Changed

- CI workflows now use `pnpm/action-setup@v4` with built-in store caching instead of corepack
- Filament compatibility workflow uses single `composer require` instead of `--no-update` + `update`

## [2.2.1] - 2026-03-19

### Fixed

- Default avatar now has a `nu-purple-10` background for better contrast against the topbar
- Redundant placeholder icon hidden in user menu dropdown header
- Table record collapse button aligned to top of row instead of center

## [2.2.0] - 2026-03-17

### Added

- Laravel 13 support

### Changed

- Minimum PHP version raised from 8.2 to 8.3
- Dropped Laravel 11 support

## [2.1.1] - 2026-03-17

### Fixed

- Install command now detects custom `viteTheme()` paths instead of only looking in `resources/css/filament/{panel}/theme.css`
- Table header selection cell (bulk-select checkbox) now receives the purple header background and border
- Filter badge no longer overlaps the table container border on tables without search enabled

## [2.1.0] - 2026-03-16

### Added

- Built-in environment indicator with gold badge and top-border (replaces [`pxlrbt/filament-environment-indicator`](https://github.com/pxlrbt/filament-environment-indicator))
- `environmentIndicator()` and `withoutEnvironmentIndicator()` fluent methods with optional custom label and visibility
- Built-in impersonation banner with auto-detection of [`lab404/laravel-impersonate`](https://github.com/404labfr/laravel-impersonate)
- `impersonationBanner()` and `withoutImpersonationBanner()` fluent methods with optional custom visibility, label, and leave URL
- Deprecation warning when a legacy custom impersonation banner view is detected alongside the built-in banner

### Fixed

- Improved color contrast for danger and gray color scales to meet WCAG accessibility standards
- Purple 400 shade adjusted to increase contrast in UI elements
- Warning button text in dark mode now uses a darker shade for better readability

## [2.0.0] - 2026-03-16

v2.0 adds optional Vite theme integration as an alternative to the default asset registration approach. Theme CSS is now bundled into a single `dist/theme.css` file, and a new `northwestern-theme:install` command handles setup. Tailwind v4 design tokens are available for projects that want Northwestern brand utilities in their own styles.

This is a breaking release. See the [Upgrading Guide](UPGRADING.md) for migration steps.

### Added

- Vite theme integration via `northwestern-theme:install` artisan command
- `withoutAssetRegistration()` to prevent double-loading when using Vite integration
- Tailwind v4 design tokens (`bg-nu-purple-100`, `text-nu-gold`, etc.)

### Changed

- Theme CSS is now compiled into `dist/theme.css` instead of registered as individual files

## [1.0.2] - 2026-03-16

### Changed

- Avatars are now square with a border outline
- Notification titles use body font (Akkurat Pro) instead of heading font, with medium weight
- Dark mode table headers use a subtle purple tint instead of near-invisible surface color

### Fixed

- Global search placeholder text invisible in light mode
- Global search input focus ring not visible on purple topbar in light mode
- Global search input background too purple in dark mode
- Avatar border not visible in dark mode
- Badge border too faint in light mode

## [1.0.1] - 2026-03-16

### Fixed

- Footer render hook now scoped to the registering panel, preventing it from rendering on unrelated panels in multi-panel applications

## [1.0.0] - 2026-03-16

- Initial public release
- Northwestern brand colors, typography (Akkurat Pro & Poppins), and layout overrides for Filament panels
- Optional footer with configurable office contact information
- Default favicon and brand logo with automatic fallback

[Unreleased]: https://github.com/NIT-Administrative-Systems/northwestern-filament-theme/compare/v2.4.0...HEAD
[2.4.0]: https://github.com/NIT-Administrative-Systems/northwestern-filament-theme/compare/v2.3.0...v2.4.0
[2.3.0]: https://github.com/NIT-Administrative-Systems/northwestern-filament-theme/compare/v2.2.1...v2.3.0
[2.2.1]: https://github.com/NIT-Administrative-Systems/northwestern-filament-theme/compare/v2.2.0...v2.2.1
[2.2.0]: https://github.com/NIT-Administrative-Systems/northwestern-filament-theme/compare/v2.1.1...v2.2.0
[2.1.1]: https://github.com/NIT-Administrative-Systems/northwestern-filament-theme/compare/v2.1.0...v2.1.1
[2.1.0]: https://github.com/NIT-Administrative-Systems/northwestern-filament-theme/compare/v2.0.0...v2.1.0
[2.0.0]: https://github.com/NIT-Administrative-Systems/northwestern-filament-theme/compare/v1.0.2...v2.0.0
[1.0.2]: https://github.com/NIT-Administrative-Systems/northwestern-filament-theme/compare/v1.0.1...v1.0.2
[1.0.1]: https://github.com/NIT-Administrative-Systems/northwestern-filament-theme/compare/v1.0.0...v1.0.1
[1.0.0]: https://github.com/NIT-Administrative-Systems/northwestern-filament-theme/releases/tag/v1.0.0
