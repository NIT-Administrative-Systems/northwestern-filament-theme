<p align="center">
    <img src="art/readme-lockup.png" alt="Northwestern Filament Theme" width="650">
</p>

<p align="center">
    <a href="https://www.php.net"><img src="https://img.shields.io/badge/PHP-8.3+-777BB4?style=flat&logo=php&logoColor=white" alt="PHP Version"></a>
    <a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-12.x%20|%2013.x-F05340?style=flat&logo=laravel&logoColor=white" alt="Laravel Version"></a>
    <a href="https://filamentphp.com"><img src="https://img.shields.io/badge/Filament-4.x%20|%205.x-FDAE4B?style=flat&logo=filament&logoColor=white" alt="Filament Version"></a>
</p>

<hr/>

<p align="center">
    A branded <a href="https://filamentphp.com">Filament</a> theme plugin for <a href="https://www.northwestern.edu">Northwestern University</a> applications. Applies the official NU color palette, typography, and institutional styling from <a href="https://common.northwestern.edu/dept/4.0/">Department Templates 4.0</a> to any Filament panel, and adds the required university footer.
</p>

<p align="center">
    <img src="art/preview.gif" alt="Before and after comparison of default Filament vs Northwestern theme" width="720">
</p>

## Installation

```bash
composer require northwestern-sysdev/northwestern-filament-theme
```

## Quick Start

Register the plugin in your Filament panel provider:

```php
use Northwestern\FilamentTheme\NorthwesternTheme;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            NorthwesternTheme::make(),
            // ... other plugins
        ])
        // ... rest of panel config
    ;
}
```

Then publish the theme assets:

```bash
php artisan filament:assets
```

