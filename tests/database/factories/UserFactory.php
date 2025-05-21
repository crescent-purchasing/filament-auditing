<?php

namespace CrescentPurchasing\FilamentAuditing\Tests\database\factories;

use CrescentPurchasing\FilamentAuditing\Tests\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'is_admin' => $this->faker->randomElement([0, 1]),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
        ];
    }
}
