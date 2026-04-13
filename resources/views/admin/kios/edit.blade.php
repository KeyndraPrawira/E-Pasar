@extends('layouts.app')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="card-body">
                  <h4 class="card-title mb-3">Edit Data Kios</h4>
                   @if ($errors->any())
    <div style="background:#fee; padding:10px;" class="mb-3">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
                  <form action="{{ route('kios.update', $kios->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                   @method('PUT')
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-floating mb-3">
                            <input type="text" value="{{ $kios->nama_kios }}" class="form-control" name="nama_kios" id="tb-fname" />
                            <input type="hidden" name="pasar_id" value="{{ $pasar->id }}" />
                         <label for="tb-fname" class="text-dark">Nama Kios</label>
                        </div>
                      </div>
                      </div>
                     
                      <div class="row">
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                            <select name="user_id" id="tb-pedagang" class="form-control @error('user_id') is-invalid @enderror">
                              <option value="">Pilih pedagang jika sudah ada</option>
                              @foreach($pedagang as $p)
 <option value="{{ $p->id }}" {{ (string) old('user_id', $selectedPedagangid) === (string) $p->id ? 'selected' : ''}}>{{ $p->name }}</option> 
                              @endforeach 
                           </select>
                            <label for="tb-pedagang" class="text-dark">Pedagang (opsional)</label>
                            @error('user_id')
                              <small style="color:red">{{ $message }}</small>
                            @enderror
                            </div>
                        </div>
                      </div>

                       <div class="row">
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                            <textarea class="form-control" name="lokasi" id="tb-lokasi">{{ $kios->lokasi }}</textarea>
                            <label for="tb-lokasi" class="text-dark">Lokasi</label>
                            </div>
                        </div>
                      </div>
                      
                       <div class="row">
                            <div class="col-md-6">
                        <div class="form-floating mb-3">
                          <input   name="jam_buka" type="time" value="{{ $kios->jam_buka }}" class="form-control {{ $errors->has('jam_buka') ? 'is-invalid' : '' }}" id="tb-pwd" placeholder="jam_buka tidak terisi" />
                          @error('jam_buka')
                            <small style="color:red">{{ $message }}</small>
                            @enderror
                          <label  class="text-dark" for="tb-pwd">jam_buka</label>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-floating mb-3">
                          <input  name="jam_tutup" type="time" value="{{ $kios->jam_tutup }}" class="form-control {{ $errors->has('jam_tutup') ? 'is-invalid' : '' }}" id="tb-cpwd" placeholder="jam_tutup tidak terisi" />
                          @error('jam_tutup')
                            <small style="color:red">{{ $message }}</small>
                            @enderror
                          <label  class="text-dark" for="tb-cpwd">Jam tutup</label>
                        </div>
                      </div>
                      </div>

                      <div class="row">
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                              <textarea name="deskripsi" class="form-control"  id="tb_deskripsi">{{ $kios->deskripsi }}</textarea>
                            <label for="tb-deskripsi" class="text-dark">Deskripsi (opsional)</label>
                            </div>
                        </div>
                      </div>
                      @if ($kios->foto_kios)
                        <div class="row">
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                              <img src="{{ asset('storage'.$kios->foto_kios) }}" width="500px" alt="foto kios">
                            <label for="tb-gambar" class="text-dark">Foto Kios</label>
                            </div>
                        </div>
                      @else
                        <div class="row">
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                              <h6 class="d-flex justify-content-center">Belum ada foto kios</h6>
                           
                            </div>
                        </div>
                      @endif
                       

                       <div class="row">
                        <div class="col-md-12">
                            <label for="tb-gambar" class="text-dark">Foto Kios</label>
                            <input name="foto_kios" type="file" class="form-control" id="tb-gambar"  />
                        </div>

                    <div class="row d-flex justify-content-between mt-3">
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
