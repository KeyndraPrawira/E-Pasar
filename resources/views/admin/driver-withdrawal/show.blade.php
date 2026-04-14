@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h4 class="card-title mb-1">Detail Withdraw Driver</h4>
                        <p class="text-muted mb-0">{{ $driverWithdrawal->user?->name }} • {{ $driverWithdrawal->user?->email }}</p>
                    </div>
                    <a href="{{ route('driver-withdrawals.index') }}" class="btn btn-outline-secondary">Kembali</a>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
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
                        <label class="form-label text-muted">Nominal Withdraw</label>
                        <div class="fw-semibold">Rp {{ number_format($driverWithdrawal->amount, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Status</label>
                        <div>
                            @if ($driverWithdrawal->status === \App\Models\DriverWithdrawal::STATUS_PENDING)
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif ($driverWithdrawal->status === \App\Models\DriverWithdrawal::STATUS_APPROVED)
                                <span class="badge bg-success">Approved</span>
                            @else
                                <span class="badge bg-danger">Rejected</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Nama Bank</label>
                        <div class="fw-semibold">{{ $driverWithdrawal->bank_name }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Nama Rekening</label>
                        <div class="fw-semibold">{{ $driverWithdrawal->account_name }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Nomor Rekening</label>
                        <div class="fw-semibold">{{ $driverWithdrawal->account_number }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Saldo Wallet Saat Ini</label>
                        <div class="fw-semibold">Rp {{ number_format($driverWithdrawal->wallet?->balance ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Diajukan Pada</label>
                        <div class="fw-semibold">{{ $driverWithdrawal->created_at?->format('d M Y H:i') }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Diproses Oleh</label>
                        <div class="fw-semibold">
                            {{ $driverWithdrawal->processor?->name ?? '-' }}
                            @if ($driverWithdrawal->processed_at)
                                <span class="d-block text-muted">{{ $driverWithdrawal->processed_at->format('d M Y H:i') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                @if ($driverWithdrawal->requested_notes)
                    <div class="alert alert-secondary mt-4 mb-3">
                        <strong>Catatan Driver:</strong><br>
                        {{ $driverWithdrawal->requested_notes }}
                    </div>
                @endif

                @if ($driverWithdrawal->admin_notes)
                    <div class="alert alert-light border mb-0">
                        <strong>Catatan Admin:</strong><br>
                        {{ $driverWithdrawal->admin_notes }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Proses Withdraw</h5>

                @if ($driverWithdrawal->isPending())
                    <form action="{{ route('driver-withdrawals.process', $driverWithdrawal) }}" method="POST">
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
                            <label for="transfer_reference" class="form-label">Referensi Transfer</label>
                            <input type="text" name="transfer_reference" id="transfer_reference" class="form-control @error('transfer_reference') is-invalid @enderror" value="{{ old('transfer_reference') }}" placeholder="Opsional, isi saat approve">
                            @error('transfer_reference')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="admin_notes" class="form-label">Catatan Admin</label>
                            <textarea name="admin_notes" id="admin_notes" rows="4" class="form-control @error('admin_notes') is-invalid @enderror" placeholder="Wajib diisi saat ditolak, opsional saat approve">{{ old('admin_notes') }}</textarea>
                            @error('admin_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Simpan Keputusan</button>
                    </form>
                @else
                    <div class="alert alert-info mb-0">
                        Permintaan withdraw ini sudah diproses dan tidak dapat diubah lagi dari halaman ini.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
