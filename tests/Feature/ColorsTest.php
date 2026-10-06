<?php

declare(strict_types=1);

use Northwestern\FilamentTheme\Colors;

/**
 * WCAG relative luminance of a palette color, given as `oklch(L C h)` or `r, g, b`.
 */
function relativeLuminance(string $color): float
{
    if (preg_match('/^oklch\(([\d.]+) ([\d.]+) ([\d.]+)\)$/', $color, $match) === 1) {
        [$lightness, $chroma, $hue] = [(float) $match[1], (float) $match[2], deg2rad((float) $match[3])];
        $a = $chroma * cos($hue);
        $b = $chroma * sin($hue);

        $l = ($lightness + 0.3963377774 * $a + 0.2158037573 * $b) ** 3;
        $m = ($lightness - 0.1055613458 * $a - 0.0638541728 * $b) ** 3;
        $s = ($lightness - 0.0894841775 * $a - 1.2914855480 * $b) ** 3;

        $linear = [
            4.0767416621 * $l - 3.3077115913 * $m + 0.2309699292 * $s,
            -1.2684380046 * $l + 2.6097574011 * $m - 0.3413193965 * $s,
            -0.0041960863 * $l - 0.7034186147 * $m + 1.7076147010 * $s,
        ];
    } else {
        $linear = array_map(function (string $channel): float {
            $value = (int) trim($channel) / 255;

            return $value <= 0.04045 ? $value / 12.92 : (($value + 0.055) / 1.055) ** 2.4;
        }, explode(',', $color));
    }

    [$red, $green, $blue] = array_map(fn (float $channel): float => min(max($channel, 0.0), 1.0), $linear);

    return 0.2126 * $red + 0.7152 * $green + 0.0722 * $blue;
}

function contrastRatio(string $foreground, string $background): float
{
    $lighter = max(relativeLuminance($foreground), relativeLuminance($background));
    $darker = min(relativeLuminance($foreground), relativeLuminance($background));

    return ($lighter + 0.05) / ($darker + 0.05);
}

// Filament colors text in dark mode with a palette's 400 shade, on cards in gray 900.
it('keeps dark-mode text readable on dark cards', function (array $palette) {
    expect(contrastRatio($palette[400], Colors::GRAY_PALETTE[900]))->toBeGreaterThanOrEqual(4.5);
})->with([
    'primary' => [Colors::PRIMARY],
    'danger' => [Colors::DANGER_PALETTE],
    'gray' => [Colors::GRAY_PALETTE],
    'info' => [Colors::INFO_PALETTE],
    'success' => [Colors::SUCCESS_PALETTE],
    'warning' => [Colors::WARNING_PALETTE],
]);
