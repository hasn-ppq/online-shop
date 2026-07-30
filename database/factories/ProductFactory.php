<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'slug' => fake()->unique()->slug(),
            'description' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 1, 100),
            'stock' => fake()->numberBetween(0, 100),
            'category_id' => null, // tests can set category if needed
        ];
    }
}
