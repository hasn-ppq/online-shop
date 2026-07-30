<div class="space-y-6">
    <div class="bg-white p-4 rounded-xl shadow-sm">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-slate-900">Products</h2>
                <p class="text-sm text-slate-500">Browse products and add them to your cart.</p>
            </div>

            <div class="grid gap-3 sm:grid-cols-2 md:flex md:items-center md:gap-4">
                <label class="block w-full">
                    <span class="sr-only">Search products</span>
                    <input
                        type="text"
                        wire:model.live="search"
                        placeholder="Search products..."
                        class="w-full rounded-lg border border-slate-300 px-4 py-2 text-slate-900 outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-200"
                    />
                </label>

                <label class="block w-full">
                    <span class="sr-only">Filter by category</span>
                    <select
                        wire:model.live="category"
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2 text-slate-900 outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-200"
                    >
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </label>
            </div>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-[1.5fr_0.9fr]">
        <div class="space-y-6">
            @if($products->isEmpty())
                <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center text-slate-600">
                    No products match your search.
                </div>
            @else
                <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach($products as $product)
                        <div class="flex h-full flex-col overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:shadow-md">
                            @if($product->image)
                                <img
                                    src="{{ asset('storage/' . $product->image) }}"
                                    alt="{{ $product->name }}"
                                    class="h-48 w-full object-cover"
                                >
                            @else
                                <div class="flex h-48 items-center justify-center bg-slate-100 text-slate-500">
                                    No image
                                </div>
                            @endif

                            <div class="flex flex-1 flex-col p-5">
                                <div class="mb-4">
                                    <h3 class="text-xl font-semibold text-slate-900">{{ $product->name }}</h3>
                                    <p class="mt-2 text-sm leading-6 text-slate-600">{{ $product->description ?? 'No description available.' }}</p>
                                </div>

                                <div class="mt-auto flex flex-col gap-3">
                                    <div class="flex items-center justify-between text-slate-900">
                                        <span class="text-lg font-semibold">${{ number_format($product->price, 2) }}</span>
                                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs text-slate-600">Stock: {{ $product->stock }}</span>
                                    </div>

                                    <button
                                        wire:click="add({{ $product->id }})"
                                        class="inline-flex items-center justify-center rounded-full bg-sky-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-400"
                                    >
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div>
            <div class="sticky top-6 space-y-4 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <livewire:cart-view />
            </div>
        </div>
    </div>
</div>