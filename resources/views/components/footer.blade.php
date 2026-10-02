{{-- <x-northwestern-filament-theme::footer />: the footer outside a Filament panel, for example on an error page. --}}
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
