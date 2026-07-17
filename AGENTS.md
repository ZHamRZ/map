# SIG Potensi & Pariwisata Desa Bilebante

GIS web app: Laravel 12 + SQLite + Leaflet.js (CDN) + Esri satellite tiles + OSRM routing.

## Stack specifics

- **DB/Queue/Session/Cache**: all SQLite via `database` driver (`.env`: `DB_CONNECTION=sqlite`, `QUEUE_CONNECTION=database`, `SESSION_DRIVER=database`, `CACHE_STORE=database`). No MySQL/Redis.
- **Assets**: All loaded via CDN in Blade views — Bootstrap 5.3.3, Leaflet 1.9.4, Leaflet Routing Machine 3.2.12, Leaflet Fullscreen 1.0.2, Leaflet.markercluster 1.5.3, leaflet.heat 0.2.0, leaflet-control-geocoder 2.4.0, @turf/turf 6, Font Awesome 6.5.1. Vite + Tailwind are installed but **not used** by any app view.
- **Auth**: Custom `AuthController` (no Breeze/Jetstream). Hidden login at `/gerbang-admin`. Guest redirect in `bootstrap/app.php`. Logout via POST form.
- **Map center**: `-8.6248, 116.1882`. No max bounds — full world navigable. Default tile: Esri satellite (+ labels overlay). Optional: OSM, CartoDB light.
- **Boundary**: `public/geojson/bilebante.geojson` (large), served via `/api/boundary`.
- **Locale**: Session-based (`session('locale')`, default `id`). Switcher at `/locale/{id|en}`. Middleware `SetLocale` appended to `web` group in `bootstrap/app.php`. Lang files at `lang/{id,en}.json`.

## Setup

```bash
composer setup              # install, .env, key:generate, migrate --force, npm install, npm run build
php artisan storage:link    # required for image uploads
php artisan db:seed --class=AdminUserSeeder
composer dev                # serve + queue:listen + pail + vite concurrently
```

Default admin: `admin@bilebante.id` / `admin123`

First-time: `composer setup` creates `database/database.sqlite`, runs migrations. After that, run `storage:link` + seed separately. `migrate:fresh` drops all data.

## Key commands

```bash
php artisan serve                    # dev server at localhost:8000
php artisan migrate:fresh            # reset + re-migrate (drops all data)
php artisan db:seed --class=AdminUserSeeder
php artisan config:clear             # needed before running tests
composer test                        # runs config:clear + php artisan test
```

## Models

All models except `Inquiry` use `HasTranslations` trait + `is_published` boolean + `scopePublished()`.

| Model | Table | Key fields | Relations |
|-------|-------|------------|-----------|
| `Place` | `places` | name, category, lat/lng, description, history, cultural_significance, video_url, audio_url, image_path (stored), image_url (accessor), translations (JSON) | hasMany `PlaceImage`, hasMany `Review` |
| `Event` | `events` | title, slug, description, start/end_date, location, category, image_path, video_url, translations, is_published | — |
| `Article` | `articles` | title, slug, excerpt, body, category, cover_image, author, translations, is_published | — |
| `ItineraryPackage` | `itinerary_packages` | title, slug, description, icon, image_path, duration, category_list (JSON), translations, is_published | belongsToMany `Place` (pivot `itinerary_package_place` with `order`) |
| `Inquiry` | `inquiries` | name, email, phone, message, is_read | — |
| `Faq` | `faqs` | question, answer, category, order, translations, is_published | — |

## Conventions

