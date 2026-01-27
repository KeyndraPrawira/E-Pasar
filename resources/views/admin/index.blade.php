@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <!--  Owl carousel - Statistik Utama -->
    <div class="owl-carousel counter-carousel owl-theme">
        <div class="item">
            <div class="card border-0 zoom-in bg-primary-subtle shadow-none">
                <div class="card-body">
                    <div class="text-center">
                        <img src="{{ asset('template/images/svgs/icon-briefcase.svg')}}" width="50" height="50" class="mb-3" alt="modernize-img" />
                        <p class="fw-semibold fs-3 text-primary mb-1">Total Pasar</p>
                        <h5 class="fw-semibold text-primary mb-0">{{ $totalPasar }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0 zoom-in bg-warning-subtle shadow-none">
                <div class="card-body">
                    <div class="text-center">
                        <img src="{{ asset('template/images/svgs/icon-mailbox.svg')}}" width="50" height="50" class="mb-3" alt="modernize-img" />
                        <p class="fw-semibold fs-3 text-warning mb-1">Total Kios</p>
                        <h5 class="fw-semibold text-warning mb-0">{{ $totalKios }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0 zoom-in bg-info-subtle shadow-none">
                <div class="card-body">
                    <div class="text-center">
                        <img src="{{ asset('template/images/svgs/icon-user-male.svg')}}" width="50" height="50" class="mb-3" alt="modernize-img" />
                        <p class="fw-semibold fs-3 text-info mb-1">Total Pengguna</p>
                        <h5 class="fw-semibold text-info mb-0">{{ $totalUser }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0 zoom-in bg-success-subtle shadow-none">
                <div class="card-body">
                    <div class="text-center">
                        <img src="{{ asset('template/images/svgs/icon-connect.svg')}}" width="50" height="50" class="mb-3" alt="modernize-img" />
                        <p class="fw-semibold fs-3 text-success mb-1">Pemilik Kios</p>
                        <h5 class="fw-semibold text-success mb-0">{{ $totalPemilikKios }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0 zoom-in bg-danger-subtle shadow-none">
                <div class="card-body">
                    <div class="text-center">
                        <img src="{{ asset('template/images/svgs/icon-favorites.svg')}}" width="50" height="50" class="mb-3" alt="modernize-img" />
                        <p class="fw-semibold fs-3 text-danger mb-1">Pelanggan</p>
                        <h5 class="fw-semibold text-danger mb-0">{{ $totalPelanggan }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0 zoom-in bg-secondary-subtle shadow-none">
                <div class="card-body">
                    <div class="text-center">
                        <img src="{{ asset('template/images/svgs/icon-speech-bubble.svg')}}" width="50" height="50" class="mb-3" alt="modernize-img" />
                        <p class="fw-semibold fs-3 text-secondary mb-1">Administrator</p>
                        <h5 class="fw-semibold text-secondary mb-0">{{ $totalAdmin }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--  Row 1 -->
    <div class="row">
        <!-- Grafik Pertumbuhan Kios -->
        <div class="col-lg-8 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body">
                    <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                        <div class="mb-3 mb-sm-0">
                            <h5 class="card-title fw-semibold">Pertumbuhan Kios</h5>
                            <p class="card-subtitle mb-0">Overview Tahun {{ date('Y') }}</p>
                        </div>
                        <select class="form-select w-auto">
                            <option value="1">Januari {{ date('Y') }}</option>
                            <option value="2">Februari {{ date('Y') }}</option>
                            <option value="3">Maret {{ date('Y') }}</option>
                            <option value="4">April {{ date('Y') }}</option>
                        </select>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div id="chart" class="mx-n6"></div>
                        </div>
                        <div class="col-md-4">
                            <div class="hstack mb-4 pb-1">
                                <div class="p-8 bg-primary-subtle rounded-1 me-3 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-building-store text-primary fs-6"></i>
                                </div>
                                <div>
                                    <h4 class="mb-0 fs-7 fw-semibold">{{ $totalKios }}</h4>
                                    <p class="fs-3 mb-0">Total Kios Terdaftar</p>
                                </div>
                            </div>
                            <div>
                                <div class="d-flex align-items-baseline mb-4">
                                    <span class="round-8 text-bg-primary rounded-circle me-6"></span>
                                    <div>
                                        <p class="fs-3 mb-1">Kios Aktif</p>
                                        <h6 class="fs-5 fw-semibold mb-0">{{ $totalKios }}</h6>
                                    </div>
                                </div>
                                <div class="d-flex align-items-baseline mb-4 pb-1">
                                    <span class="round-8 text-bg-secondary rounded-circle me-6"></span>
                                    <div>
                                        <p class="fs-3 mb-1">Pasar Terdaftar</p>
                                        <h6 class="fs-5 fw-semibold mb-0">{{ $totalPasar }}</h6>
                                    </div>
                                </div>
                                <div>
                                    <a href="{{ route('kios.index') }}" class="btn btn-primary w-100">
                                        Lihat Semua Kios
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 d-flex align-items-stretch flex-column">
            <!-- Total Pasar -->
            <div class="card w-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h5 class="card-title mb-9 fw-semibold">Total Pasar</h5>
                            <h4 class="fw-semibold mb-3">{{ $totalPasar }}</h4>
                            <div class="d-flex align-items-center mb-3">
                                <span class="me-1 rounded-circle bg-success-subtle round-20 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-arrow-up-left text-success"></i>
                                </span>
                                <p class="text-dark me-1 fs-3 mb-0">Aktif</p>
                                <p class="fs-3 mb-0">tahun ini</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="d-flex justify-content-center">
                                <div class="text-white text-bg-primary rounded-circle p-6 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                    <i class="ti ti-building-community fs-6"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Pengguna -->
            <div class="card w-100">
                <div class="card-body">
                    <div class="row align-items-start">
                        <div class="col-8">
                            <h5 class="card-title mb-9 fw-semibold">Total Pengguna</h5>
                            <h4 class="fw-semibold mb-3">{{ $totalUser }}</h4>
                            <div class="d-flex align-items-center pb-1">
                                <span class="me-2 rounded-circle bg-info-subtle round-20 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-users text-info"></i>
                                </span>
                                <p class="text-dark me-1 fs-3 mb-0">Terdaftar</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="d-flex justify-content-end">
                                <div class="text-white text-bg-info rounded-circle p-6 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-user fs-6"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Distribusi User -->
        <div class="col-lg-4 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body">
                    <div>
                        <h5 class="card-title fw-semibold mb-1">Distribusi Pengguna</h5>
                        <p class="card-subtitle mb-0">Berdasarkan Role</p>
                        <div id="userDistribution" class="mb-7 pb-8 mx-n4"></div>
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary-subtle rounded me-8 p-8 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-user-check text-primary fs-6"></i>
                                </div>
                                <div>
                                    <p class="fs-3 mb-0 fw-normal">Pemilik Kios</p>
                                    <h6 class="fw-semibold text-dark fs-4 mb-0">{{ $totalPemilikKios }}</h6>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="text-bg-light rounded me-8 p-8 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-users text-muted fs-6"></i>
                                </div>
                                <div>
                                    <p class="fs-3 mb-0 fw-normal">Pelanggan</p>
                                    <h6 class="fw-semibold text-dark fs-4 mb-0">{{ $totalPelanggan }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik Kios & Pasar -->
        <div class="col-lg-4 d-flex align-items-stretch flex-column">
            <div class="row">
                <!-- Total Kios -->
                <div class="col-sm-6 d-flex align-items-stretch">
                    <div class="card w-100">
                        <div class="card-body pb-0 mb-xxl-4 pb-1">
                            <p class="mb-1 fs-3">Total Kios</p>
                            <h4 class="fw-semibold fs-7">{{ $totalKios }}</h4>
                            <div class="d-flex align-items-center mb-3">
                                <span class="me-2 rounded-circle bg-success-subtle round-20 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-arrow-up-left text-success"></i>
                                </span>
                                <p class="text-dark fs-3 mb-0">Aktif</p>
                            </div>
                        </div>
                        <div id="kiosChart"></div>
                    </div>
                </div>

                <!-- Rata-rata Kios per Pasar -->
                <div class="col-sm-6 d-flex align-items-stretch">
                    <div class="card w-100">
                        <div class="card-body">
                            <p class="mb-1 fs-3">Rata-rata Kios</p>
                            <h4 class="fw-semibold fs-7">{{ $totalPasar > 0 ? round($totalKios / $totalPasar, 1) : 0 }}</h4>
                            <div class="d-flex align-items-center mb-3">
                                <span class="me-1 rounded-circle bg-info-subtle round-20 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-chart-line text-info"></i>
                                </span>
                                <p class="text-dark fs-3 mb-0">Per Pasar</p>
                            </div>
                            <div id="avgKiosChart"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kios Terbaru -->
            <div class="card">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">Kios Terbaru</h5>
                    @if($kiosTerbaru->count() > 0)
                        @foreach($kiosTerbaru->take(1) as $kios)
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3 pe-1">
                                @if($kios->foto_kios)
                                    <img src="{{ asset('storage/' . $kios->foto_kios) }}" class="shadow-warning rounded-2" alt="{{ $kios->nama_kios }}" width="72" height="72" style="object-fit: cover;" />
                                @else
                                    <div class="shadow-warning rounded-2 bg-primary-subtle d-flex align-items-center justify-content-center" style="width: 72px; height: 72px;">
                                        <i class="ti ti-building-store text-primary fs-3"></i>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h6 class="fw-semibold fs-4 mb-1">{{ $kios->nama_kios }}</h6>
                                <p class="fs-3 mb-0">{{ $kios->pasar->nama_pasar ?? 'N/A' }}</p>
                                <p class="fs-2 mb-0 text-muted">{{ $kios->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted">Belum ada kios terdaftar</p>
                    @endif
                </div>
            </div>
        </div>

        
        

       
    </div>
</div>
@endsection