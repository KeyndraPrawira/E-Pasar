
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
                        <h4>Data Kategori Laporan</h4>
                    </div>
                    <div class="col text-end">

                        <a href="{{ route('kategori-laporan.create') }}" class="btn btn-primary"><h5 class="text-white">Tambah Kategori Laporan</h5></a>
                    </div>
                </div>
                
                <div class="table-responsive">
                  <table id="default_kategori_laporan" class="table table-striped table-bordered display text-nowrap">
                    <thead>
                      <!-- start row -->
                      <tr>
                        <th>Nama</th>
                        <th>Tipe</th>
                        <th>Status</th>
                        <th>Aksi</th>
                      </tr>
                      <!-- end row -->
                    </thead>
                    <tbody>
                    
                        @forelse ($kategoriLaporans as $k)
                        
                        
                      <!-- start row-->
                      <tr>
                        <td>{{ $k->nama }}</td>
                        <td>
                          @php
                            switch ($k->reportable_type) {
                                case 'App\\Models\\Produk':
                                    $types = 'Produk';
                                    break;
                                case 'App\\Models\\Kios':
                                    $types = 'Kios';
                                    break;
                                case 'App\\Models\\Driver':
                                    $types = 'Driver';
                                    break;
                                default:
                                    $types = $k->reportable_type;
                            }
                          @endphp
                          {{ $types}}
                        </td>
                        <td>
                          @if($k->is_active)
                            <span class="badge bg-success">Aktif</span>
                          @else
                            <span class="badge bg-danger">Nonaktif</span>
                          @endif
                        </td>
                        <td>
                          <a href="{{ route('kategori-laporan.edit', $k->id) }}" class="btn btn-success"><i class="ti ti-pencil"></i></a>
                          <a href="{{ route('kategori-laporan.destroy', $k->id) }}" class="btn btn-danger" data-confirm-delete="true"><i class="ti ti-trash"></i></a>   
                        </td>
                        
                      </tr>
                      @empty
                      <tr>
                        <td colspan="4" class="text-center">*belum ada data kategori laporan</td>
                      </tr>
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
    if (!$.fn.DataTable.isDataTable('#default_kategori_laporan')) {
        $('#default_kategori_laporan').DataTable({
            responsive: true
        });
    }
});
</script>
@endpush

