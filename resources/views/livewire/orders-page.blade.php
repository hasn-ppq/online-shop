<div class="space-y-6">
    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-2xl font-semibold text-slate-900">My Orders</h2>
        <p class="mt-1 text-sm text-slate-500">Review your completed purchases and order details.</p>
    </div>

    @forelse($orders as $order)
        <div class="space-y-4 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-xl font-semibold text-slate-900">Order #{{ $order->id }}</h3>
                    <p class="text-sm text-slate-500">Placed {{ $order->created_at->diffForHumans() }}</p>
                </div>
                <div class="space-y-1 text-right">
                    <p class="text-sm text-slate-600">Status: <span class="font-semibold text-slate-900">{{ ucfirst($order->status) }}</span></p>
                    <p class="text-lg font-semibold text-slate-900">Total: ${{ number_format($order->total, 2) }}</p>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-100 bg-slate-50 p-4">
                <h4 class="text-sm font-semibold uppercase tracking-wide text-slate-600">Items</h4>
                <div class="mt-4 space-y-3">
                    @foreach($order->items as $item)
                        <div class="flex flex-col gap-2 rounded-2xl bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="font-medium text-slate-900">{{ $item->product->name }}</p>
                                <p class="text-sm text-slate-500">Qty: {{ $item->quantity }}</p>
                            </div>
                            <p class="text-sm font-semibold text-slate-900">${{ number_format($item->price, 2) }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @empty
        <div class="rounded-3xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center text-slate-600">
            <p class="text-lg font-semibold">No orders yet</p>
            <p class="mt-2 text-sm">Your completed orders will appear here once you checkout.</p>
        </div>
    @endforelse
</div>