<?php

use CrescentPurchasing\FilamentAuditing\Actions\RestoreAudit;
use CrescentPurchasing\FilamentAuditing\FilamentAuditingPlugin;
use CrescentPurchasing\FilamentAuditing\Tests\Models\Article;
use OwenIt\Auditing\Models\Audit;

beforeEach(function () {
    $this->restore = new RestoreAudit;
});

it('restores audits', function () {
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

    ($this->restore)($audit);

    expect($article->refresh())->title->toBe($oldTitle);
});

it('can authorize audit restoration', function () {
    test()->actingAs(test()->admin);

    Article::factory()->create();

    $audit = Audit::latest('id')->firstOrFail();

    expect($this->restore->isAuthorized($audit))->toBeTrue();
});

it('can use a custom permission for restoration', function () {
    test()->actingAs(test()->admin);

    Article::factory()->create();

    $audit = Audit::latest('id')->firstOrFail();

    $plugin = FilamentAuditingPlugin::get();

    $newPermission = 'goToOldAudit';

    $plugin->restorePermission($newPermission);

    expect($this->restore->isAuthorized($audit))->toBeTrue();
});

it('can enable audit restoration', function () {
    test()->actingAs(test()->admin);

    $oldTitle = 'I have been created!';

    $article = Article::factory()->create([
        'title' => $oldTitle,
    ]);

    $newTitle = 'I have been updated!';

    $article->update([
        'title' => $newTitle,
    ]);

    $audit = Audit::latest('id')->firstOrFail();

    expect($audit->event)->toBe('updated')
        ->and($this->restore->isEnabled($audit))->toBeTrue();
});

it('can disable audit restoration', function () {
    test()->actingAs(test()->admin);

    $article = Article::factory()->create();

    $article->delete();

    expect(Article::count())->toBeEmpty();

    $audit = Audit::latest('id')->firstOrFail();

    expect($audit->event)->toBe('deleted')
        ->and($this->restore->isEnabled($audit))->toBeFalse();
});
