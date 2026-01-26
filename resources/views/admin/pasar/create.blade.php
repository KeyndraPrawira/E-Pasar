@extends('layouts.app')
@section('content')


 <div class="card">
    @if ($pasar == null)
    
 
                <div class="card-body">
                  <h4 class="card-title mb-3">Data Pasar</h4>
                  <form action="{{ route('pasar.store') }}" method="POST">
                    @csrf
                   
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-floating mb-3">
                          <input type="text" class="form-control" name="nama_pasar" id="tb-fname" placeholder="Masukkan nama pasar" />
                          <label for="tb-fname">Nama Pasar</label>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                            <textarea name="alamat" id="tb-alamat"></textarea>
                            <label for="tb-alamat">Alamat</label>
                            </div>
                        </div>
                      </div>
                      <div class="row">
                        <div id="map"></div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-floating mb-3">
                          <input readonly id="lng" type="text"  class="form-control" id="tb-pwd" placeholder="Longitude tidak terisi" />
                          <label for="tb-pwd">Longitude</label>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-floating mb-3">
                          <input readonly id="lat" type="text" class="form-control" id="tb-cpwd" placeholder="Password" />
                          <label for="tb-cpwd">Latitude</label>
                        </div>
                      </div>
                    
                    </div>
                  </form>
                </div>
    @else
    <div class="card-body">
        <h4 class="card-title mb-3">Data Pasar Sudah Tersedia</h4>
        <a href="{{ route('pasar.index') }}" class="btn btn- d-flex justify-content-between text-center text-primary">Kembali</a>
    </div>
              </div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // default lokasi (contoh Bandung)
    let defaultLat = -6.914744;
    let defaultLng = 107.609810;

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
