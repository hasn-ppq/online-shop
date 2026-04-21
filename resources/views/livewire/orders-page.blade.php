<div>
    <h2>My Orders</h2>

    @forelse($orders as $order)
        <div style="border:1px solid #ccc; margin:10px; padding:10px;">

            <h3>Order #{{ $order->id }}</h3>
            <p>Status: {{ $order->status }}</p>
            <p>Total: {{ $order->total }}</p>

            <hr>

            @foreach($order->items as $item)
                <div>
                    {{ $item->product->name }} -
                    Qty: {{ $item->quantity }} -
                    Price: {{ $item->price }}
                </div>
            @endforeach

        </div>
    @empty
        <p>No orders yet</p>
    @endforelse
</div>