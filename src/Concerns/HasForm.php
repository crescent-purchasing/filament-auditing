<?php

namespace CrescentPurchasing\FilamentAuditing\Concerns;

use Closure;
use CrescentPurchasing\FilamentAuditing\Filament\AuditForm;
use Filament\Forms\Form;

trait HasForm
{
    protected string | Closure $form = AuditForm::class;

    /**
     * @return class-string<Form>
     */
    public function getForm(): string
    {
        return $this->evaluate($this->form);
    }

    public function form(string | Closure $form): static
    {
        $this->form = $form;

        return $this;
    }

}
