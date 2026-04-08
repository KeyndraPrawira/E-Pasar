<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="vertical">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" type="image/png" href="{{ asset('template/images/logos/favicon.png') }}" />
  <link rel="stylesheet" href="{{ asset('template/css/styles.css')}}" />
  <link rel="stylesheet" href="{{ asset('template/libs/owl.carousel/dist/assets/owl.carousel.min.css')}}" />
  <title>Lupa Password Driver</title>
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
                  <img src="{{ asset('template/images/logos/Pajajap logo.png') }}" width="250px" class="dark-logo" alt="Logo-Dark" />
                  <img src="{{ asset('template/images/logos/Pajajap logo.png') }}" width="250px" class="light-logo" alt="Logo-light" />
                </a>

                <div class="text-center mb-4">
                  <h4 class="mb-1">Lupa Password Driver</h4>
                  <p class="text-muted mb-0">Masukkan email driver untuk menerima link reset password.</p>
                </div>

                @if (session('status'))
                  <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('driver.password.email') }}">
                  @csrf

                  <div class="mb-4">
                    <label for="email" class="form-label">{{ __('Email Address') }}</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>

                  <button type="submit" class="btn btn-primary w-100 py-8 mb-3 rounded-2">
                    Kirim Link Reset Password
                  </button>

                  <div class="text-center">
                    <a href="{{ route('driver.login') }}" class="text-primary fw-semibold">Kembali ke login driver</a>
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
