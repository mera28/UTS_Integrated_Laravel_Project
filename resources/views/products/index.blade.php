@extends('layouts.app')

@section('title', 'Master Produk Outfit')
@section('page_title', 'Master Produk Outfit & Busana')

@section('content')
<div class="card card-master mb-4">
    <div class="card-header-master d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <h5 class="fw-bold mb-1 text-dark">Katalog & Manajemen Stok Pakaian</h5>
            <span class="text-muted small">Kelola data pakaian, varian ukuran, warna, serta batas minimum stok</span>
        </div>
        @if(auth()->user()->isAdmin())
            <a href="{{ route('products.create') }}" class="btn btn-brand-primary">
                <i class="bi bi-plus-circle me-1"></i> Tambah Produk Baru
            </a>
        @endif
    </div>

    <div class="card-body-master">
        <!-- Filter Form -->
        <form action="{{ route('products.index') }}" method="GET" class="mb-4">
            <div class="row g-2 align-items-center">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control form-control-custom" placeholder="Cari SKU, nama produk, warna..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="category_id" class="form-select form-select-custom">
                        <option value="">-- Semua Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="stock_status" class="form-select form-select-custom">
                        <option value="">-- Status Stok --</option>
                        <option value="available" {{ request('stock_status') == 'available' ? 'selected' : '' }}>Stok Aman</option>
                        <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Stok Menipis (Low Stock)</option>
                        <option value="empty" {{ request('stock_status') == 'empty' ? 'selected' : '' }}>Stok Habis</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-dark w-100">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    @if(request()->hasAny(['search', 'category_id', 'stock_status']))
                        <a href="{{ route('products.index') }}" class="btn btn-light border" title="Reset Filter">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>

        <!-- Product Table -->
        <div class="table-responsive rounded-3 border">
            <table class="table-custom align-middle">
                <thead>
                    <tr>
                        <th style="width: 50px;">No.</th>
                        <th>Foto</th>
                        <th>SKU & Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga Modal</th>
                        <th>Harga Jual</th>
                        <th class="text-center">Sisa Stok</th>
                        <th class="text-end" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $index => $product)
                    <tr>
                        <td>{{ $products->firstItem() + $index }}</td>
                        <td>
                            <img src="{{ $product->image_url ?? 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=200&auto=format&fit=crop' }}" 
                                 alt="{{ $product->name }}" 
                                 class="rounded-3 border object-fit-cover" 
                                 style="width: 48px; height: 48px;"
                                 onerror="this.src='https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=200&auto=format&fit=crop'">
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $product->name }}</div>
                            <div class="small text-muted">
                                <code>{{ $product->sku }}</code> | Size: {{ $product->size ?: '-' }}
                            </div>
                        </td>
                        <td><span class="badge bg-light text-dark border">{{ $product->category->name ?? '-' }}</span></td>
                        <td class="text-muted">{{ \App\Support\FormatHelper::rupiah($product->purchase_price) }}</td>
                        <td class="fw-bold text-dark">{{ \App\Support\FormatHelper::rupiah($product->selling_price) }}</td>
                        <td class="text-center">
                            {!! \App\Support\FormatHelper::stockBadge($product->stock, $product->minimum_stock) !!}
                            <div class="small text-muted mt-1" style="font-size: 0.72rem;">Min: {{ $product->minimum_stock }} pcs</div>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-info btn-action me-1" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-primary btn-action me-1" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger btn-action" title="Hapus (Soft Delete)"
                                        onclick="confirmDelete('del-prod-{{ $product->id }}', '{{ $product->name }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <form id="del-prod-{{ $product->id }}" action="{{ route('products.destroy', $product) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Tidak ada produk outfit yang sesuai kriteria pencarian.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 d-flex justify-content-end">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
