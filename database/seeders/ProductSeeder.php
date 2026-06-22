<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class ProductSeeder extends Seeder
{
    /**
     * @var array<int, int>
     */
    public const CURATED_MEAL_IDS = [
        53262,
        53373,
        53158,
        53169,
        53138,
        53284,
        53282,
        53288,
        53281,
        53430,
        53111,
        53049,
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (self::CURATED_MEAL_IDS as $position => $mealdbId) {
            $meal = Http::timeout(10)
                ->retry(3, 500)
                ->get('https://www.themealdb.com/api/json/v1/1/lookup.php', [
                    'i' => $mealdbId,
                ])
                ->throw()
                ->json('meals.0');

            if (! is_array($meal)) {
                throw new RuntimeException("TheMealDB meal [{$mealdbId}] was not returned by the API.");
            }

            $name = (string) ($meal['strMeal'] ?? 'Unknown meal');
            $price = number_format(10 + ($position * 1.25), 2, '.', '');

            Product::updateOrCreate(
                ['mealdb_id' => $mealdbId],
                [
                    'name' => $name,
                    'slug' => Str::slug($name.'-'.$mealdbId),
                    'category' => $meal['strCategory'] ?? null,
                    'image_url' => (string) ($meal['strMealThumb'] ?? ''),
                    'description' => Str::of((string) ($meal['strInstructions'] ?? ''))
                        ->squish()
                        ->limit(220)
                        ->toString(),
                    'raw_data' => $meal,
                    'price' => $price,
                    'sort_order' => $position + 1,
                    'is_active' => true,
                ],
            );
        }
    }
}
