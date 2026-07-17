<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    .navbar-custom {
        background: linear-gradient(135deg, rgba(11, 46, 27, 0.95) 0%, rgba(20, 70, 38, 0.95) 100%) !important;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.15);
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        transition: all 0.3s ease;
    }
    .navbar-custom .navbar-brand {
        font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
        font-weight: 800;
        font-size: 1.2rem;
        background: linear-gradient(to right, #ffffff, #a3e635);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        letter-spacing: 0.5px;
    }
    .navbar-custom .nav-link {
        font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
        font-weight: 500;
        font-size: 0.92rem;
        color: rgba(255, 255, 255, 0.8) !important;
        padding: 8px 16px !important;
        border-radius: 8px;
        transition: all 0.25s ease;
    }
    .navbar-custom .nav-link:hover,
    .navbar-custom .nav-link.active {
        color: #ffffff !important;
        background: rgba(255, 255, 255, 0.08);
        text-shadow: 0 0 8px rgba(255, 255, 255, 0.2);
    }
    .navbar-custom .nav-link.active {
        border-bottom: 2px solid #a3e635;
        border-radius: 8px 8px 0 0;
        background: rgba(255, 255, 255, 0.04);
    }
    .navbar-custom .dropdown-menu {
        background: rgba(11, 46, 27, 0.98);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        border-radius: 12px;
        padding: 8px;
        margin-top: 10px;
    }
    .navbar-custom .dropdown-item {
        color: rgba(255, 255, 255, 0.85);
        font-size: 0.88rem;
        font-weight: 500;
        padding: 8px 16px;
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    .navbar-custom .dropdown-item:hover {
        background: rgba(255, 255, 255, 0.1);
        color: #ffffff;
    }
    .navbar-custom .dropdown-item.text-danger:hover {
        background: rgba(220, 53, 69, 0.15) !important;
        color: #ff6b6b !important;
    }
    .locale-btn {
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.15);
        color: white;
        border-radius: 8px;
        padding: 4px 12px;
        font-size: 0.8rem;
        font-weight: 600;
        transition: all 0.2s;
        cursor: pointer;
    }
    .locale-btn:hover {
        background: rgba(255,255,255,0.2);
    }
</style>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom py-2">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('map') }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor"
                class="d-inline-block align-text-top me-2 text-lime" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                    d="M8 .5a.5.5 0 0 1 .5.5v.688l3.81-1.23a.5.5 0 0 1-.613.355l3 11.5a.5.5 0 0 1-.355.613l-4.5 1.17a.5.5 0 0 1-.583-.305l-1.5-3.77-3.81 1.23a.5.5 0 0 1-.613-.355l-3-11.5a.5.5 0 0 1 .355-.613L7.5 1.188V1a.5.5 0 0 1 .5-.5zM5.5 2.888l-2.846.92 2.44 9.35 2.845-.92L5.5 2.888zm3.5 1.352l-2 5 2 5 2-5-2-5z"/>
            </svg>
            <span>Desa Bilebante</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarPublic"
            aria-controls="navbarPublic" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarPublic">
            <ul class="navbar-nav ms-auto gap-1">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('map') || request()->is('/') ? 'active' : '' }}" href="{{ route('map') }}">
                        <i class="fa-solid fa-map-location-dot me-1"></i>{{ __('nav.map') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('library') ? 'active' : '' }}" href="{{ route('library') }}">
                        <i class="fa-solid fa-compass me-1"></i>{{ __('nav.library') }}
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('events.*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fa-solid fa-calendar-days me-1"></i>{{ __('nav.events') }}
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('events.index', ['category' => 'desa']) }}"><i class="fa-solid fa-house-chimney me-2 text-success"></i>{{ __('nav.events_village') }}</a></li>
                        <li><a class="dropdown-item" href="{{ route('events.index', ['category' => 'budaya']) }}"><i class="fa-solid fa-hands-holding-circle me-2 text-purple"></i>{{ __('nav.events_culture') }}</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('articles.*') ? 'active' : '' }}" href="{{ route('articles.index') }}">
                        <i class="fa-solid fa-book-open me-1"></i>{{ __('nav.articles') }}
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fa-solid fa-ellipsis-h me-1"></i>{{ __('common.read_more') }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('faq.index') }}"><i class="fa-solid fa-circle-question me-2 text-success"></i>{{ __('nav.faq') }}</a></li>
                        <li><a class="dropdown-item" href="{{ route('contact.create') }}"><i class="fa-regular fa-paper-plane me-2 text-primary"></i>{{ __('nav.contact') }}</a></li>
                        <li><a class="dropdown-item" href="{{ route('transportation.index') }}"><i class="fa-solid fa-road me-2 text-warning"></i>{{ __('nav.transportation') }}</a></li>
                    </ul>
                </li>
                <li class="nav-item d-flex align-items-center ms-2">
                    @php $currentLocale = app()->getLocale(); @endphp
                    @if ($currentLocale === 'id')
                        <a href="{{ route('locale.switch', 'en') }}" class="locale-btn text-decoration-none">
                            <i class="fa-solid fa-language me-1"></i>EN
                        </a>
                    @else
                        <a href="{{ route('locale.switch', 'id') }}" class="locale-btn text-decoration-none">
                            <i class="fa-solid fa-language me-1"></i>ID
                        </a>
                    @endif
                </li>
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-1" href="#" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-circle-user"></i>
                            <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('admin.places.index') }}">
                                <i class="fa-solid fa-gauge-high me-2 text-lime"></i>{{ __('nav.admin_dashboard') }}
                            </a></li>
                            <li><hr class="dropdown-divider border-secondary"></li>
                            <li>
                                <form method="POST" action="{{ route('admin.logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger d-flex align-items-center">
                                        <i class="fa-solid fa-right-from-bracket me-2"></i>{{ __('nav.logout') }}
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth

            </ul>
        </div>
    </div>
</nav>
