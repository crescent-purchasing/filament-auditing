<?php

namespace CrescentPurchasing\FilamentAuditing\Concerns;

use Closure;
use Filament\Resources\Resource as FilamentResource;
use Illuminate\Foundation\Auth\User;

trait HasUserResource
{
    /** @var class-string<User>|Closure|null  */
    protected string | Closure | null $user = User::class;

    /** @var class-string<FilamentResource>|Closure|null  */
    protected string | Closure | null $userResource = null;

    /**
     * @return class-string<User>|null
     */
    public function getUser(): ?string
    {
        return $this->evaluate($this->user);
    }

    /**
     * @param class-string<User>|Closure|null $user
     * @return $this
     */
    public function user(string | Closure | null $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return class-string<FilamentResource>|null
     */
    public function getUserResource(): ?string
    {

        if ($this->userResource !== null) {
            return $this->evaluate($this->userResource);
        }

        if ($user = $this->getUser()) {
            return filament()->getCurrentPanel()->getModelResource($user);
        }

        return null;

    }

    /**
     * @param class-string<FilamentResource>|Closure|null $userResource
     * @return $this
     */
    public function userResource(string | Closure | null $userResource): static
    {
        $this->userResource = $userResource;

        return $this;
    }
}
