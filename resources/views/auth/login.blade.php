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

    <style>
        #map {
            height: 300px;
            margin-bottom: 10px;
        }
    </style>

  <title>Login Admin</title>
</head>
<body>
     <div class="preloader">
    <img src="{{asset ('template/images/logos/Pajajap logo.png')}}" alt="loader" class="lds-ripple img-fluid" />
  </div>
     <div id="main-wrapper">



 <div id="main-wrapper" class="auth-customizer-none">
    <div class="position-relative overflow-hidden radial-gradient min-vh-100 w-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3 auth-card">
            <div class="card mb-0">
              <div class="card-body">
                <a href="{{route('login')}}" class="text-nowrap logo-img text-center d-block  w-100">
                  <img src="{{ asset('template/images/logos/Pajajap logo.png') }}" width="250px" class="dark-logo" alt="Logo-Dark" />
                  <img src="{{ asset('template/images/logos/Pajajap logo.png') }}" width="250px" class="light-logo" alt="Logo-light" />
                </a>
               
               
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                  <div class="mb-3">
                    <label for="exampleInputEmail1"  class="form-label">{{ __('Email') }}</label>
                    <input type="email" class="form-control" name="email" id="exampleInputEmail1" aria-describedby="emailHelp" @error('email') is-invalid
                    @enderror>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                  </div>
                  <div class="mb-4">
                    <label for="exampleInputPassword1" class="form-label">{{ __('Password') }}</label>
                    <input type="password" name="password" class="form-control" id="exampleInputPassword1" @error('password') is-invalid @enderror>
                                
                  
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                  </div>
                 
                  <button type="submit" class="btn btn-primary w-100 py-8 mb-4 rounded-2"> Masuk</button>
                 
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script>
  function handleColorTheme(e) {
    document.documentElement.setAttribute("data-color-theme", e);
  }
</script>
    <button class="btn btn-primary p-3 rounded-circle d-flex align-items-center justify-content-center customizer-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
      <i class="icon ti ti-settings fs-7"></i>
    </button>

    <div class="offcanvas customizer offcanvas-end" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
      <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
        <h4 class="offcanvas-title fw-semibold" id="offcanvasExampleLabel">
          Settings
        </h4>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      
    </div>
  </div>

  <div class="dark-transparent sidebartoggler"></div>
  <script src="{{ asset('template/js/vendor.min.js')}}"></script>
  <!-- Import Js Files -->
 
  <script src="{{ asset('template/js/theme/sidebarmenu.js')}}"></script>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

 <script src="{{ asset('template/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('template/libs/simplebar/dist/simplebar.min.js') }}"></script>
  <script src="{{ asset('template/js/theme/app.init.js') }}"></script>
  <script src="{{ asset('template/js/theme/theme.js') }}"></script>
  <script src="{{ asset('template/js/theme/app.min.js') }}"></script>

  <!-- solar icons -->
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js }}"></script>
</body>
</html>



