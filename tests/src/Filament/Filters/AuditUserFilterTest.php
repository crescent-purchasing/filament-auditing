<?php

use CrescentPurchasing\FilamentAuditing\Filament\Filters\QueryBuilder\AuditUserConstraint;
use CrescentPurchasing\FilamentAuditing\Filament\Filters\QueryBuilder\AuditUserOperator;
use CrescentPurchasing\FilamentAuditing\FilamentAuditingPlugin;
use CrescentPurchasing\FilamentAuditing\Tests\Models\Article;
use CrescentPurchasing\FilamentAuditing\Tests\Models\User;
use OwenIt\Auditing\Models\Audit;

beforeEach(function () {
    $this->constraint = AuditUserConstraint::make('user')
        ->selectable(
            AuditUserOperator::make()
                ->titleAttribute('email')
                ->types(FilamentAuditingPlugin::get()->getUsers())
        );

    $this->operator = $this->constraint->getOperator('isRelatedTo');

    $this->operator->constraint($this->constraint);
});

it('can filter audits by User Type', function () {

    $newUser = User::factory()->create([
        'first_name' => 'Nobody',
        'last_name' => 'OwnsMe',
        'email' => 'nobody@owns.me',
    ]);

    Article::factory()->create([
        'title' => 'I am owned by nobody!',
    ]);

    test()->actingAs(test()->admin);

    Article::factory()->create([
        'title' => 'I am owned by the admin!',
    ]);

    test()->actingAs($newUser);

    Article::factory()->create([
        'title' => 'I am owned by the new user!',
    ]);

    $typeColumn = config('audit.user.morph_prefix') . '_type';

    $filteredAudits = $this->operator
        ->settings([
            $typeColumn => User::class,
        ])
        ->apply(Audit::query(), '')->get();

    $allAudits = Audit::all();

    expect($allAudits)->toHaveCount(4)
        ->and($filteredAudits)->toHaveCount(2);
});

it('can filter audits by a specific User', function () {

    $newUser = User::factory()->create([
        'first_name' => 'Nobody',
        'last_name' => 'OwnsMe',
    ]);

    test()->actingAs($newUser);

    Article::factory()->create([
        'title' => 'I am owned by the new user!',
    ]);

    test()->actingAs(test()->admin);

    Article::factory()->create([
        'title' => 'I am owned by the admin!',
    ]);

    $typeColumn = config('audit.user.morph_prefix') . '_type';
    $valueColumn = config('audit.user.morph_prefix') . '_id';

    $filteredAudits = $this->operator
        ->settings([
            $typeColumn => User::class,
            $valueColumn => test()->admin->id,
        ])
        ->apply(Audit::query(), '')->get();

    $allAudits = Audit::all();

    expect($allAudits)->toHaveCount(3)
        ->and($filteredAudits)->toHaveCount(1);
});

describe('summary', function () {
    it('gives correct summary when type & user are provided', function () {
        $expectedSummary = __('filament-auditing::resource.fields.user.summary.value_direct', [
            'relationship' => 'Owner',
            'type' => 'User',
            'value' => test()->admin->email,
        ]);

        $typeColumn = config('audit.user.morph_prefix') . '_type';
        $valueColumn = config('audit.user.morph_prefix') . '_id';

        $summary = $this->operator
            ->settings([
                $typeColumn => User::class,
                $valueColumn => test()->admin->id,
            ])
            ->getSummary();

        expect($summary)->toBe($expectedSummary);

    })->issue(28);

    it('gives correct summary when only Type is provided', function () {
        $expectedSummary = __('filament-auditing::resource.fields.user.summary.type_direct', [
            'relationship' => 'Owner',
            'type' => 'User',
        ]);

        $typeColumn = config('audit.user.morph_prefix') . '_type';

        $summary = $this->operator
            ->settings([
                $typeColumn => User::class,
            ])
            ->getSummary();

        expect($summary)->toBe($expectedSummary);

    })->issue(28);

    it('does not throw an exception on getSummary with type is empty', function () {
        $this->operator->getSummary();
    })->throwsNoExceptions()->issue(28);
});
