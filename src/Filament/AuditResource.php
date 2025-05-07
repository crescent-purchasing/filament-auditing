<?php

namespace CrescentPurchasing\FilamentAuditing\Filament;

use CrescentPurchasing\FilamentAuditing\Contracts\AuditContract as Audit;
use CrescentPurchasing\FilamentAuditing\Filament\Actions\Forms\ViewUserAction;
use CrescentPurchasing\FilamentAuditing\FilamentAuditingPlugin;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class AuditResource extends Resource
{
    protected static ?string $recordTitleAttribute = 'title';

    protected static bool $isGloballySearchable = false;

    public static function getModel(): string
    {
        return FilamentAuditingPlugin::get()->getModel();
    }

    public static function getNavigationIcon(): string | Htmlable | null
    {
        return FilamentAuditingPlugin::get()->getNavigationIcon();
    }

    public static function form(Form $form): Form
    {
        $metaTab = Tab::make('Metadata')
            ->inlineLabel()
            ->schema([
                DateTimePicker::make('created_at')
                    ->inlineLabel()
                    ->label('Recorded at'),
                TextInput::make('event'),
                TextInput::make('url'),
                TextInput::make('ip_address'),
                TextInput::make('user_agent'),
                TextInput::make('tags'),
            ]);

        $userTab = Tabs\Tab::make('User Data')
            ->inlineLabel()
            ->hidden(fn (Audit $record) => empty($record->user))
            ->schema([
                Grid::make(1)
                    ->relationship('user')
                    ->schema([
                        TextInput::make('id')
                            ->suffixAction(ViewUserAction::make()),
                        TextInput::make('full_name'),
                        TextInput::make('email'),
                    ]),
            ]);

        $getTabSchema = function (Audit $record, string $type = 'new'): array {
            $keys = array_keys($record->old_values);
            $mappedFields = $record->getModifiedByType($type);

            return FilamentAuditingPlugin::get()->invokeAuditSchema($keys, $mappedFields);
        };

        $oldTab = Tab::make('Old values')
            ->hidden(fn (Audit $record) => empty($record->old_values))
            ->schema(fn (Audit $record): array => $getTabSchema($record, 'old'));

        $newTab = Tab::make('New values')
            ->schema(fn (Audit $record): array => $getTabSchema($record, 'new'));

        return $form->schema([
            Tabs::make('AuditTabs')
                ->contained(false)
                ->columnSpanFull()
                ->schema([$metaTab, $userTab, $oldTab, $newTab]),
        ]);
    }

    public static function table(Table $table): Table
    {

        $actions = [
            ActionGroup::make([
                ViewAction::make(),
                ViewUserAction::make(),
            ]),
        ];

        $columns = [
            Split::make([
                TextColumn::make('id')
                    ->sortable(),
                Stack::make([
                    TextColumn::make('created_at')
                        ->label(__('filament-auditing::resource.table.columns.created_at_since'))
                        ->since(),
                    TextColumn::make('created_at')
                        ->visibleFrom('md')
                        ->label(__('filament-auditing::resource.table.columns.created_at'))
                        ->size(TextColumnSize::ExtraSmall),
                ]),
                Stack::make([
                    TextColumn::make('user.full_name'),
                    TextColumn::make('user.email')
                        ->visibleFrom('md')
                        ->size(TextColumnSize::ExtraSmall),
                ]),
                Stack::make([
                    TextColumn::make('auditable_id')->visibleFrom('md'),
                    TextColumn::make('auditable_type')
                        ->size(TextColumnSize::ExtraSmall),
                ])->hiddenOn(RelationManager::class),
                TextColumn::make('event')
                    ->visibleFrom('md')
                    ->formatStateUsing(fn (string $state): string => Str::headline($state)),
                TextColumn::make('fields')
                    ->extraAttributes(['class' => 'font-mono'])
                    ->visibleFrom('md')
                    ->getStateUsing(function (Audit $record) {
                        return array_keys($record->new_values);
                    }),
            ])->from('md'),
        ];

        $getSearchResults = function (string $search): array {
            $model = FilamentAuditingPlugin::get()->getModel();
            $results = $model::query()
                ->whereLike('auditable_type', '%' . $search . '%')
                ->distinct()
                ->get('auditable_type');

            return $results->pluck('type', 'auditable_type')->toArray();
        };

        $getOptions = function (Repository $config): array {
            $events = $config->array('audit.events');
            $mapEvent = fn (string $item): array => [$item => Str::headline($item)];

            return Arr::mapWithKeys($events, $mapEvent);
        };

        $filters = [
            SelectFilter::make('user')
                ->relationship('owner', 'email')
                ->optionsLimit(8)
                ->searchable(),
            SelectFilter::make('auditable_type')
                ->hiddenOn(RelationManager::class)
                ->optionsLimit(8)
                ->searchable()
                ->getSearchResultsUsing($getSearchResults),
            SelectFilter::make('event')
                ->options($getOptions),
            QueryBuilder::make('timestamp')
                ->constraints([
                    DateConstraint::make('created_at'),
                ]),
        ];

        return $table
            ->actions($actions)
            ->columns($columns)
            ->defaultSort('id', 'desc')
            ->filters($filters)
            ->filtersFormWidth(MaxWidth::ExtraLarge);

    }

    public static function getPages(): array
    {
        return [
            'index' => AuditPage::route('/'),
        ];
    }
}
