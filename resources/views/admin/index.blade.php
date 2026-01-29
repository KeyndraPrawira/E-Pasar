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
                        <p class="fw-semibold fs-3 text-primary mb-1">Nama Pasar</p>
                        <h5 class="fw-semibold text-primary mb-0">{{ $pasar->nama_pasar ?? 'Belum Ada Pasar' }}</h5>
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
                        <img src="{{ asset('template/images/svgs/icon-connect.svg')}}" width="50" height="50" class="mb-3" alt="modernize-img" />
                        <p class="fw-semibold fs-3 text-info mb-1">Total Produk</p>
                        <h5 class="fw-semibold text-info mb-0">{{ $totalProduk }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="card border-0 zoom-in bg-success-subtle shadow-none">
                <div class="card-body">
                    <div class="text-center">
                        <img src="{{ asset('template/images/svgs/icon-favorites.svg')}}" width="50" height="50" class="mb-3" alt="modernize-img" />
                        <p class="fw-semibold fs-3 text-success mb-1">Total Kategori</p>
                        <h5 class="fw-semibold text-success mb-0">{{ $totalKategori }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--  Row 1 -->
    <div class="row">
        <!-- Produk per Kategori - Chart Pie -->
        <div class="col-lg-8 d-flex align-items-stretch">
            <div class="card w-100">
               
                <div class="card-body">
                     <div class="card-title">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h5 class="mb-0 fw-semibold">Distribusi Produk per Kategori</h5>
                    </div>
                </div>
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div id="chartProdukKategori"></div>
                        </div>
                        <div class="col-md-4">
                            <div class="hstack mb-4 pb-1">
                                <div class="p-8 bg-primary-subtle rounded-1 me-3 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-package text-primary fs-6"></i>
                                </div>
                                <div>
                                    <h4 class="mb-0 fs-7 fw-semibold">{{ $totalProduk }}</h4>
                                    <p class="fs-3 mb-0">Total Produk</p>
                                </div>
                            </div>
                            <div>
                                <div class="d-flex align-items-baseline mb-4">
                                    <span class="round-8 text-bg-primary rounded-circle me-6"></span>
                                    <div>
                                        <p class="fs-3 mb-1">Jumlah Kategori</p>
                                        <h6 class="fs-5 fw-semibold mb-0">{{ $totalKategori }}</h6>
                                    </div>
                                </div>
                                <div class="d-flex align-items-baseline mb-4 pb-1">
                                    <span class="round-8 text-bg-secondary rounded-circle me-6"></span>
                                    <div>
                                        <p class="fs-3 mb-1">Jumlah Kios</p>
                                        <h6 class="fs-5 fw-semibold mb-0">{{ $totalKios }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Pasar -->
        <div class="col-lg-4 d-flex align-items-stretch flex-column">
            <div class="card w-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <h5 class="card-title mb-9 fw-semibold">Informasi Pasar</h5>
                            @if($pasar)
                                <div class="mb-4">
                                    @if($pasar->foto_pasar)
                                        <img src="{{ asset('storage/' . $pasar->foto_pasar) }}" class="img-fluid rounded-2 mb-3" alt="{{ $pasar->nama_pasar }}" style="width: 100%; height: 200px; object-fit: cover;" />
                                    @else
                                        <div class="bg-primary-subtle rounded-2 mb-3 d-flex align-items-center justify-content-center" style="width: 100%; height: 200px;">
                                            <i class="ti ti-building-community text-primary" style="font-size: 80px;"></i>
                                        </div>
                                    @endif
                                </div>
                                <h4 class="fw-semibold mb-3">{{ $pasar->nama_pasar }}</h4>
                                <div class="d-flex align-items-start mb-3">
                                    <i class="ti ti-map-pin text-primary me-2 fs-5"></i>
                                    <p class="mb-0 fs-3">{{ $pasar->alamat }}</p>
                                </div>
                                @if($pasar->kontak)
                                <div class="d-flex align-items-center mb-3">
                                    <i class="ti ti-phone text-success me-2 fs-5"></i>
                                    <p class="mb-0 fs-3">{{ $pasar->kontak }}</p>
                                </div>
                                @endif
                                @if($pasar->deskripsi)
                                <div class="mt-3">
                                    <p class="fs-3 text-muted">{{ Str::limit($pasar->deskripsi, 150) }}</p>
                                </div>
                                @endif
                            @else
                                <div class="text-center py-5">
                                    <i class="ti ti-building-community text-muted" style="font-size: 60px;"></i>
                                    <p class="text-muted mt-3">Belum ada data pasar</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Produk per Kategori -->
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">Detail Produk per Kategori</h5>
                    <div class="table-responsive">
                        <table class="table align-middle text-nowrap mb-0">
                            <thead>
                                <tr class="text-muted fw-semibold">
                                    <th scope="col" class="ps-0">Kategori</th>
                                    <th scope="col">Jumlah Produk</th>
                                    <th scope="col">Persentase</th>
                                </tr>
                            </thead>
                            <tbody class="border-top">
                                @forelse($produkPerKategori as $item)
                                <tr>
                                    <td class="ps-0">
                                        <div class="d-flex align-items-center">
                                            <div class="me-2 pe-1">
                                                <div class="rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="ti ti-category text-primary"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="fw-semibold mb-0">{{ $item->nama_kategori }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <h6 class="fw-semibold mb-0">{{ $item->total }} Produk</h6>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress w-100 me-3" style="height: 8px;">
                                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $totalProduk > 0 ? ($item->total / $totalProduk * 100) : 0 }}%" aria-valuenow="{{ $item->total }}" aria-valuemin="0" aria-valuemax="{{ $totalProduk }}"></div>
                                            </div>
                                            <span class="fw-semibold">{{ $totalProduk > 0 ? number_format(($item->total / $totalProduk * 100), 1) : 0 }}%</span>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4">
                                        <p class="text-muted mb-0">Belum ada data produk</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Chart Produk per Kategori (Pie Chart)
    var chartProdukKategori = {
        series: @json($chartData),
        chart: {
            type: 'donut',
            height: 350,
            fontFamily: 'inherit',
        },
        labels: @json($chartLabels),
        colors: ['#5D87FF', '#49BEFF', '#13DEB9', '#FFAE1F', '#FA896B'],
        plotOptions: {
            pie: {
                donut: {
                    size: '65%',
                    labels: {
                        show: true,
                        name: {
                            show: true,
                            fontSize: '18px',
                            color: undefined,
                            offsetY: -10
                        },
                        value: {
                            show: true,
                            fontSize: '26px',
                            color: '#5A6A85',
                            offsetY: 10,
                            formatter: function (val) {
                                return val
                            }
                        },
                        total: {
                            show: true,
                            label: 'Total',
                            color: '#5A6A85',
                            formatter: function (w) {
                                return w.globals.seriesTotals.reduce((a, b) => {
                                    return a + b
                                }, 0)
                            }
                        }
                    }
                }
            }
        },
        dataLabels: {
            enabled: false,
        },
        legend: {
            show: true,
            position: 'bottom',
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    height: 300
                }
            }
        }]
    };

    var chartOptions = {
    series: @json($chartData),
    chart: {
        type: 'donut',
        height: 350,
    },
    labels: @json($chartLabels),
    colors: ['#5D87FF', '#49BEFF', '#13DEB9', '#FFAE1F', '#FA896B'],
    legend: {
        position: 'bottom'
    }
};

var chart = new ApexCharts(document.querySelector("#chartProdukKategori"), chartOptions);
chart.render();
</script>
@endpush
@endsection