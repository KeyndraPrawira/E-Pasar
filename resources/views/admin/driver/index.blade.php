@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="card-title mb-1">Pengajuan Driver</h4>
                <p class="text-muted mb-0">Daftar user yang mengajukan verifikasi driver.</p>
            </div>
            <a href="{{ route('driver.create') }}" class="btn btn-primary">Tambah Driver</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="table-responsive">
            <table id="default_order" class="table table-striped table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No. Telepon</th>
                        <th>No. Kendaraan</th>
                        <th>Jenis Kendaraan</th>
                        <th>Status</th>
                        <th>Diajukan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($drivers as $driver)
                        <tr>
                            <td>{{ $driver->user->name }}</td>
                            <td>{{ $driver->user->email }}</td>
                            <td>{{ $driver->user->nomor_telepon ?? '-' }}</td>
                            <td>{{ $driver->nomor_kendaraan }}</td>
                            <td>{{ $driver->jenis_kendaraan }}</td>
                            <td>
                                @if ($driver->status === \App\Models\Driver::STATUS_PENDING)
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif ($driver->status === \App\Models\Driver::STATUS_APPROVED)
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            </td>
                            <td>{{ $driver->created_at?->format('d M Y H:i') }}</td>
                            <td class="text-center">
                                <a href="{{ route('driver.show', $driver) }}" class="btn btn-primary btn-sm">
                                    Detail
                                </a>
                                <a href="{{ route('driver.edit', $driver) }}" class="btn btn-outline-secondary btn-sm">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Belum ada pengajuan driver.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
