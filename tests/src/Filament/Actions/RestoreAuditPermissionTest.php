<?php

use CrescentPurchasing\FilamentAuditing\Filament\Actions\Tables\RestoreAuditAction;
use CrescentPurchasing\FilamentAuditing\Filament\ManageAudits;
use CrescentPurchasing\FilamentAuditing\FilamentAuditingPlugin;
use CrescentPurchasing\FilamentAuditing\Tests\Models\Article;
use OwenIt\Auditing\Models\Audit;

use function Pest\Livewire\livewire;

it('uses default permission if not set', function () {
    test()->actingAs(test()->admin);

    $article = Article::factory()->create(['title' => 'Original']);
    $article->update(['title' => 'Modified']);

    $audit = Audit::latest('id')->firstOrFail();

    livewire(ManageAudits::class)
        ->assertTableActionEnabled(RestoreAuditAction::class, $audit);
});

it('uses custom permission if set via plugin', function () {
    FilamentAuditingPlugin::get()->permission('custom_permission_article');

    test()->actingAs(test()->admin);

    $article = Article::factory()->create(['title' => 'Original']);
    $article->update(['title' => 'Modified']);

    $audit = Audit::latest('id')->firstOrFail();

    livewire(ManageAudits::class)
        ->assertTableActionEnabled(RestoreAuditAction::class, $audit);
});

it('uses closure permission if set', function () {
    FilamentAuditingPlugin::get()->permission(fn () => 'closure_based_permission');

    test()->actingAs(test()->admin);

    $article = Article::factory()->create(['title' => 'Original']);
    $article->update(['title' => 'Modified']);

    $audit = Audit::latest('id')->firstOrFail();

    livewire(ManageAudits::class)
        ->assertTableActionEnabled(RestoreAuditAction::class, $audit);
});

it('disables restore action if permission is not granted', function () {
    FilamentAuditingPlugin::get()->permission('missing_permission');

    test()->actingAs(test()->admin);

    $article = Article::factory()->create(['title' => 'Original']);
    $article->update(['title' => 'Modified']);

    $audit = Audit::latest('id')->firstOrFail();

    livewire(ManageAudits::class)
        ->assertTableActionDisabled(RestoreAuditAction::class, $audit);
});

it('enables restore action if permission is null', function () {
    FilamentAuditingPlugin::get()->permission(null);

    test()->actingAs(test()->admin);

    $article = Article::factory()->create(['title' => 'Original']);
    $article->update(['title' => 'Modified']);

    $audit = Audit::latest('id')->firstOrFail();

    livewire(ManageAudits::class)
        ->assertTableActionEnabled(RestoreAuditAction::class, $audit);
});
