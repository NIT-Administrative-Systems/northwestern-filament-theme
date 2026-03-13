<x-filament-panels::page>

    {{-- ============================================================
         SECTION: Color Swatches
         ============================================================ --}}
    <x-filament::section>
        <x-slot name="heading">Color Palette</x-slot>
        <x-slot name="description">All registered Filament color slots</x-slot>

        @php
            $c = \Northwestern\FilamentTheme\Colors::class;

            $palette = [
                'primary' => [
                    50 => $c::PURPLE_10,
                    100 => $c::PURPLE_20,
                    200 => $c::PURPLE_30,
                    300 => $c::PURPLE_40,
                    400 => $c::PURPLE_50,
                    500 => $c::PURPLE_60,
                    600 => $c::PURPLE_100,
                    700 => $c::PURPLE_120,
                    800 => $c::PURPLE_140,
                    900 => $c::PURPLE_150,
                    950 => $c::PURPLE_160,
                ],
                'danger' => [
                    600 => $c::ORANGE,
                ],
                'success' => [
                    600 => $c::GREEN,
                ],
                'warning' => [
                    600 => $c::GOLD,
                ],
                'info' => [
                    600 => $c::BLUE,
                ],
                'gray' => [
                    100 => $c::RICH_BLACK_10,
                    200 => $c::RICH_BLACK_20,
                    500 => $c::RICH_BLACK_50,
                    800 => $c::RICH_BLACK_80,
                    950 => $c::RICH_BLACK_100,
                ],
            ];
        @endphp

        <div class="space-y-4">
            @foreach ($palette as $name => $shades)
                <div>
                    <p class="mb-1 text-sm font-medium capitalize">{{ $name }}</p>
                    <div class="flex gap-1">
                        @foreach ($shades as $shade => $hex)
                            <div class="flex flex-col items-center">
                                <div class="h-10 w-10 rounded border border-gray-200 dark:border-gray-700"
                                     style="background-color: {{ $hex }}"></div>
                                <span class="mt-0.5 text-xs text-gray-500">{{ $shade }}</span>
                                <span class="font-mono text-xs text-gray-400">{{ $hex }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Tailwind color utility demo --}}
        <div class="mt-6 space-y-3">
            <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">Tailwind Utility
                Classes (live from Filament color system)</h3>
            {{-- Primary --}}
            <div class="flex items-center gap-1">
                <span class="w-16 shrink-0 font-mono text-xs">primary</span>
                <span class="bg-primary-50 h-8 w-8 rounded border border-gray-200 dark:border-gray-700"></span>
                <span class="bg-primary-100 h-8 w-8 rounded"></span>
                <span class="bg-primary-200 h-8 w-8 rounded"></span>
                <span class="bg-primary-300 h-8 w-8 rounded"></span>
                <span class="bg-primary-400 h-8 w-8 rounded"></span>
                <span class="bg-primary-500 h-8 w-8 rounded"></span>
                <span class="bg-primary-600 h-8 w-8 rounded"></span>
                <span class="bg-primary-700 h-8 w-8 rounded"></span>
                <span class="bg-primary-800 h-8 w-8 rounded"></span>
                <span class="bg-primary-900 h-8 w-8 rounded"></span>
                <span class="bg-primary-950 h-8 w-8 rounded"></span>
            </div>
            {{-- Danger --}}
            <div class="flex items-center gap-1">
                <span class="w-16 shrink-0 font-mono text-xs">danger</span>
                <span class="bg-danger-50 h-8 w-8 rounded border border-gray-200 dark:border-gray-700"></span>
                <span class="bg-danger-100 h-8 w-8 rounded"></span>
                <span class="bg-danger-200 h-8 w-8 rounded"></span>
                <span class="bg-danger-300 h-8 w-8 rounded"></span>
                <span class="bg-danger-400 h-8 w-8 rounded"></span>
                <span class="bg-danger-500 h-8 w-8 rounded"></span>
                <span class="bg-danger-600 h-8 w-8 rounded"></span>
                <span class="bg-danger-700 h-8 w-8 rounded"></span>
                <span class="bg-danger-800 h-8 w-8 rounded"></span>
                <span class="bg-danger-900 h-8 w-8 rounded"></span>
                <span class="bg-danger-950 h-8 w-8 rounded"></span>
            </div>
            {{-- Success --}}
            <div class="flex items-center gap-1">
                <span class="w-16 shrink-0 font-mono text-xs">success</span>
                <span class="bg-success-50 h-8 w-8 rounded border border-gray-200 dark:border-gray-700"></span>
                <span class="bg-success-100 h-8 w-8 rounded"></span>
                <span class="bg-success-200 h-8 w-8 rounded"></span>
                <span class="bg-success-300 h-8 w-8 rounded"></span>
                <span class="bg-success-400 h-8 w-8 rounded"></span>
                <span class="bg-success-500 h-8 w-8 rounded"></span>
                <span class="bg-success-600 h-8 w-8 rounded"></span>
                <span class="bg-success-700 h-8 w-8 rounded"></span>
                <span class="bg-success-800 h-8 w-8 rounded"></span>
                <span class="bg-success-900 h-8 w-8 rounded"></span>
                <span class="bg-success-950 h-8 w-8 rounded"></span>
            </div>
            {{-- Warning --}}
            <div class="flex items-center gap-1">
                <span class="w-16 shrink-0 font-mono text-xs">warning</span>
                <span class="bg-warning-50 h-8 w-8 rounded border border-gray-200 dark:border-gray-700"></span>
                <span class="bg-warning-100 h-8 w-8 rounded"></span>
                <span class="bg-warning-200 h-8 w-8 rounded"></span>
                <span class="bg-warning-300 h-8 w-8 rounded"></span>
                <span class="bg-warning-400 h-8 w-8 rounded"></span>
                <span class="bg-warning-500 h-8 w-8 rounded"></span>
                <span class="bg-warning-600 h-8 w-8 rounded"></span>
                <span class="bg-warning-700 h-8 w-8 rounded"></span>
                <span class="bg-warning-800 h-8 w-8 rounded"></span>
                <span class="bg-warning-900 h-8 w-8 rounded"></span>
                <span class="bg-warning-950 h-8 w-8 rounded"></span>
            </div>
            {{-- Info --}}
            <div class="flex items-center gap-1">
                <span class="w-16 shrink-0 font-mono text-xs">info</span>
                <span class="bg-info-50 h-8 w-8 rounded border border-gray-200 dark:border-gray-700"></span>
                <span class="bg-info-100 h-8 w-8 rounded"></span>
                <span class="bg-info-200 h-8 w-8 rounded"></span>
                <span class="bg-info-300 h-8 w-8 rounded"></span>
                <span class="bg-info-400 h-8 w-8 rounded"></span>
                <span class="bg-info-500 h-8 w-8 rounded"></span>
                <span class="bg-info-600 h-8 w-8 rounded"></span>
                <span class="bg-info-700 h-8 w-8 rounded"></span>
                <span class="bg-info-800 h-8 w-8 rounded"></span>
                <span class="bg-info-900 h-8 w-8 rounded"></span>
                <span class="bg-info-950 h-8 w-8 rounded"></span>
            </div>
            {{-- Gray --}}
            <div class="flex items-center gap-1">
                <span class="w-16 shrink-0 font-mono text-xs">gray</span>
                <span class="h-8 w-8 rounded border border-gray-200 bg-gray-50 dark:border-gray-700"></span>
                <span class="h-8 w-8 rounded bg-gray-100"></span>
                <span class="h-8 w-8 rounded bg-gray-200"></span>
                <span class="h-8 w-8 rounded bg-gray-300"></span>
                <span class="h-8 w-8 rounded bg-gray-400"></span>
                <span class="h-8 w-8 rounded bg-gray-500"></span>
                <span class="h-8 w-8 rounded bg-gray-600"></span>
                <span class="h-8 w-8 rounded bg-gray-700"></span>
                <span class="h-8 w-8 rounded bg-gray-800"></span>
                <span class="h-8 w-8 rounded bg-gray-900"></span>
                <span class="h-8 w-8 rounded bg-gray-950"></span>
            </div>
        </div>
    </x-filament::section>

    {{-- ============================================================
         SECTION: Typography
         ============================================================ --}}
    <x-filament::section>
        <x-slot name="heading">Typography</x-slot>
        <x-slot name="description">Heading levels, body text, labels</x-slot>

        <div class="space-y-4">
            <h1 class="text-4xl font-bold">Heading 1 — Northwestern University</h1>
            <h2 class="text-3xl font-bold">Heading 2 — Filament Admin Panel</h2>
            <h3 class="text-2xl font-semibold">Heading 3 — Section Title</h3>
            <h4 class="text-xl font-semibold">Heading 4 — Subsection</h4>
            <h5 class="text-lg font-medium">Heading 5 — Group Label</h5>
            <h6 class="text-base font-medium">Heading 6 — Small Heading</h6>
            <p class="text-base">Body text — The quick brown fox jumps over the lazy dog. This paragraph demonstrates
                the default body font at normal weight.</p>
            <p class="text-sm text-gray-500">Small muted text — Secondary information that appears throughout the UI in
                labels, descriptions, and helper text.</p>
            <p class="text-xs text-gray-400">Extra-small text — Timestamps, badges, and fine print.</p>
            <div class="flex items-baseline gap-4">
                <span class="font-bold">Bold</span>
                <span class="font-semibold">Semibold</span>
                <span class="font-medium">Medium</span>
                <span class="font-normal">Normal</span>
                <span class="font-light">Light</span>
                <span class="italic">Italic</span>
                <span class="underline">Underline</span>
                <code class="rounded bg-gray-100 px-1.5 py-0.5 font-mono text-sm dark:bg-gray-800">monospace</code>
            </div>
            <hr class="border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold">Links</h3>
            <p>Here is a paragraph with an <a href="#">inline content link</a> that should have the NU dashed
                underline style, and <a href="#">another link here</a> for comparison.</p>
        </div>
    </x-filament::section>

    {{-- ============================================================
         SECTION: Buttons
         ============================================================ --}}
    <x-filament::section>
        <x-slot name="heading">Buttons</x-slot>
        <x-slot name="description">All color x variant x size combinations</x-slot>

        @php $btnColors = ['primary', 'danger', 'warning', 'success', 'info', 'gray']; @endphp

        <div class="space-y-4">
            <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">Filled</h3>
            <div class="flex flex-wrap items-center gap-2">
                @foreach ($btnColors as $c)
                    <x-filament::button :color="$c">{{ ucfirst($c) }}</x-filament::button>
                @endforeach
                <x-filament::button disabled>Disabled</x-filament::button>
            </div>

            <h3 class="mt-6 text-sm font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">Outlined</h3>
            <div class="flex flex-wrap items-center gap-2">
                @foreach ($btnColors as $c)
                    <x-filament::button :color="$c" outlined>{{ ucfirst($c) }}</x-filament::button>
                @endforeach
                <x-filament::button outlined disabled>Disabled</x-filament::button>
            </div>

            <h3 class="mt-6 text-sm font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">Sizes</h3>
            <div class="flex flex-wrap items-end gap-2">
                <x-filament::button size="xs">Extra Small</x-filament::button>
                <x-filament::button size="sm">Small</x-filament::button>
                <x-filament::button size="md">Medium</x-filament::button>
                <x-filament::button size="lg">Large</x-filament::button>
                <x-filament::button size="xl">Extra Large</x-filament::button>
            </div>

            <h3 class="mt-6 text-sm font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">Badges</h3>
            <div class="flex flex-wrap items-center gap-2">
                @foreach ($btnColors as $c)
                    <x-filament::badge :color="$c">{{ ucfirst($c) }}</x-filament::badge>
                @endforeach
            </div>
        </div>
    </x-filament::section>

    {{-- ============================================================
         SECTION: Table Preview
         ============================================================ --}}
    <x-filament::section>
        <x-slot name="heading">Table Preview</x-slot>
        <x-slot name="description">Static table to preview row, header, and border styling</x-slot>

        <div class="fi-ta overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <table class="w-full table-auto divide-y divide-gray-200 dark:divide-white/5">
                <thead>
                    <tr class="bg-gray-50 dark:bg-white/5">
                        <th class="px-4 py-3 text-start text-sm font-semibold text-gray-950 dark:text-white">Name</th>
                        <th class="px-4 py-3 text-start text-sm font-semibold text-gray-950 dark:text-white">Email</th>
                        <th class="px-4 py-3 text-start text-sm font-semibold text-gray-950 dark:text-white">Role</th>
                        <th class="px-4 py-3 text-start text-sm font-semibold text-gray-950 dark:text-white">Status</th>
                        <th class="px-4 py-3 text-end text-sm font-semibold text-gray-950 dark:text-white">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                    @foreach ([['Jane Cooper', 'jane@northwestern.edu', 'Administrator', 'success', 'Active'], ['John Smith', 'john@northwestern.edu', 'Editor', 'warning', 'Pending'], ['Emily Davis', 'emily@northwestern.edu', 'Viewer', 'gray', 'Inactive'], ['Robert Brown', 'robert@northwestern.edu', 'Editor', 'danger', 'Suspended'], ['Sarah Wilson', 'sarah@northwestern.edu', 'Administrator', 'info', 'Review']] as [$name, $email, $role, $statusColor, $statusLabel])
                        <tr class="transition hover:bg-gray-50 dark:hover:bg-white/5">
                            <td class="px-4 py-3 text-sm font-medium text-gray-950 dark:text-white">{{ $name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $email }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $role }}</td>
                            <td class="px-4 py-3">
                                <x-filament::badge :color="$statusColor">{{ $statusLabel }}</x-filament::badge>
                            </td>
                            <td class="px-4 py-3 text-end">
                                <div class="flex justify-end gap-1">
                                    <x-filament::icon-button icon="heroicon-o-pencil" color="gray" tooltip="Edit" />
                                    <x-filament::icon-button icon="heroicon-o-trash" color="danger" tooltip="Delete" />
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-filament::section>

    {{-- ============================================================
         SECTION: Sections & Cards
         ============================================================ --}}
    <x-filament::section>
        <x-slot name="heading">Sections & Cards</x-slot>

        <div class="grid gap-6 md:grid-cols-2">
            <x-filament::section>
                <x-slot name="heading">Default Section</x-slot>
                <p class="text-sm text-gray-500 dark:text-gray-400">This is a nested section with default styling.</p>
            </x-filament::section>

            <x-filament::section collapsible>
                <x-slot name="heading">Collapsible Section</x-slot>
                <p class="text-sm text-gray-500 dark:text-gray-400">Click the header to collapse or expand.</p>
            </x-filament::section>

            <x-filament::section compact>
                <x-slot name="heading">Compact Section</x-slot>
                <p class="text-sm text-gray-500 dark:text-gray-400">Reduced padding variant.</p>
            </x-filament::section>

            <x-filament::section icon="heroicon-o-academic-cap">
                <x-slot name="heading">Section with Icon</x-slot>
                <p class="text-sm text-gray-500 dark:text-gray-400">Section header includes a leading icon.</p>
            </x-filament::section>
        </div>
    </x-filament::section>

    {{-- ============================================================
         SECTION: Inline Alerts
         ============================================================ --}}
    <x-filament::section>
        <x-slot name="heading">Inline Alerts</x-slot>

        <div class="space-y-3">
            <div class="border-info-300 bg-info-50 dark:border-info-500/30 dark:bg-info-500/10 rounded-lg border p-4">
                <div class="flex items-start gap-3">
                    <x-filament::icon class="text-info-600 dark:text-info-400 mt-0.5 h-5 w-5 shrink-0" icon="heroicon-o-information-circle" />
                    <div>
                        <p class="text-info-800 dark:text-info-200 text-sm font-semibold">Information</p>
                        <p class="text-info-700 dark:text-info-300 mt-0.5 text-sm">This is an informational alert.</p>
                    </div>
                </div>
            </div>
            <div class="border-success-300 bg-success-50 dark:border-success-500/30 dark:bg-success-500/10 rounded-lg border p-4">
                <div class="flex items-start gap-3">
                    <x-filament::icon class="text-success-600 dark:text-success-400 mt-0.5 h-5 w-5 shrink-0" icon="heroicon-o-check-circle" />
                    <div>
                        <p class="text-success-800 dark:text-success-200 text-sm font-semibold">Success</p>
                        <p class="text-success-700 dark:text-success-300 mt-0.5 text-sm">Operation completed successfully.</p>
                    </div>
                </div>
            </div>
            <div class="border-warning-300 bg-warning-50 dark:border-warning-500/30 dark:bg-warning-500/10 rounded-lg border p-4">
                <div class="flex items-start gap-3">
                    <x-filament::icon class="text-warning-600 dark:text-warning-400 mt-0.5 h-5 w-5 shrink-0" icon="heroicon-o-exclamation-triangle" />
                    <div>
                        <p class="text-warning-800 dark:text-warning-200 text-sm font-semibold">Warning</p>
                        <p class="text-warning-700 dark:text-warning-300 mt-0.5 text-sm">Please review before continuing.</p>
                    </div>
                </div>
            </div>
            <div class="border-danger-300 bg-danger-50 dark:border-danger-500/30 dark:bg-danger-500/10 rounded-lg border p-4">
                <div class="flex items-start gap-3">
                    <x-filament::icon class="text-danger-600 dark:text-danger-400 mt-0.5 h-5 w-5 shrink-0" icon="heroicon-o-x-circle" />
                    <div>
                        <p class="text-danger-800 dark:text-danger-200 text-sm font-semibold">Error</p>
                        <p class="text-danger-700 dark:text-danger-300 mt-0.5 text-sm">Something went wrong. Please try again.</p>
                    </div>
                </div>
            </div>
        </div>
    </x-filament::section>

    {{-- ============================================================
         SECTION: Form Components (via Filament Form)
         ============================================================ --}}
    <x-filament::section>
        <x-slot name="heading">Form Components</x-slot>
        <x-slot name="description">All form inputs rendered via Filament's form builder</x-slot>

        {{ $this->form }}
    </x-filament::section>

    {{-- ============================================================
         SECTION: Loading & Disabled States
         ============================================================ --}}
    <x-filament::section>
        <x-slot name="heading">Loading & Disabled States</x-slot>

        <div class="space-y-4">
            <div class="flex flex-wrap items-center gap-2">
                <x-filament::button disabled>Disabled Primary</x-filament::button>
                <x-filament::button color="danger" disabled>Disabled Danger</x-filament::button>
                <x-filament::button color="success" disabled>Disabled Success</x-filament::button>
                <x-filament::button outlined disabled>Disabled Outlined</x-filament::button>
            </div>

            <h3 class="mt-4 text-sm font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">Skeleton Placeholders</h3>
            <div class="max-w-md space-y-3">
                <div class="h-4 w-3/4 animate-pulse rounded bg-gray-200 dark:bg-gray-700"></div>
                <div class="h-4 w-full animate-pulse rounded bg-gray-200 dark:bg-gray-700"></div>
                <div class="h-4 w-1/2 animate-pulse rounded bg-gray-200 dark:bg-gray-700"></div>
                <div class="mt-4 h-10 w-full animate-pulse rounded bg-gray-200 dark:bg-gray-700"></div>
            </div>
        </div>
    </x-filament::section>

</x-filament-panels::page>
