<?php

namespace CrescentPurchasing\FilamentAuditing\Concerns;

use Closure;
use Illuminate\Support\Facades\Config;
use OwenIt\Auditing\Models\Audit;

trait HasModel
{
    /** @var class-string<Audit>|Closure|null */
    protected string | Closure | null $model = null;

    /**
     * @return class-string<Audit>
     */
    public function getModel(): string
    {
        if (empty($this->model)) {
            return $this->getDefaultModel();
        }

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

    /**
     * @return class-string<Audit>
     */
    private function getDefaultModel(): string
    {
        return Config::get('audit.implementation');
    }
}
