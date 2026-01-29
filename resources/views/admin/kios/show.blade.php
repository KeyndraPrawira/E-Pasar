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
                                src="{{ asset('storage' . $kios->foto_kios) }}" 
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
                                {{ $kios->produk->count() }} Produk
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
                            <div class="col-md-8">
                                {{ $kios->lokasi ?? '-' }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 text-muted">Nomor Telepon</div>
                            <div class="col-md-8">
                                {{ $kios->nomor_telepon ?? '-' }}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 text-muted">Deskripsi</div>
                            <div class="col-md-8">
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

    </div>
</div>
@endsection
