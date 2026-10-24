@extends('layouts.shop')

@section('title', 'Riwayat Pesanan Saya')

@section('content')
<div class="container py-5">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold text-dark mb-1">Riwayat Pesanan Saya</h4>
            <span class="text-muted small">Daftar transaksi belanja outfit Anda di Zahiyyah's Fit</span>
        </div>
        <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-bag-plus me-1"></i> Belanja Lagi
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small">
                    <tr>
                        <th class="ps-4">No. Invoice</th>
                        <th>Tanggal</th>
                        <th>Item Pesanan</th>
                        <th>Total Pembayaran</th>
                        <th>Metode</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody class="small">
                    @forelse($orders as $order)
                    <tr>
                        <td class="ps-4 fw-bold text-primary">{{ $order->sale_number }}</td>
                        <td class="text-muted">{{ $order->sold_at->format('d M Y, H:i') }}</td>
                        <td>
                            @foreach($order->items as $item)
                                <div>&bull; {{ $item->product->name ?? 'Produk' }} ({{ $item->quantity }}x)</div>
                            @endforeach
                        </td>
                        <td class="fw-bold text-dark">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                        <td><span class="badge bg-light text-secondary border text-uppercase">{{ str_replace('_', ' ', $order->payment_method ?? 'cash') }}</span></td>
                        <td>
                            @if($order->payment_status === 'paid')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success">Lunas</span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning">Pending</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
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
