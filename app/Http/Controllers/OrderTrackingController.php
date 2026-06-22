<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Inertia\Inertia;

class OrderTrackingController extends Controller
{
    public function show(Order $order)
    {
        // Ensure the user can only track their own orders
        if ($order->user_id !== auth()->id() && ! auth()->user()->hasRole('admin')) {
            abort(403);
        }

        return Inertia::render('Orders/Tracking', [
            'order' => $order->load(['items.product']),
            'statuses' => [
                'pending',
                'confirmed',
                'preparing',
                'out_for_delivery',
                'delivered',
                'cancelled',
            ],
        ]);
    }
}
