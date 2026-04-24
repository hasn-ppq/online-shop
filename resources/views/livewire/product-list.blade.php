<div>

    <h2>Products</h2>

    <!-- Search -->
    <input type="text"
           wire:model.live="search"
           placeholder="Search products..." />

    <!-- Category Filter -->
    <select wire:model.live="category">
        <option value="">All Categories</option>

        @foreach($categories as $cat)
            <option value="{{ $cat->id }}">
                {{ $cat->name }}

            </option>
        @endforeach
    </select>

    <hr>

    @foreach($products as $product)
        <div>
            <h3>{{ $product->name }}</h3>
            <p>{{ $product->price }}</p>

            @if($product->image)
               <img src="{{ asset('storage/' . $product->image) }}"
     width="120"
     style="border-radius:8px;">
            @endif

            <button wire:click="add({{ $product->id }})">
                Add to Cart
            </button>
        </div>
    @endforeach

    <livewire:cart-view />

</div>