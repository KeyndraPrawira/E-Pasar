@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title mb-3">Tambah Data Pedagang</h4>
        
        <form action="{{ route('pedagang.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-12">
                    <div class="form-floating mb-3">
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Nama" value="{{ old('name') }}">
                        <label for="name">Nama Lengkap</label>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-floating mb-3">
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="Email" value="{{ old('email') }}">
                        <label for="email">Email</label>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-floating mb-3">
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Password">
                        <label for="password">Password</label>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-floating mb-3">
                        <input type="text" name="nomor_telepon" class="form-control @error('nomor_telepon') is-invalid @enderror" id="nomor_telepon" placeholder="08xxx" value="{{ old('nomor_telepon') }}">
                        <label for="nomor_telepon">Nomor Telepon</label>
                        @error('nomor_telepon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-floating mb-3">
                        <select name="kios_id" class="form-control @error('kios_id') is-invalid @enderror" id="kios_id">
                            <option value="" disabled {{ old('kios_id') ? '' : 'selected' }}>Pilih kios</option>
                            @foreach ($availableKios as $kios)
                                <option value="{{ $kios->id }}" {{ old('kios_id') == $kios->id ? 'selected' : '' }}>
                                    {{ $kios->nama_kios }}
                                </option>
                            @endforeach
                        </select>
                        <label for="kios_id">Kios</label>
                        @error('kios_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @if ($availableKios->isEmpty())
                        <small class="text-danger d-block mt-n2 mb-3">Belum ada kios kosong. Tambahkan kios dulu sebelum membuat pedagang.</small>
                    @endif
                </div>

            </div>

            <div class="row d-flex justify-content-between mt-3">
                <div class="col text-start">
                    <button type="submit" class="btn btn-primary" {{ $availableKios->isEmpty() ? 'disabled' : '' }}>Simpan Pedagang</button>
                </div>
                <div class="col text-end">
                    <a href="{{ route('pedagang.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
