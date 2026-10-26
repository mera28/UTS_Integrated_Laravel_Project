@extends('layouts.shop')

@section('title', 'Riwayat Pesanan - Zahiyyah\'s Fit Bali Boutique')

@section('content')
<div class="container py-4 py-md-5">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <span class="text-uppercase small fw-semibold" style="letter-spacing: 0.15em; color: var(--bali-tan); font-size: 0.72rem;">Customer History</span>
            <h3 class="fw-bold mb-1" style="font-family: var(--font-serif); font-size: 2.1rem; color: var(--bali-espresso);">Riwayat Pesanan Saya</h3>
            <span class="text-muted small">Daftar transaksi belanja busana outfit Anda di Zahiyyah's Fit</span>
        </div>
        <a href="{{ route('shop.index') }}" class="btn btn-sand btn-sm">
            <i class="bi bi-compass me-1"></i> Belanja Koleksi Baru
        </a>
    </div>

    <div class="card-bali overflow-hidden">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">No. Invoice</th>
                        <th>Tanggal</th>
                        <th>Item Pesanan</th>
                        <th>Total Pembayaran</th>
                        <th>Metode</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="small">
                    @forelse($orders as $order)
                    <tr>
                        <td class="ps-4 fw-bold">
                            <a href="{{ route('orders.show', $order) }}" class="text-decoration-none" style="color: var(--bali-espresso);">
                                {{ $order->sale_number }}
                            </a>
                        </td>
                        <td class="text-muted">{{ $order->sold_at->format('d M Y, H:i') }}</td>
                        <td>
                            @foreach($order->items as $item)
                                <div>&bull; {{ $item->product->name ?? 'Produk' }} ({{ $item->quantity }}x)</div>
                            @endforeach
                        </td>
                        <td class="fw-bold" style="color: var(--bali-espresso);">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge" style="background-color: var(--bali-bg-linen); color: var(--bali-taupe); border: 1px solid var(--bali-border);">
                                {{ str_replace('_', ' ', $order->payment_method ?? 'cash') }}
                            </span>
                        </td>
                        <td>
                            @if($order->payment_status === 'paid')
                                <span class="badge" style="background-color: #EBF5EE; color: #1E6B37; border: 1px solid #C4E2CE;">Lunas</span>
                            @elseif($order->payment_status === 'refunded')
                                <span class="badge" style="background-color: #FAF0F0; color: #A33A3A; border: 1px solid #E8C1C1;">Refunded</span>
                            @else
                                <span class="badge" style="background-color: var(--bali-bg-linen); color: var(--bali-tan-dark); border: 1px solid var(--bali-border);">Pending</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            @if($order->payment_status === 'unpaid')
                                <a href="{{ route('orders.payment', $order) }}" class="btn btn-sm btn-brand-primary px-3 py-1">
                                    <i class="bi bi-credit-card me-1"></i> Bayar
                                </a>
                            @else
                                <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-sand px-3 py-1">
                                    <i class="bi bi-receipt me-1"></i> Invoice
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2" style="color: var(--bali-sand-tag);"></i>
                            Belum ada transaksi pesanan yang dibuat.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $orders->links() }}
    </div>
</div>
@endsection
