<?php

declare(strict_types=1);

namespace Northwestern\FilamentTheme\Concerns;

use Closure;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Northwestern\FilamentTheme\Footer\FooterConfig;

trait HasFooter
{
    /** Request attribute set once a simple layout has rendered the footer. */
    protected const string FOOTER_RENDERED = 'northwestern-theme.footer-rendered';

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

    /**
     * Register the footer render hooks: after the whole layout on full
     * pages, so it spans the sidebar and the content, and inside the
     * layout on simple pages, so it sits below the card.
     */
    protected function registerFooter(): void
    {
        $footerConfig = $this->footerConfig ?? new FooterConfig();

        if ($footerConfig->enabled === false) {
            return;
        }

        $render = fn (): string => $footerConfig->isEnabled()
            ? view('northwestern-filament-theme::footer', ['config' => $footerConfig])->render()
            : '';

        // Simple pages: inside the simple layout, so the footer shares the first screen with the card.
        // Blade renders the layout's content before the body around it, so this runs before BODY_END.
        FilamentView::registerRenderHook(PanelsRenderHook::SIMPLE_LAYOUT_END, function () use ($render): string {
            request()->attributes->set(self::FOOTER_RENDERED, true);

            return $render();
        });

        // Full pages: after the whole layout, so the footer spans the sidebar and the content.
        FilamentView::registerRenderHook(
            PanelsRenderHook::BODY_END,
            fn (): string => request()->attributes->get(self::FOOTER_RENDERED) ? '' : $render(),
        );
    }
}
