<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Http\Resources\CartResource;

class CartController extends Controller
{
   public function index(Request $request)
{
    $cart = Cart::with('items.product')
        ->firstOrCreate(['user_id' => $request->user()->id]);

    return new CartResource($cart);
}

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $cart = Cart::firstOrCreate([
            'user_id' => $request->user()->id
        ]);

        $item = $cart->items()
            ->where('product_id', $request->product_id)
            ->first();

        if ($item) {
            $item->increment('quantity');
        } else {
            $cart->items()->create([
                'product_id' => $request->product_id,
                'quantity' => 1,
            ]);
        }

        return response()->json(['message' => 'Added to cart']);
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::where('user_id', $request->user()->id)->first();

        $cart->items()
            ->where('product_id', $request->product_id)
            ->update(['quantity' => $request->quantity]);

        return response()->json(['message' => 'Cart updated']);
    }

    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $cart = Cart::where('user_id', $request->user()->id)->first();

        $cart->items()
            ->where('product_id', $request->product_id)
            ->delete();

        return response()->json(['message' => 'Removed']);
    }
}