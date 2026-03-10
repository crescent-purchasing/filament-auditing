<?php

namespace CrescentPurchasing\FilamentAuditing\Concerns;

use Closure;

trait HasUserSchema
{
    /** @var \Filament\Schemas\Components\Component[]|Closure */
    protected array | Closure $userSchema = [];

    /**
     * @return \Filament\Schemas\Components\Component[]
     */
    public function getUserSchema(): array
    {
        return $this->evaluate($this->userSchema);
    }

    /**
     * @param  \Filament\Schemas\Components\Component[]|Closure  $userSchema
     * @return $this
     */
    public function userSchema(array | Closure $userSchema): static
    {
        $this->userSchema = $userSchema;

        return $this;
    }
}
