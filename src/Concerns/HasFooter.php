<?php

declare(strict_types=1);

namespace Northwestern\FilamentTheme\Concerns;

use Closure;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Northwestern\FilamentTheme\Footer\FooterConfig;

trait HasFooter
{
    protected ?FooterConfig $footerConfig = null;

    /**
     * Configure the Northwestern footer.
     *
     * The footer is on by default. All office parameters default to
     * null, falling back to `config('northwestern-theme.office.*')`
     * values at render time. The required university links always
     * render; `$links` adds to them.
     *
     * @param  bool|Closure(): bool  $enabled  Toggle footer rendering.
     * @param  non-empty-string|null  $officeName  Display name for the office block.
     * @param  non-empty-string|null  $officeAddr  Street address line.
     * @param  non-empty-string|null  $officeCity  City, state, and ZIP line.
     * @param  non-empty-string|null  $officePhone  Phone number (displayed as-is).
     * @param  non-empty-string|null  $officeEmail  Contact email address.
     * @param  non-empty-string|null  $officeFax  Fax number (displayed as-is).
     * @param  array<string, string>  $links  Extra links as label => URL, shown under "Quick Links".
     */
    public function footer(
        bool|Closure $enabled = true,
        ?string $officeName = null,
        ?string $officeAddr = null,
        ?string $officeCity = null,
        ?string $officePhone = null,
        ?string $officeEmail = null,
        ?string $officeFax = null,
        array $links = [],
    ): static {
        $this->footerConfig = new FooterConfig(
            enabled: $enabled,
            officeName: $officeName,
            officeAddr: $officeAddr,
            officeCity: $officeCity,
            officePhone: $officePhone,
            officeEmail: $officeEmail,
            officeFax: $officeFax,
            links: $links,
        );

        return $this;
    }

    /** Register the footer render hook on full and simple layouts. */
    protected function registerFooter(): void
    {
        $footerConfig = $this->footerConfig ?? new FooterConfig();

        if ($footerConfig->enabled === false) {
            return;
        }

        FilamentView::registerRenderHook(
            PanelsRenderHook::FOOTER,
            fn (): string => $footerConfig->isEnabled()
                ? view('northwestern-filament-theme::footer', ['config' => $footerConfig])->render()
                : '',
        );
    }
}
