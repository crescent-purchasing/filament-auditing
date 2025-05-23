<?php

namespace CrescentPurchasing\FilamentAuditing\Concerns;

use Closure;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Config;

trait HasUsers
{
    /** @var class-string<User>[]|Closure */
    protected array | Closure $users = [];

    /**
     * @return class-string<User>[]
     */
    public function getUsers(): array
    {
        if (empty($this->users)) {
            return $this->getDefaultUsers();
        }

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

    /**
     * @return class-string<User>[]
     */
    private function getDefaultUsers(): array
    {
        $guards = Config::get('audit.user.guards', [
            Config::get('auth.defaults.guard'),
        ]);

        $providers = Config::get('auth.providers');

        $users = [];

        foreach ($guards as $guard) {

            $provider = Config::get('auth.guards.' . $guard . '.provider');

            if (! array_key_exists($provider, $providers)) {
                continue;
            }

            if ($providers[$provider]['driver'] !== 'eloquent') {
                continue;
            }

            $users[] = $providers[$provider]['model'];
        }

        return array_unique($users);
    }
}
