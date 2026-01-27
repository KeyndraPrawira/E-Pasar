@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title mb-3">Edit Data Produk</h4>

        <form action="{{ route('produk.update', $produk->id) }}" 
              method="POST" 
              enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Nama Produk --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-floating mb-3">
                        <input type="text"
                               class="form-control"
                               name="nama_produk"
                               id="tb-nama-produk"
                               value="{{ old('nama_produk', $produk->nama_produk) }}">
                        <label for="tb-nama-produk" class="text-dark">Nama Produk</label>
                    </div>
                </div>
            </div>

            {{-- Kategori --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-floating mb-3">
                        <select name="kategori_id" class="form-control" id="tb-kategori">
                            @foreach ($kategori as $k)
                                <option value="{{ $k->id }}"
                                    {{ $produk->kategori_id == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        <label for="tb-kategori" class="text-dark">Kategori</label>
                    </div>
                </div>
            </div>

            {{-- Kios --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-floating mb-3">
                        <select name="kios_id" class="form-control" id="tb-kios">
                            @foreach ($kios as $k)
                                <option value="{{ $k->id }}"
                                    {{ $produk->kios_id == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_kios }}
                                </option>
                            @endforeach
                        </select>
                        <label for="tb-kios" class="text-dark">Kios</label>
                    </div>
                </div>
            </div>

            {{-- Harga --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-floating mb-3">
                        <input type="number"
                               class="form-control"
                               name="harga"
                               id="tb-harga"
                               value="{{ old('harga', $produk->harga) }}">
                        <label for="tb-harga" class="text-dark">Harga</label>
                    </div>
                </div>
            </div>

            {{-- Stok --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-floating mb-3">
                        <input type="number"
                               class="form-control"
                               name="stok"
                               id="tb-stok"
                               value="{{ old('stok', $produk->stok) }}">
                        <label for="tb-stok" class="text-dark">Stok</label>
                    </div>
                </div>
            </div>

            {{-- Deskripsi --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-floating mb-3">
                        <textarea name="deskripsi"
                                  class="form-control"
                                  id="tb-deskripsi">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                        <label for="tb-deskripsi" class="text-dark">Deskripsi (opsional)</label>
                    </div>
                </div>
            </div>

            {{-- FOTO LAMA --}}
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="text-dark mb-2">Foto Produk Saat Ini</label><br>
                    @if ($produk->foto)
                        <img src="{{ asset('storage/'.$produk->foto) }}"
                             width="150"
                             class="rounded border">
                    @else
                        <p class="text-muted">Belum ada foto</p>
                    @endif
                </div>
            </div>

            {{-- Upload Foto Baru (Opsional) --}}
            <div class="row">
                <div class="col-md-12">
                        <input type="file"
                               name="foto"
                               class="form-control"
                               id="tb-foto">
                        <label for="tb-foto" class="text-dark">
                            Ganti Foto Produk (Opsional)
                        </label>
                    
                </div>
            </div>

            {{-- Button --}}
            <div class="row d-flex justify-content-between">
                <div class="col text-start">
                    <button type="submit" class="btn btn-success">
                        Edit Produk
                    </button>
                </div>
                <div class="col text-end">
                    <a href="{{ route('produk.index') }}" 
                       class="btn" style="background-color: grey;color:white;">
                        Batal
                    </a>
                </div>
            </div>

        </form>
    </div>
</div>
@endsection
