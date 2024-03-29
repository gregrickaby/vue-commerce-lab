<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test accessing the orders route without authentication.
     */
    public function test_access_orders_route_without_authentication(): void
    {

        // Access the orders route.
        $response = $this->get('/orders');

        // Assert that the user is redirected back to the login page.
        $response->assertRedirect('/login');
    }

    /**
     * Test accessing the orders route with authentication.
     */
    public function test_access_orders_route_with_authentication(): void
    {
        // Create a user.
        $user = User::factory()->create();

        // Authenticate as the user.
        $this->actingAs($user);

        // Access the orders route.
        $response = $this->get('/orders');

        // Assert successful response code.
        $response->assertStatus(200);
    }

    /**
     * Test deleting an order.
     */
    public function test_order_deletion(): void
    {
        // Create a user.
        $user = User::factory()->create();

        // Authenticate as the user.
        $this->actingAs($user);

        // Create a product for the foreign key dependency.
        $product = Product::factory()->create();

        // Create a customer for the foreign key dependency.
        $customer = Customer::factory()->create();

        // Create an order.
        $order = Order::factory()->create([
            'customer_id' => $customer->id,
        ]);

        // Send DELETE request to delete the order.
        $response = $this->delete(route('orders.destroy', $order));

        // Assert that the response is a redirect to the orders index route.
        $response->assertRedirect(route('orders.index'));

        // Assert the order is deleted from the database.
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }
}
