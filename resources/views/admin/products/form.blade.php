<x-layouts.app>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-slate-900">{{ $product->exists ? 'Edit Product' : 'Create Product' }}</h2>
    </x-slot>

    <div class="container mx-auto p-6">
        <div class="max-w-3xl bg-white rounded-2xl p-6 shadow">
        <h2 class="text-xl font-semibold text-slate-800 mb-4">{{ $product->exists ? 'Edit Product' : 'Create Product' }}</h2>

        <form method="POST" action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}" enctype="multipart/form-data">
            @csrf
            @if($product->exists)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 gap-4">
                <label class="block">
                    <span class="text-sm text-slate-600">Name</span>
                    <input name="name" value="{{ old('name', $product->name) }}" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm" required>
                </label>

                <label class="block">
                    <span class="text-sm text-slate-600">Slug (optional)</span>
                    <input name="slug" value="{{ old('slug', $product->slug) }}" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm">
                </label>

                <label class="block">
                    <span class="text-sm text-slate-600">Description</span>
                    <textarea name="description" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm">{{ old('description', $product->description) }}</textarea>
                </label>

                <div class="grid grid-cols-2 gap-4">
                    <label class="block">
                        <span class="text-sm text-slate-600">Price</span>
                        <input name="price" type="number" step="0.01" value="{{ old('price', $product->price) }}" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm" required>
                    </label>

                    <label class="block">
                        <span class="text-sm text-slate-600">Stock</span>
                        <input name="stock" type="number" value="{{ old('stock', $product->stock) }}" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm" required>
                    </label>
                </div>

                <label class="block">
                    <span class="text-sm text-slate-600">Category</span>
                    <select name="category_id" class="mt-1 block w-full rounded-md border-gray-200 shadow-sm" required>
                        <option value="">Choose category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ (old('category_id', $product->category_id) == $cat->id) ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="block">
                    <span class="text-sm text-slate-600">Image</span>
                    <input type="file" name="image" class="mt-1 block w-full">
                    @if($product->image)
                        <div class="mt-2"><img src="{{ asset('storage/'.$product->image) }}" class="w-32 h-32 object-cover rounded"></div>
                    @endif
                </label>

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('admin.products.index') }}" class="text-sm text-slate-600">Cancel</a>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-teal-600 px-4 py-2 text-white hover:bg-teal-700">Save Product</button>
                </div>
            </div>
        </form>
        </div>
    </div>
</x-layouts.app>
