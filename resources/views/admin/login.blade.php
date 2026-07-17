<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('seo.admin_login_title') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f0fdf4;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="login-card card">
        <div class="card-body p-4">
            <h4 class="text-center mb-1" style="color:#1a5e2a;font-weight:700;">{{ __('admin.brand') }}</h4>
            <p class="text-muted text-center small mb-4">{{ __('auth.subtitle') }}</p>

            <form method="POST" action="{{ route('admin.login') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">{{ __('auth.email') }}</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" required autofocus autocomplete="email">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ __('auth.password') }}</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password-field" class="form-control @error('password') is-invalid @enderror"
                               required autocomplete="current-password">
                        <button type="button" class="btn btn-outline-secondary" id="toggle-password" tabindex="-1">
                            <i class="fa-regular fa-eye" id="toggle-icon"></i>
                        </button>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">{{ __('auth.remember') }}</label>
                </div>

                <button type="submit" class="btn btn-success w-100 fw-semibold">{{ __('auth.login') }}</button>
            </form>

            <hr class="my-3">
            <div class="text-center">
                <a href="/map" class="text-decoration-none small">&larr; {{ __('auth.back_to_map') }}</a>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('toggle-password').addEventListener('click', function () {
            var field = document.getElementById('password-field');
            var icon = document.getElementById('toggle-icon');
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    </script>
</body>
</html>
