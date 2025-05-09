<?php

namespace CrescentPurchasing\FilamentAuditing\Filament;

use CrescentPurchasing\FilamentAuditing\Audit;
use CrescentPurchasing\FilamentAuditing\Filament\Actions\Tables\RestoreAuditAction;
use CrescentPurchasing\FilamentAuditing\Filament\Actions\Tables\ViewAuditableAction;
use CrescentPurchasing\FilamentAuditing\Filament\Actions\Tables\ViewAuditAction;
use CrescentPurchasing\FilamentAuditing\Filament\Actions\Tables\ViewOwnerAction;
use CrescentPurchasing\FilamentAuditing\FilamentAuditingPlugin;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource as FilamentResource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class AuditResource extends FilamentResource
{
    protected static bool $isGloballySearchable = false;

    /**
     * @param  Audit|null  $record
     */
    public static function getRecordTitle(?Model $record): string | Htmlable | null
    {
        $auditable = $record->auditable;

        /** @var class-string<FilamentResource> $resource */
        $resource = filament()->getModelResource($auditable);

        return __('filament-auditing::resource.record_title', [
            'record' => $resource::getRecordTitle($auditable),
            'id' => $auditable->getKey(),
            'timestamp' => $record->created_at,
        ]);
    }

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
        $metaTab = Tab::make(__('filament-auditing::resource.tabs.meta'))
            ->schema([
                DateTimePicker::make('created_at')
                    ->label(__('filament-auditing::resource.fields.created_at'))
                    ->inlineLabel(),
                TextInput::make('event')
                    ->label(__('filament-auditing::resource.fields.event')),
                TextInput::make('url')
                    ->label(__('filament-auditing::resource.fields.url')),
                TextInput::make('ip_address')
                    ->label(__('filament-auditing::resource.fields.ip_address')),
                TextInput::make('user_agent')
                    ->label(__('filament-auditing::resource.fields.user_agent')),
                TextInput::make('tags')
                    ->label(__('filament-auditing::resource.fields.tags')),
            ]);

        $userTab = Tabs\Tab::make(__('filament-auditing::resource.tabs.user'))
            ->hidden(fn (Audit $record) => empty($record->user))
            ->schema([
                Grid::make(1)
                    ->relationship('user')
                    ->schema(FilamentAuditingPlugin::get()->makeUserSchema()),
            ]);

        $getTabSchema = function (Audit $record, string $type = 'new'): array {
            $keys = array_keys($record->old_values);
            $mappedFields = $record->getModifiedByType($type);

            return FilamentAuditingPlugin::get()->makeAuditSchema($keys, $mappedFields);
        };

        $oldTab = Tab::make(__('filament-auditing::resource.tabs.old'))
            ->hidden(fn (Audit $record) => empty($record->old_values))
            ->schema(fn (Audit $record): array => $getTabSchema($record, 'old'));

        $newTab = Tab::make(__('filament-auditing::resource.tabs.new'))
            ->schema(fn (Audit $record): array => $getTabSchema($record, 'new'));

        return $form->schema([
            Tabs::make(__('filament-auditing::resource.tabs.label'))
                ->contained(false)
                ->columnSpanFull()
                ->schema([$metaTab, $userTab, $oldTab, $newTab]),
        ]);
    }

    public static function table(Table $table): Table
    {

        $actions = [
            RestoreAuditAction::make(),
            ActionGroup::make([
                ViewAuditAction::make(),
                ViewAuditableAction::make(),
                ViewOwnerAction::make(),
            ]),
        ];

        $columns = [
            TextColumn::make('id')
                ->label(__('filament-auditing::resource.fields.id'))
                ->sortable(),
            TextColumn::make('created_at')
                ->label(__('filament-auditing::resource.fields.created_at_since'))
                ->dateTimeTooltip()
                ->since(),
            TextColumn::make('user.email')
                ->label(__('filament-auditing::resource.fields.user.email'))
                ->action(ViewOwnerAction::make('viewOwnerColumn')),
            TextColumn::make('auditable_type')
                ->label(__('filament-auditing::resource.fields.auditable_type'))
                ->action(ViewAuditableAction::make('viewAuditableColumn'))
                ->hiddenOn(AuditsRelationManager::class),
            TextColumn::make('event')
                ->label(__('filament-auditing::resource.fields.event'))
                ->visibleFrom('md')
                ->formatStateUsing(fn (string $state): string => Str::headline($state)),
            TextColumn::make('fields')
                ->label(__('filament-auditing::resource.fields.audited_fields'))
                ->extraAttributes(['class' => 'font-mono'])
                ->visibleFrom('md')
                ->getStateUsing(function (Audit $record) {
                    return array_keys($record->new_values);
                }),
        ];

        $getFilterSearchResults = function (string $search): array {
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
                ->label(__('filament-auditing::resource.fields.user.label'))
                ->relationship('owner', 'email')
                ->optionsLimit(8)
                ->searchable(),
            SelectFilter::make('auditable_type')
                ->label(__('filament-auditing::resource.fields.auditable_type'))
                ->hiddenOn(RelationManager::class)
                ->optionsLimit(8)
                ->searchable()
                ->getSearchResultsUsing($getFilterSearchResults),
            SelectFilter::make('event')
                ->label(__('filament-auditing::resource.fields.event'))
                ->options($getOptions),
            QueryBuilder::make('timestamp')
                ->label(__('filament-auditing::resource.fields.query'))
                ->constraints([
                    DateConstraint::make('created_at')
                        ->label(__('filament-auditing::resource.fields.created_at')),
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
            'index' => ManageAudits::route('/'),
        ];
    }
}
