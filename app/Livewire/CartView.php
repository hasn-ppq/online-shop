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
        // Authenticated user: load DB cart
        if (Auth::check()) {
            $this->cart = Cart::with('items.product')
                ->where('user_id', Auth::id())
                ->first();

            return;
        }

        // Guest: build a lightweight cart object from session
        $guestCart = session('guest_cart', []);

        $items = collect();

        if (!empty($guestCart)) {
            $productIds = array_keys($guestCart);
            $products = \App\Models\Product::whereIn('id', $productIds)->get()->keyBy('id');

            foreach ($guestCart as $pid => $qty) {
                if (isset($products[$pid])) {
                    $item = new \stdClass();
                    $item->product = $products[$pid];
                    $item->product_id = $pid;
                    $item->quantity = $qty;
                    $items->push($item);
                }
            }
        }

        $cart = new \stdClass();
        $cart->items = $items;

        $this->cart = $cart;
    }
    public function increase($productId)
{
    // Authenticated cart
    if (Auth::check()) {
        if (!$this->cart) {
            session()->flash('message', 'Cart not available.');
            return;
        }

        $item = $this->cart->items()->where('product_id', $productId)->first();

        if ($item) {
            $item->increment('quantity');
            $this->loadCart();
        }

        return;
    }

    // Guest cart (session)
    $guestCart = session('guest_cart', []);

    if (isset($guestCart[$productId])) {
        $guestCart[$productId] = $guestCart[$productId] + 1;
        session(['guest_cart' => $guestCart]);
        $this->loadCart();
    }
}

public function decrease($productId)
{
    // Authenticated cart
    if (Auth::check()) {
        if (!$this->cart) {
            session()->flash('message', 'Cart not available.');
            return;
        }

        $item = $this->cart->items()->where('product_id', $productId)->first();

        if ($item && $item->quantity > 1) {
            $item->decrement('quantity');
            $this->loadCart();
        }

        return;
    }

    // Guest cart (session)
    $guestCart = session('guest_cart', []);

    if (isset($guestCart[$productId]) && $guestCart[$productId] > 1) {
        $guestCart[$productId] = $guestCart[$productId] - 1;
        session(['guest_cart' => $guestCart]);
        $this->loadCart();
    }
}
public function getTotalProperty()
{
    if (!$this->cart || $this->cart->items->isEmpty()) {
        return 0;
    }

    return $this->cart->items->sum(function ($item) {
        return $item->quantity * $item->product->price;
    });
}
public function remove($productId)
{
    // Authenticated cart
    if (Auth::check()) {
        if (!$this->cart) {
            session()->flash('message', 'Cart not available.');
            return;
        }

        $this->cart->items()->where('product_id', $productId)->delete();
        $this->loadCart();

        return;
    }

    // Guest cart (session)
    $guestCart = session('guest_cart', []);

    if (isset($guestCart[$productId])) {
        unset($guestCart[$productId]);
        session(['guest_cart' => $guestCart]);
        $this->loadCart();
    }
}

public function checkout()
{
    if (!Auth::check()) {
        session()->flash('message', 'يرجى تسجيل الدخول لإتمام الدفع.');
        return redirect()->route('login');
    }

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