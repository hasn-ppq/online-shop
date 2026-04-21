<?php

namespace App\Livewire;

use Livewire\Component;

class AddToCartButton extends Component
{
    public $productId;

    public function add()
    {
        $this->dispatch('add-to-cart', id: $this->productId);
    }

    public function render()
    {
        return view('livewire.add-to-cart-button');
    }
}