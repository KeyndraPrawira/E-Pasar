@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title mb-3">Tambah Data Produk</h4>
        @if ($errors->any())
                        <div style="background:#fee; padding:10px;">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif     
        <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Nama Produk --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="nama_produk" id="tb-nama-produk">
                        <label for="tb-nama-produk" class="text-dark">Nama Produk</label>
                    </div>
                </div>
            </div>

            {{-- Kategori --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-floating mb-3">
                        <select name="kategori_id" class="form-control" id="tb-kategori">
                            <option value="" selected disabled>Pilih Kategori</option>
                            @foreach ($kategori as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
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
                            <option value="" selected disabled>Pilih Kios</option>
                            @foreach ($kios as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kios }}</option>
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
                        <input type="number" class="form-control" name="harga" id="tb-harga">
                        <label for="tb-harga" class="text-dark">Harga</label>
                    </div>
                </div>
            </div>

            {{-- Berat satuan --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" name="berat_satuan" id="tb-bruto">
                        <label for="tb-bruto" class="text-dark">Berat satuan/bruto (gram)</label>
                    </div>
                </div>
            </div>

            {{-- Stok --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-floating mb-3">
                        <input type="number" class="form-control" name="stok" id="tb-stok">
                        <label for="tb-stok" class="text-dark">Stok</label>
                    </div>
                </div>
            </div>

            {{-- Deskripsi --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-floating mb-3">
                        <textarea name="deskripsi" class="form-control" id="tb-deskripsi"></textarea>
                        <label for="tb-deskripsi" class="text-dark">Deskripsi (opsional)</label>
                    </div>
                </div>
            </div>

            {{-- Foto Produk --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-floating mb-3">
                        <input type="file" name="foto" class="form-control" id="tb-foto">
                        <label for="tb-foto" class="text-dark">Foto Produk</label>
                    </div>
                </div>
            </div>

            {{-- Button --}}
            <div class="row d-flex justify-content-between">
                <div class="col text-start">
                    <button type="submit" class="btn btn-primary">Simpan Produk</button>
                </div>
                <div class="col text-end">
                    <a href="{{ route('produk.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </div>

        </form>
    </div>
</div>
@endsection
