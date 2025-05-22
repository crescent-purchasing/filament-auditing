<?php

use Carbon\Carbon;
use CrescentPurchasing\FilamentAuditing\Filament\Actions\Tables\ViewAuditAction;
use CrescentPurchasing\FilamentAuditing\Filament\ManageAudits;
use CrescentPurchasing\FilamentAuditing\Tests\Models\Article;
use OwenIt\Auditing\Models\Audit;

use function Pest\Livewire\livewire;

it('can view Audit History', function () {
    test()->actingAs(test()->admin);

    Article::factory()->create();

    $audits = Audit::all();

    livewire(ManageAudits::class)
        ->assertCanSeeTableRecords($audits);
});

it('can view a specific Audit', function () {
    test()->actingAs(test()->admin);

    $article = Article::factory()->create([
        'title' => 'I have been created!',
    ]);

    $article->update([
        'title' => 'I have been updated!',
    ]);

    $audit = Audit::latest('id')->firstOrFail();

    livewire(ManageAudits::class)
        ->assertCountTableRecords(2)
        ->mountTableAction(ViewAuditAction::class, $audit)
        ->assertTableActionDataSet([
            'created_at' => $audit->created_at,
            'event' => $audit->event,
            'user.email' => test()->admin->email,
            'old_values.title' => 'I have been created!',
            'new_values.title' => 'I have been updated!',
        ])
        ->assertSee([
            'I have been created!',
            'I have been updated!',
        ]);
});
