<?php

declare(strict_types=1);

namespace Northwestern\FilamentTheme\Footer;

/**
 * The footer's CSS, inlined by the footer view.
 *
 * The footer renders inside Filament panels and on standalone pages
 * that load only dist/tokens.css, such as an error layout, so it
 * carries its own styles instead of relying on dist/theme.css.
 */
final class FooterStylesheet
{
    private static ?string $css = null;

    public static function css(): string
    {
        return self::$css ??= (string) file_get_contents(__DIR__ . '/../../resources/css/footer.css');
    }
}
