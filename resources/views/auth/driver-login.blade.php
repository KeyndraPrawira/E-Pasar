<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="vertical">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" type="image/png" href="{{ asset('template/images/logos/favicon.png') }}" />
  <link rel="stylesheet" href="{{ asset('template/css/styles.css')}}" />
  <link rel="stylesheet" href="{{ asset('template/libs/owl.carousel/dist/assets/owl.carousel.min.css')}}" />
  <title>Login Driver Pajajap</title>
</head>
<body>
  <div class="preloader">
    <img src="{{ asset('template/images/logos/Pajajap logo.png') }}" alt="loader" class="lds-ripple img-fluid" />
  </div>

  <div id="main-wrapper" class="auth-customizer-none">
    <div class="position-relative overflow-hidden radial-gradient min-vh-100 w-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-4 auth-card">
            <div class="card mb-0">
              <div class="card-body">
                <a href="{{ route('landingpage') }}" class="text-nowrap logo-img text-center d-block w-100 mb-4">
                  <img src="{{ asset('template/images/logos/Pajajap logo.png') }}" width="250px" class="light-logo" alt="Logo-light" />
                </a>

                <div class="text-center mb-4">
                  <h4 class="mb-1">Masuk Sebagai Driver</h4>
                  <p class="text-muted mb-0">Masuk untuk melanjutkan pendaftaran driver dan memantau status verifikasi.</p>
                </div>

                @if (session('status'))
                  <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                @if (session('success'))
                  <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                  <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form method="POST" action="{{ route('driver.login.submit') }}">
                  @csrf

                  <div class="mb-3">
                    <label for="email" class="form-label">{{ __('Email') }}</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>

                  

                  <button type="submit" class="btn btn-primary w-100 py-8 mb-3 rounded-2">Masuk</button>

                  <div class="text-center">
                    <span class="text-muted">Belum punya akun?</span>
                    <a href="{{ route('register') }}" class="text-primary fw-semibold ms-1">Daftar</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="{{ asset('template/js/vendor.min.js')}}"></script>
  <script src="{{ asset('template/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('template/libs/simplebar/dist/simplebar.min.js') }}"></script>
  <script src="{{ asset('template/js/theme/app.init.js') }}"></script>
  <script src="{{ asset('template/js/theme/theme.js') }}"></script>
  <script src="{{ asset('template/js/theme/app.min.js') }}"></script>
</body>
</html>
