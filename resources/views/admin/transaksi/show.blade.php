@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h4 class="card-title mb-1">Detail Transaksi</h4>
                        <p class="text-muted mb-0">{{ $order->kode_pesanan }} | {{ $order->created_at?->format('d M Y H:i') }}</p>
                    </div>
                    <a href="{{ route('transaksi.index') }}" class="btn btn-outline-secondary">Kembali</a>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted">Pelanggan</label>
                        <div class="fw-semibold">{{ $order->buyer?->name ?? '-' }}</div>
                        <div class="text-muted small">{{ $order->buyer?->email ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Driver</label>
                        @if ($order->driver)
                            <div class="fw-semibold">{{ $order->driver->name }}</div>
                            <div class="text-muted small">{{ $order->driver->email }}</div>
                        @else
                            <div class="text-muted">Belum ada driver yang mengambil order ini.</div>
                        @endif
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label text-muted">Metode Pembayaran</label>
                        <div class="fw-semibold">{{ strtoupper($order->metode_pembayaran ?? '-') }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Jarak Pengiriman</label>
                        <div class="fw-semibold">{{ number_format((float) $order->jarak_km, 2, ',', '.') }} km</div>
                    </div>
                    <div class="col-12">
                        <label class="form-label text-muted">Alamat Pengiriman</label>
                        <div class="fw-semibold">{{ $order->alamat_pengiriman }}</div>
                    </div>
                    @if ($order->catatan)
                        <div class="col-12">
                            <label class="form-label text-muted">Catatan Pelanggan</label>
                            <div class="fw-semibold">{{ $order->catatan }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Item Pesanan</h5>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Kios</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                                <th>Status Item</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($order->orderDetails as $detail)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $detail->produk?->nama_produk ?? 'Produk sudah tidak tersedia' }}</div>
                                        @if ($detail->produk?->kategori)
                                            <div class="text-muted small">{{ $detail->produk->kategori->nama_kategori }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $detail->kios?->nama_kios ?? $detail->produk?->kios?->nama_kios ?? '-' }}</div>
                                        <div class="text-muted small">{{ $detail->kios?->user?->name ?? $detail->produk?->kios?->user?->name ?? 'Pedagang tidak ditemukan' }}</div>
                                    </td>
                                    <td>Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                    <td>{{ $detail->jumlah }}</td>
                                    <td>Rp {{ number_format($detail->subtotal_harga, 0, ',', '.') }}</td>
                                    <td>
                                        @php
                                            $statusItemClass = match ($detail->status) {
                                                'pending', 'pending_request' => 'warning text-dark',
                                                'diambil', 'diganti' => 'success',
                                                'tidak_ada' => 'danger',
                                                default => 'secondary',
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $statusItemClass }}">
                                            {{ \Illuminate\Support\Str::headline($detail->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Belum ada item pada transaksi ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Ringkasan Pembayaran</h5>

                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Total Harga Barang</span>
                    <span class="fw-semibold">Rp {{ number_format($order->total_harga_barang, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Ongkir</span>
                    <span class="fw-semibold">Rp {{ number_format($order->ongkir, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Total Bayar</span>
                    <span class="fw-bold text-primary">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Referensi Pembayaran</span>
                    <span class="fw-semibold text-end">{{ $order->payment_reference ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Dibayar Pada</span>
                    <span class="fw-semibold text-end">{{ $order->paid_at?->format('d M Y H:i') ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
