<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="vertical">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" type="image/png" href="{{ asset('template/images/logos/favicon.png') }}" />
  <link rel="stylesheet" href="{{ asset('template/css/styles.css') }}" />
  <title>Verifikasi OTP</title>
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
                  <h4 class="mb-2">Verifikasi OTP</h4>
                  <p class="text-muted mb-0">
                    Masukkan 6 digit kode OTP yang dikirim ke
                    <strong>{{ $email }}</strong>.
                  </p>
                </div>

                @if (session('success'))
                  <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                  <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form method="POST" action="{{ route('register.otp.verify') }}">
                  @csrf
                  <input type="hidden" name="email" value="{{ $email }}">

                  <div class="mb-4">
                    <label for="otp" class="form-label">Kode OTP</label>
                    <input id="otp" type="text" inputmode="numeric" maxlength="6" class="form-control @error('otp') is-invalid @enderror" name="otp" value="{{ old('otp') }}" required autofocus>
                    @error('otp')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>

                  <button type="submit" class="btn btn-primary w-100 py-8 mb-3 rounded-2">
                    Verifikasi OTP
                  </button>
                </form>

                <form method="POST" action="{{ route('register.otp.resend') }}" class="text-center">
                  @csrf
                  <input type="hidden" name="email" value="{{ $email }}">
                  <button type="submit" class="btn btn-link text-primary text-decoration-none fw-semibold">
                    Kirim Ulang OTP
                  </button>
                </form>

                <div class="text-center mt-2">
                  <a href="{{ route('register') }}" class="text-muted">Kembali ke registrasi</a>
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
</body>
</html>
