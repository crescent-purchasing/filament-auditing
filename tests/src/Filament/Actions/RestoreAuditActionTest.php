<?php

use CrescentPurchasing\FilamentAuditing\Filament\Actions\Tables\RestoreAuditAction;
use CrescentPurchasing\FilamentAuditing\Filament\ManageAudits;
use CrescentPurchasing\FilamentAuditing\Tests\Models\Article;
use OwenIt\Auditing\Models\Audit;

use function Pest\Livewire\livewire;

it('can restore audits', function () {
    test()->actingAs(test()->admin);

    $oldTitle = 'I have been created!';

    $article = Article::factory()->create([
        'title' => $oldTitle,
    ]);

    $newTitle = 'I have been updated!';

    $article->update([
        'title' => $newTitle,
    ]);

    expect($article)->title->toBe($newTitle);

    $audit = Audit::oldest('id')->firstOrFail();

    livewire(ManageAudits::class)
        ->mountTableAction(RestoreAuditAction::class, $audit)
        ->callMountedTableAction()
        ->assertHasNoTableActionErrors();

    expect($article->refresh())->title->toBe($oldTitle);
});

it('can restore audits to old values', function () {
    test()->actingAs(test()->admin);

    $article = Article::factory()->create();

    $oldTitle = 'I have been updated once!';

    $article->update([
        'title' => $oldTitle,
    ]);

    $newTitle = 'I have been updated twice!';

    $article->update([
        'title' => $newTitle,
    ]);

    expect($article)->title->toBe($newTitle);

    $audit = Audit::latest('id')->firstOrFail();

    livewire(ManageAudits::class)
        ->mountTableAction(RestoreAuditAction::class, $audit)
        ->assertTableActionDataSet([
            'restore_to_old' => false,
        ])
        ->setTableActionData([
            'restore_to_old' => true,
        ])
        ->callMountedTableAction()
        ->assertHasNoTableActionErrors();

    expect($article->refresh())->title->toBe($oldTitle);
});

it('cannot restore audits without permission', function () {
    test()->admin->update([
        'email' => 'big.chungus@example.com',
    ]);

    test()->actingAs(test()->admin);

    $article = Article::factory()->create();

    $article->update([
        'title' => 'I have been updated!',
    ]);

    $audit = Audit::latest('id')->firstOrFail();

    livewire(ManageAudits::class)
        ->assertTableActionDisabled(RestoreAuditAction::class, $audit);
});

it('cannot restore audits of deleted records', function () {
    test()->actingAs(test()->admin);

    $article = Article::factory()->create();

    $article->delete();

    expect(Article::count())->toBeEmpty();

    $audit = Audit::latest('id')->firstOrFail();

    livewire(ManageAudits::class)
        ->assertTableActionDisabled(RestoreAuditAction::class, $audit);
});
