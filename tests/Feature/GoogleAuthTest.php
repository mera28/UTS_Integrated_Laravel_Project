<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use App\Services\GoogleAuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature Test Suite for Customer Google OAuth Authentication and Pending Cart Restoration.
 */
class GoogleAuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test guest can access Google Account Chooser screen.
     */
    public function test_guest_can_view_google_account_chooser(): void
    {
        $response = $this->get(route('auth.google'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.google_choose');
        $response->assertSee('Pilih akun Google');
        $response->assertSee('Alya Zahiyyah');
        $response->assertSee('alya.customer@gmail.com');
        $response->assertSee('Siti Rahma');
        $response->assertSee('siti.fashion@gmail.com');
        $response->assertSee('Gunakan akun Google lain');
    }

    /**
     * Test authenticated customer is redirected away from Google Account Chooser.
     */
    public function test_authenticated_user_redirected_from_google_auth_page(): void
    {
        $customer = User::create([
            'name' => 'Alya Pelanggan',
            'email' => 'alya.auth@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        $response = $this->actingAs($customer)->get(route('auth.google'));

        $response->assertRedirect(route('shop.index'));
    }

    /**
     * Test Google OAuth callback logs in existing user or creates a new customer.
     */
    public function test_google_callback_creates_or_authenticates_customer(): void
    {
        $googlePayload = [
            'name' => 'Fatimah Az-Zahra',
            'email' => 'fatimah.zahra@gmail.com',
        ];

        $response = $this->post(route('auth.google.callback'), $googlePayload);

        $response->assertRedirect(route('shop.index'));
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'name' => 'Fatimah Az-Zahra',
            'email' => 'fatimah.zahra@gmail.com',
            'role' => 'customer',
        ]);
    }

    /**
     * Test Google OAuth callback validates required fields.
     */
    public function test_google_callback_validates_email_and_name(): void
    {
        $response = $this->post(route('auth.google.callback'), [
            'name' => '',
            'email' => 'not-an-email',
        ]);

        $response->assertSessionHasErrors(['name', 'email']);
        $this->assertGuest();
    }

    /**
     * Test guest adding product to cart is redirected to login with pending cart saved in session.
     */
    public function test_guest_adding_to_cart_is_redirected_to_login_with_pending_item(): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'gamis-syari'],
            ['name' => 'Gamis Syari', 'description' => 'Gamis elegan']
        );

        $product = Product::firstOrCreate(
            ['sku' => 'TEST-SKU-001'],
            [
                'category_id' => $category->id,
                'name' => 'Gamis Silk Zahiyyah',
                'purchase_price' => 150000,
                'selling_price' => 280000,
                'stock' => 15,
                'min_stock' => 3,
                'size' => 'All Size',
                'color' => 'Dusty Pink',
                'is_active' => true,
            ]
        );

        $response = $this->post(route('cart.add', $product), [
            'quantity' => 2,
            'size' => 'All Size',
            'color' => 'Dusty Pink',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('pending_cart_item');
        $this->assertEquals($product->id, session('pending_cart_item')['product_id']);
        $this->assertEquals(2, session('pending_cart_item')['quantity']);
    }

    /**
     * Test guest adding item, then signing in via Google automatically inserts item to cart and redirects to cart.
     */
    public function test_pending_cart_item_is_automatically_added_after_google_signin(): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'hijab-voal'],
            ['name' => 'Hijab Voal', 'description' => 'Hijab voal premium']
        );

        $product = Product::firstOrCreate(
            ['sku' => 'TEST-SKU-002'],
            [
                'category_id' => $category->id,
                'name' => 'Hijab Voal Premium',
                'purchase_price' => 45000,
                'selling_price' => 89000,
                'stock' => 20,
                'min_stock' => 5,
                'size' => '115x115',
                'color' => 'Rosegold',
                'is_active' => true,
            ]
        );

        // Simulate session with pending cart item
        $response = $this->withSession([
            'pending_cart_item' => [
                'product_id' => $product->id,
                'quantity' => 2,
                'size' => '115x115',
                'color' => 'Rosegold',
                'buy_now' => false,
            ],
        ])->post(route('auth.google.callback'), [
            'name' => 'Alya Zahiyyah',
            'email' => 'alya.customer@gmail.com',
        ]);

        $response->assertRedirect(route('cart.index'));
        $this->assertAuthenticated();

        // Verify item is now in the active shopping cart session
        $cartService = app(CartService::class);
        $cart = $cartService->getCart();
        $this->assertNotEmpty($cart);
    }

    /**
     * Test guest clicking Buy Now, then signing in via Google automatically directs to checkout.
     */
    public function test_pending_buy_now_item_redirects_directly_to_checkout_after_google_signin(): void
    {
        $category = Category::firstOrCreate(
            ['slug' => 'tunik-casual'],
            ['name' => 'Tunik Casual', 'description' => 'Tunik modis']
        );

        $product = Product::firstOrCreate(
            ['sku' => 'TEST-SKU-003'],
            [
                'category_id' => $category->id,
                'name' => 'Tunik Linen Casual',
                'purchase_price' => 90000,
                'selling_price' => 175000,
                'stock' => 10,
                'min_stock' => 2,
                'size' => 'M',
                'color' => 'Sage Green',
                'is_active' => true,
            ]
        );

        // Simulate session with pending cart item from buy_now button
        $response = $this->withSession([
            'pending_cart_item' => [
                'product_id' => $product->id,
                'quantity' => 1,
                'size' => 'M',
                'color' => 'Sage Green',
                'buy_now' => true,
            ],
        ])->post(route('auth.google.callback'), [
            'name' => 'Siti Rahma',
            'email' => 'siti.fashion@gmail.com',
        ]);

        $response->assertRedirect(route('checkout.index'));
        $this->assertAuthenticated();
    }
}
