# Upgrading

## v3.x to v4.0

v4.0 moves the theme from Northwestern's `v8` global template to **Department Templates 4.0**. The footer is on by default, and the fonts, the default wordmark and the footer markup all change. Panel interiors look the same apart from the details below.

1. **Update the package:**

    ```bash
    composer require northwestern-sysdev/northwestern-filament-theme:^4.0
    ```

2. **Re-publish assets** if you use automatic asset registration:

    ```bash
    php artisan filament:assets
    ```

    If you import the theme in a Vite panel theme, rebuild instead (`npm run build`).

### The footer is on by default

Every panel that registers the plugin now renders the Northwestern footer. It used to be opt-in through `->footer()`.

- To keep a panel without a footer, opt out:

    ```php
    NorthwesternTheme::make()
        ->footer(enabled: false)
    ```

- `->footer()` with no arguments is now the default, so you can delete that call. Calls with office arguments keep working.
- The footer renders through `PanelsRenderHook::FOOTER` instead of `BODY_END`. On full pages it sits at the bottom of the content column, beside the sidebar, instead of spanning the window. On simple pages such as login it spans the page below the card.
- If you rendered the footer yourself, for example through your own `BODY_END` render hook, remove that hook to avoid two footers.

### The footer has new markup

The footer follows the dept 4.0 structure and adds the three required links the `v8` footer was missing: Building Access, Privacy Statement and Report a Concern. Its class names changed, for example from `.nu-footer-grid` and `.nu-pin-*` to `.nu-footer-columns` and `.nu-footer-icon`. The outer `.nu-footer` element is unchanged.

- **Published views:** if you published `footer.blade.php`, delete your copy, or re-publish and re-apply your edits:

    ```bash
    php artisan vendor:publish --tag=northwestern-filament-theme-views --force
    ```

- **Custom CSS:** rules that targeted the old footer classes no longer match.
- **Required links can't be removed.** To add your own, pass `links`. They render under "Quick Links":

    ```php
    NorthwesternTheme::make()
        ->footer(links: [
            'Help' => 'https://example.northwestern.edu/help',
        ])
    ```

- **Outside a panel:** use `<x-northwestern-filament-theme::footer />`. It renders without Filament, auth or the database. On a page without the theme CSS, load `dist/tokens.css` too (see below).

### Office configuration

The office fields still resolve from `->footer()`, then `config('northwestern-theme.office.*')`, then the Information Technology defaults. There is one addition:

```php
// config/northwestern-theme.php
'office' => [
    // ...
    'fax' => env('NU_THEME_OFFICE_FAX'), // optional; hidden when empty
],
```

Or pass `officeFax:` to `->footer()`. A field that resolves to an empty string is now hidden instead of rendering an empty row.

### Fonts and wordmark

- **Fonts** load as `.woff2` from `https://common.northwestern.edu/dept/4.0/css/fonts/` instead of `v8`. The CDN host is the same, so CSP allowlists don't change. Poppins 400 now uses the regular face instead of light, so text at that weight renders slightly heavier. Noto Serif is new and is available as `--nu-font-display`.
- **Default brand logo.** When `config('northwestern-theme.lockup')` is not set, the logo is the dept 4.0 "Northwestern" wordmark as inline SVG instead of `v8/css/images/northwestern.svg`, so `$panel->getBrandLogo()` returns an `Htmlable`. It is Northwestern Purple on light backgrounds and white in the topbar and in dark mode.
- **Configured lockups win.** If your `config/northwestern-theme.php` sets `lockup` to the `v8` URL (the `northwestern-laravel-ui` default), you keep the `v8` image. Set it to `null` to use the dept 4.0 wordmark.
- **Favicon.** The default is `https://common.northwestern.edu/favicon.ico` instead of `v8/icons/favicon-32.png`. The artwork is the same.

### New `dist/` files

- **`dist/tokens.css`** is new. It contains the dept 4.0 `@font-face` rules and the `--nu-*` custom properties, with no Filament selectors, for pages that render outside Filament such as an error layout. `dist/theme.css` already includes it, so panels don't need it.
- **`dist/tailwind-tokens.css`** keeps its name and its `--color-nu-*` utilities, and adds `font-nu-body`, `font-nu-heading`, `font-nu-display` and the square-corner radius scale. It still needs the custom properties from `theme.css` or `tokens.css`.

