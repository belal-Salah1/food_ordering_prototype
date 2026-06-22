<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    /**
     * Display the cart page.
     */
    public function index(): Response
    {
        return Inertia::render('Cart/Index', [
            'ui' => trans('menu'),
        ]);
    }
}
