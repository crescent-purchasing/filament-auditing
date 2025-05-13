<?php

namespace CrescentPurchasing\FilamentAuditing\Concerns;

use Closure;
use Filament\Resources\Resource as FilamentResource;
use Illuminate\Foundation\Auth\User;

trait HasUsers
{
    /** @var class-string<User>[]|Closure */
    protected array | Closure $users = [];

    /**
     * @return class-string<User>[]
     */
    public function getUsers(): array
    {
        return $this->evaluate($this->users);
    }

    /**
     * @param  class-string<User>[]|Closure  $users
     * @return $this
     */
    public function users(array | Closure $users): static
    {
        $this->users = $users;

        return $this;
    }
}
