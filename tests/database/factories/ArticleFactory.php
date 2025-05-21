<?php

namespace CrescentPurchasing\FilamentAuditing\Tests\database\factories;

use CrescentPurchasing\FilamentAuditing\Tests\Models\Article;
use CrescentPurchasing\FilamentAuditing\Tests\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Article>
 */
class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition()
    {
        return [
            'title' => fake()->unique()->sentence,
            'content' => fake()->unique()->paragraph(6),
            'user_id' => User::factory(),
            'published_at' => null,
        ];
    }
}
