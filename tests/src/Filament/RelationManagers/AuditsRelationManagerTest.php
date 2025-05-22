<?php

use CrescentPurchasing\FilamentAuditing\Filament\RelationManagers\AuditsRelationManager;
use CrescentPurchasing\FilamentAuditing\Tests\Filament\Resources\Article\Pages\EditArticle;
use CrescentPurchasing\FilamentAuditing\Tests\Models\Article;

use function Pest\Livewire\livewire;

it('can render relation manager', function () {
    test()->actingAs(test()->admin);

    $article = Article::factory()->create();

    livewire(AuditsRelationManager::class, [
        'ownerRecord' => $article,
        'pageClass' => EditArticle::class,
    ])->assertSuccessful();
});

it('can list related audits', function () {
    test()->actingAs(test()->admin);

    $article = Article::factory()->create([
        'title' => ' I have been created!',
    ]);

    $article->update([
        'title' => 'I have been updated!',
    ]);

    livewire(AuditsRelationManager::class, [
        'ownerRecord' => $article,
        'pageClass' => EditArticle::class,
    ])->assertCanSeeTableRecords($article->audits);
});

it('cannot see unrelated audits', function () {
    test()->actingAs(test()->admin);

    $article = Article::factory()->create([
        'title' => ' I have been created!',
    ]);

    $unrelated = Article::factory()->create();

    livewire(AuditsRelationManager::class, [
        'ownerRecord' => $article,
        'pageClass' => EditArticle::class,
    ])->assertCanNotSeeTableRecords($unrelated->audits);
});
