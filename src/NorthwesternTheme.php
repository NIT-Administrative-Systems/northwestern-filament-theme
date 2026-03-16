<?php

declare(strict_types=1);

namespace Northwestern\FilamentTheme;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Northwestern\FilamentTheme\EnvironmentIndicator\EnvironmentIndicatorConfig;
use Northwestern\FilamentTheme\Footer\FooterConfig;

/**
 * Filament plugin that applies Northwestern branding.
 *
 * @see Colors
 * @see EnvironmentIndicatorConfig
 * @see FooterConfig
 */
class NorthwesternTheme implements Plugin
{
    protected ?EnvironmentIndicatorConfig $environmentIndicatorConfig = null;

    protected bool $environmentIndicatorEnabled = true;

    protected ?FooterConfig $footerConfig = null;

    protected bool $registerAssets = true;

    public static function make(): static
    {
        /** @var static */
        return app(static::class);
    }

    public function getId(): string
    {
        return 'northwestern-theme';
    }

    /**
     * Configure the Northwestern footer.
     *
     * All office parameters default to null, falling back to
     * `config('northwestern-theme.office.*')` values at
     * render time.
     *
     * @param  bool|Closure(): bool  $enabled  Toggle footer rendering.
     * @param  non-empty-string|null  $officeName  Display name for the office block.
     * @param  non-empty-string|null  $officeAddr  Street address line.
     * @param  non-empty-string|null  $officeCity  City, state, and ZIP line.
     * @param  non-empty-string|null  $officePhone  Phone number (displayed as-is).
     * @param  non-empty-string|null  $officeEmail  Contact email address.
     */
    public function footer(
        bool|Closure $enabled = true,
        ?string $officeName = null,
        ?string $officeAddr = null,
        ?string $officeCity = null,
        ?string $officePhone = null,
        ?string $officeEmail = null,
    ): static {
        $this->footerConfig = new FooterConfig(
            enabled: $enabled,
            officeName: $officeName,
            officeAddr: $officeAddr,
            officeCity: $officeCity,
            officePhone: $officePhone,
            officeEmail: $officeEmail,
        );

        return $this;
    }

    /**
     * Skip CSS asset registration via FilamentAsset.
     *
     * Use when importing theme CSS in a Vite panel theme.
     * Footer CSS is still registered separately.
     */
    public function withoutAssetRegistration(): static
    {
        $this->registerAssets = false;

        return $this;
    }

    /**
     * Override the default environment indicator visibility.
     *
     * The indicator is enabled by default and auto-hides in
     * production. Use this to supply a custom visibility rule.
     *
     * @param  bool|Closure(): bool  $visible  Custom visibility logic.
     * @param  string|Closure(): string|null  $label  Custom badge label.
     */
    public function environmentIndicator(
        bool|Closure $visible = true,
        string|Closure|null $label = null,
    ): static {
        $this->environmentIndicatorEnabled = true;
        $this->environmentIndicatorConfig = new EnvironmentIndicatorConfig(
            visible: $visible,
            label: $label,
        );

        return $this;
    }

    /** Disable the environment indicator entirely. */
    public function withoutEnvironmentIndicator(): static
    {
        $this->environmentIndicatorEnabled = false;

        return $this;
    }

    /** Register brand colors and CSS assets with the panel. */
    public function register(Panel $panel): void
    {
        $panel
            ->colors([
                'primary' => Colors::PRIMARY,
                'danger' => Colors::DANGER_PALETTE,
                'gray' => Colors::GRAY_PALETTE,
                'info' => Colors::INFO_PALETTE,
                'success' => Colors::SUCCESS_PALETTE,
                'warning' => Colors::WARNING_PALETTE,
            ]);

        $distPath = __DIR__ . '/../dist';

        /** @var list<Css> $assets */
        $assets = [];

        if ($this->registerAssets) {
            $assets[] = Css::make('nu-theme', $distPath . '/theme.css');
        }

        if ($this->footerConfig instanceof FooterConfig) {
            $assets[] = Css::make('nu-footer', $distPath . '/footer.css');
        }

        if ($assets !== []) {
            FilamentAsset::register($assets, 'northwestern-sysdev/northwestern-filament-theme');
        }
    }

    /**
     * Apply default favicon, brand logo, and footer hook.
     *
     * Skips favicon and logo if the panel already
     * has its own configured.
     */
    public function boot(Panel $panel): void
    {
        $this->detectDoubleLoad($panel);
        $this->detectLegacyEnvironmentIndicator();

        if (! $panel->getFavicon()) {
            $panel->favicon('https://common.northwestern.edu/v8/icons/favicon-32.png');
        }

        if (! $panel->getBrandLogo()) {
            /** @var string $lockup */
            $lockup = config('northwestern-theme.lockup', 'https://common.northwestern.edu/v8/css/images/northwestern.svg');
            $panel->brandLogo($lockup);
        }

        if ($this->environmentIndicatorEnabled) {
            $envConfig = $this->environmentIndicatorConfig ?? new EnvironmentIndicatorConfig();

            FilamentView::registerRenderHook(
                PanelsRenderHook::GLOBAL_SEARCH_BEFORE,
                fn (): string => $envConfig->isVisible()
                    ? view('northwestern-filament-theme::environment-indicator', ['config' => $envConfig])->render()
                    : '',
            );
        }

        if ($this->footerConfig instanceof FooterConfig) {
            $footerConfig = $this->footerConfig;

            $panel->renderHook(
                PanelsRenderHook::BODY_END,
                fn (): string => $footerConfig->isEnabled()
                    ? view('northwestern-filament-theme::footer', ['config' => $footerConfig])->render()
                    : '',
            );
        }
    }

    /**
     * Warn if `pxlrbt/filament-environment-indicator` is installed.
     *
     * The environment indicator is built into the Northwestern
     * theme, so the third-party package is redundant.
     */
    protected function detectLegacyEnvironmentIndicator(): void
    {
        if (! App::isLocal()) {
            return;
        }

        if (class_exists(\Pxlrbt\FilamentEnvironmentIndicator\EnvironmentIndicatorPlugin::class)) {
            Log::warning(
                'The pxlrbt/filament-environment-indicator package is installed, but the Northwestern theme includes a built-in environment indicator. '
                . 'You can remove pxlrbt/filament-environment-indicator from your composer.json and remove EnvironmentIndicatorPlugin::make() from your panel provider(s).',
            );
        }
    }

    /**
     * Warn if theme CSS is registered and also
     * imported in the panel's Vite theme.
     */
    protected function detectDoubleLoad(Panel $panel): void
    {
        if (! $this->registerAssets || ! App::isLocal()) {
            return;
        }

        $panelId = $panel->getId();
        $themeCssPath = resource_path("css/filament/{$panelId}/theme.css");

        if (! file_exists($themeCssPath)) {
            return;
        }

        $themeCssContents = file_get_contents($themeCssPath);

        if ($themeCssContents !== false && str_contains($themeCssContents, 'northwestern-filament-theme')) {
            Log::warning(
                "Northwestern theme CSS is imported in your Vite theme [{$themeCssPath}] but asset registration is still active. "
                . 'This causes styles to load twice. Call ->withoutAssetRegistration() on the NorthwesternTheme plugin to fix this.',
            );
        }
    }
}
