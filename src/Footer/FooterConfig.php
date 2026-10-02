<?php

declare(strict_types=1);

namespace Northwestern\FilamentTheme\Footer;

use Closure;

/**
 * Configuration for the Northwestern footer.
 *
 * Empty office fields fall back to
 * config('northwestern-theme.office.*').
 */
readonly class FooterConfig
{
    /**
     * Office details shown when neither the plugin nor config sets them.
     *
     * @var array{name: string, addr: string, city: string, phone: string, fax: null, email: string}
     */
    public const array DEFAULT_OFFICE = [
        'name' => 'Information Technology',
        'addr' => '1800 Sherman Ave',
        'city' => 'Evanston, IL 60201',
        'phone' => '847-491-4357 (1-HELP)',
        'fax' => null,
        'email' => 'consultant@northwestern.edu',
    ];

    /**
     * @param  bool|Closure(): bool  $enabled  Whether the footer renders.
     * @param  non-empty-string|null  $officeName  Display name for the office block.
     * @param  non-empty-string|null  $officeAddr  Street address line.
     * @param  non-empty-string|null  $officeCity  City, state, and ZIP line.
     * @param  non-empty-string|null  $officePhone  Phone number (displayed as-is).
     * @param  non-empty-string|null  $officeEmail  Contact email address.
     * @param  non-empty-string|null  $officeFax  Fax number (displayed as-is). Hidden when empty.
     * @param  array<string, string>  $links  Extra links as label => URL, shown under "Quick Links".
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
     * Resolve the office details: plugin values first, then
     * config('northwestern-theme.office.*'), then DEFAULT_OFFICE.
     *
     * An empty string in config hides that field.
     *
     * @return array{name: ?non-empty-string, addr: ?non-empty-string, city: ?non-empty-string, phone: ?non-empty-string, fax: ?non-empty-string, email: ?non-empty-string}
     */
    public function office(): array
    {
        return [
            'name' => $this->resolveOfficeField('name', $this->officeName),
            'addr' => $this->resolveOfficeField('addr', $this->officeAddr),
            'city' => $this->resolveOfficeField('city', $this->officeCity),
            'phone' => $this->resolveOfficeField('phone', $this->officePhone),
            'fax' => $this->resolveOfficeField('fax', $this->officeFax),
            'email' => $this->resolveOfficeField('email', $this->officeEmail),
        ];
    }

    /**
     * @param  key-of<self::DEFAULT_OFFICE>  $field
     * @return ?non-empty-string
     */
    protected function resolveOfficeField(string $field, ?string $value): ?string
    {
        $value ??= config("northwestern-theme.office.{$field}", self::DEFAULT_OFFICE[$field]);

        return is_string($value) && $value !== '' ? $value : null;
    }
}
