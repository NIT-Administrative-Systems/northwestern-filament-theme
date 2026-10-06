<?php

declare(strict_types=1);

use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Northwestern\FilamentTheme\Filters\DateRangeFilter;

function dateRangeEvents(): Illuminate\Database\Eloquent\Builder
{
    return (new class extends Model
    {
        protected $table = 'events';
    })->newQuery();
}

/**
 * @return array{0: DatePicker, 1: DatePicker}
 */
function dateRangePickers(Filament\Tables\Filters\Filter $filter): array
{
    $pickers = array_values(array_filter($filter->getSchemaComponents(), fn (\Filament\Actions\Action|\Filament\Actions\ActionGroup|Filament\Schemas\Components\Component $component) => $component instanceof DatePicker));

    return [$pickers[0], $pickers[1]];
}

it('builds a filter with From and To dates on the browser date input', function () {
    $filter = resolve(DateRangeFilter::class)->make(name: 'created_at_range', label: 'Date Range', column: 'created_at');

    [$from, $until] = dateRangePickers($filter);

    expect($filter->getName())->toBe('created_at_range')
        ->and($filter->getLabel())->toBe('Date Range')
        ->and($from->getName())->toBe('from')
        ->and($from->getLabel())->toBe('From')
        ->and($from->isNative())->toBeTrue()
        ->and($until->getName())->toBe('to')
        ->and($until->getLabel())->toBe('To')
        ->and($until->isNative())->toBeTrue();
});

it('takes field names, labels, an icon and a limit of today', function () {
    Carbon::setTestNow('2026-10-06 15:00:00');

    $filter = resolve(DateRangeFilter::class)->make(
        name: 'range',
        label: 'Signed In',
        column: 'logged_in_at',
        fromField: 'start',
        untilField: 'end',
        icon: Heroicon::OutlinedCalendar,
        limitUntilToToday: true,
        fromLabel: 'Start',
        untilLabel: 'End',
    );

    [$from, $until] = dateRangePickers($filter);

    expect([$from->getName(), $from->getLabel(), $until->getName(), $until->getLabel()])->toBe(['start', 'Start', 'end', 'End'])
        ->and($from->getPrefixIcon())->toBe(Heroicon::OutlinedCalendar)
        ->and($until->getPrefixIcon())->toBe(Heroicon::OutlinedCalendar)
        // Filament stores the limit as the start or end of the day depending on its version.
        ->and($until->getMaxDate())->toStartWith('2026-10-06 ');
});

it('filters timestamps from the start of the From day to the end of the To day', function () {
    $query = resolve(DateRangeFilter::class)->apply(dateRangeEvents(), ['from' => '2026-10-01', 'to' => '2026-10-05'], 'created_at');

    expect($query->toRawSql())->toBe(
        'select * from "events" where "created_at" >= \'2026-10-01 00:00:00\' and "created_at" <= \'2026-10-05 23:59:59\'',
    );
});

it('compares dates only in date mode', function () {
    $query = resolve(DateRangeFilter::class)->apply(
        dateRangeEvents(),
        ['from' => '2026-10-01', 'to' => '2026-10-05'],
        'published_on',
        mode: DateRangeFilter::ModeDate,
    );

    expect($query->toRawSql())->toBe(
        'select * from "events" where strftime(\'%Y-%m-%d\', "published_on") >= cast(\'2026-10-01\' as text) and strftime(\'%Y-%m-%d\', "published_on") <= cast(\'2026-10-05\' as text)',
    );
});

it('leaves the query alone without dates', function (string $mode) {
    $query = resolve(DateRangeFilter::class)->apply(dateRangeEvents(), ['from' => null, 'to' => ''], 'created_at', mode: $mode);

    expect($query->toRawSql())->toBe('select * from "events"');
})->with([DateRangeFilter::ModeDate, DateRangeFilter::ModeDateTime]);

it('indicates each active date', function () {
    $filter = resolve(DateRangeFilter::class);

    expect($filter->indicators(['from' => '2026-10-01 08:00:00', 'to' => '2026-10-05']))->toBe(['From: 2026-10-01', 'To: 2026-10-05'])
        ->and($filter->indicators(['to' => '2026-10-05']))->toBe(['To: 2026-10-05'])
        ->and($filter->indicators([]))->toBe([]);
});

it('shows its indicators on the filter unless told not to', function () {
    $indicateUsing = fn (bool $show) => (new ReflectionProperty(Filament\Tables\Filters\Filter::class, 'indicateUsing'))
        ->getValue(resolve(DateRangeFilter::class)->make(name: 'range', label: 'Range', column: 'created_at', showIndicators: $show));

    expect($indicateUsing(true)(['from' => '2026-10-01']))->toBe(['From: 2026-10-01'])
        // Without its own, the filter keeps Filament's, which shows the filter's label.
        ->and((new ReflectionFunction($indicateUsing(false)))->getFileName())->toEndWith('Filters/Filter.php');
});
