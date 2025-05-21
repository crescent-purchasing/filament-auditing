<?php

use Carbon\Carbon;
use CrescentPurchasing\FilamentAuditing\Filament\ManageAudits;
use CrescentPurchasing\FilamentAuditing\Tests\Models\Article;
use OwenIt\Auditing\Models\Audit;

use function Pest\Livewire\livewire;

it('can view Audit History', function () {
    $now = Carbon::now();

    test()->actingAs(test()->admin);

    Article::factory()->create([
        'title' => 'How To Audit Eloquent Models',
        'content' => 'First step: install the laravel-auditing package.',
        'user_id' => test()->admin,
        'reviewed' => 1,
        'published_at' => $now,
    ]);

    $audits = Audit::all();

    expect($audits)->toHaveCount(1);

    livewire(ManageAudits::class)
        ->assertCanSeeTableRecords($audits);
});
