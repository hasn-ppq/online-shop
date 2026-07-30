<?php

namespace App\Providers;

use Illuminate\Auth\Events\Login;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;

class SyncGuestCart
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $user = $event->user;

        $guestCart = session('guest_cart', []);

        if (empty($guestCart)) {
            return;
        }

        DB::transaction(function () use ($user, $guestCart) {
            $cart = Cart::firstOrCreate(['user_id' => $user->id]);

            foreach ($guestCart as $productId => $quantity) {
                $item = $cart->items()->where('product_id', $productId)->first();

                if ($item) {
                    $item->update(['quantity' => $item->quantity + $quantity]);
                } else {
                    $cart->items()->create([
                        'product_id' => $productId,
                        'quantity' => $quantity,
                    ]);
                }
            }

            // clear guest cart from session
            session()->forget('guest_cart');
        });
    }
}
