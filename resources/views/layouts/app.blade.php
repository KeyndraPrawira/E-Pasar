<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="vertical">

<head>
  <!-- Required meta tags -->
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- Favicon icon-->
  <link rel="shortcut icon" type="image/png" href="{{ asset('template/images/logos/favicon.png') }}" />

  <!-- Core Css -->
  <link rel="stylesheet" href="{{ asset('template/css/styles.css')}}" />
  <link rel="stylesheet" href="{{ asset('template/libs/owl.carousel/dist/assets/owl.carousel.min.css')}}" />
  <link
      rel="stylesheet"
      href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont/tabler-icons.min.css">

    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.7/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="{{ asset('template/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css')}}" />
    <!-- Tambahkan SEBELUM tag </body> -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

  <title>Admin Pajajap</title>
</head>
<body>
  <div class="preloader">
    <img src="{{ asset('template/images/logos/Pajajap logo.png')}}" alt="loader" class="lds-ripple img-fluid" />
  </div>
  <div id="main-wrapper">
         @include('layouts.admin.sidebar')
         
<div class="page-wrapper">
  
        @include('layouts.admin.navbar')
       

        
          <div class="body-wraper" >
            <br><br>
            <div class="container-fluid mt-5 " style="margin-top: 300px;">
              @yield('content')
          </div>
         
          
            
        </main>
    </div>
  </div>

@include('sweetalert::alert')

<div class="dark-transparent sidebartoggler"></div>
</div>
</body>
  <script src="{{ asset('template/js/vendor.min.js')}}"></script>
  <!-- Import Js Files -->
 
 

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('template/libs/owl.carousel/dist/owl.carousel.min.js') }}"></script>
<script>
$(document).ready(function(){
  $('.counter-carousel').owlCarousel({
    loop:false,
    margin:10,
    nav:false,
    dots:false,
    responsive:{
      0:{ items:1 },
      768:{ items:3 },
      1200:{ items:5 }
    }
  });
});
</script>

 <script src="{{ asset('template/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('template/libs/simplebar/dist/simplebar.min.js') }}"></script>
  <script src="{{ asset('template/js/theme/app.init.js') }}"></script>
  <script src="{{ asset('template/js/theme/theme.js') }}"></script>
  <script src="{{ asset('template/js/theme/app.min.js') }}"></script>
    <script src="{{ asset('template/js/theme/sidebarmenu.js')}}"></script>
  <script src="https://cdn.datatables.net/responsive/3.0.7/js/dataTables.responsive.min.js"></script>

  <!-- solar icons -->
 <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
  <script src="{{ asset ('template/libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
  <script src="{{ asset ('template/js/datatable/datatable-basic.init.js')}}"></script></body>
@stack('scripts')
 
</html>
