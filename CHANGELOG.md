# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [4.1.2] - 2026-10-06

### Fixed

- Keyboard focus on the top bar's buttons, such as the notifications bell and the sidebar toggles, is now visible: the focus outline uses the top bar's text color instead of purple on the purple bar

## [4.1.1] - 2026-10-06

### Fixed

- The unread count on the top bar's notifications bell no longer touches the user menu: the bell gets end margin while it shows a badge
- Table placeholder text uses the muted text color, as infolist placeholders do. Filament's gray-400 fails color contrast on white
- The top bar's Northwestern wordmark lines up with the application name instead of sitting low beside it

## [4.1.0] - 2026-10-02

The theme now owns its configuration. Nothing changes for an app that changes nothing: `config/northwestern-theme.php` from `northwestern-laravel-ui` is still read, and existing `->footer()` arguments and component props still win. See [UPGRADING.md](UPGRADING.md#v40-to-v41).

### Added

- `config/northwestern-filament-theme.php`, published with the `northwestern-filament-theme-config` tag: `lockup`, `unit.*` (name, address, city, phone, fax, email), `footer.links` and `footer.social`, with the `NU_LOCKUP` and `NU_UNIT_*` env vars
- Configurable "Connect" accounts through `footer.social` (network => URL), with the Department Templates 4.0 icons for Bluesky, Facebook, Flickr, Instagram, LinkedIn, Pinterest, RSS, Spotify, Threads, TikTok, Tumblr, Vimeo, WordPress, X and YouTube. It defaults to Northwestern's Facebook, Instagram and YouTube accounts; `[]` hides the section
- `Footer\SocialNetwork` enum and the publishable `social-icon.blade.php` view
- `FooterConfig::quickLinks()` and `FooterConfig::social()`, which the footer view uses to read the resolved links and accounts

### Changed

- The lockup and each footer value resolve from a `->footer()` argument or component prop, then the new config, then the legacy `northwestern-theme.*` key, then the built-in default. The legacy file is still supported in 4.x
- `<x-northwestern-filament-theme::footer />` with no props renders from the new config
- The composer `suggest` entry describes `northwestern-laravel-ui` as an optional, still-supported legacy source of configuration
- pnpm settings (`minimumReleaseAge`, its `@nu-appdev/*` exclusion and the demo's overrides) moved to `pnpm-workspace.yaml`, because pnpm 11 ignores them in `.npmrc` and `package.json`

### Deprecated

- The `officeName`, `officeAddr`, `officeCity`, `officePhone`, `officeEmail`, `officeFax` and `links` arguments of `->footer()`, and the matching footer component props. Use the `unit.*` and `footer.links` config keys. They will be removed in a future major release. `->footer(enabled:)` is not deprecated

## [4.0.0] - 2026-10-02

This is a breaking release that moves the theme from Northwestern's `v8` global template to **Department Templates 4.0**. The footer is now on by default, and the fonts, wordmark and footer markup all change. See [UPGRADING.md](UPGRADING.md#v3x-to-v40). Breaking changes are marked **Breaking**.

### Added

- `Footer\RequiredLink` enum with the nine links the Web Style Guide requires in every footer, with their current URLs. `RequiredLink::RESOURCES` and `RequiredLink::LEGAL` give the two groups, and each case has `label()` and `url()`, so other surfaces such as a mail footer can read the same list
- Building Access, Privacy Statement and Report a Concern links in the footer
- `<x-northwestern-filament-theme::footer />` Blade component that renders the footer without Filament, auth or the database, for public and error pages
- `links` parameter on `footer()` and the component for app links (label => URL), shown under "Quick Links" alongside the required links
- Optional `officeFax` parameter on `footer()` and `office.fax` config key
- `dist/tokens.css`: the dept 4.0 `@font-face` rules and `--nu-*` custom properties on their own, for pages that render without Filament
- `font-nu-body`, `font-nu-heading` and `font-nu-display` utilities and the radius scale in `dist/tailwind-tokens.css`
- Noto Serif (`--nu-font-display`) and Poppins 500 faces
- Environment indicator on simple pages such as login, through `PanelsRenderHook::SIMPLE_LAYOUT_START`
- `NorthwesternTheme::FAVICON_URL` constant

### Changed

- **Breaking:** the footer is on by default for every panel. Opt out with `->footer(enabled: false)`
- On simple pages the footer renders inside the simple layout through `PanelsRenderHook::SIMPLE_LAYOUT_END` instead of after it at `BODY_END`, so it sits below the card. Full pages still render it at `BODY_END`, spanning the window below the sidebar and the content
- **Breaking:** the footer is rebuilt to the dept 4.0 structure with new markup and class names: wordmark and unit name, then contact, Connect, Quick Links and Northwestern Resources columns on Purple 120, and a Purple 100 bottom bar with the copyright, Accessibility, Disclaimer, Privacy Statement and Report a Concern. Published copies of `footer.blade.php` no longer match
- **Breaking:** fonts load from `common.northwestern.edu/dept/4.0/` as `.woff2` instead of `v8` `.woff`. Poppins 400 now uses the regular face instead of light, so headings set at that weight render slightly heavier
- **Breaking:** when no `northwestern-theme.lockup` is configured, the default brand logo is the dept 4.0 "Northwestern" wordmark as inline SVG instead of the `v8` SVG URL, so `getBrandLogo()` returns an `Htmlable`. It is purple on light backgrounds and white in the topbar and in dark mode
- **Breaking:** the default favicon is `common.northwestern.edu/favicon.ico` instead of `v8/icons/favicon-32.png` (same artwork)
- The footer's Accessibility link is labeled "Accessibility" and points at `/accessibility/report/`. Careers and Campus Emergency Information point at their current URLs instead of redirects
- Footer office fields that resolve to an empty string are hidden instead of rendering an empty row
- Footer icons and the wordmark are inline SVG instead of the `v8` PNG sprite and remote images. Footer styles use the tokens package's custom properties
- The brand palette, semantic colors, font stacks and `--nu-border-radius` come from [`@nu-appdev/northwestern-tokens`](https://github.com/NIT-Administrative-Systems/northwestern-tokens) 1.0, bundled at build time. Values are unchanged
- `dist/tailwind-tokens.css` is the tokens package's `tailwind.css`. Existing `--color-nu-*` names are unchanged
- Tailwind's `--radius-*` scale follows `--nu-border-radius`
- Development dependencies upgraded, clearing the open Dependabot security alerts: Pest `^4.0||^5.0` (Pest 5 on PHP 8.4+), current Rector, Larastan and Pint, the latest npm tooling (Vite 8.3, TypeScript 7, Prettier 3.9, Stylelint 17.16), and the demo app on Vite 8 and laravel-vite-plugin 3. Runtime requirements are unchanged
- GitHub Actions bumped to their latest releases (checkout v7, cache v6, setup-node v7, checkstyle v4) and pinned to commit SHAs

### Fixed

- The login card border and shadow targeted the full-width `.fi-simple-main-ctn` wrapper instead of the `.fi-simple-main` card

## [3.0.2] - 2026-06-18

### Changed

- Removed Tailwind text-size token overrides and restored Northwestern typography tokens to direct compact values, closely matching the pre-3.0.0 scale

## [3.0.1] - 2026-06-18

### Changed

- Restored compact typography token values while keeping Tailwind text-size variables as the source for overlapping `--nu-text-*` tokens

## [3.0.0] - 2026-06-09

This is a breaking release. The theme now raises the default typography floor to 16px by overriding Tailwind text-size tokens used by Filament and by this package's own CSS.

### Changed

- `--text-xs`, `--text-sm`, and `--text-base` now resolve to `1rem`
- `--nu-text-*` variables now delegate to Tailwind's `--text-*` variables instead of maintaining a separate parallel scale

### Fixed

- Table loading spinner barely visible in dark mode on column headers and search input

## [2.5.0] - 2026-04-09

### Added

- 3px left border accent on active sidebar items
- Status-tinted backgrounds on notifications (success, danger, warning, info)
- Color-aware borders on badges that reflect each badge's color
- Heading color hierarchy: page and section headings in purple, descriptions in neutral black
- Purple surface background on table group header row cells

### Changed

- Notifications use a 4px left accent border per status instead of the default ring
- Callouts use a 3px left accent border with tinted background instead of an all-around border
- Callout headings use small uppercase text with tight letter-spacing
- Badges use `font-weight: 600` and `letter-spacing: 0.02em`
- Modal header/footer borders hidden when the modal has no body content (e.g. confirmation dialogs)
- `--nu-purple-surface` shifted from `#f3f0f7` to `#f9f6ff`

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

[Unreleased]: https://github.com/NIT-Administrative-Systems/northwestern-filament-theme/compare/v4.1.2...HEAD
[4.1.2]: https://github.com/NIT-Administrative-Systems/northwestern-filament-theme/compare/v4.1.1...v4.1.2
[4.1.1]: https://github.com/NIT-Administrative-Systems/northwestern-filament-theme/compare/v4.1.0...v4.1.1
[4.1.0]: https://github.com/NIT-Administrative-Systems/northwestern-filament-theme/compare/v4.0.0...v4.1.0
[4.0.0]: https://github.com/NIT-Administrative-Systems/northwestern-filament-theme/compare/v3.0.2...v4.0.0
[3.0.2]: https://github.com/NIT-Administrative-Systems/northwestern-filament-theme/compare/v3.0.1...v3.0.2
[3.0.1]: https://github.com/NIT-Administrative-Systems/northwestern-filament-theme/compare/v3.0.0...v3.0.1
[3.0.0]: https://github.com/NIT-Administrative-Systems/northwestern-filament-theme/compare/v2.5.0...v3.0.0
[2.5.0]: https://github.com/NIT-Administrative-Systems/northwestern-filament-theme/compare/v2.4.0...v2.5.0
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
