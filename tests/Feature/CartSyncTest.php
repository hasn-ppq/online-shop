<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Category;
use Illuminate\Auth\Events\Login;

class CartSyncTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cart_is_merged_on_login()
    {
        // Create a category, a product and a user
        $category = \App\Models\Category::factory()->create(['name' => 'Test', 'slug' => 'test']);
        $product = Product::factory()->create(['stock' => 10, 'category_id' => $category->id]);
        $user = User::factory()->create();

        // Simulate guest cart in session
        $this->withSession(['guest_cart' => [$product->id => 3]]);

        // Fire the Login event (simulate login)
        event(new Login('web', $user, false));

        // Assert cart created for user
        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
        ]);

        $cart = Cart::where('user_id', $user->id)->first();

        $this->assertNotNull($cart);

        // Assert cart item created
        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 3,
        ]);

        // Session guest_cart should be cleared
        $this->assertEmpty(session('guest_cart', []));
    }
}
