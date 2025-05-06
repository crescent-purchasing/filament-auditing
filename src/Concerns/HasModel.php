<?php

namespace CrescentPurchasing\FilamentAuditing\Concerns;

use Closure;
use CrescentPurchasing\FilamentAuditing\Audit;
use Illuminate\Database\Eloquent\Model;

trait HasModel
{
    protected string | Closure $model = Audit::class;

    /**
     * @return class-string<Model>
     */
    public function getModel(): string
    {
        return $this->evaluate($this->model);
    }

    public function model(string | Closure $model): static
    {
        $this->model = $model;

        return $this;
    }

}