The palette now comes from [`@nu-appdev/northwestern-tokens`](https://github.com/NIT-Administrative-Systems/northwestern-tokens), bundled at build time. You don't need to install it: everything ships in `dist/`. The `--nu-*` names and values are unchanged.

### Environment indicator on simple pages

The environment indicator now also renders at the top of simple pages, such as login, through `PanelsRenderHook::SIMPLE_LAYOUT_START`. It follows the same `->environmentIndicator()` settings. `->withoutEnvironmentIndicator()` removes it from both places.

---

## v2.0 to v2.1

v2.1 adds a built-in environment indicator and impersonation banner. If you were using third-party packages or custom views for these features, you can remove them.

### Environment Indicator

If you are using [`pxlrbt/filament-environment-indicator`](https://github.com/pxlrbt/filament-environment-indicator):

1. **Remove the package:**

    ```bash
    composer remove pxlrbt/filament-environment-indicator
    ```

2. **Remove the plugin registration** from your panel provider:

    ```diff
    - use Pxlrbt\FilamentEnvironmentIndicator\EnvironmentIndicatorPlugin;

      return $panel
          ->plugins([
              NorthwesternTheme::make(),
    -         EnvironmentIndicatorPlugin::make(),
          ]);
    ```

The theme's indicator is enabled by default and hides in production. To customize or disable it, see the [Environment Indicator](README.md#environment-indicator) section.

### Impersonation Banner

If you have a custom impersonation banner (e.g. a Blade view registered via `FilamentView::registerRenderHook`):

1. **Remove your custom banner view** and its render hook registration.

2. The built-in banner auto-detects [`lab404/laravel-impersonate`](https://github.com/404labfr/laravel-impersonate) sessions. If you are using lab404, no configuration is needed. Just remove your custom implementation.

3. If you have custom visibility, label, or leave URL logic, migrate it to the plugin API:

    ```php
    NorthwesternTheme::make()
        ->impersonationBanner(
            visible: fn () => session()->has('impersonating'),
            label: fn () => 'Acting as ' . auth()->user()->name,
            leaveUrl: '/stop-impersonating',
        )
    ```

To disable the banner entirely, call `->withoutImpersonationBanner()`.

### Footer

Footer CSS is now inlined in the Blade view. If you previously needed to run `php artisan filament:assets` specifically for footer styles, that step is no longer necessary (though you should still run it for the main theme CSS unless you use Vite integration).

---

## v1.x to v2.0

v2.0 consolidates the theme's CSS into a single barrel file (`dist/theme.css`) instead of shipping individual module files.

### Required Steps

1. **Update the package:**

    ```bash
    composer require northwestern-sysdev/northwestern-filament-theme:^2.0
    ```

2. **Clear previously published CSS assets:**

    ```bash
    rm -rf public/css/northwestern-sysdev
    ```

3. **Re-publish assets:**

    ```bash
    php artisan filament:assets
    ```

That's it for the default integration path. The plugin will register the single `theme.css` file automatically through `@filamentStyles`.

### Optional: Vite Theme Integration

If you already have a custom Filament panel theme (created via `php artisan make:filament-theme`) and want Northwestern color tokens as Tailwind utilities, a single compiled CSS bundle, or the ability to override theme styles in your own CSS, you can switch to Vite integration.

**Steps:**

1. **Run the install command:**

    ```bash
    php artisan northwestern-theme:install
    ```

    This will:
    - Create a panel theme CSS file if one doesn't exist
    - Inject the Northwestern theme `@import` after the Filament base import
    - Optionally inject Tailwind v4 design tokens for color utilities

2. **Disable automatic asset registration** in your panel provider:

    ```php
    use Northwestern\FilamentTheme\NorthwesternTheme;

    NorthwesternTheme::make()
        ->withoutAssetRegistration()
    ```

    This prevents the theme CSS from loading twice. Once through your Vite bundle and once through `@filamentStyles`.

3. **Compile your assets:**

    ```bash
    npm run build
    ```

### Breaking Changes

- Individual CSS module files (`variables.css`, `typography.css`, etc.) are no longer registered separately. They're concatenated into `dist/theme.css`.
- The `public/css/northwestern-sysdev/` directory from v1 should be removed to avoid stale files.
