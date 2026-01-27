@extends('layouts.app')
@section('content')

<style>
       .map-view {
        height: 300px;
        width: 100%;
        border-radius: 10px;
        }


        .is-invalid {
            border: 1px solid red;
            }
    </style>
 <div class="card">
                <div class="card-body">
                  <h4 class="card-title mb-3">Data Pasar</h4>
                  <form action="{{ route('pasar.update', parameters: $pasar->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @if ($errors->any())
    <div style="background:#fee; padding:10px;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

                      <div class="row">
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                            <input type="text"  class="form-control {{ $errors->has('nama_pasar') ? 'is-invalid' : '' }}" value="{{ $pasar->nama_pasar }}" name="nama_pasar" id="tb-fname" placeholder="Masukkan nama pasar" />
                            @error('nama_pasar')
                            <small style="color:red">{{ $message }}</small>
                            @enderror
                            <label  class="text-dark" for="tb-fname">Nama Pasar</label>
                            </div>
                        </div>
                      </div>
                      
                      <div class="row">
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                            <input type="number" name="kontak" class="form-control {{ $errors->has('kontak') ? 'is-invalid' : '' }}" value="{{ $pasar->kontak }}"  id="tb-kontak" placeholder="Masukkan nomor telepon" />
                            @error('kontak')
                            <small style="color:red">{{ $message }}</small>
                            @enderror
                            <label  class="text-dark" for="tb-kontak">Kontak</label>
                            </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                            <textarea name="alamat" class="form-control {{ $errors->has('alamat') ? 'is-invalid' : '' }}" id="tb-alamat">{{ $pasar->alamat }}</textarea>
                            @error('alamat')
                            <small style="color:red">{{ $message }}</small>
                            @enderror
                            <label  class="text-dark" for="tb-alamat">Alamat</label>
                            </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                        <div class="form-floating mb-3">
                         <div id="map" class="map-view mb-3"></div>
                         <label> lokasi</label>
                        </div>
                         </div>
                       
                      </div>
                      <div class="row">
                            <div class="col-md-6">
                        <div class="form-floating mb-3">
                          <input readonly id="lng" name="longitude" type="text" value="{{ $pasar->longitude }}" class="form-control {{ $errors->has('longitude') ? 'is-invalid' : '' }}" id="tb-pwd" placeholder="Longitude tidak terisi" />
                          @error('longitude')
                            <small style="color:red">{{ $message }}</small>
                            @enderror
                          <label  class="text-dark" for="tb-pwd">Longitude</label>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-floating mb-3">
                          <input readonly id="lat" name="latitude" type="text" value="{{ $pasar->latitude }}" class="form-control {{ $errors->has('latitude') ? 'is-invalid' : '' }}" id="tb-cpwd" placeholder="Latitude tidak terisi" />
                          @error('latitude')
                            <small style="color:red">{{ $message }}</small>
                            @enderror
                          <label  class="text-dark" for="tb-cpwd">Latitude</label>
                        </div>
                      </div>
                      </div>
                      
                    
                   
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                            <input name="ongkir" type="number" class="form-control {{ $errors->has('ongkir') ? 'is-invalid' : '' }}" id="tb-ongkir" value="{{ $pasar->ongkir }}" />
                            <label for="tb-ongkir" class="text-dark">Ongkir (Per-meter)</label>
                            </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                            <textarea name="deskripsi" class="form-control {{ $errors->has('nama_pasar') ? 'is-invalid' : '' }}" id="tb-deskripsi" >{{ $pasar->deskripsi }}</textarea>
                            <label for="tb-deskripsi" class="text-dark">Deskripsi</label>
                            </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                              @if ($pasar->foto_pasar !== null)
                              <img src="{{ asset('storage/' . $pasar->foto_pasar) }}" width="100%" alt="foto pasar">
                              @else
                              <div class="card">
                                <div class="card-body">
                                  <p class="text-dark d-flex justify-content-center">Tidak ada foto pasar</p>
                              </div>
                              @endif
                            
                            </div>
                        </div>
                      </div>
                     <div class="row">
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                            <input name="foto_pasar" type="file" class="form-control" id="tb-gambar"  />
                            <label for="tb-gambar" class="text-dark">Foto Pasar</label>
                            </div>
                        </div>
                      </div>
                      <div class="row d-flex justify-content-between">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                            <button type="submit" class="btn btn-primary w-100 py-8 mb-4 rounded-2"> Simpan Perubahan</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                              <a href="{{ route('pasar.index') }}" class="btn w-100 py-8 mb-4 rounded-2" style="background-color: grey;color:white;">Batal</a>
                            </div>
                        </div>
                      </div>
                      
                  </form>
                </div>
              </div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // default lokasi (contoh Bandung)

    let defaultLat = @json($pasar->latitude ?? -6.914744);
    let defaultLng = @json($pasar->longitude ?? 107.609810)  ;

    let map = L.map('map').setView([defaultLat, defaultLng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    let marker = L.marker([defaultLat, defaultLng], {
        draggable: true
    }).addTo(map);

    // set input awal
    document.getElementById('lat').value = defaultLat;
    document.getElementById('lng').value = defaultLng;

    // saat marker digeser
    marker.on('dragend', function (e) {
        let pos = marker.getLatLng();
        document.getElementById('lat').value = pos.lat;
        document.getElementById('lng').value = pos.lng;
    });

    // saat map diklik
    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        document.getElementById('lat').value = e.latlng.lat;
        document.getElementById('lng').value = e.latlng.lng;
    });
</script>

@endsection
