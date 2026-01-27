

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
                        <h4>Data Kios</h4>
                    </div>
                    <div class="col text-end">
                        <a href="{{ route('kios.create') }}" class="btn btn-primary">Tambah Kios</a>
                    </div>
                </div>
                
                <div class="table-responsive">
                  <table id="default_order" class="table table-striped table-bordered display text-nowrap">
                    <thead>
                      <!-- start row -->
                      <tr>
                        <th>Nama Kios</th>
                        <th>Pedagang</th>
                        <th>Lokasi</th>
                        <th>Kontak</th>
                        <th>Foto Kios</th>
                        <th>Aksi</th>
                      </tr>
                      <!-- end row -->
                    </thead>
                    <tbody>
                    
                        @forelse ($kios as $k)
                        
                        
                      <!-- start row-->
                      <tr>
                        <td>{{ $k->nama_kios }}</td>
                        <td>{{ $k->user->name }}</td>
                        <td>{{ $k->lokasi }}</td>
                        <td>{{ $k->kontak }}</td>
                        <td><img src="{{ asset('storage/'.$k->foto_kios) }}" width="30px" alt="foto-kios"></td>
                        <td><a href="{{ route('kios.edit', $k->id) }}" class="btn btn-success"><i class="ti ti-pencil"></i></a>
                         <form action="{{ route('kios.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Apakah kamu yakin ingin menghapus?')">
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