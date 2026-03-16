<?php

declare(strict_types=1);

namespace Northwestern\FilamentTheme\Console;

use Filament\Commands\MakeThemeCommand;
use Filament\Facades\Filament;
use Filament\Panel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\note;
use function Laravel\Prompts\select;
use function Laravel\Prompts\warning;

/**
 * Sets up Vite theme integration for a Filament panel.
 *
 * - Creates a panel theme CSS file if one doesn't exist
 * - Injects @import for the Northwestern theme
 * - Optionally injects @import for Tailwind v4 design tokens
 * - Cleans up previously published CSS assets
 */
class InstallCommand extends Command
{
    /** @var string */
    protected $signature = 'northwestern-theme:install';

    /** @var string */
    protected $description = 'Set up Vite theme integration for a Filament panel';

    protected string $themeImport = "@import '../../../../vendor/northwestern-sysdev/northwestern-filament-theme/dist/theme.css';";

    protected string $tokensImport = "@import '../../../../vendor/northwestern-sysdev/northwestern-filament-theme/dist/tailwind-tokens.css';";

    public function handle(): void
    {
        $this->newLine();
        $this->line('  <bg=magenta;fg=bright-white;options=bold> Northwestern Filament Theme </>');
        $this->newLine();

        $panels = Filament::getPanels();

        if ($panels === []) {
            $this->components->error('No Filament panels found');
            note('Create a panel first, then run this command again.');

            return;
        }

        $panelId = count($panels) > 1
            ? select(
                label: 'Which panel should receive the theme?',
                options: array_map(
                    fn (Panel $panel): string => $panel->getId(),
                    $panels,
                ),
            )
            : array_key_first($panels);

        $themeCssPath = resource_path("css/filament/{$panelId}/theme.css");

        if (! File::exists($themeCssPath)) {
            $this->components->task("Creating theme stylesheet for [{$panelId}] panel", function () use ($panelId) {
                try {
                    $this->callSilently(MakeThemeCommand::class, ['panel' => $panelId]);
                } catch (\Throwable) {
                    // MakeThemeCommand may fail; the file-exists check below handles it.
                }

                return true;
            });
        }

        if (! File::exists($themeCssPath)) {
            $this->components->error('Could not find or create the theme file');
            $this->components->bulletList(["Expected: {$themeCssPath}"]);

            return;
        }

        $themeCss = File::get($themeCssPath);
        $isThemeAlreadyInstalled = str_contains($themeCss, $this->themeImport);

        if ($isThemeAlreadyInstalled) {
            $this->components->twoColumnDetail('Theme CSS', '<fg=blue;options=bold>ALREADY INSTALLED</>');
        } else {
            $this->components->task('Injecting theme CSS import', function () use ($themeCssPath, $themeCss) {
                $this->injectThemeImport($themeCssPath, $themeCss);

                return true;
            });
        }

        $isTokensAlreadyInstalled = str_contains(File::get($themeCssPath), $this->tokensImport);
        $shouldIncludeTokens = false;

        if ($isTokensAlreadyInstalled) {
            $this->components->twoColumnDetail('Design tokens', '<fg=blue;options=bold>ALREADY INSTALLED</>');
        } else {
            $shouldIncludeTokens = confirm(
                label: 'Include Tailwind v4 design tokens?',
                hint: 'Enables utilities like bg-nu-purple-100, text-nu-gold, etc.',
            );

            if ($shouldIncludeTokens) {
                $this->components->task('Injecting Tailwind v4 design tokens', function () use ($themeCssPath) {
                    $this->injectTokensImport($themeCssPath);

                    return true;
                });
            }
        }

        $this->cleanPublishedAssets();
        $this->newLine();

        if ($isThemeAlreadyInstalled && ($isTokensAlreadyInstalled || ! $shouldIncludeTokens)) {
            $this->components->info('Nothing to do, theme is already configured');

            return;
        }

        if (! $isThemeAlreadyInstalled) {
            $this->components->twoColumnDetail('Theme CSS', '<fg=green;options=bold>INJECTED</>');
        }

        if ($shouldIncludeTokens) {
            $this->components->twoColumnDetail('Design tokens', '<fg=green;options=bold>INJECTED</>');
        }

        $this->components->twoColumnDetail('Panel', '<fg=magenta>' . $panelId . '</>');
        $this->components->twoColumnDetail('Theme file', str_replace(base_path() . '/', '', $themeCssPath));

        $this->newLine();
        $this->line('  <options=bold>Next steps:</>');
        $this->newLine();
        $this->line('  <fg=yellow>1.</> Update the plugin in your <fg=magenta>' . $panelId . '</> panel provider:');
        $this->newLine();
        $this->line('     <fg=gray>  NorthwesternTheme::make()</>');
        $this->line('     <fg=green>+     ->withoutAssetRegistration()</>');
        $this->newLine();
        $this->line('  <fg=yellow>2.</> Compile your assets:');
        $this->newLine();
        $this->line('     <fg=green>npm run build</>');

        $this->newLine();
        $this->components->success('Setup complete');
    }

    protected function injectThemeImport(string $themeCssPath, string $themeCss): void
    {
        if (preg_match('/(@import\s*[\'"].*?vendor\/filament\/filament\/resources\/css\/(?:theme|index)\.css[\'"];)/', $themeCss, $matches)) {
            File::replaceInFile(
                $matches[1],
                $matches[1] . "\n" . $this->themeImport,
                $themeCssPath,
            );

            return;
        }

        File::append($themeCssPath, "\n" . $this->themeImport . "\n");

        warning('Could not find the Filament base theme import. Appended to end of file.');
        note("Please review: {$themeCssPath}\nEnsure the Northwestern import comes after the Filament base import.");
    }

    protected function injectTokensImport(string $themeCssPath): void
    {
        $themeCssContents = File::get($themeCssPath);

        if (str_contains($themeCssContents, $this->tokensImport)) {
            return;
        }

        File::replaceInFile(
            $this->themeImport,
            $this->themeImport . "\n" . $this->tokensImport,
            $themeCssPath,
        );
    }

    protected function cleanPublishedAssets(): void
    {
        $publishedDir = public_path('css/northwestern-sysdev/northwestern-filament-theme');

        if (! File::isDirectory($publishedDir)) {
            return;
        }

        $this->components->task('Removing previously published CSS assets', function () use ($publishedDir) {
            File::deleteDirectory($publishedDir);

            $parentDir = public_path('css/northwestern-sysdev');
            if (File::isDirectory($parentDir) && File::allFiles($parentDir) === [] && File::directories($parentDir) === []) {
                File::deleteDirectory($parentDir);
            }

            return true;
        });
    }
}
