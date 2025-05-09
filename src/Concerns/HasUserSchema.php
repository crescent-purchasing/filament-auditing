<?php

namespace CrescentPurchasing\FilamentAuditing\Concerns;

use Closure;
use CrescentPurchasing\FilamentAuditing\Contracts\UserSchemaContract;
use CrescentPurchasing\FilamentAuditing\Filament\Schemas\UserSchema;
use Filament\Forms\Components\Component;

trait HasUserSchema
{
    /** @var class-string<UserSchemaContract>|Closure */
    protected string | Closure $userSchema = UserSchema::class;

    /** @var Component[]|Closure */
    protected array | Closure $extraUserFields = [];

    /**
     * @return class-string<UserSchemaContract>
     */
    public function getUserSchema(): string
    {
        return $this->evaluate($this->userSchema);
    }

    /**
     * @return Component[]
     */
    public function makeUserSchema(): array
    {
        return $this->getUserSchema()::make();
    }

    /**
     * @param  class-string<UserSchemaContract>|Closure  $userSchema
     * @return $this
     */
    public function userSchema(string | Closure $userSchema): static
    {
        $this->userSchema = $userSchema;

        return $this;
    }

    /**
     * @return Component[]
     */
    public function getExtraUserFields(): array
    {
        return $this->evaluate($this->extraUserFields);
    }

    /**
     * @param  Component[]|Closure  $extraUserFields
     * @return $this
     */
    public function extraUserFields(array | Closure $extraUserFields): static
    {
        $this->extraUserFields = $extraUserFields;

        return $this;
    }
}
