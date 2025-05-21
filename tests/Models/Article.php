<?php

namespace CrescentPurchasing\FilamentAuditing\Tests\Models;

use CrescentPurchasing\FilamentAuditing\Tests\database\factories\ArticleFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;

class Article extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected static string $factory = ArticleFactory::class;

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'reviewed' => 'bool',
        'published_at' => 'datetime',
    ];

    protected $fillable = [
        'title',
        'content',
        'reviewed',
        'published_at',
        'user_id',
        'deleted_at',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Uppercase Content accessor.
     */
    public function content(): Attribute
    {
        return new Attribute(
            function ($value) {
                return $value;
            },
            function ($value) {
                return ucwords($value);
            }
        );
    }

    public static function contentMutate($value): string
    {
        if (! method_exists(self::class, 'hasAttributeMutator')) {
            return $value;
        }

        return ucwords($value);
    }
}
