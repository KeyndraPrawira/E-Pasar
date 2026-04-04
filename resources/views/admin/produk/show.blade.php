@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="card-title mb-0">Detail Produk</h4>
            <a href="{{ route('produks.index') }}" class="btn btn-secondary">
                <i class="ti ti-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="row g-4">

            {{-- FOTO PRODUK --}}
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <h6 class="mb-3">Foto Produk</h6>

                        @if ($produk->foto)
                            <img 
                                src="{{ asset('storage/' . $produk->foto) }}" 
                                class="img-fluid rounded"
                                style="max-height: 300px; object-fit: cover;"
                                alt="Foto {{ $produk->nama_produk }}"
                            >
                        @else
                            <div class="text-muted">
                                <i class="ti ti-photo-off fs-1"></i>
                                <p class="mt-2 mb-0">Belum ada foto</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- INFORMASI PRODUK --}}
            <div class="col-md-8">
                <div class="card h-100">
                    <div class="card-body">
                        
                        <div class="row mb-4">
                            <div class="col-md-4 text-muted">Jumlah Kios</div>
                            <div class="col-md-8 fw-semibold">
                                <p>1 kios</p> 
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 text-muted">Nama Produk</div>
                            <div class="col-md-8 fw-semibold fs-4">
                                {{ $produk->nama_produk }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 text-muted">Kategori</div>
                            <div class="col-md-8 fw-semibold">
                                <span class="badge bg-primary fs-6 px-3 py-2">
                                    {{ $produk->kategori->nama_kategori ?? '-' }}
                                </span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 text-muted">Kios</div>
                            <div class="col-md-8 fw-semibold">
                                {{ $produk->kios->nama_kios ?? '-' }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 text-muted">Stok</div>
                            <div class="col-md-8 fw-semibold text-success fs-4">
                                {{ number_format($produk->stok) }} {{ $produk->stok > 1 ? 'pcs' : 'pc' }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 text-muted">Berat Satuan</div>
                            <div class="col-md-8 fw-semibold">
                                {{ number_format($produk->berat_satuan, 2) }} gram
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 text-muted">Harga</div>
                            <div class="col-md-8 fw-semibold text-success fs-3">
                                Rp {{ number_format($produk->harga, 0, ',', '.') }}
                            </div>
                        </div>

                        @if($produk->deskripsi)
                        <div class="row mb-3">
                            <div class="col-md-4 text-muted">Deskripsi</div>
                            <div class="col-md-8">
                                <p class="fw-semibold">{{ $produk->deskripsi }}</p>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
            </div>

        </div>

        {{-- ACTION --}}
        <div class="d-flex justify-content-end mt-4 gap-2">
            <a href="{{ route('produks.edit', $produk->id) }}" class="btn btn-warning">
                <i class="ti ti-edit"></i> Edit Produk
            </a>
            <a href="{{ route('produks.index') }}" class="btn btn-secondary">
                <i class="ti ti-list"></i> Lihat Semua
            </a>
        </div>

    </div>
</div>
@endsection

