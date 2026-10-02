<?php

declare(strict_types=1);

namespace Northwestern\FilamentTheme;

use Northwestern\FilamentTheme\Footer\SocialNetwork;

/**
 * Resolves the theme's branding values, each in the same order:
 *
 * 1. An explicit argument to `->footer()` or a footer component prop (deprecated).
 * 2. `config('northwestern-filament-theme.*')`, the theme's own config.
 * 3. The legacy `config('northwestern-theme.*')` key published by
 *    northwestern-sysdev/northwestern-laravel-ui, which v2 apps still use.
 * 4. The built-in default.
 *
 * In steps 1 and 2, null means "not set" and moves on to the next step. In the
 * legacy file a key that is present wins even when it is null, as it did before
 * the theme had its own config. A value that resolves to an empty string or null
 * is hidden.
 *
 * When northwestern-laravel-ui is retired, delete legacy() and the explicit
 * arguments, and the remaining reads go straight to the theme's config.
 *
 * @internal
 */
final class ConfigResolver
{
    /**
     * Unit details shown when nothing else sets them.
     *
     * @var array{name: string, address: string, city: string, phone: string, fax: null, email: string}
     */
    public const array DEFAULT_UNIT = [
        'name' => 'Information Technology',
        'address' => '1800 Sherman Ave',
        'city' => 'Evanston, IL 60201',
        'phone' => '847-491-4357 (1-HELP)',
        'fax' => null,
        'email' => 'consultant@northwestern.edu',
    ];

    /** The legacy `northwestern-theme.office.*` key for each unit field. */
    private const array LEGACY_UNIT_KEYS = [
        'name' => 'office.name',
        'address' => 'office.addr',
        'city' => 'office.city',
        'phone' => 'office.phone',
        'fax' => 'office.fax',
        'email' => 'office.email',
    ];

    /** The brand logo: a URL or public path, or null for the dept 4.0 wordmark. */
    public static function lockup(): ?string
    {
        $lockup = config('northwestern-filament-theme.lockup') ?? self::legacy('lockup');

        return is_string($lockup) && $lockup !== '' ? $lockup : null;
    }

    /**
     * The unit's footer details.
     *
     * @param  array<key-of<self::DEFAULT_UNIT>, ?string>  $explicit  Deprecated `->footer()` arguments or component props.
     * @return array{name: ?non-empty-string, address: ?non-empty-string, city: ?non-empty-string, phone: ?non-empty-string, fax: ?non-empty-string, email: ?non-empty-string}
     */
    public static function unit(array $explicit = []): array
    {
        return [
            'name' => self::unitField('name', $explicit),
            'address' => self::unitField('address', $explicit),
            'city' => self::unitField('city', $explicit),
            'phone' => self::unitField('phone', $explicit),
            'fax' => self::unitField('fax', $explicit),
            'email' => self::unitField('email', $explicit),
        ];
    }

    /**
     * The links under "Quick Links", as label => URL.
     *
     * @param  array<string, string>  $explicit  Deprecated `->footer()` argument or component prop. [] means not set.
     * @return array<string, string>
     */
    public static function links(array $explicit = []): array
    {
        return self::urls($explicit !== [] ? $explicit : config('northwestern-filament-theme.footer.links'));
    }

    /**
     * The "Connect" accounts, as network => URL, in display order.
     *
     * A config file that leaves `footer.social` out gets Northwestern's
     * accounts; `[]` hides the section. Unknown networks are skipped.
     *
     * @return array<value-of<SocialNetwork>, string>
     */
    public static function social(): array
    {
        $accounts = [];

        foreach (self::urls(config('northwestern-filament-theme.footer.social') ?? SocialNetwork::NORTHWESTERN) as $network => $url) {
            $network = SocialNetwork::tryFrom($network);

            if ($network !== null) {
                $accounts[$network->value] = $url;
            }
        }

        return $accounts;
    }

    /**
     * Keep the string => non-empty string pairs of a config array.
     *
     * @return array<string, string>
     */
    private static function urls(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        $urls = [];

        foreach ($value as $key => $url) {
            if (is_string($url) && $url !== '') {
                $urls[(string) $key] = $url;
            }
        }

        return $urls;
    }

    /**
     * @param  key-of<self::DEFAULT_UNIT>  $field
     * @param  array<key-of<self::DEFAULT_UNIT>, ?string>  $explicit
     * @return ?non-empty-string
     */
    private static function unitField(string $field, array $explicit): ?string
    {
        $value = $explicit[$field]
            ?? config("northwestern-filament-theme.unit.{$field}")
            ?? self::legacy(self::LEGACY_UNIT_KEYS[$field], self::DEFAULT_UNIT[$field]);

        return is_string($value) && $value !== '' ? $value : null;
    }

    /** Read a key from the legacy northwestern-theme config, or $default when the key is absent. */
    private static function legacy(string $key, mixed $default = null): mixed
    {
        return config()->has("northwestern-theme.{$key}") ? config("northwestern-theme.{$key}") : $default;
    }
}
