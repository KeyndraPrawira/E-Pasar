@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h4 class="card-title mb-1">Status Verifikasi Driver</h4>
                        <p class="text-muted mb-0">Pantau hasil pengajuan driver Anda di halaman ini.</p>
                    </div>
                    @if (!$driver || $driver->isRejected())
                        <a href="{{ route('driver.application.create') }}" class="btn btn-primary">
                            {{ $driver ? 'Ajukan Ulang' : 'Daftar Driver' }}
                        </a>
                    @endif
                </div>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if (session('info'))
                    <div class="alert alert-info">{{ session('info') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if (!$driver)
                    <div class="alert alert-secondary mb-0">
                        Anda belum pernah mengajukan pendaftaran driver.
                    </div>
                @else
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <div class="text-muted small mb-1">Status Saat Ini</div>
                                @if ($driver->status === \App\Models\Driver::STATUS_PENDING)
                                    <span class="badge bg-warning text-dark mb-2">Pending</span>
                                    <p class="mb-0 text-muted">Pengajuan sedang ditinjau admin. Anda belum bisa mengambil order.</p>
                                @elseif ($driver->status === \App\Models\Driver::STATUS_APPROVED)
                                    <span class="badge bg-success mb-2">Approved</span>
                                    <p class="mb-0 text-muted">Pengajuan disetujui. Akun Anda sudah bisa menggunakan fitur driver.</p>
                                @else
                                    <span class="badge bg-danger mb-2">Rejected</span>
                                    <p class="mb-0 text-muted">Pengajuan ditolak. Anda bisa memperbaiki data lalu mengajukan ulang.</p>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <div class="text-muted small mb-1">Waktu Pengajuan</div>
                                <div class="fw-semibold">{{ $driver->created_at?->format('d M Y') }}</div>
                                @if ($driver->verified_at)
                                    <div class="text-muted small mt-3 mb-1">Waktu Verifikasi</div>
                                    <div class="fw-semibold">{{ $driver->verified_at->format('d M Y') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <div class="text-muted small mb-1">Nomor Kendaraan</div>
                                <div class="fw-semibold">{{ $driver->nomor_kendaraan }}</div>
                                <div class="text-muted small mt-3 mb-1">Jenis Kendaraan</div>
                                <div class="fw-semibold">{{ $driver->jenis_kendaraan }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <div class="text-muted small mb-1">Nomor STNK</div>
                                <div class="fw-semibold">{{ $driver->nomor_stnk }}</div>
                                <div class="text-muted small mt-3 mb-1">Nomor SIM</div>
                                <div class="fw-semibold">{{ $driver->nomor_sim }}</div>
                            </div>
                        </div>

                        @if ($driver->verification_notes)
                            <div class="col-12">
                                <div class="alert alert-secondary mb-0">
                                    <strong>Catatan Admin:</strong><br>
                                    {{ $driver->verification_notes }}
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
