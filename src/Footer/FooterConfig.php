<?php

declare(strict_types=1);

namespace Northwestern\FilamentTheme\Footer;

use Closure;
use Northwestern\FilamentTheme\ConfigResolver;

/**
 * Per-panel footer settings, and the footer content the view renders.
 *
 * The content comes from config('northwestern-filament-theme.*'). The office
 * and links values here are the deprecated `->footer()` arguments and footer
 * component props, which still take precedence. See ConfigResolver for the
 * full resolution order.
 */
readonly class FooterConfig
{
    /**
     * Office details shown when nothing else sets them.
     *
     * @var array{name: string, addr: string, city: string, phone: string, fax: null, email: string}
     */
    public const array DEFAULT_OFFICE = [
        'name' => ConfigResolver::DEFAULT_UNIT['name'],
        'addr' => ConfigResolver::DEFAULT_UNIT['address'],
        'city' => ConfigResolver::DEFAULT_UNIT['city'],
        'phone' => ConfigResolver::DEFAULT_UNIT['phone'],
        'fax' => ConfigResolver::DEFAULT_UNIT['fax'],
        'email' => ConfigResolver::DEFAULT_UNIT['email'],
    ];

    /**
     * @param  bool|Closure(): bool  $enabled  Whether the footer renders.
     * @param  non-empty-string|null  $officeName  Deprecated: set `unit.name` in config/northwestern-filament-theme.php.
     * @param  non-empty-string|null  $officeAddr  Deprecated: set `unit.address` in config/northwestern-filament-theme.php.
     * @param  non-empty-string|null  $officeCity  Deprecated: set `unit.city` in config/northwestern-filament-theme.php.
     * @param  non-empty-string|null  $officePhone  Deprecated: set `unit.phone` in config/northwestern-filament-theme.php.
     * @param  non-empty-string|null  $officeEmail  Deprecated: set `unit.email` in config/northwestern-filament-theme.php.
     * @param  non-empty-string|null  $officeFax  Deprecated: set `unit.fax` in config/northwestern-filament-theme.php.
     * @param  array<string, string>  $links  Deprecated: set `footer.links` in config/northwestern-filament-theme.php.
     */
    public function __construct(
        public bool|Closure $enabled = true,
        public ?string $officeName = null,
        public ?string $officeAddr = null,
        public ?string $officeCity = null,
        public ?string $officePhone = null,
        public ?string $officeEmail = null,
        public ?string $officeFax = null,
        public array $links = [],
    ) {
    }

    /** Resolve the enabled state, evaluating closures. */
    public function isEnabled(): bool
    {
        $enabled = $this->enabled;

        return $enabled instanceof Closure ? (bool) ($enabled)() : $enabled;
    }

    /**
     * The unit's contact details, keyed as in the legacy `office` config so
     * that footer views published from v4.0 keep working. A null field is hidden.
     *
     * @return array{name: ?non-empty-string, addr: ?non-empty-string, city: ?non-empty-string, phone: ?non-empty-string, fax: ?non-empty-string, email: ?non-empty-string}
     */
    public function office(): array
    {
        $unit = ConfigResolver::unit([
            'name' => $this->officeName,
            'address' => $this->officeAddr,
            'city' => $this->officeCity,
            'phone' => $this->officePhone,
            'fax' => $this->officeFax,
            'email' => $this->officeEmail,
        ]);

        return [
            'name' => $unit['name'],
            'addr' => $unit['address'],
            'city' => $unit['city'],
            'phone' => $unit['phone'],
            'fax' => $unit['fax'],
            'email' => $unit['email'],
        ];
    }

    /**
     * The links under "Quick Links", as label => URL.
     *
     * @return array<string, string>
     */
    public function quickLinks(): array
    {
        return ConfigResolver::links($this->links);
    }

    /**
     * The "Connect" accounts, as network => URL. Empty hides the section.
     *
     * @return array<value-of<SocialNetwork>, string>
     */
    public function social(): array
    {
        return ConfigResolver::social();
    }
}
