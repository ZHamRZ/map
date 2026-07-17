<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', __('admin.brand'))</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    @stack('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        #map-admin { height: 350px; border-radius: 8px; z-index: 1; }
        .map-wrapper { position: relative; }
        .map-wrapper .coord-info {
            position: absolute; bottom: 10px; left: 10px; z-index: 1000;
            background: rgba(255,255,255,0.9); padding: 6px 12px;
            border-radius: 6px; font-size: 13px; font-weight: 600;
            box-shadow: 0 1px 6px rgba(0,0,0,0.2); pointer-events: none;
        }
        .admin-navbar {
            background: linear-gradient(135deg, rgba(11, 46, 27, 0.97) 0%, rgba(20, 70, 38, 0.97) 100%) !important;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.15);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
        }
        .admin-navbar .navbar-brand {
            font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
            font-weight: 800;
            font-size: 1.15rem;
            background: linear-gradient(to right, #ffffff, #a3e635);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: 0.5px;
        }
        .admin-nav-link {
            font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif !important;
            font-weight: 500 !important;
            font-size: 0.9rem !important;
            color: rgba(255, 255, 255, 0.8) !important;
            padding: 7px 14px !important;
            border-radius: 8px !important;
            transition: all 0.25s ease !important;
        }
        .admin-nav-link:hover,
        .admin-nav-link.active,
        .admin-nav-link.show {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.08) !important;
        }
        .admin-navbar .dropdown-menu {
            background: rgba(11, 46, 27, 0.98);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            border-radius: 12px;
            padding: 8px;
            margin-top: 10px;
        }
        .admin-navbar .dropdown-item {
            color: rgba(255, 255, 255, 0.85);
            font-size: 0.88rem;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.2s ease;
            font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
        }
        .admin-navbar .dropdown-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
        }
        .admin-navbar .dropdown-item.text-danger:hover {
            background: rgba(220, 53, 69, 0.15) !important;
            color: #ff6b6b !important;
        }
        .admin-navbar .navbar-toggler {
            border-color: rgba(255,255,255,0.2);
        }
        .admin-navbar .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255,255,255,0.7%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark admin-navbar py-2">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('admin.places.index') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                    class="d-inline-block align-text-top me-2" style="color:#a3e635;" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M8 .5a.5.5 0 0 1 .5.5v.688l3.81-1.23a.5.5 0 0 1-.613.355l3 11.5a.5.5 0 0 1-.355.613l-4.5 1.17a.5.5 0 0 1-.583-.305l-1.5-3.77-3.81 1.23a.5.5 0 0 1-.613-.355l-3-11.5a.5.5 0 0 1 .355-.613L7.5 1.188V1a.5.5 0 0 1 .5-.5z"/>
                </svg>
                <span>{{ __('admin.brand') }}</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdmin">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarAdmin">
                <ul class="navbar-nav ms-auto gap-1">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle admin-nav-link" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-database me-1"></i> {{ __('admin.manage') }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('admin.places.index') }}"><i class="fa-solid fa-map-pin me-2 text-success"></i>{{ __('admin.places') }}</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.events.index') }}"><i class="fa-solid fa-calendar me-2 text-danger"></i>{{ __('admin.events') }}</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.articles.index') }}"><i class="fa-solid fa-newspaper me-2 text-info"></i>{{ __('admin.articles') }}</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.itinerary-packages.index') }}"><i class="fa-solid fa-route me-2 text-warning"></i>{{ __('admin.itinerary') }}</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.categories.index') }}"><i class="fa-solid fa-tags me-2 text-warning"></i>{{ __('admin.categories') }}</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.faqs.index') }}"><i class="fa-solid fa-question-circle me-2 text-muted"></i>{{ __('admin.faqs') }}</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.inquiries.index') }}"><i class="fa-regular fa-envelope me-2 text-primary"></i>{{ __('admin.inquiries') }} <span class="badge bg-warning text-dark ms-1">{{ App\Models\Inquiry::unread()->count() }}</span></a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link admin-nav-link" href="{{ route('admin.places.create') }}"><i class="fa-solid fa-plus me-1"></i>{{ __('admin.add_new') }}</a></li>
                    <li class="nav-item"><a class="nav-link admin-nav-link" href="{{ route('map') }}"><i class="fa-solid fa-map me-1"></i>{{ __('admin.map') }}</a></li>
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle admin-nav-link" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fa-regular fa-user me-1"></i>{{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <form method="POST" action="{{ route('admin.logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @stack('scripts')
</body>
</html>
