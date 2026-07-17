<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('seo.home_title') }}</title>
    <meta name="description" content="{{ __('seo.home_desc') }}">
    <meta property="og:title" content="{{ __('seo.home_title') }}">
    <meta property="og:description" content="{{ __('seo.home_desc') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary_large_image">
    @verbatim
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "TouristAttraction",
        "name": "Desa Bilebante",
        "description": "Desa Wisata Hijau Bilebante, Lombok Tengah, NTB",
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "Bilebante",
            "addressRegion": "NTB",
            "addressCountry": "ID"
        },
        "geo": {
            "@type": "GeoCoordinates",
            "latitude": -8.6248,
            "longitude": 116.1882
        }
    }
    </script>
    @endverbatim

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Leaflet --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    {{-- Leaflet Fullscreen --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-fullscreen@1.0.2/dist/leaflet.fullscreen.css" />
    {{-- Leaflet Control Geocoder --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-control-geocoder@2.4.0/dist/Control.Geocoder.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />

    {{-- Font Awesome 6 (gratis) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700&family=Nunito:wght@400;600;700;800&display=swap');

        /* ====================================================
               RESET
            ==================================================== */
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            overflow-x: hidden;
        }

        body {
            height: 100%;
            width: 100%;
            font-family: 'Poppins', 'Inter', 'Nunito', 'Segoe UI', sans-serif;
            overflow-x: hidden;
            position: relative;
        }

        /* ====================================================
               NAVBAR
            ==================================================== */
        .navbar {
            z-index: 2000;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.15);
        }

        .navbar-brand {
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        /* ====================================================
               MAP
            ==================================================== */
        #map {
            height: calc(100vh - 56px);
            width: 100%;
            z-index: 1;
            overflow: hidden;
        }

        /* ====================================================
               FILTER PANEL
            ==================================================== */
        #filter-panel {
            position: absolute;
            top: 70px;
            right: 12px;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            padding: 18px;
            min-width: 240px;
            max-width: 270px;
            max-height: calc(100vh - 120px);
            overflow-y: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
            transition: transform 0.3s ease, opacity 0.3s ease, visibility 0.3s ease;
            font-family: 'Inter', sans-serif;
        }

        #filter-panel::-webkit-scrollbar {
            display: none;
        }

        #filter-panel.hidden {
            transform: translateX(60px);
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        /* ====================================================
               SEARCH (in-filter)
            ==================================================== */
        .search-container {
            position: relative;
            margin-bottom: 10px;
        }

        #search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            z-index: 1050;
            max-height: 240px;
            overflow-y: auto;
            display: none;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
            background: #fff;
        }

        #search-results.show {
            display: block;
        }

        #search-results .list-group-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 14px;
            font-size: 13px;
            cursor: pointer;
            border: none;
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.1s;
        }
        #search-results .list-group-item:last-child {
            border-bottom: none;
        }

        #search-results .list-group-item:hover {
            background: #f0fdf4;
        }

        .search-icon-wrap {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            border-radius: 6px;
            flex-shrink: 0;
        }
        .search-icon-wrap svg {
            display: block;
        }
        .search-name {
            flex: 1;
            font-weight: 500;
            color: #1a202c;
        }
        .search-cat {
            color: #94a3b8;
            font-size: 11px;
            font-weight: 500;
        }

        #filter-panel h6 {
            font-weight: 700;
            font-size: 13px;
            margin-bottom: 10px;
            color: #2E7D32;
            border-bottom: 2px solid #2E7D32;
            padding-bottom: 6px;
            font-family: 'Poppins', sans-serif;
        }

        .filter-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 8px;
            cursor: pointer;
            font-size: 13px;
            transition: background 0.15s, transform 0.15s;
            border-radius: 10px;
        }

        .filter-item:hover {
            background: rgba(46, 125, 50, 0.04);
            transform: translateX(2px);
        }

        .filter-item input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #2E7D32;
            cursor: pointer;
            border-radius: 4px;
        }

        .filter-icon-wrap {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 26px;
            height: 26px;
            border-radius: 8px;
            flex-shrink: 0;
        }
        .filter-icon-wrap svg {
            display: block;
        }
        .filter-label {
            flex: 1;
            font-weight: 500;
            color: #334155;
        }

        .filter-count {
            margin-left: auto;
            background: #e8f5e9;
            border-radius: 12px;
            padding: 0 9px;
            font-size: 11px;
            font-weight: 700;
            color: #2E7D32;
            min-width: 22px;
            text-align: center;
        }


        /* ====================================================
               ANALYSIS PANEL
            ==================================================== */
        #analysis-panel {
            position: absolute;
            top: 70px;
            right: 12px;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
            padding: 18px;
            min-width: 250px;
            max-height: calc(100vh - 120px);
            overflow-y: auto;
            transition: transform 0.3s ease, opacity 0.3s ease, visibility 0.3s ease;
            margin-top: 10px;
        }

        #analysis-panel.hidden {
            transform: translateX(50px);
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        #analysis-panel .analysis-header h6 {
            font-weight: 700;
            font-size: 13px;
            color: #7b2d8e;
        }

        .analysis-section {
            margin-bottom: 12px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .analysis-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .analysis-section label {
            font-size: 12px;
            font-weight: 600;
            color: #444;
        }

        .analysis-section .small {
            font-size: 11px;
            color: #888;
        }

        .analysis-section input[type="range"] {
            width: 100%;
            accent-color: #7b2d8e;
        }


        .analysis-btn {
            width: 100%;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 6px;
        }

        /* ====================================================
               BOUNDARY POPUP
            ==================================================== */
        .village-popup .leaflet-popup-content-wrapper {
            border-radius: 12px;
            padding: 4px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .village-popup .leaflet-popup-content {
            margin: 14px 18px;
            font-size: 14px;
            line-height: 1.6;
        }

        .village-popup h3 {
            font-size: 18px;
            font-weight: 700;
            color: #1a5e2a;
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 2px solid #4caf50;
        }

        .village-popup .info-row {
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
            gap: 16px;
        }

        .village-popup .info-label {
            font-weight: 600;
            color: #555;
            min-width: 90px;
        }

        .village-popup .info-value {
            color: #222;
            text-align: right;
        }

        .village-popup .source-info {
            margin-top: 10px;
            padding-top: 8px;
            border-top: 1px solid #eee;
            font-size: 11px;
            color: #999;
            text-align: center;
        }




        /* ====================================================
               INFO TITLE
            ==================================================== */
        .info-title {
            background: rgba(255, 255, 255, 0.95);
            padding: 8px 16px;
            border-radius: 8px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.2);
            font-size: 14px;
            font-weight: 700;
            color: #1a5e2a;
            display: flex;
            align-items: center;
            gap: 8px;
            backdrop-filter: blur(4px);
        }

        .info-title .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #4caf50;
            display: inline-block;
            animation: pulse-dot 2s infinite;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }

        /* ====================================================
               LOADING
            ==================================================== */
        #loading {
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: opacity 0.5s ease;
        }

        #loading.hidden {
            opacity: 0;
            pointer-events: none;
        }

        .spinner {
            width: 48px;
            height: 48px;
            border: 4px solid #e0e0e0;
            border-top-color: #4caf50;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        #loading p {
            margin-top: 16px;
            color: #555;
            font-size: 14px;
        }

        /* ====================================================
               ERROR BANNER
            ==================================================== */
        #error-banner {
            position: fixed;
            top: 70px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10000;
            background: #d32f2f;
            color: white;
            padding: 10px 22px;
            border-radius: 8px;
            box-shadow: 0 4px 16px rgba(211, 47, 47, 0.3);
            font-weight: 600;
            font-size: 14px;
            display: none;
            align-items: center;
            gap: 10px;
        }

        #error-banner.show {
            display: flex;
        }

        /* ====================================================
               CENTROID MARKER
            ==================================================== */
        .centroid-marker {
            background: #4caf50;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .centroid-marker:hover {
            transform: scale(1.15);
        }

        .centroid-marker::after {
            content: "\2302";
            color: white;
            font-weight: bold;
        }

        /* ====================================================
               MODERN MAP PIN MARKER
            ==================================================== */
        .mapi-pin { display: flex; align-items: center; justify-content: center; }
        .mapi-pin > svg {
            display: block;
            transition: transform 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
        }
        .mapi-pin > svg:hover {
            transform: scale(1.08);
            z-index: 10000 !important;
        }

        @keyframes markerDrop {
            0%   { transform: translateY(-60px) scale(0.4); opacity: 0; }
            60%  { transform: translateY(6px) scale(1.05); opacity: 1; }
            100% { transform: translateY(0) scale(1); opacity: 1; }
        }

        /* Tooltip CSS dihapus — digantikan hover info card di luar map */

        /* ====================================================
               POPUP — PREMIUM AIRBNB-STYLE CARD
            ==================================================== */
        .leaflet-popup-custom .leaflet-popup-content-wrapper {
            border-radius: 16px;
            padding: 0;
            box-shadow: 0 8px 32px rgba(0,0,0,0.18);
            overflow: hidden;
            background: #FFFFFF;
        }
        .leaflet-popup-custom .leaflet-popup-content {
            margin: 0;
            padding: 0;
            width: 290px !important;
            font-family: 'Poppins', 'Inter', sans-serif;
        }
        .leaflet-popup-custom .leaflet-popup-tip {
            box-shadow: none;
        }
        .popup-card-img {
            width: 100%;
            height: 155px;
            object-fit: cover;
            display: block;
            background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
        }
        .popup-card-body {
            padding: 14px 16px 16px;
        }
        .popup-card-body .popup-category {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 0.6rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #FFFFFF;
            padding: 3px 10px 3px 8px;
            border-radius: 20px;
            margin-bottom: 8px;
        }
        .popup-card-body h5 {
            font-weight: 700;
            font-size: 1rem;
            margin: 0 0 4px;
            color: #1a202c;
            line-height: 1.3;
            font-family: 'Poppins', sans-serif;
        }
        .popup-card-body .popup-desc {
            font-size: 0.8rem;
            color: #64748b;
            line-height: 1.5;
            margin-bottom: 10px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .popup-card-body .popup-rating {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 0.78rem;
            color: #D4AF37;
            margin-bottom: 10px;
            font-weight: 600;
        }
        .popup-card-actions {
            display: flex;
            gap: 8px;
        }
        .popup-card-actions .btn-popup {
            flex: 1;
            padding: 9px 12px;
            border-radius: 12px;
            font-size: 0.78rem;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
            font-family: 'Inter', sans-serif;
        }
        .popup-card-actions .btn-popup-detail {
            background: #2E7D32;
            color: #fff;
            box-shadow: 0 2px 8px rgba(46,125,50,0.25);
        }
        .popup-card-actions .btn-popup-detail:hover {
            background: #1b5e20;
            box-shadow: 0 4px 16px rgba(46,125,50,0.35);
            transform: translateY(-1px);
        }
        .popup-card-actions .btn-popup-route {
            background: #f0fdf4;
            color: #2E7D32;
            border: 1px solid #bbf7d0;
        }
        .popup-card-actions .btn-popup-route:hover {
            background: #dcfce7;
        }
        .popup-card-actions .btn-popup-edit {
            background: #fff3e0;
            color: #E65100;
            flex: 0.5;
        }


        /* ====================================================
               RESPONSIVE
            ==================================================== */
        @media (max-width: 600px) {
            #filter-panel {
                top: 62px;
                right: 8px;
                min-width: 150px;
                padding: 10px 12px;
                font-size: 12px;
            }

            #filter-toggle {
                top: 62px;
                right: 8px;
                padding: 6px 10px;
                font-size: 12px;
            }

            .info-title {
                font-size: 12px;
                padding: 6px 12px;
            }

            .village-popup .leaflet-popup-content {
                margin: 10px 14px;
                font-size: 13px;
            }

            .village-popup h3 {
                font-size: 16px;
            }

            .village-popup .info-row {
                flex-direction: column;
                gap: 2px;
            }

            .village-popup .info-value {
                text-align: left;
            }
        }

        /* ====================================================
               STORYTELLING
            ==================================================== */

        #story-sidebar {
            position: absolute;
            top: 70px;
            left: 12px;
            z-index: 1000;
            width: 330px;
            max-height: calc(100vh - 140px);
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
            display: none;
            flex-direction: column;
            overflow: hidden;
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        #story-sidebar.show {
            display: flex;
        }

        #story-sidebar .story-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 16px 10px;
            border-bottom: 2px solid #7b2d8e;
            flex-shrink: 0;
        }

        #story-sidebar .story-header h6 {
            margin: 0;
            font-weight: 700;
            color: #7b2d8e;
            font-size: 14px;
        }

        #story-sidebar .story-body {
            padding: 12px 16px 16px;
            overflow-y: auto;
            flex: 1;
        }

        .story-paragraph {
            background: white;
            border-radius: 10px;
            padding: 14px;
            margin-bottom: 10px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
            cursor: pointer;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .story-paragraph:hover {
            box-shadow: 0 2px 12px rgba(123,45,142,0.12);
            border-left-color: #7b2d8e;
            transform: translateX(2px);
        }

        .story-paragraph .story-title {
            font-weight: 700;
            font-size: 0.9rem;
            color: #333;
            margin-bottom: 4px;
        }

        .story-paragraph .story-text {
            font-size: 0.82rem;
            color: #666;
            line-height: 1.6;
        }

        .story-paragraph .story-icon {
            display: inline-block;
            margin-right: 6px;
            font-size: 0.85rem;
        }

        /* ====================================================
               ADMIN FLOATING PANEL
            ==================================================== */
        .admin-panel {
            position: absolute;
            top: 70px;
            right: 12px;
            z-index: 990;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.35);
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08), 0 1px 4px rgba(0, 0, 0, 0.04);
            padding: 18px 20px;
            min-width: 240px;
            max-width: 280px;
            display: none;
        }

        .admin-panel.show {
            display: block;
        }

        .admin-panel .admin-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #e8f5e9;
            color: #1a5e2a;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 20px;
            margin-bottom: 12px;
        }

        .admin-panel .admin-badge .dot-online {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #2ecc71;
            display: inline-block;
            animation: pulse-online 1.5s infinite;
        }

        @keyframes pulse-online {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }

        .admin-panel .admin-title {
            font-weight: 700;
            font-size: 0.9rem;
            color: #333;
            margin-bottom: 12px;
        }

        .admin-panel .admin-btn {
            width: 100%;
            padding: 8px 14px;
            font-size: 0.82rem;
            font-weight: 600;
            border-radius: 8px;
            margin-bottom: 6px;
            transition: all 0.2s;
        }

        .admin-panel .admin-btn:last-child {
            margin-bottom: 0;
        }

        .admin-panel .admin-btn:hover {
            transform: translateX(2px);
        }

        .admin-panel .future-slot {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px dashed #ddd;
        }

        .admin-panel .future-slot .slot-label {
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #bbb;
            font-weight: 600;
        }

        @media (max-width: 600px) {
            #story-sidebar {
                width: calc(100vw - 24px);
                left: 12px;
                top: 62px;
                max-height: calc(100vh - 110px);
            }
        }

        /* ====================================================
           GOOGLE MAPS-STYLE ROUTE PANEL
        ==================================================== */
        #route-panel {
            position: absolute;
            top: 70px;
            left: 12px;
            z-index: 1000;
            width: 372px;
            max-height: calc(100vh - 90px);
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            transition: transform 0.25s ease, opacity 0.25s ease;
        }

        #route-panel.hidden {
            transform: translateX(-420px);
            opacity: 0;
            pointer-events: none;
        }

        #route-panel .rp-header {
            padding: 16px 20px 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #e8eaed;
            flex-shrink: 0;
        }

        #route-panel .rp-header h5 {
            font-size: 1rem;
            font-weight: 500;
            color: #202124;
            margin: 0;
        }

        #route-panel .rp-header .rp-close {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: none;
            background: transparent;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #5f6368;
            font-size: 1.1rem;
            transition: background 0.15s;
        }

        #route-panel .rp-header .rp-close:hover { background: #f1f3f4; }

        #route-panel .rp-body {
            padding: 12px 20px 16px;
            overflow-y: auto;
            flex: 1;
        }

        .rp-input-wrap {
            position: relative;
            margin-bottom: 6px;
        }

        .rp-input-wrap .rp-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 2;
            font-size: 0.8rem;
            width: 20px;
            text-align: center;
            pointer-events: none;
        }

        .rp-input-wrap input {
            width: 100%;
            padding: 10px 36px 10px 38px;
            font-size: 0.88rem;
            border: 1px solid #dadce0;
            background: #fff;
            transition: all 0.15s;
            outline: none;
        }

        .rp-input-wrap input:focus {
            border-color: #1a73e8;
            box-shadow: inset 0 0 0 1px #1a73e8;
        }

        .rp-input-wrap input.rp-origin {
            border-radius: 8px 8px 0 0;
            border-bottom-color: transparent;
        }

        .rp-input-wrap input.rp-dest {
            border-radius: 0 0 8px 8px;
        }

        .rp-input-wrap .rp-swap {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%) rotate(90deg);
            z-index: 3;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: 1px solid #dadce0;
            background: #fff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            color: #5f6368;
            transition: all 0.15s;
            box-shadow: 0 1px 2px rgba(0,0,0,0.06);
        }

        .rp-input-wrap .rp-swap:hover { background: #f8f9fa; color: #1a73e8; }

        .rp-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            z-index: 10;
            background: #fff;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
            max-height: 180px;
            overflow-y: auto;
            display: none;
        }

        .rp-suggestions.show { display: block; }

        .rp-suggestion-item {
            padding: 10px 14px;
            font-size: 0.82rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: background 0.1s;
        }

        .rp-suggestion-item:hover { background: #f1f3f4; }
        .rp-suggestion-item .rp-sug-icon { color: #5f6368; font-size: 0.75rem; width: 18px; text-align: center; }

        .rp-gps-row {
            display: flex;
            gap: 8px;
            margin: 10px 0;
        }

        .rp-gps-row button {
            flex: 1;
            padding: 8px;
            font-size: 0.78rem;
            font-weight: 500;
            border-radius: 24px;
            border: 1px solid #dadce0;
            background: #fff;
            cursor: pointer;
            transition: all 0.15s;
            color: #3c4043;
        }

        .rp-gps-row button:hover { background: #f8f9fa; border-color: #1a73e8; color: #1a73e8; }
        .rp-gps-row button:disabled { opacity: 0.5; cursor: default; }

        .rp-calc-btn {
            width: 100%;
            padding: 11px;
            font-size: 0.88rem;
            font-weight: 500;
            border-radius: 24px;
            border: none;
            background: #1a73e8;
            color: #fff;
            cursor: pointer;
            transition: background 0.15s;
            margin-top: 2px;
        }

        .rp-calc-btn:hover { background: #1765cc; }
        .rp-calc-btn:disabled { background: #c4c7c5; cursor: default; }

        .rp-result {
            margin-top: 14px;
            border-top: 1px solid #e8eaed;
            padding-top: 14px;
            display: none;
        }

        .rp-result.show { display: block; }

        .rp-result .rp-summary {
            display: flex;
            gap: 16px;
            margin-bottom: 10px;
        }

        .rp-result .rp-summary .rp-stat {
            flex: 1;
        }

        .rp-result .rp-summary .rp-stat .rp-stat-label {
            font-size: 0.68rem;
            color: #5f6368;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            font-weight: 500;
        }

        .rp-result .rp-summary .rp-stat .rp-stat-value {
            font-size: 1.2rem;
            font-weight: 400;
            color: #202124;
        }

        .rp-result .rp-summary .rp-stat .rp-stat-value.small { font-size: 0.95rem; }

        .rp-progress {
            margin-top: 10px;
            display: none;
        }

        .rp-progress.show { display: block; }

        .rp-progress-bar {
            height: 4px;
            background: #e8eaed;
            border-radius: 4px;
            overflow: hidden;
        }

        .rp-progress-bar .rp-progress-fill {
            height: 100%;
            background: #1a73e8;
            border-radius: 4px;
            width: 0%;
            transition: width 1s ease;
        }

        .rp-progress-text {
            display: flex;
            justify-content: space-between;
            font-size: 0.7rem;
            color: #5f6368;
            margin-top: 4px;
        }

        .rp-track-status {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.78rem;
            font-weight: 500;
            padding: 6px 12px;
            border-radius: 8px;
            margin-top: 8px;
            justify-content: center;
        }

        .rp-track-status.active { background: #e8f5e9; color: #1b5e20; }
        .rp-track-status.inactive { background: #fef7e0; color: #e65100; }

        .rp-track-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
        }

        .rp-track-dot.active { background: #1a73e8; animation: rp-pulse 1.5s infinite; }
        .rp-track-dot.inactive { background: #ff9800; }

        @keyframes rp-pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.3); }
        }

        .rp-track-btn {
            width: 100%;
            padding: 8px;
            font-size: 0.78rem;
            font-weight: 500;
            border-radius: 24px;
            border: 1px solid #dadce0;
            background: #fff;
            cursor: pointer;
            transition: all 0.15s;
            margin-top: 6px;
            color: #3c4043;
        }

        .rp-track-btn:hover { background: #f8f9fa; border-color: #1a73e8; color: #1a73e8; }

        .rp-turns {
            margin-top: 10px;
            max-height: 180px;
            overflow-y: auto;
            display: none;
        }

        .rp-turns.show { display: block; }

        .rp-turn-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #f1f3f4;
            font-size: 0.8rem;
        }

        .rp-turn-item .rp-turn-icon {
            width: 24px;
            height: 24px;
            background: #f1f3f4;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #5f6368;
            flex-shrink: 0;
            font-size: 0.65rem;
        }

        .rp-turn-item .rp-turn-text { line-height: 1.4; color: #3c4043; }
        .rp-turn-item .rp-turn-dist {
            font-size: 0.7rem;
            color: #5f6368;
            font-weight: 500;
            white-space: nowrap;
            margin-left: auto;
            flex-shrink: 0;
        }

        .rp-live-marker {
            width: 20px;
            height: 20px;
            background: #1a73e8;
            border: 3px solid #fff;
            border-radius: 50%;
            box-shadow: 0 2px 8px rgba(26,115,232,0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
            color: #fff;
        }

        .rp-live-marker::after {
            content: '';
            position: absolute;
            width: 36px;
            height: 36px;
            background: rgba(26,115,232,0.15);
            border-radius: 50%;
            animation: rp-ring 2s infinite;
        }

        @keyframes rp-ring {
            0% { transform: scale(0.8); opacity: 0.8; }
            100% { transform: scale(1.8); opacity: 0; }
        }

        /* First #rp-trigger-btn definition removed — see glassmorphism definition below */

        @media (max-width: 768px) {
            #route-panel {
                width: calc(100vw - 24px);
                left: 12px;
                top: 62px;
                max-height: calc(100vh - 80px);
                border-radius: 12px !important;
            }
            #route-panel.hidden { transform: translateY(-110%); opacity: 0; }
        }

        /* ══════════════════════════════════════════════════════
           MATERIAL DESIGN 3 — FLOATING CARD SYSTEM
           ══════════════════════════════════════════════════════ */

        /* ── Glassmorphism card mixin ── */
        .glass-card {
            background: rgba(255, 255, 255, 0.92) !important;
            backdrop-filter: blur(20px) !important;
            -webkit-backdrop-filter: blur(20px) !important;
            border: 1px solid rgba(255, 255, 255, 0.35) !important;
            border-radius: 16px !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08), 0 1px 4px rgba(0, 0, 0, 0.04) !important;
            transition: transform 0.2s ease, box-shadow 0.2s ease !important;
        }
        .glass-card:hover {
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12), 0 2px 8px rgba(0, 0, 0, 0.06) !important;
        }

        /* ── Search bar — Premium rounded pill ── */
        .leaflet-control-geocoder {
            margin-top: 24px !important;
            margin-left: 12px !important;
            width: 340px !important;
            max-width: calc(100vw - 40px) !important;
            border-radius: 28px !important;
            background: rgba(255, 255, 255, 0.94) !important;
            backdrop-filter: blur(24px) !important;
            -webkit-backdrop-filter: blur(24px) !important;
            border: 1px solid rgba(255, 255, 255, 0.5) !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08), 0 1px 4px rgba(0, 0, 0, 0.04) !important;
            font-family: 'Poppins', sans-serif !important;
            transition: box-shadow 0.2s ease, transform 0.2s ease !important;
            overflow: hidden;
        }
        .leaflet-control-geocoder:focus-within {
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.14) !important;
            transform: translateY(-1px);
        }
        .leaflet-control-geocoder-icon {
            border-radius: 28px 0 0 28px !important;
            width: 20px !important;
            height: 20px !important;
            margin: 12px 0 12px 16px !important;
        }
        .leaflet-control-geocoder-form input {
            font-family: 'Inter', sans-serif !important;
            font-size: 14px !important;
            padding: 11px 16px 11px 12px !important;
            border: none !important;
            background: transparent !important;
            outline: none !important;
            color: #1a202c !important;
        }
        .leaflet-control-geocoder-form input::placeholder { color: #94a3b8 !important; font-weight: 400; }
        .leaflet-control-geocoder-form { border-radius: 28px !important; }

        /* ── Route trigger (top-left, below search) ── */
        #rp-trigger-btn {
            position: absolute;
            top: 140px;
            left: 12px;
            z-index: 999;
            background: rgba(255, 255, 255, 0.94) !important;
            backdrop-filter: blur(24px) !important;
            -webkit-backdrop-filter: blur(24px) !important;
            border: 1px solid rgba(255, 255, 255, 0.5) !important;
            border-radius: 14px !important;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08), 0 1px 4px rgba(0, 0, 0, 0.04) !important;
            padding: 10px 18px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 600;
            color: #334155;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: transform 0.2s ease, box-shadow 0.2s ease !important;
            font-family: 'Inter', sans-serif;
        }
        #rp-trigger-btn:hover {
            box-shadow: 0 8px 28px rgba(0, 0, 0, 0.14) !important;
            transform: translateY(-2px) !important;
            color: #2E7D32;
        }
        #rp-trigger-btn i { color: #2E7D32; font-size: 1rem; }

        /* ── Legend Card (compact) ── */
        #info-card {
            position: absolute;
            bottom: 26px;
            left: 12px;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 14px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
            padding: 8px 10px;
            transition: box-shadow 0.2s ease;
            cursor: default;
            font-family: 'Inter', sans-serif;
            min-width: 120px;
            max-height: 38vh;
            overflow-y: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        #info-card::-webkit-scrollbar { display: none; }
        #info-card:hover {
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
        }
        #info-card .legend-header {
            display: flex;
            align-items: center;
            gap: 4px;
            font-weight: 700;
            font-size: 10px;
            color: #2E7D32;
            font-family: 'Poppins', sans-serif;
            padding-bottom: 4px;
            margin-bottom: 4px;
            border-bottom: 1.5px solid #2E7D32;
        }
        #info-card .legend-body {
            display: flex;
            flex-direction: column;
            gap: 1px;
        }
        #info-card .legend-body .legend-item {
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 1px 3px;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.15s ease;
        }
        #info-card .legend-body .legend-item:hover {
            background: rgba(46, 125, 50, 0.06);
        }
        #info-card .legend-body .legend-item .legend-label {
            flex: 1;
            font-size: 9px;
            font-weight: 500;
            color: #334155;
        }
        #info-card .legend-body .legend-item .legend-count {
            background: #e8f5e9;
            color: #2E7D32;
            font-weight: 700;
            font-size: 8px;
            padding: 0 4px;
            border-radius: 6px;
            min-width: 14px;
            text-align: center;
            line-height: 14px;
        }
        #info-card .legend-body .legend-item .legend-marker-wrap {
            display: none;
        }
        #info-card .legend-body .legend-item > svg {
            flex-shrink: 0;
            display: block;
        }

        /* ── Hover Info Card (outside map, connector line) ── */
        #hover-info-card {
            position: fixed;
            z-index: 9999;
            width: 260px;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 12px 40px rgba(0,0,0,0.18);
            overflow: hidden;
            transition: opacity 0.25s ease;
            font-family: 'Inter', sans-serif;
            pointer-events: none;
        }
        #hover-info-card.hidden {
            opacity: 0;
            pointer-events: none;
        }
        #hover-info-card .hover-info-img {
            width: 100%;
            height: 100px;
            background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
            background-size: cover;
            background-position: center;
        }
        #hover-info-card .hover-info-body {
            padding: 10px 12px 12px;
        }
        #hover-info-card .hover-info-category {
            display: inline-block;
            font-size: 9px;
            font-weight: 700;
            color: #fff;
            padding: 2px 8px;
            border-radius: 8px;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        #hover-info-card h5 {
            margin: 0 0 4px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            font-weight: 700;
            color: #1a202c;
            line-height: 1.3;
        }
        #hover-info-card p {
            margin: 0;
            font-size: 11px;
            color: #64748b;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        #hover-connector {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 99999;
            pointer-events: none;
            transition: opacity 0.25s ease;
        }
        #hover-connector.hidden {
            opacity: 0;
        }

        /* ── Scale bar (bottom-left, above info card) ── */
        .leaflet-control-scale {
            margin-bottom: 62px !important;
            margin-left: 12px !important;
        }
        .leaflet-control-scale-line {
            background: rgba(255, 255, 255, 0.92) !important;
            backdrop-filter: blur(24px) !important;
            -webkit-backdrop-filter: blur(24px) !important;
            border: 1px solid rgba(255, 255, 255, 0.5) !important;
            border-radius: 14px !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06) !important;
            padding: 4px 12px !important;
            font-family: 'Inter', sans-serif !important;
            font-size: 11px !important;
            color: #334155 !important;
        }

        /* ── Zoom controls — Premium 56x56 circular glass buttons ── */
        .leaflet-control-zoom {
            margin-bottom: 90px !important;
            border: none !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            gap: 8px !important;
            display: flex !important;
            flex-direction: column;
        }
        .leaflet-control-zoom a {
            display: flex !important;
            align-items: center;
            justify-content: center;
            width: 56px !important;
            height: 56px !important;
            font-size: 22px !important;
            font-weight: 300;
            color: #1a202c !important;
            background: rgba(255, 255, 255, 0.92) !important;
            backdrop-filter: blur(24px) !important;
            -webkit-backdrop-filter: blur(24px) !important;
            border: 1px solid rgba(255, 255, 255, 0.5) !important;
            text-decoration: none !important;
            cursor: pointer;
            border-radius: 50% !important;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08), 0 1px 4px rgba(0, 0, 0, 0.04) !important;
            transition: transform 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.2s ease !important;
        }
        .leaflet-control-zoom a:hover {
            box-shadow: 0 8px 28px rgba(0, 0, 0, 0.14) !important;
            transform: translateY(-2px) scale(1.05) !important;
            background: rgba(255, 255, 255, 0.98) !important;
            color: #2E7D32 !important;
        }
        .leaflet-control-zoom a:active {
            transform: scale(0.92) !important;
        }
        .leaflet-control-zoom a:first-child { border-radius: 50% !important; }
        .leaflet-control-zoom a:last-child { border-radius: 50% !important; }
        .leaflet-control-zoom a:first-child::after {
            content: '';
            position: absolute;
            bottom: 0;
            width: 24px;
            height: 1px;
            background: #e2e8f0;
        }

        /* ── Tools FAB (bottom-right, below zoom) ── */
        #tools-fab-wrap {
            position: absolute;
            right: 12px;
            bottom: 24px;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 8px;
        }
        #tools-fab-btn {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08), 0 1px 4px rgba(0, 0, 0, 0.04);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: #1a202c;
            transition: transform 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.25s ease;
            position: relative;
        }
        #tools-fab-btn:hover {
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.14);
            transform: translateY(-2px) scale(1.05);
            color: #2E7D32;
        }
        #tools-fab-btn:active {
            transform: scale(0.92);
        }
        #tools-fab-btn.active {
            transform: rotate(45deg);
            background: #2E7D32;
            color: #fff;
            border-color: #2E7D32;
            box-shadow: 0 4px 16px rgba(46, 125, 50, 0.3);
        }
        #tools-fab-btn.active:hover {
            background: #1b5e20;
            color: #fff;
            transform: rotate(45deg) scale(1.05);
        }

        /* ── Tools FAB menu ── */
        #tools-menu {
            display: none;
            flex-direction: column;
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.35);
            border-radius: 16px;
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.12), 0 2px 8px rgba(0, 0, 0, 0.06);
            padding: 6px;
            min-width: 200px;
            max-height: 380px;
            overflow-y: auto;
            transform-origin: bottom right;
            animation: toolsMenuIn 0.2s ease;
        }
        #tools-menu.show {
            display: flex;
        }
        @keyframes toolsMenuIn {
            from { opacity: 0; transform: scale(0.85) translateY(8px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        .tools-menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 12px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            color: #334155;
            transition: background 0.15s, transform 0.15s;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            font-family: 'Inter', sans-serif;
        }
        .tools-menu-item:hover {
            background: rgba(46, 125, 50, 0.06);
            transform: translateX(3px);
        }
        .tools-menu-item:active {
            background: rgba(46, 125, 50, 0.12);
        }
        .tools-menu-item i,
        .tools-menu-item svg {
            width: 20px;
            height: 20px;
            font-size: 17px;
            color: #2E7D32;
            flex-shrink: 0;
            text-align: center;
        }
        .tools-menu-item .tmi-label {
            flex: 1;
        }

        /* ── Layer switcher (hidden by default, toggled via tools) ── */
        .leaflet-control-layers {
            border-radius: 14px !important;
            background: rgba(255, 255, 255, 0.94) !important;
            backdrop-filter: blur(20px) !important;
            -webkit-backdrop-filter: blur(20px) !important;
            border: 1px solid rgba(255, 255, 255, 0.35) !important;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08), 0 1px 4px rgba(0, 0, 0, 0.04) !important;
            padding: 6px 8px !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            font-size: 13px !important;
        }
        .leaflet-control-layers-toggle {
            width: 36px !important;
            height: 36px !important;
            background-size: 18px !important;
        }
        .leaflet-control-layers-expanded {
            padding: 8px 10px !important;
        }

        /* ── Map Mode Switcher (top-right) ── */
        #map-mode-switcher {
            position: absolute;
            top: 12px;
            right: 12px;
            z-index: 1000;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        #mode-switcher-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border: 1px solid rgba(255, 255, 255, 0.35);
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            color: #2b3d32;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: inherit;
        }
        #mode-switcher-btn:hover {
            box-shadow: 0 8px 28px rgba(0, 0, 0, 0.14);
            transform: translateY(-2px);
            color: #2E7D32;
        }
        .mode-arrow {
            font-size: 10px;
            transition: transform 0.25s ease;
        }
        #mode-label {
            white-space: nowrap;
        }
        #mode-options {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            min-width: 180px;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
            padding: 6px;
            display: none;
            overflow: hidden;
        }
        #mode-options.show {
            display: block;
        }
        .mode-option {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 10px 14px;
            border: none;
            border-radius: 10px;
            background: transparent;
            color: #334155;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.15s ease;
            font-family: 'Inter', sans-serif;
        }
        .mode-option:hover {
            background: rgba(46, 125, 50, 0.08);
        }
        .mode-option.active {
            background: rgba(46, 125, 50, 0.12);
            color: #2E7D32;
            font-weight: 700;
        }
        .mode-option.active::after {
            content: '\f00c';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            margin-left: auto;
            font-size: 11px;
        }
        .mode-option i {
            width: 18px;
            text-align: center;
            font-size: 14px;
        }

        /* ── GPS/Locate accuracy circle ── */
        .locate-marker {
            width: 20px;
            height: 20px;
            background: #43a047;
            border: 3px solid #fff;
            border-radius: 50%;
            box-shadow: 0 2px 8px rgba(67, 160, 71, 0.4);
        }

        /* ── Mobile bottom sheet ── */
        @media (max-width: 768px) {
            /* Admin panel hidden on mobile — accessible via navbar dropdown */
            #admin-panel, #admin-panel.show {
                display: none !important;
            }

            /* Search bar — smaller, full width minus margins */
            .leaflet-control-geocoder {
                margin-top: 12px !important;
                margin-left: 8px !important;
                width: calc(100vw - 16px) !important;
                max-width: calc(100vw - 16px) !important;
                border-radius: 12px !important;
            }
            .leaflet-control-geocoder-form input {
                font-size: 13px !important;
                padding: 8px 12px !important;
            }

            /* Route trigger — smaller, positioned below geocoder */
            #rp-trigger-btn {
                top: 118px;
                left: 8px;
                padding: 8px 14px;
                font-size: 0.78rem;
                border-radius: 12px !important;
            }

            /* Info card — compact, shifted up */
            #info-card {
                bottom: 110px;
                left: 8px;
                padding: 8px 12px;
                border-radius: 10px;
                min-width: 120px;
            }
            #info-card .legend-header { font-size: 9px; padding-bottom: 3px; margin-bottom: 3px; }
            #info-card .legend-body .legend-item .legend-label { font-size: 8px; }
            #info-card .legend-body .legend-item .legend-count { font-size: 7px; line-height: 12px; min-width: 12px; padding: 0 3px; }
            #info-card .legend-body .legend-item > svg { width: 10px; height: 14px; }

            /* Scale */
            .leaflet-control-scale {
                margin-bottom: 56px !important;
                margin-left: 8px !important;
            }
            .leaflet-control-scale-line {
                border-radius: 10px !important;
                font-size: 10px !important;
                padding: 3px 8px !important;
            }

            /* Zoom — smaller, shifted up */
            .leaflet-control-zoom {
                margin-bottom: 130px !important;
            }
            .leaflet-control-zoom a {
                width: 36px !important;
                height: 36px !important;
                font-size: 15px !important;
            }
            .leaflet-control-zoom a:first-child { border-radius: 50% !important; }
            .leaflet-control-zoom a:last-child { border-radius: 50% !important; }

            /* Tools FAB — smaller, shifted up */
            #tools-fab-wrap {
                right: 8px;
                bottom: 110px;
            }
            #tools-fab-btn {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }
            #tools-menu {
                min-width: 170px;
                max-height: 300px;
                border-radius: 12px;
            }
            .tools-menu-item {
                font-size: 12px;
                padding: 8px 12px;
            }

            /* Panels — adjust top spacing */
            #route-panel {
                border-radius: 12px !important;
            }
            #route-panel.hidden { transform: translateY(-110%); opacity: 0; }

            /* Bottom sheet mode: collapse non-essential controls */
            .desktop-only { display: none !important; }
            #tools-fab-wrap.desktop-only { display: flex !important; }
        }

        @media (max-width: 480px) {
            .leaflet-control-geocoder {
                width: calc(100vw - 16px) !important;
                max-width: calc(100vw - 16px) !important;
                margin-top: 8px !important;
                margin-left: 8px !important;
            }
            #info-card {
                padding: 6px 8px;
                max-height: 35vh;
                min-width: 100px;
            }
            #info-card .legend-header { font-size: 9px; padding-bottom: 2px; margin-bottom: 2px; }
            #info-card .legend-body .legend-item .legend-label { font-size: 8px; }
            #info-card .legend-body .legend-item { padding: 1px 2px; gap: 3px; }
            #info-card .legend-body .legend-item .legend-count { font-size: 7px; line-height: 12px; min-width: 12px; padding: 0 3px; }
            #info-card .legend-body .legend-item > svg { width: 10px; height: 14px; }
            #tools-fab-btn {
                width: 36px;
                height: 36px;
                font-size: 14px;
            }
        }

        /* ── Pulse animation for dot ── */
        @keyframes pulse-dot {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }

        /* ══════════════════════════════════════════════
           CINEMATIC ANIMATIONS
           ══════════════════════════════════════════════ */

        /* Popup fade-in */
        .cinematic-popup .leaflet-popup-content-wrapper {
            animation: popupFadeIn 0.6s cubic-bezier(0.23, 1, 0.32, 1) forwards;
        }
        @keyframes popupFadeIn {
            0%   { opacity: 0; transform: translateY(12px) scale(0.92); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Arrival pulse ring */
        .mapi-pin.marker-arrive > svg {
            animation: arrivePulse 0.8s ease-out 2;
        }
        @keyframes arrivePulse {
            0%   { filter: drop-shadow(0 0 0 0 rgba(46,125,50,0.5)); }
            70%  { filter: drop-shadow(0 0 0 18px rgba(46,125,50,0)); }
            100% { filter: drop-shadow(0 0 0 0 rgba(46,125,50,0)); }
        }

        /* Route drawing trail */
        .route-drawing {
            stroke-dasharray: 2000;
            stroke-dashoffset: 2000;
            animation: routeDraw 2s cubic-bezier(0.23, 1, 0.32, 1) forwards;
        }
        @keyframes routeDraw {
            to { stroke-dashoffset: 0; }
        }

        /* Cinematic blur overlay during flight */
        #cinematic-overlay {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            z-index: 9999;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s ease;
            background: radial-gradient(ellipse at center, transparent 50%, rgba(0,0,0,0.06) 100%);
        }
        #cinematic-overlay.active {
            opacity: 1;
        }

        /* Marker drop-in */
        .mapi-pin.marker-drop > svg {
            animation: markerDrop 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }
        @keyframes markerDrop {
            0%   { transform: translateY(-60px) scale(0.4); opacity: 0; }
            60%  { transform: translateY(6px) scale(1.05); opacity: 1; }
            100% { transform: translateY(0) scale(1); opacity: 1; }
        }
    </style>
