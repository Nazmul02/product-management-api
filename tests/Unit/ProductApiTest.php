<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use  Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_creation()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/products', [
            'name' => 'Test Product',
            'description' => 'This is a test product',
            'price' => 100.00,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('products', ['name' => 'Test Product']);
    }

    public function test_unauthenticated_product_creation()
    {
        $response = $this->postJson('/api/products', [
            'name' => 'Test Product',
            'description' => 'This is a test product',
            'price' => 100.00,
        ]);

        $response->assertStatus(401);
    }

    public function test_product_update()
    {
        $user = User::factory()->create();
        $product = Product::factory()->for($user)->create();

        $response = $this->actingAs($user, 'sanctum')->putJson("/api/products/{$product->id}", [
            'name' => 'Updated Product Name',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('products', ['name' => 'Updated Product Name']);
    }

    public function test_product_delete()
    {
        $user = User::factory()->create();
        $product = Product::factory()->for($user)->create();

        $response = $this->actingAs($user, 'sanctum')->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_guest_can_view_products()
    {
        Product::factory()->count(5)->create();

        $response = $this->getJson('/api/products');
        $response->assertStatus(200);
        $response->assertJsonCount(5);
    }

    public function test_create_product_with_missing_fields()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/products', [
            'description' => 'Missing name and price',
        ]);

        $response->assertStatus(422); // Validation error
        $response->assertJsonValidationErrors(['name', 'price']);
    }

    public function test_delete_non_existent_product()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->deleteJson('/api/products/999');

        $response->assertStatus(404); // Not found
    }

}
