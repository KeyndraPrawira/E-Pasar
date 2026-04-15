@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="card-title mb-1">Transaksi</h4>
                <p class="text-muted mb-0">Daftar semua order yang telah dibuat pelanggan.</p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table id="default_order" class="table table-striped table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Kode Pesanan</th>
                        <th>Pelanggan</th>
                        <th>Driver</th>
                        <th>Total Item</th>
                        <th>Total Bayar</th>
                        <th>Metode Pembayaran</th>
                        <th>Tanggal</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $order->kode_pesanan }}</div>
                                <div class="text-muted small">{{ ucfirst($order->metode_pembayaran ?? '-') }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $order->buyer?->name ?? '-' }}</div>
                                <div class="text-muted small">{{ $order->buyer?->email ?? '-' }}</div>
                            </td>
                            <td>
                                @if ($order->driver)
                                    <div class="fw-semibold">{{ $order->driver->name }}</div>
                                    <div class="text-muted small">{{ $order->driver->email }}</div>
                                @else
                                    <span class="text-muted">Belum ada driver</span>
                                @endif
                            </td>
                            <td>{{ $order->orderDetailHistory->sum('jumlah') }} item</td>
                            <td>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                           
                            <td>
                                @php
                                    $metodePembayaran = match ($order->metode_pembayaran) {
                                        'cod' => 'warning text-dark',
                                        'midtrans' => 'info',
                                        default => 'secondary',
                                    };
                                    
                                @endphp
                                <span class="badge bg-{{ $metodePembayaran }}">
                                    {{ \Illuminate\Support\Str::headline($order->metode_pembayaran ?? 'belum ada') }}
                                </span>
                            </td>
                            <td>{{ $order->created_at?->format('d M Y H:i') }}</td>
                            <td class="text-center">
                                <a href="{{ route('transaksi.show', $order) }}" class="btn btn-primary btn-sm">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">Belum ada transaksi yang dibuat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
