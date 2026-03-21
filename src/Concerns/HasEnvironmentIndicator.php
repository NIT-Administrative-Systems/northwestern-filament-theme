<?php

declare(strict_types=1);

namespace Northwestern\FilamentTheme\Concerns;

use Closure;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Northwestern\FilamentTheme\EnvironmentIndicator\EnvironmentIndicatorConfig;

trait HasEnvironmentIndicator
{
    protected ?EnvironmentIndicatorConfig $environmentIndicatorConfig = null;

    protected bool $environmentIndicatorEnabled = true;

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

    /** Register the environment indicator render hook. */
    protected function registerEnvironmentIndicator(): void
    {
        if (! $this->environmentIndicatorEnabled) {
            return;
        }

        $envConfig = $this->environmentIndicatorConfig ?? new EnvironmentIndicatorConfig();

        FilamentView::registerRenderHook(
            PanelsRenderHook::GLOBAL_SEARCH_BEFORE,
            fn (): string => $envConfig->isVisible()
                ? view('northwestern-filament-theme::environment-indicator', ['config' => $envConfig])->render()
                : '',
        );
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
                . 'Remove pxlrbt/filament-environment-indicator from your composer.json and remove EnvironmentIndicatorPlugin::make() from your panel provider(s).',
            );
        }
    }
}
