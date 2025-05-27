<?php

namespace CrescentPurchasing\FilamentAuditing\Filament\Filters\QueryBuilder;

use CrescentPurchasing\FilamentAuditing\Actions\FormatAuditableType;
use Exception;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\MorphToSelect\Type;
use Filament\Forms\Components\Select;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Illuminate\Config\Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Arr;
use OwenIt\Auditing\Models\Audit;

class AuditUserOperator extends IsRelatedToOperator
{
    public function getTypeColumn(): string
    {
        return $this->getUserPrefix() . '_type';
    }

    public function getValueColumn(): string
    {
        return $this->getUserPrefix() . '_id';
    }

    /**
     * @var class-string<User>[]
     */
    protected array $types = [];

    /**
     * @return class-string<User>[]
     */
    public function getTypes(): array
    {
        return $this->evaluate($this->types);
    }

    /**
     * @param  class-string<User>[]  $types
     * @return $this
     */
    public function types(array $types): static
    {
        $this->types = $types;

        return $this;
    }

    public function getSummary(): string
    {
        /** @var ?class-string<Model> $type */
        $type = $this->getSettings()[$this->getTypeColumn()] ?? null;

        $value = $this->getSettings()[$this->getValueColumn()] ?? null;

        if (! is_subclass_of($type, Model::class)) {
            return '';
        }

        $relationshipLabel = $this->getConstraint()->getAttributeLabel();

        $formattedType = (new FormatAuditableType)($type);

        if ($userTitle = $type::query()->whereKey($value)->value($this->getTitleAttribute())) {
            return __(
                $this->isInverse() ?
                    'filament-auditing::resource.fields.user.summary.value_inverse' :
                    'filament-auditing::resource.fields.user.summary.value_direct',
                [
                    'relationship' => $relationshipLabel,
                    'type' => $formattedType,
                    'value' => $userTitle,
                ],
            );
        }

        return __(
            $this->isInverse() ?
                'filament-auditing::resource.fields.user.summary.type_inverse' :
                'filament-auditing::resource.fields.user.summary.type_direct',
            [
                'relationship' => $relationshipLabel,
                'type' => $formattedType,
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

    /**
     * @param  Builder<Audit>  $query
     * @return Builder<Audit>
     *
     * @throws Exception
     */
    public function apply(Builder $query, string $qualifiedColumn): Builder
    {
        $constraint = $this->getConstraint();

        $userType = $this->getSettings()[$this->getTypeColumn()] ?? null;

        $userValue = $this->getSettings()[$this->getValueColumn()] ?? null;

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

    protected function getUserPrefix(): string
    {
        /** @phpstan-ignore argument.type */
        return $this->evaluate(fn (Repository $config): string => $config->string('audit.user.morph_prefix'));
    }

    /**
     * @return Field[]
     */
    protected function getMorphGridSchema(Grid $component): array
    {

        $types = Arr::mapWithKeys($this->getTypes(), function (string $item) {
            $format = new FormatAuditableType;
            $type = Type::make($item);
            $type->titleAttribute($this->getTitleAttribute());
            $type->label($format($item));

            return [$type->getAlias() => $type];
        });

        $typeLabels = Arr::map($types, fn (Type $type): string => $type->getLabel());

        /**
         * @var ?Type $selectedType
         *
         * @phpstan-ignore argument.type
         */
        $selectedType = $types[$component->evaluate(fn (Get $get): ?string => $get($this->getTypeColumn()))] ?? null;

        $typeSelect = Select::make($this->getValueColumn());
        $typeSelect->label(__('filament-auditing::resource.fields.user.type_label'));
        $typeSelect->options($typeLabels);
        $typeSelect->native($this->isNative());
        $typeSelect->live();
        $typeSelect->afterStateUpdated(function (Set $set) use ($component) {
            $set($this->getTypeColumn(), null);
            $component->callAfterStateUpdated();
        });

        $valueSelect = Select::make($this->getValueColumn());
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
