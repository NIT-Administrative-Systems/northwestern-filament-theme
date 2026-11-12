{{--
    An application's own top-bar item, as the starter's Help menu is. The
    theme's nu-topbar-icon-below-xl class shows only its icon below xl.
--}}
<x-filament::dropdown data-testid="help-menu"
                      placement="bottom-end"
                      teleport>
    <x-slot name="trigger">
        <button class="nu-topbar-icon-below-xl flex h-9 items-center gap-1.5 rounded-lg px-2 text-sm font-medium text-white hover:bg-white/10 sm:px-3"
                data-testid="help-menu-trigger"
                type="button"
                aria-label="Help">
            <x-filament::icon class="size-5" icon="heroicon-o-question-mark-circle" />
            <span class="hidden sm:inline">Help</span>
            <x-filament::icon class="hidden size-4 sm:block" icon="heroicon-m-chevron-down" />
        </button>
    </x-slot>

    <x-filament::dropdown.list>
        <x-filament::dropdown.list.item href="https://www.northwestern.edu/"
                                        tag="a"
                                        icon="heroicon-o-book-open">
            Documentation
        </x-filament::dropdown.list.item>
    </x-filament::dropdown.list>
</x-filament::dropdown>
