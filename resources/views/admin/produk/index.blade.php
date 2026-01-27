@extends('layouts.app')
@section('content')

<div class="datatables">
    <div class="card mt-5">
        <div class="card-body">

            <div class="card-title d-flex justify-content-between mb-4">
                <div class="col text-start">
                    <h4>Data Produk</h4>
                </div>
                <div class="col text-end">
                    <a href="{{ route('produk.create') }}" class="btn btn-primary">
                        <h5 class="text-white">Tambah Produk</h5>
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table id="default_produk" class="table table-striped table-bordered display text-nowrap">
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Kios</th>
                            <th>Gambar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse ($produks as $p)
                        <tr>
                            <td>{{ $p->nama_produk }}</td>
                            <td>{{ $p->kategori->nama_kategori ?? '-' }}</td>
                            <td>Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                            <td>{{ $p->kios->nama_kios ?? '-' }}</td>
                            <td>
                                @if ($p->foto != null)
                                    <img src="{{ asset('storage/'.$p->foto) }}" 
                                         width="70px" class="rounded">
                                @else
                                    -
                                @endif
                            </td>
                            <td class="d-flex gap-2">
                                <a href="{{ route('produk.edit', $p->id) }}" 
                                   class="btn btn-success">
                                    <i class="ti ti-pencil"></i>
                                </a>
                            

                                <form action="{{ route('produk.destroy', $p->id) }}" 
                                      method="POST"
                                      onsubmit="return confirm('Yakin mau hapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <p style="color: red;">*tidak ada data produk</p>
                        @endforelse

                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function () {
    if (!$.fn.DataTable.isDataTable('#default_produk')) {
        $('#default_produk').DataTable({
            responsive: true
        });
    }
});
</script>
@endpush
