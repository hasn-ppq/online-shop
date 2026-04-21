<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartComponent extends Component
{
    public $cart;

    public function mount()
    {
        $this->loadCart();
    }

    public function loadCart()
    {
        $this->cart = Cart::with('items.product')
            ->firstOrCreate(['user_id' => Auth::id()]);
    }

    public function add($productId)
    {
        $cart = $this->cart;

        $item = $cart->items()->where('product_id', $productId)->first();

        if ($item) {
            $item->increment('quantity');
        } else {
            $cart->items()->create([
                'product_id' => $productId,
                'quantity' => 1,
            ]);
        }

        $this->loadCart();
    }

    public function remove($productId)
    {
        $this->cart->items()->where('product_id', $productId)->delete();
        $this->loadCart();
    }

    public function increase($productId)
    {
        $this->cart->items()->where('product_id', $productId)->increment('quantity');
        $this->loadCart();
    }

    public function decrease($productId)
    {
        $item = $this->cart->items()->where('product_id', $productId)->first();

        if ($item && $item->quantity > 1) {
            $item->decrement('quantity');
        }

        $this->loadCart();
    }

    public function getTotalProperty()
    {
        return $this->cart->items->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });
    }

    public function render()
    {
        return view('livewire.cart-component');
    }
}