<?php

declare(strict_types=1);

namespace Northwestern\FilamentTheme\Filters;

use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Builds a table filter with From and To dates, its query and its indicators.
 *
 * Both dates use the browser's own date input. Filament's custom picker nests its input inside
 * a button, which screen readers can't operate and axe reports as a nested interactive control.
 *
 * ```php
 * ->filters([
 *     resolve(DateRangeFilter::class)->make(name: 'created_at_range', label: 'Date Range', column: 'created_at'),
 * ])
 * ```
 */
class DateRangeFilter
{
    /** Compare the column's date only. */
    public const string ModeDate = 'date';

    /** Compare the column's timestamp, from the start of the From day to the end of the To day. */
    public const string ModeDateTime = 'datetime';

    public function make(
        string $name,
        string $label,
        string $column,
        string $fromField = 'from',
        string $untilField = 'to',
        string $mode = self::ModeDateTime,
        ?Heroicon $icon = null,
        bool $limitUntilToToday = false,
        bool $showIndicators = true,
        ?string $fromLabel = null,
        ?string $untilLabel = null,
    ): Filter {
        $fromPicker = DatePicker::make($fromField)
            ->label($fromLabel ?? 'From');

        $untilPicker = DatePicker::make($untilField)
            ->label($untilLabel ?? 'To')
            ->minDate(fn (callable $get): mixed => $get($fromField));

        if ($icon instanceof Heroicon) {
            $fromPicker->prefixIcon($icon);
            $untilPicker->prefixIcon($icon);
        }

        if ($limitUntilToToday) {
            $untilPicker->maxDate(Carbon::today());
        }

        $filter = Filter::make($name)
            ->label($label)
            ->columns(2)
            ->schema([$fromPicker, $untilPicker])
            ->query(fn (Builder $query, array $data): Builder => $this->apply($query, $data, $column, $fromField, $untilField, $mode));

        if ($showIndicators) {
            $filter->indicateUsing(fn (array $data): array => $this->indicators($data, $fromField, $untilField));
        }

        return $filter;
    }

    /**
     * Apply a From and To date range to a query.
     *
     * @template TModel of Model
     *
     * @param  Builder<TModel>  $query
     * @param  array<array-key, mixed>  $data
     * @return Builder<TModel>
     */
    public function apply(
        Builder $query,
        array $data,
        string $column,
        string $fromField = 'from',
        string $untilField = 'to',
        string $mode = self::ModeDateTime,
    ): Builder {
        $from = $this->date($data, $fromField);
        $until = $this->date($data, $untilField);

        if ($mode === self::ModeDate) {
            if ($from !== null) {
                $query->whereDate($column, '>=', $from);
            }

            if ($until !== null) {
                $query->whereDate($column, '<=', $until);
            }

            return $query;
        }

        if ($from !== null) {
            $query->where($column, '>=', Carbon::parse($from)->startOfDay());
        }

        if ($until !== null) {
            $query->where($column, '<=', Carbon::parse($until)->endOfDay());
        }

        return $query;
    }

    /**
     * The active filter's indicator labels.
     *
     * @param  array<array-key, mixed>  $data
     * @return list<string>
     */
    public function indicators(array $data, string $fromField = 'from', string $untilField = 'to'): array
    {
        $indicators = [];
        $from = $this->date($data, $fromField);
        $until = $this->date($data, $untilField);

        if ($from !== null) {
            $indicators[] = 'From: ' . Carbon::parse($from)->toDateString();
        }

        if ($until !== null) {
            $indicators[] = 'To: ' . Carbon::parse($until)->toDateString();
        }

        return $indicators;
    }

    /**
     * A field's date as the form sent it, or null when it's empty.
     *
     * @param  array<array-key, mixed>  $data
     */
    private function date(array $data, string $field): ?string
    {
        $value = $data[$field] ?? null;

        return is_string($value) && $value !== '' ? $value : null;
    }
}
