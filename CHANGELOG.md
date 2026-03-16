# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

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

[Unreleased]: https://github.com/NIT-Administrative-Systems/northwestern-filament-theme/compare/v2.0.0...HEAD
[2.0.0]: https://github.com/NIT-Administrative-Systems/northwestern-filament-theme/compare/v1.0.2...v2.0.0
[1.0.2]: https://github.com/NIT-Administrative-Systems/northwestern-filament-theme/compare/v1.0.1...v1.0.2
[1.0.1]: https://github.com/NIT-Administrative-Systems/northwestern-filament-theme/compare/v1.0.0...v1.0.1
[1.0.0]: https://github.com/NIT-Administrative-Systems/northwestern-filament-theme/releases/tag/v1.0.0
