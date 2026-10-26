@extends('layouts.app')

@section('title', 'Master Kategori')
@section('page_title', 'Master Kategori Outfit')

@section('content')
<div class="card card-master mb-4">
    <div class="card-header-master d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div>
            <h5 class="fw-bold mb-1 text-dark">Daftar Kategori Busana</h5>
            <span class="text-muted small">Kelola klasifikasi produk outfit Zahiyyah's Fit</span>
        </div>
        <a href="{{ route('categories.create') }}" class="btn btn-brand-primary">
            <i class="bi bi-plus-circle me-1"></i> Tambah Kategori
        </a>
    </div>

    <div class="card-body-master">
        <form action="{{ route('categories.index') }}" method="GET" class="mb-4">
            <div class="row g-2">
                <div class="col-md-6 col-lg-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control form-control-custom" placeholder="Cari kategori..." value="{{ request('search') }}">
                        <button class="btn btn-dark" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </div>
                @if(request('search'))
                    <div class="col-auto">
                        <a href="{{ route('categories.index') }}" class="btn btn-light border"><i class="bi bi-x-circle"></i> Reset</a>
                    </div>
                @endif
            </div>
        </form>

        <div class="table-responsive rounded-3 border">
            <table class="table table-custom align-middle w-100">
                <thead>
                    <tr>
                        <th style="width: 60px;">No.</th>
                        <th>Nama Kategori</th>
                        <th>Slug URL</th>
                        <th>Deskripsi</th>
                        <th class="text-center">Jumlah Produk</th>
                        <th class="text-end" style="width: 120px; white-space: nowrap;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $index => $category)
                    <tr>
                        <td>{{ $categories->firstItem() + $index }}</td>
                        <td class="fw-bold text-dark">{{ $category->name }}</td>
                        <td><code>{{ $category->slug }}</code></td>
                        <td class="text-muted small">{{ Str::limit($category->description, 60, '...') ?: '-' }}</td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark border px-2 py-1">{{ $category->products_count }} item</span>
                        </td>
                        <td class="text-end" style="white-space: nowrap;">
                            <div class="d-inline-flex align-items-center gap-1 flex-nowrap">
                                <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-outline-primary btn-action" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger btn-action" title="Hapus" 
                                        onclick="confirmDelete('del-cat-{{ $category->id }}', '{{ $category->name }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <form id="del-cat-{{ $category->id }}" action="{{ route('categories.destroy', $category) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada data kategori.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 d-flex justify-content-end">
            {{ $categories->links() }}
        </div>
    </div>
</div>
@endsection