- **Validation**: `StorePlaceRequest` for places (images: max 2MB, 4000x4000px, jpeg/png/jpg). All other models validated inline in controllers.
- **Translations**: `translations` JSON column stores `{"en": {"field": "val"}, ...}`. `getTranslated($field)` returns English if locale=`en`, otherwise falls back to base column.
- **API format**: `map.blade.php` fetches raw GeoJSON from `/api/places` (not a resource transformer).
- **Image storage**: `storage/app/public/{places,events,articles,itinerary-packages}/` (disk `public`). `hashName()` used for filenames. Old files cleaned up on destroy.
- **Views**: `map.blade.php` and `place/detail.blade.php` are standalone HTML (no layout). All other public views (events, articles, faq, contact, transportation, library) are also standalone. Admin CRUD extends `layouts/admin.blade.php`. Public navbar lives in `partials/public-navbar.blade.php` (included via `@include`).
- **Map JS**: IIFE pattern, ES5 (`var`, `function`), Fetch API, `L.divIcon` for custom teardrop SVG markers (Google Maps style), `L.markerClusterGroup` for clustering, `L.Routing.control` with OSRM v1, `L.control.geocoder` for search.
- **Map UI controls**: Premium Google Maps / Airbnb-style — Search geocoder pill (top-left, 340px, 28px radius, glassmorphism), Route button (top-left), Merged Info Card (bottom-left: Desa Bilebante stats + Virtual Tour + count badges), Scale (bottom-left), Map mode switcher (top-right pill: Satelit/Jalan/Terang), Zoom controls (bottom-right, 56×56 circular glass, 24px blur, split layout), Tools FAB (bottom-right, expandable menu with Filter, Analysis, Layers, GPS, Compass, Home, Measure, Fullscreen, Download). All controls use glassmorphism cards (16px radius, blur, shadow). Legend rendered as floating glass card with SVG inline icons, category counts, and clickable items (visible by default, bottom-right above zoom).
- **Popup style**: Airbnb-style premium card (290px wide, 155px hero image with gradient fallback, category badge pill, star rating with review count, rounded action buttons with shadow).
- **Tooltip style**: Premium pill badge with Poppins heading, Inter body, category tag, image thumb.
- **Typography**: Poppins (headings), Inter (UI), Nunito (body).
- **Route model binding**: `{place}` auto-resolves `Place` model. Others follow Laravel conventions.

## Rate limiting (AppServiceProvider::boot)

| Limiter | Rate | Applied to |
|---------|------|-----------|
| `api` | 60/min/IP | `/api/places`, `/api/map-points` |
| `login` | 5/min/IP | POST `/gerbang-admin` |
| `review` | 5/min/IP | POST `/place/{place}/review` |
| `contact` | 3/min/IP | POST `/contact` |

## Marker categories (11)

| Kategori | Color | FA Icon |
|----------|-------|---------|
| Wisata | `#2E7D32` | `fa-mountain` |
| Budaya | `#7B1FA2` | `fa-landmark` |
| Pendidikan | `#1565C0` | `fa-graduation-cap` |
| Kesehatan | `#C62828` | `fa-heart` |
| Kuliner | `#BF360C` | `fa-utensils` |
| Infrastruktur | `#E65100` | `fa-wrench` |
| Ekonomi | `#F9A825` | `fa-store` |
| Ruang Terbuka | `#43A047` | `fa-tree` |
| Tempat Ibadah | `#00695C` | `fa-place-of-worship` |
| Penginapan | `#4E342E` | `fa-hotel` |
| Umum (default) | `#546E7A` | `fa-circle-dot` |

## Dead / orphan code

- **Breeze controllers**: `app/Http/Controllers/Auth/*.php` (9 files) — not routed (no `routes/auth.php` loaded). Keep as-is.
- **`ProfileController`**: Not routed.
- **`quickUpdate` route**: `routes/web.php:61` has `PUT /admin/places/{place}/quick-update` but `PlaceController@quickUpdate` method does not exist.
- **`/api/map-points`**: Legacy duplicate of `/api/places` (different controller, same data). Map uses `/api/places`.
- **`events.show` view**: `routes/web.php:39` routes to `EventController@show` which renders `events.show` — view `resources/views/events/show.blade.php` does not exist.
- **Blade components**: `AppLayout`, `GuestLayout` — not used.
- **Vite + Tailwind**: Installed but not used in any app view.

## Tests

PHPUnit with `:memory:` SQLite (config in `phpunit.xml`). Only `ExampleTest.php` files exist (Unit + Feature). `composer test` runs `config:clear + php artisan test`.
