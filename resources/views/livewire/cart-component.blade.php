<div>
    <h2>Cart</h2>

    @foreach($cart->items as $item)
        <div>
            <h4>{{ $item->product->name }}</h4>
            <p>{{ $item->product->price }}</p>

            <button wire:click="decrease({{ $item->product_id }})">-</button>
            {{ $item->quantity }}
            <button wire:click="increase({{ $item->product_id }})">+</button>

            <button wire:click="remove({{ $item->product_id }})">
                Remove
            </button>
        </div>
    @endforeach

    <h3>Total: {{ $this->total }}</h3>
</div>