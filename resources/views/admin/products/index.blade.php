<x-layouts.app>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-slate-900">Manage Products</h1>
            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-teal-600 px-4 py-2 text-white hover:bg-teal-700">+ Add Product</a>
        </div>
    </x-slot>

    <div class="container mx-auto p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-slate-800">Manage Products</h1>
        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-teal-600 px-4 py-2 text-white hover:bg-teal-700">+ Add Product</a>
    </div>

    <div class="flex gap-6">
        <aside class="w-1/4">
            <div class="bg-white rounded-lg p-4 shadow">
                <h3 class="font-medium text-slate-700 mb-3">Categories</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('admin.products.index') }}" class="text-sm text-slate-600 {{ empty($selected) ? 'font-semibold text-teal-700' : '' }}">All ({{ \App\Models\Product::count() }})</a></li>
                    @foreach($categories as $cat)
                        <li>
                            <a href="{{ route('admin.products.index', ['category' => $cat->id]) }}" class="text-sm text-slate-600 {{ (string)$selected === (string)$cat->id ? 'font-semibold text-teal-700' : '' }}">{{ $cat->name }} ({{ $cat->products_count }})</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </aside>

        <main class="flex-1">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($products as $product)
                    <div class="bg-white rounded-2xl p-4 shadow hover:shadow-md transition">
                        <div class="flex items-center gap-4">
                            <div class="w-20 h-20 bg-slate-100 rounded-lg flex items-center justify-center overflow-hidden">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" class="object-cover w-full h-full" alt="{{ $product->name }}">
                                @else
                                    <span class="text-slate-400 text-sm">No image</span>
                                @endif
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-slate-800">{{ $product->name }}</h3>
                                <div class="text-sm text-slate-500">{{ $product->category?->name ?? 'Uncategorized' }}</div>
                                <div class="mt-2 text-teal-600 font-medium">${{ number_format($product->price,2) }}</div>
                            </div>
                        </div>

                        <div class="mt-4 flex items-center justify-between">
                            <div class="text-sm text-slate-600">Stock: {{ $product->stock }}</div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.products.edit', $product) }}" class="text-sm text-indigo-600 hover:underline">Edit</a>
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete this product?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-600 hover:underline">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $products->withQueryString()->links() }}
            </div>
        </main>
    </div>
    </div>
</x-layouts.app>
