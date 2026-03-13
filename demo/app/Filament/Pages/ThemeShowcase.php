<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

/**
 * @property-read Schema $form
 */
class ThemeShowcase extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.theme-showcase';

    protected static ?string $title = 'Theme Showcase';

    protected static ?string $navigationLabel = 'Theme Showcase';

    protected static ?string $slug = 'theme-showcase';

    protected static string|null|\BackedEnum $navigationIcon = Heroicon::OutlinedPaintBrush;

    public ?array $formData = [];

    public function mount(): void
    {
        $this->form->fill([
            'text_input' => 'Sample text',
            'email_input' => 'user@northwestern.edu',
            'select_input' => 'option_1',
            'textarea_input' => 'This is a textarea with some sample content for testing.',
            'tags_input' => ['Laravel', 'Filament', 'Northwestern'],
            'toggle_input' => true,
            'checkbox_input' => true,
            'radio_input' => 'option_1',
            'date_input' => now()->format('Y-m-d'),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Text Inputs')
                    ->description('Various text input states and variants')
                    ->columns(2)
                    ->schema([
                        TextInput::make('text_input')
                            ->label('Default Text Input'),
                        TextInput::make('email_input')
                            ->label('Email Input')
                            ->email()
                            ->prefixIcon('heroicon-o-envelope'),
                        TextInput::make('disabled_input')
                            ->label('Disabled Input')
                            ->default('Cannot edit')
                            ->disabled(),
                        TextInput::make('placeholder_input')
                            ->label('With Placeholder')
                            ->placeholder('Enter something...'),
                        TextInput::make('required_input')
                            ->label('Required Input')
                            ->required(),
                        TextInput::make('helper_input')
                            ->label('With Helper Text')
                            ->helperText('This is helper text below the input'),
                        TextInput::make('hint_input')
                            ->label('With Hint')
                            ->hint('Hint text')
                            ->hintIcon('heroicon-o-information-circle'),
                        TextInput::make('prefix_suffix')
                            ->label('Prefix & Suffix')
                            ->prefix('$')
                            ->suffix('.00'),
                    ]),

                Section::make('Select & Choice Inputs')
                    ->description('Dropdowns, radios, checkboxes, toggles')
                    ->schema([
                        Select::make('select_input')
                            ->label('Select')
                            ->options([
                                'option_1' => 'Option One',
                                'option_2' => 'Option Two',
                                'option_3' => 'Option Three',
                            ]),
                        Select::make('multi_select')
                            ->label('Multi Select')
                            ->multiple()
                            ->options([
                                'laravel' => 'Laravel',
                                'filament' => 'Filament',
                                'livewire' => 'Livewire',
                                'alpine' => 'Alpine.js',
                            ]),
                        Select::make('searchable_select')
                            ->label('Searchable Select')
                            ->searchable()
                            ->options([
                                'evanston' => 'Evanston Campus',
                                'chicago' => 'Chicago Campus',
                                'doha' => 'Qatar Campus',
                            ]),
                        Radio::make('radio_input')
                            ->label('Radio Buttons')
                            ->options([
                                'option_1' => 'First Choice',
                                'option_2' => 'Second Choice',
                                'option_3' => 'Third Choice',
                            ]),
                        Checkbox::make('checkbox_input')
                            ->label('Checkbox'),
                        Toggle::make('toggle_input')
                            ->label('Toggle Switch'),
                        Toggle::make('toggle_disabled')
                            ->label('Disabled Toggle')
                            ->disabled()
                            ->default(true),
                    ]),

                Section::make('Rich Text & Textarea')
                    ->schema([
                        Textarea::make('textarea_input')
                            ->label('Textarea')
                            ->rows(3),
                        RichEditor::make('rich_editor')
                            ->label('Rich Editor')
                            ->default('<p>Rich text with <strong>bold</strong> and <em>italic</em> content.</p>'),
                    ]),

                Section::make('Miscellaneous Inputs')
                    ->columns(2)
                    ->schema([
                        DatePicker::make('date_input')
                            ->label('Date Picker'),
                        ColorPicker::make('color_input')
                            ->label('Color Picker')
                            ->default('#4E2A84'),
                        TagsInput::make('tags_input')
                            ->label('Tags Input'),
                        FileUpload::make('file_input')
                            ->label('File Upload'),
                    ]),

                Tabs::make('Sample Tabs')
                    ->tabs([
                        Tabs\Tab::make('General')
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                TextInput::make('tab_general_name')
                                    ->label('Name')
                                    ->default('General Tab Content'),
                            ]),
                        Tabs\Tab::make('Details')
                            ->icon('heroicon-o-document-text')
                            ->badge(3)
                            ->schema([
                                TextInput::make('tab_details_value')
                                    ->label('Value')
                                    ->default('Details Tab Content'),
                            ]),
                        Tabs\Tab::make('Settings')
                            ->icon('heroicon-o-adjustments-horizontal')
                            ->schema([
                                Toggle::make('tab_settings_toggle')
                                    ->label('Enable Feature')
                                    ->default(true),
                            ]),
                    ]),
            ])
            ->statePath('formData');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('primary_action')
                ->label('Primary Action')
                ->icon(Heroicon::OutlinedPlus)
                ->action(fn () => Notification::make()->title('Primary action clicked')->success()->send()),
            Action::make('danger_action')
                ->label('Danger Action')
                ->color('danger')
                ->icon(Heroicon::OutlinedTrash)
                ->requiresConfirmation()
                ->action(fn () => null),
            Action::make('warning_action')
                ->label('Warning')
                ->color('warning')
                ->action(fn () => null),
            Action::make('success_action')
                ->label('Success')
                ->color('success')
                ->action(fn () => null),
            Action::make('info_action')
                ->label('Info')
                ->color('info')
                ->action(fn () => null),
            Action::make('gray_action')
                ->label('Gray')
                ->color('gray')
                ->action(fn () => null),
            ActionGroup::make([
                Action::make('group_edit')
                    ->label('Edit')
                    ->icon(Heroicon::OutlinedPencil),
                Action::make('group_duplicate')
                    ->label('Duplicate')
                    ->icon(Heroicon::OutlinedDocumentDuplicate),
                Action::make('group_delete')
                    ->label('Delete')
                    ->icon(Heroicon::OutlinedTrash)
                    ->color('danger'),
            ])->label('More Actions'),
        ];
    }

    public function triggerNotifications(): void
    {
        Notification::make()->title('Success Notification')->body('This is a success message.')->success()->send();
        Notification::make()->title('Danger Notification')->body('Something went wrong.')->danger()->send();
        Notification::make()->title('Warning Notification')->body('Be careful about this.')->warning()->send();
        Notification::make()->title('Info Notification')->body('Here is some information.')->info()->send();
    }
}
