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

    /** Register the footer render hook. */
    protected function registerFooter(): void
    {
        if (! $this->footerConfig instanceof FooterConfig) {
            return;
        }

        $footerConfig = $this->footerConfig;

        FilamentView::registerRenderHook(
            PanelsRenderHook::BODY_END,
            fn (): string => $footerConfig->isEnabled()
                ? view('northwestern-filament-theme::footer', ['config' => $footerConfig])->render()
                : '',
        );
    }
}
