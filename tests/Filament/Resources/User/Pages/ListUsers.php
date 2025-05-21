<?php

namespace CrescentPurchasing\FilamentAuditing\Tests\Filament\Resources\User\Pages;

use CrescentPurchasing\FilamentAuditing\Tests\Filament\Resources\User\UserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
