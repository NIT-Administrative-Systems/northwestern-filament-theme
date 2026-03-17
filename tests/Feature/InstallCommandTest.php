<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use Filament\FilamentManager;
use Filament\Panel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

beforeEach(function () {
    $this->themeCssDir = resource_path('css/filament/admin');
    $this->themeCssPath = $this->themeCssDir . '/theme.css';
    $this->publishedDir = public_path('css/northwestern-sysdev/northwestern-filament-theme');

    app()->scoped('filament', fn () => new FilamentManager());

    if (File::isDirectory($this->themeCssDir)) {
        File::deleteDirectory($this->themeCssDir);
    }
    if (File::isDirectory($this->publishedDir)) {
        File::deleteDirectory($this->publishedDir);
    }
});

afterEach(function () {
    if (File::isDirectory($this->themeCssDir)) {
        File::deleteDirectory($this->themeCssDir);
    }
    if (File::isDirectory($this->publishedDir)) {
        File::deleteDirectory($this->publishedDir);
    }
});

function mockSinglePanel(?string $viteTheme = null): void
{
    $panel = Mockery::mock(Panel::class);
    $panel->shouldReceive('getId')->andReturn('admin');
    $panel->shouldReceive('getViteTheme')->andReturn($viteTheme);

    Filament::shouldReceive('getPanels')->andReturn(['admin' => $panel]);
}

it('prompts for panel selection when multiple panels exist', function () {
    $admin = Mockery::mock(Panel::class);
    $admin->shouldReceive('getId')->andReturn('admin');
    $admin->shouldReceive('getViteTheme')->andReturn(null);

    $portal = Mockery::mock(Panel::class);
    $portal->shouldReceive('getId')->andReturn('portal');
    $portal->shouldReceive('getViteTheme')->andReturn(null);

    Filament::shouldReceive('getPanels')->andReturn(['admin' => $admin, 'portal' => $portal]);

    File::ensureDirectoryExists(resource_path('css/filament/portal'));
    File::put(resource_path('css/filament/portal/theme.css'), implode("\n", [
        "@import '/tailwindcss';",
        "@import '../../../../vendor/filament/filament/resources/css/theme.css';",
    ]));

    $this->artisan('northwestern-theme:install')
        ->expectsChoice('Which panel should receive the theme?', 'portal', ['admin' => 'admin', 'portal' => 'portal'])
        ->expectsConfirmation('Include Tailwind v4 design tokens?', 'no')
        ->expectsOutputToContain('Setup complete')
        ->assertSuccessful();

    $themeCssContents = File::get(resource_path('css/filament/portal/theme.css'));

    expect($themeCssContents)
        ->toContain("@import '../../../../vendor/northwestern-sysdev/northwestern-filament-theme/dist/theme.css';");

    File::deleteDirectory(resource_path('css/filament/portal'));
});

it('exits when no panels are registered', function () {
    Filament::shouldReceive('getPanels')->andReturn([]);

    $this->artisan('northwestern-theme:install')
        ->expectsOutputToContain('No Filament panels found')
        ->assertSuccessful();
});

it('shows error when theme file cannot be created', function () {
    mockSinglePanel();

    Log::shouldReceive('debug')
        ->once()
        ->withArgs(function (string $message, array $context) {
            return $message === 'MakeThemeCommand did not succeed'
                && $context['panel_id'] === 'admin'
                && isset($context['exception'], $context['exception_class']);
        });

    // Replace MakeThemeCommand with a stub that throws
    $this->app->instance(
        Filament\Commands\MakeThemeCommand::class,
        new class extends Illuminate\Console\Command
        {
            protected $signature = 'make:filament-theme {panel?} {--F|force}';

            public function handle(): int
            {
                throw new RuntimeException('Simulated failure');
            }
        },
    );

    $this->artisan('northwestern-theme:install')
        ->expectsOutputToContain('Could not find or create the theme file')
        ->assertSuccessful();

    expect(File::exists($this->themeCssPath))->toBeFalse();
});

