

@extends('layouts.app')
@section('content')


      <div class="datatables ">
            <!-- start Zero Configuration -->
            
            <!-- end Zero Configuration -->
            <!-- start Default Ordering -->
            <div class="card mt-5">
              <div class="card-body">
                <div class="card-title d-flex justify-content-between mb-4">
                    <div class="col text-start">
                        <h4>Data Kategori</h4>
                    </div>
                    <div class="col text-end">

                        <a href="{{ route('kategori.create') }}" class="btn btn-primary"><h5 class="text-white">Tambah Kategori</h5></a>
                    </div>
                </div>
                
                <div class="table-responsive">
                  <table id="default_kategori" class="table table-striped table-bordered display text-nowrap">
                    <thead>
                      <!-- start row -->
                      <tr>
                        <th>Kategori</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                      </tr>
                      <!-- end row -->
                    </thead>
                    <tbody>
                    
                        @forelse ($kategori as $k)
                        
                        
                      <!-- start row-->
                      <tr>
                        <td>{{ $k->nama_kategori }}</td>
                        <td>@if ($k->deskripsi)
                            {{ $k->deskripsi }}
                        @else
                            -
                        @endif </td>
                        <td><a href="{{ route('kategori.edit', $k->id) }}" class="btn btn-success"><i class="ti ti-pencil"></i></a>
                         <form action="{{ route('kategori.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Apakah kamu yakin ingin menghapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </form></td>
                        
                      </tr>
                      @empty
                      <h4 class="d-flex justify-content-center">Belum ada data kios</h4>
                      <!-- end row -->
                      @endforelse
                    </tbody>
                    
                  </table>
                </div>
              </div>
            </div>
            <!-- end Default Ordering -->
            
            
          </div>

          <!-- solar icons -->
    


@endsection
@push('scripts')
<script>
$(document).ready(function () {
    if (!$.fn.DataTable.isDataTable('#default_order')) {
        $('#default_kategori').DataTable({
            responsive: true
        });
    }
});
</script>
@endpush