<?php

namespace CrescentPurchasing\FilamentAuditing\Tests\Filament;

use CrescentPurchasing\FilamentAuditing\Filament\Filters\QueryBuilder\AuditUserConstraint;
use CrescentPurchasing\FilamentAuditing\Filament\Filters\QueryBuilder\AuditUserOperator;
use CrescentPurchasing\FilamentAuditing\FilamentAuditingPlugin;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;

/**
 * @property Form $form
 */
class FilterForm extends Component implements HasForms
{
    use InteractsWithForms;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
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

        return $form
            ->schema($operator->getFormSchema())
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
