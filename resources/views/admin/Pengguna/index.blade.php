

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
                        <h4>Data Pengguna</h4>
                    </div>
                    <div class="col text-end">
                        <a href="{{ route('pengguna.create') }}" class="btn btn-primary">Tambah Pengguna</a>
                    </div>
                </div>
                
                <div class="table-responsive">
                  <table id="default_order" class="table table-striped table-bordered display text-nowrap">
                    <thead>
                      <!-- start row -->
                      <tr>
                        <th>Nama</th>
                        <th>Role</th>
                        <th>Email</th>
                        <th>Nomor Telepon</th>
                        <th>Aksi</th>
                      </tr>
                      <!-- end row -->
                    </thead>
                    <tbody>
                        @foreach ($user as $pengguna)
                        
                        
                      <!-- start row -->
                      <tr>
                        <td>{{ $pengguna->name }}</td>
                        <td>{{ $pengguna->role }}</td>
                        <td>{{ $pengguna->email }}</td>
                        <td>{{ $pengguna->nomor_telepon }}</td>
                        <td><a href="{{ route('pengguna.edit', $pengguna->id) }}" class="btn btn-success"><i class="ti ti-pencil"></i></a>
                         <form action="{{ route('pengguna.destroy', $pengguna->id) }}" method="POST" onsubmit="return confirm('Apakah kamu yakin ingin menghapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </form></td>
                        
                      </tr>
                      <!-- end row -->
                      @endforeach
                    </tbody>
                    
                  </table>
                </div>
              </div>
            </div>
            <!-- end Default Ordering -->
            
            
          </div>

          <!-- solar icons -->
    

@endsection