<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'mealdb_id' => fake()->unique()->numberBetween(100000, 999999),
            'name' => Str::title($name),
            'slug' => Str::slug($name.'-'.fake()->unique()->numberBetween(1000, 9999)),
            'category' => fake()->randomElement(['Breakfast', 'Lunch', 'Dinner', 'Dessert']),
            'image_url' => fake()->imageUrl(800, 600, 'food', true),
            'description' => fake()->optional()->sentence(),
            'raw_data' => [
                'idMeal' => (string) fake()->unique()->numberBetween(100000, 999999),
                'strMeal' => Str::title($name),
            ],
            'price' => fake()->randomFloat(2, 8, 30),
            'sort_order' => fake()->numberBetween(1, 100),
            'is_active' => true,
        ];
    }
}
