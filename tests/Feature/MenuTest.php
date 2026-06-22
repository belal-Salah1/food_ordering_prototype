<?php

use App\Models\Product;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Inertia\Testing\AssertableInertia;

function themeDbMealPayload(int $mealdbId, string $name, string $category): array
{
    return [
        'meals' => [
            [
                'idMeal' => (string) $mealdbId,
                'strMeal' => $name,
                'strCategory' => $category,
                'strMealThumb' => "https://www.themealdb.com/images/media/meals/{$mealdbId}.jpg",
                'strInstructions' => "{$name} is a curated test meal from TheMealDB.",
            ],
        ],
    ];
}

function themeDbLookupResponse(Request $request): Response
{
    parse_str((string) parse_url($request->url(), PHP_URL_QUERY), $query);

    $mealdbId = (int) ($query['i'] ?? 0);

    $fixtures = [
        53262 => ['Adana kebab', 'Beef'],
        53373 => ['Air Fryer Egg Rolls', 'Chicken'],
        53158 => ['Air fryer patatas bravas', 'Side'],
        53169 => ['Ajo blanco', 'Soup'],
        53138 => ['Alfajores', 'Dessert'],
        53284 => ['Algerian Bouzgene Berber Bread with Roasted Pepper Sauce', 'Vegetarian'],
        53282 => ['Algerian Carrots', 'Vegetarian'],
        53288 => ['Algerian Flafla (Bell Pepper Salad)', 'Vegetarian'],
        53281 => ['Algerian Kefta (Meatballs)', 'Beef'],
        53430 => ['Antiguan Breakfast (Chop Up and Saltfish)', 'Breakfast'],
        53111 => ['Anzac biscuits', 'Dessert'],
        53049 => ['Apam balik', 'Dessert'],
    ];

    [$name, $category] = $fixtures[$mealdbId];

    return Http::response(themeDbMealPayload($mealdbId, $name, $category));
}

test('guests can view the public menu page', function () {
    Http::preventStrayRequests();

    Http::fake(function (Request $request) {
        if (str_contains($request->url(), 'lookup.php')) {
            return themeDbLookupResponse($request);
        }

        return Http::response([], 404);
    });

    $this->seed(DatabaseSeeder::class);

    $response = $this->get(route('menu'));

    $response
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Menu/Index')
            ->has('products', count(ProductSeeder::CURATED_MEAL_IDS))
            ->where('products.0.name', 'Adana kebab')
            ->where('featuredProduct.name', 'Adana kebab')
        );
});

test('the curated menu seeder creates deterministic products', function () {
    Http::preventStrayRequests();

    Http::fake(function (Request $request) {
        if (str_contains($request->url(), 'lookup.php')) {
            return themeDbLookupResponse($request);
        }

        return Http::response([], 404);
    });

    $this->seed(DatabaseSeeder::class);

    expect(Product::count())->toBe(count(ProductSeeder::CURATED_MEAL_IDS));

    $this->assertDatabaseHas('products', [
        'mealdb_id' => 53262,
        'name' => 'Adana kebab',
        'price' => '10.00',
        'sort_order' => 1,
    ]);

    $this->assertDatabaseHas('products', [
        'mealdb_id' => 53373,
        'name' => 'Air Fryer Egg Rolls',
        'price' => '11.25',
        'sort_order' => 2,
    ]);

    $this->assertDatabaseHas('products', [
        'mealdb_id' => 53049,
        'name' => 'Apam balik',
        'price' => '23.75',
        'sort_order' => 12,
    ]);
});