> [!NOTE]
>
> If your panel has a [custom theme](https://filamentphp.com/docs/5.x/styling/overview#creating-a-custom-theme) via `->viteTheme()`, it will continue to work alongside this plugin. The Northwestern theme is an **additive CSS layer** registered separately through Filament's asset system. It does not replace your custom theme.

## Vite Theme Integration

You can also import the theme directly into your panel's Vite-compiled stylesheet instead of loading it as a separate `<link>` tag. This gives you a single compiled CSS bundle, access to Northwestern design tokens as Tailwind v4 utility classes (e.g., `bg-nu-purple-100`, `text-nu-gold`), and the ability to override specific theme styles in your own CSS.

### Setup

Run the install command:

```bash
php artisan northwestern-theme:install
```

This will:

- Create a panel theme CSS file if one doesn't exist
- Inject the Northwestern theme `@import` after the Filament base import
- Optionally inject Tailwind v4 design tokens for color utilities

Then disable automatic asset registration in your panel provider to prevent the CSS from loading twice:

```php
NorthwesternTheme::make()
    ->withoutAssetRegistration()
```

Finally, compile your assets:

```bash
npm run build
```

### Tailwind v4 Design Tokens

When you opt in to design tokens during `northwestern-theme:install`, the plugin provides Northwestern colors and fonts as Tailwind v4 utility classes:

```html
<div class="bg-nu-purple-100 text-white">Purple background</div>
<span class="text-nu-gold">Gold accent text</span>
<div class="border border-nu-black-20">Subtle border</div>
<h2 class="font-nu-display text-nu-purple-100">Serif heading</h2>
```

Available token groups:

| Group             | Examples                                                                                  |
| ----------------- | ----------------------------------------------------------------------------------------- |
| Purple scale      | `nu-purple-10` through `nu-purple-160`                                                    |
| Grays             | `nu-black-10`, `nu-black-20`, `nu-black-50`, `nu-black-80`, `nu-black-100`                |
| Brand colors      | `nu-green`, `nu-teal`, `nu-blue`, `nu-yellow`, `nu-gold`, `nu-orange`                     |
| Dark brand colors | `nu-dark-green`, `nu-dark-teal`, `nu-dark-blue`, etc.                                     |
| Semantic colors   | `nu-success`, `nu-info`, `nu-warning`, `nu-danger`                                        |
| Fonts             | `font-nu-body` (Akkurat Pro), `font-nu-heading` (Poppins), `font-nu-display` (Noto Serif) |

> [!NOTE]
>
> Design tokens require **Tailwind CSS v4+** (they use the `@theme` syntax). The core theme CSS works with any Tailwind version.

The palette, fonts and radius come from [`@nu-appdev/northwestern-tokens`](https://github.com/NIT-Administrative-Systems/northwestern-tokens), which this package bundles into `dist/` at build time. You don't install the npm package yourself.

### Tokens Without Filament

`dist/tokens.css` contains only the Northwestern `@font-face` rules and the `--nu-*` custom properties, with no Filament selectors. Use it on pages that render outside a panel, such as an error layout, together with the [footer component](#outside-a-panel). Inside a panel you don't need it: `dist/theme.css` already includes it.

## Dark Mode

The theme adapts to Filament's dark mode setting automatically. No extra configuration needed.

## Favicon & Brand Logo

The plugin automatically sets a Northwestern favicon and brand logo when the panel has not already configured them. If you call `->favicon()` or `->brandLogo()` on your panel, your values take precedence.

The brand logo is resolved in the following order:

1. Panel-level `->brandLogo()` (if set, the plugin does not override it)
2. `config('northwestern-theme.lockup')`, passed through `asset()` (from [`northwestern-sysdev/northwestern-laravel-ui`](https://github.com/NIT-Administrative-Systems/northwestern-laravel-ui), if installed)
3. The Department Templates 4.0 "Northwestern" wordmark, as inline SVG. It is Northwestern Purple on light backgrounds and white in the topbar and in dark mode.

The default favicon is `https://common.northwestern.edu/favicon.ico`, the icon dept 4.0 pages use.

If your application uses [`northwestern-sysdev/northwestern-laravel-ui`](https://github.com/NIT-Administrative-Systems/northwestern-laravel-ui), the `northwestern-theme.lockup` config value is already published for you. The Filament theme plugin reads it automatically. Its default is the `v8` wordmark URL, so set `lockup` to `null` to get the dept 4.0 wordmark.

## Environment Indicator

The theme includes a built-in environment indicator that displays a gold badge and a colored top border on non-production environments: in the topbar of full pages, and at the top of simple pages such as login. This is enabled by default.

The indicator automatically hides in production. The topbar badge also hides on small screens.

### Disabling the Indicator

```php
NorthwesternTheme::make()
    ->withoutEnvironmentIndicator()
```

### Custom Visibility

To control when the indicator appears (e.g., only for admins):

```php
NorthwesternTheme::make()
    ->environmentIndicator(
        visible: fn () => ! app()->isProduction() && auth()->user()?->hasRole('admin'),
    )
```

### Custom Label

By default, the badge reads "Environment: Local" (or whatever `APP_ENV` is set to). To customize:

```php
NorthwesternTheme::make()
    ->environmentIndicator(label: Str::upper(App::environment()))
```

> [!NOTE]
>
> If you are using [`pxlrbt/filament-environment-indicator`](https://github.com/pxlrbt/filament-environment-indicator), you can remove that package. Remove `EnvironmentIndicatorPlugin::make()` from your panel provider and the theme handles the rest.

## Impersonation Banner

The theme includes a built-in impersonation banner that renders a red bar above the topbar when a user is being impersonated. It is registered by default and shows automatically when [`lab404/laravel-impersonate`](https://github.com/404labfr/laravel-impersonate) is installed and an impersonation session is active. Without lab404, the banner will not appear unless you provide a custom `visible` closure.

### Custom Visibility

If you are not using [`lab404/laravel-impersonate`](https://github.com/404labfr/laravel-impersonate), or want to override the default detection, provide your own visibility logic:

```php
NorthwesternTheme::make()
    ->impersonationBanner(
        visible: fn () => session()->has('impersonating'),
    )
```

### Custom Label

```php
NorthwesternTheme::make()
    ->impersonationBanner(
        label: fn () => 'Acting as ' . auth()->user()->name,
    )
```

### Custom Leave URL

```php
NorthwesternTheme::make()
    ->impersonationBanner(
        leaveUrl: '/my-app/stop-impersonating',
    )
```

### Custom Leave Label

```php
NorthwesternTheme::make()
    ->impersonationBanner(
        leaveLabel: 'Return to Admin',
    )
```

### Custom Leave Method

The leave form defaults to `POST`. If your leave endpoint expects a different HTTP method:

```php
NorthwesternTheme::make()
    ->impersonationBanner(
        leaveUrl: '/my-app/stop-impersonating',
        leaveMethod: 'DELETE',
    )
```

### Disabling the Banner

```php
NorthwesternTheme::make()
    ->withoutImpersonationBanner()
```

> [!NOTE]
>
> If your application has a custom impersonation banner registered, remove it when enabling the built-in banner to avoid duplicates. A warning is logged in local environments when both are detected.

## Footer

Every panel gets the Northwestern footer, built to the Department Templates 4.0 structure:

- the "Northwestern University" wordmark, linked to northwestern.edu, and your office name
- your office's address, phone, fax and email
- Northwestern's social accounts under "Connect"
- your own links under "Quick Links", if you add any
- the links the university's Web Style Guide requires, which can't be removed: Building Access, Campus Emergency Information, Careers, Contact Northwestern University, University Policies, Accessibility, Disclaimer, Privacy Statement and Report a Concern

The footer renders through Filament's `FOOTER` render hook, on full pages and on simple pages such as login.

### Office Information

Office contact details displayed in the footer are resolved in the following order:

1. Values passed directly to `->footer()` (see below)
2. `config('northwestern-theme.office.*')` values (from `northwestern-sysdev/northwestern-laravel-ui`, if installed)
3. Hardcoded IT defaults (Information Technology, 1800 Sherman Ave, etc.)

If your application already has [`northwestern-sysdev/northwestern-laravel-ui`](https://github.com/NIT-Administrative-Systems/northwestern-laravel-ui) installed, the footer will pick up `office.name`, `office.addr`, `office.city`, `office.phone`, `office.fax`, and `office.email` automatically. A field that resolves to an empty string is hidden. The fax is optional and has no default.

To override specific fields:

```php
NorthwesternTheme::make()
    ->footer(
        officeName: 'My Office',
        officeAddr: '633 Clark St',
        officeCity: 'Evanston, IL 60208',
        officePhone: '847-555-1234',
        officeEmail: 'my-office@northwestern.edu',
        officeFax: '847-555-1235',
    )
```

### Adding Links

Pass `links` as label => URL pairs. They render under "Quick Links", next to the required links:

```php
NorthwesternTheme::make()
    ->footer(links: [
        'Help' => 'https://example.northwestern.edu/help',
        'Service Status' => 'https://status.example.northwestern.edu',
    ])
```

### Required Links

The required links live in the `Northwestern\FilamentTheme\Footer\RequiredLink` enum, so anything else that needs them, such as a text-only mail footer, reads the same list:

```php
use Northwestern\FilamentTheme\Footer\RequiredLink;

RequiredLink::Accessibility->url();   // https://www.northwestern.edu/accessibility/report/
RequiredLink::PrivacyStatement->label(); // Privacy Statement

RequiredLink::RESOURCES; // the five links under "Northwestern Resources"
RequiredLink::LEGAL;     // the four links in the bottom bar
```

### Outside a Panel

The `<x-northwestern-filament-theme::footer />` component renders the same footer without Filament, auth or the database, so it works on public pages and on error pages that render while the database is down. It takes the same options as `->footer()`:

```blade
<x-northwestern-filament-theme::footer office-name="My Office" :links="["Help"=> route("help")]" />
```

The footer inlines its own styles, which use the `--nu-*` custom properties and fonts. Inside a panel those come from the theme CSS; on a page without it, load `dist/tokens.css` as well (see [Tokens Without Filament](#tokens-without-filament)). The standalone footer is light only.

### Disabling the Footer

Pass `enabled: false` or a closure that returns a boolean:

```php
NorthwesternTheme::make()
    ->footer(enabled: false)

NorthwesternTheme::make()
    ->footer(enabled: fn () => auth()->user()?->isStudent())
```

## Customizing Views

To modify the environment indicator, impersonation banner, or footer markup, publish the views:

```bash
php artisan vendor:publish --tag=northwestern-filament-theme-views
```

This publishes the following templates to `resources/views/vendor/northwestern-filament-theme/`:

- `environment-indicator.blade.php` — the environment badge and border
- `impersonation-banner.blade.php` — the impersonation session banner
- `footer.blade.php` — the institutional footer
- `components/footer.blade.php` — the `<x-northwestern-filament-theme::footer />` component
- `wordmark.blade.php` — the inline dept 4.0 wordmarks

The required links are not in the published view's control: they come from the `RequiredLink` enum.

## External CDN Dependency

This theme loads fonts and the favicon from Northwestern's CDN (`common.northwestern.edu/dept/4.0/`). The wordmark and footer icons are inline SVG. Your application needs network access to this CDN at runtime. If your environment restricts outbound requests or enforces a strict CSP, allowlist `https://common.northwestern.edu`.

Akkurat Pro is licensed for central hosting by the university only, so the fonts always load from the CDN and are never bundled into this package.

## Upgrading

See [UPGRADING.md](UPGRADING.md) for migration guides between major versions.

## License

The MIT License (MIT). Please see [LICENSE](LICENSE) for more information.
