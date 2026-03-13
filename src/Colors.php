<?php

declare(strict_types=1);

namespace Northwestern\FilamentTheme;

/**
 * Northwestern University brand color constants.
 *
 * Hex constants follow the official brand guidelines. Palette arrays
 * use Filament's 50–950 shade scale and are formatted for
 * {@see \Filament\Panel::colors()}.
 *
 * @see https://www.northwestern.edu/brand/visual-identity/color/index.html
 */
final class Colors
{
    // -------------------------------------------------------------------------
    // Primary: Northwestern Purple scale
    // -------------------------------------------------------------------------

    public const PURPLE_10 = '#E4E0EE';

    public const PURPLE_20 = '#CCC4DF';

    public const PURPLE_30 = '#B6ACD1';

    public const PURPLE_40 = '#A495C3';

    public const PURPLE_50 = '#9380B6';

    public const PURPLE_60 = '#836EAA';

    public const PURPLE_70 = '#765DA0';

    public const PURPLE_80 = '#684C96';

    public const PURPLE_90 = '#5B3B90';

    public const PURPLE_100 = '#4E2A84';

    public const PURPLE_110 = '#482476';

    public const PURPLE_120 = '#401F68';

    public const PURPLE_130 = '#38175A';

    public const PURPLE_140 = '#30104E';

    public const PURPLE_150 = '#260841';

    public const PURPLE_160 = '#1D0235';

    // -------------------------------------------------------------------------
    // Primary: Rich Black scale
    // -------------------------------------------------------------------------

    public const RICH_BLACK_100 = '#000000';

    public const RICH_BLACK_80 = '#342F2E';

    public const RICH_BLACK_50 = '#716C6B';

    public const RICH_BLACK_20 = '#BBB8B8';

    public const RICH_BLACK_10 = '#D8D6D6';

    // -------------------------------------------------------------------------
    // Secondary: Brights
    // -------------------------------------------------------------------------

    public const GREEN = '#58B947';

    public const TEAL = '#7FCECD';

    public const BLUE = '#5091CD';

    public const YELLOW = '#EDE93B';

    public const GOLD = '#FFC520';

    public const DANGER = '#dc3545';

    public const ORANGE = '#EF553F';

    // -------------------------------------------------------------------------
    // Secondary: Darks
    // -------------------------------------------------------------------------

    public const DARK_GREEN = '#008656';

    public const DARK_TEAL = '#007FA4';

    public const DARK_BLUE = '#0D2D6C';

    public const DARK_YELLOW = '#D9C826';

    public const DARK_GOLD = '#CA7C1B';

    public const DARK_ORANGE = '#D85820';

    // -------------------------------------------------------------------------
    // Filament palette arrays (50–950 scales)
    //
    // Color::hex() normalizes all inputs to an identical lightness/chroma curve,
    // discarding the actual color's saturation and brightness. These explicit
    // palettes preserve the true Bootstrap/NU color at the anchor shade and
    // scale proportionally so UI elements match the established NU Laravel
    // UI look across the board.
    // -------------------------------------------------------------------------

    /**
     * Primary — Northwestern Purple.
     *
     * Mapped from the official Purple 10–160 scale (RGB triplets).
     *
     * @var array<int<50, 950>, non-empty-string>
     */
    public const PRIMARY = [
        50 => '228, 224, 238',  // Purple 10
        100 => '204, 196, 223', // Purple 20
        200 => '182, 172, 209', // Purple 30
        300 => '164, 149, 195', // Purple 40
        400 => '147, 128, 182', // Purple 50
        500 => '131, 110, 170', // Purple 60
        600 => '78, 42, 132',   // Purple 100 (brand primary)
        700 => '64, 31, 104',   // Purple 120
        800 => '48, 16, 78',    // Purple 140
        900 => '38, 8, 65',     // Purple 150
        950 => '29, 2, 53',     // Purple 160
    ];

