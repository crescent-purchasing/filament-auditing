<?php

namespace CrescentPurchasing\FilamentAuditing\Tests\Filament\Resources\User;

use CrescentPurchasing\FilamentAuditing\Filament\RelationManagers\AuditsRelationManager;
use CrescentPurchasing\FilamentAuditing\Filament\RelationManagers\OwnedAuditsRelationManager;
use CrescentPurchasing\FilamentAuditing\Tests\Filament\Resources\User\Pages\CreateUser;
use CrescentPurchasing\FilamentAuditing\Tests\Filament\Resources\User\Pages\EditUser;
use CrescentPurchasing\FilamentAuditing\Tests\Filament\Resources\User\Pages\ListUsers;
use CrescentPurchasing\FilamentAuditing\Tests\Models\User;
use Filament\Resources\Resource;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $recordTitleAttribute = 'email';

    public static function getRelations(): array
    {
        return [
            AuditsRelationManager::class,
            OwnedAuditsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
