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
use Northwestern\FilamentTheme\Footer\FooterConfig;

/**
 * Filament plugin that applies Northwestern University branding.
 *
 * Registers brand colors, typography, layout overrides, and an optional
 * footer.
 *
 * @see Colors
 * @see FooterConfig
 */
class NorthwesternTheme implements Plugin
{
    protected ?FooterConfig $footerConfig = null;

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
     * `config('northwestern-theme.office.*')` values at render time.
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
     * Register brand colors and CSS assets with the panel.
     *
     * Footer CSS is only registered when {@see self::footer()} has been called.
     */
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

        $cssPath = __DIR__ . '/../resources/css';

        /** @var list<Css> $assets */
        $assets = [
            Css::make('nu-variables', $cssPath . '/variables.css'),
            Css::make('nu-typography', $cssPath . '/typography.css'),
            Css::make('nu-layout', $cssPath . '/layout.css'),
            Css::make('nu-buttons', $cssPath . '/buttons.css'),
            Css::make('nu-forms', $cssPath . '/forms.css'),
            Css::make('nu-tables', $cssPath . '/tables.css'),
            Css::make('nu-navigation', $cssPath . '/navigation.css'),
            Css::make('nu-components', $cssPath . '/components.css'),
            Css::make('nu-utilities', $cssPath . '/utilities.css'),
        ];

        if ($this->footerConfig instanceof FooterConfig) {
            $assets[] = Css::make('nu-footer', $cssPath . '/footer.css');
        }

        FilamentAsset::register($assets, 'northwestern-sysdev/northwestern-filament-theme');
    }

    /**
     * Apply default favicon, brand logo, and footer render hook.
     *
     * Favicon and logo are only set when the panel has not already
     * configured them, allowing consumer overrides to take precedence.
     */
    public function boot(Panel $panel): void
    {
        if (! $panel->getFavicon()) {
            $panel->favicon('https://common.northwestern.edu/v8/icons/favicon-32.png');
        }

        if (! $panel->getBrandLogo()) {
            /** @var string $lockup */
            $lockup = config('northwestern-theme.lockup', 'https://common.northwestern.edu/v8/css/images/northwestern.svg');
            $panel->brandLogo($lockup);
        }

        if ($this->footerConfig instanceof FooterConfig) {
            $config = $this->footerConfig;

            FilamentView::registerRenderHook(
                PanelsRenderHook::BODY_END,
                fn (): string => $config->isEnabled()
                    ? view('northwestern-filament-theme::footer', ['config' => $config])->render()
                    : '',
            );
        }
    }
}
