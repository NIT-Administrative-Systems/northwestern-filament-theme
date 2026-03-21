<?php

declare(strict_types=1);

namespace Northwestern\FilamentTheme;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Northwestern\FilamentTheme\Concerns\HasEnvironmentIndicator;
use Northwestern\FilamentTheme\Concerns\HasFooter;
use Northwestern\FilamentTheme\Concerns\HasImpersonationBanner;

/**
 * Filament plugin that applies Northwestern branding.
 *
 * @see Colors
 * @see EnvironmentIndicator\EnvironmentIndicatorConfig
 * @see Footer\FooterConfig
 * @see ImpersonationBanner\ImpersonationBannerConfig
 */
class NorthwesternTheme implements Plugin
{
    use HasEnvironmentIndicator;
    use HasFooter;
    use HasImpersonationBanner;

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
     * Skip CSS asset registration via FilamentAsset.
     *
     * Use when importing theme CSS in a Vite panel theme.
     */
    public function withoutAssetRegistration(): static
    {
        $this->registerAssets = false;

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

        if ($assets !== []) {
            FilamentAsset::register($assets, 'northwestern-sysdev/northwestern-filament-theme');
        }
    }

    /**
     * Apply default favicon, brand logo, and render hooks.
     *
     * Skips favicon and logo if the panel already
     * has its own configured.
     */
    public function boot(Panel $panel): void
    {
        $this->detectDoubleLoad($panel);
        $this->detectLegacyEnvironmentIndicator();
        $this->detectLegacyImpersonationBanner();

        $this->applyBranding($panel);
        $this->registerEnvironmentIndicator();
        $this->registerImpersonationBanner();
        $this->registerFooter();
    }

    /** Set default favicon and brand logo when the panel has none. */
    protected function applyBranding(Panel $panel): void
    {
        if (! $panel->getFavicon()) {
            $panel->favicon('https://common.northwestern.edu/v8/icons/favicon-32.png');
        }

        if (! $panel->getBrandLogo()) {
            /** @var string $lockup */
            $lockup = config('northwestern-theme.lockup', 'https://common.northwestern.edu/v8/css/images/northwestern.svg');
            $panel->brandLogo($lockup);
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
