<?php

namespace CrescentPurchasing\FilamentAuditing\Contracts;

use CrescentPurchasing\FilamentAuditing\Audit;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User;

/**
 * @phpstan-require-extends Audit
 */
interface AuditContract
{
    /**
     * @return BelongsTo<User, $this>
     */
    public function owner(): BelongsTo;

    public function getModifiedByType(string $type = 'new'): array;
}
