@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <!--  Owl carousel - Statistik Utama -->
    <div class="owl-carousel counter-carousel owl-theme">
        
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
                        <p class="fw-semibold fs-3 text-info mb-1">Total User</p>
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
                        <p class="fw-semibold fs-3 text-success mb-1">Pedagang</p>
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
                        <p class="fw-semibold fs-3 text-danger mb-1">Pengguna</p>
                        <h5 class="fw-semibold text-danger mb-0">{{ $totalPelanggan }}</h5>
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

       

       

        
        

       
    </div>
</div>
@endsection