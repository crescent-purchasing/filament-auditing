<?php

use CrescentPurchasing\FilamentAuditing\Filament\Actions\Tables\ViewAuditableAction;
use CrescentPurchasing\FilamentAuditing\Filament\ManageAudits;
use CrescentPurchasing\FilamentAuditing\Tests\Filament\Resources\Article\ArticleResource;
use CrescentPurchasing\FilamentAuditing\Tests\Models\Article;
use OwenIt\Auditing\Models\Audit;
use function Pest\Livewire\livewire;

it('Can see the url of the audit record', function () {
    test()->actingAs(test()->admin);

    $article = Article::factory()->create();

    $audit = Audit::latest('id')->firstOrFail();

    $articleUrl = ArticleResource::getGlobalSearchResultUrl($article);

    livewire(ManageAudits::class)
        ->assertTableActionHasUrl(ViewAuditableAction::class, $articleUrl, $audit);
});

it('restricts viewing auditable when user does not have permission', function () {
    test()->admin->update([
        'email' => 'big.chungus@example.com',
    ]);

    test()->actingAs(test()->admin);

    Article::factory()->create();

    $audit = Audit::latest('id')->firstOrFail();

    livewire(ManageAudits::class)
        ->assertTableActionHidden(ViewAuditableAction::class, record: $audit);
});
