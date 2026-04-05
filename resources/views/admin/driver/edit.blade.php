@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title mb-3">Edit Data Driver: {{ $driver->user->name }}</h4>
        
        <form action="{{ route('driver.update', $driver->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" value="{{ $driver->nomor_kendaraan }}" name="nomor_kendaraan" required />
                        <label>Nomor Kendaraan</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" value="{{ $driver->jenis_kendaraan }}" name="jenis_kendaraan" required />
                        <label>Jenis Kendaraan</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" value="{{ $driver->nomor_stnk }}" name="nomor_stnk" required />
                        <label>Nomor STNK</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" value="{{ $driver->nomor_sim }}" name="nomor_sim" required />
                        <label>Nomor SIM</label>
                    </div>
                </div>
            </div>

            <div class="alert alert-info">Kosongkan upload gambar jika tidak ingin mengubah foto.</div>

            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Update KTP</label>
                    <input type="file" name="foto_ktp" class="form-control">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Update SIM</label>
                    <input type="file" name="foto_sim" class="form-control">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Update STNK</label>
                    <input type="file" name="foto_stnk" class="form-control">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Update Kendaraan</label>
                    <input type="file" name="foto_kendaraan" class="form-control">
                </div>
            </div>

            <div class="row d-flex justify-content-between mt-4">
                <div class="col text-start">
                    <button type="submit" class="btn btn-success">Update Data</button>
                </div>
                <div class="col text-end">
                    <a href="{{ route('driver.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection