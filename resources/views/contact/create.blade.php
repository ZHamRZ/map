<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('seo.contact_title') }}</title>
    <meta name="description" content="{{ __('seo.contact_desc') }}">
    <meta property="og:title" content="{{ __('seo.contact_title') }}">
    <meta property="og:description" content="{{ __('seo.contact_desc') }}">
    <meta property="og:type" content="website">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f4fcf6; color: #2b3d32; }
        .navbar { box-shadow: 0 2px 12px rgba(0,0,0,0.1); z-index: 2000; }
        .page-header {
            background: linear-gradient(135deg, #1565c0 0%, #1976d2 50%, #42a5f5 100%);
            color: white; padding: 50px 0 40px; text-align: center;
        }
        .page-header h1 { font-weight: 800; font-size: 2rem; }
        .contact-form { background: white; border-radius: 20px; padding: 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); }
    </style>
</head>
<body>
    @include('partials.public-navbar')

    <div class="page-header">
        <div class="container">
            <h1><i class="fa-regular fa-paper-plane me-2"></i>{{ __('contact.title') }}</h1>
            <p class="opacity-90 mt-2">{{ __('contact.subtitle') }}</p>
        </div>
    </div>

    <div class="container py-4" style="max-width:600px;">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fa-solid fa-circle-check me-1"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="contact-form">
            <form method="POST" action="{{ route('contact.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">{{ __('contact.name') }} <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" required maxlength="255">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">{{ __('contact.email') }} <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" required maxlength="255">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">{{ __('contact.phone') }}</label>
                    <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror"
                           value="{{ old('phone') }}" maxlength="20">
                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">{{ __('contact.message') }} <span class="text-danger">*</span></label>
                    <textarea name="message" rows="5" class="form-control @error('message') is-invalid @enderror"
                              required maxlength="5000">{{ old('message') }}</textarea>
                    @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold" style="border-radius:12px;">
                    <i class="fa-regular fa-paper-plane me-2"></i>{{ __('contact.submit') }}
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
