<div>
    @if (session()->has('message'))
        <div class="rounded-2xl border border-sky-200 bg-sky-50 p-4 text-sm text-slate-800">
            {{ session('message') }}
        </div>
    @endif

    <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">Shopping Cart</h2>
                <p class="text-sm text-slate-500">Manage your cart items before checkout.</p>
            </div>
            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                {{ $cart && $cart->items->count() ? $cart->items->sum('quantity') : 0 }} items
            </span>
        </div>

        @if($cart && $cart->items->count())
            <div class="mt-6 space-y-4">
                @foreach($cart->items as $item)
                    <div class="flex flex-col gap-4 rounded-3xl border border-slate-200 bg-slate-50 p-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex flex-1 items-start gap-4">
                            @if($item->product->image)
                                <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="h-20 w-20 rounded-2xl object-cover" />
                            @else
                                <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-slate-200 text-slate-500">No image</div>
                            @endif

                            <div>
                                <h4 class="text-lg font-semibold text-slate-900">{{ $item->product->name }}</h4>
                                <p class="mt-1 text-sm text-slate-600">Unit price: ${{ number_format($item->product->price, 2) }}</p>
                                <p class="mt-1 text-sm font-medium text-slate-900">Subtotal: ${{ number_format($item->quantity * $item->product->price, 2) }}</p>
                            </div>
                        </div>

                        <div class="flex flex-col items-start gap-3 sm:items-end">
                            <div class="flex items-center gap-2 rounded-full bg-white px-2 py-1 shadow-sm">
                                <button wire:click="decrease({{ $item->product_id }})" class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-slate-300 text-slate-700 transition hover:bg-slate-100">-</button>
                                <span class="w-8 text-center text-sm font-semibold">{{ $item->quantity }}</span>
                                <button wire:click="increase({{ $item->product_id }})" class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-slate-300 text-slate-700 transition hover:bg-slate-100">+</button>
                            </div>
                            <button wire:click="remove({{ $item->product_id }})" class="rounded-full bg-red-500 px-3 py-1 text-sm font-semibold text-white transition hover:bg-red-600">Remove</button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 rounded-3xl border border-slate-200 bg-slate-100 p-5">
                <div class="flex items-center justify-between gap-4">
                    <span class="text-base font-medium text-slate-700">Total</span>
                    <span class="text-2xl font-semibold text-slate-900">${{ number_format($this->total, 2) }}</span>
                </div>

                <button wire:click="checkout" class="mt-4 w-full rounded-full bg-sky-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-sky-700">
                    Checkout
                </button>
            </div>
        @else
            <div class="mt-6 rounded-3xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center text-slate-600">
                <p class="text-lg font-semibold">Your cart is empty</p>
                <p class="mt-2 text-sm">Add some products from the catalog to get started.</p>
            </div>
        @endif
    </div>
</div>