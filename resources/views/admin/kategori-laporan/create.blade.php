@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title mb-3">Tambah Kategori Laporan</h4>

        <form action="{{ route('kategori-laporan.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-12">
                    <div class="form-floating mb-3">
                        <input 
                            type="text" 
                            class="form-control @error('nama') is-invalid @enderror" 
                            name="nama" 
                            id="tb-nama"
                            value="{{ old('nama') }}"
                            placeholder="Nama"
                            required
                        />
                        <label for="tb-nama" class="text-dark">
                            Nama Kategori Laporan *
                        </label>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-floating mb-3">
                        <select class="form-control @error('reportable_type') is-invalid @enderror" name="reportable_type" id="tb-reportable-type" required>
                            <option value="">Pilih Tipe</option>
                            <option value="App\\Models\\Produk" {{ old('reportable_type') == 'App\\\\Models\\\\Produk' ? 'selected' : '' }}>Produk</option>
                            <option value="App\\Models\\Kios" {{ old('reportable_type') == 'App\\\\Models\\\\Kios' ? 'selected' : '' }}>Kios</option>
                            <option value="App\\Models\\Driver" {{ old('reportable_type') == 'App\\\\Models\\\\Driver' ? 'selected' : '' }}>Driver</option>
                        </select>
                        <label for="tb-reportable-type" class="text-dark">
                            Tipe Reportable *
                        </label>
                        @error('reportable_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" {{ old('is_active', 1) ? 'checked' : '' }} value="1">
                        <label class="form-check-label" for="is_active">
                            Aktif
                        </label>
                    </div>
                    @error('is_active')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row d-flex justify-content-between">
                <div class="col text-start">
                    <button type="submit" class="btn btn-primary">
                        Simpan
                    </button>
                </div>
                <div class="col text-end">
                    <a href="{{ route('kategori-laporan.index') }}" class="btn btn-secondary">
                        Batal
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

