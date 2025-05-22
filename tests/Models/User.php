<?php

namespace CrescentPurchasing\FilamentAuditing\Tests\Models;

use CrescentPurchasing\FilamentAuditing\Tests\database\factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Config;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;

class User extends Authenticatable implements Auditable, FilamentUser, HasName
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected static string $factory = UserFactory::class;

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'is_admin' => 'bool',
    ];

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function getFilamentName(): string
    {
        return $this->email;
    }

    /**
     * @return MorphMany<Audit, $this>
     */
    public function ownedAudits(): MorphMany
    {
        /** @var class-string<Audit> $model */
        $model = Config::get('audit.implementation', Audit::class);

        $morphPrefix = Config::get('audit.user.morph_prefix', 'user');

        return $this->morphMany($model, $morphPrefix);
    }
}
