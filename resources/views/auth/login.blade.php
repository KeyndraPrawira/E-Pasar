<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="vertical">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" type="image/png" href="{{ asset('template/images/logos/favicon.png') }}" />
  <link rel="stylesheet" href="{{ asset('template/css/styles.css') }}" />
  <link rel="stylesheet" href="{{ asset('template/libs/owl.carousel/dist/assets/owl.carousel.min.css') }}" />
  <title>Login Pajajap</title>
</head>
<body>
  @php
    $selectedRole = old('login_as', $loginAs ?? request('as', 'admin'));
  @endphp

  <div class="preloader">
    <img src="{{ asset('template/images/logos/Pajajap logo.png') }}" alt="loader" class="lds-ripple img-fluid" />
  </div>

  <div id="main-wrapper" class="auth-customizer-none">
    <div class="position-relative overflow-hidden radial-gradient min-vh-100 w-100 d-flex align-items-center justify-content-center">
      <div class="container">
        <div class="row justify-content-center align-items-center">
          <div class="col-lg-9 col-xl-8">
            <div class="card border-0 shadow-lg overflow-hidden">
              <div class="row g-0">
                <div class="col-md-5 bg-primary text-white p-4 p-lg-5 d-flex flex-column justify-content-center">
                  <a href="{{ route('landingpage') }}" class="text-center d-block mb-4">
                    <img src="{{ asset('template/images/logos/Pajajap logo.png') }}" width="220" alt="Pajajap Logo" class="img-fluid" />
                  </a>
                  <h3 class="text-white mb-3">Login untuk melanjutkan</h3>
                  
                </div>

                <div class="col-md-7">
                  <div class="card-body p-4 p-lg-5">
                    <div class="text-center text-md-start mb-4">
                      <h4 class="mb-1" id="login-title">
                        {{ $selectedRole === 'driver' ? 'Masuk Sebagai Driver' : 'Masuk Sebagai Admin' }}
                      </h4>
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

                    <form method="POST" action="{{ route('login') }}">
                      @csrf

                      <div class="mb-4">
                        <label class="form-label d-block">Masuk sebagai</label>
                        <div class="btn-group w-100" role="group" aria-label="Pilih jenis akun">
                          <input type="radio" class="btn-check" name="login_as" id="login_as_admin" value="admin" autocomplete="off" {{ $selectedRole === 'admin' ? 'checked' : '' }}>
                          <label class="btn btn-outline-primary" for="login_as_admin">Admin</label>

                          <input type="radio" class="btn-check" name="login_as" id="login_as_driver" value="driver" autocomplete="off" {{ $selectedRole === 'driver' ? 'checked' : '' }}>
                          <label class="btn btn-outline-primary" for="login_as_driver">Driver</label>
                        </div>
                        @error('login_as')
                          <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                      </div>

                      <div class="mb-3">
                        <label for="email" class="form-label">{{ __('Email') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                      </div>

                      <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                          <label for="password" class="form-label mb-0">{{ __('Password') }}</label>
                          <a href="{{ route('driver.password.request') }}" class="text-primary small fw-semibold {{ $selectedRole === 'driver' ? '' : 'd-none' }}" id="driver-forgot-password">Lupa password?</a>
                        </div>
                        <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="current-password">
                        @error('password')
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                      </div>

                      <button type="submit" class="btn btn-primary w-100 py-8 mb-3 rounded-2">Masuk</button>

                      <div class="text-center" id="driver-register-hint">
                        <span class="text-muted">Belum punya akun driver?</span>
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
    </div>
  </div>

  <script src="{{ asset('template/js/vendor.min.js') }}"></script>
  <script src="{{ asset('template/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('template/libs/simplebar/dist/simplebar.min.js') }}"></script>
  <script src="{{ asset('template/js/theme/app.init.js') }}"></script>
  <script src="{{ asset('template/js/theme/theme.js') }}"></script>
  <script src="{{ asset('template/js/theme/app.min.js') }}"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const adminRadio = document.getElementById('login_as_admin');
      const driverRadio = document.getElementById('login_as_driver');
      const title = document.getElementById('login-title');
      const description = document.getElementById('login-description');
      const forgotPassword = document.getElementById('driver-forgot-password');
      const registerHint = document.getElementById('driver-register-hint');

      function updateCopy() {
        const isDriver = driverRadio.checked;

        title.textContent = isDriver ? 'Masuk Sebagai Driver' : 'Masuk Sebagai Admin';
        description.textContent = isDriver
          ? 'Masuk sebagai driver untuk lanjut pendaftaran dan cek status verifikasi.'
          : 'Masuk sebagai admin untuk mengelola data pasar, pengguna, dan operasional.';

        forgotPassword.classList.toggle('d-none', !isDriver);
        registerHint.classList.toggle('d-none', !isDriver);
      }

      adminRadio.addEventListener('change', updateCopy);
      driverRadio.addEventListener('change', updateCopy);
      updateCopy();
    });
  </script>
</body>
</html>
