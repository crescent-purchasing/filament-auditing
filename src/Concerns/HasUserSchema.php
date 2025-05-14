<?php

namespace CrescentPurchasing\FilamentAuditing\Concerns;

use Closure;
use Filament\Forms\Components\Component;

trait HasUserSchema
{
    /** @var Component[]|Closure */
    protected array | Closure $userSchema = [];

    /**
     * @return Component[]
     */
    public function getUserSchema(): array
    {
        return $this->evaluate($this->userSchema);
    }

    /**
     * @param  Component[]|Closure  $userSchema
     * @return $this
     */
    public function userSchema(array | Closure $userSchema): static
    {
        $this->userSchema = $userSchema;

        return $this;
    }
}
