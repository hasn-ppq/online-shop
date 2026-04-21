<?php

namespace App\Livewire;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartView extends Component
{
    public $cart;

    protected $listeners = ['cartUpdated' => 'loadCart'];

    public function mount()
    {
        $this->loadCart();
    }

    public function loadCart()
    {
        $this->cart = Cart::with('items.product')
            ->where('user_id', Auth::id())
            ->first();
    }
    public function increase($productId)
{
    $item = $this->cart->items()->where('product_id', $productId)->first();

    if ($item) {
        $item->increment('quantity');
        $this->loadCart();
    }
}

public function decrease($productId)
{
    $item = $this->cart->items()->where('product_id', $productId)->first();

    if ($item && $item->quantity > 1) {
        $item->decrement('quantity');
        $this->loadCart();
    }
}
public function getTotalProperty()
{
    return $this->cart->items->sum(function ($item) {
        return $item->quantity * $item->product->price;
    });
}
public function remove($productId)
{
    $this->cart->items()->where('product_id', $productId)->delete();
    $this->loadCart();
} 

public function checkout()
{
    if (!$this->cart || $this->cart->items->isEmpty()) {
        session()->flash('message', 'Cart is empty');
        return;
    }

    DB::transaction(function () {

        // حساب المجموع
        $total = $this->cart->items->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        // إنشاء الطلب
        $order = Order::create([
            'user_id' => Auth::id(),
            'total' => $total,
            'status' => 'pending',
        ]);

        foreach ($this->cart->items as $item) {

            $product = $item->product;

            // تحقق من المخزون
            if ($product->stock < $item->quantity) {
                throw new \Exception("Not enough stock for {$product->name}");
            }

            // تقليل المخزون
            $product->decrement('stock', $item->quantity);

            // إضافة للطلب
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $item->quantity,
                'price' => $product->price,
            ]);
        }

        // تفريغ الكارت
        $this->cart->items()->delete();
    });

    // تحديث الكارت
    $this->loadCart();

    session()->flash('message', 'Order placed successfully');
}
    public function render()
    {
        return view('livewire.cart-view');
    }
}