<?php

declare(strict_types=1);

/*
 * Branding content for the Northwestern Filament theme. It describes the unit
 * that runs the application; per-panel behavior, such as turning the footer
 * off, stays on the NorthwesternTheme plugin.
 *
 * A null value falls back to the legacy config/northwestern-theme.php from
 * northwestern-sysdev/northwestern-laravel-ui, then to the built-in default.
 * An empty string hides the field.
 */
return [
    // A unit lockup for the panel's brand logo: a full URL or a path in public/.
    // null falls back to the legacy northwestern-theme.lockup key, then to the
    // Department Templates 4.0 "Northwestern" wordmark.
    'lockup' => env('NU_LOCKUP'),

    // The unit responsible for the application. The Web Style Guide requires its
    // address, phone, fax (if any) and email in the footer. A null field falls back
    // to the legacy northwestern-theme.office.* key, then to the built-in default
    // (Information Technology, with no fax).
    'unit' => [
        'name' => env('NU_UNIT_NAME'),
        'address' => env('NU_UNIT_ADDRESS'),
        'city' => env('NU_UNIT_CITY'),
        'phone' => env('NU_UNIT_PHONE'),
        'fax' => env('NU_UNIT_FAX'),
        'email' => env('NU_UNIT_EMAIL'),
    ],

    'footer' => [
        // Shown under "Quick Links", as label => URL. The links the Web Style
        // Guide requires always render and can't be removed.
        'links' => [],

        // The "Connect" accounts, as network => URL, in display order. [] hides
        // the section. Networks: bluesky, facebook, flickr, instagram, linkedin,
        // pinterest, rss, spotify, threads, tiktok, tumblr, vimeo, wordpress, x
        // and youtube.
        'social' => [
            'facebook' => 'https://www.facebook.com/NorthwesternU',
            'instagram' => 'https://instagram.com/northwesternu',
            'youtube' => 'https://www.youtube.com/user/NorthwesternU',
        ],
    ],
];
