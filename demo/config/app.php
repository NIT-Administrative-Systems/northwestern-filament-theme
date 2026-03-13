<?php

declare(strict_types=1);

return [
    'name' => 'Theme Demo',
    'env' => env('APP_ENV', 'local'),
    'debug' => env('APP_DEBUG', true),
    'url' => env('APP_URL', 'http://localhost:8000'),
    'timezone' => 'America/Chicago',
    'locale' => 'en',
    'key' => env('APP_KEY'),
    'maintenance' => ['driver' => 'file'],
    'providers' => Illuminate\Support\ServiceProvider::defaultProviders()->merge([
        App\Providers\Filament\DemoPanelProvider::class,
    ])->toArray(),
];