</head>
<body>

    @include('partials.public-navbar')

    {{-- ====================================================
    LOADING OVERLAY
    ==================================================== --}}
    <div id="loading" aria-hidden="true">
        <div class="spinner"></div>
        <p>{{ __('map.loading') }}</p>
    </div>

    {{-- ====================================================
    ERROR BANNER
    ==================================================== --}}
    <div id="error-banner" role="alert">
        <span>&#9888;</span>
        <span id="error-message">{{ __('map.error') }}</span>
    </div>


    {{-- ====================================================
    FILTER PANEL
    ==================================================== --}}
    <div id="filter-panel">
        <div class="search-container">
            <input type="text" id="search-input" class="form-control form-control-sm" placeholder="{{ __('map.search_placeholder') }}" autocomplete="off">
            <div id="search-results" class="list-group"></div>
        </div>
        <h6>{{ __('map.filter_category') }}</h6>
        <div id="filter-list"></div>

        <div class="mt-3 pt-2 border-top border-light" style="border-color:#e9ecef !important;">
            <button onclick="window.showRoutePanel()" style="display:inline-flex;align-items:center;gap:6px;width:100%;background:none;border:1px dashed #dadce0;border-radius:8px;padding:8px 12px;font-size:0.8rem;font-weight:600;color:#1a73e8;cursor:pointer;transition:all 0.15s;" onmouseover="this.style.background='#f1f3f4'" onmouseout="this.style.background='none'">
                <i class="fa-solid fa-route" style="font-size:0.9rem;"></i>
                <span>{{ __('map.open_route') }}</span>
            </button>

            <h6 class="m-0 mb-2 mt-3" style="font-size:0.8rem; font-weight:700; color:#2d6a4f;">{{ __('map.export_print') }}</h6>
            <div class="d-flex gap-1">
                <button id="btn-export-geojson" class="btn btn-outline-success btn-sm flex-fill" style="font-size:0.72rem; font-weight:600; padding:5px;" title="{{ __('map.export_geojson_title') }}">
                    <i class="fa-solid fa-download me-1"></i> GeoJSON
                </button>
                <span class="btn btn-outline-secondary btn-sm flex-fill disabled" style="font-size:0.72rem; font-weight:600; padding:5px; text-decoration:none; text-align:center; cursor:not-allowed;" title="{{ __('map.feature_development') }}">
                    <i class="fa-solid fa-print me-1"></i> {{ __('map.brochure') }}
                </span>
            </div>
        </div>
    </div>

    {{-- ====================================================
    ROUTE PANEL TRIGGER BUTTON (Google Maps style)
    ==================================================== --}}
    <button id="rp-trigger-btn" onclick="window.showRoutePanel()" title="{{ __('nav.route') }}">
        <i class="fa-solid fa-route"></i>
        <span>{{ __('map.route') }}</span>
    </button>

    {{-- ====================================================
    ROUTE PANEL (Google Maps style, hidden by default)
    ==================================================== --}}
    <div id="route-panel" class="hidden">
        <div class="rp-header">
            <h5><i class="fa-solid fa-route me-2" style="color:#1a73e8;"></i>{{ __('nav.route') }}</h5>
            <button class="rp-close" onclick="window.hideRoutePanel()" aria-label="{{ __('common.close') }}">&times;</button>
        </div>
        <div class="rp-body">
            <div class="rp-input-wrap">
                <span class="rp-icon" style="color:#1a73e8;"><i class="fa-solid fa-circle"></i></span>
                <input type="text" id="origin-input" class="rp-origin" placeholder="{{ __('route.origin_placeholder') }}" autocomplete="off">
                <button type="button" class="rp-swap" onclick="window.swapLocations()" title="{{ __('route.swap') }}">
                    <i class="fa-solid fa-arrow-up-arrow-down"></i>
                </button>
                <div class="rp-suggestions" id="origin-suggestions"></div>
            </div>

            <div class="rp-input-wrap">
                <span class="rp-icon" style="color:#e74c3c;"><i class="fa-solid fa-flag-checkered"></i></span>
                <input type="text" id="dest-input" class="rp-dest" placeholder="{{ __('route.dest_placeholder') }}" autocomplete="off">
                <div class="rp-suggestions" id="dest-suggestions"></div>
            </div>

            <div class="rp-gps-row">
                <button id="btn-gps-origin">
                    <i class="fa-solid fa-location-crosshairs me-1"></i>{{ __('route.my_location') }}
                </button>
                <button id="btn-gps-dest">
                    <i class="fa-solid fa-location-dot me-1"></i>{{ __('route.pick_map') }}
                </button>
            </div>

            <button id="btn-calculate" class="rp-calc-btn" disabled>
                <i class="fa-solid fa-magnifying-glass-location me-1"></i> {{ __('route.calculate') }}
            </button>

            <div id="rp-result" class="rp-result">
                <div class="rp-summary">
                    <div class="rp-stat">
                    <div class="rp-stat-label">{{ __('route.distance') }}</div>
                    <div class="rp-stat-value" id="ri-distance">0</div>
                </div>
                <div class="rp-stat">
                    <div class="rp-stat-label">{{ __('route.duration') }}</div>
                    <div class="rp-stat-value" id="ri-duration">0</div>
                </div>
                <div class="rp-stat">
                    <div class="rp-stat-label">{{ __('route.eta') }}</div>
                        <div class="rp-stat-value small" id="ri-eta">-</div>
                    </div>
                </div>

                <div id="rp-progress" class="rp-progress">
                    <div class="rp-progress-bar">
                        <div class="rp-progress-fill" id="progress-fill"></div>
                    </div>
                    <div class="rp-progress-text">
                        <span id="progress-traveled">0 km</span>
                        <span id="progress-remaining">{{ __('route.remaining') }}</span>
                    </div>
                </div>

                <div id="tracking-status" class="rp-track-status inactive">
                    <span class="rp-track-dot inactive"></span>
                    <span id="tracking-text">{{ __('route.tracking') }}</span>
                </div>

                <button id="btn-start-tracking" class="rp-track-btn">
                    <i class="fa-solid fa-satellite-dish me-1"></i> {{ __('route.start_tracking') }}
                </button>

                <div id="turn-list" class="rp-turns">
                    <hr class="my-2">
                    <small class="fw-bold text-muted d-block mb-2"><i class="fa-solid fa-turn-down me-1"></i>{{ __('route.directions') }}</small>
                    <div id="turn-items"></div>
                </div>
            </div>
        </div>
    </div>


    {{-- ====================================================
    ANALYSIS PANEL
    ==================================================== --}}
    <div id="analysis-panel" class="hidden">
        <div class="analysis-header d-flex justify-content-between align-items-center" style="border-bottom:2px solid #7b2d8e;padding-bottom:6px;margin-bottom:10px;">
            <h6 class="m-0">{{ __('map.analysis') }}</h6>
            <button type="button" id="analysis-close" class="btn-close btn-close-sm" aria-label="{{ __('common.close') }}"></button>
        </div>

        <div class="analysis-section">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="heatmap-toggle">
                <label class="form-check-label" for="heatmap-toggle">{{ __('map.heatmap') }}</label>
            </div>
            <div class="small mt-1">{{ __('map.heatmap_desc') }}</div>
        </div>

        <div class="analysis-section">
            <label for="buffer-slider">{{ __('map.buffer') }}</label>
            <input type="range" id="buffer-slider" min="100" max="1000" value="500" step="50">
            <div class="d-flex justify-content-between small">
                <span>100 m</span>
                <span id="buffer-value">500 m</span>
                <span>1000 m</span>
            </div>
            <div class="small mt-1">{{ __('map.buffer_desc') }}</div>
        </div>

        <div class="analysis-section">
            <button id="btn-centroid" class="btn btn-outline-secondary analysis-btn">
                {{ __('map.centroid') }}
            </button>
            <div class="small mt-1">{{ __('map.centroid_desc') }}</div>
        </div>
    </div>

    {{-- ====================================================
    STORYTELLING BUTTON
    ==================================================== --}}
    <button id="story-toggle" title="{{ __('map.story') }}" style="display:none;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
            <path d="M6.271 5.055a.5.5 0 0 1 .52.038l3.5 2.5a.5.5 0 0 1 0 .814l-3.5 2.5A.5.5 0 0 1 6 10.5v-5a.5.5 0 0 1 .271-.445z"/>
        </svg>
        {{ __('map.story') }}
    </button>

    {{-- ====================================================
    STORYTELLING SIDEBAR
    ==================================================== --}}
    <div id="story-sidebar">
        <div class="story-header">
            <h6><i class="fa-solid fa-book-open me-1"></i> {{ __('map.story_title') }}</h6>
            <button type="button" id="story-close" class="btn-close btn-close-sm" aria-label="{{ __('common.close') }}"></button>
        </div>
        <div class="story-body" id="story-body">
            <p class="text-muted small mb-2">{{ __('map.story_hint') }}</p>
        </div>
    </div>

    {{-- ====================================================
    FLOATING ADMIN PANEL (@auth only)
    ==================================================== --}}
    @auth
    <div id="admin-panel" class="admin-panel show">
        <div class="admin-badge">
            <span class="dot-online"></span> {{ __('map.admin_panel') }}
        </div>
        <div class="admin-title">
            <i class="fa-regular fa-user me-1"></i>{{ Auth::user()->name }}
        </div>
        <a href="{{ route('admin.places.create') }}" class="btn btn-success admin-btn">
            <i class="fa-solid fa-plus me-1"></i>{{ __('map.add_place') }}
        </a>
        <a href="{{ route('admin.places.index') }}" class="btn btn-outline-success admin-btn">
            <i class="fa-solid fa-list me-1"></i>{{ __('map.manage_places') }}
        </a>

        {{-- ADMIN FEATURE SLOT --}}
        <div class="future-slot border-top pt-2 mt-2">
            <div class="slot-label text-success" style="font-size:0.68rem;text-transform:uppercase;letter-spacing:0.5px;font-weight:600;"><i class="fa-solid fa-gears me-1"></i>{{ __('admin.active_features') }}</div>
            <div class="text-muted small mt-1" style="font-size:0.7rem; line-height:1.4;">
                <i class="fa-solid fa-info-circle text-info me-1"></i>
                {!! __('admin.quick_edit_hint') !!}
            </div>
        </div>
    </div>

    {{-- MODAL EDIT CEPAT ADMIN --}}
    <div class="modal fade" id="quickEditModal" tabindex="-1" aria-hidden="true" style="z-index: 9999;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 15px 50px rgba(0,0,0,0.2);">
                <div class="modal-header bg-success text-white" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
                    <h5 class="modal-title fw-bold" id="quickEditModalLabel"><i class="fa-solid fa-pen-to-square me-1"></i> {{ __('admin.quick_edit_title') }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="quick-edit-form">
                    <div class="modal-body p-4">
                        <input type="hidden" id="edit-id">
                        
                        <div class="mb-3">
                           <label class="form-label small fw-semibold">{{ __('admin.place_name') }}</label>
                           <input type="text" id="edit-name" class="form-control form-control-sm" required>
                        </div>
                        
                        <div class="mb-3">
                           <label class="form-label small fw-semibold">{{ __('admin.category') }}</label>
                           <select id="edit-category" class="form-select form-select-sm" required>
                                <option value="Kesehatan">Kesehatan</option>
                                <option value="Kantor Desa / Pemerintahan">Kantor Desa / Pemerintahan</option>
                                <option value="Pendidikan">Pendidikan</option>
                                <option value="Wisata Alam">Wisata Alam</option>
                                <option value="Kuliner">Kuliner</option>
                                <option value="Penginapan">Penginapan</option>
                                <option value="UMKM / Ekonomi">UMKM / Ekonomi</option>
                                <option value="Tempat Ibadah">Tempat Ibadah</option>
                                <option value="Budaya">Budaya</option>
                                <option value="Infrastruktur">Infrastruktur</option>
                                <option value="Ruang Terbuka">Ruang Terbuka</option>
                                <option value="Umum">Umum</option>
                            </select>
                        </div>
                        
                        <div class="row g-2 mb-3">
                           <div class="col-6">
                                <label class="form-label small fw-semibold">{{ __('admin.latitude') }}</label>
                               <input type="number" step="any" id="edit-lat" class="form-control form-control-sm" readonly>
                           </div>
                           <div class="col-6">
                                <label class="form-label small fw-semibold">{{ __('admin.longitude') }}</label>
                               <input type="number" step="any" id="edit-lng" class="form-control form-control-sm" readonly>
                           </div>
                           <div class="col-12 mt-1">
                                <small class="text-muted" style="font-size:0.7rem;"><i class="fa-solid fa-arrows-up-down-left-right me-1"></i> {{ __('admin.drag_hint') }}</small>
                           </div>
                        </div>
                        
                        <div class="mb-3">
                           <label class="form-label small fw-semibold">{{ __('admin.description') }}</label>
                           <textarea id="edit-description" rows="3" class="form-control form-control-sm"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px; padding:10px 20px;">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">{{ __('common.cancel') }}</button>
                        <button type="submit" class="btn btn-success btn-sm fw-bold px-4">{{ __('admin.save_changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    {{-- Banner Instruksi Drag Admin --}}
    <div id="drag-instruction" class="alert alert-warning position-fixed shadow d-none align-items-center gap-2" style="bottom: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; border-radius: 30px; font-weight: 600; font-size:0.8rem; padding: 10px 20px;">
        <i class="fa-solid fa-hand-pointer fa-bounce"></i>
        <span>{{ __('admin.drag_instruction') }}</span>
    </div>
    @endauth

    {{-- ====================================================
    LEGEND CARD (bottom-left) — replaces old info-card
    ==================================================== --}}
    <div id="info-card">
        <div class="legend-header">
            <i class="fa-solid fa-map-signs" style="color:#2E7D32;font-size:13px;"></i>
            <span>Kategori Lokasi</span>
        </div>
        <div class="legend-body" id="legend-body">
            <div class="legend-item">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#2E7D32" stroke-width="2"><path d="M3 21h18M12 3L3 12h3v9h12v-9h3L12 3z"/></svg>
                <span class="legend-label">Batas Desa</span>
            </div>
        </div>
    </div>

    {{-- ====================================================
    TOOLS FAB (bottom-right, expandable)
    ==================================================== --}}
    <div id="tools-fab-wrap">
        <div id="tools-menu">
            <button class="tools-menu-item" data-action="filter">
                <i class="fa-solid fa-sliders"></i>
                <span class="tmi-label">Filter Kategori</span>
            </button>
            <button class="tools-menu-item" data-action="analysis">
                <i class="fa-solid fa-chart-simple"></i>
                <span class="tmi-label">Analisis Spasial</span>
            </button>
            <button class="tools-menu-item" data-action="layers">
                <i class="fa-solid fa-layer-group"></i>
                <span class="tmi-label">Layer Peta</span>
            </button>
            <button class="tools-menu-item" data-action="story">
                <i class="fa-solid fa-play"></i>
                <span class="tmi-label">Virtual Tour</span>
            </button>
            <button class="tools-menu-item" data-action="gps">
                <i class="fa-solid fa-location-crosshairs"></i>
                <span class="tmi-label">Lokasi Saya</span>
            </button>
            <button class="tools-menu-item" data-action="compass">
                <i class="fa-solid fa-location-arrow"></i>
                <span class="tmi-label">Kompas</span>
            </button>
            <button class="tools-menu-item" data-action="home">
                <i class="fa-solid fa-house"></i>
                <span class="tmi-label">Pusat Bilebante</span>
            </button>
            <button class="tools-menu-item" data-action="measure">
                <i class="fa-solid fa-ruler"></i>
                <span class="tmi-label">Ukur Jarak</span>
            </button>
            <button class="tools-menu-item" data-action="fullscreen">
                <i class="fa-solid fa-expand"></i>
                <span class="tmi-label">Layar Penuh</span>
            </button>
            <button class="tools-menu-item" data-action="download">
                <i class="fa-solid fa-download"></i>
                <span class="tmi-label">Unduh Peta</span>
            </button>
        </div>
        <div id="tools-fab-btn" title="Tools">
            <i class="fa-solid fa-toolbox"></i>
        </div>
    </div>

    {{-- ====================================================
    MAP MODE SWITCHER (top-right)
    ==================================================== --}}
    <div id="map-mode-switcher">
        <button id="mode-switcher-btn" title="Mode Peta">
            <i class="fa-solid fa-layer-group"></i>
            <span id="mode-label">Satelit</span>
            <i class="fa-solid fa-chevron-down mode-arrow"></i>
        </button>
        <div id="mode-options">
            <button class="mode-option active" data-mode="satellite">
                <i class="fa-solid fa-satellite"></i> Satelit
            </button>
            <button class="mode-option" data-mode="osm">
                <i class="fa-solid fa-road"></i> Jalan
            </button>
            <button class="mode-option" data-mode="light">
                <i class="fa-solid fa-sun"></i> Terang
            </button>
        </div>
    </div>

    {{-- ====================================================
    MAP CONTAINER
    ==================================================== --}}
    <div id="map"></div>
    <div id="cinematic-overlay"></div>

    {{-- ====================================================
    HOVER INFO CARD (outside map, connected by line)
    ==================================================== --}}
    <div id="hover-info-card" class="hidden">
        <div class="hover-info-img" id="hover-info-img"></div>
        <div class="hover-info-body">
            <div class="hover-info-category" id="hover-info-category"></div>
            <h5 id="hover-info-name"></h5>
            <p id="hover-info-desc"></p>
        </div>
    </div>
    <svg id="hover-connector" class="hidden" xmlns="http://www.w3.org/2000/svg" style="filter: drop-shadow(0 1px 3px rgba(0,0,0,0.6));">
        <!-- Outline path for visibility -->
        <path id="hover-connector-outline" d="" fill="none" stroke="rgba(0,0,0,0.4)" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
        <path id="hover-connector-path" d="" fill="none" stroke="#FFFFFF" stroke-width="2.5" stroke-dasharray="6,4" stroke-linecap="round" stroke-linejoin="round"/>
        <circle id="hover-connector-dot-outline" cx="0" cy="0" r="7" fill="rgba(0,0,0,0.3)"/>
        <circle id="hover-connector-dot" cx="0" cy="0" r="5" fill="#FFFFFF"/>
    </svg>
    </svg>

    {{-- ====================================================
    SCRIPTS
    ==================================================== --}}

    {{-- Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Leaflet --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    {{-- Leaflet Fullscreen --}}
    <script src="https://cdn.jsdelivr.net/npm/leaflet-fullscreen@1.0.2/dist/Leaflet.fullscreen.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@turf/turf@6/turf.min.js"></script>
    {{-- Leaflet Control Geocoder --}}
    <script src="https://cdn.jsdelivr.net/npm/leaflet-control-geocoder@2.4.0/dist/Control.Geocoder.min.js"></script>

    <script>
        (function () {
            'use strict';

            var IS_ADMIN = @json(auth()->check());
            var LANG = {
                error: @json(__('common.error')),
                invalid_geojson: @json(__('map.invalid_geojson')),
                not_available: @json(__('map.not_available')),
                no_description: @json(__('place.no_description')),
                filter_all: @json(__('map.filter_all')),
                village_boundary: @json(__('map.village_boundary')),
                centroid_label: @json(__('map.centroid_label')),
                package_min_points: @json(__('map.package_min_points')),
                package_label: @json(__('map.package_label')),
                no_export_data: @json(__('map.no_export_data')),
                network_error: @json(__('common.network_error')),
                update_success: @json(__('admin.update_success')),
                update_failed: @json(__('admin.update_failed')),
                route_my_location: @json(__('route.my_location')),
                route_detecting: @json(__('route.detecting')),
                route_gps_error: @json(__('route.gps_error')),
                route_gps_unsupported: @json(__('route.gps_unsupported')),
                route_click_map: @json(__('route.click_map')),
                route_dest_map: @json(__('route.dest_map')),
                route_searching: @json(__('route.searching')),
                route_calculate: @json(__('route.calculate')),
                route_at_destination: @json(__('route.at_destination')),
                route_calculate_first: @json(__('route.calculate_first')),
                route_tracking_active: @json(__('route.tracking_active')),
                route_gps_lost: @json(__('route.gps_lost')),
                route_tracking: @json(__('route.tracking')),
                route_start_tracking: @json(__('route.start_tracking')),
                route_almost_there: @json(__('route.almost_there')),
                route_arrived: @json(__('route.arrived')),
                route_origin_label: @json(__('route.origin_label')),
                route_dest_label: @json(__('route.dest_label')),
                route_distance: @json(__('route.distance')),
                route_duration: @json(__('route.duration')),
            };

            // ────────────────────────────────────────────────
            // 1. KONSTANSA
            // ────────────────────────────────────────────────

            // ════════════════════════════════════════════════
            // UNIFIED CATEGORY DESIGN SYSTEM
            // ════════════════════════════════════════════════

            // Categories sourced from DB — always in sync with admin
            var CATEGORY_COLORS = {};
            var CATEGORY_SVG = {};
            var MARKER_ICONS = {};
            var CATEGORY_ORDER = [];
            @foreach ($categories as $cat)
            CATEGORY_COLORS['{{ $cat->key }}'] = '{{ $cat->color }}';
            CATEGORY_SVG['{{ $cat->key }}'] = '{!! $cat->svg_path !!}';
            MARKER_ICONS['{{ $cat->key }}'] = '{{ $cat->icon }}';
            CATEGORY_ORDER.push('{{ $cat->key }}');
            @endforeach

            // Keep legacy aliases for backward compat
            var MARKER_COLORS = CATEGORY_COLORS;

            var DEFAULT_COLOR = '#757575';
            var DEFAULT_ICON = 'fa-location-dot';
            var DEFAULT_CATEGORY = 'Umum';

            // Pusat koordinat Desa Bilebante (dari data BIG)
            var BILEBANTE_CENTER = [-8.6248, 116.1882];

            // ────────────────────────────────────────────────
            // 2. DOM REFS
            // ────────────────────────────────────────────────

            var loadingEl = document.getElementById('loading');
            var errorBanner = document.getElementById('error-banner');
            var errorMessage = document.getElementById('error-message');
            var filterPanel = document.getElementById('filter-panel');
            var filterToggle = document.getElementById('filter-toggle');
            var filterList = document.getElementById('filter-list');

            // ────────────────────────────────────────────────
            // 3. CINEMATIC CAMERA ANIMATIONS (Google Earth-style)
            // ────────────────────────────────────────────────

            var Cinematic = (function () {
                var overlay = document.getElementById('cinematic-overlay');

                // Custom cubic bezier approximation of Google Earth ease
                function geEase(t) {
                    // Google Earth-style: slow start, fast middle, slow end
                    return t < 0.5
                        ? 2 * t * t
                        : 1 - Math.pow(-2 * t + 2, 2) / 2;
                }

                // Overshoot ease for dramatic zoom-in
                function overshootEase(t) {
                    return t < 0.5
                        ? 4 * t * t * t
                        : 1 - Math.pow(-2 * t + 2, 3) / 2;
                }

                // ─── Multi-phase cinematic flight ───
                function cinematicFlyTo(lat, lng, targetZoom, opts) {
                    opts = opts || {};
                    var duration = opts.duration || 2500;
                    var zoomOutBy = opts.zoomOutBy || 2;
                    var onEnd = opts.onEnd || null;
                    var currentZoom = map.getZoom();
                    var midZoom = Math.max(currentZoom - zoomOutBy, 8);
                    var startCenter = map.getCenter();
                    var endCenter = L.latLng(lat, lng);
                    var startTime = null;

                    overlay.classList.add('active');

                    function step(ts) {
                        if (!startTime) startTime = ts;
                        var elapsed = ts - startTime;
                        var progress = Math.min(elapsed / duration, 1);

                        // Phase 1 (0–35%): zoom out
                        var p1 = Math.min(progress / 0.35, 1);
                        var z1 = currentZoom + (midZoom - currentZoom) * geEase(p1);
                        map.setZoom(z1, { animate: false });

                        // Phase 2 (15–80%): fly toward destination
                        var p2 = Math.max(0, Math.min((progress - 0.15) / 0.65, 1));
                        var e2 = geEase(p2);
                        var lat2 = startCenter.lat + (endCenter.lat - startCenter.lat) * e2;
                        var lng2 = startCenter.lng + (endCenter.lng - startCenter.lng) * e2;
                        map.panTo([lat2, lng2], { animate: false });

                        // Phase 3 (55–100%): zoom in to target
                        if (progress > 0.55) {
                            var p3 = Math.min((progress - 0.55) / 0.45, 1);
                            var z3 = midZoom + (targetZoom - midZoom) * overshootEase(p3);
                            map.setZoom(z3, { animate: false });
                        }

                        if (progress < 1) {
                            requestAnimationFrame(step);
                        } else {
                            overlay.classList.remove('active');
                            map.setView(endCenter, targetZoom, { animate: false });
                            if (onEnd) onEnd();
                        }
                    }

                    requestAnimationFrame(step);
                }

                // ─── Smooth rotation (bearing) ───
                function smoothRotate(targetBearing, duration) {
                    duration = duration || 1000;
                    var start = map.getBearing ? map.getBearing() : 0;
                    var delta = targetBearing - start;
                    var startTime = null;

                    function step(ts) {
                        if (!startTime) startTime = ts;
                        var p = Math.min((ts - startTime) / duration, 1);
                        var e = geEase(p);
                        var current = start + delta * e;
                        if (map.setBearing) {
                            map.setBearing(current);
                        } else if (map._controlCorners) {
                            // Fallback: rotate the map container via CSS
                            var container = map.getContainer();
                            container.style.transform = 'rotate(' + current + 'deg)';
                            container.style.transformOrigin = 'center center';
                        }
                        if (p < 1) requestAnimationFrame(step);
                    }

                    requestAnimationFrame(step);
                }

                // ─── Animated route drawing ───
                function animateRouteDrawing(polyline, duration) {
                    duration = duration || 2000;
                    var latlngs = polyline.getLatLngs();
                    if (!latlngs.length) return;
                    var total = latlngs.length;
                    var startTime = null;

                    // Replace with empty temporary polyline
                    var tempLine = L.polyline([], {
                        color: polyline.options.color || '#1a73e8',
                        weight: polyline.options.weight || 4,
                        opacity: polyline.options.opacity || 0.9,
                        dashArray: null,
                        className: 'route-drawing',
                    }).addTo(map);

                    function step(ts) {
                        if (!startTime) startTime = ts;
                        var p = Math.min((ts - startTime) / duration, 1);
                        var e = geEase(p);
                        var count = Math.max(1, Math.floor(e * total));
                        var visible = latlngs.slice(0, count);
                        tempLine.setLatLngs(visible);

                        if (p < 1) {
                            requestAnimationFrame(step);
                        } else {
                            // Replace with original polyline
                            map.removeLayer(tempLine);
                            polyline.addTo(map);
                        }
                    }

                    requestAnimationFrame(step);
                }

                // ─── Animated marker drop ───
                function dropMarker(marker, delay) {
                    delay = delay || 0;
                    setTimeout(function () {
                        var el = marker.getElement();
                        if (el) {
                            el.classList.add('marker-drop');
                        }
                    }, delay);
                }

                // ─── Arrival pulse on marker ───
                function pulseOnArrival(marker, duration) {
                    duration = duration || 1600;
                    var el = marker.getElement();
                    if (el) {
                        el.classList.add('marker-arrive');
                        setTimeout(function () {
                            el.classList.remove('marker-arrive');
                        }, duration);
                    }
                }

                // ─── Fade-in popup ───
                function openPopupFade(marker) {
                    marker.openPopup();
                    var p = marker.getPopup();
                    if (p) {
                        var container = p._container;
                        if (container) {
                            container.classList.add('cinematic-popup');
                        }
                    }
                }

                // Public API
                return {
                    flyTo: cinematicFlyTo,
                    rotate: smoothRotate,
                    drawRoute: animateRouteDrawing,
                    dropMarker: dropMarker,
                    pulseOnArrival: pulseOnArrival,
                    openPopup: openPopupFade,
                };
            })();

            // ────────────────────────────────────────────────
            // 4. HELPER
            // ────────────────────────────────────────────────

            function escapeHtml(str) {
                if (!str) return '';
                var div = document.createElement('div');
                div.appendChild(document.createTextNode(str));
                return div.innerHTML;
            }

            // Modern map pin marker with embedded category SVG icon.
            // Size: 32×42 desktop, 28×36 mobile — 3px white border, soft shadow.
            function makeModernMarkerIcon(color, category, size) {
                size = size || 32;
                var half = size / 2;
                var svgPath = CATEGORY_SVG[category] || CATEGORY_SVG[DEFAULT_CATEGORY];
                var iconScale = size <= 28 ? 16 : 18;
                var svg = '<svg width="' + size + '" height="' + (size + 10) + '" viewBox="0 0 44 54" xmlns="http://www.w3.org/2000/svg">' +
                    '<path d="M22 53C22 53 4 33 4 18C4 8.2 12.2 0 22 0C31.8 0 40 8.2 40 18C40 33 22 53 22 53Z" fill="' + color + '" stroke="#FFFFFF" stroke-width="3"/>' +
                    '<circle cx="22" cy="17" r="11" fill="#FFFFFF"/>' +
                    '<g transform="translate(22,17) scale(' + (iconScale/24) + ') translate(-12,-12)">' +
                    svgPath +
                    '</g>' +
                    '</svg>';
                return L.divIcon({
                    className: 'mapi-pin',
                    html: svg,
                    iconSize: [size, size + 10],
                    iconAnchor: [half, size + 6],
                    popupAnchor: [0, -(size + 12)]
                });
            }

            // close other panels on mobile to optimize view compatibility
            function closeAllPanelsExcept(exceptId) {
                if (window.innerWidth < 768) {
                    if (exceptId !== 'route-panel') {
                        var rp = document.getElementById('route-panel');
                        if (rp) rp.classList.add('hidden');
                    }
                    if (exceptId !== 'filter-panel') {
                        var fp = document.getElementById('filter-panel');
                        if (fp) fp.classList.add('hidden');
                    }
                    if (exceptId !== 'analysis-panel') {
                        var ap = document.getElementById('analysis-panel');
                        if (ap) ap.classList.add('hidden');
                    }
                    if (exceptId !== 'story-sidebar') {
                        var sb = document.getElementById('story-sidebar');
                        var st = document.getElementById('story-toggle');
                        if (sb) sb.classList.remove('show');
                        if (st) st.classList.remove('active');
                    }
                }
            }

            function showError(msg) {
                errorMessage.textContent = msg || LANG.error;
                errorBanner.classList.add('show');
                loadingEl.classList.add('hidden');
                setTimeout(function () {
                    errorBanner.classList.remove('show');
                }, 8000);
            }

            function hideLoading() {
                loadingEl.classList.add('hidden');
            }

            // Fallback: sembunyikan loading setelah 10 detik (jaga-jaga ada JS error)
            setTimeout(function () {
                hideLoading();
            }, 10000);

            // ────────────────────────────────────────────────
            // 5. INISIALISASI PETA
            // ────────────────────────────────────────────────

            var map = L.map('map', {
                center: BILEBANTE_CENTER,
                zoom: 15,
                minZoom: 2,
                maxZoom: 19,
                zoomControl: false,
                fullscreenControl: false,
                inertia: true,
                inertiaDeceleration: 3000,
                inertiaMaxSpeed: 2000,
                inertiaThreshold: 32,
            });

            // ── Bottom-right controls (Zoom only) ──
            L.control.zoom({ position: 'bottomright' }).addTo(map);



            // ── Scale bar (bottom-left) ──
            L.control.scale({
                imperial: false,
                metric: true,
                position: 'bottomleft',
            }).addTo(map);

            // ── Search box (top-left, 24px from navbar via CSS) ──
            L.Control.geocoder({
                position: 'topleft',
                placeholder: 'Cari lokasi...',
                errorMessage: 'Lokasi tidak ditemukan',
                showResultIcons: true,
                collapsed: false,
                expand: 'click',
            }).addTo(map);

            // ── Global helpers for Tools FAB ──
            window._locationMarker = null;
            window._map = map;
            window._fullscreenOn = false;
            window._compassOn = false;
            window._layersVisible = false;

            // Basemaps definition
            var satelliteLayer = L.layerGroup([
                L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                    maxZoom: 19,
                    attribution: '&copy; Esri World Imagery'
                }),
                L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
                    maxZoom: 19,
                    attribution: '&copy; Esri'
                })
            ]);

            var osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            });

            var lightLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                maxZoom: 19,
                attribution: '&copy; CartoDB'
            });

            // Default: Esri Satellite
            satelliteLayer.addTo(map);

            // ── Map Mode Switcher ──
            (function () {
                var modeBtn = document.getElementById('mode-switcher-btn');
                var modeOptions = document.getElementById('mode-options');
                var modeLabel = document.getElementById('mode-label');
                var modeArrow = document.querySelector('.mode-arrow');
                var isOpen = false;
                var currentLayer = satelliteLayer;

                var modes = {
                    satellite: { layer: satelliteLayer, label: 'Satelit' },
                    osm: { layer: osmLayer, label: 'Jalan' },
                    light: { layer: lightLayer, label: 'Terang' }
                };

                function switchMode(mode) {
                    if (currentLayer) map.removeLayer(currentLayer);
                    currentLayer = modes[mode].layer;
                    currentLayer.addTo(map);
                    modeLabel.textContent = modes[mode].label;
                    document.querySelectorAll('.mode-option').forEach(function (el) {
                        el.classList.toggle('active', el.getAttribute('data-mode') === mode);
                    });
                    // Sync hidden Leaflet layers control
                    var ls = document.querySelector('.leaflet-control-layers');
                    if (ls) {
                        var radios = ls.querySelectorAll('input[type="radio"]');
                        var idx = mode === 'satellite' ? 0 : mode === 'osm' ? 1 : 2;
                        if (radios[idx]) radios[idx].checked = true;
                    }
                }

                modeBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    isOpen = !isOpen;
                    modeOptions.classList.toggle('show', isOpen);
                    modeArrow.style.transform = isOpen ? 'rotate(180deg)' : '';
                });

                modeOptions.addEventListener('click', function (e) {
                    var opt = e.target.closest('.mode-option');
                    if (!opt || opt.classList.contains('active')) return;
                    switchMode(opt.getAttribute('data-mode'));
                    isOpen = false;
                    modeOptions.classList.remove('show');
                    modeArrow.style.transform = '';
                });

                document.addEventListener('click', function () {
                    if (isOpen) {
                        isOpen = false;
                        modeOptions.classList.remove('show');
                        modeArrow.style.transform = '';
                    }
                });
            })();

            var baseMaps = {
                "<i class='fa-solid fa-satellite me-1 text-success'></i> Satelit (Esri)": satelliteLayer,
                "<i class='fa-solid fa-road me-1 text-success'></i> Jalan (OSM)": osmLayer,
                "<i class='fa-solid fa-sun me-1 text-success'></i> Terang (CartoDB)": lightLayer
            };

            L.control.layers(baseMaps, null, { position: 'bottomright', collapsed: true }).addTo(map);
            // Hide layer switcher by default (shown via Tools menu)
            document.querySelector('.leaflet-control-layers').style.display = 'none';

            // ── Tools FAB interaction ──
            (function () {
                var fabBtn = document.getElementById('tools-fab-btn');
                var fabMenu = document.getElementById('tools-menu');
                var fabOpen = false;

                function closeFab() {
                    fabOpen = false;
                    fabBtn.classList.remove('active');
                    fabMenu.classList.remove('show');
                }

                fabBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    fabOpen = !fabOpen;
                    if (fabOpen) {
                        fabBtn.classList.add('active');
                        fabMenu.classList.add('show');
                    } else {
                        closeFab();
                    }
                });

                // Close on outside click
                document.addEventListener('click', function (e) {
                    if (fabOpen && !document.getElementById('tools-fab-wrap').contains(e.target)) {
                        closeFab();
                    }
                });

                // Menu item actions
                fabMenu.addEventListener('click', function (e) {
                    var item = e.target.closest('.tools-menu-item');
                    if (!item) return;
                    var action = item.getAttribute('data-action');
                    closeFab();

                    switch (action) {
                        case 'filter':
                            var fp = document.getElementById('filter-panel');
                            if (fp.classList.contains('hidden')) {
                                closeAllPanelsExcept('filter-panel');
                                fp.classList.remove('hidden');
                            } else {
                                fp.classList.add('hidden');
                            }
                            break;

                        case 'analysis':
                            var ap = document.getElementById('analysis-panel');
                            if (ap.classList.contains('hidden')) {
                                closeAllPanelsExcept('analysis-panel');
                                ap.classList.remove('hidden');
                            } else {
                                ap.classList.add('hidden');
                            }
                            break;

                        case 'layers':
                            var ls = document.querySelector('.leaflet-control-layers');
                            if (ls) {
                                var current = ls.style.display;
                                ls.style.display = current === 'none' ? 'block' : 'none';
                            }
                            break;

                        case 'story':
                            var st = document.getElementById('story-toggle');
                            if (st) st.click();
                            break;

                        case 'gps':
                            if (!navigator.geolocation) {
                                alert('GPS tidak didukung browser ini');
                                return;
                            }
                            item.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i><span class="tmi-label">Mendeteksi...</span>';
                            navigator.geolocation.getCurrentPosition(function (pos) {
                                item.innerHTML = '<i class="fa-solid fa-location-crosshairs"></i><span class="tmi-label">Lokasi Saya</span>';
                                if (window._locationMarker) {
                                    map.removeLayer(window._locationMarker);
                                }
                                Cinematic.flyTo(pos.coords.latitude, pos.coords.longitude, 15, { duration: 1800, zoomOutBy: 1 });
                                window._locationMarker = L.circleMarker([pos.coords.latitude, pos.coords.longitude], {
                                    radius: 8, color: '#1b5e20', fillColor: '#43a047',
                                    fillOpacity: 0.7, weight: 3,
                                }).addTo(map).bindPopup('<strong>Lokasi Saya</strong>').openPopup();
                            }, function () {
                                item.innerHTML = '<i class="fa-solid fa-location-crosshairs"></i><span class="tmi-label">Lokasi Saya</span>';
                                alert('Gagal mendapatkan lokasi. Periksa izin GPS.');
                            }, { enableHighAccuracy: true, timeout: 8000 });
                            break;

                        case 'compass':
                            if (!window._compassOn) {
                                window._compassOn = true;
                                item.innerHTML = '<i class="fa-solid fa-location-arrow" style="color:#1b5e20"></i><span class="tmi-label">Kompas Aktif</span>';
                                L.control({ position: 'bottomright' }).onAdd = function () {
                                    var d = L.DomUtil.create('div', 'leaflet-bar leaflet-control');
                                    d.innerHTML = '<a style="cursor:default;display:flex;align-items:center;justify-content:center;width:40px;height:40px;border-radius:50%;background:rgba(255,255,255,0.94);backdrop-filter:blur(20px);box-shadow:0 2px 8px rgba(0,0,0,0.08);"><svg viewBox="0 0 24 24" width="22" height="22"><polygon points="12,2 16,11 12,9 8,11" fill="#d32f2f"/><polygon points="12,22 8,13 12,15 16,13" fill="#f5f5f5"/><polygon points="12,9 16,11 12,2" fill="#b71c1c"/><polygon points="12,15 8,13 12,22" fill="#e0e0e0"/><circle cx="12" cy="12" r="1.5" fill="#333"/></svg></a>';
                                    return d;
                                };
                            } else {
                                window._compassOn = false;
                                item.innerHTML = '<i class="fa-solid fa-location-arrow"></i><span class="tmi-label">Kompas</span>';
                            }
                            break;

                        case 'home':
                            Cinematic.flyTo(BILEBANTE_CENTER[0], BILEBANTE_CENTER[1], 15, { duration: 2500, zoomOutBy: 2 });
                            break;

                        case 'measure':
                            alert('Fitur Ukur Jarak akan segera tersedia.');
                            break;

                        case 'fullscreen':
                            if (!document.fullscreenElement) {
                                document.documentElement.requestFullscreen().catch(function () {});
                            } else {
                                document.exitFullscreen().catch(function () {});
                            }
                            break;

                        case 'download':
                            var btnGeo = document.getElementById('btn-export-geojson');
                            if (btnGeo) {
                                btnGeo.click();
                            } else {
                                alert('Export GeoJSON tidak tersedia saat ini.');
                            }
                            break;
                    }
                });
            })();

            // ── Legend card: click "Batas Desa" to recenter ──
            document.querySelector('#info-card .legend-item:first-child').addEventListener('click', function () {
                Cinematic.flyTo(BILEBANTE_CENTER[0], BILEBANTE_CENTER[1], 15, { duration: 2500, zoomOutBy: 2 });
            });

            // ────────────────────────────────────────────────
            // 6. LAYER — BATAS DESA
            // ────────────────────────────────────────────────

            var villageLayer = L.layerGroup().addTo(map);

            fetch('/api/boundary', {
                headers: {
                    'Accept': 'application/json',
                    'X-Tunnel-Skip-AntiPhishing-Page': 'true'
                }
            })
                .then(function (res) {
                    if (!res.ok) throw new Error('HTTP ' + res.status);
                    return res.json();
                })
                .then(function (geojson) {
                    if (!geojson || geojson.type !== 'FeatureCollection' || !geojson.features.length) {
                        throw new Error(LANG.invalid_geojson);
                    }

                    var props = geojson.features[0].properties || {};

                    var geoLayer = L.geoJSON(geojson, {
                        style: {
                            color: '#4caf50',
                            weight: 3,
                            opacity: 0.9,
                            fillColor: '#4caf50',
                            fillOpacity: 0.04,
                        },
                    });

                    villageLayer.addLayer(geoLayer);

                    // Zoom ke batas desa (tanpa mengunci navigasi)
                    map.setView(BILEBANTE_CENTER, 15);

                    // Village boundary popup removed to prevent干扰 on mobile

                    L.marker(center, { icon: centroidIcon, zIndexOffset: 1000 })
                        .addTo(villageLayer)
                        .bindPopup('<strong style="color:#1a5e2a;">' + safeVillageName + '</strong>', {
                            className: 'village-popup',
                            closeButton: false,
                            offset: [0, -8],
                        });
                })
                .catch(function (err) {
                    console.error('Gagal muat batas desa:', err);
                });

            // ────────────────────────────────────────────────
            // 7. LAYER — MARKER TEMPAT
            // ────────────────────────────────────────────────

            var markerCluster = L.layerGroup();
            markerCluster.addTo(map);
            var allMarkers = []; // simpan referensi marker untuk filter
            var placesList = []; // daftar tempat untuk pencarian
            var analysisLayerGroup = L.layerGroup().addTo(map); // layer untuk heatmap, buffer, centroid

            var kategoriList = [];

            fetch('/api/places', {
                headers: {
                    'Accept': 'application/json',
                    'X-Tunnel-Skip-AntiPhishing-Page': 'true'
                }
            })
                .then(function (res) {
                    if (!res.ok) throw new Error('HTTP ' + res.status);
                    return res.json();
                })
                .then(function (geojson) {
                    if (!geojson || !geojson.features) return;

                    var catSet = {};

                    L.geoJSON(geojson, {
                        pointToLayer: function (feature, latlng) {
                            var category = feature.properties.category || 'Lainnya';
                            var color = MARKER_COLORS[category] || DEFAULT_COLOR;
                            // Hitung per kategori
                            catSet[category] = (catSet[category] || 0) + 1;

                            var divIcon = makeModernMarkerIcon(color, category);

                            var marker = L.marker(latlng, {
                                icon: divIcon,
                            });

                            // Simpan kategori dan ID untuk filtering & edit cepat
                            marker._category = category;
                            marker._id = feature.properties.id;
                            allMarkers.push(marker);

                            return marker;
                        },
                        onEachFeature: function (feature, layer) {
                            var p = feature.properties;
                            var lat = p.latitude || feature.geometry.coordinates[1];
                            var lng = p.longitude || feature.geometry.coordinates[0];
                            var safeName = escapeHtml(p.name || '');
                            var safeCategory = escapeHtml(p.category || '');
                            var safeDesc = escapeHtml(p.description || LANG.no_description);
                            var safeImgUrl = escapeHtml(p.image_url || '');
                            var imgHtml = safeImgUrl
                                ? '<img class="popup-image" src="' + safeImgUrl +
                                '" alt="' + safeName + '" onerror="this.style.display=\'none\'">'
                                : '';

                            var detailUrl = '/place/' + p.id;

                            // Tambahkan tombol admin edit cepat jika IS_ADMIN true
                            var adminBtn = '';
                            if (IS_ADMIN) {
                                adminBtn = '<button class="btn btn-sm btn-warning flex-fill fw-semibold btn-quick-edit" data-id="' + p.id + '">' +
                                    '✏️ Edit' +
                                    '</button>';
                            }

                            // Tooltip dihapus — digantikan oleh hover info card di luar map

                            var randRating = (4 + Math.random()).toFixed(1);
                            var catColor = CATEGORY_COLORS[p.category] || CATEGORY_COLORS[DEFAULT_CATEGORY];
                            var catSvgPath = CATEGORY_SVG[p.category] || CATEGORY_SVG[DEFAULT_CATEGORY];
                            var catBadgeSvg = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;margin-right:4px;">' + catSvgPath + '</svg>';
                            var popupContent =
                                '<div class="popup-card">' +
                                (safeImgUrl ? '<img class="popup-card-img" src="' + safeImgUrl + '" alt="' + safeName + '" onerror="this.style.display=\'none\'">' : '<div style="height:155px;background:linear-gradient(135deg,#e8f5e9,#c8e6c9);display:flex;align-items:center;justify-content:center;color:#a0c0a0;"><svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#a0c0a0" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg></div>') +
                                '<div class="popup-card-body">' +
                                '<div class="popup-category" style="background:' + catColor + ';">' + catBadgeSvg + safeCategory + '</div>' +
                                '<h5>' + safeName + '</h5>' +
                                '<div class="popup-rating"><i class="fa-solid fa-star"></i> ' + randRating + ' <span style="color:#94a3b8;font-weight:400;font-size:0.7rem;">(' + Math.floor(Math.random() * 50 + 10) + ' ulasan)</span></div>' +
                                '<div class="popup-desc">' + safeDesc + '</div>' +
                                '<div class="popup-card-actions">' +
                                '<a href="' + detailUrl + '" class="btn-popup btn-popup-detail">Lihat Detail</a>' +
                                '<button class="btn-popup btn-popup-route btn-route-place" ' +
                                'data-lat="' + lat + '" data-lng="' + lng + '" data-name="' + safeName.replace(/"/g, '&quot;') + '">' +
                                '<i class="fa-solid fa-route"></i>' +
                                '</button>' +
                                (adminBtn ? adminBtn : '') +
                                '</div>' +
                                '</div>' +
                                '</div>';

                            // Buttons inside popup need default styling — adjust adminBtn
                            if (IS_ADMIN) {
                                // Replace raw button with styled version
                                popupContent = popupContent.replace(
                                    '<button class="btn btn-sm btn-warning flex-fill fw-semibold btn-quick-edit"',
                                    '<button class="btn-popup btn-popup-edit"'
                                );
                            }

                            layer.bindPopup(popupContent, {
                                maxWidth: 280,
                                className: 'leaflet-popup-custom',
                            });

                            // Hover info card + connector line
                            layer.on('mouseover', function () {
                                var card = document.getElementById('hover-info-card');
                                var imgEl = document.getElementById('hover-info-img');
                                var catEl = document.getElementById('hover-info-category');
                                var nameEl = document.getElementById('hover-info-name');
                                var descEl = document.getElementById('hover-info-desc');
                                var connector = document.getElementById('hover-connector');

                                var color = CATEGORY_COLORS[p.category] || CATEGORY_COLORS[DEFAULT_CATEGORY];

                                catEl.textContent = safeCategory;
                                catEl.style.background = color;
                                nameEl.textContent = safeName;
                                descEl.textContent = safeDesc || LANG.no_description;
                                if (safeImgUrl) {
                                    imgEl.style.backgroundImage = 'url(' + safeImgUrl + ')';
                                    imgEl.style.display = 'block';
                                } else {
                                    imgEl.style.display = 'none';
                                }

                                card.classList.remove('hidden');
                                connector.classList.remove('hidden');

                                window._hoveredLayer = layer;
                                // Tunggu reflow layout card sebelum mengukur posisi
                                requestAnimationFrame(function () {
                                    updateHoverConnector(layer, card);
                                });
                            });

                            layer.on('mouseout', function () {
                                document.getElementById('hover-info-card').classList.add('hidden');
                                document.getElementById('hover-connector').classList.add('hidden');
                                document.getElementById('hover-connector-path').setAttribute('d', '');
                                document.getElementById('hover-connector-outline').setAttribute('d', '');
                                window._hoveredLayer = null;
                            });
                        }
                    });

                    // Tambah semua marker ke cluster group
                    for (var m = 0; m < allMarkers.length; m++) {
                        markerCluster.addLayer(allMarkers[m]);
                    }

                    // Animated marker drops (staggered)
                    for (var md = 0; md < Math.min(allMarkers.length, 30); md++) {
                        (function (idx) {
                            setTimeout(function () {
                                Cinematic.dropMarker(allMarkers[idx], 0);
                            }, idx * 60);
                        })(md);
                    }

                    // Bangun daftar kategori unik
                    kategoriList = Object.keys(catSet).sort();
                    renderFilter(kategoriList, catSet);

                    // Bangun daftar pencarian
                    if (geojson.features) {
                        for (var f = 0; f < geojson.features.length; f++) {
                            var ft = geojson.features[f];
                            var fp = ft.properties;
                            placesList.push({
                                id: fp.id,
                                name: fp.name,
                                lat: fp.latitude || ft.geometry.coordinates[1],
                                lng: fp.longitude || ft.geometry.coordinates[0],
                                category: fp.category,
                            });
                        }
                    }

                    // Populate legend card with categories
                    renderLegendCard(catSet);

                    hideLoading();

                })
                .catch(function (err) {
                    console.error('Gagal muat marker:', err);
                    showError('Gagal memuat data tempat.');
                });

            // ────────────────────────────────────────────────
            // 8. FILTER KATEGORI
            // ────────────────────────────────────────────────

            function renderFilter(kategori, counts) {
                filterList.innerHTML = '';

                // "Semua" checkbox
                var allItem = document.createElement('label');
                allItem.className = 'filter-item';
                allItem.innerHTML =
                    '<input type="checkbox" id="filter-all" checked>' +
                    '<strong>' + LANG.filter_all + '</strong>';
                filterList.appendChild(allItem);

                var allCheckbox = allItem.querySelector('input');
                allCheckbox.addEventListener('change', function () {
                    var checked = this.checked;
                    var catChecks = filterList.querySelectorAll('.filter-cat-checkbox');
                    catChecks.forEach(function (cb) {
                        cb.checked = checked;
                    });
                    applyFilter();
                });

                // Tiap kategori — uses same SVG icon as marker & legend
                kategori.forEach(function (cat) {
                    var count = counts[cat] || 0;
                    var color = CATEGORY_COLORS[cat] || CATEGORY_COLORS[DEFAULT_CATEGORY];
                    var svgPath = CATEGORY_SVG[cat] || CATEGORY_SVG[DEFAULT_CATEGORY];

                    var item = document.createElement('label');
                    item.className = 'filter-item';
                    item.innerHTML =
                        '<input type="checkbox" class="filter-cat-checkbox" data-cat="' + cat + '" checked>' +
                        '<span class="filter-icon-wrap" style="background:' + color + ';">' +
                        '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">' +
                        svgPath +
                        '</svg></span>' +
                        '<span class="filter-label">' + cat + '</span>' +
                        '<span class="filter-count">' + count + '</span>';

                    filterList.appendChild(item);

                    var checkbox = item.querySelector('input');
                    checkbox.addEventListener('change', function () {
                        // Update "Semua" state
                        var allChecked = true;
                        var anyChecked = false;
                        var catChecks = filterList.querySelectorAll('.filter-cat-checkbox');
                        catChecks.forEach(function (cb) {
                            if (cb.checked) anyChecked = true;
                            else allChecked = false;
                        });
                        allCheckbox.checked = allChecked;

                        // Pastikan minimal satu terpilih
                        if (!anyChecked) {
                            this.checked = true;
                            allCheckbox.checked = false;
                            return;
                        }

                        applyFilter();
                    });

                    item.addEventListener('click', function (e) {
                        if (e.target.tagName !== 'INPUT') {
                            var cb = this.querySelector('input');
                            cb.checked = !cb.checked;
                            cb.dispatchEvent(new Event('change'));
                        }
                    });
                });

                // Event untuk "Semua"
                allCheckbox.addEventListener('change', function () {
                    var catChecks = filterList.querySelectorAll('.filter-cat-checkbox');
                    catChecks.forEach(function (cb) {
                        cb.checked = this.checked;
                    }, this);
                    applyFilter();
                });
            }

            function applyFilter() {
                var activeCategories = [];
                var catChecks = filterList.querySelectorAll('.filter-cat-checkbox:checked');
                catChecks.forEach(function (cb) {
                    activeCategories.push(cb.getAttribute('data-cat'));
                });

                markerCluster.clearLayers();
                allMarkers.forEach(function (marker) {
                    var show = activeCategories.indexOf(marker._category) !== -1;
                    if (show) {
                        markerCluster.addLayer(marker);
                    }
                });
            }

            // ────────────────────────────────────────────────
            // 9. SEARCH
            // ────────────────────────────────────────────────

            var searchInput = document.getElementById('search-input');
            var searchResults = document.getElementById('search-results');

            searchInput.addEventListener('input', function () {
                var query = this.value.trim().toLowerCase();
                searchResults.innerHTML = '';
                searchResults.classList.remove('show');

                if (query.length < 1) return;

                var matches = [];
                for (var i = 0; i < placesList.length; i++) {
                    if (placesList[i].name.toLowerCase().indexOf(query) !== -1) {
                        matches.push(placesList[i]);
                    }
                }

                if (matches.length === 0) return;

                var limit = matches.length > 10 ? 10 : matches.length;
                for (var j = 0; j < limit; j++) {
                    var item = document.createElement('a');
                    item.className = 'list-group-item list-group-item-action';
                    item.href = '#';
                    item.setAttribute('data-lat', matches[j].lat);
                    item.setAttribute('data-lng', matches[j].lng);
                    var safeMatchName = escapeHtml(matches[j].name);
                    var safeMatchCat = escapeHtml(matches[j].category);
                    var matchColor = CATEGORY_COLORS[matches[j].category] || CATEGORY_COLORS[DEFAULT_CATEGORY];
                    var matchSvg = CATEGORY_SVG[matches[j].category] || CATEGORY_SVG[DEFAULT_CATEGORY];
                    item.innerHTML = '<span class="search-icon-wrap" style="background:' + matchColor + ';"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' + matchSvg + '</svg></span><span class="search-name">' + safeMatchName + '</span> <small class="search-cat">' + safeMatchCat + '</small>';

                    item.addEventListener('click', function (e) {
                        e.preventDefault();
                        var lat = parseFloat(this.getAttribute('data-lat'));
                        var lng = parseFloat(this.getAttribute('data-lng'));
                        searchInput.value = this.textContent.trim();
                        searchResults.innerHTML = '';
                        searchResults.classList.remove('show');
                        flyToPlace(lat, lng);
                    });

                    searchResults.appendChild(item);
                }

                searchResults.classList.add('show');
            });

            searchInput.addEventListener('blur', function () {
                setTimeout(function () {
                    searchResults.innerHTML = '';
                    searchResults.classList.remove('show');
                }, 250);
            });

            searchInput.addEventListener('keydown', function (e) {
                if (e.keyCode === 13) {
                    var firstResult = searchResults.querySelector('.list-group-item');
                    if (firstResult) {
                        firstResult.click();
                    }
                }
            });

            function flyToPlace(lat, lng) {
                for (var i = 0; i < allMarkers.length; i++) {
                    var m = allMarkers[i];
                    var mLat = m.getLatLng().lat;
                    var mLng = m.getLatLng().lng;
                    if (Math.abs(mLat - lat) < 0.0001 && Math.abs(mLng - lng) < 0.0001) {
                        if (!markerCluster.hasLayer(m)) {
                            markerCluster.addLayer(m);
                        }
                        Cinematic.flyTo(lat, lng, 19, {
                            duration: 2500,
                            zoomOutBy: 2,
                            onEnd: function () {
                                Cinematic.pulseOnArrival(m, 1600);
                                setTimeout(function () {
                                    Cinematic.openPopup(m);
                                }, 200);
                            },
                        });
                        break;
                    }
                }
            }

            // ────────────────────────────────────────────────
            // 10. FILTER PANEL
            // ────────────────────────────────────────────────

            // Pastikan panel hidden di awal (mobile first)
            filterPanel.classList.add('hidden');

            // ────────────────────────────────────────────────
            // 11. ANALYSIS SPASIAL
            // ────────────────────────────────────────────────

            var analysisToggle = document.getElementById('analysis-toggle');
            var analysisPanel = document.getElementById('analysis-panel');
            var heatmapCheckbox = document.getElementById('heatmap-toggle');
            var bufferSlider = document.getElementById('buffer-slider');
            var bufferValue = document.getElementById('buffer-value');
            var btnCentroid = document.getElementById('btn-centroid');

            var heatLayer = null;
            var bufferLayer = null;

            // Sembunyikan panel di awal
            analysisPanel.classList.add('hidden');

            document.getElementById('analysis-close').addEventListener('click', function () {
                analysisPanel.classList.add('hidden');
            });

            // ─── A. HEATMAP — Wisata & Ekonomi ──────────────
            heatmapCheckbox.addEventListener('change', function () {
                if (allMarkers.length === 0) return;

                analysisLayerGroup.clearLayers();
                if (heatLayer) {
                    map.removeLayer(heatLayer);
                    heatLayer = null;
                }

                if (!this.checked) return;

                var heatData = [];
                for (var hi = 0; hi < allMarkers.length; hi++) {
                    var hm = allMarkers[hi];
                    var cat = hm._category;
                    if (cat === 'Wisata Alam' || cat === 'UMKM / Ekonomi') {
                        var ll = hm.getLatLng();
                        heatData.push([ll.lat, ll.lng, 0.8]);
                    }
                }

                if (heatData.length > 0) {
                    heatLayer = L.heatLayer(heatData, {
                        radius: 30,
                        blur: 20,
                        maxZoom: 17,
                        max: 1.0,
                        gradient: {
                            0.0: '#313695',
                            0.3: '#74add1',
                            0.5: '#fee090',
                            0.7: '#f46d43',
                            1.0: '#a50026',
                        },
                    }).addTo(map);
                }
            });

            // ─── B. BUFFER — Kesehatan & Infrastruktur ──────
            bufferSlider.addEventListener('input', function () {
                bufferValue.textContent = this.value + ' m';
                if (allMarkers.length > 0) {
                    drawBuffer(parseFloat(this.value));
                }
            });

            function drawBuffer(radiusMeters) {
                if (bufferLayer) {
                    analysisLayerGroup.removeLayer(bufferLayer);
                    bufferLayer = null;
                }

                var bufferPoints = [];
                for (var bi = 0; bi < allMarkers.length; bi++) {
                    var bm = allMarkers[bi];
                    if (bm._category === 'Kesehatan' || bm._category === 'Infrastruktur') {
                        var ll2 = bm.getLatLng();
                        bufferPoints.push(turf.point([ll2.lng, ll2.lat]));
                    }
                }

                if (bufferPoints.length === 0) return;

                var fc = turf.featureCollection(bufferPoints);

                try {
                    var buffered = turf.buffer(fc, radiusMeters, { units: 'meters' });
                    if (buffered) {
                        bufferLayer = L.geoJSON(buffered, {
                            style: {
                                color: '#2196F3',
                                weight: 2,
                                opacity: 0.7,
                                fillColor: '#2196F3',
                                fillOpacity: 0.12,
                            },
                        });
                        analysisLayerGroup.addLayer(bufferLayer);
                    }
                } catch (e) {
                    console.warn('Buffer error:', e);
                }
            }

            // ─── C. CENTROID & BOUNDING BOX ─────────────────
            btnCentroid.addEventListener('click', function () {
                if (allMarkers.length === 0) return;

                // Hapus centroid/bbox sebelumnya
                analysisLayerGroup.clearLayers();

                // Kumpulkan semua koordinat
                var allPoints = [];
                for (var ci = 0; ci < allMarkers.length; ci++) {
                    var cm = allMarkers[ci];
                    var cll = cm.getLatLng();
                    allPoints.push(turf.point([cll.lng, cll.lat]));
                }

                var allFC = turf.featureCollection(allPoints);

                try {
                    // Centroid
                    var centroid = turf.centroid(allFC);
                    var centroidCoords = centroid.geometry.coordinates;

                    var centroidIcon = L.divIcon({
                        className: '',
                        html: '<div style="background:#ff5722;width:28px;height:28px;border-radius:50%;border:3px solid white;box-shadow:0 2px 12px rgba(0,0,0,0.4);display:flex;align-items:center;justify-content:center;font-size:14px;color:white;">&#9733;</div>',
                        iconSize: [28, 28],
                        iconAnchor: [14, 14],
                    });

                    L.marker([centroidCoords[1], centroidCoords[0]], { icon: centroidIcon })
                        .addTo(analysisLayerGroup)
                        .bindPopup('<strong>' + LANG.centroid_label + '</strong><br>Centroid dari ' + allMarkers.length + ' titik.');

                    // Bounding box
                    var bbox = turf.bbox(allFC);
                    var bboxPolygon = turf.bboxPolygon(bbox);

                    L.geoJSON(bboxPolygon, {
                        style: {
                            color: '#ff5722',
                            weight: 2,
                            opacity: 0.5,
                            fillColor: '#ff5722',
                            fillOpacity: 0.04,
                            dashArray: '8 6',
                        },
                    }).addTo(analysisLayerGroup);

                    // Zoom ke bounding box
                    map.fitBounds([
                        [bbox[1], bbox[0]],
                        [bbox[3], bbox[2]],
                    ], { padding: [30, 30], maxZoom: 15 });
                } catch (e) {
                    console.warn('Centroid error:', e);
                }
            });

            // ────────────────────────────────────────────────
            // 12. RENDER LEGEND CARD (bottom-left) — uses same SVG icons as markers
            // ────────────────────────────────────────────────

            function makeLegendPin(color, category) {
                var svgPath = CATEGORY_SVG[category] || CATEGORY_SVG[DEFAULT_CATEGORY];
                return '<svg width="12" height="16" viewBox="0 0 44 54" style="display:block;flex-shrink:0;">' +
                    '<path d="M22 53C22 53 4 33 4 18C4 8.2 12.2 0 22 0C31.8 0 40 8.2 40 18C40 33 22 53 22 53Z" fill="' + color + '" stroke="#FFFFFF" stroke-width="2.5"/>' +
                    '<circle cx="22" cy="17" r="10" fill="#FFFFFF"/>' +
                    '<g transform="translate(22,17) scale(0.44) translate(-12,-12)">' +
                    svgPath +
                    '</g>' +
                    '</svg>';
            }

            function renderLegendCard(catSet) {
                var body = document.getElementById('legend-body');
                var html = '';
                CATEGORY_ORDER.forEach(function (cat) {
                    var color = CATEGORY_COLORS[cat] || CATEGORY_COLORS[DEFAULT_CATEGORY];
                    var pin = makeLegendPin(color, cat);
                    html += '<div class="legend-item" data-cat="' + cat + '">' +
                        pin +
                        '<span class="legend-label">' + cat + '</span>' +
                        '<span class="legend-count">' + (catSet[cat] || 0) + '</span>' +
                        '</div>';
                });
                body.innerHTML = html;
            }

            // ────────────────────────────────────────────────
            // 13. HOVER CONNECTOR LINE
            // ────────────────────────────────────────────────

            function updateHoverConnector(layer, card) {
                var path = document.getElementById('hover-connector-path');
                var outline = document.getElementById('hover-connector-outline');
                var dot = document.getElementById('hover-connector-dot');
                var dotOutline = document.getElementById('hover-connector-dot-outline');

                if (!layer || !card || card.classList.contains('hidden')) return;

                // Gunakan DOM marker langsung untuk posisi yang akurat
                var iconEl = layer._icon;
                if (!iconEl) return;

                var iconRect = iconEl.getBoundingClientRect();
                var mx = iconRect.left + iconRect.width / 2;
                var my = iconRect.top + iconRect.height * 0.35; // 35% dari atas = area lingkaran putih
                var vpW = window.innerWidth;
                var vpH = window.innerHeight;
                var cardW = 260;
                var cardH = card.offsetHeight || 170;
                var gap = 48;

                card.style.left = '';
                card.style.right = '';
                card.style.top = '';
                card.style.bottom = '';
                card.style.transform = '';

                // Cari arah dengan ruang cukup dari marker (hindari bawah agar tidak nutup marker)
                var distRight = vpW - mx;
                var distLeft = mx;
                var distTop = my;
                var distBottom = vpH - my;

                var cardLeft, cardTop;

                if (distTop >= cardH + gap) {
                    // Atas (prioritas)
                    cardLeft = Math.max(8, Math.min(vpW - cardW - 8, mx - cardW / 2));
                    cardTop = my - cardH - gap;
                } else if (distRight >= cardW + gap && distRight >= distLeft) {
                    // Kanan
                    cardLeft = mx + gap;
                    cardTop = Math.max(8, Math.min(vpH - cardH - 8, my - cardH / 2));
                } else if (distLeft >= cardW + gap) {
                    // Kiri
                    cardLeft = mx - cardW - gap;
                    cardTop = Math.max(8, Math.min(vpH - cardH - 8, my - cardH / 2));
                } else if (distBottom >= cardH + gap) {
                    // Bawah (opsi terakhir)
                    cardLeft = Math.max(8, Math.min(vpW - cardW - 8, mx - cardW / 2));
                    cardTop = my + gap;
                } else {
                    // Fallback: atas dengan penyesuaian
                    cardLeft = Math.max(8, Math.min(vpW - cardW - 8, mx - cardW / 2));
                    cardTop = Math.max(8, my - cardH - gap);
                }

                card.style.left = cardLeft + 'px';
                card.style.top = cardTop + 'px';

                var cardRect = card.getBoundingClientRect();
                var iconRect = iconEl.getBoundingClientRect();

                // Tentukan tepi marker yang menghadap card
                var mEdgeX, mEdgeY;
                var dx = (cardRect.left + cardRect.width / 2) - (iconRect.left + iconRect.width / 2);
                var dy = (cardRect.top + cardRect.height / 2) - (iconRect.top + iconRect.height / 2);

                if (Math.abs(dx) > Math.abs(dy)) {
                    // Kiri/kanan
                    mEdgeX = dx > 0 ? iconRect.right : iconRect.left;
                    mEdgeY = iconRect.top + iconRect.height / 2;
                } else {
                    // Atas/bawah
                    mEdgeX = iconRect.left + iconRect.width / 2;
                    mEdgeY = dy > 0 ? iconRect.bottom : iconRect.top;
                }

                // Tentukan tepi card yang menghadap marker
                var cEdgeX, cEdgeY;
                if (Math.abs(dx) > Math.abs(dy)) {
                    cEdgeX = dx > 0 ? cardRect.left : cardRect.right;
                    cEdgeY = cardRect.top + cardRect.height / 2;
                } else {
                    cEdgeX = cardRect.left + cardRect.width / 2;
                    cEdgeY = dy > 0 ? cardRect.top : cardRect.bottom;
                }

                // Elbow: titik siku antara marker edge dan card edge
                var elbowX, elbowY;
                if (Math.abs(dx) > Math.abs(dy)) {
                    // Siku horizontal dulu, lalu vertikal
                    elbowX = cEdgeX;
                    elbowY = mEdgeY;
                } else {
                    // Siku vertikal dulu, lalu horizontal
                    elbowX = mEdgeX;
                    elbowY = cEdgeY;
                }

                var pathD = 'M' + mEdgeX + ',' + mEdgeY +
                    ' L' + elbowX + ',' + elbowY +
                    ' L' + cEdgeX + ',' + cEdgeY;

                outline.setAttribute('d', pathD);
                path.setAttribute('d', pathD);
                dotOutline.setAttribute('cx', mEdgeX);
                dotOutline.setAttribute('cy', mEdgeY);
                dot.setAttribute('cx', mEdgeX);
                dot.setAttribute('cy', mEdgeY);
            }

            map.on('moveend', function () {
                var card = document.getElementById('hover-info-card');
                if (!card.classList.contains('hidden') && window._hoveredLayer) {
                    updateHoverConnector(window._hoveredLayer, card);
                }
            });

            map.on('zoomend', function () {
                var card = document.getElementById('hover-info-card');
                if (!card.classList.contains('hidden') && window._hoveredLayer) {
                    updateHoverConnector(window._hoveredLayer, card);
                }
            });

            // Update connector on any map interaction
            map.on('mousemove', function () {
                if (window._hoveredLayer) {
                    updateHoverConnector(window._hoveredLayer, document.getElementById('hover-info-card'));
                }
            });

            map.on('drag', function () {
                if (window._hoveredLayer) {
                    updateHoverConnector(window._hoveredLayer, document.getElementById('hover-info-card'));
                }
            });

            // Hide card on map click
            map.on('click', function () {
                document.getElementById('hover-info-card').classList.add('hidden');
                document.getElementById('hover-connector').classList.add('hidden');
                document.getElementById('hover-connector-path').setAttribute('d', '');
                document.getElementById('hover-connector-outline').setAttribute('d', '');
                window._hoveredLayer = null;
            });

            // ────────────────────────────────────────────────
            // 14. RESIZE HANDLER
            // ────────────────────────────────────────────────

            window.addEventListener('resize', function () {
                map.invalidateSize();
                if (window._hoveredLayer) {
                    updateHoverConnector(window._hoveredLayer, document.getElementById('hover-info-card'));
                }
            });

            // ────────────────────────────────────────────────
            // 15. PAKET REKOMENDASI (LIBRARY)
            // ────────────────────────────────────────────────

            var PACKAGES = {
                'budaya': {
                    name: 'Tur Budaya & Edukasi',
                    categories: ['Wisata Alam', 'Pendidikan'],
                },
                'kuliner': {
                    name: 'Kuliner & Relaksasi',
                    categories: ['Wisata Alam', 'UMKM / Ekonomi'],
                },
                'sehat': {
                    name: 'Fasilitas & Layanan',
                    categories: ['Kesehatan', 'Infrastruktur'],
                },
            };

            var urlParams = new URLSearchParams(window.location.search);
            var packageParam = urlParams.get('package');

            if (packageParam && PACKAGES[packageParam]) {
                // Tunggu semua marker ter-load, lalu route
                var pkgCheckInterval = setInterval(function () {
                    if (allMarkers.length > 0) {
                        clearInterval(pkgCheckInterval);
                        setTimeout(function () {
                            routePackage(packageParam);
                        }, 600);
                    }
                }, 200);

                // Batas waktu maksimal
                setTimeout(function () {
                    clearInterval(pkgCheckInterval);
                }, 10000);
            }

            function routePackage(pkgId) {
                var pkg = PACKAGES[pkgId];
                if (!pkg) return;

                var filtered = [];
                for (var ri = 0; ri < allMarkers.length; ri++) {
                    var rm = allMarkers[ri];
                    if (pkg.categories.indexOf(rm._category) !== -1) {
                        filtered.push(rm);
                    }
                }

                if (filtered.length < 2) {
                    showError(LANG.package_min_points.replace(':name', pkg.name));
                    return;
                }

                // Zoom ke semua marker paket dulu
                var group = L.featureGroup(filtered);
                map.fitBounds(group.getBounds(), { padding: [40, 40], maxZoom: 15 });

                // Bangun waypoints
                var waypoints = [];
                for (var wi = 0; wi < filtered.length; wi++) {
                    var ll = filtered[wi].getLatLng();
                    waypoints.push(L.latLng(ll.lat, ll.lng));
                }

                var routingControl = L.Routing.control({
                    waypoints: waypoints,
                    router: L.Routing.osrmv1({
                        serviceUrl: 'https://router.project-osrm.org/route/v1',
                        profile: 'driving',
                    }),
                    lineOptions: {
                        styles: [{
                            color: '#7b2d8e',
                            weight: 5,
                            opacity: 0.8,
                        }],
                    },
                    addWaypoints: false,
                    draggableWaypoints: false,
                    fitSelectedRoutes: false,
                    showAlternatives: false,
                    show: false,
                    createMarker: function () { return null; },
                }).addTo(map);

                // Info ringkas di filter panel
                var pkgInfo = document.createElement('div');
                pkgInfo.id = 'package-info';
                pkgInfo.style.cssText = 'background:#f3e5f5;border-radius:8px;padding:8px 10px;margin-bottom:10px;font-size:12px;';
                pkgInfo.innerHTML =
                    '<strong style="color:#7b2d8e;"><i class="fa-solid fa-route me-1"></i>' +
                    LANG.package_label.replace(':name', pkg.name).replace(':count', filtered.length) + '</strong>';

                var filterPanel = document.getElementById('filter-panel');
                filterPanel.insertBefore(pkgInfo, filterPanel.firstChild);
            }

            // ────────────────────────────────────────────────
            // 15. VIRTUAL STORYTELLING (PETA BERCERITA)
            // ────────────────────────────────────────────────

            var STORY_SPOTS = [
                {
                    id: 'sambutan',
                    title: 'Selamat Datang di Bilebante',
                    text: 'Desa Bilebante terletak di Kecamatan Pringgarata, Kabupaten Lombok Tengah, Nusa Tenggara Barat. Dikelilingi hamparan sawah hijau dan pemandangan Gunung Rinjani, desa ini menyimpan segudang potensi wisata, pendidikan, dan ekonomi kreatif yang siap Anda jelajahi.',
                    lat: -8.6248,
                    lng: 116.1882,
                    zoom: 14,
                    icon: 'fa-house-chimney',
                },
                {
                    id: 'wisata',
                    title: 'Pesona Wisata Alam',
                    text: 'Dari air terjun tersembunyi hingga bukit hijau dengan panorama sawah terasering, Bilebante menawarkan pengalaman wisata alam yang autentik. Spot-spot favorit warga lokal menjadi destinasi wajib bagi pencinta fotografi dan petualang.',
                    lat: -8.6255,
                    lng: 116.1850,
                    zoom: 17,
                    icon: 'fa-mountain',
                    category: 'Wisata Alam',
                },
                {
                    id: 'pendidikan',
                    title: 'Pusat Belajar & Edukasi',
                    text: 'Bilebante memiliki beberapa lembaga pendidikan yang tersebar di seluruh desa. Mulai dari PAUD hingga sekolah dasar, semuanya mendukung tumbuhnya generasi cerdas di lingkungan pedesaan yang asri.',
                    lat: -8.6210,
                    lng: 116.1900,
                    zoom: 17,
                    icon: 'fa-graduation-cap',
                    category: 'Pendidikan',
                },
                {
                    id: 'kesehatan',
                    title: 'Layanan Kesehatan Warga',
                    text: 'Fasilitas kesehatan seperti posyandu dan puskesmas pembantu tersedia untuk melayani kebutuhan dasar warga Bilebante. Lokasinya strategis dan mudah dijangkau dari berbagai penjuru desa.',
                    lat: -8.6270,
                    lng: 116.1870,
                    zoom: 17,
                    icon: 'fa-heart',
                    category: 'Kesehatan',
                },
                {
                    id: 'ekonomi',
                    title: 'Ekonomi Kreatif & Kuliner',
                    text: 'Produk lokal dan kuliner khas Bilebante menghadirkan cita rasa Lombok yang autentik. Dari anyaman bambu hingga olahan hasil bumi, ekonomi kreatif desa terus tumbuh berkat semangat warganya.',
                    lat: -8.6240,
                    lng: 116.1910,
                    zoom: 17,
                    icon: 'fa-store',
                    category: 'UMKM / Ekonomi',
                },
                {
                    id: 'infrastruktur',
                    title: 'Infrastruktur Desa',
                    text: 'Jalan desa yang terawat, jembatan penghubung, dan fasilitas umum lainnya menunjang mobilitas warga dan wisatawan. Infrastruktur yang baik menjadi fondasi kemajuan Bilebante.',
                    lat: -8.6230,
                    lng: 116.1890,
                    zoom: 17,
                    icon: 'fa-wrench',
                    category: 'Infrastruktur',
                },
                {
                    id: 'penutup',
                    title: 'Jelajahi Sekarang',
                    text: 'Itulah sekilas potensi Desa Bilebante. Gunakan peta interaktif untuk menjelajahi setiap sudut desa, temukan tempat favorit Anda, dan rencanakan kunjungan wisata yang tak terlupakan!',
                    lat: -8.6248,
                    lng: 116.1882,
                    zoom: 14,
                    icon: 'fa-flag-checkered',
                },
            ];

            var storyToggle = document.getElementById('story-toggle');
            var storySidebar = document.getElementById('story-sidebar');
            var storyBody = document.getElementById('story-body');
            var storyClose = document.getElementById('story-close');

            function buildStoryContent() {
                var html = '';
                for (var si = 0; si < STORY_SPOTS.length; si++) {
                    var spot = STORY_SPOTS[si];
                    html +=
                        '<div class="story-paragraph" data-id="' + spot.id + '" data-lat="' + spot.lat + '" data-lng="' + spot.lng + '" data-zoom="' + spot.zoom + '">' +
                        '<div class="story-title"><span class="story-icon"><i class="fa-solid ' + spot.icon + '"></i></span>' + spot.title + '</div>' +
                        '<div class="story-text">' + spot.text + '</div>' +
                        '</div>';
                }
                storyBody.innerHTML = html;

                // Event listener tiap paragraf
                var paragraphs = storyBody.querySelectorAll('.story-paragraph');
                for (var pi = 0; pi < paragraphs.length; pi++) {
                    paragraphs[pi].addEventListener('click', function () {
                        var lat = parseFloat(this.getAttribute('data-lat'));
                        var lng = parseFloat(this.getAttribute('data-lng'));
                        var zoom = parseInt(this.getAttribute('data-zoom'), 10);

                        // Cari marker terdekat
                        var closest = null;
                        var minDist = Infinity;
                        for (var mi = 0; mi < allMarkers.length; mi++) {
                            var ml = allMarkers[mi].getLatLng();
                            var d = Math.sqrt(
                                Math.pow(ml.lat - lat, 2) + Math.pow(ml.lng - lng, 2)
                            );
                            if (d < minDist) {
                                minDist = d;
                                closest = allMarkers[mi];
                            }
                        }

                        Cinematic.flyTo(lat, lng, zoom, {
                            duration: 2500,
                            zoomOutBy: 1.5,
                            onEnd: function () {
                                if (closest && minDist < 0.002) {
                                    Cinematic.pulseOnArrival(closest, 1600);
                                    setTimeout(function () {
                                        Cinematic.openPopup(closest);
                                    }, 200);
                                }
                            },
                        });
                    });
                }
            }

            storyToggle.addEventListener('click', function () {
                var isActive = storySidebar.classList.contains('show');
                if (isActive) {
                    storySidebar.classList.remove('show');
                    storyToggle.classList.remove('active');
                } else {
                    closeAllPanelsExcept('story-sidebar');
                    storySidebar.classList.add('show');
                    storyToggle.classList.add('active');
                    if (!storyBody.hasChildNodes()) {
                        buildStoryContent();
                    }
                }
            });

            storyClose.addEventListener('click', function () {
                storySidebar.classList.remove('show');
                storyToggle.classList.remove('active');
            });

            // ────────────────────────────────────────────────
            // 16. ROUTING & NAVIGASI
            // ────────────────────────────────────────────────

            var routePlaces = @json($placesJson);
            var rOriginLat = null, rOriginLng = null;
            var rDestLat = null, rDestLng = null, rDestName = '';
            var rRoutingControl = null;
            var rCurrentRoute = null;
            var rLiveMarker = null;
            var rWatchId = null;
            var rIsTracking = false;
            var rIsSelectingDest = false;
            var rOriginMarker = null;
            var rDestMarker = null;

            /* DOM refs */
            var routePanel = document.getElementById('route-panel');
            var originInput = document.getElementById('origin-input');
            var destInput = document.getElementById('dest-input');
            var originSuggest = document.getElementById('origin-suggestions');
            var destSuggest = document.getElementById('dest-suggestions');
            var btnGpsOrigin = document.getElementById('btn-gps-origin');
            var btnGpsDest = document.getElementById('btn-gps-dest');
            var btnCalculate = document.getElementById('btn-calculate');
            var rpResult = document.getElementById('rp-result');
            var riDistance = document.getElementById('ri-distance');
            var riDuration = document.getElementById('ri-duration');
            var riEta = document.getElementById('ri-eta');
            var rpProgress = document.getElementById('rp-progress');
            var progressFill = document.getElementById('progress-fill');
            var progressTraveled = document.getElementById('progress-traveled');
            var progressRemaining = document.getElementById('progress-remaining');
            var trackingStatus = document.getElementById('tracking-status');
            var trackingText = document.getElementById('tracking-text');
            var btnTracking = document.getElementById('btn-start-tracking');
            var turnList = document.getElementById('turn-list');
            var turnItems = document.getElementById('turn-items');

            /* Helper functions */
            function formatDistance(m) {
                return m >= 1000 ? (m / 1000).toFixed(1) + ' km' : Math.round(m) + ' m';
            }

            function formatDuration(s) {
                var h = Math.floor(s / 3600);
                var m = Math.floor((s % 3600) / 60);
                if (h > 0) return h + ' jam ' + m + ' menit';
                return m + ' menit';
            }

            function formatTime(date) {
                return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            }

            function makeMarkerIcon(color, icon, size) {
                size = size || 28;
                return L.divIcon({
                    className: '',
                    html: '<div style="background:' + color + ';width:' + size + 'px;height:' + size +
                        'px;border-radius:50%;border:3px solid white;box-shadow:0 2px 12px rgba(0,0,0,0.3);display:flex;align-items:center;justify-content:center;color:white;font-size:' +
                        Math.round(size * 0.45) + 'px;"><i class="fa-solid ' + (icon || 'fa-location-dot') + '"></i></div>',
                    iconSize: [size, size],
                    iconAnchor: [size / 2, size / 2],
                });
            }

            function placeOriginMarker(lat, lng, name) {
                if (rOriginMarker) map.removeLayer(rOriginMarker);
                rOriginMarker = L.marker([lat, lng], { icon: makeMarkerIcon('#2196F3', 'fa-circle-dot') })
                    .addTo(map)
                    .bindPopup('<strong>' + (name || LANG.route_origin_label) + '</strong>', { closeButton: false });
                rOriginLat = lat;
                rOriginLng = lng;
                originInput.value = name || originInput.value;
                checkRouteReady();
            }

            function placeDestMarker(lat, lng, name) {
                if (rDestMarker) map.removeLayer(rDestMarker);
                rDestMarker = L.marker([lat, lng], { icon: makeMarkerIcon('#e74c3c', 'fa-flag-checkered') })
                    .addTo(map)
                    .bindPopup('<strong>' + (name || LANG.route_dest_label) + '</strong>', { closeButton: false });
                rDestLat = lat;
                rDestLng = lng;
                rDestName = name || '';
                if (!destInput.value || name) destInput.value = name || destInput.value;
                Cinematic.flyTo(lat, lng, 15, { duration: 2000, zoomOutBy: 1 });
                checkRouteReady();
            }

            function checkRouteReady() {
                btnCalculate.disabled = !(rOriginLat && rOriginLng && rDestLat && rDestLng);
            }

            /* Autocomplete */
            function showSuggestions(input, suggestEl, isOrigin) {
                var q = input.value.toLowerCase().trim();
                if (!q) { suggestEl.classList.remove('show'); return; }
                var matches = routePlaces.filter(function (p) {
                    return p.name.toLowerCase().indexOf(q) > -1;
                }).slice(0, 6);
                if (matches.length === 0) { suggestEl.classList.remove('show'); return; }
                suggestEl.innerHTML = '';
                matches.forEach(function (p) {
                    var item = document.createElement('div');
                    item.className = 'rp-suggestion-item';
                    item.innerHTML = '<span class="rp-sug-icon"><i class="fa-solid fa-location-dot"></i></span>' +
                        '<span><strong>' + p.name + '</strong><br><small class="text-muted">' + (p.category || '') + '</small></span>';
                    item.addEventListener('click', function () {
                        input.value = p.name;
                        suggestEl.classList.remove('show');
                        if (isOrigin) placeOriginMarker(p.lat, p.lng, p.name);
                        else placeDestMarker(p.lat, p.lng, p.name);
                    });
                    suggestEl.appendChild(item);
                });
                suggestEl.classList.add('show');
            }

            originInput.addEventListener('input', function () { showSuggestions(this, originSuggest, true); });
            destInput.addEventListener('input', function () { showSuggestions(this, destSuggest, false); });

            document.addEventListener('click', function (e) {
                if (!originInput.contains(e.target) && !originSuggest.contains(e.target)) originSuggest.classList.remove('show');
                if (!destInput.contains(e.target) && !destSuggest.contains(e.target)) destSuggest.classList.remove('show');
            });

            /* GPS Origin */
            btnGpsOrigin.addEventListener('click', function () {
                if (!navigator.geolocation) { alert(LANG.route_gps_unsupported); return; }
                var btn = this;
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i>' + LANG.route_detecting;
                navigator.geolocation.getCurrentPosition(function (pos) {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa-solid fa-location-crosshairs me-1"></i>' + LANG.route_my_location;
                    placeOriginMarker(pos.coords.latitude, pos.coords.longitude, LANG.route_my_location);
                    Cinematic.flyTo(pos.coords.latitude, pos.coords.longitude, 15, { duration: 1800, zoomOutBy: 1 });
                }, function () {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa-solid fa-location-crosshairs me-1"></i>' + LANG.route_my_location;
                    alert(LANG.route_gps_error);
                }, { enableHighAccuracy: true, timeout: 10000 });
            });

            /* Pilih tujuan di peta */
            btnGpsDest.addEventListener('click', function () {
                rIsSelectingDest = !rIsSelectingDest;
                this.style.borderColor = rIsSelectingDest ? '#f9a825' : '#dadce0';
                this.style.background = rIsSelectingDest ? '#fff8e1' : '#fff';
                this.style.color = rIsSelectingDest ? '#e65100' : '#3c4043';
                this.innerHTML = rIsSelectingDest
                    ? '<i class="fa-solid fa-magnifying-glass-location me-1"></i>' + LANG.route_click_map
                    : '<i class="fa-solid fa-location-dot me-1"></i>' + LANG.route_pick_map;
                map.getContainer().style.cursor = rIsSelectingDest ? 'crosshair' : '';
            });

            map.on('click', function (e) {
                if (rIsSelectingDest) {
                    rIsSelectingDest = false;
                    btnGpsDest.style.borderColor = '#dadce0';
                    btnGpsDest.style.background = '#fff';
                    btnGpsDest.style.color = '#3c4043';
                    btnGpsDest.innerHTML = '<i class="fa-solid fa-location-dot me-1"></i>' + LANG.route_pick_map;
                    map.getContainer().style.cursor = '';
                    placeDestMarker(e.latlng.lat, e.latlng.lng, LANG.route_dest_map);
                }
            });

            /* Swap */
            window.swapLocations = function () {
                var tLat = rOriginLat, tLng = rOriginLng, tName = originInput.value;
                if (rDestLat && rDestLng) {
                    placeOriginMarker(rDestLat, rDestLng, destInput.value);
                } else {
                    if (rOriginMarker) map.removeLayer(rOriginMarker);
                    rOriginLat = null; rOriginLng = null;
                    originInput.value = '';
                }
                if (tLat && tLng) {
                    placeDestMarker(tLat, tLng, tName);
                } else {
                    if (rDestMarker) map.removeLayer(rDestMarker);
                    rDestLat = null; rDestLng = null;
                    destInput.value = '';
                }
                clearRoute();
                checkRouteReady();
            };

            /* Hitung rute */
            btnCalculate.addEventListener('click', function () {
                if (!rOriginLat || !rOriginLng || !rDestLat || !rDestLng) return;
                calculateRoute(rOriginLat, rOriginLng, rDestLat, rDestLng);
            });

            function calculateRoute(fromLat, fromLng, toLat, toLng) {
                clearRoute();

                rRoutingControl = L.Routing.control({
                    waypoints: [L.latLng(fromLat, fromLng), L.latLng(toLat, toLng)],
                    router: L.Routing.osrmv1({
                        serviceUrl: 'https://router.project-osrm.org/route/v1',
                        profile: 'driving',
                    }),
                    lineOptions: {
                        styles: [{ color: '#1b5e20', weight: 6, opacity: 0.85 }],
                        extendToWaypoints: true,
                        missingRouteStyles: [{ color: '#f39c12', weight: 4, opacity: 0.6, dashArray: '8,12' }],
                    },
                    addWaypoints: false,
                    draggableWaypoints: false,
                    fitSelectedRoutes: true,
                    showAlternatives: false,
                    show: false,
                    createMarker: function () { return null; },
                }).addTo(map);

                rRoutingControl.on('routesfound', function (e) {
                    rCurrentRoute = e.routes[0];
                    if (!rCurrentRoute) return;
                    var summary = rCurrentRoute.summary;
                    riDistance.textContent = formatDistance(summary.totalDistance);
                    riDuration.textContent = formatDuration(summary.totalTime);
                    var eta = new Date(Date.now() + summary.totalTime * 1000);
                    riEta.textContent = formatTime(eta);
                    rpResult.classList.add('show');
                    rpResult.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    renderTurns(rCurrentRoute);
                    rpProgress.classList.remove('show');
                    progressFill.style.width = '0%';
                    if (rIsTracking) updateProgress();
                });

                var btn = btnCalculate;
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i>' + LANG.route_searching;
                setTimeout(function () {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa-solid fa-magnifying-glass-location me-1"></i> ' + LANG.route_calculate;
                }, 3000);
            }

            function clearRoute() {
                if (rRoutingControl) { map.removeControl(rRoutingControl); rRoutingControl = null; }
                rCurrentRoute = null;
                rpResult.classList.remove('show');
                turnList.classList.remove('show');
                rpProgress.classList.remove('show');
            }

            /* Petunjuk arah */
            function renderTurns(route) {
                var instructions = route.instructions || [];
                turnItems.innerHTML = '';
                if (instructions.length === 0) { turnList.classList.remove('show'); return; }
                var iconMap = {
                    'Head': 'fa-arrow-up', 'Continue': 'fa-arrow-up', 'Turn': 'fa-turn-down',
                    'Sharp': 'fa-turn-down', 'Slight': 'fa-turn-down', 'Roundabout': 'fa-rotate-right',
                    'Fork': 'fa-code-branch', 'Merge': 'fa-code-merge', 'End': 'fa-flag-checkered',
                    'Destination': 'fa-flag-checkered',
                };
                instructions.forEach(function (inst) {
                    if (!inst.text && inst.type !== 'Destination') return;
                    var dir = inst.type || 'Continue';
                    var iconClass = iconMap[dir] || 'fa-arrow-up';
                    var item = document.createElement('div');
                    item.className = 'rp-turn-item';
                    item.innerHTML =
                        '<div class="rp-turn-icon"><i class="fa-solid ' + iconClass + '"></i></div>' +
                        '<div class="rp-turn-text">' + (inst.text || LANG.route_at_destination) + '</div>' +
                        '<div class="rp-turn-dist">' + (inst.distance ? formatDistance(inst.distance) : '') + '</div>';
                    turnItems.appendChild(item);
                });
                turnList.classList.add('show');
            }

            /* Tracking real-time */
            btnTracking.addEventListener('click', function () {
                if (rIsTracking) stopTracking();
                else startTracking();
            });

            function startTracking() {
                if (!navigator.geolocation) { alert(LANG.route_gps_unsupported); return; }
                if (!rCurrentRoute) { alert(LANG.route_calculate_first); return; }
                var rFirstFix = true;

                rWatchId = navigator.geolocation.watchPosition(
                    function (pos) {
                        var lat = pos.coords.latitude;
                        var lng = pos.coords.longitude;
                        if (rLiveMarker) {
                            rLiveMarker.setLatLng([lat, lng]);
                        } else {
                            rLiveMarker = L.marker([lat, lng], {
                                icon: L.divIcon({
                                    className: '',
                                    html: '<div class="live-marker"><i class="fa-solid fa-location-crosshairs"></i></div>',
                                    iconSize: [20, 20],
                                    iconAnchor: [10, 10],
                                }),
                                zIndexOffset: 10000,
                            }).addTo(map);
                        }
                        if (rFirstFix) {
                            rFirstFix = false;
                            Cinematic.flyTo(lat, lng, map.getZoom(), { duration: 1200, zoomOutBy: 0 });
                        }

                        rIsTracking = true;
                        trackingStatus.className = 'rp-track-status active';
                        trackingStatus.querySelector('.rp-track-dot').className = 'rp-track-dot active';
                        trackingText.textContent = LANG.route_tracking_active;
                        btnTracking.innerHTML = '<i class="fa-solid fa-stop-circle me-1"></i> Hentikan Lacak';
                        btnTracking.className = 'rp-track-btn text-danger border-danger fw-bold';
                        btnTracking.style.borderRadius = '8px';

                        rpProgress.classList.add('show');
                        updateProgress();
                    },
                    function (err) {
                        console.error('GPS error:', err);
                        stopTracking();
                        alert(LANG.route_gps_lost);
                    },
                    { enableHighAccuracy: true, timeout: 8000, maximumAge: 2000 }
                );
            }

            function stopTracking() {
                if (rWatchId !== null) {
                    navigator.geolocation.clearWatch(rWatchId);
                    rWatchId = null;
                }
                rIsTracking = false;
                trackingStatus.className = 'rp-track-status inactive';
                trackingStatus.querySelector('.rp-track-dot').className = 'rp-track-dot inactive';
                trackingText.textContent = 'Lacak posisi real-time';
                btnTracking.innerHTML = '<i class="fa-solid fa-satellite-dish me-1"></i> Mulai Lacak Real-time';
                btnTracking.className = 'rp-track-btn fw-bold';
                btnTracking.style.borderRadius = '8px';
            }

            function updateProgress() {
                if (!rCurrentRoute || !rLiveMarker) return;
                var coords = rCurrentRoute.coordinates;
                if (!coords || coords.length < 2) return;
                var pos = rLiveMarker.getLatLng();
                var totalDist = rCurrentRoute.summary.totalDistance;
                var minDist = Infinity, closestIdx = 0;
                for (var i = 0; i < coords.length - 1; i++) {
                    var segDist = pos.distanceTo(coords[i]);
                    if (segDist < minDist) { minDist = segDist; closestIdx = i; }
                }
                var traveled = 0;
                for (var i = 0; i < closestIdx && i < coords.length - 1; i++) {
                    traveled += coords[i].distanceTo(coords[i + 1]);
                }
                var pct = Math.min(100, Math.round((traveled / totalDist) * 100));
                var remaining = Math.max(0, totalDist - traveled);
                progressFill.style.width = pct + '%';
                progressTraveled.textContent = formatDistance(traveled);
                progressRemaining.textContent = formatDistance(remaining);
                if (pct >= 95) {
                    progressFill.style.background = 'linear-gradient(90deg, #4caf50, #ff9800)';
                    trackingText.textContent = LANG.route_almost_there;
                }
                if (pct > 99) {
                    progressFill.style.background = '#4caf50';
                    progressTraveled.textContent = formatDistance(totalDist);
                    progressRemaining.textContent = '0 m';
                    trackingText.textContent = LANG.route_arrived;
                }
            }

            /* Show/hide route panel (Google Maps style) */
            window.showRoutePanel = function () {
                closeAllPanelsExcept('route-panel');
                routePanel.classList.remove('hidden');
                setTimeout(function () { map.invalidateSize(); }, 350);
            };

            window.hideRoutePanel = function () {
                routePanel.classList.add('hidden');
            };

            /* Open route panel with destination from marker popup */
            window.openRouteToPlace = function (lat, lng, name) {
                placeDestMarker(lat, lng, name);
                closeAllPanelsExcept('route-panel');
                routePanel.classList.remove('hidden');
                var destInputEl = document.getElementById('dest-input');
                if (name) destInputEl.value = name;
                setTimeout(function () { map.invalidateSize(); }, 350);
            };

            // ────────────────────────────────────────────────
            // 17. EKSPOR GEOJSON
            // ────────────────────────────────────────────────

            document.getElementById('btn-export-geojson').addEventListener('click', function () {
                var activeFeatures = [];

                // Kumpulkan marker yang saat ini tampil di kluster
                allMarkers.forEach(function (m) {
                    if (markerCluster.hasLayer(m)) {
                        var latlng = m.getLatLng();
                        activeFeatures.push({
                            type: 'Feature',
                            geometry: {
                                type: 'Point',
                                coordinates: [latlng.lng, latlng.lat]
                            },
                            properties: {
                                name: m.getPopup() ? m.getPopup().getContent().match(/<h5>(.*?)<\/h5>/)[1] : 'Titik Wisata',
                                category: m._category
                            }
                        });
                    }
                });

                if (activeFeatures.length === 0) {
                    alert(LANG.no_export_data);
                    return;
                }

                var geojsonData = {
                    type: 'FeatureCollection',
                    features: activeFeatures
                };

                var blob = new Blob([JSON.stringify(geojsonData, null, 2)], { type: 'application/json' });
                var url = URL.createObjectURL(blob);
                var a = document.createElement('a');
                a.href = url;
                a.download = 'bilebante_potensi_terfilter.geojson';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            });

            // ────────────────────────────────────────────────
            // 18. ADMIN QUICK EDIT & DRAGGABLE MARKER
            // ────────────────────────────────────────────────

            var activeEditMarker = null;

            map.on('popupopen', function (e) {
                var container = e.popup.getElement();
                if (!container) return;

                var btnEdit = container.querySelector('.btn-popup-edit') || container.querySelector('.btn-quick-edit');
                if (btnEdit) {
                    btnEdit.addEventListener('click', function () {
                        var id = this.getAttribute('data-id');
                        openQuickEdit(id);
                    });
                }

                var btnRoute = container.querySelector('.btn-route-place');
                if (btnRoute) {
                    btnRoute.addEventListener('click', function () {
                        var lat = parseFloat(this.getAttribute('data-lat'));
                        var lng = parseFloat(this.getAttribute('data-lng'));
                        var name = this.getAttribute('data-name');
                        window.openRouteToPlace(lat, lng, name);
                    });
                }
            });

            function openQuickEdit(id) {
                var marker = allMarkers.find(function (m) { return m._id == id; });
                if (!marker) return;

                activeEditMarker = marker;

                map.closePopup();

                // Tampilkan modal edit
                document.getElementById('edit-id').value = id;
                
                var popupContent = marker.getPopup().getContent();
                var nameMatch = popupContent.match(/<h5>(.*?)<\/h5>/);
                var catMatch = popupContent.match(/<div class="popup-category[^>]*>.*?<\/svg>([^<]+)<\/div>/);
                var descMatch = popupContent.match(/<div class="popup-description">(.*?)<\/div>/);
                
                var latlng = marker.getLatLng();

                document.getElementById('edit-name').value = nameMatch ? nameMatch[1] : '';
                document.getElementById('edit-category').value = catMatch ? catMatch[1].trim() : 'Umum';
                document.getElementById('edit-lat').value = latlng.lat.toFixed(6);
                document.getElementById('edit-lng').value = latlng.lng.toFixed(6);
                document.getElementById('edit-description').value = descMatch ? descMatch[1] : '';

                // Aktifkan draggable
                markerCluster.removeLayer(marker);
                marker.addTo(map);
                marker.dragging.enable();

                document.getElementById('drag-instruction').classList.remove('d-none');
                document.getElementById('drag-instruction').classList.add('d-flex');

                marker.on('dragend', function (e) {
                    var pos = e.target.getLatLng();
                    document.getElementById('edit-lat').value = pos.lat.toFixed(6);
                    document.getElementById('edit-lng').value = pos.lng.toFixed(6);
                });

                var myModal = new bootstrap.Modal(document.getElementById('quickEditModal'));
                myModal.show();

                document.getElementById('quickEditModal').addEventListener('hidden.bs.modal', function () {
                    if (activeEditMarker) {
                        activeEditMarker.dragging.disable();
                        activeEditMarker.off('dragend');
                        map.removeLayer(activeEditMarker);
                        markerCluster.addLayer(activeEditMarker);
                        activeEditMarker = null;
                    }
                    document.getElementById('drag-instruction').classList.add('d-none');
                    document.getElementById('drag-instruction').classList.remove('d-flex');
                }, { once: true });
            }

            if (IS_ADMIN) {
                document.getElementById('quick-edit-form').addEventListener('submit', function (e) {
                    e.preventDefault();

                    var id = document.getElementById('edit-id').value;
                    var name = document.getElementById('edit-name').value;
                    var category = document.getElementById('edit-category').value;
                    var lat = parseFloat(document.getElementById('edit-lat').value);
                    var lng = parseFloat(document.getElementById('edit-lng').value);
                    var description = document.getElementById('edit-description').value;

                    // Menggunakan standard form fetch dengan method spoofing PUT
                    fetch('/admin/places/' + id + '/quick-update', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            _method: 'PUT',
                            name: name,
                            category: category,
                            latitude: lat,
                            longitude: lng,
                            description: description
                        })
                    })
                    .then(function (res) { return res.json(); })
                    .then(function (data) {
                        if (data.success) {
                            alert(LANG.update_success);
                            
                            if (activeEditMarker) {
                                var color = MARKER_COLORS[category] || DEFAULT_COLOR;
                                
                                activeEditMarker.setIcon(makeModernMarkerIcon(color, category));

                                activeEditMarker.setLatLng([lat, lng]);
                                activeEditMarker._category = category;

                                var safeName = escapeHtml(name);
                                var safeCategory = escapeHtml(category);
                                var safeDesc = escapeHtml(description);
                                var detailUrl = '/place/' + id;
                                var adminBtn = '<button class="btn btn-sm btn-warning flex-fill fw-semibold btn-quick-edit" data-id="' + id + '">✏️ Edit</button>';
                                
                                var catColor2 = CATEGORY_COLORS[category] || CATEGORY_COLORS[DEFAULT_CATEGORY];
                                var catSvg2 = CATEGORY_SVG[category] || CATEGORY_SVG[DEFAULT_CATEGORY];
                                var badgeEl2 = '<span class="popup-category" style="background:' + catColor2 + ';"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:middle;margin-right:4px;">' + catSvg2 + '</svg>' + safeCategory + '</span>';
                                var popupContent =
                                    '<div class="popup-card">' +
                                    '<div class="popup-card-body">' +
                                    badgeEl2 +
                                    '<h5>' + safeName + '</h5>' +
                                    '<div class="popup-desc">' + safeDesc + '</div>' +
                                    '<div class="popup-card-actions">' +
                                    '<a href="' + detailUrl + '" class="btn-popup btn-popup-detail">Detail</a>' +
                                    '<button class="btn-popup btn-popup-route btn-route-place" data-lat="' + lat + '" data-lng="' + lng + '" data-name="' + safeName.replace(/"/g, '&quot;') + '"><i class="fa-solid fa-route"></i> Rute</button>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';

                                activeEditMarker.bindPopup(popupContent, {
                                    maxWidth: 280,
                                    className: 'leaflet-popup-custom',
                                });
                            }

                            var myModalEl = document.getElementById('quickEditModal');
                            var modal = bootstrap.Modal.getInstance(myModalEl);
                            modal.hide();
                        } else {
                            alert(LANG.update_failed + (data.message || 'Error tidak diketahui'));
                        }
                    })
                    .catch(function (err) {
                        console.error('Error quick update:', err);
                        alert(LANG.network_error);
                    });
                });
            }

            // ────────────────────────────────────────────────
            // 19. BFCACHE HANDLER — refresh map saat user klik Back
            // ────────────────────────────────────────────────

            window.addEventListener('pageshow', function (e) {
                if (e.persisted) {
                    map.invalidateSize();
                }
            });

        })();
    </script>

</body>
</html>
