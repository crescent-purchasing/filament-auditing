<?php

namespace CrescentPurchasing\FilamentAuditing\Filament;

use CrescentPurchasing\FilamentAuditing\Filament\Actions\Tables\ViewUserAction;
use CrescentPurchasing\FilamentAuditing\FilamentAuditingPlugin;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions;
use Filament\Tables\Columns;
use Filament\Tables\Filters;
use Filament\Tables\Table;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use OwenIt\Auditing\Contracts\Audit;

class AuditTable extends Table
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->actions($this->getTableActions());

        $this->columns($this->getTableColumns());

        $this->defaultSort('id', 'desc');

        $this->filters($this->getTableFilters());

        $this->filtersFormWidth(MaxWidth::ExtraLarge);
    }

    private function getTableActions(): array
    {
        return [
            Actions\ActionGroup::make([
                Actions\ViewAction::make(),
                ViewUserAction::make(),
            ]),
        ];
    }

    private function getTableColumns(): array
    {
        return [
            Columns\Layout\Split::make([
                Columns\Layout\Stack::make([
                    Columns\TextColumn::make('created_at')
                        ->label(__('filament-auditing::table.columns.created_at_since'))
                        ->since(),
                    Columns\TextColumn::make('created_at')
                        ->visibleFrom('md')
                        ->label(__('filament-auditing::table.columns.created_at'))
                        ->size(Columns\TextColumn\TextColumnSize::ExtraSmall),
                ]),
                Columns\Layout\Stack::make([
                    Columns\TextColumn::make('user.full_name'),
                    Columns\TextColumn::make('user.email')
                        ->visibleFrom('md')
                        ->size(Columns\TextColumn\TextColumnSize::ExtraSmall),
                ]),
                Columns\Layout\Stack::make([
                    Columns\TextColumn::make('auditable_id')->visibleFrom('md'),
                    Columns\TextColumn::make('auditable_type')
                        ->size(Columns\TextColumn\TextColumnSize::ExtraSmall),
                ])->hiddenOn(RelationManager::class),
                Columns\TextColumn::make('event')
                    ->visibleFrom('md')
                    ->formatStateUsing(fn (string $state): string => Str::headline($state)),
                Columns\TextColumn::make('fields')
                    ->extraAttributes(['class' => 'font-mono'])
                    ->visibleFrom('md')
                    ->getStateUsing(function (Audit $record) {
                        return array_keys($record->new_values);
                    }),
            ])->from('md'),
        ];
    }

    private function getTableFilters(): array
    {
        return [
            Filters\SelectFilter::make('user')
                ->relationship('owner', 'email')
                ->optionsLimit(8)
                ->searchable(),
            Filters\SelectFilter::make('auditable_type')
                ->hiddenOn(RelationManager::class)
                ->optionsLimit(8)
                ->searchable()
                ->getSearchResultsUsing(function (string $search): array {
                    $model = FilamentAuditingPlugin::get()->getModel();
                    $results = $model::query()
                        ->whereLike('auditable_type', '%' . $search . '%')
                        ->distinct()
                        ->get('auditable_type');

                    return $results->pluck('type', 'auditable_type')->toArray();
                }),
            Filters\SelectFilter::make('event')
                ->options(fn (Repository $config): array => Arr::mapWithKeys(
                    $config->array('audit.events'),
                    fn ($item): array => [$item => Str::headline($item)]
                )),
            Filters\QueryBuilder::make('timestamp')
                ->constraints([
                    Filters\QueryBuilder\Constraints\DateConstraint::make('created_at'),
                ]),
        ];
    }
}
