<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_categories(): void
    {
        Category::create([
            'name' => 'Bags',
            'slug' => 'bags',
            'status' => 'active',
        ]);

        $response = $this->getJson('/api/v1/categories');

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Bags']);
    }

    public function test_can_list_products_with_filters(): void
    {
        $cat = Category::create([
            'name' => 'Outerwear',
            'slug' => 'outerwear',
            'status' => 'active',
        ]);

        $product = Product::create([
            'sku' => 'jacket-chore',
            'name' => 'Chore Field Jacket',
            'slug' => 'chore-field-jacket-1',
            'base_price' => 148,
            'status' => 'active',
            'is_active' => true,
        ]);
        $product->categories()->attach($cat->id);

        // List all
        $response = $this->getJson('/api/v1/products');
        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Chore Field Jacket']);

        // Filter by category
        $responseCategory = $this->getJson('/api/v1/products?category=Outerwear');
        $responseCategory->assertStatus(200)
            ->assertJsonFragment(['name' => 'Chore Field Jacket']);

        // Search by query
        $responseSearch = $this->getJson('/api/v1/products?q=Chore');
        $responseSearch->assertStatus(200)
            ->assertJsonFragment(['name' => 'Chore Field Jacket']);
    }

    public function test_can_create_product(): void
    {
        $payload = [
            'name' => 'New Leather Boot',
            'sku' => 'boot-new-1',
            'base_price' => 199.99,
            'short_description' => 'A durable leather boot',
            'colors' => ['Black', 'Brown'],
            'stock' => 10,
        ];

        $response = $this->postJson('/api/v1/products', $payload);

        $response->assertStatus(210)
            ->assertJsonPath('data.name', 'New Leather Boot')
            ->assertJsonPath('data.sku', 'boot-new-1');

        $this->assertDatabaseHas('products', ['sku' => 'boot-new-1']);
    }

    public function test_can_show_single_product_by_id_or_sku(): void
    {
        $product = Product::create([
            'sku' => 'mug-stoneware',
            'name' => 'Stoneware Mug',
            'slug' => 'stoneware-mug',
            'base_price' => 22,
            'status' => 'active',
            'is_active' => true,
        ]);

        $response = $this->getJson("/api/v1/products/{$product->sku}");

        $response->assertStatus(200)
            ->assertJsonPath('data.name', 'Stoneware Mug');
    }

    public function test_can_update_product(): void
    {
        $product = Product::create([
            'sku' => 'belt-fullgrain',
            'name' => 'Full-Grain Belt',
            'slug' => 'full-grain-belt',
            'base_price' => 58,
            'status' => 'active',
            'is_active' => true,
        ]);

        $response = $this->putJson("/api/v1/products/{$product->id}", [
            'name' => 'Updated Belt Name',
            'base_price' => 65.00,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.name', 'Updated Belt Name')
            ->assertJsonPath('data.price', 65);

        $this->assertDatabaseHas('products', ['name' => 'Updated Belt Name']);
    }

    public function test_can_delete_product(): void
    {
        $product = Product::create([
            'sku' => 'wallet-card',
            'name' => 'Card Holder Wallet',
            'slug' => 'card-holder-wallet',
            'base_price' => 34,
            'status' => 'active',
            'is_active' => true,
        ]);

        $response = $this->deleteJson("/api/v1/products/{$product->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
