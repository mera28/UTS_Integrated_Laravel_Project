<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Validation\ValidationException;

class CartService
{
    /**
     * Retrieve all items currently in user's shopping cart.
     *
     * @return array<string, array{product_id: int, name: string, sku: string, price: float, quantity: int, size: string|null, color: string|null, image_url: string|null, max_stock: int, subtotal: float}>
     */
    public function getCart(): array
    {
        return session()->get('cart', []);
    }

    /**
     * Add product outfit item to cart with size, color, and stock check.
     *
     * @throws ValidationException
     */
    public function addToCart(Product $product, int $quantity = 1, ?string $size = null, ?string $color = null): array
    {
        $cart = $this->getCart();
        $safeSize = $size ?: ($product->size ? explode(',', $product->size)[0] : 'All Size');
        $safeColor = $color ?: ($product->color ? explode(',', $product->color)[0] : 'Standar');

        $cartKey = "item_{$product->id}_" . md5(trim($safeSize) . '_' . trim($safeColor));

        $existingQty = isset($cart[$cartKey]) ? (int) $cart[$cartKey]['quantity'] : 0;
        $newQty = $existingQty + $quantity;

        if ($newQty > $product->stock) {
            throw ValidationException::withMessages([
                'quantity' => "Maaf, sisa stok produk '{$product->name}' hanya tersedia {$product->stock} pcs. Anda sudah memiliki {$existingQty} pcs di keranjang.",
            ]);
        }

        $price = (float) $product->selling_price;

        $cart[$cartKey] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku,
            'price' => $price,
            'quantity' => $newQty,
            'size' => trim($safeSize),
            'color' => trim($safeColor),
            'image_url' => $product->image_url,
            'max_stock' => $product->stock,
            'subtotal' => $newQty * $price,
        ];

        session()->put('cart', $cart);

        return [
            'success' => true,
            'message' => "'{$product->name}' berhasil ditambahkan ke keranjang belanja.",
            'cart_count' => count($cart),
        ];
    }

    /**
     * Update quantity for a specific item in cart.
     *
     * @throws ValidationException
     */
    public function updateQuantity(string $cartKey, int $quantity): array
    {
        $cart = $this->getCart();

        if (! isset($cart[$cartKey])) {
            throw ValidationException::withMessages(['cart' => 'Item pakaian tidak ditemukan di keranjang belanja.']);
        }

        $productId = $cart[$cartKey]['product_id'];
        $product = Product::findOrFail($productId);

        if ($quantity <= 0) {
            unset($cart[$cartKey]);
            session()->put('cart', $cart);
            return [
                'success' => true,
                'message' => 'Item telah dihapus dari keranjang belanja.',
            ];
        }

        if ($quantity > $product->stock) {
            throw ValidationException::withMessages([
                'quantity' => "Kuantitas melebihi sisa stok fisik ({$product->stock} pcs).",
            ]);
        }

        $cart[$cartKey]['quantity'] = $quantity;
        $cart[$cartKey]['subtotal'] = $quantity * $cart[$cartKey]['price'];

        session()->put('cart', $cart);

        return [
            'success' => true,
            'message' => 'Kuantitas keranjang berhasil diperbarui.',
        ];
    }

    /**
     * Remove item from cart.
     */
    public function removeFromCart(string $cartKey): void
    {
        $cart = $this->getCart();
        unset($cart[$cartKey]);
        session()->put('cart', $cart);
    }

    /**
     * Clear entire cart.
     */
    public function clearCart(): void
    {
        session()->forget('cart');
    }

    /**
     * Get aggregate totals of the cart.
     *
     * @return array{total_items: int, total_unique_items: int, grand_total: float}
     */
    public function getTotals(): array
    {
        $cart = $this->getCart();
        $totalItems = 0;
        $grandTotal = 0;

        foreach ($cart as $item) {
            $totalItems += $item['quantity'];
            $grandTotal += $item['subtotal'];
        }

        return [
            'total_items' => $totalItems,
            'total_unique_items' => count($cart),
            'grand_total' => $grandTotal,
        ];
    }
}
