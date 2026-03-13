<?php

declare(strict_types=1);

namespace Northwestern\FilamentTheme\Footer;

use Closure;

/**
 * Immutable configuration for the Northwestern footer component.
 *
 * Office fields fall back to `config('northwestern-theme.office.*')` at
 * render time when empty, so consumers only need to specify overrides.
 */
readonly class FooterConfig
{
    /**
     * @param  bool|Closure(): bool  $enabled  Whether the footer renders.
     * @param  non-empty-string|null  $officeName  Display name for the office block.
     * @param  non-empty-string|null  $officeAddr  Street address line.
     * @param  non-empty-string|null  $officeCity  City, state, and ZIP line.
     * @param  non-empty-string|null  $officePhone  Phone number (displayed as-is).
     * @param  non-empty-string|null  $officeEmail  Contact email address.
     */
    public function __construct(
        public bool|Closure $enabled = true,
        public ?string $officeName = null,
        public ?string $officeAddr = null,
        public ?string $officeCity = null,
        public ?string $officePhone = null,
        public ?string $officeEmail = null,
    ) {
    }

    /** Resolve the enabled state, evaluating the closure if one was provided. */
    public function isEnabled(): bool
    {
        $enabled = $this->enabled;

        return $enabled instanceof Closure ? (bool) ($enabled)() : $enabled;
    }
}
