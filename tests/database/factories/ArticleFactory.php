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

    public function definition(): array
    {
        return [
            'title' => $this->faker->unique()->sentence,
            'content' => $this->faker->unique()->paragraph(6),
            'reviewed' => $this->faker->boolean(),
            'published_at' => null,
            'user_id' => null,
        ];
    }
}
