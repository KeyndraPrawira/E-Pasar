@extends('layouts.app')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-body">
                  <h4 class="card-title mb-3">Tambah Data Kios</h4>
                  <form action="{{ route('kios.update', $pasar->id) }}" method="POST">
                    @csrf
                   @method('PUT')
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="nama_kios" id="tb-fname" />
                            <input type="hidden" name="pasar_id" value="{{ $pasar->id }}" />
                         <label for="tb-fname" class="text-dark">Nama Kios</label>
                        </div>
                      </div>
                      </div>
                     
                      <div class="row">
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                            <select name="user_id" id="tb-pedagang" class="form-control">
                              <option value="" selected disabled >Pilih pedagang</option>
                              @foreach($pedagang as $p)
 <option value="{{ $p->id }}" {{ $p->id === $selectedPedagangid  ? 'selected' : ''}}>{{ $p->name }}</option>                            </select>
                            <label for="tb-pedagang" class="text-dark">Pedagang</label>
                            </div>
                        </div>
                      </div>

                       <div class="row">
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                            <textarea class="form-control" name="lokasi" id="tb-lokasi"></textarea>
                            <label for="tb-lokasi" class="text-dark">Lokasi</label>
                            </div>
                        </div>
                      </div>
                      
                      <div class="row">
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                            <input type="number" class="form-control" name="kontak" id="tb-nomor_telepon" />
                            <label for="tb-nomor_telepon" class="text-dark">Nomor telepon</label>
                            </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                              <textarea name="deskripsi"  id="tb_deskripsi"></textarea>
                            <label for="tb-deskripsi" class="text-dark">Deskripsi (opsional)</label>
                            </div>
                        </div>
                      </div>

                    <div class="row d-flex justify-content-between">
                        <div class="col text-start">
                        <button type="submit" class="btn btn-success">Edit Kios</button>
                      </div>
                      <div class="col text-end">
                        <a href="{{ route('kios.index') }}" style="background-color: grey;color:white;" class="btn">Batal</a>
                      </div>
                    </div>
                      
                    
                    
                  </form>
                </div>
        </div>
    </div>
@endsection