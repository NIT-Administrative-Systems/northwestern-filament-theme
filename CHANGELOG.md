# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

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

[Unreleased]: https://github.com/NIT-Administrative-Systems/northwestern-filament-theme/compare/v1.0.2...HEAD
[1.0.2]: https://github.com/NIT-Administrative-Systems/northwestern-filament-theme/compare/v1.0.1...v1.0.2
[1.0.1]: https://github.com/NIT-Administrative-Systems/northwestern-filament-theme/compare/v1.0.0...v1.0.1
[1.0.0]: https://github.com/NIT-Administrative-Systems/northwestern-filament-theme/releases/tag/v1.0.0
