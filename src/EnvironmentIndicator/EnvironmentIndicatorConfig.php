<?php

declare(strict_types=1);

namespace Northwestern\FilamentTheme\EnvironmentIndicator;

use Closure;
use Illuminate\Support\Facades\App;

/**
 * Configuration for the environment indicator.
 *
 * The indicator is enabled by default and auto-hides
 * in production unless a custom visibility closure
 * is provided.
 */
readonly class EnvironmentIndicatorConfig
{
    /**
     * @param  bool|Closure(): bool  $visible  Whether the indicator renders.
     * @param  string|Closure(): string|null  $label  Custom badge label.
     */
    public function __construct(
        public bool|Closure $visible = true,
        public string|Closure|null $label = null,
    ) {
    }

    /** Resolve the badge label, evaluating closures. */
    public function resolveLabel(): string
    {
        $label = $this->label;

        if ($label instanceof Closure) {
            return (string) ($label)();
        }

        return $label ?? 'Environment: ' . ucfirst(App::environment());
    }

    /** Resolve visibility, evaluating closures. */
    public function isVisible(): bool
    {
        $visible = $this->visible;

        if ($visible instanceof Closure) {
            return (bool) ($visible)();
        }

        return $visible && ! App::isProduction();
    }
}
