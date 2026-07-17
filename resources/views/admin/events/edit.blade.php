@extends('layouts.admin')

@section('title', 'Edit Acara Budaya')

@section('content')
<h4 class="mb-4">Edit Acara / Festival Budaya</h4>

<form action="{{ route('admin.events.update', $event) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Judul Acara <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title', $event->title) }}" required maxlength="255">
            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Kategori</label>
            <select name="category" class="form-select @error('category') is-invalid @enderror">
                <option value="">— Pilih Kategori —</option>
                @foreach (['Festival', 'Tradisi', 'Workshop', 'Pertunjukan', 'Kuliner'] as $cat)
                    <option value="{{ $cat }}" {{ old('category', $event->category)==$cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
            @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-3">
            <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
            <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror"
                   value="{{ old('start_date', $event->start_date->format('Y-m-d')) }}" required>
            @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-3">
            <label class="form-label">Jam Mulai</label>
            <input type="time" name="start_time" class="form-control @error('start_time') is-invalid @enderror"
                   value="{{ old('start_time', $event->start_time) }}">
            @error('start_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-3">
            <label class="form-label">Tanggal Selesai</label>
            <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror"
                   value="{{ old('end_date', $event->end_date?->format('Y-m-d')) }}">
            @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-3">
            <label class="form-label">Jam Selesai</label>
            <input type="time" name="end_time" class="form-control @error('end_time') is-invalid @enderror"
                   value="{{ old('end_time', $event->end_time) }}">
            @error('end_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-4">
            <label class="form-label">Nama Lokasi</label>
            <input type="text" name="location" class="form-control @error('location') is-invalid @enderror"
                   value="{{ old('location', $event->location) }}" maxlength="255" placeholder="Mis: Halaman Kantor Desa">
            @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-4">
            <label class="form-label">Latitude</label>
            <input type="number" step="any" name="latitude" id="input-lat"
                   class="form-control @error('latitude') is-invalid @enderror"
                   value="{{ old('latitude', $event->latitude) }}" readonly placeholder="Klik peta untuk memilih">
            @error('latitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-4">
            <label class="form-label">Longitude</label>
            <input type="number" step="any" name="longitude" id="input-lng"
                   class="form-control @error('longitude') is-invalid @enderror"
                   value="{{ old('longitude', $event->longitude) }}" readonly placeholder="Klik peta untuk memilih">
            @error('longitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-12">
            <div class="section-card" style="background:#fafafa;border:1px solid #e0e0e0;border-radius:12px;overflow:hidden;">
                <div class="card-header" style="background:#8e24aa;color:#fff;padding:12px 16px;font-weight:600;">
                    <i class="fa-solid fa-map me-2"></i> Peta Lokasi Acara
                </div>
                <div class="card-body p-3">
                    <div class="d-flex gap-2 mb-2 flex-wrap align-items-center">
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="btnSearchMap">
                            <i class="fa-solid fa-magnifying-glass me-1"></i> Cari Lokasi
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="btnGpsMap">
                            <i class="fa-solid fa-location-crosshairs me-1"></i> GPS Saya
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="btnResetMap">
                            <i class="fa-solid fa-arrows-rotate me-1"></i> Reset
                        </button>
                        <span class="map-mode-divider"></span>
                        <span class="map-mode-pills">
                            <button type="button" class="map-mode-pill" data-mode="satellite"><i class="fa-solid fa-satellite"></i> Satelit</button>
                            <button type="button" class="map-mode-pill active" data-mode="osm"><i class="fa-solid fa-road"></i> Jalan</button>
                            <button type="button" class="map-mode-pill" data-mode="light"><i class="fa-solid fa-sun"></i> Terang</button>
                        </span>
                    </div>
                    <div id="event-map" style="height:320px;border-radius:8px;border:1px solid #ddd;"></div>
                    <div class="mt-2 small text-muted">
                        <i class="fa-solid fa-location-dot me-1" style="color:#8e24aa;"></i>
                        Koordinat: <span id="coordDisplay" class="fw-semibold">Belum dipilih</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <label class="form-label">Deskripsi Acara</label>
            <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror"
                      maxlength="5000">{{ old('description', $event->description) }}</textarea>
            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">URL Video (YouTube)</label>
            <input type="url" name="video_url" class="form-control @error('video_url') is-invalid @enderror"
                   value="{{ old('video_url', $event->video_url) }}">
            @error('video_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Gambar / Poster</label>
            @if ($event->image_url)
                <div class="mb-2">
                    <img src="{{ $event->image_url }}" alt="{{ $event->title }}" width="120" style="border-radius:6px;object-fit:cover;">
                </div>
            @endif
            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                   accept="image/jpeg,image/png,image/jpg">
            <small class="text-muted">Format: jpeg, png, jpg. Maks 2MB.</small>
            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-12">
            <div class="form-check">
                <input type="checkbox" name="is_published" class="form-check-input" value="1"
                       id="published" {{ old('is_published', $event->is_published) ? 'checked' : '' }}>
                <label class="form-check-label" for="published">Publikasikan</label>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">Batal</a>
    </div>
</form>
@endsection

@push('styles')
<style>
    .form-label { font-weight: 600; font-size: 0.875rem; }
    .section-card .card-header { cursor: default !important; }
    .map-wrapper #event-map, #event-map { height: 320px; border-radius: 8px; border: 1px solid #ddd; }
    .map-mode-divider { width: 1px; height: 24px; background: #e2e8f0; align-self: center; }
    .map-mode-pills { display: inline-flex; gap: 2px; background: #f1f5f9; border-radius: 8px; padding: 2px; align-self: center; }
    .map-mode-pills .map-mode-pill { border: none; background: transparent; padding: 5px 12px; font-size: 0.78rem; font-weight: 600; color: #64748b; border-radius: 6px; cursor: pointer; display: flex; align-items: center; gap: 4px; transition: all 0.2s ease; }
    .map-mode-pills .map-mode-pill i { font-size: 0.75rem; }
    .map-mode-pills .map-mode-pill.active { background: #fff; color: #2e7d32; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
    .map-mode-pills .map-mode-pill:hover:not(.active) { color: #334155; }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var map = L.map('event-map', {
            center: [-8.6248, 116.1882],
            zoom: 14,
            zoomControl: true,
        });

        // Mode switcher
        var osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap', maxZoom: 19,
        }).addTo(map);
        var satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: '&copy; <a href="https://www.esri.com/">Esri</a>', maxZoom: 19,
        });
        var labelsLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
            attribution: '&copy; Esri', maxZoom: 19,
        });
        var lightLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://carto.com/">CARTO</a>', maxZoom: 19,
        });
        var currentBase = osmLayer;
        var currentLabel = null;

        var modePills = document.querySelectorAll('.map-mode-pill');
        modePills.forEach(function (btn) {
            btn.addEventListener('click', function () {
                var mode = this.getAttribute('data-mode');
                modePills.forEach(function (p) { p.classList.remove('active'); });
                this.classList.add('active');
                map.removeLayer(currentBase);
                if (currentLabel) map.removeLayer(currentLabel);
                if (mode === 'satellite') { currentBase = satelliteLayer; currentLabel = labelsLayer; }
                else if (mode === 'osm') { currentBase = osmLayer; currentLabel = null; }
                else if (mode === 'light') { currentBase = lightLayer; currentLabel = null; }
                currentBase.addTo(map);
                if (currentLabel) currentLabel.addTo(map);
            });
        });

        fetch('/api/boundary', {
            headers: { 'Accept': 'application/json', 'X-Tunnel-Skip-AntiPhishing-Page': 'true' }
        })
            .then(function (r) { return r.json(); })
            .then(function (geo) {
                L.geoJSON(geo, {
                    style: { color: '#4caf50', weight: 2, opacity: 0.7, fillColor: '#4caf50', fillOpacity: 0.06 },
                }).addTo(map);
            })
            .catch(function () {});

        var marker = null;
        var latInput = document.getElementById('input-lat');
        var lngInput = document.getElementById('input-lng');
        var coordDisplay = document.getElementById('coordDisplay');

        function updateCoords(lat, lng) {
            var latStr = lat.toFixed(6);
            var lngStr = lng.toFixed(6);
            latInput.value = latStr;
            lngInput.value = lngStr;
            coordDisplay.textContent = latStr + ', ' + lngStr;
        }

        function setMarker(latlng) {
            if (marker) {
                marker.setLatLng(latlng);
            } else {
                marker = L.marker(latlng, { draggable: true }).addTo(map);
                marker.on('dragend', function () {
                    var pos = marker.getLatLng();
                    updateCoords(pos.lat, pos.lng);
                });
            }
            updateCoords(latlng.lat, latlng.lng);
        }

        @if ($event->latitude && $event->longitude)
            var latlng = L.latLng({{ $event->latitude }}, {{ $event->longitude }});
            setMarker(latlng);
            map.setView(latlng, 15);
        @endif

        map.on('click', function (e) { setMarker(e.latlng); });

        document.getElementById('btnSearchMap').addEventListener('click', function () {
            var query = prompt('Masukkan nama lokasi:');
            if (!query) return;
            fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(query))
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (data.length > 0) {
                        var latlng = L.latLng(parseFloat(data[0].lat), parseFloat(data[0].lon));
                        map.setView(latlng, 16);
                        setMarker(latlng);
                    } else {
                        alert('Lokasi tidak ditemukan.');
                    }
                })
                .catch(function () { alert('Gagal mencari lokasi.'); });
        });

        document.getElementById('btnGpsMap').addEventListener('click', function () {
            if (!navigator.geolocation) { alert('GPS tidak didukung browser ini.'); return; }
            navigator.geolocation.getCurrentPosition(function (pos) {
                var latlng = L.latLng(pos.coords.latitude, pos.coords.longitude);
                map.setView(latlng, 16);
                setMarker(latlng);
            }, function () { alert('Gagal mendapatkan lokasi GPS.'); });
        });

        document.getElementById('btnResetMap').addEventListener('click', function () {
            map.setView([-8.6248, 116.1882], 14);
            if (marker) { map.removeLayer(marker); marker = null; }
            latInput.value = '';
            lngInput.value = '';
            coordDisplay.textContent = 'Belum dipilih';
        });

        setTimeout(function () { map.invalidateSize(); }, 300);
    });
</script>
@endpush
