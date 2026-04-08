@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h4 class="card-title mb-1">Daftar Sebagai Driver</h4>
                        <p class="text-muted mb-0">Lengkapi data kendaraan dan dokumen untuk proses verifikasi admin.</p>
                    </div>
                    <a href="{{ route('driver.application.status') }}" class="btn btn-outline-secondary">Lihat Status</a>
                </div>

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if ($driver && $driver->isRejected())
                    <div class="alert alert-warning">
                        Pengajuan sebelumnya ditolak. Silakan perbarui data dan unggah ulang dokumen untuk mengajukan kembali.
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('driver.application.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('nomor_kendaraan') is-invalid @enderror" id="nomor_kendaraan" name="nomor_kendaraan" value="{{ old('nomor_kendaraan', $driver?->nomor_kendaraan) }}" placeholder="Nomor Kendaraan">
                                <label for="nomor_kendaraan">Nomor Kendaraan</label>
                                @error('nomor_kendaraan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('jenis_kendaraan') is-invalid @enderror" id="jenis_kendaraan" name="jenis_kendaraan" value="{{ old('jenis_kendaraan', $driver?->jenis_kendaraan) }}" placeholder="Jenis Kendaraan">
                                <label for="jenis_kendaraan">Jenis Kendaraan</label>
                                @error('jenis_kendaraan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('nomor_stnk') is-invalid @enderror" id="nomor_stnk" name="nomor_stnk" value="{{ old('nomor_stnk', $driver?->nomor_stnk) }}" placeholder="Nomor STNK">
                                <label for="nomor_stnk">Nomor STNK</label>
                                @error('nomor_stnk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('nomor_sim') is-invalid @enderror" id="nomor_sim" name="nomor_sim" value="{{ old('nomor_sim', $driver?->nomor_sim) }}" placeholder="Nomor SIM">
                                <label for="nomor_sim">Nomor SIM</label>
                                @error('nomor_sim')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="foto_ktp" class="form-label">Foto KTP</label>
                            <input type="file" name="foto_ktp" id="foto_ktp" class="form-control @error('foto_ktp') is-invalid @enderror" accept=".jpg,.jpeg,.png,image/*">
                            @error('foto_ktp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="foto_sim" class="form-label">Foto SIM</label>
                            <input type="file" name="foto_sim" id="foto_sim" class="form-control @error('foto_sim') is-invalid @enderror" accept=".jpg,.jpeg,.png,image/*">
                            @error('foto_sim')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="foto_stnk" class="form-label">Foto STNK</label>
                            <input type="file" name="foto_stnk" id="foto_stnk" class="form-control @error('foto_stnk') is-invalid @enderror" accept=".jpg,.jpeg,.png,image/*">
                            @error('foto_stnk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="foto_kendaraan" class="form-label">Foto Kendaraan</label>
                            <input type="file" name="foto_kendaraan" id="foto_kendaraan" class="form-control @error('foto_kendaraan') is-invalid @enderror" accept=".jpg,.jpeg,.png,image/*">
                            @error('foto_kendaraan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="alert alert-info mb-4">
                        Pastikan foto dokumen terbaca jelas. Setiap pengajuan baru akan masuk dengan status <strong>pending</strong>.
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('driver.application.status') }}" class="btn btn-light">Batal</a>
                        <button type="submit" class="btn btn-primary">Kirim Pengajuan Driver</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
