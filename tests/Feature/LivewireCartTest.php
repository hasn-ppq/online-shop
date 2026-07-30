<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Livewire\Livewire;
use App\Livewire\ProductList;
use App\Livewire\CartView;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;

class LivewireCartTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_add_product_to_session_cart_and_cartview_shows_it()
    {
        // Arrange: create category and product
        $category = Category::factory()->create();
        $product = Product::factory()->create([ 'category_id' => $category->id, 'stock' => 5 ]);

        // Act: as guest, call ProductList->add
        Livewire::test(ProductList::class)
            ->call('add', $product->id);

        // Assert: session contains guest_cart with the product
        $this->assertEquals(1, session('guest_cart')[$product->id] ?? 0);

        // CartView should render the product name and total
        $this->withSession(['guest_cart' => [ $product->id => 1 ]]);

        Livewire::test(CartView::class)
            ->assertSee($product->name)
            ->assertSee(number_format($product->price, 2));
    }

    public function test_authenticated_user_can_add_and_checkout_creates_order_and_decrements_stock()
    {
        // Arrange: user, category, product
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create([ 'category_id' => $category->id, 'stock' => 5, 'price' => 12.50 ]);

        // Act: as authenticated user, add product to cart
        $this->actingAs($user);

        Livewire::test(ProductList::class)
            ->call('add', $product->id);

        // Ensure cart stored in DB
        $this->assertDatabaseHas('carts', ['user_id' => $user->id]);

        // Perform checkout via CartView
        Livewire::actingAs($user)
            ->test(CartView::class)
            ->call('checkout');

        // Assert: order created for user with expected total
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'total' => 12.50,
            'status' => 'pending',
        ]);

        $order = Order::where('user_id', $user->id)->first();
        $this->assertNotNull($order);

        // Assert: order_items contains the product
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $product->price,
        ]);

        // Assert: product stock decremented
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 4,
        ]);

        // Assert: cart items removed
        $cart = \App\Models\Cart::where('user_id', $user->id)->first();
        if ($cart) {
            $this->assertDatabaseMissing('cart_items', ['cart_id' => $cart->id]);
        }
    }
}