it('injects theme import after the filament base import', function () {
    mockSinglePanel();

    File::ensureDirectoryExists($this->themeCssDir);
    File::put($this->themeCssPath, implode("\n", [
        "@import '/tailwindcss';",
        "@import '../../../../vendor/filament/filament/resources/css/theme.css';",
    ]));

    $this->artisan('northwestern-theme:install')
        ->expectsConfirmation('Include Tailwind v4 design tokens?', 'no')
        ->expectsOutputToContain('Setup complete')
        ->assertSuccessful();

    $themeCssContents = File::get($this->themeCssPath);

    expect($themeCssContents)
        ->toContain("@import '../../../../vendor/northwestern-sysdev/northwestern-filament-theme/dist/theme.css';")
        ->not->toContain('tailwind-tokens.css')
        ->and(strpos(
            $themeCssContents,
            'vendor/filament/filament/resources/css/theme.css'
        ))->toBeLessThan(strpos(
            $themeCssContents,
            'northwestern-filament-theme/dist/theme.css'
        ));
});

it('injects tokens import when confirmed', function () {
    mockSinglePanel();

    File::ensureDirectoryExists($this->themeCssDir);
    File::put($this->themeCssPath, implode("\n", [
        "@import '/tailwindcss';",
        "@import '../../../../vendor/filament/filament/resources/css/theme.css';",
    ]));

    $this->artisan('northwestern-theme:install')
        ->expectsConfirmation('Include Tailwind v4 design tokens?', 'yes')
        ->expectsOutputToContain('Setup complete')
        ->assertSuccessful();

    $themeCssContents = File::get($this->themeCssPath);

    expect($themeCssContents)
        ->toContain("@import '../../../../vendor/northwestern-sysdev/northwestern-filament-theme/dist/theme.css';")
        ->toContain("@import '../../../../vendor/northwestern-sysdev/northwestern-filament-theme/dist/tailwind-tokens.css';");
});

it('skips theme injection when already installed but still offers tokens', function () {
    mockSinglePanel();

    File::ensureDirectoryExists($this->themeCssDir);
    File::put($this->themeCssPath, implode("\n", [
        "@import '/tailwindcss';",
        "@import '../../../../vendor/filament/filament/resources/css/theme.css';",
        "@import '../../../../vendor/northwestern-sysdev/northwestern-filament-theme/dist/theme.css';",
    ]));

    $this->artisan('northwestern-theme:install')
        ->expectsOutputToContain('ALREADY INSTALLED')
        ->expectsConfirmation('Include Tailwind v4 design tokens?', 'yes')
        ->expectsOutputToContain('Setup complete')
        ->assertSuccessful();

    $themeCssContents = File::get($this->themeCssPath);

    expect($themeCssContents)
        ->toContain("@import '../../../../vendor/northwestern-sysdev/northwestern-filament-theme/dist/tailwind-tokens.css';");
});

it('reports fully configured when theme and tokens are both installed', function () {
    mockSinglePanel();

    File::ensureDirectoryExists($this->themeCssDir);
    File::put($this->themeCssPath, implode("\n", [
        "@import '/tailwindcss';",
        "@import '../../../../vendor/filament/filament/resources/css/theme.css';",
        "@import '../../../../vendor/northwestern-sysdev/northwestern-filament-theme/dist/theme.css';",
        "@import '../../../../vendor/northwestern-sysdev/northwestern-filament-theme/dist/tailwind-tokens.css';",
    ]));

    $this->artisan('northwestern-theme:install')
        ->expectsOutputToContain('Nothing to do')
        ->assertSuccessful();
});

it('appends import when filament base import is not found', function () {
    mockSinglePanel();

    File::ensureDirectoryExists($this->themeCssDir);
    File::put($this->themeCssPath, "@import '/tailwindcss';\n");

    $this->artisan('northwestern-theme:install')
        ->expectsConfirmation('Include Tailwind v4 design tokens?', 'no')
        ->expectsOutputToContain('Could not find the Filament base theme import')
        ->assertSuccessful();

    $themeCssContents = File::get($this->themeCssPath);

    expect($themeCssContents)
        ->toContain("@import '../../../../vendor/northwestern-sysdev/northwestern-filament-theme/dist/theme.css';");
});

