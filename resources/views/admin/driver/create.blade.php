@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title mb-3">Tambah Data Driver</h4>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('driver.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <h5 class="mt-4 mb-3">Informasi Akun</h5>
            <div class="row">
                <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" 
                                class="form-control @error('name') is-invalid @enderror" 
                                name="name" 
                                id="tb-fname" 
                                value="{{ old('name') }}" />
                            <label for="tb-fname" class="text-dark">Nama Pengguna</label>
                            
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" 
                                class="form-control @error('email') is-invalid @enderror" 
                                name="email" 
                                id="tb-email" 
                                value="{{ old('email') }}" />
                            <label for="tb-email" class="text-dark">Email</label>
                            
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" 
                                class="form-control @error('password') is-invalid @enderror" 
                                name="password" 
                                id="password" 
                                value="{{ old('password') }}" />
                            <label for="password" class="text-dark">Password</label>
                            
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" 
                                class="form-control @error('nomor_telepon') is-invalid @enderror" 
                                name="nomor_telepon" 
                                id="nomor_telepon" 
                                value="{{ old('nomor_telepon') }}" />
                            <label for="nomor_telepon" class="text-dark">Nomor Telepon</label>
                            
                            @error('nomor_telepon')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
            </div>

            <h5 class="mt-4 mb-3">Informasi Kendaraan & Dokumen</h5>
            <div class="row">
                <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" 
                                class="form-control @error('nomor_kendaraan') is-invalid @enderror" 
                                name="nomor_kendaraan" 
                                id="nomor_kendaraan" 
                                value="{{ old('nomor_kendaraan') }}" />
                            <label for="nomor_kendaraan" class="text-dark">Nomor Kendaraan (Plat)</label>
                            
                            @error('nomor_kendaraan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" 
                                class="form-control @error('jenis_kendaraan') is-invalid @enderror" 
                                name="jenis_kendaraan" 
                                id="jenis_kendaraan" 
                                value="{{ old('jenis_kendaraan') }}" />
                            <label for="jenis_kendaraan" class="text-dark">Jenis Kendaraan</label>
                            
                            @error('jenis_kendaraan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="number" 
                                class="form-control @error('nomor_stnk') is-invalid @enderror" 
                                name="nomor_stnk" 
                                id="nomor_stnk" 
                                value="{{ old('nomor_stnk') }}" />
                            <label for="nomor_stnk" class="text-dark">Nomor STNK</label>
                            
                            @error('nomor_stnk')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="number" 
                                class="form-control @error('nomor_sim') is-invalid @enderror" 
                                name="nomor_sim" 
                                id="nomor_sim" 
                                value="{{ old('nomor_sim') }}" />
                            <label for="nomor_sim" class="text-dark">Nomor SIM</label>
                            
                            @error('nomor_sim')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
            </div>

            <div class="row">
               <div class="col-md-3 mb-3">
                        <label class="form-label">Foto KTP</label>
                        <input type="file" 
                            name="foto_ktp" 
                            class="form-control @error('foto_ktp') is-invalid @enderror" 
                            accept="image/*">
                        @error('foto_ktp')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Foto SIM</label>
                    <input type="file" name="foto_sim" class="form-control @error('foto_sim') is-invalid @enderror" accept="image/*">
                    @error('foto_sim')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Foto STNK</label>
                    <input type="file" name="foto_stnk" class="form-control @error('foto_stnk') is-invalid @enderror" accept="image/*">
                    @error('foto_stnk')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Foto Kendaraan</label>
                    <input type="file" name="foto_kendaraan" class="form-control @error('foto_kendaraan') is-invalid @enderror" accept="image/*">
                    @error('foto_kendaraan')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="row d-flex justify-content-between mt-4">
                <div class="col text-start">
                    <button type="submit" class="btn btn-primary">Simpan Data Driver</button>
                </div>
                <div class="col text-end">
                    <a href="{{ route('driver.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection