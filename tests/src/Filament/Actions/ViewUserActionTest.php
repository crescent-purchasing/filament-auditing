<?php

use CrescentPurchasing\FilamentAuditing\Filament\Actions\Tables\ViewUserAction;
use CrescentPurchasing\FilamentAuditing\Filament\ManageAudits;
use CrescentPurchasing\FilamentAuditing\Tests\Filament\Resources\User\UserResource;
use CrescentPurchasing\FilamentAuditing\Tests\Models\Article;
use OwenIt\Auditing\Models\Audit;
use function Pest\Livewire\livewire;

it('Can see the url of the audit record', function () {
    test()->actingAs(test()->admin);

    Article::factory()->create();

    $audit = Audit::latest('id')->firstOrFail();

    $userUrl = UserResource::getGlobalSearchResultUrl(test()->admin);

    livewire(ManageAudits::class)
        ->assertTableActionHasUrl(ViewUserAction::class, $userUrl, $audit);
});
