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

class EndToEndHealthCheckTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $staff;
    protected User $customer;
    protected Category $category;
    protected Supplier $supplier;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Owner',
            'email' => 'admin@zahiyyah.com',
            'password' => bcrypt('AdminZahiyyah#2026'),
            'role' => 'admin',
        ]);

        $this->staff = User::create([
            'name' => 'Staff Kasir',
            'email' => 'staff@zahiyyah.com',
            'password' => bcrypt('StaffGudang#2026'),
            'role' => 'staff',
        ]);

        $this->customer = User::create([
            'name' => 'Alya Customer',
            'email' => 'customer@zahiyyah.com',
            'password' => bcrypt('Customer#2026'),
            'role' => 'customer',
            'phone' => '081234567890',
            'address' => 'Jl. Teuku Umar No. 28, Banda Aceh',
        ]);

        $this->category = Category::create([
            'name' => 'Gamis & Maxy Dress',
            'slug' => 'gamis-maxy-dress',
            'icon' => 'sparkles',
            'description' => 'Koleksi gaun gamis muslimah eksklusif',
        ]);

        $this->supplier = Supplier::create([
            'name' => 'Haji Ridwan',
            'company_name' => 'CV Tekstil Bandung Utama',
            'phone' => '08123456789',
            'email' => 'supplier@zahiyyah.com',
            'address' => 'Bandung, Jawa Barat',
        ]);

        $this->product = Product::create([
            'category_id' => $this->category->id,
            'sku' => 'ZF-GMS-001',
            'name' => 'Gamis Linen Aisyah Silk',
            'slug' => 'gamis-linen-aisyah-silk',
            'description' => 'Gamis linen premium dengan sentuhan silk halus.',
            'size' => 'S, M, L, XL',
            'color' => 'Sage Green, Broken White, Warm Tan',
            'purchase_price' => 150000,
            'selling_price' => 285000,
            'stock' => 15,
            'minimum_stock' => 5,
        ]);
    }

    /**
     * 1. Health check seluruh 9 menu di Sidebar Backoffice untuk Admin (harus 200 OK, tanpa error 500 / 404).
     */
    public function test_admin_can_access_all_sidebar_menus_without_errors(): void
    {
        $admin = $this->admin;

        // Menu 1: Dashboard
        $this->actingAs($admin)->get(route('dashboard'))
            ->assertOk()
            ->assertViewIs('dashboard.index')
            ->assertSee('Zahiyyah\'s Fit', false);

        // Menu 2: Master Produk Outfit
        $this->actingAs($admin)->get(route('products.index'))
            ->assertOk()
            ->assertViewIs('products.index')
            ->assertSee('ZF-GMS-001');

        // Form Tambah Produk (Admin)
        $this->actingAs($admin)->get(route('products.create'))
            ->assertOk()
            ->assertViewIs('products.create');

        // Menu 3: Monitoring Stok Rendah
        $this->actingAs($admin)->get(route('dashboard.low-stock'))
            ->assertOk()
            ->assertViewIs('dashboard.low_stock');

        $this->actingAs($admin)->get('/products?stock_status=low')
            ->assertOk()
            ->assertViewIs('products.index');

        // Menu 4: Master Kategori (Admin Only)
        $this->actingAs($admin)->get(route('categories.index'))
            ->assertOk()
            ->assertViewIs('categories.index')
            ->assertSee('Gamis & Maxy Dress');

        // Menu 5: Master Supplier (Admin Only)
        $this->actingAs($admin)->get(route('suppliers.index'))
            ->assertOk()
            ->assertViewIs('suppliers.index')
            ->assertSee('CV Tekstil Bandung Utama');

        // Menu 6: Pembelian (Stock-In)
        $this->actingAs($admin)->get(route('purchases.index'))
            ->assertOk()
            ->assertViewIs('purchases.index');

        $this->actingAs($admin)->get(route('purchases.create'))
            ->assertOk()
            ->assertViewIs('purchases.create');

        // Menu 7: Penjualan (POS & Order)
        $this->actingAs($admin)->get(route('sales.index'))
            ->assertOk()
            ->assertViewIs('sales.index');

        $this->actingAs($admin)->get(route('sales.create'))
            ->assertOk()
            ->assertViewIs('sales.create');

        // Menu 8: Laporan Transaksi & Cetak
        $this->actingAs($admin)->get(route('reports.index'))
            ->assertOk()
            ->assertViewIs('reports.index');

        $this->actingAs($admin)->get(route('reports.print'))
            ->assertOk()
            ->assertViewIs('reports.print');

        // Menu 9: Kelola Pengguna (Admin Only)
        $this->actingAs($admin)->get(route('users.index'))
            ->assertOk()
            ->assertViewIs('users.index')
            ->assertSee('admin@zahiyyah.com');
    }

    /**
     * 2. Health check batasan hak akses Staff Kasir/Gudang (RBAC terisolasi rapi).
     */
    public function test_staff_access_is_properly_restricted_on_admin_menus(): void
    {
        $staff = $this->staff;

        // Staff diizinkan membuka menu operasional
        $this->actingAs($staff)->get(route('dashboard'))->assertOk();
        $this->actingAs($staff)->get(route('products.index'))->assertOk();
        $this->actingAs($staff)->get(route('dashboard.low-stock'))->assertOk();
        $this->actingAs($staff)->get(route('purchases.index'))->assertOk();
        $this->actingAs($staff)->get(route('purchases.create'))->assertOk();
        $this->actingAs($staff)->get(route('sales.index'))->assertOk();
        $this->actingAs($staff)->get(route('sales.create'))->assertOk();
        $this->actingAs($staff)->get(route('reports.index'))->assertOk();

        // Staff DILARANG membuka menu Admin:
        $this->actingAs($staff)->get(route('products.create'))->assertForbidden();
        $this->actingAs($staff)->get(route('categories.index'))->assertForbidden();
        $this->actingAs($staff)->get(route('suppliers.index'))->assertForbidden();
        $this->actingAs($staff)->get(route('users.index'))->assertForbidden();
    }

    /**
     * 3. Alur transaksi Admin & Staff: Pembelian stok, Penjualan POS, dan pembatalan transaksi.
     */
    public function test_backoffice_purchases_and_sales_transactions_with_cancellation(): void
    {
        // A. Admin membuat Pembelian Stok (Stock-In) 10 unit -> Stok dari 15 naik jadi 25
        $response = $this->actingAs($this->admin)->post(route('purchases.store'), [
            'supplier_id' => $this->supplier->id,
            'notes' => 'Pengadaan kain silk tambahan',
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => 10,
                    'unit_cost' => 150000,
                ],
            ],
        ]);

        $purchase = Purchase::latest()->first();
        $this->assertNotNull($purchase);
        $response->assertRedirect(route('purchases.show', $purchase));

        $this->product->refresh();
        $this->assertEquals(25, $this->product->stock);

        $purchase = Purchase::latest()->first();

        // Staff mencoba membatalkan purchase -> 403 Forbidden
        $this->actingAs($this->staff)->patch(route('purchases.cancel', $purchase))->assertForbidden();

        // Admin membatalkan purchase -> Stok kembali turun jadi 15
        $this->actingAs($this->admin)->patch(route('purchases.cancel', $purchase))->assertRedirect();
        $this->product->refresh();
        $this->assertEquals(15, $this->product->stock);

        // B. Staff Kasir membuat Penjualan POS 5 unit -> Stok dari 15 turun jadi 10
        $this->actingAs($this->staff)->post(route('sales.store'), [
            'customer_name' => 'Pelanggan Butik',
            'payment_method' => 'cash',
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'selected_size' => 'M',
                    'selected_color' => 'Sage Green',
                    'quantity' => 5,
                ],
            ],
        ])->assertRedirect();

        $this->product->refresh();
        $this->assertEquals(10, $this->product->stock);

        $sale = Sale::latest()->first();

        // Staff mencoba membatalkan sale -> 403 Forbidden
        $this->actingAs($this->staff)->post(route('sales.cancel', $sale))->assertForbidden();

        // Admin membatalkan sale -> Stok kembali naik jadi 15
        $this->actingAs($this->admin)->post(route('sales.cancel', $sale))->assertRedirect();
        $this->product->refresh();
        $this->assertEquals(15, $this->product->stock);
    }

    /**
     * 4. Alur End-to-End Customer: Toko -> Pilih Varian -> Checkout -> Payment -> Pembatalan & Pemulihan Stok.
     */
    public function test_customer_storefront_order_and_cancellation_lifecycle(): void
    {
        // 1. Customer membuka katalog toko
        $shopResponse = $this->get(route('shop.index'));
        $shopResponse->assertOk();
        $shopResponse->assertSee('Zahiyyah\'s Fit', false);
        $shopResponse->assertSee('Gamis Linen Aisyah Silk');

        // Pastikan tidak ada tombol besar portal di top bar toko
        $shopResponse->assertDontSee('portal-link-promo');

        // 2. Customer membuka halaman Login & Register (Continue with Google tersedia)
        $loginPage = $this->get(route('login'));
        $loginPage->assertOk();
        $loginPage->assertSee('Continue with Google');
        $loginPage->assertSee('Masuk Member Pelanggan');

        $registerPage = $this->get(route('register'));
        $registerPage->assertOk();
        $registerPage->assertSee('Continue with Google');
        $registerPage->assertSee('Buat Akun Member Customer');

        // 3. Customer memilih varian (Size: M, Color: Warm Tan, Qty: 4) dan memasukkan ke keranjang
        $this->actingAs($this->customer)->post(route('cart.add', $this->product), [
            'quantity' => 4,
            'size' => 'M',
            'color' => 'Warm Tan',
        ])->assertRedirect();

        // Cek halaman keranjang
        $cartPage = $this->actingAs($this->customer)->get(route('cart.index'));
        $cartPage->assertOk();
        $cartPage->assertSee('Gamis Linen Aisyah Silk');
        $cartPage->assertSee('M');
        $cartPage->assertSee('Warm Tan');

        // 4. Customer checkout dengan QRIS
        $checkoutResponse = $this->actingAs($this->customer)->post(route('checkout.process'), [
            'customer_name' => 'Alya Customer',
            'customer_phone' => '081234567890',
            'shipping_address' => 'Jl. Teuku Umar No. 28, Banda Aceh',
            'payment_method' => 'qris',
        ]);

        $sale = Sale::where('user_id', $this->customer->id)->latest()->first();
        $this->assertNotNull($sale);
        $checkoutResponse->assertRedirect(route('orders.payment', $sale));

        // Stok produk otomatis berkurang dari 15 - 4 = 11
        $this->product->refresh();
        $this->assertEquals(11, $this->product->stock);

        // 5. Customer membuka halaman Payment Gateway
        $paymentPage = $this->actingAs($this->customer)->get(route('orders.payment', $sale));
        $paymentPage->assertOk();
        $paymentPage->assertSee('Payment Gateway Interaktif');
        $paymentPage->assertSee('QRIS STANDAR INDONESIA');
        $paymentPage->assertSee('Batalkan Pesanan Ini (Kembalikan Stok)');

        // 6. Customer membatalkan pesanan dari halaman payment
        $cancelResponse = $this->actingAs($this->customer)->post(route('orders.cancel', $sale));
        $cancelResponse->assertRedirect(route('orders.show', $sale));
        $cancelResponse->assertSessionHas('success');

        // 7. Status pesanan menjadi cancelled
        $sale->refresh();
        $this->assertEquals('cancelled', $sale->status);

        // 8. Stok produk otomatis bertambah kembali 100% utuh menjadi 15
        $this->product->refresh();
        $this->assertEquals(15, $this->product->stock);

        // 9. Customer melihat invoice dengan badge Dibatalkan (Cancelled)
        $invoicePage = $this->actingAs($this->customer)->get(route('orders.show', $sale));
        $invoicePage->assertOk();
        $invoicePage->assertSee('Dibatalkan (Cancelled)');

        // 10. Customer melihat daftar pesanan
        $ordersPage = $this->actingAs($this->customer)->get(route('shop.orders'));
        $ordersPage->assertOk();
        $ordersPage->assertSee('Dibatalkan (Cancelled)');
    }

    /**
     * 5. Customer login tidak pernah terlempar ke /dashboard dan ditolak dari /portal/login.
     */
    public function test_customer_cannot_access_admin_dashboard_or_portal(): void
    {
        // A. Customer dilarang membuka /dashboard -> 403 Forbidden
        $this->actingAs($this->customer)->get(route('dashboard'))->assertForbidden();

        // B. Login customer via /login mengarahkan ke /shop, bukan ke /dashboard
        $loginResponse = $this->post(route('login'), [
            'email' => 'customer@zahiyyah.com',
            'password' => 'Customer#2026',
        ]);
        $loginResponse->assertRedirect(route('shop.index'));

        // C. Customer yang mencoba login di /portal/login ditolak dengan pesan error
        $portalResponse = $this->post(route('portal.login'), [
            'email' => 'customer@zahiyyah.com',
            'password' => 'Customer#2026',
        ]);
        $portalResponse->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }
}
