<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;


class ProductList extends Component
{
   public $search = '';
public $category = '';
public $categories = [];

public function mount()
{
    $this->categories = Category::all();
}

    public function add($productId)
    {
        $cart = Cart::firstOrCreate([
            'user_id' => Auth::id()
        ]);

        $item = $cart->items()
            ->where('product_id', $productId)
            ->first();

        if ($item) {
            $item->update([
                'quantity' => $item->quantity + 1
            ]);
        } else {
            $cart->items()->create([
                'product_id' => $productId,
                'quantity' => 1,
            ]);
        }
       $this->dispatch('cartUpdated');
        // إضافة رسالة نجاح
        session()->flash('message', 'تم إضافة المنتج إلى السلة!');

       
        
    }

    
   public function render()
{
    $products =Product::query()
        ->when($this->search, function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%');
        })
        ->when($this->category, function ($query) {
            $query->where('category_id', $this->category);
        })
        ->get();

    return view('livewire.product-list', [
        'products' => $products
    ]);
}
}