<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $place->name }} — Desa Bilebante</title>
    <meta name="description" content="{{ Str::limit($place->description ?? $place->history ?? __("place.meta_fallback"), 160) }}">
    <meta property="og:title" content="{{ $place->name }} — {{ __("place.meta_fallback") }}">
    <meta property="og:description" content="{{ Str::limit($place->description ?? __("place.meta_og_fallback"), 160) }}">
    @if ($place->image_url)
        <meta property="og:image" content="{{ url($place->image_url) }}">
    @endif
    <meta property="og:type" content="place">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary_large_image">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-fullscreen@1.0.2/dist/leaflet.fullscreen.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    @php
        $allCats = \App\Models\Category::active()->ordered()->get()->keyBy('key');
        $catColors = $allCats->pluck('color', 'key')->toArray();
        $catSvg = $allCats->pluck('svg_path', 'key')->toArray();
        $cat = $place->category;
        $catColor = $catColors[$cat] ?? '#757575';
        $catIcon = $catSvg[$cat] ?? '<path d="M12 3a7 7 0 00-7 7c0 5 7 12 7 12s7-7 7-12a7 7 0 00-7-7z"/><circle cx="12" cy="10" r="2.5"/>';
    @endphp
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f4fcf6; color: #2b3d32; }
        .navbar { box-shadow: 0 2px 12px rgba(0,0,0,0.1); z-index: 2000; }

        /* ── Hero Banner ── */
        .hero-banner {
            position: relative;
            height: 420px;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 15px 45px rgba(0,0,0,0.18);
            margin-bottom: 30px;
        }
        .hero-slider {
            width: 100%;
            height: 100%;
            position: relative;
        }
        .hero-slide {
            position: absolute;
            inset: 0;
            opacity: 0;
            transition: opacity 1.2s ease;
        }
        .hero-slide.active {
            opacity: 1;
        }
        .hero-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 6s ease;
        }
        .hero-slide.active img {
            transform: scale(1.08);
        }
        .hero-overlay {
            position: absolute;
            inset: 0;
            z-index: 2;
            display: flex;
            align-items: flex-end;
            padding: 36px 40px;
        }
        .hero-deco-left {
            position: absolute;
            top: 0;
            left: 0;
            width: 200px;
            height: 200px;
            z-index: 3;
            pointer-events: none;
            opacity: 0.25;
        }
        .hero-deco-left svg { width: 100%; height: 100%; }
        .hero-deco-right {
            position: absolute;
            top: 0;
            right: 0;
            width: 180px;
            height: 180px;
            z-index: 3;
            pointer-events: none;
            opacity: 0.2;
            transform: scaleX(-1);
        }
        .hero-deco-right svg { width: 100%; height: 100%; }
        .hero-title-area { color: white; position: relative; z-index: 4; max-width: 700px; }
        .hero-category {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 16px 5px 12px;
            border-radius: 30px;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #fff;
            margin-bottom: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        .hero-category svg {
            display: inline-block;
            vertical-align: middle;
        }
        .hero-rating-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: rgba(255,255,255,0.12);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.18);
            padding: 4px 12px;
            border-radius: 30px;
            font-size: 0.7rem;
            font-weight: 600;
            color: #fff;
            margin-left: 8px;
        }
        .hero-title-area h1 {
            font-family: 'Playfair Display', serif;
            font-weight: 800;
            font-size: 2.6rem;
            line-height: 1.15;
            margin-bottom: 8px;
            text-shadow: 0 3px 20px rgba(0,0,0,0.4);
        }
        .hero-meta {
            font-size: 0.82rem;
            opacity: 0.8;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .hero-meta i { color: #d4af37; }
        /* ── Slider controls ── */
        .hero-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 5;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.25);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.25s ease;
            opacity: 0;
            font-size: 16px;
        }
        .hero-banner:hover .hero-arrow { opacity: 1; }
        .hero-arrow:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-50%) scale(1.1);
        }
        .hero-arrow-left { left: 16px; }
        .hero-arrow-right { right: 16px; }
        .hero-dots {
            position: absolute;
            bottom: 16px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 5;
            display: flex;
            gap: 8px;
        }
        .hero-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgba(255,255,255,0.4);
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .hero-dot.active { background: #fff; width: 24px; border-radius: 4px; }
        /* ── Glassmorphism info card ── */
        .place-info { background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.04); }
        .badge-category { font-size: 0.82rem; padding: 6px 14px; border-radius: 20px; font-weight: 600; }
        .place-description { font-size: 0.98rem; line-height: 1.8; color: #4a5d4e; white-space: pre-wrap; }
        #map-routing { height: 480px; border-radius: 20px; z-index: 1; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border: 1px solid rgba(0,0,0,0.05); }
        .routing-panel { background: white; border-radius: 20px; padding: 26px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.04); }
        .btn-start { width: 100%; padding: 12px; font-weight: 700; border-radius: 12px; transition: all 0.2s ease; }
        .btn-start:hover { transform: scale(1.01); }
        .route-summary { background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%); border-radius: 16px; padding: 18px; margin-top: 16px; margin-bottom: 16px; display: none; }
        .route-summary.show { display: block; }
        .route-summary .stat-value { font-size: 1.4rem; font-weight: 800; color: #0b2e1b; }
        .route-summary .stat-label { font-size: 0.72rem; color: #4a5d4e; text-transform: uppercase; letter-spacing: 0.8px; font-weight: 700; }
        .leaflet-routing-container { display: none; }
        .coord-click-hint { position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); z-index: 1000; background: rgba(11, 46, 27, 0.95); color: white; padding: 8px 20px; border-radius: 30px; font-size: 13px; font-weight: 600; pointer-events: none; opacity: 0; transition: opacity 0.3s ease; }
        .coord-click-hint.show { opacity: 1; }
        .btn-back-float { position: fixed; top: 74px; left: 20px; z-index: 2000; background: white; border: 1px solid rgba(0,0,0,0.1); border-radius: 50%; width: 44px; height: 44px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); display: flex; align-items: center; justify-content: center; font-size: 18px; cursor: pointer; text-decoration: none; color: #2b3d32; transition: all 0.25s; }
        .btn-back-float:hover { background: #0b2e1b; color: white; transform: scale(1.1) rotate(-5deg); }
        .reviews-section { margin-top: 1.5rem; }
        @media (min-width: 992px) { .reviews-section { width: 41.66666667%; } }
        .review-card { background: #f7faf8; border-radius: 12px; padding: 16px; margin-bottom: 12px; border: 1px solid rgba(0,0,0,0.03); transition: transform 0.2s ease; }
        .review-card:hover { transform: translateX(3px); }
        .rating-star-interactive { cursor: pointer; font-size: 1.8rem; color: #e2e8f0; transition: color 0.15s ease, transform 0.15s ease; }
        .rating-star-interactive:hover { transform: scale(1.15); }
        .gallery-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 8px; }
        .gallery-grid img { width: 100%; height: 120px; object-fit: cover; border-radius: 10px; cursor: pointer; transition: transform 0.2s; }
        .gallery-grid img:hover { transform: scale(1.05); }
        .share-btn { width: 40px; height: 40px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; color: white; transition: all 0.2s; text-decoration: none; }
        .share-btn:hover { transform: scale(1.15); }
        .lightbox-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.9); z-index: 99999; display: none; align-items: center; justify-content: center; cursor: pointer; }
        .lightbox-overlay.show { display: flex; }
        .lightbox-overlay img { max-width: 90vw; max-height: 90vh; border-radius: 8px; object-fit: contain; }
        .video-wrapper { position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 12px; }
        .video-wrapper iframe { position: absolute; top: 0; left: 0; width: 100%; height: 100%; border-radius: 12px; }
        @media (max-width: 768px) {
            .hero-banner { height: 280px; border-radius: 16px; }
            .hero-title-area h1 { font-size: 1.8rem; }
            .hero-deco-left, .hero-deco-right { width: 110px; height: 110px; opacity: 0.15; }
            .hero-overlay { padding: 24px 20px; }
            #map-routing { height: 360px; }
            .place-info { padding: 20px; }
            .btn-back-float { top: 68px; left: 10px; width: 38px; height: 38px; }
        }
    </style>
</head>
<body>
    @include('partials.public-navbar')

    <a href="{{ route('map') }}" class="btn-back-float" title="{{ __('common.back') }}">
        <i class="fa-solid fa-arrow-left"></i>
    </a>

    <div class="container py-4">
        {{-- Hero Banner with Image Slider --}}
        <div class="hero-banner" id="heroBanner">
            <div class="hero-slider" id="heroSlider">
                @php
                    $heroImages = collect([]);
                    if ($place->image_url) { $heroImages->push($place->image_url); }
                    foreach ($place->images as $img) {
                        if ($img->image_url !== $place->image_url) { $heroImages->push($img->image_url); }
                    }
                    if ($heroImages->isEmpty()) {
                        $heroImages->push('https://images.unsplash.com/photo-1585409677983-0f6c41ca9c3b?w=1200'); // rice terraces fallback
                    }
                @endphp
                @foreach ($heroImages as $i => $src)
                    <div class="hero-slide {{ $i === 0 ? 'active' : '' }}" data-index="{{ $i }}">
                        <img src="{{ $src }}" alt="{{ $place->name }}" loading="{{ $i === 0 ? 'eager' : 'lazy' }}">
                    </div>
                @endforeach
            </div>

            {{-- Decorative leaves --}}
            <div class="hero-deco-left">
                <svg viewBox="0 0 200 200" fill="none"><path d="M0 0h200v200C90 190 10 110 0 0z" fill="#d4af37" opacity="0.5"/><path d="M0 0c30 60 80 90 150 100C100 60 50 30 0 0z" fill="#4a7c59" opacity="0.6"/><path d="M20 10c20 40 60 60 100 70C80 50 50 30 20 10z" fill="#2d5a3d" opacity="0.7"/><ellipse cx="60" cy="50" rx="30" ry="12" fill="#d4af37" opacity="0.4" transform="rotate(-20 60 50)"/><ellipse cx="110" cy="30" rx="22" ry="8" fill="#2d5a3d" opacity="0.5" transform="rotate(15 110 30)"/></svg>
            </div>
            <div class="hero-deco-right">
                <svg viewBox="0 0 200 200" fill="none"><path d="M0 0h200v200C90 190 10 110 0 0z" fill="#d4af37" opacity="0.5"/><path d="M0 0c30 60 80 90 150 100C100 60 50 30 0 0z" fill="#4a7c59" opacity="0.6"/><path d="M20 10c20 40 60 60 100 70C80 50 50 30 20 10z" fill="#2d5a3d" opacity="0.7"/><ellipse cx="60" cy="50" rx="30" ry="12" fill="#d4af37" opacity="0.4" transform="rotate(-20 60 50)"/><ellipse cx="110" cy="30" rx="22" ry="8" fill="#2d5a3d" opacity="0.5" transform="rotate(15 110 30)"/></svg>
            </div>

            <div class="hero-overlay">
                <div class="hero-title-area">
                    <div class="d-flex align-items-center flex-wrap mb-2">
                        <span class="hero-category" style="background:{{ $catColor }};">{!! $catIcon !!} {{ $place->category }}</span>
                        @if($place->reviews->count() > 0)
                            <span class="hero-rating-badge">
                                <i class="fa-solid fa-star" style="color:#d4af37;"></i>
                                {{ round($place->reviews->avg('rating'), 1) }}
                            </span>
                        @endif
                    </div>
                    <h1>{{ $place->name }}</h1>
                    <div class="hero-meta">
                        <i class="fa-solid fa-location-dot"></i>
                        {{ $place->latitude }}, {{ $place->longitude }}
                    </div>
                </div>
            </div>

            {{-- Arrows --}}
            <button class="hero-arrow hero-arrow-left" onclick="heroSlide(-1)" aria-label="Previous">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <button class="hero-arrow hero-arrow-right" onclick="heroSlide(1)" aria-label="Next">
                <i class="fa-solid fa-chevron-right"></i>
            </button>

            {{-- Dots --}}
            <div class="hero-dots" id="heroDots">
                @foreach ($heroImages as $i => $src)
                    <span class="hero-dot {{ $i === 0 ? 'active' : '' }}" data-index="{{ $i }}" onclick="heroGoTo({{ $i }})"></span>
                @endforeach
            </div>
        </div>

        {{-- Share buttons --}}
        <div class="d-flex gap-2 justify-content-end mb-3">
            <span class="small text-muted align-self-center me-1">{{ __('place.share') }}:</span>
            <a href="https://www.facebook.com/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="share-btn" style="background:#1877f2;" title="{{ __('place.share_facebook') }}"><i class="fa-brands fa-facebook-f"></i></a>
            <a href="https://wa.me/?text={{ urlencode($place->name . ' - ' . url()->current()) }}" target="_blank" class="share-btn" style="background:#25d366;" title="{{ __('place.share_whatsapp') }}"><i class="fa-brands fa-whatsapp"></i></a>
            <a href="https://twitter.com/intent/tweet?text={{ urlencode($place->name) }}&url={{ urlencode(url()->current()) }}" target="_blank" class="share-btn" style="background:#000;" title="{{ __('place.share_twitter') }}"><i class="fa-brands fa-x-twitter"></i></a>
            <button onclick="copyPageUrl()" class="share-btn border-0" style="background:#6c757d;" title="{{ __('place.share_copy') }}"><i class="fa-solid fa-link"></i></button>
        </div>

        <div class="row g-4">
            {{-- Kiri --}}
            <div class="col-lg-5">
                <div class="place-info">
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2" style="color:#0b2e1b;">
                        <i class="fa-solid fa-circle-info text-lime"></i> {{ __('place.detail') }}
                    </h5>
                    @if ($place->description)
                        <div class="place-description">{{ $place->description }}</div>
                    @else
                        <p class="text-muted fst-italic">{{ __('place.no_description') }}</p>
                    @endif
                </div>

                {{-- History --}}
                @if ($place->history)
                <div class="place-info mt-4">
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2" style="color:#0b2e1b;">
                        <i class="fa-solid fa-landmark text-lime"></i> {{ __('place.history') }}
                    </h5>
                    <div class="place-description">{{ $place->history }}</div>
                </div>
                @endif

                {{-- Cultural Significance --}}
                @if ($place->cultural_significance)
                <div class="place-info mt-4">
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2" style="color:#0b2e1b;">
                        <i class="fa-solid fa-hands-praying text-lime"></i> {{ __('place.cultural_significance') }}
                    </h5>
                    <div class="place-description">{{ $place->cultural_significance }}</div>
                </div>
                @endif

                {{-- Video --}}
                @if ($place->video_url)
                <div class="place-info mt-4">
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2" style="color:#0b2e1b;">
                        <i class="fa-solid fa-video text-lime"></i> {{ __('place.video') }}
                    </h5>
                    @php
                        $videoId = null;
                        if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]+)/', $place->video_url, $m)) {
                            $videoId = $m[1];
                        }
                    @endphp
                    @if ($videoId)
                        <div class="video-wrapper">
                            <iframe src="https://www.youtube.com/embed/{{ $videoId }}" title="{{ $place->name }}" frameborder="0" allowfullscreen></iframe>
                        </div>
                    @else
                        <a href="{{ $place->video_url }}" target="_blank" class="btn btn-sm btn-outline-danger">
                            <i class="fa-brands fa-youtube me-1"></i>{{ __('place.video') }}
                        </a>
                    @endif
                </div>
                @endif

                {{-- Gallery --}}
                @if ($place->images->count() > 0)
                <div class="place-info mt-4">
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2" style="color:#0b2e1b;">
                        <i class="fa-solid fa-images text-lime"></i> {{ __('place.gallery') }}
                    </h5>
                    <div class="gallery-grid">
                        @foreach ($place->images as $img)
                            <img src="{{ $img->image_url }}" alt="{{ $place->name }}" loading="lazy"
                                 onclick="openLightbox('{{ $img->image_url }}')">
                        @endforeach
                    </div>
                </div>
                @endif

            </div>

            {{-- Kanan: Peta --}}
            <div class="col-lg-7">
                <div class="routing-panel">
                    <h5><i class="fa-solid fa-route text-lime"></i> {{ __('place.route_title') }}</h5>
                    <p class="text-muted small mb-3">{{ __('place.route_desc') }}</p>
                    <div class="d-flex gap-2 mb-3">
                        <button id="btn-set-start" class="btn btn-success btn-start flex-fill d-flex align-items-center justify-content-center gap-2">
                            <i class="fa-solid fa-map-pin"></i> <span>{{ __('place.route_pick') }}</span>
                        </button>
                        <button id="btn-my-location" class="btn btn-outline-success btn-start flex-fill d-flex align-items-center justify-content-center gap-2">
                            <i class="fa-solid fa-location-crosshairs"></i> <span>{{ __('place.route_gps') }}</span>
                        </button>
                    </div>
                    <div id="route-summary" class="route-summary">
                        <div class="row g-2">
                            <div class="col-6 stat border-end border-success border-opacity-10">
                                <div class="stat-value" id="route-distance">0</div>
                                <div class="stat-label">{{ __('place.route_distance') }}</div>
                            </div>
                            <div class="col-6 stat">
                                <div class="stat-value" id="route-duration">0</div>
                                <div class="stat-label">{{ __('place.route_duration') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="position-relative">
                        <div id="map-routing"></div>
                        <div id="click-hint" class="coord-click-hint">{{ __('place.route_pick') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Reviews --}}
    <div class="container">
        <div class="place-info reviews-section">
            <h5 class="fw-bold mb-3 d-flex align-items-center justify-content-between" style="color:#0b2e1b;">
                <span class="d-flex align-items-center gap-2"><i class="fa-solid fa-comments text-lime"></i> {{ __('place.reviews') }}</span>
                <span class="badge bg-success bg-opacity-10 text-success small" style="font-size:0.75rem;">{{ $place->reviews->count() }} {{ __('place.review_count') }}</span>
            </h5>
            @if ($place->reviews->count() > 0)
                <div style="max-height:360px; overflow-y:auto; padding-right:5px;">
                    @foreach ($place->reviews as $review)
                        <div class="review-card">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width:28px;height:28px;font-size:0.75rem;font-weight:700;">
                                        {{ strtoupper(substr($review->visitor_name, 0, 1)) }}
                                    </div>
                                    <strong class="small text-dark">{{ $review->visitor_name }}</strong>
                                </div>
                                <span class="text-warning small d-flex gap-0.5">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="fa-solid fa-star{{ $i <= $review->rating ? '' : ' text-muted opacity-25' }}"></i>
                                    @endfor
                                </span>
                            </div>
                            @if ($review->comment)
                                <p class="small text-secondary mb-1 ps-1">{{ $review->comment }}</p>
                            @endif
                            <div class="text-muted small ps-1" style="font-size:0.7rem;text-align:right;">
                                {{ $review->created_at->diffForHumans() }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4 text-muted fst-italic small bg-light rounded-3 mb-3">
                    <i class="fa-regular fa-face-smile d-block fs-3 mb-2 opacity-50"></i>
                    {{ __('place.no_reviews') }}
                </div>
            @endif

            <hr class="my-4">
            <h6 class="fw-bold mb-3" style="color:#0b2e1b;"><i class="fa-solid fa-pen-to-square me-1"></i> {{ __('place.write_review') }}</h6>
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show py-2 small" role="alert">
                    <i class="fa-solid fa-circle-check me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
                </div>
            @endif
            <form method="POST" action="{{ route('place.review.store', $place) }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary">{{ __('place.your_name') }}</label>
                    <input type="text" name="visitor_name" class="form-control form-control-sm @error('visitor_name') is-invalid @enderror"
                        placeholder="{{ __('place.your_name') }}" value="{{ old('visitor_name') }}" required maxlength="100">
                    @error('visitor_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary d-block">{{ __('place.your_rating') }}</label>
                    <div class="d-flex gap-2" id="rating-stars">
                        @for ($i = 1; $i <= 5; $i++)
                            <label class="rating-star-interactive">
                                <input type="radio" name="rating" value="{{ $i }}" class="d-none" {{ old('rating') == $i ? 'checked' : '' }}>
                                <i class="fa-solid fa-star"></i>
                            </label>
                        @endfor
                    </div>
                    @error('rating') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary">{{ __('place.your_comment') }}</label>
                    <textarea name="comment" class="form-control form-control-sm @error('comment') is-invalid @enderror"
                        rows="3" placeholder="{{ __('place.your_comment') }}" maxlength="2000">{{ old('comment') }}</textarea>
                    @error('comment') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <button type="submit" class="btn btn-success btn-sm w-100 fw-bold py-2 rounded-3">
                    <i class="fa-regular fa-paper-plane me-1"></i> {{ __('place.submit_review') }}
                </button>
            </form>
        </div>
    </div>

    {{-- Lightbox --}}
    <div id="lightbox" class="lightbox-overlay" onclick="this.classList.remove('show')">
        <img id="lightbox-img" src="" alt="">
    </div>

    <script>
        function openLightbox(url) {
            document.getElementById('lightbox-img').src = url;
            document.getElementById('lightbox').classList.add('show');
        }
        function copyPageUrl() {
            navigator.clipboard.writeText(window.location.href).then(function () {
                var btn = event.target.closest('.share-btn');
                var orig = btn.innerHTML;
                btn.innerHTML = '<i class="fa-solid fa-check"></i>';
                btn.style.background = '#28a745';
                setTimeout(function () { btn.innerHTML = orig; btn.style.background = '#6c757d'; }, 2000);
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet-fullscreen@1.0.2/dist/Leaflet.fullscreen.min.js"></script>

    <script>
        (function () {
            'use strict';
            var DEST_LAT = {{ $place->latitude }};
            var DEST_LNG = {{ $place->longitude }};
            var PLACE_NAME = '{{ $place->name }}';
            var LANG = {
                gps_unsupported: @json(__('route.gps_unsupported')),
                tracking: @json(__('place.tracking')),
                gps_error: @json(__('route.gps_error')),
                calculating: @json(__('place.calculating')),
            };

            var map = L.map('map-routing', {
                center: [DEST_LAT, DEST_LNG], zoom: 14, minZoom: 2, maxZoom: 19,
                zoomControl: true, fullscreenControl: true,
            });

            L.control.scale({ imperial: false, metric: true, position: 'bottomleft' }).addTo(map);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap', maxZoom: 19,
            }).addTo(map);

            fetch('/api/boundary', {
                headers: {
                    'Accept': 'application/json',
                    'X-Tunnel-Skip-AntiPhishing-Page': 'true'
                }
            })
                .then(function (r) { return r.json(); })
                .then(function (geo) {
                    L.geoJSON(geo, { style: { color: '#4caf50', weight: 3, opacity: 0.9, fillColor: '#4caf50', fillOpacity: 0.04 } }).addTo(map);
                }).catch(function () {});

            var destIcon = L.divIcon({
                className: '',
                html: '<div style="position:relative;"><svg width="36" height="36" viewBox="0 0 36 36"><rect x="2" y="2" width="32" height="32" rx="10" fill="#2e7d32" stroke="#fff" stroke-width="3" filter="url(#ds)"/><defs><filter id="ds"><feDropShadow dx="0" dy="2" stdDeviation="3" flood-color="rgba(0,0,0,0.3)"/></filter></defs><circle cx="18" cy="18" r="11" fill="#fff"/></svg><div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;pointer-events:none;color:#2e7d32;font-size:11px;"><i class="fa-solid fa-location-dot"></i></div></div>',
                iconSize: [36, 36], iconAnchor: [18, 18], popupAnchor: [0, -20],
            });

            L.marker([DEST_LAT, DEST_LNG], { icon: destIcon }).addTo(map).bindPopup('<strong>' + PLACE_NAME + '</strong>', { closeButton: false });

            var routingControl = null;
            var startMarker = null;
            var routeSummary = document.getElementById('route-summary');
            var routeDistance = document.getElementById('route-distance');
            var routeDuration = document.getElementById('route-duration');
            var clickHint = document.getElementById('click-hint');
            var btnSetStart = document.getElementById('btn-set-start');
            var btnMyLocation = document.getElementById('btn-my-location');
            var isSelectingStart = false;

            function formatDistance(meters) {
                return meters >= 1000 ? (meters / 1000).toFixed(1) + ' km' : Math.round(meters) + ' m';
            }
            function formatDuration(s) {
                var h = Math.floor(s / 3600), m = Math.floor((s % 3600) / 60);
                return h > 0 ? h + ' jam ' + m + ' menit' : m + ' mnt';
            }
            function calculateRoute(slat, slng) {
                if (routingControl) { map.removeControl(routingControl); routingControl = null; }
                if (startMarker) { map.removeLayer(startMarker); startMarker = null; }
                var startIcon = L.divIcon({
                    className: '',
                    html: '<div style="position:relative;"><svg width="32" height="32" viewBox="0 0 32 32"><rect x="2" y="2" width="28" height="28" rx="9" fill="#2196F3" stroke="#fff" stroke-width="3" filter="url(#ss)"/><defs><filter id="ss"><feDropShadow dx="0" dy="2" stdDeviation="3" flood-color="rgba(0,0,0,0.3)"/></filter></defs><circle cx="16" cy="16" r="10" fill="#fff"/></svg><div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;pointer-events:none;color:#2196F3;font-size:10px;"><i class="fa-solid fa-location-crosshairs"></i></div></div>',
                    iconSize: [32, 32], iconAnchor: [16, 16],
                });
                startMarker = L.marker([slat, slng], { icon: startIcon }).addTo(map).bindPopup('<strong>{{ __("place.route_gps") }}</strong>', { closeButton: false });
                routingControl = L.Routing.control({
                    waypoints: [L.latLng(slat, slng), L.latLng(DEST_LAT, DEST_LNG)],
                    router: L.Routing.osrmv1({ serviceUrl: 'https://router.project-osrm.org/route/v1', profile: 'driving' }),
                    lineOptions: { styles: [{ color: '#1565C0', weight: 10, opacity: 0.25 }, { color: '#1565C0', weight: 6, opacity: 0.85 }] },
                    addWaypoints: false, draggableWaypoints: false, fitSelectedRoutes: false,
                    showAlternatives: false, show: false, createMarker: function () { return null; },
                }).addTo(map);
                routingControl.on('routesfound', function (e) {
                    var route = e.routes[0];
                    if (route) { routeDistance.textContent = formatDistance(route.summary.totalDistance); routeDuration.textContent = formatDuration(route.summary.totalTime); routeSummary.classList.add('show'); map.fitBounds(route.bounds, { padding: [40, 40], maxZoom: 15 }); }
                });
                routeDistance.textContent = LANG.calculating; routeDuration.textContent = LANG.calculating; routeSummary.classList.add('show');
            }

            btnSetStart.addEventListener('click', function () {
                isSelectingStart = !isSelectingStart;
                btnSetStart.classList.toggle('btn-success', !isSelectingStart);
                btnSetStart.classList.toggle('btn-warning', isSelectingStart);
                var span = btnSetStart.querySelector('span'), icon = btnSetStart.querySelector('i');
                if (isSelectingStart) { span.textContent = '{{ __("place.route_pick") }}...'; icon.className = 'fa-solid fa-magnifying-glass-location'; clickHint.classList.add('show'); map.getContainer().style.cursor = 'crosshair'; }
                else { span.textContent = '{{ __("place.route_pick") }}'; icon.className = 'fa-solid fa-map-pin'; clickHint.classList.remove('show'); map.getContainer().style.cursor = ''; }
            });

            map.on('click', function (e) {
                if (!isSelectingStart) return;
                isSelectingStart = false; btnSetStart.classList.remove('btn-warning'); btnSetStart.classList.add('btn-success');
                btnSetStart.querySelector('span').textContent = '{{ __("place.route_pick") }}';
                btnSetStart.querySelector('i').className = 'fa-solid fa-map-pin';
                clickHint.classList.remove('show'); map.getContainer().style.cursor = '';
                calculateRoute(e.latlng.lat, e.latlng.lng);
            });

            btnMyLocation.addEventListener('click', function () {
                if (!navigator.geolocation) { alert(LANG.gps_unsupported); return; }
                var span = btnMyLocation.querySelector('span'), icon = btnMyLocation.querySelector('i');
                btnMyLocation.disabled = true; span.textContent = LANG.tracking; icon.className = 'fa-solid fa-spinner fa-spin';
                navigator.geolocation.getCurrentPosition(function (pos) {
                    btnMyLocation.disabled = false; span.textContent = '{{ __("place.route_gps") }}'; icon.className = 'fa-solid fa-location-crosshairs';
                    calculateRoute(pos.coords.latitude, pos.coords.longitude);
                }, function () {
                    btnMyLocation.disabled = false; span.textContent = '{{ __("place.route_gps") }}'; icon.className = 'fa-solid fa-location-crosshairs';
                    alert(LANG.gps_error);
                }, { enableHighAccuracy: true, timeout: 8000 });
            });

            setTimeout(function () { map.invalidateSize(); }, 300);
            window.addEventListener('pageshow', function (e) { if (e.persisted) map.invalidateSize(); });
        })();
    </script>

    <script>
        var ratingStars = document.querySelectorAll('#rating-stars .rating-star-interactive');
        function updateRating(el) {
            var val = parseInt(el.value, 10);
            ratingStars.forEach(function (star, index) {
                var icon = star.querySelector('i');
                icon.className = index < val ? 'fa-solid fa-star text-warning' : 'fa-regular fa-star text-muted';
            });
        }
        ratingStars.forEach(function (star, index) {
            star.addEventListener('mouseenter', function () {
                ratingStars.forEach(function (s, i) { s.querySelector('i').style.color = i <= index ? '#f59e0b' : '#cbd5e1'; });
            });
            star.addEventListener('mouseleave', function () {
                var checked = document.querySelector('#rating-stars input[type="radio"]:checked');
                var val = checked ? parseInt(checked.value, 10) : 0;
                ratingStars.forEach(function (s, i) {
                    var icon = s.querySelector('i');
                    icon.className = i < val ? 'fa-solid fa-star text-warning' : 'fa-regular fa-star text-muted';
                    icon.style.color = '';
                });
            });
        });
        (function () { var c = document.querySelector('#rating-stars input[type="radio"]:checked'); if (c) updateRating(c); })();
    </script>
    {{-- Hero Slider JS --}}
    <script>
        (function () {
            var slides = document.querySelectorAll('#heroSlider .hero-slide');
            var dots = document.querySelectorAll('#heroDots .hero-dot');
            var current = 0;
            var timer = null;
            var total = slides.length;
            if (total < 2) return;
            function goTo(idx) {
                slides.forEach(function (s, i) {
                    s.classList.toggle('active', i === idx);
                });
                dots.forEach(function (d, i) {
                    d.classList.toggle('active', i === idx);
                });
                current = idx;
            }
            function next() { goTo((current + 1) % total); }
            function prev() { goTo((current - 1 + total) % total); }
            function resetTimer() {
                if (timer) clearInterval(timer);
                timer = setInterval(next, 5000);
            }
            window.heroSlide = function (dir) {
                dir > 0 ? next() : prev();
                resetTimer();
            };
            window.heroGoTo = function (idx) {
                goTo(idx);
                resetTimer();
            };
            // Touch/swipe support
            var touchStartX = 0;
            var banner = document.getElementById('heroBanner');
            banner.addEventListener('touchstart', function (e) {
                touchStartX = e.changedTouches[0].screenX;
            }, { passive: true });
            banner.addEventListener('touchend', function (e) {
                var diff = touchStartX - e.changedTouches[0].screenX;
                if (Math.abs(diff) > 50) {
                    diff > 0 ? next() : prev();
                    resetTimer();
                }
            }, { passive: true });
            resetTimer();
        })();
    </script>
</body>
</html>
