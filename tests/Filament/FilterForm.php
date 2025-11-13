<?php

namespace CrescentPurchasing\FilamentAuditing\Tests\Filament;

use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Schemas\Schema;
use CrescentPurchasing\FilamentAuditing\Filament\Filters\QueryBuilder\AuditUserConstraint;
use CrescentPurchasing\FilamentAuditing\Filament\Filters\QueryBuilder\AuditUserOperator;
use CrescentPurchasing\FilamentAuditing\FilamentAuditingPlugin;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

/**
 * @property Schema $form
 */
class FilterForm extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        $constraint = AuditUserConstraint::make('user')
            ->selectable(
                AuditUserOperator::make()
                    ->titleAttribute('email')
                    ->types(FilamentAuditingPlugin::get()->getUsers())
            );

        /** @var AuditUserOperator $operator */
        $operator = $constraint->getOperator('isRelatedTo');

        $operator->constraint($constraint);

        return $schema
            ->components($operator->getFormSchema())
            ->statePath('data');
    }

    public function render(): string
    {
        return <<<'HTML'
        <div>
            {{ $this->form }}
        </div>
        HTML;
    }
}
