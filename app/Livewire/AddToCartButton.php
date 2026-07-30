<?php

namespace App\Livewire;

use Livewire\Component;

class AddToCartButton extends Component
{
    public $productId;

    public function add()
    {
        // Dispatch an event with the product id so parent components can react
        $this->dispatch('add-to-cart', ['id' => $this->productId]);
    }

    public function render()
    {
        return view('livewire.add-to-cart-button');
    }
}