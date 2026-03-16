<?php

declare(strict_types=1);

use Symfony\Component\Finder\Finder;

it('only targets fi-* selectors that exist in Filament source', function () {
    $cssDir = __DIR__ . '/../../resources/css';
    $filamentDir = __DIR__ . '/../../vendor/filament';

    $themeSelectors = [];
    foreach (glob($cssDir . '/*.css') as $file) {
        preg_match_all('/\bfi-[a-z][a-z0-9-]*/', file_get_contents($file), $matches);
        $themeSelectors = array_merge($themeSelectors, $matches[0]);
    }
    $themeSelectors = array_unique($themeSelectors);
    sort($themeSelectors);

    expect($themeSelectors)->not->toBeEmpty();

    $filamentSource = '';
    $finder = Finder::create()
        ->files()
        ->in($filamentDir)
        ->name(['*.php', '*.blade.php', '*.js', '*.css'])
        ->notPath('vendor');

    foreach ($finder as $file) {
        $filamentSource .= $file->getContents() . "\n";
    }

    $missing = [];
    foreach ($themeSelectors as $selector) {
        if (! str_contains($filamentSource, $selector)) {
            $missing[] = $selector;
        }
    }

    expect($missing)->toBeEmpty(
        "These fi-* selectors are used by the theme but not found in Filament source:\n  - "
        . implode("\n  - ", $missing)
        . "\n\nFilament may have renamed or removed these classes. Update the theme CSS to match."
    );
});
