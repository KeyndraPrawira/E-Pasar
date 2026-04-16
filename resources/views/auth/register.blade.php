<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="vertical">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="{{ asset('template/css/styles.css')}}" />
  <link rel="stylesheet" href="{{ asset('template/libs/owl.carousel/dist/assets/owl.carousel.min.css')}}" />
  <title>Register Driver Pajajap</title>
</head>

<body>
  <div class="preloader">
    <img src="{{ asset('template/images/logos/Pajajap logo.png') }}" alt="loader" class="lds-ripple img-fluid" />
  </div>

  <div id="main-wrapper" class="auth-customizer-none">
    <div class="position-relative overflow-hidden radial-gradient min-vh-100 w-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-7 col-xxl-5 auth-card">
            <div class="card mb-0">
              <div class="card-body">
                <a href="{{ route('landingpage') }}" class="text-nowrap logo-img text-center d-block w-100 mb-4">
                  <img src="{{ asset('template/images/logos/Pajajap logo.png') }}" width="250px" class="dark-logo" alt="Logo-Dark" />
                  <img src="{{ asset('template/images/logos/Pajajap logo.png') }}" width="250px" class="light-logo" alt="Logo-light" />
                </a>

                <div class="text-center mb-4">
                  <h4 class="mb-1">Buat Akun Driver</h4>
                  <p class="text-muted mb-0">Daftarkan diri sebagai driver setelah diverifikasi oleh admin.</p>
                </div>

                @if (session('error'))
                  <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                  @csrf

                  <div class="mb-3">
                    <label for="name" class="form-label">{{ __('Nama') }}</label>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                    @error('name')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>

                  <div class="mb-3">
                    <label for="email" class="form-label">{{ __('Email') }}</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                    @error('email')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>

                  <div class="mb-3">
                    <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
                    <input id="nomor_telepon" type="text" class="form-control @error('nomor_telepon') is-invalid @enderror" name="nomor_telepon" value="{{ old('nomor_telepon') }}" required autocomplete="tel">
                    @error('nomor_telepon')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>

                  <div class="mb-3">
                    <label for="password" class="form-label">{{ __('Password') }}</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                    @error('password')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>

                  <div class="mb-4">
                    <label for="password-confirm" class="form-label">{{ __('Konfirmasi Password') }}</label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                  </div>

                  <button type="submit" class="btn btn-primary w-100 py-8 mb-3 rounded-2">
                    {{ __('Register') }}
                  </button>

                  <div class="text-center">
                    <span class="text-muted">Sudah punya akun?</span>
                    <a href="{{ route('driver.login') }}" class="text-primary fw-semibold ms-1">Masuk</a>
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
