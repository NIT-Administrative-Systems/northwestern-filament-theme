{{--
    <x-northwestern-filament-theme::footer />: the footer outside a Filament panel, for example on an error page.
    With no props it renders from config/northwestern-filament-theme.php.

    The props are deprecated since 4.1 and will be removed in a future major. They still take precedence
    over the config. Set unit.* and footer.links in config/northwestern-filament-theme.php instead.
--}}
@props([
    "officeName" => null,
    "officeAddr" => null,
    "officeCity" => null,
    "officePhone" => null,
    "officeEmail" => null,
    "officeFax" => null,
    "links" => [],
])

@include("northwestern-filament-theme::footer", [
    "config" => new \Northwestern\FilamentTheme\Footer\FooterConfig(
        officeName: $officeName,
        officeAddr: $officeAddr,
        officeCity: $officeCity,
        officePhone: $officePhone,
        officeEmail: $officeEmail,
        officeFax: $officeFax,
        links: $links),
])
