@extends('layouts.app')

@section('content')
<div class="datatables">
    <div class="card mt-5">
        <div class="card-body">
            <div class="card-title d-flex justify-content-between mb-4">
                <div class="col text-start">
                    <h4>Data Driver</h4>
                </div>
                <div class="col text-end">
                    <a href="{{ route('driver.create') }}" class="btn btn-primary">Tambah Driver</a>
                </div>
            </div>

            <div class="table-responsive">
                <table id="default_order" class="table table-striped table-bordered display text-nowrap">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No. Telepon</th>
                            <th>No. Kendaraan</th>
                            <th>Jenis</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($driver as $item)
                        <tr>
                            <td>{{ $item->user->name }}</td>
                            <td>{{ $item->user->email }}</td>
                            <td>{{ $item->user->nomor_telepon }}</td>
                            <td>{{ $item->nomor_kendaraan }}</td>
                            <td>{{ $item->jenis_kendaraan }}</td>
                            <td align="center">
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ route('driver.edit', $item->id) }}" class="btn btn-success">
                                        <i class="ti ti-pencil"></i>
                                    </a>
                                    <a href="{{ route('driver.destroy', $item->id) }}" class="btn btn-danger" data-confirm-delete="true">
                                        <i class="ti ti-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection