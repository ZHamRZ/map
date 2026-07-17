<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('seo.transportation_title') }}</title>
    <meta name="description" content="{{ __('seo.transportation_desc') }}">
    <meta property="og:title" content="{{ __('seo.transportation_title') }}">
    <meta property="og:description" content="{{ __('seo.transportation_desc') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary_large_image">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f4fcf6; color: #2b3d32; }
        .navbar { box-shadow: 0 2px 12px rgba(0,0,0,0.1); z-index: 2000; }
        .page-header {
            background: linear-gradient(135deg, #0b2e1b 0%, #1b5e20 50%, #2e7d32 100%);
            color: white; padding: 50px 0 40px; text-align: center;
        }
        .page-header h1 { font-weight: 800; font-size: 2rem; }
        .transport-card {
            background: white; border-radius: 16px; padding: 24px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.04); border: 1px solid rgba(0,0,0,0.04);
            height: 100%; transition: all 0.3s ease;
        }
        .transport-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
        .transport-card .icon-circle {
            width: 56px; height: 56px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; color: white; flex-shrink: 0;
        }
        #map-transport { height: 400px; border-radius: 16px; z-index: 1; }
    </style>
</head>
<body>
    @include('partials.public-navbar')

    <div class="page-header">
        <div class="container">
            <h1><i class="fa-solid fa-road me-2"></i>{{ __('transportation.title') }}</h1>
            <p class="opacity-90 mt-2">{{ __('transportation.subtitle') }}</p>
        </div>
    </div>

    <div class="container py-4">
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="transport-card">
                    <div class="d-flex gap-3 align-items-start mb-3">
                        <div class="icon-circle" style="background:linear-gradient(135deg,#1565c0,#42a5f5);">
                            <i class="fa-solid fa-plane"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold">{{ __('transportation.by_air') }}</h5>
                            <p class="text-muted small mb-0">{{ __('transportation.air_desc') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="transport-card">
                    <div class="d-flex gap-3 align-items-start mb-3">
                        <div class="icon-circle" style="background:linear-gradient(135deg,#2e7d32,#66bb6a);">
                            <i class="fa-solid fa-car"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold">{{ __('transportation.by_land') }}</h5>
                            <p class="text-muted small mb-0">{{ __('transportation.land_desc') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="transport-card mb-4">
            <h5 class="fw-bold mb-3"><i class="fa-solid fa-map me-2 text-success"></i>{{ __('transportation.map_title') }}</h5>
            <div id="map-transport"></div>
            <p class="text-muted small mt-2 mb-0 text-center">{{ __('transportation.coordinates') }}</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        (function () {
            var map = L.map('map-transport', {
                center: [-8.6248, 116.1882],
                zoom: 13,
                zoomControl: true,
            });

            L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: '&copy; Esri', maxZoom: 19,
            }).addTo(map);

            L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
                attribution: '&copy; Esri', maxZoom: 19,
            }).addTo(map);

            fetch('/api/boundary', {
                headers: {
                    'Accept': 'application/json',
                    'X-Tunnel-Skip-AntiPhishing-Page': 'true'
                }
            })
                .then(function (r) { return r.json(); })
                .then(function (geo) {
                    L.geoJSON(geo, {
                        style: { color: '#4caf50', weight: 3, opacity: 0.9, fillColor: '#4caf50', fillOpacity: 0.04 },
                    }).addTo(map).bindPopup('<strong>Desa Bilebante</strong>');
                })
                .catch(function () {});

            var airportIcon = L.divIcon({
                className: '',
                html: '<div style="position:relative;"><svg width="36" height="36" viewBox="0 0 36 36"><rect x="2" y="2" width="32" height="32" rx="10" fill="#1565c0" stroke="#fff" stroke-width="3" filter="url(#as)"/><defs><filter id="as"><feDropShadow dx="0" dy="2" stdDeviation="3" flood-color="rgba(0,0,0,0.3)"/></filter></defs><circle cx="18" cy="18" r="11" fill="#fff"/></svg><div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;pointer-events:none;color:#1565c0;font-size:11px;"><i class="fa-solid fa-plane"></i></div></div>',
                iconSize: [36, 36],
                iconAnchor: [18, 18],
            });

            L.marker([-8.757, 116.276], { icon: airportIcon })
                .addTo(map)
                .bindPopup('<strong>Bandara Internasional Lombok (LOP)</strong><br><small>Praya, Lombok Tengah</small>');

            var bilebanteIcon = L.divIcon({
                className: '',
                html: '<div style="position:relative;"><svg width="32" height="32" viewBox="0 0 32 32"><rect x="2" y="2" width="28" height="28" rx="9" fill="#2e7d32" stroke="#fff" stroke-width="3" filter="url(#bs)"/><defs><filter id="bs"><feDropShadow dx="0" dy="2" stdDeviation="3" flood-color="rgba(0,0,0,0.3)"/></filter></defs><circle cx="16" cy="16" r="10" fill="#fff"/></svg><div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;pointer-events:none;color:#2e7d32;font-size:9px;"><i class="fa-solid fa-location-dot"></i></div></div>',
                iconSize: [32, 32],
                iconAnchor: [16, 16],
            });

            L.marker([-8.6248, 116.1882], { icon: bilebanteIcon })
                .addTo(map)
                .bindPopup('<strong>Desa Bilebante</strong>');

            setTimeout(function () { map.invalidateSize(); }, 300);
        })();
    </script>
</body>
</html>
