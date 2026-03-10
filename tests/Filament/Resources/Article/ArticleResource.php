<?php

namespace CrescentPurchasing\FilamentAuditing\Tests\Filament\Resources\Article;

use BackedEnum;
use CrescentPurchasing\FilamentAuditing\Filament\RelationManagers\AuditsRelationManager;
use CrescentPurchasing\FilamentAuditing\Tests\Filament\Resources\Article\Pages\CreateArticle;
use CrescentPurchasing\FilamentAuditing\Tests\Filament\Resources\Article\Pages\EditArticle;
use CrescentPurchasing\FilamentAuditing\Tests\Filament\Resources\Article\Pages\ListArticles;
use CrescentPurchasing\FilamentAuditing\Tests\Models\Article;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::Newspaper;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getRelations(): array
    {
        return [
            AuditsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListArticles::route('/'),
            'create' => CreateArticle::route('/create'),
            'edit' => EditArticle::route('/{record}/edit'),
        ];
    }
}
