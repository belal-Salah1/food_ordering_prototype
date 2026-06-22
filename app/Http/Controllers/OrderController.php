<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class OrderController extends Controller
{
    /**
     * Store a new order.
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'total' => 'required|numeric',
            'payment_method' => 'required|in:cod,online',
            'address' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($request) {
            $order = Order::create([
                'user_id' => $request->user()->id,
                'total_amount' => $request->total,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'address' => $request->address,
                'notes' => $request->notes,
            ]);

            foreach ($request->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['price'] * $item['quantity'],
                ]);
            }

            if ($request->payment_method === 'online') {
                Stripe::setApiKey(config('services.stripe.secret'));

                $lineItems = [];
                foreach ($request->items as $item) {
                    $lineItems[] = [
                        'price_data' => [
                            'currency' => 'usd',
                            'product_data' => [
                                'name' => $item['name'],
                            ],
                            'unit_amount' => $item['price'] * 100,
                        ],
                        'quantity' => $item['quantity'],
                    ];
                }

                $session = Session::create([
                    'payment_method_types' => ['card'],
                    'line_items' => $lineItems,
                    'mode' => 'payment',
                    'success_url' => route('orders.success', ['order' => $order->id]) . '?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => route('cart'),
                    'metadata' => [
                        'order_id' => $order->id,
                    ],
                ]);

                $order->update(['stripe_session_id' => $session->id]);

                return response()->json(['url' => $session->url]);
            }

            return redirect()->route('orders.success', ['order' => $order->id])->with('success', 'Order placed successfully!');
        });
    }

    /**
     * Handle order success.
     */
    public function success(Request $request, Order $order)
    {
        if ($request->has('session_id')) {
            Stripe::setApiKey(config('services.stripe.secret'));
            $session = Session::retrieve($request->get('session_id'));

            if ($session->payment_status === 'paid') {
                $order->update(['payment_status' => 'paid']);
            }
        }

        return inertia('Orders/Success', [
            'order' => $order->load('items.product'),
        ]);
    }
}
