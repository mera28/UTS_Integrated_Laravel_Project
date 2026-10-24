<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseTransactionTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $staff;
    protected Supplier $supplier;
    protected Product $product;

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

        $this->supplier = Supplier::create([
            'name' => 'Haji Ridwan',
            'company_name' => 'CV Busana Muslimah Bandung',
            'phone' => '0812345678',
            'email' => 'supplier@test.com',
        ]);

        $category = Category::create([
            'name' => 'Gamis',
            'slug' => 'gamis',
        ]);

        $this->product = Product::create([
            'category_id' => $category->id,
            'sku' => 'ZF-GMS-999',
            'name' => 'Gamis Test Stock',
            'purchase_price' => 100000,
            'selling_price' => 200000,
            'stock' => 5,
            'minimum_stock' => 5,
        ]);
    }

    public function test_staff_and_admin_can_view_purchases(): void
    {
        $this->actingAs($this->staff)->get('/purchases')->assertStatus(200);
        $this->actingAs($this->staff)->get('/purchases/create')->assertStatus(200);
        $this->actingAs($this->admin)->get('/purchases')->assertStatus(200);
    }

    public function test_purchase_creation_increments_stock_and_calculates_total(): void
    {
        $initialStock = $this->product->stock; // 5

        $response = $this->actingAs($this->staff)->post('/purchases', [
            'supplier_id' => $this->supplier->id,
            'purchased_at' => now()->toDateTimeString(),
            'notes' => 'Restok 15 pcs baju gamis',
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => 15,
                    'unit_cost' => 120000,
                ],
            ],
        ]);

        $purchase = Purchase::latest()->first();
        $response->assertRedirect('/purchases/' . $purchase->id);

        // Verify stock increment (5 + 15 = 20)
        $this->assertEquals($initialStock + 15, $this->product->fresh()->stock);

        // Verify server-side total calculation (15 * 120,000 = 1,800,000)
        $this->assertEquals(1800000, (float) $purchase->total);
        $this->assertEquals('completed', $purchase->status);
    }

    public function test_admin_can_cancel_purchase_and_reverse_stock(): void
    {
        // First, create a purchase
        $this->actingAs($this->admin)->post('/purchases', [
            'supplier_id' => $this->supplier->id,
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => 10,
                    'unit_cost' => 100000,
                ],
            ],
        ]);

        $purchase = Purchase::latest()->first();
        $this->assertEquals(15, $this->product->fresh()->stock); // 5 + 10 = 15

        // Admin cancels the purchase
        $cancelResponse = $this->actingAs($this->admin)->patch('/purchases/' . $purchase->id . '/cancel');
        $cancelResponse->assertRedirect('/purchases/' . $purchase->id);

        $this->assertEquals('cancelled', $purchase->fresh()->status);
        // Stock should be decremented back (15 - 10 = 5)
        $this->assertEquals(5, $this->product->fresh()->stock);
    }

    public function test_staff_cannot_cancel_purchase(): void
    {
        $purchase = Purchase::create([
            'supplier_id' => $this->supplier->id,
            'user_id' => $this->admin->id,
            'purchase_number' => 'PO-TEST-001',
            'total' => 500000,
            'status' => 'completed',
        ]);

        $response = $this->actingAs($this->staff)->patch('/purchases/' . $purchase->id . '/cancel');
        $response->assertStatus(403);
    }

    public function test_cannot_cancel_purchase_if_stock_already_sold_out(): void
    {
        // Buy 10 pcs
        $this->actingAs($this->admin)->post('/purchases', [
            'supplier_id' => $this->supplier->id,
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => 10,
                    'unit_cost' => 100000,
                ],
            ],
        ]);

        $purchase = Purchase::latest()->first();

        // Simulate sales: stock drops to 2 pcs (less than the 10 pcs bought)
        $this->product->update(['stock' => 2]);

        $cancelResponse = $this->actingAs($this->admin)->patch('/purchases/' . $purchase->id . '/cancel');
        $cancelResponse->assertRedirect('/purchases/' . $purchase->id);
        $cancelResponse->assertSessionHas('error');

        // Status remains completed
        $this->assertEquals('completed', $purchase->fresh()->status);
        // Stock remains 2
        $this->assertEquals(2, $this->product->fresh()->stock);
    }
}
