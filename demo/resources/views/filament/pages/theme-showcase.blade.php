<x-filament-panels::page>
    <x-filament::section data-testid="theme-preview">
        <x-slot name="heading">Theme Preview</x-slot>
        <x-slot name="description">Condensed overview of key themed components</x-slot>

        <div class="space-y-4">
            <div class="flex flex-wrap items-center gap-6">
                <div class="flex items-center gap-2">
                    <x-filament::button>Primary</x-filament::button>
                    <x-filament::button color="danger">Danger</x-filament::button>
                    <x-filament::button color="success">Success</x-filament::button>
                    <x-filament::button outlined>Outlined</x-filament::button>
                    <x-filament::button disabled>Disabled</x-filament::button>
                    <x-filament::icon-button icon="heroicon-o-pencil" tooltip="Edit" />
                </div>
                <div class="flex items-center gap-2">
                    <x-filament::badge color="primary">Active</x-filament::badge>
                    <x-filament::badge color="success" icon="heroicon-o-check-circle">Approved</x-filament::badge>
                    <x-filament::badge color="danger">Rejected</x-filament::badge>
                    <x-filament::badge color="warning">Pending</x-filament::badge>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr auto 1fr; gap: 0.75rem; align-items: end;">
                <div>
                    <label class="fi-fo-field-label mb-1 block text-sm font-medium text-gray-950 dark:text-white">Text
                        Input</label>
                    <div class="fi-input-wrp overflow-hidden">
                        <input class="fi-input block w-full border-none bg-transparent px-3 py-1.5 text-sm text-gray-950 outline-none dark:text-white"
                               type="text"
                               value="Sample text"
                               readonly />
                    </div>
                </div>
                <div>
                    <label
                           class="fi-fo-field-label mb-1 block text-sm font-medium text-gray-950 dark:text-white">Select</label>
                    <div class="fi-input-wrp overflow-hidden">
                        <select
                                class="fi-select-input block w-full border-none bg-transparent px-3 py-1.5 text-sm text-gray-950 outline-none dark:text-white">
                            <option>Option One</option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center gap-4 pb-1.5">
                    <label class="flex items-center gap-2">
                        <input class="fi-checkbox-input rounded"
                               type="checkbox"
                               checked />
                        <span class="text-sm text-gray-950 dark:text-white">Checked</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input class="fi-checkbox-input rounded" type="checkbox" />
                        <span class="text-sm text-gray-950 dark:text-white">Unchecked</span>
                    </label>
                </div>
                <div>
                    <label
                           class="fi-fo-field-label mb-1 block text-sm font-medium text-gray-950 dark:text-white">Disabled</label>
                    <div class="fi-input-wrp fi-disabled overflow-hidden">
                        <input class="fi-input block w-full border-none bg-transparent px-3 py-1.5 text-sm text-gray-950 outline-none dark:text-white"
                               type="text"
                               value="Cannot edit"
                               disabled />
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 280px; gap: 1rem;">
                <div class="fi-ta-ctn overflow-hidden">
                    <table class="w-full table-auto">
                        <thead>
                            <tr>
                                <th
                                    class="fi-ta-header-cell px-3 py-2 text-start text-xs font-semibold uppercase tracking-wider">
                                    Name</th>
                                <th
                                    class="fi-ta-header-cell px-3 py-2 text-start text-xs font-semibold uppercase tracking-wider">
                                    Role</th>
                                <th
                                    class="fi-ta-header-cell px-3 py-2 text-start text-xs font-semibold uppercase tracking-wider">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="fi-ta-row">
                                <td class="px-3 py-2 text-sm font-medium text-gray-950 dark:text-white">Jane Cooper</td>
                                <td class="px-3 py-2 text-sm text-gray-500">Administrator</td>
                                <td class="px-3 py-2"><x-filament::badge color="success">Active</x-filament::badge></td>
                            </tr>
                            <tr class="fi-ta-row">
                                <td class="px-3 py-2 text-sm font-medium text-gray-950 dark:text-white">John Smith</td>
                                <td class="px-3 py-2 text-sm text-gray-500">Editor</td>
                                <td class="px-3 py-2"><x-filament::badge color="warning">Pending</x-filament::badge>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="flex flex-col gap-3">
                    <div
                         class="fi-wi-stats-overview-stat relative rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Users</span>
                        <div class="text-2xl font-semibold text-gray-950 dark:text-white">1,247</div>
                        <div class="text-success-600 dark:text-success-400 text-xs">12% increase</div>
                    </div>
                    <div
                         class="fi-wi-stats-overview-stat relative rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Sessions</span>
                        <div class="text-2xl font-semibold text-gray-950 dark:text-white">342</div>
                        <div class="text-danger-600 dark:text-danger-400 text-xs">3% decrease</div>
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.75rem;">
                <x-filament::section compact>
                    <x-slot name="heading">Compact Section</x-slot>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Card-style container with border treatment.</p>
                </x-filament::section>
                <x-filament::section compact icon="heroicon-o-academic-cap">
                    <x-slot name="heading">With Icon</x-slot>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Section header with leading icon.</p>
                </x-filament::section>
                <div
                     class="border-success-300 bg-success-50 dark:border-success-500/30 dark:bg-success-500/10 flex items-start gap-3 rounded-lg border p-4">
                    <x-filament::icon class="text-success-600 dark:text-success-400 mt-0.5 h-5 w-5 shrink-0"
                                      icon="heroicon-o-check-circle" />
                    <div>
                        <p class="text-success-800 dark:text-success-200 text-sm font-semibold">Success</p>
                        <p class="text-success-700 dark:text-success-300 text-sm">Operation completed.</p>
                    </div>
                </div>
            </div>
        </div>
    </x-filament::section>

    {{-- Color Palette --}}
    <x-filament::section data-testid="color-palette">
        <x-slot name="heading">Color Palette</x-slot>
        <x-slot name="description">All registered Filament color slots</x-slot>

        @php
            $c = \Northwestern\FilamentTheme\Colors::class;
            $palette = [
                "primary" => [
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
                "danger" => [600 => $c::ORANGE],
                "success" => [600 => $c::GREEN],
                "warning" => [600 => $c::GOLD],
                "info" => [600 => $c::BLUE],
                "gray" => [
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

        <div class="mt-6 space-y-3">
            <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">Tailwind Utility
                Classes</h3>
            @foreach (["primary", "danger", "success", "warning", "info"] as $colorName)
                <div class="flex items-center gap-1">
                    <span class="w-16 shrink-0 font-mono text-xs">{{ $colorName }}</span>
                    @foreach ([50, 100, 200, 300, 400, 500, 600, 700, 800, 900, 950] as $shade)
                        <span
                              class="bg-{{ $colorName }}-{{ $shade }} h-8 w-8 rounded {{ $shade === 50 ? "border border-gray-200 dark:border-gray-700" : "" }}"></span>
                    @endforeach
                </div>
            @endforeach
            <div class="flex items-center gap-1">
                <span class="w-16 shrink-0 font-mono text-xs">gray</span>
                @foreach ([50, 100, 200, 300, 400, 500, 600, 700, 800, 900, 950] as $shade)
                    <span
                          class="bg-gray-{{ $shade }} h-8 w-8 rounded {{ $shade === 50 ? "border border-gray-200 dark:border-gray-700" : "" }}"></span>
                @endforeach
            </div>
        </div>
    </x-filament::section>

    {{-- Typography --}}
    <x-filament::section data-testid="typography">
        <x-slot name="heading">Typography</x-slot>
        <x-slot name="description">Heading levels, body text, labels</x-slot>

        <div class="space-y-4">
            <h1 class="text-4xl font-bold">Heading 1 — Northwestern University</h1>
            <h2 class="text-3xl font-bold">Heading 2 — Filament Admin Panel</h2>
            <h3 class="text-2xl font-semibold">Heading 3 — Section Title</h3>
            <h4 class="text-xl font-semibold">Heading 4 — Subsection</h4>
            <h5 class="text-lg font-medium">Heading 5 — Group Label</h5>
            <h6 class="text-base font-medium">Heading 6 — Small Heading</h6>
            <p class="text-base">Body text — The quick brown fox jumps over the lazy dog.</p>
            <p class="text-sm text-gray-500">Small muted text — Secondary information in labels and descriptions.</p>
            <p class="text-xs text-gray-400">Extra-small text — Timestamps, badges, and fine print.</p>
            <div class="flex items-baseline gap-4">
                <span class="font-bold">Bold</span>
                <span class="font-semibold">Semibold</span>
                <span class="font-medium">Medium</span>
                <span class="font-normal">Normal</span>
                <span class="italic">Italic</span>
                <span class="underline">Underline</span>
                <code class="rounded bg-gray-100 px-1.5 py-0.5 font-mono text-sm dark:bg-gray-800">monospace</code>
            </div>
            <hr class="border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold">Links</h3>
            <p>Here is a paragraph with an <a href="#">inline link</a> that should have the NU dashed underline
                style.</p>
            <div class="flex items-baseline gap-4">
                <a href="#">Default link</a>
                <a class="font-bold" href="#">Bold link</a>
                <a class="text-sm" href="#">Small link</a>
            </div>
        </div>
    </x-filament::section>

    {{-- Buttons --}}
    <x-filament::section data-testid="buttons">
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

            <h3 class="mt-6 text-sm font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">Outlined
            </h3>
            <div class="flex flex-wrap items-center gap-2">
                @foreach ($btnColors as $c)
                    <x-filament::button :color="$c" outlined>{{ ucfirst($c) }}</x-filament::button>
                @endforeach
            </div>

            <h3 class="mt-6 text-sm font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">Sizes</h3>
            <div class="flex flex-wrap items-end gap-2">
                <x-filament::button size="xs">Extra Small</x-filament::button>
                <x-filament::button size="sm">Small</x-filament::button>
                <x-filament::button size="md">Medium</x-filament::button>
                <x-filament::button size="lg">Large</x-filament::button>
                <x-filament::button size="xl">Extra Large</x-filament::button>
            </div>

            <h3 class="mt-6 text-sm font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">Icon
                Buttons</h3>
            <div class="flex flex-wrap items-center gap-2">
                @foreach ($btnColors as $c)
                    <x-filament::icon-button :color="$c"
                                             icon="heroicon-o-pencil"
                                             :tooltip="ucfirst($c)" />
                @endforeach
            </div>

            <h3 class="mt-6 text-sm font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">Link
                Buttons</h3>
            <div class="flex flex-wrap items-center gap-4">
                @foreach ($btnColors as $c)
                    <x-filament::link href="#" :color="$c">{{ ucfirst($c) }} Link</x-filament::link>
                @endforeach
            </div>
        </div>
    </x-filament::section>

    {{-- Badges --}}
    <x-filament::section data-testid="badges">
        <x-slot name="heading">Badges</x-slot>
        <x-slot name="description">Color and size variants</x-slot>

        <div class="space-y-4">
            <div class="flex flex-wrap items-center gap-2">
                @foreach ($btnColors as $c)
                    <x-filament::badge :color="$c">{{ ucfirst($c) }}</x-filament::badge>
                @endforeach
            </div>
            <div class="flex flex-wrap items-center gap-2">
                @foreach ($btnColors as $c)
                    <x-filament::badge :color="$c" icon="heroicon-o-check-circle">With Icon</x-filament::badge>
                @endforeach
            </div>
        </div>
    </x-filament::section>

    {{-- Notifications --}}
    <x-filament::section data-testid="notifications">
        <x-slot name="heading">Notifications</x-slot>
        <x-slot name="description">Click to fire all notification types</x-slot>

        <x-filament::button wire:click="triggerNotifications" icon="heroicon-o-bell">
            Fire All Notifications
        </x-filament::button>
    </x-filament::section>

    {{-- Modals --}}
    <x-filament::section data-testid="modals">
        <x-slot name="heading">Modals</x-slot>
        <x-slot name="description">Modal dialogs with different content and interaction patterns</x-slot>

        <div class="space-y-4">
            <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">Content Modals
            </h3>
            <div class="flex flex-wrap items-center gap-2">
                {{ $this->simpleModalAction }}
                {{ $this->wideModalAction }}
                {{ $this->longContentModalAction }}
            </div>

            <h3 class="mt-6 text-sm font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">
                Interactive Modals</h3>
            <div class="flex flex-wrap items-center gap-2">
                {{ $this->confirmationModalAction }}
                {{ $this->formModalAction }}
                {{ $this->iconModalAction }}
                {{ $this->footerActionsModalAction }}
            </div>
        </div>

        <x-filament-actions::modals />
    </x-filament::section>

    {{-- Stats Overview --}}
    <x-filament::section data-testid="stats-overview">
        <x-slot name="heading">Stats Overview</x-slot>

        <div class="fi-wi-stats-overview-stats-ctn grid gap-6 md:grid-cols-3">
            @foreach ([["heroicon-o-users", "Total Users", "1,247", "12% increase", "success"], ["heroicon-o-signal", "Active Sessions", "342", "3% decrease", "danger"], ["heroicon-o-clock", "Avg Response Time", "124ms", "Within SLA", "info"]] as [$icon, $label, $value, $trend, $trendColor])
                <div
                     class="fi-wi-stats-overview-stat relative rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                    <div class="flex items-center gap-x-2">
                        <x-filament::icon class="h-5 w-5 text-gray-400 dark:text-gray-500" :icon="$icon" />
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $label }}</span>
                    </div>
                    <div class="mt-1 text-3xl font-semibold text-gray-950 dark:text-white">{{ $value }}</div>
                    <div class="text-{{ $trendColor }}-600 dark:text-{{ $trendColor }}-400 mt-1 text-sm">
                        {{ $trend }}</div>
                </div>
            @endforeach
        </div>
    </x-filament::section>

    {{-- Table Preview --}}
    <x-filament::section data-testid="table-preview">
        <x-slot name="heading">Table Preview</x-slot>
        <x-slot name="description">Static table to preview row, header, and border styling</x-slot>

        <div
             class="fi-ta overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <table class="w-full table-auto divide-y divide-gray-200 dark:divide-white/5">
                <thead>
                    <tr class="bg-gray-50 dark:bg-white/5">
                        <th class="px-4 py-3 text-start text-sm font-semibold text-gray-950 dark:text-white">Name</th>
                        <th class="px-4 py-3 text-start text-sm font-semibold text-gray-950 dark:text-white">Email</th>
                        <th class="px-4 py-3 text-start text-sm font-semibold text-gray-950 dark:text-white">Status
                        </th>
                        <th class="px-4 py-3 text-end text-sm font-semibold text-gray-950 dark:text-white">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                    @foreach ([["Jane Cooper", "jane@northwestern.edu", "success", "Active"], ["John Smith", "john@northwestern.edu", "warning", "Pending"], ["Emily Davis", "emily@northwestern.edu", "gray", "Inactive"], ["Robert Brown", "robert@northwestern.edu", "danger", "Suspended"], ["Sarah Wilson", "sarah@northwestern.edu", "info", "Review"]] as [$name, $email, $statusColor, $statusLabel])
                        <tr class="transition hover:bg-gray-50 dark:hover:bg-white/5">
                            <td class="px-4 py-3 text-sm font-medium text-gray-950 dark:text-white">
                                {{ $name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $email }}</td>
                            <td class="px-4 py-3"><x-filament::badge
                                                   :color="$statusColor">{{ $statusLabel }}</x-filament::badge></td>
                            <td class="px-4 py-3 text-end">
                                <div class="flex justify-end gap-1">
                                    <x-filament::icon-button icon="heroicon-o-pencil"
                                                             color="gray"
                                                             tooltip="Edit" />
                                    <x-filament::icon-button icon="heroicon-o-trash"
                                                             color="danger"
                                                             tooltip="Delete" />
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-filament::section>

    {{-- Sections & Cards --}}
    <x-filament::section data-testid="sections-cards">
        <x-slot name="heading">Sections & Cards</x-slot>
        <x-slot name="description">Layout containers and their variants</x-slot>

        <div class="grid gap-6 md:grid-cols-2">
            <x-filament::section>
                <x-slot name="heading">Default Section</x-slot>
                <p class="text-sm text-gray-500 dark:text-gray-400">Nested section with default styling.</p>
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

    {{-- Form Components --}}
    <x-filament::section data-testid="form-components">
        <x-slot name="heading">Form Components</x-slot>
        <x-slot name="description">All form inputs rendered via Filament's form builder</x-slot>

        {{ $this->form }}
    </x-filament::section>

    {{-- Infolist / Read-Only Display --}}
    <x-filament::section data-testid="infolist">
        <x-slot name="heading">Infolist / Read-Only Display</x-slot>
        <x-slot name="description">Simulates infolist entry styling for view pages</x-slot>

        <div class="grid gap-4 sm:grid-cols-2">
            <div class="fi-in-entry">
                <dt class="fi-in-entry-label text-sm font-medium text-gray-500 dark:text-gray-400">Full Name</dt>
                <dd class="fi-in-entry-content mt-1 text-sm text-gray-950 dark:text-white">Jane Cooper</dd>
            </div>
            <div class="fi-in-entry">
                <dt class="fi-in-entry-label text-sm font-medium text-gray-500 dark:text-gray-400">Email Address</dt>
                <dd class="fi-in-entry-content mt-1 text-sm text-gray-950 dark:text-white">jane@northwestern.edu</dd>
            </div>
            <div class="fi-in-entry">
                <dt class="fi-in-entry-label text-sm font-medium text-gray-500 dark:text-gray-400">Department</dt>
                <dd class="fi-in-entry-content mt-1 text-sm text-gray-950 dark:text-white">Computer Science</dd>
            </div>
            <div class="fi-in-entry">
                <dt class="fi-in-entry-label text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                <dd class="fi-in-entry-content mt-1"><x-filament::badge color="success">Active</x-filament::badge>
                </dd>
            </div>
            <div class="fi-in-entry sm:col-span-2">
                <dt class="fi-in-entry-label text-sm font-medium text-gray-500 dark:text-gray-400">Bio</dt>
                <dd class="fi-in-entry-content mt-1 text-sm text-gray-950 dark:text-white">Professor of Computer
                    Science at Northwestern University, specializing in distributed systems and cloud computing.</dd>
            </div>
            <div class="fi-in-entry">
                <dt class="fi-in-entry-label text-sm font-medium text-gray-500 dark:text-gray-400">Empty Field</dt>
                <dd class="fi-in-placeholder mt-1 text-sm italic text-gray-400 dark:text-gray-500">No value provided
                </dd>
            </div>
        </div>
    </x-filament::section>

    {{-- Empty State --}}
    <x-filament::section data-testid="empty-state">
        <x-slot name="heading">Empty State</x-slot>
        <x-slot name="description">Shown when a table or list has no records</x-slot>

        <div class="fi-ta-empty-state flex flex-col items-center justify-center p-12">
            <div class="fi-empty-state-icon-bg mb-4 flex h-16 w-16 items-center justify-center rounded-full">
                <x-filament::icon class="h-8 w-8 text-gray-400 dark:text-gray-500"
                                  icon="heroicon-o-document-magnifying-glass" />
            </div>
            <h3 class="fi-empty-state-heading text-base font-semibold text-gray-950 dark:text-white">No records found
            </h3>
            <p class="fi-empty-state-description mt-1 text-sm text-gray-500 dark:text-gray-400">Try adjusting your
                search or filter criteria.</p>
            <div class="mt-4"><x-filament::button icon="heroicon-o-plus">Create Record</x-filament::button></div>
        </div>
    </x-filament::section>

    {{-- Callouts --}}
    <x-filament::section data-testid="callouts">
        <x-slot name="heading">Callouts</x-slot>
        <x-slot name="description">Filament's built-in callout component in all color variants</x-slot>

        <div class="space-y-3">
            <x-filament::callout color="info"
                                 icon="heroicon-o-information-circle"
                                 heading="Information"
                                 description="This is an informational callout for general guidance or tips." />

            <x-filament::callout color="success"
                                 icon="heroicon-o-check-circle"
                                 heading="Success"
                                 description="Operation completed successfully. All changes have been saved." />

            <x-filament::callout color="warning"
                                 icon="heroicon-o-exclamation-triangle"
                                 heading="Warning"
                                 description="Please review before continuing. This action may have side effects." />

            <x-filament::callout color="danger"
                                 icon="heroicon-o-x-circle"
                                 heading="Error"
                                 description="Something went wrong. Please try again or contact support." />

            <x-filament::callout color="gray"
                                 icon="heroicon-o-chat-bubble-left-ellipsis"
                                 heading="Note"
                                 description="A neutral callout for general notes or supplementary context." />

            <x-filament::callout color="primary"
                                 icon="heroicon-o-academic-cap"
                                 heading="Tip"
                                 description="A primary-colored callout for highlighting best practices or recommendations." />

            <x-filament::callout color="warning"
                                 icon="heroicon-o-exclamation-triangle"
                                 description="A callout with no heading — useful for brief inline messages." />

            <x-filament::callout color="info"
                                 icon="heroicon-o-light-bulb"
                                 heading="With footer action">
                <x-slot name="description">Callouts can include footer actions for additional interactivity.</x-slot>
                <x-slot name="footer">
                    <x-filament::link href="#"
                                      color="info"
                                      icon="heroicon-o-arrow-top-right-on-square">Learn more</x-filament::link>
                </x-slot>
            </x-filament::callout>
        </div>
    </x-filament::section>

    {{-- Inline Alerts --}}
    <x-filament::section data-testid="inline-alerts">
        <x-slot name="heading">Inline Alerts</x-slot>

        <div class="space-y-3">
            @foreach ([["info", "heroicon-o-information-circle", "Information", "This is an informational alert."], ["success", "heroicon-o-check-circle", "Success", "Operation completed successfully."], ["warning", "heroicon-o-exclamation-triangle", "Warning", "Please review before continuing."], ["danger", "heroicon-o-x-circle", "Error", "Something went wrong. Please try again."]] as [$alertColor, $alertIcon, $alertTitle, $alertBody])
                <div
                     class="border-{{ $alertColor }}-300 bg-{{ $alertColor }}-50 dark:border-{{ $alertColor }}-500/30 dark:bg-{{ $alertColor }}-500/10 rounded-lg border p-4">
                    <div class="flex items-start gap-3">
                        <x-filament::icon class="text-{{ $alertColor }}-600 dark:text-{{ $alertColor }}-400 mt-0.5 h-5 w-5 shrink-0"
                                          :icon="$alertIcon" />
                        <div>
                            <p
                               class="text-{{ $alertColor }}-800 dark:text-{{ $alertColor }}-200 text-sm font-semibold">
                                {{ $alertTitle }}</p>
                            <p class="text-{{ $alertColor }}-700 dark:text-{{ $alertColor }}-300 mt-0.5 text-sm">
                                {{ $alertBody }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </x-filament::section>

    {{-- Loading & Disabled States --}}
    <x-filament::section data-testid="loading-disabled">
        <x-slot name="heading">Loading & Disabled States</x-slot>

        <div class="space-y-4">
            <div class="flex flex-wrap items-center gap-2">
                <x-filament::button disabled>Disabled Primary</x-filament::button>
                <x-filament::button color="danger" disabled>Disabled Danger</x-filament::button>
                <x-filament::button color="success" disabled>Disabled Success</x-filament::button>
                <x-filament::button outlined disabled>Disabled Outlined</x-filament::button>
            </div>

            <h3 class="mt-4 text-sm font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">Skeleton
                Placeholders</h3>
            <div class="max-w-md space-y-3">
                <div class="h-4 w-3/4 animate-pulse rounded bg-gray-200 dark:bg-gray-700"></div>
                <div class="h-4 w-full animate-pulse rounded bg-gray-200 dark:bg-gray-700"></div>
                <div class="h-4 w-1/2 animate-pulse rounded bg-gray-200 dark:bg-gray-700"></div>
                <div class="mt-4 h-10 w-full animate-pulse rounded bg-gray-200 dark:bg-gray-700"></div>
            </div>
        </div>
    </x-filament::section>

    {{-- Dropdowns --}}
    <x-filament::section data-testid="dropdowns">
        <x-slot name="heading">Dropdowns</x-slot>

        <div class="flex gap-4">
            <x-filament::dropdown>
                <x-slot name="trigger">
                    <x-filament::button>Dropdown Menu</x-filament::button>
                </x-slot>
                <x-filament::dropdown.list>
                    <x-filament::dropdown.list.item icon="heroicon-o-pencil">Edit</x-filament::dropdown.list.item>
                    <x-filament::dropdown.list.item
                                                    icon="heroicon-o-document-duplicate">Duplicate</x-filament::dropdown.list.item>
                    <x-filament::dropdown.list.item
                                                    icon="heroicon-o-archive-box">Archive</x-filament::dropdown.list.item>
                </x-filament::dropdown.list>
                <x-filament::dropdown.list>
                    <x-filament::dropdown.list.item icon="heroicon-o-trash"
                                                    color="danger">Delete</x-filament::dropdown.list.item>
                </x-filament::dropdown.list>
            </x-filament::dropdown>
        </div>
    </x-filament::section>

    {{-- Navigation Elements --}}
    <x-filament::section data-testid="navigation">
        <x-slot name="heading">Navigation Elements</x-slot>
        <x-slot name="description">Pagination preview — sidebar and tabs visible in page chrome</x-slot>

        <div class="space-y-4">
            <nav class="flex items-center gap-x-1">
                <button class="fi-btn fi-color fi-btn-color-gray fi-size-sm fi-btn-size-sm relative grid-flow-col items-center justify-center gap-1 rounded-lg px-2 py-1.5 text-sm font-semibold outline-none transition duration-75 focus-visible:ring-2"
                        disabled>Previous</button>
                <div class="flex items-center gap-x-0.5">
                    <button
                            class="fi-btn fi-color fi-btn-color-gray fi-size-sm fi-btn-size-sm bg-primary-50 text-primary-600 dark:bg-primary-400/10 dark:text-primary-400 relative grid-flow-col items-center justify-center gap-1 rounded-lg px-2 py-1.5 text-sm font-semibold outline-none transition duration-75 focus-visible:ring-2">1</button>
                    <button
                            class="fi-btn fi-color fi-btn-color-gray fi-size-sm fi-btn-size-sm relative grid-flow-col items-center justify-center gap-1 rounded-lg px-2 py-1.5 text-sm font-semibold text-gray-700 outline-none transition duration-75 focus-visible:ring-2 dark:text-gray-300">2</button>
                    <button
                            class="fi-btn fi-color fi-btn-color-gray fi-size-sm fi-btn-size-sm relative grid-flow-col items-center justify-center gap-1 rounded-lg px-2 py-1.5 text-sm font-semibold text-gray-700 outline-none transition duration-75 focus-visible:ring-2 dark:text-gray-300">3</button>
                </div>
                <button
                        class="fi-btn fi-color fi-btn-color-gray fi-size-sm fi-btn-size-sm relative grid-flow-col items-center justify-center gap-1 rounded-lg px-2 py-1.5 text-sm font-semibold text-gray-700 outline-none transition duration-75 focus-visible:ring-2 dark:text-gray-300">Next</button>
            </nav>
        </div>
    </x-filament::section>

</x-filament-panels::page>
