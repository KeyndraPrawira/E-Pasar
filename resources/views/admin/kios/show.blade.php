@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="card-title mb-0">Detail Kios</h4>
            <a href="{{ route('kios.index') }}" class="btn btn-secondary">
                <i class="ti ti-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="row g-4">

            {{-- FOTO KIOS --}}
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <h6 class="mb-3">Foto Kios</h6>

                        @if ($kios->foto_kios)
                            <img 
                                src="{{ asset('storage/' . $kios->foto_kios) }}" 
                                class="img-fluid rounded"
                                style="max-height: 250px; object-fit: cover;"
                                alt="Foto Kios"
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

            {{-- INFORMASI KIOS --}}
            <div class="col-md-8">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row mb-3">
                            
                            <div class="col-md-4 text-muted">Jumlah Produk</div>
                            <div class="col-md-8 fw-semibold">
                               <p>{{ $kios->produk->count() }} produk</p> 
                            </div>
                        </div>


                        <div class="row mb-3">
                            <div class="col-md-4 text-muted">Nama Kios</div>
                            <div class="col-md-8 fw-semibold">
                                {{ $kios->nama_kios }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 text-muted">Pasar</div>
                            <div class="col-md-8 fw-semibold">
                                {{ $kios->pasar?->nama_pasar ?? '-' }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 text-muted">Pedagang</div>
                            <div class="col-md-8 fw-semibold">
                                {{ $kios->user?->name ?? '-' }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 text-muted">Lokasi</div>
                            <div class="col-md-8 fw-semibold">
                                {{ $kios->lokasi ?? '-' }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 text-muted">Jam Operasional</div>
                            <div class="col-md-8 fw-semibold">
                                {{ $kios->jam_buka.  '-' .$kios->jam_tutup}}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 text-muted">Deskripsi</div>
                            <div class="col-md-8 fw-semibold">
                                {{ $kios->deskripsi ?? '-' }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>


        {{-- ACTION --}}
        <div class="d-flex justify-content-end mt-4">
            <a href="{{ route('kios.edit', $kios->id) }}" class="btn btn-warning">
                <i class="ti ti-edit"></i> Edit Kios
            </a>
        </div>


        <h5 class="card-title mb-4 mt-4"> Daftar Produk <strong>{{ $kios->nama_kios }}</strong></h5>
        
@forelse($kios->produk as $produk)
        <div class="row g-4 mb-4">
            <div class="col-lg-3 col-md-6 col-sm-12">
                <div class="card h-100 border-0 shadow-sm">
                    {{-- GAMBAR FULL WIDTH VERTIKAL --}}
                    <div class="position-relative">
                        @if($produk->foto)
                            <img src="{{ asset('storage/' . $produk->foto) }}" 
                                 class="card-img-top h-100" 
                                 style="height: 200px; object-fit: cover;"
                                 alt="{{ $produk->nama_produk }}">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" 
                                 style="height: 200px;">
                                <i class="ti ti-image-off fs-2 text-muted"></i>
                            </div>
                        @endif
                    </div>
                    
                    {{-- INFO FULL WIDTH --}}
                    <div class="card-body p-3">
                        <h6 class="card-title mb-2">{{ Str::limit($produk->nama_produk, 35) }}</h6>
                        
                        <div class="mb-2">
                            <span class="badge bg-primary">{{ $produk->kategori->nama_kategori ?? 'Uncategorized' }}</span>
                        </div>
                        
                        <div class="mb-2 small text-muted">
                            <i class="ti ti-package me-1"></i>
                            Stok: <strong>{{ number_format($produk->stok) }} {{ $produk->stok > 1 ? 'pcs' : 'pc' }}</strong>
                        </div>
                        
                        <div class="mb-3 fw-semibold text-success fs-5">
                            Rp {{ number_format($produk->harga, 0, ',', '.') }}
                        </div>
                        
                        <a href="{{ route('produks.show', $produk->id) }}" class="btn btn-primary w-100">
                            <i class="ti ti-eye"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="card border-dashed h-100">
            <div class="card-body text-center py-5 text-muted">
                <i class="ti ti-package-off fs-1 mb-3 opacity-50"></i>
                <h6>Belum ada produk di kios ini</h6>
                <p class="mb-0">Kios {{ $kios->nama_kios }} belum memiliki produk yang terdaftar.</p>
            </div>
        </div>
        @endforelse




    </div>
</div>
@endsection
