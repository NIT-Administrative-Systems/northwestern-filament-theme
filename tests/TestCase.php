<?php

declare(strict_types=1);

namespace Northwestern\FilamentTheme\Tests;

use Northwestern\FilamentTheme\NorthwesternThemeServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            NorthwesternThemeServiceProvider::class,
        ];
    }
}
