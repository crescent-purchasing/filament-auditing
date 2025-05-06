<?php

namespace CrescentPurchasing\FilamentAuditing;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Arr;
use OwenIt\Auditing\Models\Audit as BaseAudit;

class Audit extends BaseAudit
{
    protected function title(): Attribute
    {
        $getName = function (mixed $value, array $attributes): string {
            return __('filament-auditing::audit.title', [
                'type' => self::formatAuditableType($attributes['auditable_type']),
                'id' => $attributes['auditable_id'],
                'timestamp' => $attributes['created_at'],
            ]);
        };

        return Attribute::make(get: $getName);
    }

    protected function name(): Attribute
    {
        $getName = function (mixed $value, array $attributes): string {
            return __('filament-auditing::audit.name', [
                'type' => self::formatAuditableType($attributes['auditable_type']),
                'id' => $attributes['auditable_id'],
            ]);
        };

        return Attribute::make(get: $getName);
    }

    /**
     * Filament doesn't support Polymorphic relationships on Select filters
     * This is used as a temporary workaround to support the Select filter on Laravel's default User model
     *
     * @return BelongsTo<User, $this>
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getModifiedByType(string $type = 'new'): array
    {
        return Arr::map($this->getModified(), fn (array $value) => $value[$type] ?? null);
    }
}
