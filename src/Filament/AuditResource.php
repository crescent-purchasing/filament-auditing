<?php

namespace CrescentPurchasing\FilamentAuditing\Filament;

use CrescentPurchasing\FilamentAuditing\Actions\FormatAuditableType;
use CrescentPurchasing\FilamentAuditing\Actions\FormatEvent;
use CrescentPurchasing\FilamentAuditing\Actions\GetAuditable;
use CrescentPurchasing\FilamentAuditing\Actions\GetAuditSchema;
use CrescentPurchasing\FilamentAuditing\Actions\GetModifiedFields;
use CrescentPurchasing\FilamentAuditing\Actions\GetUser;
use CrescentPurchasing\FilamentAuditing\Actions\GetUserSchema;
use CrescentPurchasing\FilamentAuditing\Filament\Actions\Tables\RestoreAuditAction;
use CrescentPurchasing\FilamentAuditing\Filament\Actions\Tables\ViewAuditableAction;
use CrescentPurchasing\FilamentAuditing\Filament\Actions\Tables\ViewAuditAction;
use CrescentPurchasing\FilamentAuditing\Filament\Actions\Tables\ViewUserAction;
use CrescentPurchasing\FilamentAuditing\Filament\Filters\QueryBuilder\AuditUserConstraint;
use CrescentPurchasing\FilamentAuditing\Filament\Filters\QueryBuilder\AuditUserOperator;
use CrescentPurchasing\FilamentAuditing\Filament\RelationManagers\AuditsRelationManager;
use CrescentPurchasing\FilamentAuditing\Filament\RelationManagers\OwnedAuditsRelationManager;
use CrescentPurchasing\FilamentAuditing\FilamentAuditingPlugin;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Resources\Resource as FilamentResource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\SelectConstraint;
use Filament\Tables\Table;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use OwenIt\Auditing\Models\Audit;

class AuditResource extends FilamentResource
{
    protected static bool $isGloballySearchable = false;

    public static function getModel(): string
    {
        return FilamentAuditingPlugin::get()->getModel();
    }

    public static function getNavigationGroup(): ?string
    {
        return FilamentAuditingPlugin::get()->getNavigationGroup();
    }

