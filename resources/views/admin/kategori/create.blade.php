@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title mb-3">Tambah Data Kategori</h4>

        <form action="{{ route('kategori.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-12">
                    <div class="form-floating mb-3">
                        <input 
                            type="text" 
                            class="form-control" 
                            name="nama_kategori" 
                            id="tb-nama-kategori"
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
                        ></textarea>
                        <label for="tb-deskripsi" class="text-dark">
                            Deskripsi (opsional)
                        </label>
                    </div>
                </div>
            </div>

            <div class="row d-flex justify-content-between">
                <div class="col text-start">
                    <button type="submit" class="btn btn-primary">
                        Simpan Kategori
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
