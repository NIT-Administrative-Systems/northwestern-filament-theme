{{--
    <x-northwestern-filament-theme::lockup />: the configured unit lockup for a purple header outside a
    Filament panel, such as a public page's header. It shows the white lockup from NU_LOCKUP, or the white
    Department Templates 4.0 wordmark when no lockup is configured, at the panels' logo height.

    It reads only config, so it renders on error pages too. Props:
    - href: wrap it in a link, for example to https://www.northwestern.edu/ or the app's home page.
    - height: the logo's height (default 1.5rem, the panels' brand logo height).
    - label: the image's accessible name. Defaults to "Northwestern University", followed by the unit's
      name when a lockup is configured.

    It keeps clear space of half its height above, below and after it, where no text or graphics may
    sit, and it never shrinks below its height in a flex row.
--}}
@props([
    "href" => null,
    "height" => "1.5rem",
    "label" => null,
])

@php
    $lockupUrl = \Northwestern\FilamentTheme\NorthwesternTheme::lockupUrl();
    $unitName = \Northwestern\FilamentTheme\ConfigResolver::unit()["name"];
    $label ??=
        $lockupUrl !== null && $unitName !== null ? "Northwestern University {$unitName}" : "Northwestern University";
    $logo = new \Illuminate\Support\HtmlString(
        $lockupUrl !== null
            ? '<img src="' . e($lockupUrl) . '" alt="' . e($label) . '" />'
            : view("northwestern-filament-theme::wordmark", ["label" => $label])->render(),
    );
    $attributes = $attributes->class("nu-lockup")->style(["--nu-lockup-height: {$height}"]);
@endphp

<style>
    .nu-lockup {
        --nu-lockup-clear-space: calc(var(--nu-lockup-height) / 2);
        display: inline-flex;
        flex-shrink: 0;
        align-items: center;
        margin-block: var(--nu-lockup-clear-space);
        margin-inline-end: var(--nu-lockup-clear-space);
        line-height: 0;
        color: #fff;
    }

    .nu-lockup img,
    .nu-lockup .nu-wordmark {
        display: block;
        width: auto;
        max-width: none;
        height: var(--nu-lockup-height);
    }

    a.nu-lockup:focus-visible {
        outline: var(--nu-focus-outline-on-purple, 3px solid #fff);
        outline-offset: var(--nu-focus-outline-offset-on-purple, 2px);
    }
</style>

@if ($href)
    <a {{ $attributes->merge(["href" => $href]) }}>{{ $logo }}</a>
@else
    <span {{ $attributes }}>{{ $logo }}</span>
@endif
