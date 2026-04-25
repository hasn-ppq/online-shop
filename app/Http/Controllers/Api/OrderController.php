<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $cart = Cart::with('items.product')
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }

        $order = DB::transaction(function () use ($cart, $request) {

            $total = $cart->items->sum(function ($item) {
                return $item->quantity * $item->product->price;
            });

            $order = Order::create([
                'user_id' => $request->user()->id,
                'total' => $total,
                'status' => 'pending',
            ]);

            foreach ($cart->items as $item) {

                $product = $item->product;

                if ($product->stock < $item->quantity) {
                    throw new \Exception("Stock issue");
                }

                $product->decrement('stock', $item->quantity);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item->quantity,
                    'price' => $product->price,
                ]);
            }

            $cart->items()->delete();

            return $order;
        });

       return response()->json([
    'message' => 'Order created',
    'order' => new OrderResource($order)

        ]);
    }

    public function myOrders(Request $request)
    {
       return OrderResource::collection(
    Order::with('items.product')
        ->where('user_id', $request->user()->id)
        ->latest()
        ->get()
);
    }
}