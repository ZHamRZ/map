@extends('layouts.admin')

@section('title', 'Edit Data Tempat')

@section('content')
<h4 class="mb-4">Edit Titik / Potensi Desa</h4>

<form action="{{ route('admin.places.update', $place) }}" method="POST" enctype="multipart/form-data" id="placeForm">
    @csrf @method('PUT')

    <input type="hidden" name="cover_image_id" id="coverImageId" value="">
    <input type="hidden" name="cover_index" id="coverIndex" value="0">

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

        {{-- Galeri --}}
        <div class="col-12">
            <label class="form-label">Galeri Foto</label>

            {{-- Existing images --}}
            @if ($place->images->count() > 0)
                <div class="d-flex flex-wrap gap-2 mb-3" id="existingGallery">
                    @foreach ($place->images as $img)
                        <div class="gallery-item existing-item {{ $img->image_path === $place->image_path ? 'is-cover' : '' }}"
                             data-id="{{ $img->id }}"
                             data-path="{{ $img->image_path }}"
                             style="position:relative;width:110px;height:82px;border-radius:8px;overflow:hidden;border:2px solid transparent;cursor:pointer;transition:all 0.2s;">
                            <img src="{{ $img->image_url }}" alt=""
                                 style="width:100%;height:100%;object-fit:cover;">
                            @if ($img->image_path === $place->image_path)
                                <div class="cover-badge">Cover</div>
                            @endif
                            <button type="button" class="del-img-btn"
                                    title="Hapus gambar"
                                    style="position:absolute;top:4px;right:4px;width:22px;height:22px;border-radius:50%;border:none;background:rgba(0,0,0,0.55);color:#fff;font-size:12px;display:flex;align-items:center;justify-content:center;cursor:pointer;opacity:0;transition:opacity 0.2s;">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted mb-3" id="noImagesText">Belum ada foto galeri.</p>
            @endif

            {{-- Upload area --}}
            <div id="galleryUploadArea">
                <div id="galleryDropzone" style="border:2px dashed #cbd5e1;border-radius:12px;padding:28px 20px;text-align:center;cursor:pointer;transition:all 0.2s;background:#f8fafc;">
                    <i class="fa-regular fa-images" style="font-size:1.8rem;color:#94a3b8;display:block;margin-bottom:6px;"></i>
                    <span style="color:#64748b;font-size:0.85rem;font-weight:500;">Klik atau seret foto ke sini</span>
                    <br><span style="color:#94a3b8;font-size:0.75rem;">JPEG, PNG, WebP • Maks 20MB</span>
                </div>
                <input type="file" name="images[]" id="galleryInput" accept="image/*" multiple style="display:none;">
                <div id="galleryProcessing" style="display:none;text-align:center;padding:12px;color:#718096;font-size:0.85rem;">
                    <i class="fa-solid fa-spinner fa-spin me-2"></i> Memproses gambar...
                </div>
                <div id="galleryGrid" class="d-flex flex-wrap gap-2 mt-2"></div>
            </div>

            @error('images.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.min.css">
<style>
    .map-wrapper #map-admin { height: 360px !important; border-radius: 10px; border: 1.5px solid #e2e8f0; z-index: 1; }
    .coord-info { margin-top: 8px; padding: 8px 14px; background: #f7fafc; border-radius: 8px; font-size: 0.85rem; font-weight: 600; color: #4a5568; }
    .map-mode-divider { width: 1px; height: 24px; background: #e2e8f0; align-self: center; }
    .map-mode-pills { display: inline-flex; gap: 2px; background: #f1f5f9; border-radius: 8px; padding: 2px; align-self: center; }
    .map-mode-pills .map-mode-pill { border: none; background: transparent; padding: 5px 12px; font-size: 0.78rem; font-weight: 600; color: #64748b; border-radius: 6px; cursor: pointer; display: flex; align-items: center; gap: 4px; transition: all 0.2s ease; }
    .map-mode-pills .map-mode-pill i { font-size: 0.75rem; }
    .map-mode-pills .map-mode-pill.active { background: #fff; color: #2e7d32; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
    .map-mode-pills .map-mode-pill:hover:not(.active) { color: #334155; }

    .gallery-item { position:relative; width:110px; height:82px; border-radius:8px; overflow:hidden; border:2px solid transparent; cursor:pointer; transition:all 0.2s; flex-shrink:0; }
    .gallery-item img, .gallery-item video { width:100%; height:100%; object-fit:cover; }
    .gallery-item.is-cover { border-color: #2e7d32; box-shadow: 0 0 0 3px rgba(46,125,50,0.15); }
    .gallery-item .cover-badge { position:absolute; bottom:4px; left:4px; background:#2e7d32; color:#fff; font-size:10px; font-weight:700; padding:1px 7px; border-radius:4px; line-height:1.5; }
    .gallery-item:hover { border-color: #2e7d32; }
    .gallery-item:hover .del-img-btn { opacity:1 !important; }

    #galleryDropzone:hover { border-color: #2e7d32; background: #f0fdf4; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/heic2any@0.0.4/dist/heic2any.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ─── Existing gallery: delete & cover ──────────
        var coverImageIdInput = document.getElementById('coverImageId');
        var coverIndexInput = document.getElementById('coverIndex');
        var existingGallery = document.getElementById('existingGallery');

        if (existingGallery) {
            existingGallery.querySelectorAll('.existing-item').forEach(function (el) {
                // Click to set as cover
                el.addEventListener('click', function (e) {
                    if (e.target.closest('.del-img-btn')) return;
                    var imgId = this.getAttribute('data-id');
                    coverImageIdInput.value = imgId;
                    existingGallery.querySelectorAll('.existing-item').forEach(function (item) {
                        item.classList.remove('is-cover');
                        var badge = item.querySelector('.cover-badge');
                        if (badge) badge.remove();
                    });
                    this.classList.add('is-cover');
                    var badge = document.createElement('div');
                    badge.className = 'cover-badge';
                    badge.textContent = 'Cover';
                    this.appendChild(badge);
                });

                // Delete (batch — mark for deletion)
                var delBtn = el.querySelector('.del-img-btn');
                if (delBtn) {
                    delBtn.addEventListener('click', function (e) {
                        e.stopPropagation();
                        if (!confirm('Hapus gambar ini?')) return;
                        var imgId = el.getAttribute('data-id');
                        // Add hidden input for batch deletion
                        var hiddenDel = document.createElement('input');
                        hiddenDel.type = 'hidden';
                        hiddenDel.name = 'delete_images[]';
                        hiddenDel.value = imgId;
                        document.getElementById('placeForm').appendChild(hiddenDel);
                        // Animate removal
                        el.style.transition = 'all 0.3s';
                        el.style.opacity = '0';
                        el.style.transform = 'scale(0.85)';
                        setTimeout(function () {
                            el.remove();
                            if (!existingGallery.querySelector('.existing-item')) {
                                existingGallery.remove();
                                var noText = document.getElementById('noImagesText');
                                if (noText) noText.style.display = 'block';
                            }
                        }, 300);
                    });
                }
            });
        }

        // ─── New gallery upload (same as create) ──────
        var galleryInput = document.getElementById('galleryInput');
        var grid = document.getElementById('galleryGrid');
        var dropzone = document.getElementById('galleryDropzone');
        var processing = document.getElementById('galleryProcessing');

        dropzone.addEventListener('click', function () { galleryInput.click(); });

        galleryInput.addEventListener('change', function () {
            grid.innerHTML = '';
            window._galleryFiles = [];
            var files = this.files;
            if (!files || files.length === 0) return;

            processing.style.display = 'block';
            var pending = files.length;

            function checkDone() {
                pending--;
                if (pending > 0) return;
                processing.style.display = 'none';
            }

            for (var i = 0; i < files.length; i++) {
                (function (file, idx) {
                    var isVideo = file.type ? file.type.match('video.*') : false;
                    var isHeic = file.name.match(/\.(heic|heif)$/i);
                    var item = document.createElement('div');
                    item.className = 'gallery-item' + (idx === 0 ? ' is-cover' : '');

                    function showPreview(src) {
                        item.style.cursor = 'pointer';
                        item.title = 'Klik untuk jadikan cover';
                        item.addEventListener('click', function () {
                            coverImageIdInput.value = '';
                            coverIndexInput.value = idx;
                            grid.querySelectorAll('.gallery-item').forEach(function (el) {
                                el.classList.remove('is-cover');
                                var b = el.querySelector('.cover-badge');
                                if (b) b.remove();
                            });
                            item.classList.add('is-cover');
                            var b = document.createElement('div');
                            b.className = 'cover-badge';
                            b.textContent = 'Cover';
                            item.appendChild(b);
                            // Unmark existing covers
                            if (existingGallery) {
                                existingGallery.querySelectorAll('.existing-item').forEach(function (el) {
                                    el.classList.remove('is-cover');
                                    var badge = el.querySelector('.cover-badge');
                                    if (badge) badge.remove();
                                });
                            }
                        });

                        if (isVideo) {
                            var vid = document.createElement('video');
                            vid.src = src;
                            vid.muted = true;
                            vid.loop = true;
                            vid.style.cssText = 'width:100%;height:100%;object-fit:cover;';
                            vid.addEventListener('mouseenter', function () { this.play(); });
                            vid.addEventListener('mouseleave', function () { this.pause(); });
                            item.appendChild(vid);
                            var pi = document.createElement('div');
                            pi.style.cssText = 'position:absolute;inset:0;display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.7);font-size:1.5rem;pointer-events:none;';
                            pi.innerHTML = '<i class="fa-solid fa-play"></i>';
                            item.appendChild(pi);
                        } else {
                            var img = document.createElement('img');
                            img.src = src;
                            img.alt = file.name;
                            img.onerror = function () {
                                this.style.display = 'none';
                                var icon = document.createElement('div');
                                icon.style.cssText = 'display:flex;align-items:center;justify-content:center;height:100%;color:#a0aec0;font-size:2rem;';
                                icon.innerHTML = '<i class="fa-regular fa-file-image"></i>';
                                item.insertBefore(icon, this);
                            };
                            item.appendChild(img);
                        }
                        if (idx === 0 && !document.querySelector('#existingGallery .is-cover') && !coverImageIdInput.value && coverIndexInput.value === '0') {
                            var badge = document.createElement('div');
                            badge.className = 'cover-badge';
                            badge.textContent = 'Cover';
                            item.appendChild(badge);
                        }
                        grid.appendChild(item);
                        checkDone();
                    }

                    function makePreviewAndStore(convFile) {
                        var srcFile = convFile || file;
                        var r = new FileReader();
                        r.onload = function (e) {
                            showPreview(e.target.result);
                            window._galleryFiles = window._galleryFiles || [];
                            window._galleryFiles.push(srcFile);
                        };
                        r.readAsDataURL(srcFile);
                    }

                    if (isHeic && typeof heic2any !== 'undefined') {
                        heic2any({ blob: file, toType: 'image/jpeg' }).then(function (resultBlob) {
                            var newName = file.name.replace(/\.(heic|heif)$/i, '.jpg');
                            var jpegFile = new File([resultBlob], newName, { type: 'image/jpeg' });
                            makePreviewAndStore(jpegFile);
                        }).catch(function () {
                            makePreviewAndStore(file);
                        });
                    } else {
                        makePreviewAndStore();
                    }
                })(files[i], i);
            }
        });

        // ─── AJAX form submit when new files present ──
        document.getElementById('placeForm').addEventListener('submit', function (e) {
            var gf = window._galleryFiles;
            if (!gf || gf.length === 0) return;

            e.preventDefault();
            var form = this;
            document.getElementById('galleryInput').value = '';
            var fd = new FormData(form);
            gf.forEach(function (f) { fd.append('images[]', f, f.name); });
            var btn = form.querySelector('[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Menyimpan...';

            fetch(form.action, {
                method: 'POST',
                body: fd,
                headers: { 'Accept': 'application/json' },
            }).then(function (r) {
                if (r.ok || r.redirected) { window.location.href = '{{ route("admin.places.index") }}'; return; }
                return r.json().then(function (data) {
                    var msg = data.message || 'Gagal menyimpan.';
                    if (data.errors) msg += '\n\n' + Object.values(data.errors).flat().join('\n');
                    alert(msg);
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa-solid fa-floppy-disk me-1"></i> Update';
                });
            }).catch(function () {
                alert('Terjadi kesalahan jaringan.');
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-floppy-disk me-1"></i> Update';
            });
        });

        // ─── Map ───────────────────────────────────────
        var existingLat = {{ old('latitude', $place->latitude) }};
        var existingLng = {{ old('longitude', $place->longitude) }};

        var map = L.map('map-admin', {
            center: [existingLat, existingLng],
            zoom: 15,
            zoomControl: true,
        });

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

        var latInput = document.getElementById('input-lat');
        var lngInput = document.getElementById('input-lng');
        var coordInfo = document.getElementById('coord-info');

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
                    } else { alert('Lokasi tidak ditemukan.'); }
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

        var marker = L.marker([existingLat, existingLng], { draggable: true }).addTo(map);

        marker.on('dragend', function () {
            var pos = marker.getLatLng();
            latInput.value = pos.lat.toFixed(6);
            lngInput.value = pos.lng.toFixed(6);
            coordInfo.innerHTML = '&#128204; ' + pos.lat.toFixed(6) + ', ' + pos.lng.toFixed(6);
        });

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