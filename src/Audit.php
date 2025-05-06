<?php

namespace CrescentPurchasing\FilamentAuditing;

use CrescentPurchasing\FilamentAuditing\Contracts\AuditContract;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use OwenIt\Auditing\Models\Audit as BaseAudit;


/**
 * @property-read string $title
 * @property-read string $name
 * @property-read string $type
 */
class Audit extends BaseAudit implements AuditContract
{
    protected function title(): Attribute
    {
        $getTitle = function (mixed $value, array $attributes): string {
            return __('filament-auditing::audit.title', [
                'type' => $this->type,
                'id' => $attributes['auditable_id'],
                'timestamp' => $attributes['created_at'],
            ]);
        };

        return Attribute::make(get: $getTitle);
    }

    protected function name(): Attribute
    {
        $getName = function (mixed $value, array $attributes): string {
            return __('filament-auditing::audit.name', [
                'type' => $this->type,
                'id' => $attributes['auditable_id'],
            ]);
        };

        return Attribute::make(get: $getName);
    }

    protected function type(): Attribute
    {
        $getType = function (mixed $value, array $attributes): string {
            return Str::of($attributes['auditable_type'])->classBasename()->headline()->toString();
        };

        return Attribute::make(get: $getType);
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
