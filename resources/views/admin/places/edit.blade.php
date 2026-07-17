@extends('layouts.admin')

@section('title', 'Edit Data Tempat')

@section('content')
<h4 class="mb-4">Edit Titik / Potensi Desa</h4>

<form action="{{ route('admin.places.update', $place) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')

    <div class="row g-3">
        {{-- Nama --}}
        <div class="col-md-6">
            <label class="form-label">Nama Tempat <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name', $place->name) }}" required maxlength="255">
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Kategori --}}
        <div class="col-md-6">
            <label class="form-label">Kategori <span class="text-danger">*</span></label>
            <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                <option value="">— Pilih Kategori —</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->key }}" {{ (old('category', $place->category)==$cat->key) ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
            @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Koordinat (hidden—diisi otomatis lewat peta) --}}
        <div class="col-md-6">
            <label class="form-label">Latitude <span class="text-danger">*</span></label>
            <input type="number" step="any" name="latitude" id="input-lat"
                   class="form-control @error('latitude') is-invalid @enderror"
                   value="{{ old('latitude', $place->latitude) }}" required readonly>
            @error('latitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Longitude <span class="text-danger">*</span></label>
            <input type="number" step="any" name="longitude" id="input-lng"
                   class="form-control @error('longitude') is-invalid @enderror"
                   value="{{ old('longitude', $place->longitude) }}" required readonly>
            @error('longitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Deskripsi --}}
        <div class="col-12">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror"
                      maxlength="2000">{{ old('description', $place->description) }}</textarea>
            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Galeri saat ini --}}
        <div class="col-12">
            <label class="form-label">Galeri Foto Saat Ini</label>
            <div class="d-flex flex-wrap gap-2">
                @forelse ($place->images as $img)
                    <div style="position:relative;width:100px;height:75px;">
                        <img src="{{ $img->image_url }}" alt=""
                             style="width:100%;height:100%;object-fit:cover;border-radius:6px;border:1px solid #dee2e6;">
                    </div>
                @empty
                    <span class="text-muted">Belum ada foto galeri.</span>
                @endforelse
            </div>
        </div>

        {{-- Tambah foto galeri --}}
        <div class="col-12">
            <label class="form-label">Tambah Foto Baru</label>
            <input type="file" name="images[]" class="form-control @error('images.*') is-invalid @enderror"
                   accept="image/jpeg,image/png,image/jpg" multiple>
            <small class="text-muted">Format: jpeg, png, jpg. Maksimal 2MB per file. Kosongkan jika tidak ingin menambah foto.</small>
            @error('images.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <div id="preview-container" class="d-flex flex-wrap gap-2 mt-2"></div>
        </div>

        {{-- Peta interaktif --}}
        <div class="col-12 map-wrapper">
            <label class="form-label">Geser marker atau klik peta untuk mengubah lokasi</label>
            <div class="map-toolbar mb-2 d-flex gap-2 flex-wrap align-items-center">
                <button type="button" class="btn btn-sm btn-outline-secondary" id="btnSearchMap"><i class="fa-solid fa-magnifying-glass"></i> Cari Lokasi</button>
                <button type="button" class="btn btn-sm btn-outline-secondary" id="btnGpsMap"><i class="fa-solid fa-location-crosshairs"></i> GPS Saya</button>
                <button type="button" class="btn btn-sm btn-outline-secondary" id="btnResetMap"><i class="fa-solid fa-arrows-rotate"></i> Reset</button>
                <span class="map-mode-divider"></span>
                    <span class="map-mode-pills">
                        <button type="button" class="map-mode-pill" data-mode="satellite"><i class="fa-solid fa-satellite"></i> Satelit</button>
                        <button type="button" class="map-mode-pill active" data-mode="osm"><i class="fa-solid fa-road"></i> Jalan</button>
                        <button type="button" class="map-mode-pill" data-mode="light"><i class="fa-solid fa-sun"></i> Terang</button>
                    </span>
            </div>
            <div id="map-admin"></div>
            <div class="coord-info" id="coord-info">
                &#128204; {{ old('latitude', $place->latitude) }}, {{ old('longitude', $place->longitude) }}
            </div>
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin.places.index') }}" class="btn btn-secondary">Batal</a>
    </div>
</form>
@endsection

@push('styles')
<style>
    .map-wrapper #map-admin { height: 360px !important; border-radius: 10px; border: 1.5px solid #e2e8f0; z-index: 1; }
    .coord-info { margin-top: 8px; padding: 8px 14px; background: #f7fafc; border-radius: 8px; font-size: 0.85rem; font-weight: 600; color: #4a5568; }
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
        // ─── Preview galeri ───────────────────────────────
        var fileInput = document.querySelector('input[name="images[]"]');
        var previewContainer = document.getElementById('preview-container');

        fileInput.addEventListener('change', function () {
            previewContainer.innerHTML = '';
            for (var i = 0; i < this.files.length; i++) {
                (function (file) {
                    if (!file.type.match('image.*')) return;
                    var reader = new FileReader();
                    reader.addEventListener('load', function (e) {
                        var wrapper = document.createElement('div');
                        wrapper.style.cssText = 'position:relative;width:100px;height:75px;';
                        var img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.cssText = 'width:100%;height:100%;object-fit:cover;border-radius:6px;border:1px solid #dee2e6;';
                        var name = document.createElement('div');
                        name.textContent = file.name;
                        name.style.cssText = 'font-size:10px;color:#666;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:100px;';
                        wrapper.appendChild(img);
                        wrapper.appendChild(name);
                        previewContainer.appendChild(wrapper);
                    });
                    reader.readAsDataURL(file);
                })(this.files[i]);
            }
        });

        var existingLat = {{ old('latitude', $place->latitude) }};
        var existingLng = {{ old('longitude', $place->longitude) }};

        // Inisialisasi peta
        var map = L.map('map-admin', {
            center: [existingLat, existingLng],
            zoom: 15,
            zoomControl: true,
        });

        // Tile layers for mode switching
        // Default: OSM jalan
        var osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap',
            maxZoom: 19,
        }).addTo(map);

        var satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: '&copy; <a href="https://www.esri.com/">Esri</a>',
            maxZoom: 19,
        });

        var labelsLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
            attribution: '&copy; Esri',
            maxZoom: 19,
        });

        var lightLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://carto.com/">CARTO</a>',
            maxZoom: 19,
        });

        var currentBase = osmLayer;
        var currentLabel = null;

        // Mode switcher
        var modePills = document.querySelectorAll('.map-mode-pill');
        modePills.forEach(function (btn) {
            btn.addEventListener('click', function () {
                var mode = this.getAttribute('data-mode');
                modePills.forEach(function (p) { p.classList.remove('active'); });
                this.classList.add('active');
                map.removeLayer(currentBase);
                if (currentLabel) map.removeLayer(currentLabel);
                if (mode === 'satellite') {
                    currentBase = satelliteLayer;
                    currentLabel = labelsLayer;
                } else if (mode === 'osm') {
                    currentBase = osmLayer;
                    currentLabel = null;
                } else if (mode === 'light') {
                    currentBase = lightLayer;
                    currentLabel = null;
                }
                currentBase.addTo(map);
                if (currentLabel) currentLabel.addTo(map);
            });
        });

        // Boundary Bilebante (referensi visual)
        fetch('/api/boundary', {
            headers: {
                'Accept': 'application/json',
                'X-Tunnel-Skip-AntiPhishing-Page': 'true'
            }
        })
            .then(function (r) { return r.json(); })
            .then(function (geo) {
                L.geoJSON(geo, {
                    style: {
                        color: '#4caf50', weight: 2, opacity: 0.7,
                        fillColor: '#4caf50', fillOpacity: 0.06,
                    },
                }).addTo(map);
            })
            .catch(function () {});

        var latInput = document.getElementById('input-lat');
        var lngInput = document.getElementById('input-lng');
        var coordInfo = document.getElementById('coord-info');

        // Map tools
        document.getElementById('btnSearchMap').addEventListener('click', function () {
            var query = prompt('Masukkan nama lokasi:');
            if (!query) return;
            fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(query))
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (data.length > 0) {
                        var ll = L.latLng(parseFloat(data[0].lat), parseFloat(data[0].lon));
                        map.setView(ll, 16);
                        marker.setLatLng(ll);
                        latInput.value = ll.lat.toFixed(6);
                        lngInput.value = ll.lng.toFixed(6);
                        coordInfo.innerHTML = '&#128204; ' + ll.lat.toFixed(6) + ', ' + ll.lng.toFixed(6);
                    } else {
                        alert('Lokasi tidak ditemukan.');
                    }
                })
                .catch(function () { alert('Gagal mencari lokasi.'); });
        });

        document.getElementById('btnGpsMap').addEventListener('click', function () {
            if (!navigator.geolocation) { alert('GPS tidak didukung browser ini.'); return; }
            navigator.geolocation.getCurrentPosition(function (pos) {
                var ll = L.latLng(pos.coords.latitude, pos.coords.longitude);
                map.setView(ll, 16);
                marker.setLatLng(ll);
                latInput.value = ll.lat.toFixed(6);
                lngInput.value = ll.lng.toFixed(6);
                coordInfo.innerHTML = '&#128204; ' + ll.lat.toFixed(6) + ', ' + ll.lng.toFixed(6);
            }, function () { alert('Gagal mendapatkan lokasi GPS.'); });
        });

        document.getElementById('btnResetMap').addEventListener('click', function () {
            map.setView([existingLat, existingLng], 15);
            marker.setLatLng([existingLat, existingLng]);
            latInput.value = existingLat.toFixed(6);
            lngInput.value = existingLng.toFixed(6);
            coordInfo.innerHTML = '&#128204; ' + existingLat.toFixed(6) + ', ' + existingLng.toFixed(6);
        });

        // Marker existing — bisa digeser
        var marker = L.marker([existingLat, existingLng], {
            draggable: true,
        }).addTo(map);

        marker.on('dragend', function () {
            var pos = marker.getLatLng();
            latInput.value = pos.lat.toFixed(6);
            lngInput.value = pos.lng.toFixed(6);
            coordInfo.innerHTML = '&#128204; ' + pos.lat.toFixed(6) + ', ' + pos.lng.toFixed(6);
        });

        // Klik peta → pindahkan marker
        map.on('click', function (e) {
            marker.setLatLng(e.latlng);
            latInput.value = e.latlng.lat.toFixed(6);
            lngInput.value = e.latlng.lng.toFixed(6);
            coordInfo.innerHTML = '&#128204; ' + e.latlng.lat.toFixed(6) + ', ' + e.latlng.lng.toFixed(6);
        });

        setTimeout(function () { map.invalidateSize(); }, 300);
    });
</script>
@endpush
