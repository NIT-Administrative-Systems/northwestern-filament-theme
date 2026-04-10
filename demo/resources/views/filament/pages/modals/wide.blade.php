<div class="space-y-4 text-sm text-gray-600 dark:text-gray-300">
    <div class="grid gap-4 sm:grid-cols-3">
        @foreach ([["heroicon-o-users", "Total Users", "1,247"], ["heroicon-o-signal", "Active Sessions", "342"], ["heroicon-o-clock", "Avg Response", "124ms"]] as [$icon, $label, $value])
            <div class="rounded-lg bg-gray-50 p-4 dark:bg-white/5">
                <div class="flex items-center gap-2">
                    <x-filament::icon class="h-5 w-5 text-gray-400" :icon="$icon" />
                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ $label }}</span>
                </div>
                <div class="mt-1 text-2xl font-semibold text-gray-950 dark:text-white">{{ $value }}</div>
            </div>
        @endforeach
    </div>
    <p>Wide modals are useful for displaying data-heavy content like dashboards, comparison tables, or multi-column
        layouts that benefit from extra horizontal space.</p>
</div>
