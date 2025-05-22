<?php

use CrescentPurchasing\FilamentAuditing\Filament\RelationManagers\OwnedAuditsRelationManager;
use CrescentPurchasing\FilamentAuditing\Tests\Filament\Resources\User\Pages\EditUser;
use CrescentPurchasing\FilamentAuditing\Tests\Models\Article;

use function Pest\Livewire\livewire;

it('can render relation manager', function () {
    test()->actingAs(test()->admin);

    Article::factory()->create();

    livewire(OwnedAuditsRelationManager::class, [
        'ownerRecord' => test()->admin,
        'pageClass' => EditUser::class,
    ])->assertSuccessful();
});

it('can list owned audits', function () {
    test()->actingAs(test()->admin);

    $ownedArticle = Article::factory()->create();

    livewire(OwnedAuditsRelationManager::class, [
        'ownerRecord' => test()->admin,
        'pageClass' => EditUser::class,
    ])->assertCanSeeTableRecords($ownedArticle->audits);
});

it('cannot see unowned audits', function () {
    $unownedArticle = Article::factory()->create([
        'title' => ' I am owned by nobody!',
    ]);

    test()->actingAs(test()->admin);

    Article::factory()->create([
        'title' => ' I am owned by the admin!',
    ]);

    livewire(OwnedAuditsRelationManager::class, [
        'ownerRecord' => test()->admin,
        'pageClass' => EditUser::class,
    ])->assertCanNotSeeTableRecords($unownedArticle->audits);
});
