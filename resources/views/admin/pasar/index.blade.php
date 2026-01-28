@extends('layouts.app')
@section('content')

<style>
        .map-view {
        height: 300px;
        width: 100%;
        border-radius: 10px;
        }
    </style>
    <a href="{{ route('pasar.edit', $pasar->id) }}" class="btn btn-primary p-3 rounded-circle d-flex align-items-center justify-content-center customizer-btn"  aria-controls="offcanvasExample">
        <i class="icon ti ti-pencil fs-7"></i>
      </a>

 <div class="card">
                <div class="card-body">
                    @if ($pasar !== null)
                    
                   
                  <h4 class="card-title mb-3">{{ $pasar->nama_pasar }}
                  
                  </h4>
                  <form action="{{ route('pasar.show', $pasar->id) }}" method="POST">
                    @csrf
                   
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-floating mb-3">
                          <input type="text" readonly class="form-control" value="{{ $pasar->nama_pasar }}" name="nama_pasar" id="tb-fname" placeholder="Masukkan nama pasar" />
                          <label for="tb-fname">Nama Pasar</label>
                        </div>
                      </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                            <div class="form-floating mb-3">
                            <textarea name="alamat" readonly class="form-control" id="tb-alamat">{{ $pasar->alamat }}</textarea>
                            <label for="tb-alamat">Alamat</label>
                            </div>
                        </div>
                      </div>
                      <div class="row">
                        <div id="map" class="map-view mb-3"></div>
                      </div>

                      
                      <div class="row">

                      
                     


                    
                    
                  </form>
                </div>
                @else
                <div class="card-body">
                  <h4 class="card-title mb-3">Data Pasar Belum Tersedia</h4>
                    <a href="{{ route('pasar.create') }}" class="btn btn- d-flex justify-content-between text-center text-primary">Buat Data Pasar</a>
                </div>
                @endif
              </div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    


    // default lokasi (contoh Bandung)
    let defaultLat = @json($pasar->latitude ?? -6.914744);
    let defaultLng = @json($pasar->longitude ?? 107.609810);

   let map = L.map('map', {
    center: [defaultLat, defaultLng],
    zoom: 15,
    dragging: true,
    scrollWheelZoom: true,
    doubleClickZoom: true,
    boxZoom: false,
    keyboard: false,
    zoomControl: true,
    touchZoom: true
});

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    let marker = L.marker([defaultLat, defaultLng], {
        draggable: false
    }).addTo(map);

    // set input awal
    document.getElementById('lat').value = defaultLat;
    document.getElementById('lng').value = defaultLng;

    // saat marker digeser
   

    // saat map diklik
    
</script>

@endsection
