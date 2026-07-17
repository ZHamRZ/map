<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('seo.events_title') }}</title>
    <meta name="description" content="{{ __('seo.events_desc') }}">
    <meta property="og:title" content="{{ __('seo.events_title') }}">
    <meta property="og:description" content="{{ __('seo.events_desc') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary_large_image">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f4fcf6; color: #2b3d32; }
        .navbar { box-shadow: 0 2px 12px rgba(0,0,0,0.1); z-index: 2000; }
        .page-header {
            background: linear-gradient(135deg, #7b2d8e 0%, #9b59b6 50%, #c084d2 100%);
            color: white; padding: 50px 0 40px; text-align: center;
        }
        .page-header h1 { font-weight: 800; font-size: 2rem; }
        .event-card {
            background: white; border-radius: 16px; overflow: hidden;
            box-shadow: 0 4px 16px rgba(0,0,0,0.04); border: 1px solid rgba(0,0,0,0.04);
            transition: all 0.3s ease; height: 100%;
        }
        .event-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(123,45,142,0.1); }
        .event-card .card-body { padding: 20px; }
        .event-date-badge {
            background: linear-gradient(135deg, #7b2d8e, #9b59b6);
            color: white; border-radius: 12px; padding: 10px 14px; text-align: center;
            min-width: 60px; display: inline-block;
        }
        .event-date-badge .day { font-size: 1.5rem; font-weight: 800; line-height: 1; }
        .event-date-badge .month { font-size: 0.75rem; text-transform: uppercase; font-weight: 700; }
        .event-time { font-size: 0.8rem; color: #7b2d8e; font-weight: 600; }
        .event-coords { font-size: 0.75rem; color: #999; }
    </style>
</head>
<body>
    @include('partials.public-navbar')

    <div class="page-header">
        <div class="container">
            <h1><i class="fa-solid fa-calendar-days me-2"></i>
                @if ($category === 'desa')
                    {{ __('event.village_title') }}
                @elseif ($category === 'budaya')
                    {{ __('event.culture_title') }}
                @else
                    {{ __('event.title') }}
                @endif
            </h1>
            <p class="opacity-90 mt-2">
                @if ($category === 'desa')
                    {{ __('event.village_subtitle') }}
                @elseif ($category === 'budaya')
                    {{ __('event.culture_subtitle') }}
                @else
                    {{ __('event.subtitle') }}
                @endif
            </p>
        </div>
    </div>

    <div class="container py-4">
        @if ($upcoming->count() > 0)
            <h5 class="fw-bold mb-3"><i class="fa-solid fa-clock text-primary me-2"></i>{{ __('event.upcoming') }}</h5>
            <div class="row g-3 mb-5">
                @foreach ($upcoming as $event)
                <div class="col-md-6 col-lg-4">
                    <div class="event-card">
                        @if ($event->image_url)
                            <img src="{{ $event->image_url }}" alt="{{ $event->title }}" class="w-100" style="height:160px;object-fit:cover;">
                        @endif
                        <div class="card-body">
                            <div class="d-flex gap-3 align-items-start mb-3">
                                <div class="event-date-badge flex-shrink-0">
                                    <div class="day">{{ $event->start_date->format('d') }}</div>
                                    <div class="month">{{ $event->start_date->format('M') }}</div>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1">{{ $event->title }}</h5>
                                    @if ($event->start_time)
                                        <div class="event-time mb-1">
                                            <i class="fa-regular fa-clock me-1"></i>
                                            {{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }}
                                            @if ($event->end_time)
                                                – {{ \Carbon\Carbon::parse($event->end_time)->format('H:i') }}
                                            @endif
                                        </div>
                                    @endif
                                    @if ($event->location)
                                        <small class="text-muted"><i class="fa-solid fa-location-dot me-1"></i>{{ $event->location }}</small>
                                    @endif
                                    @if ($event->latitude && $event->longitude)
                                        <br><small class="event-coords">
                                            <i class="fa-solid fa-map-pin me-1"></i>
                                            {{ number_format($event->latitude, 4) }}, {{ number_format($event->longitude, 4) }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                            @if ($event->category)
                                <span class="badge bg-purple">{{ $event->category }}</span>
                            @endif
                            @if ($event->description)
                                <p class="small text-muted mt-2">{{ Str::limit($event->description, 120) }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif

        @if ($past->count() > 0)
            <h5 class="fw-bold mb-3"><i class="fa-solid fa-clock-rotate-left text-muted me-2"></i>{{ __('event.past') }}</h5>
            <div class="row g-3">
                @foreach ($past as $event)
                <div class="col-md-4 col-lg-3">
                    <div class="event-card opacity-75">
                        <div class="card-body">
                            <div class="d-flex gap-2 align-items-start mb-2">
                                <div class="event-date-badge flex-shrink-0" style="background:linear-gradient(135deg,#666,#999);">
                                    <div class="day" style="font-size:1.2rem;">{{ $event->start_date->format('d') }}</div>
                                    <div class="month">{{ $event->start_date->format('M') }}</div>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">{{ $event->title }}</h6>
                                    <small class="text-muted">{{ $event->location }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif

        @if ($upcoming->isEmpty() && $past->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="fa-regular fa-calendar-xmark d-block fs-1 mb-3 opacity-50"></i>
                <p>{{ __('event.no_events') }}</p>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>.bg-purple { background: #9b59b6 !important; color: white; }</style>
</body>
</html>
