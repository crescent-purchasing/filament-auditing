<?php

namespace CrescentPurchasing\FilamentAuditing\Tests\Filament\Resources\Article\Pages;

use CrescentPurchasing\FilamentAuditing\Tests\Filament\Resources\Article\ArticleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListArticles extends ListRecords
{
    protected static string $resource = ArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
