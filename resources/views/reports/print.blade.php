<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi - Zahiyyah's Fit</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #2D2426;
            background-color: #FFFFFF;
            padding: 24px;
        }
        .report-header {
            border-bottom: 2px solid #2D2426;
            padding-bottom: 16px;
            margin-bottom: 24px;
        }
        .store-brand {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1.8rem;
            color: #B76E79;
        }
        .table-report th {
            background-color: #F8F9FA !important;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #DEE2E6;
        }
        .table-report td {
            font-size: 0.85rem;
            vertical-align: middle;
        }
        .summary-box {
            border: 1px solid #ECEEF2;
            border-radius: 10px;
            padding: 16px;
            background: #FAFAFB;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid max-w-100">
        <!-- Floating Print Actions -->
        <div class="no-print d-flex justify-content-between align-items-center mb-4 p-3 bg-light rounded-3 border">
            <div>
                <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
                </a>
            </div>
            <div class="d-flex gap-2">
                <button onclick="window.print()" class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm">
                    <i class="bi bi-printer-fill me-1"></i> Cetak / Simpan PDF
                </button>
            </div>
        </div>

        <!-- Store Official Header -->
        <div class="report-header d-flex justify-content-between align-items-end">
            <div>
                <div class="store-brand">Zahiyyah's Fit</div>
                <div class="fw-semibold">Modest Fashion Boutique & Sales Management System</div>
                <div class="text-muted small">Jl. Cihampelas No. 128, Bandung, Jawa Barat | Telp: (022) 7891234</div>
            </div>
            <div class="text-end">
                <h5 class="fw-bold mb-0 text-uppercase">
                    Laporan {{ $printData['type'] === 'purchases' ? 'Pengadaan Stok (Purchases)' : ($printData['type'] === 'stock' ? 'Valuasi Stok Outfit' : 'Penjualan (Sales)') }}
                </h5>
                <div class="text-muted small">Dicetak: {{ $printData['printed_at'] }}</div>
                @if(!empty($printData['filters']['start_date']) || !empty($printData['filters']['end_date']))
                    <div class="small fw-semibold mt-1">
                        Periode: {{ $printData['filters']['start_date'] ?? 'Awal' }} s/d {{ $printData['filters']['end_date'] ?? 'Sekarang' }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Financial Summary Cards Box -->
        <div class="row g-3 mb-4">
            <div class="col-4">
                <div class="summary-box">
                    <div class="text-muted small fw-bold text-uppercase">Total Penjualan</div>
                    <div class="fs-5 fw-bold text-success">{{ \App\Support\FormatHelper::rupiah($printData['summary']['total_sales_revenue']) }}</div>
                    <div class="small text-muted">{{ $printData['summary']['total_sales_count'] }} Transaksi Selesai</div>
                </div>
            </div>
            <div class="col-4">
                <div class="summary-box">
                    <div class="text-muted small fw-bold text-uppercase">Total Pengadaan Stok</div>
                    <div class="fs-5 fw-bold text-primary">{{ \App\Support\FormatHelper::rupiah($printData['summary']['total_purchases_expense']) }}</div>
                    <div class="small text-muted">{{ $printData['summary']['total_purchases_count'] }} PO Pembelian</div>
                </div>
            </div>
            <div class="col-4">
                <div class="summary-box">
                    <div class="text-muted small fw-bold text-uppercase">Estimasi Margin Bersih</div>
                    <div class="fs-5 fw-bold {{ $printData['summary']['net_margin'] >= 0 ? 'text-dark' : 'text-danger' }}">
                        {{ \App\Support\FormatHelper::rupiah($printData['summary']['net_margin']) }}
                    </div>
                    <div class="small text-muted">Rasio Margin: {{ $printData['summary']['profit_margin_percentage'] }}%</div>
                </div>
            </div>
        </div>

        <!-- Table Data -->
        @if($printData['type'] === 'purchases')
            <table class="table table-bordered table-report">
                <thead>
                    <tr>
                        <th style="width: 40px;">No</th>
                        <th>No. Pembelian</th>
                        <th>Tanggal</th>
                        <th>Supplier</th>
                        <th>Operator</th>
                        <th>Status</th>
                        <th class="text-end">Total Biaya</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($printData['records'] as $idx => $pur)
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td class="fw-bold font-monospace">{{ $pur->purchase_number }}</td>
                            <td>{{ $pur->purchased_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $pur->supplier->name ?? '-' }} ({{ $pur->supplier->company_name ?? '' }})</td>
                            <td>{{ $pur->user->name ?? '-' }}</td>
                            <td class="text-uppercase small">{{ $pur->status }}</td>
                            <td class="text-end fw-bold">{{ \App\Support\FormatHelper::rupiah($pur->total) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-3 text-muted">Tidak ada data pembelian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @elseif($printData['type'] === 'stock')
            <table class="table table-bordered table-report">
                <thead>
                    <tr>
                        <th style="width: 40px;">No</th>
                        <th>SKU</th>
                        <th>Produk Outfit</th>
                        <th>Kategori</th>
                        <th class="text-center">Stok</th>
                        <th class="text-end">Harga Beli</th>
                        <th class="text-end">Harga Jual</th>
                        <th class="text-end">Nilai Pokok</th>
                        <th class="text-end">Nilai Retail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($printData['records'] as $idx => $prod)
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td class="font-monospace small">{{ $prod->sku }}</td>
                            <td>{{ $prod->name }}</td>
                            <td>{{ $prod->category->name ?? '-' }}</td>
                            <td class="text-center fw-bold">{{ $prod->stock }} pcs</td>
                            <td class="text-end">{{ \App\Support\FormatHelper::rupiah($prod->purchase_price) }}</td>
                            <td class="text-end">{{ \App\Support\FormatHelper::rupiah($prod->selling_price) }}</td>
                            <td class="text-end">{{ \App\Support\FormatHelper::rupiah($prod->stock * $prod->purchase_price) }}</td>
                            <td class="text-end fw-bold">{{ \App\Support\FormatHelper::rupiah($prod->stock * $prod->selling_price) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-3 text-muted">Tidak ada data produk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @else
            <table class="table table-bordered table-report">
                <thead>
                    <tr>
                        <th style="width: 40px;">No</th>
                        <th>No. Nota</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Metode Bayar</th>
                        <th>Status</th>
                        <th>Pembayaran</th>
                        <th class="text-end">Total Tagihan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($printData['records'] as $idx => $sale)
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td class="fw-bold font-monospace">{{ $sale->sale_number }}</td>
                            <td>{{ $sale->sold_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $sale->customer_name }}</td>
                            <td class="text-uppercase small">{{ $sale->payment_method ?? 'CASH' }}</td>
                            <td class="text-uppercase small">{{ $sale->status }}</td>
                            <td class="text-uppercase small">{{ $sale->payment_status }}</td>
                            <td class="text-end fw-bold">{{ \App\Support\FormatHelper::rupiah($sale->total) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-3 text-muted">Tidak ada transaksi penjualan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @endif

        <!-- Signature Section -->
        <div class="row mt-5 pt-4">
            <div class="col-6 text-center">
                <div class="text-muted small mb-5">Petugas / Operator Kasir</div>
                <div class="fw-bold text-decoration-underline">{{ auth()->user()->name ?? 'Operator' }}</div>
                <div class="text-muted small">Staff Zahiyyah's Fit</div>
            </div>
            <div class="col-6 text-center">
                <div class="text-muted small mb-5">Bandung, {{ date('d F Y') }}<br>Pemilik / Store Manager</div>
                <div class="fw-bold text-decoration-underline">Owner Zahiyyah's Fit</div>
                <div class="text-muted small">Pimpinan Manajemen</div>
            </div>
        </div>
    </div>
</body>
</html>
