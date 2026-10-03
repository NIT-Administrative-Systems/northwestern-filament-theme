<?php

declare(strict_types=1);

use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Northwestern\FilamentTheme\Footer\FooterConfig;
use Northwestern\FilamentTheme\Footer\RequiredLink;
use Northwestern\FilamentTheme\Footer\SocialNetwork;
use Northwestern\FilamentTheme\NorthwesternTheme;

it('registers the footer after the layout on full pages and inside the simple layout', function () {
    bootPluginOnPanel(NorthwesternTheme::make(), 'test-footer-default');

    expect(registeredRenderHooks())
        ->toHaveKey(PanelsRenderHook::BODY_END)
        ->toHaveKey(PanelsRenderHook::SIMPLE_LAYOUT_END)
        ->not->toHaveKey(PanelsRenderHook::FOOTER);
});

it('renders the footer once on full pages', function () {
    bootPluginOnPanel(NorthwesternTheme::make(), 'test-footer-full');

    expect(FilamentView::renderHook(PanelsRenderHook::BODY_END)->toHtml())
        ->toContain('<footer class="nu-footer">');
});

it('renders the footer once on simple pages', function () {
    bootPluginOnPanel(NorthwesternTheme::make(), 'test-footer-simple');

    // The simple layout renders before the body around it.
    expect(FilamentView::renderHook(PanelsRenderHook::SIMPLE_LAYOUT_END)->toHtml())
        ->toContain('<footer class="nu-footer">')
        ->and(FilamentView::renderHook(PanelsRenderHook::BODY_END)->toHtml())
        ->toBe('');
});

it('does not register the footer when disabled', function () {
    bootPluginOnPanel(NorthwesternTheme::make()->footer(enabled: false), 'test-footer-disabled');

    expect(registeredRenderHooks())
        ->not->toHaveKey(PanelsRenderHook::BODY_END)
        ->not->toHaveKey(PanelsRenderHook::SIMPLE_LAYOUT_END);
});

it('evaluates a closure for the enabled state at render time', function () {
    $enabled = false;

    bootPluginOnPanel(NorthwesternTheme::make()->footer(enabled: function () use (&$enabled) {
        return $enabled;
    }), 'test-footer-closure');

    expect(FilamentView::renderHook(PanelsRenderHook::BODY_END)->toHtml())->toBe('');

    $enabled = true;

    expect(FilamentView::renderHook(PanelsRenderHook::BODY_END)->toHtml())->toContain('<footer class="nu-footer">');
});

it('can be explicitly disabled', function () {
    $config = new FooterConfig(enabled: false);

    expect($config->isEnabled())->toBeFalse();
});

it('resolves a closure for the enabled state', function () {
    $config = new FooterConfig(enabled: fn () => true);
    expect($config->isEnabled())->toBeTrue();

    $config = new FooterConfig(enabled: fn () => false);
    expect($config->isEnabled())->toBeFalse();
});

it('stores office information', function () {
    $config = new FooterConfig(
        officeName: 'Test Office',
        officeAddr: '123 Main St',
        officeCity: 'Evanston, IL 60201',
        officePhone: '847-555-0000',
        officeEmail: 'test@northwestern.edu',
        officeFax: '847-555-0001',
    );

    expect($config->office())->toBe([
        'name' => 'Test Office',
        'addr' => '123 Main St',
        'city' => 'Evanston, IL 60201',
        'phone' => '847-555-0000',
        'fax' => '847-555-0001',
        'email' => 'test@northwestern.edu',
    ]);
});

it('falls back to config and then to the default office', function () {
    config()->set('northwestern-theme.office', [
        'name' => 'Config Office',
        'fax' => '847-555-0002',
        'phone' => '',
    ]);

    $office = (new FooterConfig(officeEmail: 'plugin@northwestern.edu'))->office();

    expect($office)->toBe([
        'name' => 'Config Office',
        'addr' => FooterConfig::DEFAULT_OFFICE['addr'],
        'city' => FooterConfig::DEFAULT_OFFICE['city'],
        'phone' => null,
        'fax' => '847-555-0002',
        'email' => 'plugin@northwestern.edu',
    ]);
});

it('has no fax by default', function () {
    expect((new FooterConfig())->office()['fax'])->toBeNull();
});

it('passes office overrides and links through the fluent api', function () {
    $plugin = NorthwesternTheme::make()->footer(
        officeName: 'My Office',
        officeEmail: 'me@northwestern.edu',
        officeFax: '847-555-1111',
        links: ['Help' => 'https://example.com/help'],
    );

    $config = (new ReflectionProperty($plugin, 'footerConfig'))->getValue($plugin);

    expect($config->officeName)->toBe('My Office')
        ->and($config->officeEmail)->toBe('me@northwestern.edu')
        ->and($config->officeFax)->toBe('847-555-1111')
        ->and($config->officeAddr)->toBeNull()
        ->and($config->links)->toBe(['Help' => 'https://example.com/help']);
});

