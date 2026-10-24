<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterDataTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $staff;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Owner',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->staff = User::create([
            'name' => 'Staff Kasir',
            'email' => 'staff@test.com',
            'password' => bcrypt('password'),
            'role' => 'staff',
        ]);
    }

    public function test_admin_can_view_and_create_categories(): void
    {
        $response = $this->actingAs($this->admin)->get('/categories');
        $response->assertStatus(200);

        $postResponse = $this->actingAs($this->admin)->post('/categories', [
            'name' => 'Koleksi Syar\'i',
            'description' => 'Gamis syar\'i premium',
        ]);

        $postResponse->assertRedirect('/categories');
        $this->assertDatabaseHas('categories', ['name' => 'Koleksi Syar\'i']);
    }

    public function test_staff_cannot_manage_categories(): void
    {
        $response = $this->actingAs($this->staff)->get('/categories');
        $response->assertStatus(403);
    }

    public function test_staff_can_view_products_but_cannot_create_or_delete(): void
    {
        $category = Category::create(['name' => 'Gamis', 'slug' => 'gamis']);
        $product = Product::create([
            'category_id' => $category->id,
            'sku' => 'ZF-TEST-001',
            'name' => 'Gamis Test',
            'purchase_price' => 100000,
            'selling_price' => 200000,
            'stock' => 10,
            'minimum_stock' => 5,
        ]);

        // Staff can view list and detail
        $this->actingAs($this->staff)->get('/products')->assertStatus(200);
        $this->actingAs($this->staff)->get('/products/' . $product->id)->assertStatus(200);

        // Staff is forbidden from create and delete
        $this->actingAs($this->staff)->get('/products/create')->assertStatus(403);
        $this->actingAs($this->staff)->delete('/products/' . $product->id)->assertStatus(403);
    }

    public function test_admin_can_soft_delete_product(): void
    {
        $category = Category::create(['name' => 'Abaya', 'slug' => 'abaya']);
        $product = Product::create([
            'category_id' => $category->id,
            'sku' => 'ZF-ABY-001',
            'name' => 'Abaya Silk',
            'purchase_price' => 150000,
            'selling_price' => 250000,
            'stock' => 8,
            'minimum_stock' => 5,
        ]);

        $response = $this->actingAs($this->admin)->delete('/products/' . $product->id);
        $response->assertRedirect('/products');

        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    public function test_cannot_delete_category_that_has_products(): void
    {
        $category = Category::create(['name' => 'Tunik', 'slug' => 'tunik']);
        Product::create([
            'category_id' => $category->id,
            'sku' => 'ZF-TNK-001',
            'name' => 'Tunik Rayon',
            'purchase_price' => 90000,
            'selling_price' => 160000,
            'stock' => 10,
            'minimum_stock' => 5,
        ]);

        $response = $this->actingAs($this->admin)->delete('/categories/' . $category->id);
        $response->assertRedirect('/categories');
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    public function test_admin_cannot_delete_own_account(): void
    {
        $response = $this->actingAs($this->admin)->delete('/users/' . $this->admin->id);
        $response->assertRedirect('/users');
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }
}
