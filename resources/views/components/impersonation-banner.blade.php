{{--
    <x-northwestern-filament-theme::impersonation-banner />: the impersonation banner outside a Filament panel,
    for example in a public or error page layout. It renders nothing unless someone is impersonating.

    The props match ->impersonationBanner() on the plugin, and each defaults the same way: visibility from
    lab404/laravel-impersonate, the signed-in user's name, and its impersonate.leave route. It reads no
    Filament state, so it works on pages that are not in a panel.
--}}
@props([
    "visible" => null,
    "label" => null,
    "leaveUrl" => null,
    "leaveLabel" => null,
    "leaveMethod" => "POST",
])

@php
    $config = new \Northwestern\FilamentTheme\ImpersonationBanner\ImpersonationBannerConfig(
        visible: $visible,
        label: $label,
        leaveUrl: $leaveUrl,
        leaveLabel: $leaveLabel,
        leaveMethod: $leaveMethod,
    );
@endphp

@if ($config->isVisible())
    @include("northwestern-filament-theme::impersonation-banner", ["config" => $config])
@endif
