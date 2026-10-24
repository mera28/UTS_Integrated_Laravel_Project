<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Comprehensive Feature Tests for Slide 19 PPT Specifications:
 * Authentication, Inventory Integrity, Stock Atomicity, and Role Authorization.
 */
class InventorySalesSystemTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $staff;
    protected User $customer;
    protected Category $category;
    protected Supplier $supplier;
    protected Product $productA;
    protected Product $productB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Owner',
            'email' => 'admin@zahiyyah.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->staff = User::create([
            'name' => 'Staff Kasir',
            'email' => 'staff@zahiyyah.com',
            'password' => bcrypt('password'),
            'role' => 'staff',
        ]);

        $this->customer = User::create([
            'name' => 'Customer Belanja',
            'email' => 'customer@zahiyyah.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        $this->category = Category::create([
            'name' => 'Gamis Maxy Dress',
            'slug' => 'gamis-maxy-dress',
        ]);

        $this->supplier = Supplier::create([
            'name' => 'Haji Ridwan',
            'company_name' => 'CV Busana Muslim Bandung',
            'phone' => '08123456789',
            'email' => 'supplier@zahiyyah.com',
        ]);

        // Product A: 10 units available
        $this->productA = Product::create([
            'category_id' => $this->category->id,
            'sku' => 'ZF-GMS-001',
            'name' => 'Gamis Aisyah Silk',
            'purchase_price' => 120000,
            'selling_price' => 250000,
            'stock' => 10,
            'minimum_stock' => 5,
        ]);

        // Product B: 4 units available
        $this->productB = Product::create([
            'category_id' => $this->category->id,
            'sku' => 'ZF-GMS-002',
            'name' => 'Gamis Fatima Velvet',
            'purchase_price' => 150000,
            'selling_price' => 290000,
            'stock' => 4,
            'minimum_stock' => 5,
        ]);
    }

    /**
     * Skenario 1 (Slide 19): Guest diarahkan ke /login; password salah ditolak.
     */
    public function test_guest_cannot_access_dashboard_and_invalid_password_is_rejected(): void
    {
        // 1. Guest access to dashboard redirects to login
        $guestResponse = $this->get(route('dashboard'));
        $guestResponse->assertRedirect(route('login'));

        // 2. Login with wrong password is rejected with validation error
        $loginFailedResponse = $this->post(route('login'), [
            'email' => 'admin@zahiyyah.com',
            'password' => 'wrongpassword123',
        ]);

        $loginFailedResponse->assertSessionHasErrors(['email']);
        $this->assertGuest();

        // 3. Login with correct password succeeds
        $loginSuccessResponse = $this->post(route('login'), [
            'email' => 'admin@zahiyyah.com',
            'password' => 'password',
        ]);

        $loginSuccessResponse->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($this->admin);
    }

    /**
     * Skenario 2 (Slide 19): Validasi server menolak SKU kembar dan harga beli/jual bernilai negatif.
     */
    public function test_duplicate_sku_and_negative_price_are_rejected(): void
    {
        // 1. Attempt to create product with existing SKU 'ZF-GMS-001'
        $duplicateSkuResponse = $this->actingAs($this->admin)->post(route('products.store'), [
            'category_id' => $this->category->id,
            'sku' => 'ZF-GMS-001', // Already exists!
            'name' => 'Produk Duplikat',
            'purchase_price' => 100000,
            'selling_price' => 150000,
            'stock' => 10,
            'minimum_stock' => 5,
        ]);

        $duplicateSkuResponse->assertSessionHasErrors(['sku']);

        // 2. Attempt to create product with negative prices
        $negativePriceResponse = $this->actingAs($this->admin)->post(route('products.store'), [
            'category_id' => $this->category->id,
            'sku' => 'ZF-GMS-099',
            'name' => 'Produk Harga Negatif',
            'purchase_price' => -50000, // Invalid negative price
            'selling_price' => -80000,  // Invalid negative price
            'stock' => 5,
            'minimum_stock' => 2,
        ]);

        $negativePriceResponse->assertSessionHasErrors(['purchase_price', 'selling_price']);
    }

    /**
     * Skenario 3 (Slide 19): Transaksi Purchase (Stock-In) berhasil menambah stok produk sesuai kuantitas.
     */
    public function test_purchase_transaction_increments_product_stock_correctly(): void
    {
        $initialStock = $this->productA->stock; // 10

        $response = $this->actingAs($this->staff)->post(route('purchases.store'), [
            'supplier_id' => $this->supplier->id,
            'notes' => 'Pasokan batch 1 konveksi',
            'items' => [
                [
                    'product_id' => $this->productA->id,
                    'quantity' => 15,
                    'unit_cost' => 120000,
                ],
            ],
        ]);

        $response->assertRedirect();

        // Check purchase recorded
        $purchase = Purchase::latest()->first();
        $this->assertNotNull($purchase);
        $this->assertEquals(15 * 120000, (float) $purchase->total);
        $this->assertEquals('completed', $purchase->status);

        // Product A stock must increase from 10 to 25
        $this->productA->refresh();
        $this->assertEquals($initialStock + 15, $this->productA->stock);
    }

    /**
     * Skenario 4 (Slide 19): Transaksi Sale (Stock-Out) berhasil mengurangi stok produk dan menghitung total di server.
     */
    public function test_sale_transaction_decrements_product_stock_correctly(): void
    {
        $initialStock = $this->productA->stock; // 10

        $response = $this->actingAs($this->staff)->post(route('sales.store'), [
            'customer_name' => 'Ibu Rahmawati',
            'customer_phone' => '081299887766',
            'payment_method' => 'cash',
            'notes' => 'Pembelian langsung di kasir toko',
            'items' => [
                [
                    'product_id' => $this->productA->id,
                    'selected_size' => 'M',
                    'selected_color' => 'Navy',
                    'quantity' => 3,
                ],
            ],
        ]);

        $response->assertRedirect();

        // Check sale recorded with server-side calculated price (3 * 250000 = 750000)
        $sale = Sale::latest()->first();
        $this->assertNotNull($sale);
        $this->assertEquals(750000, (float) $sale->total);
        $this->assertEquals('completed', $sale->status);
        $this->assertEquals('paid', $sale->payment_status);

        // Product A stock must decrement from 10 to 7
        $this->productA->refresh();
        $this->assertEquals($initialStock - 3, $this->productA->stock);
    }

    /**
     * Skenario 5 (Slide 19): Jika stok tersedia 4 tetapi dibeli 6, transaksi ditolak dan stok produk serta tabel sales tidak berubah.
     */
    public function test_sale_with_insufficient_stock_is_rejected_without_changing_data(): void
    {
        // Product B currently has 4 units in stock
        $initialStock = $this->productB->stock;
        $initialSalesCount = Sale::count();

        $response = $this->actingAs($this->staff)->post(route('sales.store'), [
            'customer_name' => 'Pembeli Borongan',
            'payment_method' => 'cash',
            'items' => [
                [
                    'product_id' => $this->productB->id,
                    'quantity' => 6, // 6 requested > 4 available!
                ],
            ],
        ]);

        // Validation error must be triggered
        $response->assertSessionHasErrors(['items']);

        // Database must remain intact
        $this->productB->refresh();
        $this->assertEquals($initialStock, $this->productB->stock); // Still 4!
        $this->assertEquals($initialSalesCount, Sale::count());     // No sale created!
    }

    /**
     * Skenario 6 (Slide 19): Transaksi atomik multi-item me-rollback seluruh item jika satu item gagal stok.
     */
    public function test_transaction_atomicity_rolls_back_all_items_if_one_item_fails(): void
    {
        // Product A has 10 units (Valid request: 2)
        // Product B has 4 units (Invalid request: 9 > 4)
        $initialStockA = $this->productA->stock;
        $initialStockB = $this->productB->stock;
        $initialSalesCount = Sale::count();

        $response = $this->actingAs($this->staff)->post(route('sales.store'), [
            'customer_name' => 'Uji Transaksi Atomik',
            'payment_method' => 'cash',
            'items' => [
                [
                    'product_id' => $this->productA->id,
                    'quantity' => 2, // Valid
                ],
                [
                    'product_id' => $this->productB->id,
                    'quantity' => 9, // Invalid! (Exceeds stock of 4)
                ],
            ],
        ]);

        $response->assertSessionHasErrors(['items']);

        // Due to DB::transaction and lockForUpdate, Product A must NOT be decremented!
        $this->productA->refresh();
        $this->productB->refresh();
        $this->assertEquals($initialStockA, $this->productA->stock); // Still 10!
        $this->assertEquals($initialStockB, $this->productB->stock); // Still 4!
        $this->assertEquals($initialSalesCount, Sale::count());
    }

    /**
     * Skenario 7 (Slide 19): Staff & Customer yang mengakses route khusus Admin ditolak oleh server (403 Forbidden).
     */
    public function test_staff_and_customer_cannot_access_admin_only_routes(): void
    {
        // 1. Staff attempts to create or delete product -> 403 Forbidden
        $staffProductCreate = $this->actingAs($this->staff)->get(route('products.create'));
        $staffProductCreate->assertForbidden();

        $staffProductDelete = $this->actingAs($this->staff)->delete(route('products.destroy', $this->productA));
        $staffProductDelete->assertForbidden();

        // 2. Staff attempts to manage users or categories -> 403 Forbidden
        $staffCategoryCreate = $this->actingAs($this->staff)->get(route('categories.create'));
        $staffCategoryCreate->assertForbidden();

        $staffUserIndex = $this->actingAs($this->staff)->get(route('users.index'));
        $staffUserIndex->assertForbidden();

        // 3. Customer attempts to access backoffice dashboard or sales -> 403 Forbidden
        $customerDashboard = $this->actingAs($this->customer)->get(route('dashboard'));
        $customerDashboard->assertForbidden();

        $customerSales = $this->actingAs($this->customer)->get(route('sales.index'));
        $customerSales->assertForbidden();
    }

    /**
     * Skenario 8 (Slide 19): Pencarian SKU/nama dan filter kategori/low-stock menghasilkan data yang sesuai.
     */
    public function test_product_search_filter_and_pagination_work_properly(): void
    {
        // 1. Search by SKU
        $searchResponse = $this->actingAs($this->staff)->get(route('products.index', [
            'search' => 'ZF-GMS-001',
        ]));
        $searchResponse->assertOk();
        $searchResponse->assertSee('Gamis Aisyah Silk');
        $searchResponse->assertDontSee('Gamis Fatima Velvet');

        // 2. Filter by Category
        $categoryFilterResponse = $this->actingAs($this->staff)->get(route('products.index', [
            'category_id' => $this->category->id,
        ]));
        $categoryFilterResponse->assertOk();
        $categoryFilterResponse->assertSee('Gamis Aisyah Silk');
        $categoryFilterResponse->assertSee('Gamis Fatima Velvet');

        // 3. Filter by Stock Status 'low'
        $lowStockFilterResponse = $this->actingAs($this->staff)->get(route('products.index', [
            'stock_status' => 'low',
        ]));
        $lowStockFilterResponse->assertOk();
        $lowStockFilterResponse->assertSee('Gamis Fatima Velvet'); // 4 <= 5 is low
        $lowStockFilterResponse->assertDontSee('Gamis Aisyah Silk'); // 10 > 5 is normal
    }
}
