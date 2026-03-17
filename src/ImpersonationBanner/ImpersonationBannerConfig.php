<?php

declare(strict_types=1);

namespace Northwestern\FilamentTheme\ImpersonationBanner;

use Closure;

/**
 * Configuration for the impersonation banner.
 *
 * Auto-detects `lab404/laravel-impersonate` when no custom
 * visibility closure is provided. The banner only renders
 * when an impersonation session is active.
 */
readonly class ImpersonationBannerConfig
{
    public string $leaveMethod;

    /**
     * @param  bool|Closure(): bool|null  $visible  Custom visibility logic. Defaults to auto-detection.
     * @param  string|Closure(): string|null  $label  Custom banner label.
     * @param  string|Closure(): string|null  $leaveUrl  URL for the "Leave Impersonation" form action.
     * @param  string|Closure(): string|null  $leaveLabel  Custom leave button text. Defaults to "Leave Impersonation".
     * @param  string  $leaveMethod  HTTP method for the leave form (e.g. POST, DELETE, GET).
     */
    public function __construct(
        public bool|Closure|null $visible = null,
        public string|Closure|null $label = null,
        public string|Closure|null $leaveUrl = null,
        public string|Closure|null $leaveLabel = null,
        string $leaveMethod = 'POST',
    ) {
        $this->leaveMethod = strtoupper($leaveMethod);
    }

    /** Resolve the leave button label, evaluating closures. */
    public function resolveLeaveLabel(): string
    {
        $leaveLabel = $this->leaveLabel;

        if ($leaveLabel instanceof Closure) {
            return (string) $leaveLabel();
        }

        return $leaveLabel ?? 'Leave Impersonation';
    }

    /** Resolve visibility, falling back to lab404 auto-detection. */
    public function isVisible(): bool
    {
        $visible = $this->visible;

        if ($visible instanceof Closure) {
            return (bool) $visible();
        }

        if (is_bool($visible)) {
            return $visible;
        }

        // Auto-detect lab404/laravel-impersonate
        if (function_exists('is_impersonating')) {
            /** @var bool */
            return is_impersonating();
        }

        return false;
    }

    /** Resolve the banner label, evaluating closures. */
    public function resolveLabel(): string
    {
        $label = $this->label;

        if ($label instanceof Closure) {
            return (string) $label();
        }

        if (is_string($label)) {
            return $label;
        }

        /** @var object{full_name?: string, name?: string, email?: string}|null $user */
        $user = auth()->user();

        /** @var string $username */
        $username = $user?->full_name ?? $user?->name ?? $user?->email ?? 'Unknown'; // @phpstan-ignore-line

        return 'Impersonating user · ' . $username;
    }

    /** Resolve the leave URL, evaluating closures. */
    public function resolveLeaveUrl(): ?string
    {
        $leaveUrl = $this->leaveUrl;

        if ($leaveUrl instanceof Closure) {
            return (string) ($leaveUrl)();
        }

        if (is_string($leaveUrl)) {
            return $leaveUrl;
        }

        // Auto-detect lab404 route
        try {
            return route('impersonate.leave');
        } catch (\Symfony\Component\Routing\Exception\RouteNotFoundException) {
            return null;
        }
    }
}
