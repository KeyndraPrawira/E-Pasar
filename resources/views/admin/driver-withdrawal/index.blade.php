@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="card-title mb-1">Withdraw Driver</h4>
                <p class="text-muted mb-0">Daftar permintaan pencairan saldo dari driver.</p>
            </div>
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
                        <th>Driver</th>
                        <th>Nominal</th>
                        <th>Bank</th>
                        <th>Rekening</th>
                        <th>Status</th>
                        <th>Diajukan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($withdrawals as $withdrawal)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $withdrawal->user?->name }}</div>
                                <div class="text-muted small">{{ $withdrawal->user?->email }}</div>
                            </td>
                            <td>Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}</td>
                            <td>{{ $withdrawal->bank_name }}</td>
                            <td>
                                <div class="fw-semibold">{{ $withdrawal->account_name }}</div>
                                <div class="text-muted small">{{ $withdrawal->account_number }}</div>
                            </td>
                            <td>
                                @if ($withdrawal->status === \App\Models\DriverWithdrawal::STATUS_PENDING)
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif ($withdrawal->status === \App\Models\DriverWithdrawal::STATUS_APPROVED)
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            </td>
                            <td>{{ $withdrawal->created_at?->format('d M Y H:i') }}</td>
                            <td class="text-center">
                                <a href="{{ route('driver-withdrawals.show', $withdrawal) }}" class="btn btn-primary btn-sm">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada permintaan withdraw driver.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