    /**
     * Danger — Bootstrap red (#dc3545), matching NU Laravel UI.
     *
     * @var array<int<50, 950>, non-empty-string>
     */
    public const DANGER_PALETTE = [
        50 => 'oklch(0.9800 0.0162 21.239)',
        100 => 'oklch(0.9153 0.0471 21.239)',
        200 => 'oklch(0.8507 0.0781 21.239)',
        300 => 'oklch(0.7860 0.1091 21.239)',
        400 => 'oklch(0.7213 0.1401 21.239)',
        500 => 'oklch(0.6567 0.1710 21.239)',
        600 => 'oklch(0.5920 0.2020 21.239)',
        700 => 'oklch(0.5236 0.1838 21.239)',
        800 => 'oklch(0.4552 0.1656 21.239)',
        900 => 'oklch(0.3868 0.1475 21.239)',
        950 => 'oklch(0.3184 0.1293 21.239)',
    ];

    /**
     * Success — NU Dark Green (#008656), matching NU Laravel UI.
     *
     * @var array<int<50, 950>, non-empty-string>
     */
    public const SUCCESS_PALETTE = [
        50 => 'oklch(0.9800 0.0100 159.536)',
        100 => 'oklch(0.9078 0.0292 159.536)',
        200 => 'oklch(0.8357 0.0483 159.536)',
        300 => 'oklch(0.7635 0.0675 159.536)',
        400 => 'oklch(0.6913 0.0867 159.536)',
        500 => 'oklch(0.6192 0.1058 159.536)',
        600 => 'oklch(0.5470 0.1250 159.536)',
        700 => 'oklch(0.4876 0.1138 159.536)',
        800 => 'oklch(0.4282 0.1025 159.536)',
        900 => 'oklch(0.3688 0.0912 159.536)',
        950 => 'oklch(0.3094 0.0800 159.536)',
    ];

    /**
     * Warning — NU Gold (#FFC520). Anchored at shade 400 where Filament renders button backgrounds.
     *
     * @var array<int<50, 950>, non-empty-string>
     */
    public const WARNING_PALETTE = [
        50 => 'oklch(0.9800 0.0135 86.169)',
        100 => 'oklch(0.9480 0.0524 86.169)',
        200 => 'oklch(0.9160 0.0913 86.169)',
        300 => 'oklch(0.8840 0.1301 86.169)',
        400 => 'oklch(0.8520 0.1690 86.169)',  // #FFC520
        500 => 'oklch(0.7660 0.1593 86.169)',
        600 => 'oklch(0.6800 0.1497 86.169)',
        700 => 'oklch(0.5940 0.1400 86.169)',
        800 => 'oklch(0.5080 0.1304 86.169)',
        900 => 'oklch(0.4220 0.1207 86.169)',
        950 => 'oklch(0.3360 0.1111 86.169)',
    ];

    /**
     * Gray — Based on NU Rich Black, with darker 900/950 for dark mode surfaces.
     *
     * Default Color::hex() produces 900=0.394 / 950=0.278 which is too bright
     * against the #18181b body background. These are pulled down to match.
     *
     * @var array<int<50, 950>, non-empty-string>
     */
    public const GRAY_PALETTE = [
        50 => 'oklch(0.977 0 31.068)',
        100 => 'oklch(0.950 0 31.068)',
        200 => 'oklch(0.905 0 31.068)',
        300 => 'oklch(0.840 0 31.068)',
        400 => 'oklch(0.754 0 31.068)',
        500 => 'oklch(0.683 0 31.068)',
        600 => 'oklch(0.598 0 31.068)',
        700 => 'oklch(0.515 0 31.068)',
        800 => 'oklch(0.370 0 31.068)',
        900 => 'oklch(0.280 0 31.068)',
        950 => 'oklch(0.210 0 31.068)',
    ];

    /**
     * Info — NU Dark Teal (#007FA4), matching NU Laravel UI.
     *
     * @var array<int<50, 950>, non-empty-string>
     */
    public const INFO_PALETTE = [
        50 => 'oklch(0.9800 0.0086 227.119)',
        100 => 'oklch(0.9093 0.0252 227.119)',
        200 => 'oklch(0.8387 0.0418 227.119)',
        300 => 'oklch(0.7680 0.0583 227.119)',
        400 => 'oklch(0.6973 0.0749 227.119)',
        500 => 'oklch(0.6267 0.0914 227.119)',
        600 => 'oklch(0.5560 0.1080 227.119)',
        700 => 'oklch(0.4948 0.0983 227.119)',
        800 => 'oklch(0.4336 0.0886 227.119)',
        900 => 'oklch(0.3724 0.0788 227.119)',
        950 => 'oklch(0.3112 0.0691 227.119)',
    ];
}
