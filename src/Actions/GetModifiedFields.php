<?php

namespace CrescentPurchasing\FilamentAuditing\Actions;

use Illuminate\Support\Arr;
use OwenIt\Auditing\Models\Audit;

readonly class GetModifiedFields
{
    /**
     * @return array<string, mixed>
     */
    public function __invoke(Audit $audit, bool $old = false): array
    {
        $type = $old ? 'old' : 'new';

        return Arr::map(
            (array) $audit->getModified(),
            fn (array $value) => $value[$type] ?? null
        );
    }
}