it('handles the index.css variant of the filament import', function () {
    mockSinglePanel();

    File::ensureDirectoryExists($this->themeCssDir);
    File::put($this->themeCssPath, implode("\n", [
        "@import '/tailwindcss';",
        "@import '../../../../vendor/filament/filament/resources/css/index.css';",
    ]));

    $this->artisan('northwestern-theme:install')
        ->expectsConfirmation('Include Tailwind v4 design tokens?', 'no')
        ->expectsOutputToContain('Setup complete')
        ->assertSuccessful();

    $themeCssContents = File::get($this->themeCssPath);

    expect($themeCssContents)
        ->toContain("@import '../../../../vendor/northwestern-sysdev/northwestern-filament-theme/dist/theme.css';");
});

it('does not duplicate tokens import if already present', function () {
    mockSinglePanel();

    $tokensImport = "@import '../../../../vendor/northwestern-sysdev/northwestern-filament-theme/dist/tailwind-tokens.css';";

    File::ensureDirectoryExists($this->themeCssDir);
    File::put($this->themeCssPath, implode("\n", [
        "@import '/tailwindcss';",
        "@import '../../../../vendor/filament/filament/resources/css/theme.css';",
        "@import '../../../../vendor/northwestern-sysdev/northwestern-filament-theme/dist/theme.css';",
        $tokensImport,
    ]));

    $this->artisan('northwestern-theme:install')
        ->expectsOutputToContain('Nothing to do')
        ->assertSuccessful();

    $themeCssContents = File::get($this->themeCssPath);

    expect(substr_count($themeCssContents, $tokensImport))->toBe(1);
});

it('removes previously published CSS assets', function () {
    mockSinglePanel();

    File::ensureDirectoryExists($this->themeCssDir);
    File::put($this->themeCssPath, implode("\n", [
        "@import '/tailwindcss';",
        "@import '../../../../vendor/filament/filament/resources/css/theme.css';",
    ]));

    File::ensureDirectoryExists($this->publishedDir);
    File::put($this->publishedDir . '/nu-theme.css', '/* stale */');

    $this->artisan('northwestern-theme:install')
        ->expectsConfirmation('Include Tailwind v4 design tokens?', 'no')
        ->expectsOutputToContain('Removing previously published CSS assets')
        ->assertSuccessful();

    expect(File::isDirectory($this->publishedDir))->toBeFalse();
});

it('does not warn about published assets when none exist', function () {
    mockSinglePanel();

    File::ensureDirectoryExists($this->themeCssDir);
    File::put($this->themeCssPath, implode("\n", [
        "@import '/tailwindcss';",
        "@import '../../../../vendor/filament/filament/resources/css/theme.css';",
    ]));

    $this->artisan('northwestern-theme:install')
        ->expectsConfirmation('Include Tailwind v4 design tokens?', 'no')
        ->doesntExpectOutputToContain('Removing previously published CSS assets')
        ->assertSuccessful();
});

it('detects theme file from panel viteTheme configuration', function () {
    $customThemeDir = resource_path('sass/filament/administration');
    $customThemePath = $customThemeDir . '/theme.css';

    mockSinglePanel('resources/sass/filament/administration/theme.css');

    File::ensureDirectoryExists($customThemeDir);
    File::put($customThemePath, implode("\n", [
        "@import 'tailwindcss';",
        "@import '../../../../vendor/filament/filament/resources/css/index.css';",
    ]));

    $this->artisan('northwestern-theme:install')
        ->expectsConfirmation('Include Tailwind v4 design tokens?', 'no')
        ->expectsOutputToContain('Setup complete')
        ->assertSuccessful();

    $themeCssContents = File::get($customThemePath);

    expect($themeCssContents)
        ->toContain("@import '../../../../vendor/northwestern-sysdev/northwestern-filament-theme/dist/theme.css';")
        ->and(strpos(
            $themeCssContents,
            'vendor/filament/filament/resources/css/index.css'
        ))->toBeLessThan(strpos(
            $themeCssContents,
            'northwestern-filament-theme/dist/theme.css'
        ))
        ->and(File::exists(resource_path('css/filament/admin/theme.css')))->toBeFalse();

    File::deleteDirectory(resource_path('sass'));
});
