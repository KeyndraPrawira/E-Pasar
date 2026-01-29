@extends('layouts.app')
@section('content')

<style>
    .map-view {
        height: 400px;
        width: 100%;
        border-radius: 10px;
        overflow: hidden;
    }
    .pasar-image {
        width: 100%;
        height: 300px;
        object-fit: cover;
        border-radius: 10px;
    }
    .info-item {
        padding: 15px;
        background: #f6f9fc;
        border-radius: 8px;
        margin-bottom: 15px;
        border-left: 4px solid #5d87ff;
    }
    .info-label {
        font-size: 12px;
        color: #5a6a85;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 5px;
    }
    .info-value {
        font-size: 15px;
        color: #2a3547;
        font-weight: 500;
    }
    .edit-btn-float {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 999;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
</style>

<!-- Floating Edit Button -->
@if ($pasar !== null)
<a href="{{ route('pasar.edit', $pasar->id) }}" 
   class="btn btn-primary edit-btn-float p-3 rounded-circle d-flex align-items-center justify-content-center">
    <i class="ti ti-pencil fs-7"></i>
</a>
@endif

<!-- Page Header -->
<div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
    <div class="card-body px-4 py-3">
        <div class="row align-items-center">
            <div class="col-9">
                <h4 class="fw-semibold mb-2">Informasi Pasar</h4>
                
            </div>
            
        </div>
    </div>
</div>

@if ($pasar !== null)
    <!-- Main Content -->
    <div class="row">
        <!-- Left Column - Image -->
        <div class="col-lg-5 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-3">
                        <i class="ti ti-photo me-2"></i>Foto Pasar
                    </h5>
                    <img src="{{ asset('storage/'. $pasar->foto_pasar) }}" 
                         class="pasar-image shadow-sm" 
                         alt="Foto Pasar">
                </div>
            </div>
        </div>

        <!-- Right Column - Information -->
        <div class="col-lg-7 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">
                        <i class="ti ti-info-circle me-2"></i>Detail Informasi
                    </h5>

                    <!-- Nama Pasar -->
                    @if(isset($pasar->nama_pasar))
                    <div class="info-item">
                        <div class="info-label">
                            <i class="ti ti-building me-1"></i>Nama Pasar
                        </div>
                        <div class="info-value">{{ $pasar->nama_pasar }}</div>
                    </div>
                    @endif

                    <!-- Alamat -->
                    <div class="info-item">
                        <div class="info-label">
                            <i class="ti ti-map-pin me-1"></i>Alamat
                        </div>
                        <div class="info-value">{{ $pasar->alamat }}</div>
                    </div>

                    <!-- Koordinat -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="ti ti-compass me-1"></i>Ongkir
                                </div>
                                <div class="info-value">{{ $pasar->ongkir ?? '-' }}/meter</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="ti ti-compass me-1"></i>Kontak
                                </div>
                                <div class="info-value"> {{ $pasar->kontak ?? '-' }} </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Info if exists -->
                    @if(isset($pasar->deskripsi))
                    <div class="info-item">
                        <div class="info-label">
                            <i class="ti ti-file-text me-1"></i>Deskripsi
                        </div>
                        <div class="info-value">{{ $pasar->deskripsi }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Map Section -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-3">
                        <i class="ti ti-map me-2"></i>Lokasi Pasar
                    </h5>
                    <div id="map" class="map-view"></div>
                </div>
            </div>
        </div>
    </div>

@else
    <!-- Empty State -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="ti ti-building-store fs-10 text-muted"></i>
                    </div>
                    <h4 class="card-title mb-3">Data Pasar Belum Tersedia</h4>
                    <p class="text-muted mb-4">Silakan tambahkan data pasar terlebih dahulu</p>
                    <a href="{{ route('pasar.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus me-2"></i>Buat Data Pasar
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    @if ($pasar !== null && isset($pasar->latitude) && isset($pasar->longitude))
    // Lokasi pasar
    let defaultLat = {{ $pasar->latitude }};
    let defaultLng = {{ $pasar->longitude }};

    // Initialize map
    let map = L.map('map', {
        center: [defaultLat, defaultLng],
        zoom: 15,
        dragging: true,
        scrollWheelZoom: true,
        doubleClickZoom: true,
        zoomControl: true,
        touchZoom: true
    });

    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Add marker
    let marker = L.marker([defaultLat, defaultLng], {
        draggable: false
    }).addTo(map);

    // Add popup to marker
    marker.bindPopup("<b>{{ $pasar->nama_pasar ?? 'Pasar' }}</b><br>{{ $pasar->alamat }}").openPopup();
    @endif
</script>
@endpush