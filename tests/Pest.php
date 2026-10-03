<?php

declare(strict_types=1);

use Filament\Panel;
use Filament\Support\Facades\FilamentView;
use Northwestern\FilamentTheme\NorthwesternTheme;
use Northwestern\FilamentTheme\Tests\TestCase;

uses(TestCase::class)->in('Feature');

function registeredRenderHooks(): array
{
    $view = FilamentView::getFacadeRoot();

    return (new ReflectionProperty($view, 'renderHooks'))->getValue($view);
}

function bootPluginOnPanel(NorthwesternTheme $plugin, string $panelId): Panel
{
    $panel = app(Panel::class)->id($panelId);
    $plugin->register($panel);
    $plugin->boot($panel);

    return $panel;
}
