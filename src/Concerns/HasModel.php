<?php

namespace CrescentPurchasing\FilamentAuditing\Concerns;

use Closure;
use OwenIt\Auditing\Models\Audit;

trait HasModel
{
    /** @var class-string<Audit>|Closure */
    protected string | Closure $model = Audit::class;

    /**
     * @return class-string<Audit>
     */
    public function getModel(): string
    {
        return $this->evaluate($this->model);
    }

    /**
     * @param  class-string<Audit>|Closure  $model
     * @return $this
     */
    public function model(string | Closure $model): static
    {
        $this->model = $model;

        return $this;
    }
}
