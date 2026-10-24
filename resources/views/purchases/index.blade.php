@extends('layouts.app')

@section('title', 'Pembelian (Stock-In)')
@section('page_title', 'Transaksi Pembelian & Restok Gudang')

@section('content')
<div class="card card-master mb-4">
    <div class="card-header-master d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <h5 class="fw-bold mb-1 text-dark">Riwayat Pengadaan Pakaian (Stock-In)</h5>
            <span class="text-muted small">Catatan transaksi pasokan baju dari supplier konveksi ke gudang</span>
        </div>
        <a href="{{ route('purchases.create') }}" class="btn btn-brand-primary">
            <i class="bi bi-plus-circle me-1"></i> Buat Pengadaan Baru
        </a>
    </div>

    <div class="card-body-master">
        <!-- Filter Form -->
        <form action="{{ route('purchases.index') }}" method="GET" class="mb-4">
            <div class="row g-2 align-items-center">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control form-control-custom" placeholder="Cari No. PO atau supplier..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="supplier_id" class="form-select form-select-custom">
                        <option value="">-- Semua Supplier --</option>
                        @foreach($suppliers as $sup)
                            <option value="{{ $sup->id }}" {{ request('supplier_id') == $sup->id ? 'selected' : '' }}>
                                {{ $sup->company_name ?: $sup->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select form-select-custom">
                        <option value="">-- Status --</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-1">
                    <input type="date" name="start_date" class="form-control form-control-custom" title="Dari Tanggal" value="{{ request('start_date') }}">
                    <input type="date" name="end_date" class="form-control form-control-custom" title="Sampai Tanggal" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-1 d-flex gap-1">
                    <button type="submit" class="btn btn-dark w-100" title="Filter Data"><i class="bi bi-funnel"></i></button>
                    @if(request()->hasAny(['search', 'supplier_id', 'status', 'start_date', 'end_date']))
                        <a href="{{ route('purchases.index') }}" class="btn btn-light border" title="Reset"><i class="bi bi-x-circle"></i></a>
                    @endif
                </div>
            </div>
        </form>

        <!-- Table -->
        <div class="table-responsive rounded-3 border">
            <table class="table-custom align-middle">
                <thead>
                    <tr>
                        <th style="width: 50px;">No.</th>
                        <th>Nomor PO</th>
                        <th>Supplier / Konveksi</th>
                        <th>Petugas Input</th>
                        <th>Waktu Transaksi</th>
                        <th class="text-center">Variasi Item</th>
                        <th>Total Biaya</th>
                        <th class="text-center">Status</th>
                        <th class="text-end" style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchases as $index => $purchase)
                    <tr>
                        <td>{{ $purchases->firstItem() + $index }}</td>
                        <td class="fw-bold text-dark">
                            <a href="{{ route('purchases.show', $purchase) }}" class="text-decoration-none text-dark">
                                {{ $purchase->purchase_number }}
                            </a>
                        </td>
                        <td>{{ $purchase->supplier->company_name ?? $purchase->supplier->name ?? '-' }}</td>
                        <td>
                            <span class="badge bg-light text-secondary border">
                                <i class="bi bi-person me-1"></i>{{ $purchase->user->name ?? 'User' }}
                            </span>
                        </td>
                        <td class="text-muted small">{{ \App\Support\FormatHelper::indonesianDate($purchase->purchased_at, true) }}</td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark border">{{ $purchase->items_count }} item pakaian</span>
                        </td>
                        <td class="fw-bold text-dark">{{ \App\Support\FormatHelper::rupiah($purchase->total) }}</td>
                        <td class="text-center">
                            @if($purchase->status === 'completed')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-1">
                                    <i class="bi bi-check-circle me-1"></i> Selesai
                                </span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-2 py-1">
                                    <i class="bi bi-x-circle me-1"></i> Dibatalkan
                                </span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('purchases.show', $purchase) }}" class="btn btn-sm btn-outline-primary btn-action" title="Lihat Nota & Faktur">
                                <i class="bi bi-receipt"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">Belum ada riwayat transaksi pembelian stok.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 d-flex justify-content-end">
            {{ $purchases->links() }}
        </div>
    </div>
</div>
@endsection
