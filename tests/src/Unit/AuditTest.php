<?php

use Carbon\Carbon;
use CrescentPurchasing\FilamentAuditing\Tests\Models\Article;
use OwenIt\Auditing\Resolvers\UrlResolver;

it('resolves audit data', function () {
    $now = Carbon::now();

    $article = Article::factory()->create([
        'title' => 'How To Audit Eloquent Models',
        'content' => 'First step: install the laravel-auditing package.',
        'reviewed' => 1,
        'published_at' => $now,
        'user_id' => null,
    ]);

    $audit = $article->audits()->first();
    expect($audit)->not()->toBeNull();

    $resolvedData = $audit->resolveData();

    expect($resolvedData)->toHaveCount(16)
        ->and($resolvedData)->toMatchArray([
            'audit_id' => 1,
            'audit_event' => 'created',
            'audit_url' => UrlResolver::resolveCommandLine(),
            'audit_ip_address' => '127.0.0.1',
            'audit_user_agent' => 'Symfony',
            'audit_tags' => null,
            'audit_created_at' => $audit->getSerializedDate($audit->created_at),
            'audit_updated_at' => $audit->getSerializedDate($audit->updated_at),
            'user_id' => null,
            'user_type' => null,
            'new_title' => 'How To Audit Eloquent Models',
            'new_content' => Article::contentMutate('First step: install the laravel-auditing package.'),
            'new_published_at' => $now->toDateTimeString(),
            'new_reviewed' => 1,
            'new_id' => 1,
            'new_user_id' => null,
        ]);

});
