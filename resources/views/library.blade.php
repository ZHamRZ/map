<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('seo.library_title') }}</title>
    <meta name="description" content="{{ __('seo.library_desc') }}">
    <meta property="og:title" content="{{ __('seo.library_title') }}">
    <meta property="og:description" content="{{ __('seo.library_desc') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary_large_image">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f4fcf6; min-height: 100vh; color: #2b3d32; }
        .navbar { box-shadow: 0 2px 12px rgba(0,0,0,0.1); z-index: 2000; }
        .page-header {
            background: linear-gradient(135deg, #0b2e1b 0%, #1b5e20 50%, #2e7d32 100%);
            color: white; padding: 60px 0 50px; text-align: center; position: relative;
            box-shadow: inset 0 -10px 20px rgba(0,0,0,0.1);
        }
        .page-header h1 { font-weight: 800; font-size: 2.25rem; margin-bottom: 12px; letter-spacing: -0.5px; }
        .page-header p { opacity: 0.9; font-size: 1.1rem; max-width: 650px; margin: 0 auto 20px; line-height: 1.6; }
        .action-buttons-group { display: flex; justify-content: center; gap: 12px; flex-wrap: wrap; }
        .container-cards { max-width: 1200px; margin: -30px auto 50px; padding: 0 20px; position: relative; z-index: 10; }
        .package-card {
            background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.04);
            overflow: hidden; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%; display: flex; flex-direction: column; border: 1px solid rgba(0, 0, 0, 0.05);
        }
        .package-card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(27, 94, 32, 0.12); }
        .package-card .card-img-top { height: 180px; object-fit: cover; position: relative; display: flex; align-items: center; justify-content: center; }
        .package-card .card-icon {
            width: 70px; height: 70px; border-radius: 20px; display: flex; align-items: center; justify-content: center;
            font-size: 30px; color: white; box-shadow: 0 8px 20px rgba(0,0,0,0.15); transition: transform 0.3s ease;
        }
        .package-card:hover .card-icon { transform: scale(1.1) rotate(5deg); }
        .package-card .card-body { padding: 28px; flex: 1; display: flex; flex-direction: column; }
        .package-card .card-title { font-weight: 750; font-size: 1.3rem; margin-bottom: 12px; color: #0b2e1b; }
        .package-card .card-text { color: #5a6e5f; font-size: 0.95rem; line-height: 1.6; flex: 1; }
        .package-card .places-badge { display: flex; flex-wrap: wrap; gap: 8px; margin: 16px 0 24px; }
        .package-card .places-badge .badge { font-size: 0.8rem; padding: 6px 14px; border-radius: 20px; font-weight: 600; }
        .package-card .btn-route { width: 100%; padding: 12px; font-weight: 700; border-radius: 12px; border: none; transition: all 0.25s ease; font-size: 0.95rem; }
        .package-card .btn-route:hover { transform: scale(1.02); box-shadow: 0 4px 15px rgba(40, 167, 69, 0.2); }
        .back-link { display: inline-flex; align-items: center; gap: 6px; color: rgba(255,255,255,0.8); text-decoration: none; font-size: 0.92rem; font-weight: 600; transition: color 0.2s; }
        .back-link:hover { color: #a3e635; }
        .bg-purple { background-color: #9b59b6 !important; }
        @media (max-width: 768px) {
            .page-header { padding: 45px 16px 35px; }
            .page-header h1 { font-size: 1.75rem; }
            .container-cards { margin-top: -15px; }
            .package-card .card-img-top { height: 140px; }
        }
    </style>
</head>
<body>
    @include('partials.public-navbar')

    <div class="page-header">
        <div class="container">
            <h1><i class="fa-solid fa-compass me-2 text-lime"></i>{{ __('library.title') }}</h1>
            <p>{{ __('library.subtitle') }}</p>
            <div class="action-buttons-group">
                <a href="{{ route('map') }}" class="back-link"><i class="fa-solid fa-arrow-left"></i> {{ __('library.back') }}</a>
            </div>
        </div>
    </div>

    <div class="container-cards">
        <div class="row g-4">
            @foreach ($packages as $pkg)
            <div class="col-md-6 col-lg-4">
                <div class="package-card">
                    @php
                        $iconBg = ['budaya' => '#e74c3c', 'kuliner' => '#9b59b6', 'sehat' => '#2ecc71'][$pkg->slug ?? $pkg->id] ?? '#27ae60';
                        $categories = $pkg->category_list ?? $pkg->places->pluck('category')->unique()->values()->toArray() ?? [];
                    @endphp

                    @if ($pkg->image_url)
                        <img src="{{ $pkg->image_url }}" alt="{{ $pkg->title }}" class="card-img-top">
                    @else
                        <div class="card-img-top" style="background: linear-gradient(135deg, {{ $iconBg }}18 0%, {{ $iconBg }}08 100%);">
                            <div class="card-icon" style="background: {{ $iconBg }};">
                                <i class="fa-solid {{ $pkg->icon ?? 'fa-compass' }}"></i>
                            </div>
                        </div>
                    @endif

                    <div class="card-body">
                        <h5 class="card-title">{{ $pkg->title }}</h5>
                        <p class="card-text">{{ $pkg->description }}</p>

                        @if ($pkg->duration)
                            <div class="mb-2 small text-muted">
                                <i class="fa-regular fa-clock me-1"></i>{{ $pkg->duration }}
                            </div>
                        @endif

                        @if (count($categories) > 0)
                            <div class="places-badge">
                                @foreach ($categories as $cat)
                                    <span class="badge bg-success bg-opacity-10 text-success">{{ $cat }}</span>
                                @endforeach
                            </div>
                        @endif

                        @if ($pkg->places->count() > 0)
                            <div class="mb-3 small text-muted">
                                <i class="fa-solid fa-location-dot me-1"></i>{{ __('library.places_count', ['count' => $pkg->places->count()]) }}
                            </div>
                        @endif

                        <a href="{{ route('map') }}?package={{ $pkg->slug ?? $pkg->id }}"
                           class="btn btn-success btn-route d-flex align-items-center justify-content-center gap-2">
                            <i class="fa-solid fa-map-location-dot"></i> {{ __('library.view_route') }}
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
