@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title mb-3">Edit Data Kategori</h4>

        <form action="{{ route('kategori.update', $kategori->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-12">
                    <div class="form-floating mb-3">
                        <input 
                            type="text" 
                            class="form-control" 
                            name="nama_kategori" 
                            id="tb-nama-kategori"
                            value="{{ old('nama_kategori', $kategori->nama_kategori) }}"
                            placeholder="Nama Kategori"
                        />
                        <label for="tb-nama-kategori" class="text-dark">
                            Nama Kategori
                        </label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-floating mb-3">
                        <textarea 
                            class="form-control" 
                            name="deskripsi" 
                            id="tb-deskripsi"
                            placeholder="Deskripsi"
                            style="height: 120px"
                        >{{ old('deskripsi', $kategori->deskripsi) }}</textarea>
                        <label for="tb-deskripsi" class="text-dark">
                            Deskripsi (opsional)
                        </label>
                    </div>
                </div>
            </div>

            <div class="row d-flex justify-content-between">
                <div class="col text-start">
                    <button type="submit" class="btn btn-success">
                        Edit Kategori
                    </button>
                </div>
                <div class="col text-end">
                    <a href="{{ route('kategori.index') }}" class="btn" style="background-color: grey; color: white;">
                        Batal
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
