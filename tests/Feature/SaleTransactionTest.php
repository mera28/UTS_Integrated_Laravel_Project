<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaleTransactionTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $staff;
    protected User $customer;
    protected User $otherCustomer;
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

        $this->customer = User::create([
            'name' => 'Siti Khadijah',
            'email' => 'siti@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'phone' => '08123456789',
            'address' => 'Jl. Anggrek No. 12, Bandung',
        ]);

        $this->otherCustomer = User::create([
            'name' => 'Fatimah Zahra',
            'email' => 'fatimah@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        $category = Category::create([
            'name' => 'Gamis Syari',
            'slug' => 'gamis-syari',
        ]);

        $this->product = Product::create([
            'category_id' => $category->id,
            'sku' => 'ZF-GMS-001',
            'name' => 'Gamis Aisyah Premium',
            'purchase_price' => 150000,
            'selling_price' => 285000,
            'stock' => 10,
            'minimum_stock' => 5,
            'size' => 'S, M, L, XL',
            'color' => 'Navy, Mocca, Sage',
        ]);
    }

    /**
     * Test staff can create a POS cashier sale and stock is properly decremented.
     */
    public function test_staff_can_create_pos_sale_and_stock_is_decremented(): void
    {
        $response = $this->actingAs($this->staff)->post(route('sales.store'), [
            'customer_name' => 'Pelanggan Walk-in',
            'customer_phone' => '08987654321',
            'payment_method' => 'cash',
            'notes' => 'Transaksi kasir offline',
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'selected_size' => 'M',
                    'selected_color' => 'Navy',
                    'quantity' => 3,
                ],
            ],
        ]);

        $response->assertRedirect();
        
        $sale = Sale::latest()->first();
        $this->assertNotNull($sale);
        $this->assertEquals('Pelanggan Walk-in', $sale->customer_name);
        $this->assertEquals(855000, (float) $sale->total);
        $this->assertEquals('completed', $sale->status);
        $this->assertEquals('paid', $sale->payment_status);
        $this->assertEquals('cash', $sale->payment_method);

        $this->assertDatabaseHas('sale_items', [
            'product_id' => $this->product->id,
            'quantity' => 3,
        ]);

        // Stock must be 10 - 3 = 7
        $this->product->refresh();
        $this->assertEquals(7, $this->product->stock);
    }

    /**
     * Test overselling prevention: throws validation exception when qty exceeds stock.
     */
    public function test_cannot_create_sale_if_quantity_exceeds_available_stock(): void
    {
        $response = $this->actingAs($this->staff)->post(route('sales.store'), [
            'customer_name' => 'Pembeli Borongan',
            'payment_method' => 'cash',
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'selected_size' => 'L',
                    'selected_color' => 'Mocca',
                    'quantity' => 20, // Stock is only 10!
                ],
            ],
        ]);

        $response->assertSessionHasErrors(['items']);

        // Stock should remain unchanged
        $this->product->refresh();
        $this->assertEquals(10, $this->product->stock);
    }

    /**
     * Test staff cannot cancel sale (403 forbidden), but admin can cancel and stock is restored.
     */
    public function test_only_admin_can_cancel_sale_and_restore_stock(): void
    {
        // 1. Create sale
        $this->actingAs($this->staff)->post(route('sales.store'), [
            'customer_name' => 'Pembeli Uji Coba',
            'payment_method' => 'cash',
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => 4,
                ],
            ],
        ]);

        $this->product->refresh();
        $this->assertEquals(6, $this->product->stock);

        $sale = Sale::latest()->first();

        // 2. Staff attempts to cancel -> Forbidden (403)
        $staffCancelResponse = $this->actingAs($this->staff)->patch(route('sales.cancel', $sale));
        $staffCancelResponse->assertForbidden();

        // 3. Admin cancels sale -> Success, stock restored to 10
        $adminCancelResponse = $this->actingAs($this->admin)->patch(route('sales.cancel', $sale));
        $adminCancelResponse->assertRedirect();
        $adminCancelResponse->assertSessionHas('success');

        $sale->refresh();
        $this->assertEquals('cancelled', $sale->status);
        $this->assertEquals('refunded', $sale->payment_status);

        $this->product->refresh();
        $this->assertEquals(10, $this->product->stock);
    }

    /**
     * Test customer e-commerce cart and checkout flow with payment gateway.
     */
    public function test_customer_can_add_to_cart_and_checkout(): void
    {
        // 1. Customer adds item to cart
        $addCartResponse = $this->actingAs($this->customer)
            ->from(route('cart.index'))
            ->post(route('cart.add', $this->product), [
                'selected_size' => 'M',
                'selected_color' => 'Navy',
                'quantity' => 2,
            ]);

        $addCartResponse->assertRedirect(route('cart.index'));
        $addCartResponse->assertSessionHas('success');

        // 2. Customer processes checkout
        $checkoutResponse = $this->actingAs($this->customer)->post(route('checkout.process'), [
            'customer_name' => 'Siti Khadijah',
            'customer_phone' => '08123456789',
            'shipping_address' => 'Jl. Anggrek No. 12, RT 02/05, Bandung',
            'payment_method' => 'va_bca',
            'notes' => 'Mohon dikemas rapi dengan bubble wrap',
        ]);

        $sale = Sale::where('user_id', $this->customer->id)->latest()->first();
        $this->assertNotNull($sale);
        $this->assertEquals('unpaid', $sale->payment_status);
        $this->assertEquals('pending', $sale->status);
        $this->assertEquals('va_bca', $sale->payment_method);
        $this->assertNotNull($sale->payment_reference);
        $this->assertEquals(2 * 285000, (float) $sale->total);

        // Redirects to payment gateway simulation page
        $checkoutResponse->assertRedirect(route('orders.payment', $sale));

        // Stock decreased by 2
        $this->product->refresh();
        $this->assertEquals(8, $this->product->stock);
    }

    /**
     * Test payment gateway simulation confirms payment to paid and completed.
     */
    public function test_interactive_payment_gateway_simulation_confirms_paid(): void
    {
        // Create a pending sale for the customer
        $this->actingAs($this->customer)->post(route('cart.add', $this->product), [
            'quantity' => 1,
        ]);

        $this->actingAs($this->customer)->post(route('checkout.process'), [
            'customer_name' => 'Siti Khadijah',
            'customer_phone' => '08123456789',
            'shipping_address' => 'Jl. Anggrek No. 12, Bandung',
            'payment_method' => 'qris',
        ]);

        $sale = Sale::where('user_id', $this->customer->id)->latest()->first();

        // 1. View payment page
        $paymentPageResponse = $this->actingAs($this->customer)->get(route('orders.payment', $sale));
        $paymentPageResponse->assertOk();
        $paymentPageResponse->assertSee('QRIS');

        // 2. Click "Simulasikan Pembayaran Berhasil"
        $simulateResponse = $this->actingAs($this->customer)->post(route('orders.simulate-pay', $sale));
        $simulateResponse->assertRedirect(route('orders.show', $sale));
        $simulateResponse->assertSessionHas('success');

        $sale->refresh();
        $this->assertEquals('paid', $sale->payment_status);
        $this->assertEquals('completed', $sale->status);
        $this->assertNotNull($sale->paid_at);
    }

    /**
     * Test another customer cannot access or simulate payment for someone else's order.
     */
    public function test_customer_cannot_access_or_pay_other_customer_order(): void
    {
        $this->actingAs($this->customer)->post(route('cart.add', $this->product), [
            'quantity' => 1,
        ]);

        $this->actingAs($this->customer)->post(route('checkout.process'), [
            'customer_name' => 'Siti Khadijah',
            'customer_phone' => '08123456789',
            'shipping_address' => 'Jl. Anggrek No. 12, Bandung',
            'payment_method' => 'va_mandiri',
        ]);

        $sale = Sale::where('user_id', $this->customer->id)->latest()->first();

        // Fatimah tries to view Siti's payment page -> Forbidden 403
        $forbiddenView = $this->actingAs($this->otherCustomer)->get(route('orders.payment', $sale));
        $forbiddenView->assertForbidden();

        // Fatimah tries to simulate payment for Siti's order -> Forbidden 403
        $forbiddenPay = $this->actingAs($this->otherCustomer)->post(route('orders.simulate-pay', $sale));
        $forbiddenPay->assertForbidden();
    }

    /**
     * Test customer can cancel their own order and product stock is restored automatically.
     */
    public function test_customer_can_cancel_own_order_and_stock_is_automatically_restored(): void
    {
        // 1. Customer buys 3 pcs of product (stock: 10 -> 7)
        $this->actingAs($this->customer)->post(route('cart.add', $this->product), [
            'quantity' => 3,
        ]);

        $this->actingAs($this->customer)->post(route('checkout.process'), [
            'customer_name' => 'Siti Khadijah',
            'customer_phone' => '08123456789',
            'shipping_address' => 'Jl. Anggrek No. 12, Bandung',
            'payment_method' => 'qris',
        ]);

        $this->product->refresh();
        $this->assertEquals(7, $this->product->stock);

        $sale = Sale::where('user_id', $this->customer->id)->latest()->first();
        $this->assertNotNull($sale);
        $this->assertEquals('pending', $sale->status);

        // 2. Customer cancels the order
        $cancelResponse = $this->actingAs($this->customer)->post(route('orders.cancel', $sale));
        $cancelResponse->assertRedirect(route('orders.show', $sale));
        $cancelResponse->assertSessionHas('success');

        // 3. Verify database state
        $sale->refresh();
        $this->assertEquals('cancelled', $sale->status);
        $this->assertEquals('cancelled', $sale->payment_status);

        // 4. Stock MUST be automatically restored to 10
        $this->product->refresh();
        $this->assertEquals(10, $this->product->stock);
    }

    /**
     * Test customer cannot cancel someone else's order.
     */
    public function test_customer_cannot_cancel_other_customer_order(): void
    {
        // 1. Customer creates order
        $this->actingAs($this->customer)->post(route('cart.add', $this->product), [
            'quantity' => 2,
        ]);

        $this->actingAs($this->customer)->post(route('checkout.process'), [
            'customer_name' => 'Siti Khadijah',
            'customer_phone' => '08123456789',
            'shipping_address' => 'Jl. Anggrek No. 12, Bandung',
            'payment_method' => 'qris',
        ]);

        $sale = Sale::where('user_id', $this->customer->id)->latest()->first();

        // 2. Other customer attempts to cancel -> 403 Forbidden
        $unauthorizedCancel = $this->actingAs($this->otherCustomer)->post(route('orders.cancel', $sale));
        $unauthorizedCancel->assertForbidden();

        // 3. Sale and stock remain intact
        $sale->refresh();
        $this->assertNotEquals('cancelled', $sale->status);
        $this->product->refresh();
        $this->assertEquals(8, $this->product->stock);
    }

    /**
     * Test admin can cancel sale via POST /sales/{sale}/cancel.
     */
    public function test_admin_can_cancel_sale_via_post_route(): void
    {
        $this->actingAs($this->staff)->post(route('sales.store'), [
            'customer_name' => 'Pembeli Toko',
            'payment_method' => 'cash',
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'quantity' => 2,
                ],
            ],
        ]);

        $sale = Sale::latest()->first();
        $this->product->refresh();
        $this->assertEquals(8, $this->product->stock);

        // Admin cancels via POST
        $adminCancelResponse = $this->actingAs($this->admin)->post(route('sales.cancel', $sale));
        $adminCancelResponse->assertRedirect();
        $adminCancelResponse->assertSessionHas('success');

        $sale->refresh();
        $this->assertEquals('cancelled', $sale->status);
        $this->product->refresh();
        $this->assertEquals(10, $this->product->stock);
    }
}
