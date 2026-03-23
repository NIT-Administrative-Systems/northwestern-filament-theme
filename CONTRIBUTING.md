# Contributing

Thanks for your interest in improving the Northwestern Filament Theme. This guide covers everything you need to get a development environment running, make changes, and submit a pull request.

## Prerequisites

| Tool     | Version |
| -------- | ------- |
| PHP      | 8.3+    |
| Node.js  | 24+     |
| pnpm     | 10+     |
| Composer | 2.x     |

## Setup

Clone the repo and install both PHP and Node dependencies:

```bash
git clone git@github.com:NIT-Administrative-Systems/northwestern-filament-theme.git
cd northwestern-filament-theme
composer install
pnpm install
```

## Project structure

```
src/                     # PHP source (plugin, service provider, config classes)
resources/css/           # Modular CSS source files
resources/views/         # Blade templates (footer, indicator, banner)
dist/                    # Built artifacts (theme.css, tailwind-tokens.css)
scripts/build-css.mjs    # CSS build script
tests/Feature/           # Pest test suite
```

CSS lives in individual files organized by concern (`buttons.css`, `forms.css`, `modals.css`, `tables.css`, etc.). The build script concatenates and minifies these into `dist/theme.css` using [LightningCSS](https://lightningcss.dev/). See `SOURCE_FILES` in `scripts/build-css.mjs` for the full list and concatenation order.

## Making changes

### PHP

Write your code, then run `composer fix` to auto-format and modernize it. This runs Rector, Pint, Prettier, Stylelint, and rebuilds the CSS dist in one shot:

```bash
composer fix
```

To check without modifying files (the same thing CI runs):

```bash
composer check
```

You can run individual tools when you want faster feedback:

```bash
composer format          # Pint (PHP formatting)
composer rector          # Rector (code modernization)
composer analyse         # PHPStan level 10
composer test            # Pest
```

### CSS

Edit files in `resources/css/`, then rebuild:

```bash
pnpm build:css
```

The build concatenates all source files, minifies with LightningCSS, and writes `dist/theme.css`. You must commit the updated dist file with your changes.

> [!IMPORTANT]
> CI checks that `dist/theme.css` matches the source files. If you edit CSS and forget to rebuild, your PR will fail the `build:css:check` step.

Lint and format CSS separately if needed:

```bash
pnpm format              # Prettier
pnpm lint:css:fix        # Stylelint with auto-fix
```

### Blade templates

The three views (`footer.blade.php`, `environment-indicator.blade.php`, `impersonation-banner.blade.php`) use scoped inline `<style>` blocks. Prettier formats these with the Blade plugin. Run `pnpm format` after editing.

## Testing

All tests use [Pest](https://pestphp.com/) with [Orchestra Testbench](https://github.com/orchestral/testbench):

```bash
composer test
```

Run a single test file or filter:

```bash
./vendor/bin/pest --filter=FooterTest
./vendor/bin/pest --filter="it sets default favicon"
```

### Test expectations

- Add or update tests for any behavioral change. CSS-only changes don't need new tests unless you add or remove a `fi-*` selector.[^1]
- The test suite runs against Filament 4 and 5, Laravel 12 and 13, and PHP 8.3 through 8.5. Your tests should not rely on version-specific behavior.
- Static analysis runs at PHPStan level 10. If you add new PHP code, it must pass `composer analyse` without baseline additions.

[^1]: The `FilamentSelectorIntegrityTest` validates that every `fi-*` class in `dist/theme.css` exists in Filament's source. If you reference a new Filament class, the test will verify it for you. If you reference one that doesn't exist, the test will catch it.

## Code style

### PHP

[Laravel Pint](https://laravel.com/docs/pint) with the `laravel` preset handles formatting. [Rector](https://getrector.com/) handles code modernization (typed constants, strict types, etc.). Run `composer fix` and both tools apply their changes.

A few specifics:

- Use `declare(strict_types=1)` in all PHP files
- Use typed properties and return types
- Use `readonly` on config classes and value objects
- Use `final` on classes that aren't designed for extension

### CSS

[Stylelint](https://stylelint.io/) enforces standard CSS rules and [recess property ordering](https://github.com/stormwarning/stylelint-config-recess-order). [Prettier](https://prettier.io/) handles formatting.

A few specifics:

- Use CSS custom properties from `variables.css` for colors, spacing, and transitions
- Scope selectors to `fi-*` Filament classes. Don't add global element selectors
- Support both light and dark mode (`.dark` class prefix)
- Test visual changes in a consuming application before submitting

### Commits

Use [Conventional Commits](https://www.conventionalcommits.org/) for your commit messages:

```
feat: add support for custom brand logo URL
fix: table header checkbox missing purple background
style: apply Prettier formatting to Blade views
ci: add prefer-lowest test run to matrix
chore: update CHANGELOG for v2.3.0
```

Keep the subject line under 70 characters. A body is optional; use one if the "why" isn't obvious from the subject.

## Pull requests

1. Fork the repo and create a branch from `main`
2. Make your changes and run `composer check` to verify everything passes
3. Push your branch and open a PR against `main`

The PR template includes a checklist. Fill it out. CI runs Rector, Pint, Prettier, Stylelint, PHPStan, the CSS build check, and Pest across the full PHP/Laravel matrix. All checks must pass before merge.

> [!NOTE]
> Open a draft PR if you want early feedback on an approach before finishing the implementation.

### CSS changes

Include a screenshot of the affected UI in your PR description. Theme changes are visual, and reviewers need to see what changed. Show both light and dark mode if both are affected.

### New features

Open an issue first to discuss the feature. The theme follows Northwestern's brand guidelines, so not all changes will be accepted. Discussing the approach up front saves you from building something that doesn't fit.

## Questions?

Open an issue on [GitHub](https://github.com/NIT-Administrative-Systems/northwestern-filament-theme/issues).
