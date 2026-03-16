# Upgrading

## v1.x to v2.0

v2.0 consolidates the theme's CSS into a single barrel file (`dist/theme.css`) instead of shipping individual module files. This reduces HTTP requests and simplifies asset management.

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

If you want tighter control over CSS loading — for example, to use Tailwind v4 utility classes with Northwestern design tokens, or to eliminate the separate `<link>` tag in favor of a single Vite-compiled stylesheet — you can switch to Vite integration.

**When to consider:**

- You have a custom Filament panel theme CSS file (created via `php artisan make:filament-theme`)
- You want to use Northwestern color tokens as Tailwind utilities (e.g., `bg-nu-purple-100`, `text-nu-gold`)
- You want a single compiled CSS bundle instead of a separate `<link>` tag for the theme
- You need to override specific theme styles in your own CSS

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

- Individual CSS module files (`variables.css`, `typography.css`, etc.) are no longer registered as separate assets. They are concatenated into `dist/theme.css`.
- The `public/css/northwestern-sysdev/` directory from v1 should be removed to avoid stale files.