    public static function getNavigationIcon(): string | Htmlable | null
    {
        return FilamentAuditingPlugin::get()->getNavigationIcon();
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-auditing::resource.title');
    }

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
            'timestamp' => $record->created_at, // @phpstan-ignore  property.notFound
        ]);
    }

    public static function form(Form $form): Form
    {
        $metaTab = Tab::make(__('filament-auditing::resource.tabs.meta'))
            ->schema([
                Grid::make([
                    'md' => 2,
                ])->schema([
                    TextInput::make('event')
                        ->label(__('filament-auditing::resource.fields.event')),
                    DateTimePicker::make('created_at')
                        ->label(__('filament-auditing::resource.fields.created_at')),
                    TextInput::make('url')
                        ->label(__('filament-auditing::resource.fields.url')),
                    TextInput::make('ip_address')
                        ->label(__('filament-auditing::resource.fields.ip_address')),
                    Textarea::make('user_agent')
                        ->rows(3)
                        ->label(__('filament-auditing::resource.fields.user_agent')),
                    Textarea::make('tags')
                        ->rows(3)
                        ->label(__('filament-auditing::resource.fields.tags')),
                ]),
            ]);

        $userTab = Tabs\Tab::make(__('filament-auditing::resource.tabs.user'))
            ->hidden(function (Audit $record, HasForms $livewire): bool {
                if ($livewire instanceof OwnedAuditsRelationManager) {
                    return true;
                }

                return empty($record->user);

            })
            ->schema([
                Grid::make(1)
                    ->relationship('user')
                    ->schema(fn (GetUserSchema $userSchema): array => $userSchema()),
            ]);

        $oldTab = Tab::make(__('filament-auditing::resource.tabs.old'))
            ->hidden(fn (Audit $record) => empty($record->old_values))
            ->schema(function (Audit $record, GetAuditSchema $schema, GetModifiedFields $fields): array {
                return $schema($fields($record, true));
            });

        $newTab = Tab::make(__('filament-auditing::resource.tabs.new'))
            ->schema(function (Audit $record, GetAuditSchema $schema, GetModifiedFields $fields): array {
                return $schema($fields($record));
            });

        return $form->schema([
            Tabs::make(__('filament-auditing::resource.tabs.label'))
                ->contained(false)
                ->columnSpanFull()
                ->schema([$metaTab, $userTab, $oldTab, $newTab]),
        ]);
    }

    public static function table(Table $table): Table
    {

        $table->actions(static::getTableActions());

        $table->columns(static::getTableColumns());

        $table->defaultSort('id', 'desc');

        $table->filters(static::getTableFilters());

        $table->filtersFormWidth(MaxWidth::TwoExtraLarge);

        $table->filtersLayout(FiltersLayout::Modal);

        return $table;

    }

    public static function getPages(): array
    {
        return [
            'index' => ManageAudits::route('/'),
        ];
    }

    protected static function getTableActions(): array
    {
        return [
            RestoreAuditAction::make(),
            ActionGroup::make([
                ViewAuditAction::make(),
                ViewAuditableAction::make(),
                ViewUserAction::make(),
            ]),
        ];
    }

    protected static function getTableColumns(): array
    {
        return [
            TextColumn::make('id')
                ->label(__('filament-auditing::resource.fields.id'))
                ->sortable(),
            TextColumn::make('created_at')
                ->label(__('filament-auditing::resource.fields.created_at_since'))
                ->dateTimeTooltip()
                ->since(),
            TextColumn::make('user.email')
                ->label(__('filament-auditing::resource.fields.user.label'))
                ->tooltip(fn (Audit $record, GetUser $getUser): ?string => $getUser($record)->getKey())
                ->url(fn (Audit $record, GetUser $getUser): ?string => $getUser->url($record))
                ->hiddenOn(OwnedAuditsRelationManager::class),
            TextColumn::make('auditable_type')
                ->label(__('filament-auditing::resource.fields.auditable_type'))
                ->tooltip(fn (Audit $record, GetAuditable $getAuditable): ?string => $getAuditable($record)->getKey())
                ->url(fn (Audit $record, GetAuditable $getAuditable): ?string => $getAuditable->url($record))
                ->hiddenOn(AuditsRelationManager::class),
            TextColumn::make('event')
                ->label(__('filament-auditing::resource.fields.event'))
                ->visibleFrom('md')
                ->formatStateUsing(fn (string $state, FormatEvent $event): string => $event($state)),
            TextColumn::make('fields')
                ->label(__('filament-auditing::resource.fields.audited_fields'))
                ->extraAttributes(['class' => 'font-mono'])
                ->visibleFrom('md')
                ->getStateUsing(function (Audit $record) {
                    return array_keys($record->new_values);
                }),
        ];
    }

    protected static function getTableFilters(): array
    {
        $getAuditableTypeSearchResults = function (string $search): array {
            $model = FilamentAuditingPlugin::get()->getModel();
            $results = $model::query()
                ->whereLike('auditable_type', '%' . $search . '%')
                ->distinct()
                ->get('auditable_type');

            return $results->pluck('type', 'auditable_type')->toArray();
        };

        $getEventOptions = function (Repository $config, FormatEvent $event): array {
            $events = $config->array('audit.events');
            $mapEvent = fn (string $item): array => [$item => $event($item)];

            return Arr::mapWithKeys($events, $mapEvent);
        };

        return [
            QueryBuilder::make('timestamp')
                ->label(__('filament-auditing::resource.fields.query'))
                ->constraints([
                    AuditUserConstraint::make('user')
                        ->selectable(
                            AuditUserOperator::make()
                                ->titleAttribute('email')
                                ->types(FilamentAuditingPlugin::get()->getUsers())
                        ),
                    SelectConstraint::make('auditable_type')
                        ->label(__('filament-auditing::resource.fields.auditable_type'))
                        ->searchable()
                        ->getOptionLabelUsing(function (?string $value, FormatAuditableType $auditableType): string {
                            return $auditableType($value);
                        })
                        ->getSearchResultsUsing($getAuditableTypeSearchResults)
                        ->optionsLimit(7),
                    SelectConstraint::make('event')
                        ->label(__('filament-auditing::resource.fields.event'))
                        ->options($getEventOptions),
                    DateConstraint::make('created_at')
                        ->label(__('filament-auditing::resource.fields.created_at')),
                ]),
        ];
    }
}
