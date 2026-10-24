<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthAndRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_dashboard_to_login(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_customer_cannot_access_dashboard_and_receives_403(): void
    {
        $customer = User::create([
            'name' => 'Alya Customer',
            'email' => 'customer@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        $response = $this->actingAs($customer)->get('/dashboard');
        $response->assertStatus(403);
    }

    public function test_admin_can_access_dashboard(): void
    {
        $admin = User::create([
            'name' => 'Admin Owner',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertStatus(200);
    }

    public function test_staff_can_access_dashboard(): void
    {
        $staff = User::create([
            'name' => 'Staff Kasir',
            'email' => 'staff@test.com',
            'password' => bcrypt('password'),
            'role' => 'staff',
        ]);

        $response = $this->actingAs($staff)->get('/dashboard');
        $response->assertStatus(200);
    }

    public function test_customer_registration_works(): void
    {
        $response = $this->post('/register', [
            'name' => 'New Customer',
            'email' => 'new@customer.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '08123456789',
            'address' => 'Banda Aceh',
        ]);

        $response->assertRedirect('/shop');
        $this->assertDatabaseHas('users', [
            'email' => 'new@customer.com',
            'role' => 'customer',
        ]);
    }
}
