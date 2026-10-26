@extends('layouts.app')

@section('title', 'Nota Pembelian ' . $purchase->purchase_number)
@section('page_title', 'Faktur Pengadaan Pakaian (Stock-In)')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <!-- Action Header Bar -->
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2 d-print-none">
            <a href="{{ route('purchases.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar PO
            </a>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-light border" onclick="window.print()">
                    <i class="bi bi-printer me-1"></i> Cetak Faktur (Print)
                </button>
                @if(auth()->user()->isAdmin() && $purchase->status === 'completed')
                    <button type="button" class="btn btn-outline-danger" 
                            onclick="confirmAction('cancel-po-form', 'PERINGATAN: Apakah Anda yakin ingin membatalkan transaksi pembelian [{{ $purchase->purchase_number }}]? Stok baju yang sebelumnya bertambah akan ditarik/dikurangkan kembali dari gudang.')">
                        <i class="bi bi-x-circle me-1"></i> Batalkan Transaksi (Restore Stok)
                    </button>
                    <form id="cancel-po-form" action="{{ route('purchases.cancel', $purchase) }}" method="POST" class="d-none">
                        @csrf
                        @method('PATCH')
                    </form>
                @endif
            </div>
        </div>

        <!-- Invoice Paper Card -->
        <div class="card card-master p-4 p-md-5 bg-white border shadow-sm">
            <!-- Header Invoice -->
            <div class="d-flex justify-content-between align-items-start border-bottom pb-4 mb-4 flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="brand-logo-icon" style="width: 50px; height: 50px; font-size: 1.5rem;">
                        <i class="bi bi-gem"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0 text-dark">Zahiyyah's Fit</h4>
                        <span class="text-muted small">Modest Fashion Boutique & Inventory Hub</span>
                        <div class="small text-muted mt-1"><i class="bi bi-geo-alt"></i> Banda Aceh, Indonesia</div>
                    </div>
                </div>
                <div class="text-md-end">
                    <span class="badge bg-light text-secondary border px-3 py-1 mb-2 text-uppercase" style="letter-spacing: 1px;">
                        Bukti Pengadaan Stok
                    </span>
                    <h5 class="fw-bold text-dark mb-1">{{ $purchase->purchase_number }}</h5>
                    <div class="text-muted small">
                        Waktu Input: {{ \App\Support\FormatHelper::indonesianDate($purchase->purchased_at, true) }}
                    </div>
                    <div class="mt-2">
                        @if($purchase->status === 'completed')
                            <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-1">
                                <i class="bi bi-check-circle me-1"></i> Stok Masuk Selesai
                            </span>
                        @else
                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 py-1">
                                <i class="bi bi-x-circle me-1"></i> Transaksi Dibatalkan
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Supplier & Staff Info -->
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded-3 border h-100">
                        <span class="text-muted small text-uppercase fw-semibold d-block mb-2">Mitra Pemasok / Konveksi:</span>
                        <h6 class="fw-bold text-dark mb-1">{{ $purchase->supplier->company_name ?: $purchase->supplier->name }}</h6>
                        <div class="small text-muted mb-1"><i class="bi bi-person me-1"></i> PIC: {{ $purchase->supplier->name }}</div>
                        <div class="small text-muted mb-1"><i class="bi bi-whatsapp me-1"></i> {{ $purchase->supplier->phone }}</div>
                        <div class="small text-muted"><i class="bi bi-envelope me-1"></i> {{ $purchase->supplier->email }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded-3 border h-100">
                        <span class="text-muted small text-uppercase fw-semibold d-block mb-2">Petugas Penerima:</span>
                        <h6 class="fw-bold text-dark mb-1">{{ $purchase->user->name }}</h6>
                        <div class="small text-muted mb-1"><i class="bi bi-shield-check me-1"></i> Role: {{ strtoupper($purchase->user->role) }}</div>
                        <div class="small text-muted mb-1"><i class="bi bi-envelope me-1"></i> {{ $purchase->user->email }}</div>
                        <div class="small text-muted"><i class="bi bi-sticky me-1"></i> Catatan: {{ $purchase->notes ?: 'Tidak ada catatan khusus.' }}</div>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="table-responsive rounded-3 border mb-4">
                <table class="table table-custom align-middle mb-0 w-100">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No.</th>
                            <th>Kode SKU</th>
                            <th>Nama Produk Outfit</th>
                            <th>Kategori</th>
                            <th class="text-center">Kuantitas Masuk</th>
                            <th class="text-end">Harga Beli Satuan</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchase->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><code>{{ $item->product->sku ?? '-' }}</code></td>
                            <td>
                                <div class="fw-bold text-dark">{{ $item->product->name ?? 'Produk Outfit' }}</div>
                                <div class="small text-muted">
                                    Size: {{ $item->product->size ?: '-' }} | Warna: {{ $item->product->color ?: '-' }}
                                </div>
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ $item->product->category->name ?? '-' }}</span></td>
                            <td class="text-center fw-bold text-primary">{{ $item->quantity }} pcs</td>
                            <td class="text-end text-muted">{{ \App\Support\FormatHelper::rupiah($item->unit_cost) }}</td>
                            <td class="text-end fw-bold text-dark">{{ \App\Support\FormatHelper::rupiah($item->subtotal) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="text-end fw-bold">Total Kuantitas:</td>
                            <td class="text-center fw-extrabold text-primary fs-6">{{ $purchase->items->sum('quantity') }} pcs</td>
                            <td class="text-end fw-bold">Total Nilai Pengadaan:</td>
                            <td class="text-end fw-extrabold text-danger fs-5">{{ \App\Support\FormatHelper::rupiah($purchase->total) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Footer Signatures -->
            <div class="row pt-4 text-center">
                <div class="col-6">
                    <span class="small text-muted d-block mb-5">Pihak Pemasok / Pengirim,</span>
                    <strong class="text-dark d-block border-top pt-2 mx-auto" style="max-width: 200px;">
                        {{ $purchase->supplier->name }}
                    </strong>
                </div>
                <div class="col-6">
                    <span class="small text-muted d-block mb-5">Petugas Penerima Gudang,</span>
                    <strong class="text-dark d-block border-top pt-2 mx-auto" style="max-width: 200px;">
                        {{ $purchase->user->name }}
                    </strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
