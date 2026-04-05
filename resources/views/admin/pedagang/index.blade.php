@extends('layouts.app')

@section('content')
<div class="datatables">
    <div class="card mt-5">
        <div class="card-body">
            <div class="card-title d-flex justify-content-between mb-4">
                <div class="col text-start">
                    <h4>Data Pedagang</h4>
                </div>
                <div class="col text-end">
                    <a href="{{ route('pedagang.create') }}" class="btn btn-primary">Tambah Pedagang</a>
                </div>
            </div>
            
            <div class="table-responsive">
                <table id="default_order" class="table table-striped table-bordered display text-nowrap">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Nomor Telepon</th>
                            <th>Nama Kios</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pedagang as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->nomor_telepon }}</td>
                            <td>
                                {{ $item->kios->nama_kios ?? 'Belum Ada Kios' }}
                            </td>
                            <td align="center">
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ route('pedagang.edit', $item->id) }}" class="btn btn-success">
                                        <i class="ti ti-pencil"></i>
                                    </a>
                                    <a href="{{ route('pedagang.destroy', $item->id) }}" class="btn btn-danger" data-confirm-delete="true">
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