it('returns the plugin instance for chaining', function () {
    $plugin = NorthwesternTheme::make();

    expect($plugin->footer())->toBe($plugin);
});

it('defines the nine required links once each', function () {
    expect(RequiredLink::cases())->toHaveCount(9)
        ->and([...RequiredLink::RESOURCES, ...RequiredLink::LEGAL])->toEqualCanonicalizing(RequiredLink::cases());

    foreach (RequiredLink::cases() as $link) {
        expect($link->url())->toStartWith('https://')
            ->and($link->url())->not->toEndWith('index.html')
            ->and($link->label())->not->toBeEmpty();
    }

    expect(RequiredLink::Accessibility->url())->toBe('https://www.northwestern.edu/accessibility/report/')
        ->and(RequiredLink::PrivacyStatement->url())->toBe('https://www.northwestern.edu/privacy/');
});

it('renders every required link', function () {
    $html = view('northwestern-filament-theme::footer', ['config' => new FooterConfig()])->render();

    foreach (RequiredLink::cases() as $link) {
        expect($html)->toContain('<a href="' . $link->url() . '">' . e($link->label()) . '</a>');
    }

    expect($html)
        ->toContain('Northwestern Resources')
        ->toContain('&copy; ' . date('Y') . ' Northwestern University')
        ->toContain('<a class="nu-footer-wordmark" href="https://www.northwestern.edu/">');
});

it('renders app links in addition to the required links', function () {
    $html = view('northwestern-filament-theme::footer', ['config' => new FooterConfig(
        links: ['Help & Support' => 'https://example.com/help'],
    )])->render();

    expect($html)
        ->toContain('Quick Links')
        ->toContain('<a href="https://example.com/help">Help &amp; Support</a>')
        ->toContain(RequiredLink::ReportAConcern->url());
});

it('omits the quick links section when the app adds none', function () {
    $html = view('northwestern-filament-theme::footer', ['config' => new FooterConfig()])->render();

    expect($html)->not->toContain('Quick Links');
});

it('renders office details including an optional fax', function () {
    $html = view('northwestern-filament-theme::footer', ['config' => new FooterConfig(
        officeName: 'Test Office',
        officeEmail: 'test@northwestern.edu',
        officeFax: '847-555-0001',
    )])->render();

    expect($html)
        ->toContain('<h2 class="nu-footer-unit">Test Office</h2>')
        ->toContain('Fax number')
        ->toContain('847-555-0001')
        ->toContain('<a href="mailto:test@northwestern.edu">test@northwestern.edu</a>');
});

it('hides the fax row when there is no fax', function () {
    $html = view('northwestern-filament-theme::footer', ['config' => new FooterConfig()])->render();

    expect($html)->not->toContain('Fax number');
});

it('uses inline SVG instead of remote images', function () {
    $html = view('northwestern-filament-theme::footer', ['config' => new FooterConfig()])->render();

    expect($html)
        ->toContain('<svg class="nu-wordmark nu-wordmark-university"')
        ->not->toContain('<img')
        ->not->toContain('common.northwestern.edu')
        ->not->toContain('/v8/');
});

it('inlines the token-based footer stylesheet', function () {
    $html = view('northwestern-filament-theme::footer', ['config' => new FooterConfig()])->render();

    expect($html)
        ->toContain('<style>')
        ->toContain('.nu-footer {')
        ->toContain('var(--nu-purple-120)');
});

it('renders as a Blade component outside Filament', function () {
    $html = Blade::render(
        '<x-northwestern-filament-theme::footer office-name="Error Page Office" office-fax="847-555-0003" :links="$links" />',
        ['links' => ['Status' => 'https://status.example.com']],
    );

    expect($html)
        ->toContain('<footer class="nu-footer">')
        ->toContain('Error Page Office')
        ->toContain('847-555-0003')
        ->toContain('<a href="https://status.example.com">Status</a>')
        ->toContain(RequiredLink::Accessibility->url());
});

it('keeps the footer markup free of Filament, auth and database calls', function () {
    $sources = file_get_contents(__DIR__ . '/../../resources/views/footer.blade.php')
        . file_get_contents(__DIR__ . '/../../resources/views/components/footer.blade.php')
        . file_get_contents(__DIR__ . '/../../resources/views/wordmark.blade.php');

    expect($sources)
        ->not->toContain('filament(')
        ->not->toContain('Filament\\')
        ->not->toContain('auth(')
        ->not->toContain('DB::');
});

it('renders the unit and quick links from the theme config', function () {
    config()->set('northwestern-filament-theme.unit', [
        'name' => 'Feinberg IT',
        'address' => '420 E Superior St',
        'city' => 'Chicago, IL 60611',
        'phone' => '312-503-0000',
        'fax' => '312-503-0001',
        'email' => 'feinberg-it@northwestern.edu',
    ]);
    config()->set('northwestern-filament-theme.footer.links', ['Service Status' => 'https://status.example.com']);

    $html = view('northwestern-filament-theme::footer', ['config' => new FooterConfig()])->render();

    expect($html)
        ->toContain('<h2 class="nu-footer-unit">Feinberg IT</h2>')
        ->toContain('420 E Superior St')
        ->toContain('312-503-0001')
        ->toContain('<a href="mailto:feinberg-it@northwestern.edu">feinberg-it@northwestern.edu</a>')
        ->toContain('<a href="https://status.example.com">Service Status</a>')
        ->not->toContain(FooterConfig::DEFAULT_OFFICE['name']);
});

it('lets deprecated plugin arguments override the theme config', function () {
    config()->set('northwestern-filament-theme.unit.name', 'Config Unit');
    config()->set('northwestern-filament-theme.footer.links', ['Config Link' => '/config']);

    bootPluginOnPanel(NorthwesternTheme::make()->footer(officeName: 'Plugin Office', links: ['Plugin Link' => '/plugin']), 'test-footer-deprecated-args');

    expect(FilamentView::renderHook(PanelsRenderHook::BODY_END)->toHtml())
        ->toContain('<h2 class="nu-footer-unit">Plugin Office</h2>')
        ->toContain('<a href="/plugin">Plugin Link</a>')
        ->not->toContain('Config Unit')
        ->not->toContain('Config Link');
});

it('renders the configured social accounts in order, with icons and accessible names', function () {
    config()->set('northwestern-filament-theme.footer.social', [
        'linkedin' => 'https://www.linkedin.com/company/unit',
        'facebook' => 'https://www.facebook.com/NorthwesternU',
        'bluesky' => 'https://bsky.app/profile/unit',
    ]);

    $html = view('northwestern-filament-theme::footer', ['config' => new FooterConfig()])->render();

    expect($html)
        ->toContain('<span class="nu-sr-only">LinkedIn</span>')
        ->toContain('<span class="nu-sr-only">Northwestern University on Facebook</span>')
        ->toContain('<span class="nu-sr-only">Bluesky</span>')
        ->not->toContain('instagram.com')
        ->and(strpos($html, 'linkedin.com'))->toBeLessThan(strpos($html, 'facebook.com'))
        ->and(substr_count($html, '<svg aria-hidden="true"'))->toBeGreaterThanOrEqual(3);
});

it('has an icon for every social network', function () {
    foreach (SocialNetwork::cases() as $network) {
        $svg = view('northwestern-filament-theme::social-icon', ['network' => $network->value])->render();

        expect(substr_count($svg, '<svg aria-hidden="true"'))->toBe(1, $network->value)
            ->and($svg)->not->toContain('class=');
    }
});

it('hides the connect section when social is empty', function () {
    config()->set('northwestern-filament-theme.footer.social', []);

    $html = view('northwestern-filament-theme::footer', ['config' => new FooterConfig()])->render();

    expect($html)
        ->not->toContain('<h2>Connect</h2>')
        ->not->toContain('<ul class="nu-footer-social">')
        ->toContain(RequiredLink::Accessibility->url());
});

it('renders the component from the theme config with no props and no Filament panel', function () {
    config()->set('northwestern-filament-theme.unit.name', 'Error Page Unit');
    config()->set('northwestern-filament-theme.footer.links', ['Status' => 'https://status.example.com']);

    $html = Blade::render('<x-northwestern-filament-theme::footer />');

    expect(app()->providerIsLoaded(Filament\FilamentServiceProvider::class))->toBeFalse()
        ->and(registeredRenderHooks())->toBe([])
        ->and($html)
        ->toContain('<footer class="nu-footer">')
        ->toContain('<h2 class="nu-footer-unit">Error Page Unit</h2>')
        ->toContain('<a href="https://status.example.com">Status</a>')
        ->toContain('Northwestern University on Instagram');
});

it('lets deprecated component props override the theme config', function () {
    config()->set('northwestern-filament-theme.unit.name', 'Config Unit');

    $html = Blade::render('<x-northwestern-filament-theme::footer office-name="Prop Office" />');

    expect($html)
        ->toContain('<h2 class="nu-footer-unit">Prop Office</h2>')
        ->not->toContain('Config Unit');
});
