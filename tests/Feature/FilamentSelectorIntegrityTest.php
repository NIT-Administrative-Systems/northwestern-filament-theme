<?php

declare(strict_types=1);

use Symfony\Component\Finder\Finder;

it('only targets fi-* selectors that exist in Filament source', function () {
    $distFile = __DIR__ . '/../../dist/theme.css';
    $filamentDir = __DIR__ . '/../../vendor/filament';

    expect($distFile)->toBeFile();

    $themeSelectors = [];
    preg_match_all('/\bfi-[a-z][a-z0-9-]*/', file_get_contents($distFile), $matches);
    $themeSelectors = array_merge($themeSelectors, $matches[0]);
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

    $missingSelectors = [];
    foreach ($themeSelectors as $selector) {
        if (! str_contains($filamentSource, $selector)) {
            $missingSelectors[] = $selector;
        }
    }

    expect($missingSelectors)->toBeEmpty(
        "These fi-* selectors are used by the theme but not found in Filament source:\n  - "
        . implode("\n  - ", $missingSelectors)
        . "\n\nFilament may have renamed or removed these classes. Update the theme CSS to match."
    );
});
