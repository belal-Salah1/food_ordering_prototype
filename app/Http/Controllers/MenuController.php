<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class MenuController extends Controller
{
    /**
     * Display the public menu page.
     */
    public function index(): Response
    {
        $products = Product::query()
            ->active()
            ->ordered()
            ->get([
                'id',
                'mealdb_id',
                'name',
                'slug',
                'category',
                'image_url',
                'description',
                'price',
                'sort_order',
            ]);

        $categories = $products
            ->pluck('category')
            ->filter()
            ->unique()
            ->values()
            ->map(fn (string $category) => [
                'value' => $category,
                'label' => $category,
                'count' => $products->where('category', $category)->count(),
            ]);

        $productsInertia = $products->map(fn (Product $product): array => [
            'id' => $product->id,
            'mealdbId' => $product->mealdb_id,
            'name' => $product->name,
            'slug' => $product->slug,
            'category' => $product->category,
            'imageUrl' => $product->image_url,
            'description' => $product->description,
            'price' => (float) $product->price,
            'sortOrder' => $product->sort_order,
        ]);

        $featuredProduct = $productsInertia->first();

        return Inertia::render('Menu/Index', [
            'products' => $productsInertia,
            'featuredProduct' => $featuredProduct,
            'categories' => $categories,
            'stats' => [
                'meals' => $productsInertia->count(),
                'categories' => $categories->count(),
                'images' => $productsInertia->whereNotNull('imageUrl')->count(),
            ],
            'ui' => trans('menu'),
        ]);
    }
}
