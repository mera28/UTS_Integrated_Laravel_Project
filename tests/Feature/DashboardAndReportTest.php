<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Supplier;
use App\Models\User;
use App\Services\DashboardService;
use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardAndReportTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $staff;
    protected User $customer;
    protected Product $productHighStock;
    protected Product $productLowStock;

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
            'name' => 'Customer Belanja',
            'email' => 'customer@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        $category = Category::create([
            'name' => 'Gamis Syari',
            'slug' => 'gamis-syari',
        ]);

        $supplier = Supplier::create([
            'name' => 'Pak Haji Ridwan',
            'company_name' => 'CV Busana Muslim Bandung',
            'phone' => '0812345678',
            'email' => 'supplier@test.com',
        ]);

        // Product with safe stock (25 > 5)
        $this->productHighStock = Product::create([
            'category_id' => $category->id,
            'sku' => 'ZF-GMS-SAFE',
            'name' => 'Gamis Safa Velvet',
            'purchase_price' => 120000,
            'selling_price' => 250000,
            'stock' => 25,
            'minimum_stock' => 5,
        ]);

        // Product with low stock (2 <= 5)
        $this->productLowStock = Product::create([
            'category_id' => $category->id,
            'sku' => 'ZF-GMS-ALERT',
            'name' => 'Gamis Marwa Silk',
            'purchase_price' => 150000,
            'selling_price' => 300000,
            'stock' => 2,
            'minimum_stock' => 5,
        ]);

        // Seed a completed sale
        $sale = Sale::create([
            'user_id' => $this->staff->id,
            'customer_name' => 'Pelanggan Walk-In',
            'sale_number' => 'INV-TEST-001',
            'total' => 500000,
            'status' => 'completed',
            'payment_method' => 'qris',
            'payment_status' => 'paid',
            'sold_at' => Carbon::today(),
        ]);

        SaleItem::create([
            'sale_id' => $sale->id,
            'product_id' => $this->productHighStock->id,
            'quantity' => 2,
            'unit_price' => 250000,
            'subtotal' => 500000,
        ]);

        // Seed a completed purchase
        $purchase = Purchase::create([
            'user_id' => $this->admin->id,
            'supplier_id' => $supplier->id,
            'purchase_number' => 'PO-TEST-001',
            'total' => 600000,
            'status' => 'completed',
            'purchased_at' => Carbon::today(),
        ]);

        PurchaseItem::create([
            'purchase_id' => $purchase->id,
            'product_id' => $this->productLowStock->id,
            'quantity' => 4,
            'unit_cost' => 150000,
            'subtotal' => 600000,
        ]);
    }

    /**
     * Test admin and staff can access dashboard and metrics are computed dynamically.
     */
    public function test_staff_and_admin_can_access_dashboard_with_live_kpis(): void
    {
        $response = $this->actingAs($this->staff)->get(route('dashboard'));
        $response->assertOk();
        $response->assertSee('Gamis Marwa Silk'); // In low stock section
        $response->assertSee('QRIS');

        $adminResponse = $this->actingAs($this->admin)->get(route('dashboard'));
        $adminResponse->assertOk();
    }

    /**
     * Test low stock monitoring page and service calculation.
     */
    public function test_low_stock_page_displays_products_at_or_below_minimum_stock(): void
    {
        $dashboardService = app(DashboardService::class);
        $lowStock = $dashboardService->getLowStockProducts();

        $this->assertCount(1, $lowStock);
        $this->assertEquals('ZF-GMS-ALERT', $lowStock->first()->sku);

        $response = $this->actingAs($this->staff)->get(route('dashboard.low-stock'));
        $response->assertOk();
        $response->assertSee('ZF-GMS-ALERT');
        $response->assertDontSee('ZF-GMS-SAFE');
    }

    /**
     * Test customer cannot access dashboard or low stock monitoring.
     */
    public function test_customer_cannot_access_dashboard_or_reports(): void
    {
        $dashboardResponse = $this->actingAs($this->customer)->get(route('dashboard'));
        $dashboardResponse->assertForbidden();

        $lowStockResponse = $this->actingAs($this->customer)->get(route('dashboard.low-stock'));
        $lowStockResponse->assertForbidden();

        $reportResponse = $this->actingAs($this->customer)->get(route('reports.index'));
        $reportResponse->assertForbidden();
    }

    /**
     * Test report service financial aggregation and filters.
     */
    public function test_report_service_calculates_financial_summary_correctly(): void
    {
        $reportService = app(ReportService::class);
        $summary = $reportService->getFinancialSummary();

        $this->assertEquals(500000, $summary['total_sales_revenue']);
        $this->assertEquals(1, $summary['total_sales_count']);
        $this->assertEquals(600000, $summary['total_purchases_expense']);
        $this->assertEquals(1, $summary['total_purchases_count']);
        $this->assertEquals(-100000, $summary['net_margin']);
        $this->assertEquals(2, $summary['total_units_sold']);
        $this->assertEquals(4, $summary['total_units_purchased']);
    }

    /**
     * Test report index view and printable layout.
     */
    public function test_staff_can_view_and_print_reports(): void
    {
        // 1. Index view with filters
        $indexResponse = $this->actingAs($this->staff)->get(route('reports.index', [
            'report_type' => 'sales',
            'status' => 'completed',
        ]));
        $indexResponse->assertOk();
        $indexResponse->assertSee('INV-TEST-001');

        // 2. Purchases report view
        $purchasesResponse = $this->actingAs($this->staff)->get(route('reports.index', [
            'report_type' => 'purchases',
        ]));
        $purchasesResponse->assertOk();
        $purchasesResponse->assertSee('PO-TEST-001');

        // 3. Print view
        $printResponse = $this->actingAs($this->admin)->get(route('reports.print', [
            'report_type' => 'sales',
        ]));
        $printResponse->assertOk();
        $printResponse->assertSee("Zahiyyah's Fit", false);
        $printResponse->assertSee('INV-TEST-001');
    }

    /**
     * Test report filter validation rejects invalid date ranges.
     */
    public function test_report_filter_validates_date_range(): void
    {
        $response = $this->actingAs($this->staff)->get(route('reports.index', [
            'start_date' => '2026-12-31',
            'end_date' => '2026-01-01', // End date before start date!
        ]));

        $response->assertSessionHasErrors(['end_date']);
    }
}
