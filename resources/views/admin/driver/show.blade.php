@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h4 class="card-title mb-1">Detail Pengajuan Driver</h4>
                        <p class="text-muted mb-0">{{ $driver->user->name }} • {{ $driver->user->email }}</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('driver.edit', $driver) }}" class="btn btn-outline-primary">Edit</a>
                        <form action="{{ route('driver.destroy', $driver) }}" method="POST" onsubmit="return confirm('Hapus driver ini? Dokumen juga akan dihapus.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">Hapus</button>
                        </form>
                        <a href="{{ route('driver.index') }}" class="btn btn-outline-secondary">Kembali</a>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
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

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted">Nomor Telepon</label>
                        <div class="fw-semibold">{{ $driver->user->nomor_telepon ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Status</label>
                        <div>
                            @if ($driver->status === \App\Models\Driver::STATUS_PENDING)
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif ($driver->status === \App\Models\Driver::STATUS_APPROVED)
                                <span class="badge bg-success">Approved</span>
                            @else
                                <span class="badge bg-danger">Rejected</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Nomor Kendaraan</label>
                        <div class="fw-semibold">{{ $driver->nomor_kendaraan }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Jenis Kendaraan</label>
                        <div class="fw-semibold">{{ $driver->jenis_kendaraan }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Nomor STNK</label>
                        <div class="fw-semibold">{{ $driver->nomor_stnk }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Nomor SIM</label>
                        <div class="fw-semibold">{{ $driver->nomor_sim }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Diajukan Pada</label>
                        <div class="fw-semibold">{{ $driver->created_at?->format('d M Y H:i') }}</div>
                    </div>
                   
                </div>

                @if ($driver->verification_notes)
                    <div class="alert alert-secondary mt-4 mb-0">
                        <strong>Catatan Verifikasi:</strong><br>
                        {{ $driver->verification_notes }}
                    </div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Dokumen Pendukung</h5>
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label">Foto KTP</label>
                        <a href="{{ asset('storage/' . $driver->foto_ktp) }}" class="d-block">
                            <img src="{{ asset('storage/' . $driver->foto_ktp) }}" alt="Foto KTP" class="img-fluid rounded border">
                        </a>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Foto SIM</label>
                        <a href="{{ asset('storage/' . $driver->foto_sim) }}" class="d-block">
                            <img src="{{ asset('storage/' . $driver->foto_sim) }}" alt="Foto SIM" class="img-fluid rounded border">
                        </a>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Foto STNK</label>
                        <a href="{{ asset('storage/' . $driver->foto_stnk) }}" class="d-block">
                            <img src="{{ asset('storage/' . $driver->foto_stnk) }}" alt="Foto STNK" class="img-fluid rounded border">
                        </a>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Foto Kendaraan</label>
                        <a href="{{ asset('storage/' . $driver->foto_kendaraan) }}" class="d-block">
                            <img src="{{ asset('storage/' . $driver->foto_kendaraan) }}" alt="Foto Kendaraan" class="img-fluid rounded border">
                        </a>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Foto Diri</label>
                        <a href="{{ asset('storage/' . $driver->foto_diri) }}" class="d-block">
                            <img src="{{ asset('storage/' . $driver->foto_diri) }}" alt="Foto Diri" class="img-fluid rounded border">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Verifikasi Admin</h5>

                @if ($driver->isPending())
                    <form action="{{ route('driver.verify', $driver) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="status" class="form-label">Keputusan</label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="">Pilih keputusan</option>
                                <option value="approved" @selected(old('status') === 'approved')>Terima</option>
                                <option value="rejected" @selected(old('status') === 'rejected')>Tolak</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="verification_notes" class="form-label">Catatan Verifikasi</label>
                            <textarea name="verification_notes" id="verification_notes" rows="4" class="form-control @error('verification_notes') is-invalid @enderror" placeholder="Opsional, misalnya alasan penolakan atau catatan penerimaan">{{ old('verification_notes') }}</textarea>
                            @error('verification_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Simpan Verifikasi</button>
                    </form>
                @else
                    <div class="alert alert-info mb-0">
                        Pengajuan ini sudah diverifikasi dan tidak dapat diproses ulang dari halaman ini.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
