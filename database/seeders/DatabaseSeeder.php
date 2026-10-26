<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Users
        $admin = User::create([
            'name' => 'Zahiyyah Owner',
            'email' => 'admin@zahiyyah.com',
            'password' => Hash::make('AdminZahiyyah#2026'),
            'role' => 'admin',
            'phone' => '08111222333',
            'address' => 'Jl. Daud Beureueh No. 10, Banda Aceh',
            'email_verified_at' => now(),
        ]);

        $staff = User::create([
            'name' => 'Nadia Staff',
            'email' => 'staff@zahiyyah.com',
            'password' => Hash::make('StaffGudang#2026'),
            'role' => 'staff',
            'phone' => '08222333444',
            'address' => 'Jl. Panglima Polem No. 15, Banda Aceh',
            'email_verified_at' => now(),
        ]);

        $customer = User::create([
            'name' => 'Alya Customer',
            'email' => 'customer@zahiyyah.com',
            'password' => Hash::make('Customer#2026'),
            'role' => 'customer',
            'phone' => '081234567890',
            'address' => 'Jl. Teuku Umar No. 28, Banda Aceh',
            'email_verified_at' => now(),
        ]);

        // 2. Categories
        $categoriesData = [
            [
                'name' => 'Gamis & Maxy Dress',
                'slug' => 'gamis-maxy-dress',
                'icon' => 'sparkles',
                'description' => 'Koleksi gamis dan maxy dress anggun, syar\'i, dan modern untuk momen istimewa.',
            ],
            [
                'name' => 'Blouse & Tunik',
                'slug' => 'blouse-tunik',
                'icon' => 'shirt',
                'description' => 'Pilihan blouse dan tunik kasual maupun formal yang nyaman dan modis.',
            ],
            [
                'name' => 'Celana & Kulot',
                'slug' => 'celana-kulot',
                'icon' => 'columns-2',
                'description' => 'Celana kulot, loose pants, dan palazzo berpotongan santun dan elegan.',
            ],
            [
                'name' => 'Rok & Pleated Skirt',
                'slug' => 'rok-pleated-skirt',
                'icon' => 'layers',
                'description' => 'Rok plisket, A-line skirt, dan mermaid skirt anggun untuk look feminin.',
            ],
            [
                'name' => 'Hijab & Pashmina',
                'slug' => 'hijab-pashmina',
                'icon' => 'smile',
                'description' => 'Pashmina silk shimmer, voal miracle premium, dan hijab instan lembut berkualitas tinggi.',
            ],
            [
                'name' => 'Outerwear & Cardigan',
                'slug' => 'outerwear-cardigan',
                'icon' => 'briefcase',
                'description' => 'Cardigan knit premium, blazer tweed modern, dan vest modest terkini.',
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[$cat['slug']] = Category::create($cat);
        }

        // 3. Suppliers
        $supplierBdg = Supplier::create([
            'name' => 'Haji Ridwan',
            'company_name' => 'CV Busana Muslimah Bandung',
            'phone' => '081320112233',
            'email' => 'info@busanamuslimah-bdg.com',
            'address' => 'Jl. Cigondewah Hilir No. 45, Bandung, Jawa Barat',
        ]);

        $supplierSby = Supplier::create([
            'name' => 'Ibu Ratna Dewi',
            'company_name' => 'PT Tekstil Nusantara Jaya',
            'phone' => '081299887766',
            'email' => 'order@tekstilnusantara.co.id',
            'address' => 'Kawasan Industri Rungkut Blok B-12, Surabaya',
        ]);

        $supplierJkt = Supplier::create([
            'name' => 'Ko Hendra',
            'company_name' => 'Grosir Hijab Tanah Abang',
            'phone' => '081987654321',
            'email' => 'grosir@tanahabanghijab.id',
            'address' => 'Pasar Tanah Abang Blok A Lt. 2 Los E No. 88, Jakarta Pusat',
        ]);

        $supplierSurabaya = Supplier::create([
            'name' => 'Sarah Wijaya',
            'company_name' => 'Atelier Modest Fashion Surabaya',
            'phone' => '082134567890',
            'email' => 'hello@ateliermodest.com',
            'address' => 'Jl. Mayjen Sungkono No. 12, Surabaya',
        ]);

        // 4. Products (12 items)
        $productsData = [
            // Gamis & Maxy Dress
            [
                'category_id' => $categories['gamis-maxy-dress']->id,
                'sku' => 'ZF-GMS-001',
                'name' => 'Madina Silk Abaya Dress',
                'description' => 'Gamis abaya mewah berbahan silk premium dengan kilau lembut, tidak terawang, wudhu friendly dengan aksen kancing mutiara di pergelangan tangan.',
                'size' => 'S, M, L, XL',
                'color' => 'Sage Green, Dusty Rose, Mocca',
                'image_url' => 'https://images.unsplash.com/photo-1583391733956-3750e0ff4e8b?q=80&w=800&auto=format&fit=crop',
                'purchase_price' => 150000,
                'selling_price' => 265000,
                'stock' => 25,
                'minimum_stock' => 5,
            ],
            [
                'category_id' => $categories['gamis-maxy-dress']->id,
                'sku' => 'ZF-GMS-002',
                'name' => 'Zahra Floral Chiffon Maxy',
                'description' => 'Dress anggun motif floral pastel dengan furing katun halus, kerah shanghai, tali serut pinggang yang memberi siluet ramping.',
                'size' => 'M, L, XL',
                'color' => 'Lilac Blossom, Sky Blue, Vanilla',
                'image_url' => 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?q=80&w=800&auto=format&fit=crop',
                'purchase_price' => 165000,
                'selling_price' => 285000,
                'stock' => 3, // LOW STOCK
                'minimum_stock' => 5,
            ],

            // Blouse & Tunik
            [
                'category_id' => $categories['blouse-tunik']->id,
                'sku' => 'ZF-BLS-001',
                'name' => 'Aurelia Linen Peplum Blouse',
                'description' => 'Blouse modern dengan aksen peplum manis, terbuat dari bahan pure linen rami adem, cocok untuk outfit ngantor maupun hangout.',
                'size' => 'S, M, L',
                'color' => 'Cream, Terracotta, Broken White',
                'image_url' => 'https://images.unsplash.com/photo-1564257631407-4deb1f99d992?q=80&w=800&auto=format&fit=crop',
                'purchase_price' => 95000,
                'selling_price' => 165000,
                'stock' => 30,
                'minimum_stock' => 5,
            ],
            [
                'category_id' => $categories['blouse-tunik']->id,
                'sku' => 'ZF-BLS-002',
                'name' => 'Khadija Rayon Oversized Tunik',
                'description' => 'Tunik panjang dengan cutting oversized yang jatuh dan sangat adem dari material rayon twill grade A, busui friendly dengan kancing depan.',
                'size' => 'All Size',
                'color' => 'Denim Blue, Olive Green, Mocca',
                'image_url' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=800&auto=format&fit=crop',
                'purchase_price' => 85000,
                'selling_price' => 150000,
                'stock' => 18,
                'minimum_stock' => 5,
            ],

            // Celana & Kulot
            [
                'category_id' => $categories['celana-kulot']->id,
                'sku' => 'ZF-CLN-001',
                'name' => 'Safiya Linen Highwaist Kulot',
                'description' => 'Kulot potongan lebar berpinggang tinggi dengan karet belakang fleksibel dan saku multifungsi kanan-kiri, bahan linen blend tebal berserat natural.',
                'size' => 'S, M, L, XL',
                'color' => 'Sage Green, Khaki, Charcoal',
                'image_url' => 'https://images.unsplash.com/photo-1509631179647-0177331693ae?q=80&w=800&auto=format&fit=crop',
                'purchase_price' => 105000,
                'selling_price' => 175000,
                'stock' => 22,
                'minimum_stock' => 5,
            ],
            [
                'category_id' => $categories['celana-kulot']->id,
                'sku' => 'ZF-CLN-002',
                'name' => 'Yasmin Loose Flare Palazzo',
                'description' => 'Palazzo flowy dari bahan polyester crep anti kusut, jatuh sempurna saat berjalan memberikan kesan jenjang dan profesional.',
                'size' => 'M, L, XL',
                'color' => 'Black, Taupe, Ivory',
                'image_url' => 'https://images.unsplash.com/photo-1551488831-00ddcb6c6bd3?q=80&w=800&auto=format&fit=crop',
                'purchase_price' => 110000,
                'selling_price' => 189000,
                'stock' => 15,
                'minimum_stock' => 5,
            ],

            // Rok & Pleated Skirt
            [
                'category_id' => $categories['rok-pleated-skirt']->id,
                'sku' => 'ZF-ROK-001',
                'name' => 'Elina Silk Pleated Maxi Skirt',
                'description' => 'Rok plisket lidi rapi dan tahan lama berbahan satin silk mewah bertekstur glossy doff, pinggang full karet nyaman dipakai seharian.',
                'size' => 'All Size',
                'color' => 'Mocca, Champagne, Navy Blue',
                'image_url' => 'https://images.unsplash.com/photo-1583496661160-fb5886a0aaaa?q=80&w=800&auto=format&fit=crop',
                'purchase_price' => 115000,
                'selling_price' => 185000,
                'stock' => 20,
                'minimum_stock' => 5,
            ],
            [
                'category_id' => $categories['rok-pleated-skirt']->id,
                'sku' => 'ZF-ROK-002',
                'name' => 'Nayla A-Line Denim Button Skirt',
                'description' => 'Rok denim A-line dengan kancing estetik di bagian depan, material soft denim ringan dan tidak kaku, memberi look vintage modern.',
                'size' => 'S, M, L',
                'color' => 'Light Wash, Dark Indigo',
                'image_url' => 'https://images.unsplash.com/photo-1576995853123-5a10305d93c0?q=80&w=800&auto=format&fit=crop',
                'purchase_price' => 125000,
                'selling_price' => 199000,
                'stock' => 14,
                'minimum_stock' => 5,
            ],

            // Hijab & Pashmina
            [
                'category_id' => $categories['hijab-pashmina']->id,
                'sku' => 'ZF-HJB-001',
                'name' => 'Premium Voal Miracle Lasercut',
                'description' => 'Hijab segi empat 120x120cm bahan voal miracle superfine, tegak paripurna di dahi tanpa perlu semprotan pengaku, tepian rapi lasercut.',
                'size' => '120 x 120 cm',
                'color' => 'Dusty Pink, Milo, Sage, Olive, Charcoal',
                'image_url' => 'https://images.unsplash.com/photo-1609357605129-26f69add5d6e?q=80&w=800&auto=format&fit=crop',
                'purchase_price' => 35000,
                'selling_price' => 65000,
                'stock' => 50,
                'minimum_stock' => 10,
            ],
            [
                'category_id' => $categories['hijab-pashmina']->id,
                'sku' => 'ZF-HJB-002',
                'name' => 'Layla Pashmina Silk Shimmer',
                'description' => 'Pashmina 180x75cm bahan silk shimmer bertekstur mewah dengan efek kelap-kelip elegan saat terpapar cahaya, mudah dibentuk dan tidak licin.',
                'size' => '180 x 75 cm',
                'color' => 'Dusty Rose, Bronze Gold, Silver Ash',
                'image_url' => 'https://images.unsplash.com/photo-1617627143750-d86bc21e42bb?q=80&w=800&auto=format&fit=crop',
                'purchase_price' => 45000,
                'selling_price' => 85000,
                'stock' => 2, // LOW STOCK
                'minimum_stock' => 6,
            ],

            // Outerwear & Cardigan
            [
                'category_id' => $categories['outerwear-cardigan']->id,
                'sku' => 'ZF-OUT-001',
                'name' => 'Hana Knit Oversize Cardigan',
                'description' => 'Cardigan rajut tebal 7 gauge premium anti gatal, model oversized kekinian ala Korean modest style dengan kancing batok kayu vintage.',
                'size' => 'All Size',
                'color' => 'Oatmeal, Espresso, Sage Green',
                'image_url' => 'https://images.unsplash.com/photo-1434389677669-e08b4cac3105?q=80&w=800&auto=format&fit=crop',
                'purchase_price' => 120000,
                'selling_price' => 210000,
                'stock' => 16,
                'minimum_stock' => 5,
            ],
            [
                'category_id' => $categories['outerwear-cardigan']->id,
                'sku' => 'ZF-OUT-002',
                'name' => 'Camila Linen Tweed Tailored Blazer',
                'description' => 'Blazer modest semi formal dengan kombinasi serat linen dan tweed elegan, berfuring halus di bagian dalam, cocok untuk presentasi dan acara resmi.',
                'size' => 'S, M, L, XL',
                'color' => 'Oatmeal Tweed, Pearl White',
                'image_url' => 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?q=80&w=800&auto=format&fit=crop',
                'purchase_price' => 170000,
                'selling_price' => 275000,
                'stock' => 4, // LOW STOCK
                'minimum_stock' => 5,
            ],
        ];

        $products = [];
        foreach ($productsData as $prod) {
            $products[$prod['sku']] = Product::create($prod);
        }

        // 5. Purchases (2 sample purchases with items)
        $purchase1 = Purchase::create([
            'supplier_id' => $supplierBdg->id,
            'user_id' => $admin->id,
            'purchase_number' => 'PO-202610-0001',
            'total' => 5375000,
            'status' => 'completed',
            'notes' => 'Restok pengadaan awal koleksi Gamis Silk Abaya dan Blouse Peplum',
            'purchased_at' => now()->subDays(4),
        ]);

        PurchaseItem::create([
            'purchase_id' => $purchase1->id,
            'product_id' => $products['ZF-GMS-001']->id,
            'quantity' => 20,
            'unit_cost' => 150000,
            'subtotal' => 3000000,
        ]);

        PurchaseItem::create([
            'purchase_id' => $purchase1->id,
            'product_id' => $products['ZF-BLS-001']->id,
            'quantity' => 25,
            'unit_cost' => 95000,
            'subtotal' => 2375000,
        ]);

        $purchase2 = Purchase::create([
            'supplier_id' => $supplierJkt->id,
            'user_id' => $staff->id,
            'purchase_number' => 'PO-202610-0002',
            'total' => 2750000,
            'status' => 'completed',
            'notes' => 'Restok pengadaan hijab voal miracle dan pashmina shimmer',
            'purchased_at' => now()->subDays(2),
        ]);

        PurchaseItem::create([
            'purchase_id' => $purchase2->id,
            'product_id' => $products['ZF-HJB-001']->id,
            'quantity' => 40,
            'unit_cost' => 35000,
            'subtotal' => 1400000,
        ]);

        PurchaseItem::create([
            'purchase_id' => $purchase2->id,
            'product_id' => $products['ZF-HJB-002']->id,
            'quantity' => 30,
            'unit_cost' => 45000,
            'subtotal' => 1350000,
        ]);

        // 6. Sales (3 sample sales with items & payment methods)
        // Sale 1: Online Order by Customer Member (QRIS)
        $sale1 = Sale::create([
            'user_id' => $customer->id,
            'customer_name' => 'Alya Customer',
            'shipping_address' => 'Jl. Teuku Umar No. 28, Banda Aceh',
            'sale_number' => 'INV-202610-0001',
            'total' => 350000,
            'status' => 'completed',
            'payment_method' => 'qris',
            'payment_status' => 'paid',
            'payment_reference' => 'QRIS-ZF-98321044',
            'paid_at' => now()->subDays(1),
            'sold_at' => now()->subDays(1),
        ]);

        SaleItem::create([
            'sale_id' => $sale1->id,
            'product_id' => $products['ZF-GMS-001']->id,
            'selected_size' => 'M',
            'selected_color' => 'Sage Green',
            'quantity' => 1,
            'unit_price' => 265000,
            'subtotal' => 265000,
        ]);

        SaleItem::create([
            'sale_id' => $sale1->id,
            'product_id' => $products['ZF-HJB-002']->id,
            'selected_size' => '180 x 75 cm',
            'selected_color' => 'Dusty Rose',
            'quantity' => 1,
            'unit_price' => 85000,
            'subtotal' => 85000,
        ]);

        // Sale 2: Boutique Offline/Walk-in recorded by Staff (ShopeePay)
        $sale2 = Sale::create([
            'user_id' => $staff->id,
            'customer_name' => 'Siti Khadijah (Walk-in)',
            'shipping_address' => 'Store Pickup (Boutique Zahiyyah\'s Fit)',
            'sale_number' => 'INV-202610-0002',
            'total' => 515000,
            'status' => 'completed',
            'payment_method' => 'ewallet_shopeepay',
            'payment_status' => 'paid',
            'payment_reference' => 'SPAY-88291039',
            'paid_at' => now()->subHours(12),
            'sold_at' => now()->subHours(12),
        ]);

        SaleItem::create([
            'sale_id' => $sale2->id,
            'product_id' => $products['ZF-BLS-001']->id,
            'selected_size' => 'L',
            'selected_color' => 'Cream',
            'quantity' => 2,
            'unit_price' => 165000,
            'subtotal' => 330000,
        ]);

        SaleItem::create([
            'sale_id' => $sale2->id,
            'product_id' => $products['ZF-ROK-001']->id,
            'selected_size' => 'All Size',
            'selected_color' => 'Mocca',
            'quantity' => 1,
            'unit_price' => 185000,
            'subtotal' => 185000,
        ]);

        // Sale 3: Customer Order via Virtual Account BCA
        $sale3 = Sale::create([
            'user_id' => $customer->id,
            'customer_name' => 'Alya Customer',
            'shipping_address' => 'Jl. Teuku Umar No. 28, Banda Aceh',
            'sale_number' => 'INV-202610-0003',
            'total' => 450000,
            'status' => 'completed',
            'payment_method' => 'va_bca',
            'payment_status' => 'paid',
            'payment_reference' => '8271081234567890',
            'paid_at' => now()->subHours(3),
            'sold_at' => now()->subHours(3),
        ]);

        SaleItem::create([
            'sale_id' => $sale3->id,
            'product_id' => $products['ZF-OUT-002']->id,
            'selected_size' => 'M',
            'selected_color' => 'Oatmeal Tweed',
            'quantity' => 1,
            'unit_price' => 275000,
            'subtotal' => 275000,
        ]);

        SaleItem::create([
            'sale_id' => $sale3->id,
            'product_id' => $products['ZF-CLN-001']->id,
            'selected_size' => 'M',
            'selected_color' => 'Sage Green',
            'quantity' => 1,
            'unit_price' => 175000,
            'subtotal' => 175000,
        ]);
    }
}
