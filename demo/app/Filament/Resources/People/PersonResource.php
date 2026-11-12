<?php

declare(strict_types=1);

namespace App\Filament\Resources\People;

use App\Filament\Resources\People\Pages\ListPeople;
use App\Filament\Resources\People\Pages\ViewPerson;
use App\Models\User;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * People, so the top bar has global search and the
 * role badge wraps in a real table and infolist.
 */
class PersonResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $modelLabel = 'person';

    protected static ?string $pluralModelLabel = 'people';

    protected static ?string $slug = 'people';

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|null|\BackedEnum $navigationIcon = Heroicon::OutlinedUsers;

    /** @return list<string> */
    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email'];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('email'),
                TextColumn::make('role')->badge()->color('primary')->wrap(),
                TextColumn::make('department')->wrap(),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Person')
                ->columns(2)
                ->schema([
                    TextEntry::make('name'),
                    TextEntry::make('email'),
                    TextEntry::make('role')->badge()->color('primary')->wrap(),
                    TextEntry::make('department'),
                ]),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPeople::route('/'),
            'view' => ViewPerson::route('/{record}'),
        ];
    }
}
