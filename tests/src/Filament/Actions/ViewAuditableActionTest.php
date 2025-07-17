<?php

use CrescentPurchasing\FilamentAuditing\Filament\Actions\Tables\ViewAuditableAction;
use CrescentPurchasing\FilamentAuditing\Filament\ManageAudits;
use CrescentPurchasing\FilamentAuditing\Tests\Filament\Resources\Article\ArticleResource;
use CrescentPurchasing\FilamentAuditing\Tests\Models\Article;
use CrescentPurchasing\FilamentAuditing\Tests\Models\User;
use Illuminate\Support\Facades\Gate;
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
    test()->actingAs(test()->admin);

    Article::factory()->create();

    $audit = Audit::latest('id')->firstOrFail();

    Gate::before(function (User $user, string $ability, array $arguments): bool {
        if (empty($arguments)) {
            return true;
        }

        if ($arguments[0] ?? null instanceof Article) {
            return ! in_array($ability, ['view', 'update']);
        }

        return true;
    });

    livewire(ManageAudits::class)
        ->assertTableActionHidden(ViewAuditableAction::class, record: $audit);
});
