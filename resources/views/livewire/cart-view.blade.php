<div>
    @if (session()->has('message'))
    <div>
        {{ session('message') }}
    </div>
@endif
    <h2>Cart</h2>

    @if($cart && $cart->items->count())
        @foreach($cart->items as $item)
            <div style="border:1px solid #ccc; margin:10px; padding:10px;">
                
                <h4>{{ $item->product->name }}</h4>
                <p>Price: {{ $item->product->price }}</p>

                <button wire:click="decrease({{ $item->product_id }})">-</button>
                {{ $item->quantity }}
                <button wire:click="increase({{ $item->product_id }})">+</button>

                <button wire:click="remove({{ $item->product_id }})">
                    Remove
                </button>
                
            </div>
        @endforeach
<button wire:click="checkout">
    Checkout
</button>
        <h3>Total: {{ $this->total }}</h3>

    @else
        <p>Cart is empty</p>
    @endif
</div>