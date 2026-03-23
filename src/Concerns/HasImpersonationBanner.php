<?php

declare(strict_types=1);

namespace Northwestern\FilamentTheme\Concerns;

use Closure;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Northwestern\FilamentTheme\ImpersonationBanner\ImpersonationBannerConfig;

trait HasImpersonationBanner
{
    protected ?ImpersonationBannerConfig $impersonationBannerConfig = null;

    protected bool $impersonationBannerEnabled = true;

    /**
     * Enable the impersonation banner.
     *
     * Auto-detects `lab404/laravel-impersonate` when no custom
     * visibility closure is provided. The banner renders a fixed
     * red bar at the top of the page during impersonation sessions.
     *
     * @param  bool|Closure(): bool|null  $visible  Custom visibility logic. Defaults to auto-detection.
     * @param  string|Closure(): string|null  $label  Custom banner label.
     * @param  string|Closure(): string|null  $leaveUrl  URL for the "Leave Impersonation" form action.
     * @param  string|Closure(): string|null  $leaveLabel  Custom leave button text. Defaults to "Leave Impersonation".
     * @param  string  $leaveMethod  HTTP method for the leave form (e.g. POST, DELETE, GET).
     */
    public function impersonationBanner(
        bool|Closure|null $visible = null,
        string|Closure|null $label = null,
        string|Closure|null $leaveUrl = null,
        string|Closure|null $leaveLabel = null,
        string $leaveMethod = 'POST',
    ): static {
        $this->impersonationBannerEnabled = true;
        $this->impersonationBannerConfig = new ImpersonationBannerConfig(
            visible: $visible,
            label: $label,
            leaveUrl: $leaveUrl,
            leaveLabel: $leaveLabel,
            leaveMethod: $leaveMethod,
        );

        return $this;
    }

    /** Disable the impersonation banner. */
    public function withoutImpersonationBanner(): static
    {
        $this->impersonationBannerEnabled = false;

        return $this;
    }

    /** Register the impersonation banner render hook. */
    protected function registerImpersonationBanner(): void
    {
        if (! $this->impersonationBannerEnabled) {
            return;
        }

        $bannerConfig = $this->impersonationBannerConfig ?? new ImpersonationBannerConfig();

        FilamentView::registerRenderHook(
            PanelsRenderHook::TOPBAR_BEFORE,
            fn (): string => $bannerConfig->isVisible()
                ? view('northwestern-filament-theme::impersonation-banner', ['config' => $bannerConfig])->render()
                : '',
        );
    }

    /**
     * Warn if a custom impersonation banner view exists.
     *
     * The northwestern-laravel-starter ships a manual
     * impersonation banner Blade component. When the
     * theme's built-in banner is enabled, the manual
     * one should be removed.
     */
    protected function detectLegacyImpersonationBanner(): void
    {
        if (! $this->impersonationBannerEnabled || ! App::isLocal()) {
            return;
        }

        if (view()->exists('components.filament.impersonation-banner')) { // @phpstan-ignore method.impossibleType (view exists in consuming apps, not this package)
            Log::warning(
                'A custom impersonation banner view [components.filament.impersonation-banner] was detected, but the Northwestern theme\'s built-in impersonation banner is enabled. '
                . 'Remove the custom view and its FilamentView::registerRenderHook() call from your FilamentServiceProvider to avoid duplicate banners.',
            );
        }
    }
}
