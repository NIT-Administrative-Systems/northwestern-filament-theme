<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\CodeEditor;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Wizard;
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

    protected static ?int $navigationSort = 999;

    /** @var array<string, mixed>|null */
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
            'date_input' => '2026-01-15',
            'datetime_input' => '2026-01-15 09:00:00',
            'time_input' => '09:00',
            'checkbox_list' => ['laravel', 'filament'],
            'toggle_buttons' => 'option_1',
            'repeater_items' => [
                ['name' => 'First Item', 'description' => 'Description for first item'],
                ['name' => 'Second Item', 'description' => 'Description for second item'],
            ],
            'builder_blocks' => [
                ['type' => 'paragraph', 'data' => ['content' => 'This is a paragraph block built with Filament Builder.']],
                ['type' => 'heading', 'data' => ['content' => 'A Heading Block', 'level' => 'h2']],
            ],
            'key_value' => [
                'name' => 'Northwestern University',
                'location' => 'Evanston, IL',
                'founded' => '1851',
            ],
            'markdown_editor' => "## Markdown Preview\n\nThis is **bold** and this is *italic* text.",
            'code_editor' => "<?php\n\nnamespace App;\n\nclass Example\n{\n    public function greet(): string\n    {\n        return 'Hello, Northwestern!';\n    }\n}",
            'fieldset_name' => 'Jane Cooper',
            'fieldset_email' => 'jane@northwestern.edu',
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
                        Grid::make(2)->schema([
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
                            Select::make('disabled_select')
                                ->label('Disabled Select')
                                ->options(['locked' => 'Locked Value'])
                                ->default('locked')
                                ->disabled(),
                        ]),
                        Radio::make('radio_input')
                            ->label('Radio Buttons')
                            ->options([
                                'option_1' => 'First Choice',
                                'option_2' => 'Second Choice',
                                'option_3' => 'Third Choice',
                            ]),
                        Grid::make(3)->schema([
                            Checkbox::make('checkbox_input')
                                ->label('Checkbox'),
                            Toggle::make('toggle_input')
                                ->label('Toggle Switch'),
                            Toggle::make('toggle_disabled')
                                ->label('Disabled Toggle')
                                ->disabled()
                                ->default(true),
                        ]),
                    ]),

                Section::make('Checkbox List & Toggle Buttons')
                    ->description('Multi-choice components')
                    ->columns(2)
                    ->schema([
                        CheckboxList::make('checkbox_list')
                            ->label('Checkbox List')
                            ->options([
                                'laravel' => 'Laravel',
                                'filament' => 'Filament',
                                'livewire' => 'Livewire',
                                'alpine' => 'Alpine.js',
                                'tailwind' => 'Tailwind CSS',
                            ])
                            ->searchable()
                            ->columns(2),
                        ToggleButtons::make('toggle_buttons')
                            ->label('Toggle Buttons')
                            ->options([
                                'option_1' => 'Draft',
                                'option_2' => 'Published',
                                'option_3' => 'Archived',
                            ])
                            ->icons([
                                'option_1' => 'heroicon-o-pencil',
                                'option_2' => 'heroicon-o-check-circle',
                                'option_3' => 'heroicon-o-archive-box',
                            ])
                            ->colors([
                                'option_1' => 'warning',
                                'option_2' => 'success',
                                'option_3' => 'gray',
                            ]),
                    ]),

                Section::make('Date & Time Inputs')
                    ->description('Date pickers, time pickers, and datetime pickers')
                    ->columns(3)
                    ->schema([
                        DatePicker::make('date_input')
                            ->native(false)
                            ->label('Date Picker'),
                        TimePicker::make('time_input')
                            ->label('Time Picker'),
                        DateTimePicker::make('datetime_input')
                            ->label('Date/Time Picker'),
                    ]),

                Section::make('Rich Text & Editors')
                    ->schema([
                        Textarea::make('textarea_input')
                            ->label('Textarea')
                            ->rows(3),
                        RichEditor::make('rich_editor')
                            ->label('Rich Editor')
                            ->default('<p>Rich text with <strong>bold</strong> and <em>italic</em> content.</p>'),
                        MarkdownEditor::make('markdown_editor')
                            ->label('Markdown Editor'),
                        CodeEditor::make('code_editor')
                            ->label('Code Editor'),
                    ]),

                Section::make('Miscellaneous Inputs')
                    ->columns(2)
                    ->schema([
                        ColorPicker::make('color_input')
                            ->label('Color Picker')
                            ->default('#4E2A84'),
                        TagsInput::make('tags_input')
                            ->label('Tags Input'),
                        FileUpload::make('file_input')
                            ->label('File Upload'),
                        Placeholder::make('placeholder_display')
                            ->label('Placeholder')
                            ->content('This is a read-only placeholder field — useful for displaying computed values.'),
                    ]),

                Section::make('Key-Value')
                    ->description('Editable key-value pair input')
                    ->schema([
                        KeyValue::make('key_value')
                            ->label('Key-Value Pairs')
                            ->keyLabel('Property')
                            ->valueLabel('Value')
                            ->addActionLabel('Add Row')
                            ->reorderable(),
                    ]),

                Section::make('Repeater')
                    ->description('Dynamic repeatable field groups')
                    ->schema([
                        Repeater::make('repeater_items')
                            ->label('Repeater')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Item Name')
                                    ->required(),
                                Textarea::make('description')
                                    ->label('Description')
                                    ->rows(2),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->reorderable()
                            ->addActionLabel('Add Item')
                            ->defaultItems(0),
                    ]),

                Section::make('Builder')
                    ->description('Block-based content builder')
                    ->schema([
                        Builder::make('builder_blocks')
                            ->label('Content Builder')
                            ->blocks([
                                Builder\Block::make('heading')
                                    ->label('Heading')
                                    ->icon('heroicon-o-bookmark')
                                    ->schema([
                                        TextInput::make('content')
                                            ->label('Heading Text')
                                            ->required(),
                                        Select::make('level')
                                            ->label('Level')
                                            ->options([
                                                'h2' => 'H2',
                                                'h3' => 'H3',
                                                'h4' => 'H4',
                                            ])
                                            ->default('h2'),
                                    ])
                                    ->columns(2),
                                Builder\Block::make('paragraph')
                                    ->label('Paragraph')
                                    ->icon('heroicon-o-bars-3-bottom-left')
                                    ->schema([
                                        Textarea::make('content')
                                            ->label('Text')
                                            ->rows(3)
                                            ->required(),
                                    ]),
                                Builder\Block::make('image')
                                    ->label('Image')
                                    ->icon('heroicon-o-photo')
                                    ->schema([
                                        FileUpload::make('url')
                                            ->label('Image')
                                            ->image(),
                                        TextInput::make('alt')
                                            ->label('Alt Text'),
                                    ]),
                            ])
                            ->collapsible()
                            ->reorderable()
                            ->addActionLabel('Add Block'),
                    ]),

                Fieldset::make('Fieldset Example')
                    ->schema([
                        TextInput::make('fieldset_name')
                            ->label('Full Name')
                            ->required(),
                        TextInput::make('fieldset_email')
                            ->label('Email Address')
                            ->email()
                            ->required(),
                    ])
                    ->columns(2),

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

                Wizard::make([
                    Wizard\Step::make('Account')
                        ->icon('heroicon-o-user')
                        ->description('Basic account info')
                        ->schema([
                            TextInput::make('wizard_name')
                                ->label('Full Name')
                                ->default('Jane Cooper'),
                            TextInput::make('wizard_email')
                                ->label('Email')
                                ->email()
                                ->default('jane@northwestern.edu'),
                        ]),
                    Wizard\Step::make('Preferences')
                        ->icon('heroicon-o-cog-6-tooth')
                        ->description('Customize your experience')
                        ->schema([
                            Select::make('wizard_department')
                                ->label('Department')
                                ->options([
                                    'cs' => 'Computer Science',
                                    'ee' => 'Electrical Engineering',
                                    'mech' => 'Mechanical Engineering',
                                ])
                                ->default('cs'),
                            Toggle::make('wizard_notifications')
                                ->label('Enable Notifications')
                                ->default(true),
                        ]),
                    Wizard\Step::make('Review')
                        ->icon('heroicon-o-check-circle')
                        ->description('Confirm your details')
                        ->schema([
                            Placeholder::make('wizard_review')
                                ->content('Review your information and submit.'),
                        ]),
                ]),
            ])
            ->statePath('formData');
    }

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [];
    }

    public function triggerNotifications(): void
    {
        Notification::make()->title('Success Notification')->body('This is a success message.')->success()->send();
        Notification::make()->title('Danger Notification')->body('Something went wrong.')->danger()->send();
        Notification::make()->title('Warning Notification')->body('Be careful about this.')->warning()->send();
        Notification::make()->title('Info Notification')->body('Here is some information.')->info()->send();
    }
}
