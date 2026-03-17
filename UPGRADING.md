# Upgrading

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
