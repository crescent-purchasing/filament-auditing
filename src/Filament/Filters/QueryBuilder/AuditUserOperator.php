<?php

namespace CrescentPurchasing\FilamentAuditing\Filament\Filters\QueryBuilder;

use CrescentPurchasing\FilamentAuditing\Actions\FormatAuditableType;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\MorphToSelect\Type;
use Filament\Forms\Components\Select;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class AuditUserOperator extends IsRelatedToOperator
{
    protected string $typeColumn = 'user_type';

    protected string $valueColumn = 'user_id';

    protected array $types = [];

    public function getTypes()
    {
        return $this->evaluate($this->types);
    }

    public function types(array $types): static
    {
        $this->types = $types;

        return $this;
    }

    public function getSummary(): string
    {
        $constraint = $this->getConstraint();

        /** @var ?class-string<Model> $type */
        $type = $this->getSettings()[$this->typeColumn];

        $value = Arr::wrap($this->getSettings()[$this->valueColumn]);

        $user = '';

        if (is_subclass_of($type, Model::class)) {
            $user = $type::query()->whereKey($value)->value($this->getTitleAttribute());
        }

        $formattedType = (new FormatAuditableType)($type);

        if (! $user) {
            return __(
                $this->isInverse() ?
                    'filament-auditing::resource.fields.user.summary.type_inverse' :
                    'filament-auditing::resource.fields.user.summary.type_direct',
                [
                    'relationship' => $constraint->getAttributeLabel(),
                    'type' => $formattedType,
                ],
            );
        }

        return __(
            $this->isInverse() ?
                'filament-auditing::resource.fields.user.summary.value_inverse' :
                'filament-auditing::resource.fields.user.summary.value_direct',
            [
                'relationship' => $constraint->getAttributeLabel(),
                'type' => $formattedType,
                'value' => $user,
            ],
        );
    }

    public function getFormSchema(): array
    {
        $morphGrid = Grid::make(3);
        $morphGrid->columnSpanFull();
        $morphGrid->schema($this->getMorphGridSchema(...));

        return [$morphGrid];
    }

    public function apply(Builder $query, string $qualifiedColumn): Builder
    {
        $constraint = $this->getConstraint();

        $userType = $this->getSettings()[$this->typeColumn];

        $userValue = $this->getSettings()[$this->valueColumn];

        if (! $userType) {
            return $query;
        }

        return $query->{$this->isInverse() ? 'whereDoesntHaveMorph' : 'whereHasMorph'}(
            $constraint->getRelationshipName(),
            $userType,
            function (Builder $query) use ($userValue): void {
                if ($userValue) {
                    $query->whereKey($userValue);
                }
            },
        );
    }

    protected function getMorphGridSchema(Grid $component): array
    {
        $types = Arr::mapWithKeys($this->getTypes(), function (string $item) {
            $type = Type::make($item);
            $type->titleAttribute($this->getTitleAttribute());

            return [$type->getAlias() => $type];
        });

        $typeLabels = Arr::map($types, fn (Type $type): string => $type->getLabel());

        /**
         * @var ?Type $selectedType
         *
         * @phpstan-ignore argument.type
         */
        $selectedType = $types[$component->evaluate(fn (Get $get): ?string => $get($this->typeColumn))] ?? null;

        $typeSelect = Select::make($this->typeColumn);
        $typeSelect->label(__('filament-auditing::resource.fields.user.type_label'));
        $typeSelect->options($typeLabels);
        $typeSelect->native($this->isNative());
        $typeSelect->live();
        $typeSelect->afterStateUpdated(function (Set $set) use ($component) {
            $set($this->valueColumn, null);
            $component->callAfterStateUpdated();
        });

        $valueSelect = Select::make($this->valueColumn);
        $valueSelect->columnSpan(2);
        $valueSelect->label($selectedType?->getLabel());
        $valueSelect->searchable();
        $valueSelect->getSearchResultsUsing($selectedType?->getSearchResultsUsing);
        $valueSelect->getOptionLabelUsing($selectedType?->getOptionLabelUsing);
        $valueSelect->native();
        $valueSelect->hidden(blank($selectedType));
        $valueSelect->dehydratedWhenHidden();
        $valueSelect->optionsLimit(7);

        return [$typeSelect, $valueSelect];
    }
}
