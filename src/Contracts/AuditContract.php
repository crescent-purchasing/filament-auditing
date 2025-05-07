<?php

namespace CrescentPurchasing\FilamentAuditing\Contracts;

use CrescentPurchasing\FilamentAuditing\Audit;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @phpstan-require-extends Audit
 */
interface AuditContract
{
    public function owner(): BelongsTo;

    public function getModifiedByType(string $type = 'new'): array;
}
