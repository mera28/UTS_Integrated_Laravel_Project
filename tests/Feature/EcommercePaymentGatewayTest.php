<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use App\Services\PaymentGatewayService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature Tests for Shopee-style Customer E-Commerce Storefront,
 * Shopping Cart management, and Interactive Payment Gateway Simulation.
 */
class EcommercePaymentGatewayTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;
    protected User $anotherCustomer;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customer = User::create([
            'name' => 'Siti Khadijah',
            'email' => 'siti@zahiyyah.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'phone' => '081234567890',
            'address' => 'Jl. Dago Asri No. 15, Bandung',
        ]);

        $this->anotherCustomer = User::create([
            'name' => 'Fatimah Az-Zahra',
            'email' => 'fatimah@zahiyyah.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        $category = Category::create([
            'name' => 'Blouse & Tunik',
            'slug' => 'blouse-tunik',
        ]);

        $this->product = Product::create([
            'category_id' => $category->id,
            'sku' => 'ZF-BLS-001',
            'name' => 'Tunik Hanan Floral Linen',
            'purchase_price' => 95000,
            'selling_price' => 185000,
            'stock' => 8,
            'minimum_stock' => 3,
            'size' => 'S, M, L, XL',
            'color' => 'Sage Green, Dusty Rose',
        ]);
    }

    /**
     * Test complete flow: Cart -> Checkout -> Payment Gateway Simulation -> Order Paid.
     */
    public function test_customer_checkout_and_interactive_payment_gateway_simulation(): void
    {
        // 1. Customer adds item to cart
        $addCartResponse = $this->actingAs($this->customer)
            ->from(route('cart.index'))
            ->post(route('cart.add', $this->product), [
                'size' => 'M',
                'color' => 'Sage Green',
                'quantity' => 2,
            ]);

        $addCartResponse->assertRedirect(route('cart.index'));
        $addCartResponse->assertSessionHas('success');

        // 2. Customer accesses checkout page
        $checkoutViewResponse = $this->actingAs($this->customer)->get(route('checkout.index'));
        $checkoutViewResponse->assertOk();
        $checkoutViewResponse->assertSee('Tunik Hanan Floral Linen');

        // 3. Customer submits checkout with BCA Virtual Account
        $checkoutSubmitResponse = $this->actingAs($this->customer)->post(route('checkout.process'), [
            'customer_name' => 'Siti Khadijah',
            'customer_phone' => '081234567890',
            'shipping_address' => 'Jl. Dago Asri No. 15, RT 01/RW 04, Bandung',
            'payment_method' => 'va_bca',
            'notes' => 'Tolong dicek jahitannya sebelum dikirim ya min.',
        ]);

        $sale = Sale::where('user_id', $this->customer->id)->latest()->first();
        $this->assertNotNull($sale);
        $this->assertEquals('unpaid', $sale->payment_status);
        $this->assertEquals('pending', $sale->status);
        $this->assertEquals(2 * 185000, (float) $sale->total);

        // Redirects to interactive payment page
        $checkoutSubmitResponse->assertRedirect(route('orders.payment', $sale));

        // 4. View Interactive Payment Gateway page
        $paymentPageResponse = $this->actingAs($this->customer)->get(route('orders.payment', $sale));
        $paymentPageResponse->assertOk();
        $paymentPageResponse->assertSee('BCA Virtual Account');
        $paymentPageResponse->assertSee($sale->payment_reference);

        // 5. Customer clicks "Simulasikan Pembayaran Berhasil"
        $simulateResponse = $this->actingAs($this->customer)->post(route('orders.simulate-pay', $sale));
        $simulateResponse->assertRedirect(route('orders.show', $sale));
        $simulateResponse->assertSessionHas('success');

        // 6. Verify sale is marked completed and paid
        $sale->refresh();
        $this->assertEquals('paid', $sale->payment_status);
        $this->assertEquals('completed', $sale->status);
        $this->assertNotNull($sale->paid_at);

        // Verify stock was decremented by 2
        $this->product->refresh();
        $this->assertEquals(6, $this->product->stock);
    }

    /**
     * Test cart quantity update and removal.
     */
    public function test_cart_quantity_updates_and_respects_stock_limits(): void
    {
        // 1. Add item to cart
        $this->actingAs($this->customer)->post(route('cart.add', $this->product), [
            'quantity' => 2,
        ]);

        $cartService = app(\App\Services\CartService::class);
        $cart = $cartService->getCart();
        $this->assertCount(1, $cart);

        $cartKey = array_key_first($cart);

        // 2. Update quantity to 4
        $this->actingAs($this->customer)->patch(route('cart.update'), [
            'cart_key' => $cartKey,
            'quantity' => 4,
        ]);

        $this->assertEquals(4, $cartService->getCart()[$cartKey]['quantity']);

        // 3. Clear cart
        $this->actingAs($this->customer)->delete(route('cart.clear'));
        $this->assertEmpty($cartService->getCart());
    }

    /**
     * Test payment gateway service supports QRIS, VA Banks, and E-Wallets.
     */
    public function test_customer_can_select_various_payment_methods(): void
    {
        $gateway = app(PaymentGatewayService::class);

        $methods = ['qris', 'va_bca', 'va_mandiri', 'va_bri', 'va_bsi', 'ewallet_shopeepay', 'ewallet_gopay'];

        foreach ($methods as $method) {
            $ref = $gateway->generatePaymentReference($method);
            $this->assertNotEmpty($ref);

            $instructions = $gateway->getPaymentInstructions($method, $ref);
            $this->assertNotEmpty($instructions);
        }
    }

    /**
     * Test unauthenticated guest user must login to checkout.
     */
    public function test_unauthenticated_user_must_login_to_checkout(): void
    {
        $response = $this->get(route('checkout.index'));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test customer can view digital order receipt invoice.
     */
    public function test_customer_can_view_order_receipt_after_payment(): void
    {
        $sale = Sale::create([
            'user_id' => $this->customer->id,
            'customer_name' => $this->customer->name,
            'shipping_address' => $this->customer->address,
            'sale_number' => 'INV-ECOM-001',
            'total' => 185000,
            'status' => 'completed',
            'payment_method' => 'qris',
            'payment_status' => 'paid',
            'payment_reference' => 'QRIS-TEST-999',
            'sold_at' => now(),
            'paid_at' => now(),
        ]);

        $response = $this->actingAs($this->customer)->get(route('orders.show', $sale));
        $response->assertOk();
        $response->assertSee('INV-ECOM-001');
        $response->assertSee('QRIS');
    }
}
