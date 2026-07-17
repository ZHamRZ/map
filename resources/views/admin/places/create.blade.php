@extends('layouts.admin')

@section('title', 'Tambah Data Tempat')

@push('styles')
<style>
    :root { --admin-green: #2E7D32; --admin-green-light: #e8f5e9; --admin-green-dark: #1b5e20; }
    body { background: #f0f4f8 !important; }
    .section-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
        border: 1px solid #eef2f6;
        margin-bottom: 20px;
        overflow: hidden;
        transition: box-shadow 0.2s ease;
    }
    .section-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
    .section-card .card-header {
        background: #fff;
        border-bottom: 1px solid #f0f4f8;
        padding: 16px 24px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 700;
        font-size: 0.95rem;
        color: #1a202c;
        cursor: pointer;
        user-select: none;
    }
    .section-card .card-header .icon-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        flex-shrink: 0;
    }
    .section-card .card-header .toggle-icon {
        margin-left: auto;
        transition: transform 0.3s ease;
        color: #a0aec0;
    }
    .section-card .card-header.collapsed .toggle-icon { transform: rotate(-90deg); }
    .section-card .card-body { padding: 24px; }
    .form-label { font-weight: 600; font-size: 0.85rem; color: #2d3748; margin-bottom: 6px; }
    .form-control, .form-select {
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        padding: 10px 14px;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--admin-green);
        box-shadow: 0 0 0 3px rgba(46,125,50,0.12);
    }
    .form-control[readonly] { background: #f7fafc; cursor: default; }
    .btn-pick-map {
        border-radius: 10px;
        border: 1.5px dashed var(--admin-green);
        background: var(--admin-green-light);
        color: var(--admin-green);
        font-weight: 600;
        font-size: 0.85rem;
        padding: 10px 18px;
        transition: all 0.2s ease;
        width: 100%;
    }
    .btn-pick-map:hover { background: #c8e6c9; border-color: var(--admin-green-dark); }
    .coord-inputs { display: flex; gap: 12px; }
    .coord-inputs .coord-field { flex: 1; }

    /* ── Hero Image ── */
    .drop-zone {
        border: 2px dashed #cbd5e0;
        border-radius: 16px;
        padding: 40px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.25s ease;
        background: #f7fafc;
        position: relative;
    }
    .drop-zone:hover, .drop-zone.dragover { border-color: var(--admin-green); background: var(--admin-green-light); }
    .drop-zone.has-image { padding: 0; border: 2px solid #e2e8f0; background: transparent; cursor: default; }
    .drop-zone .dz-icon { font-size: 2.5rem; color: #a0aec0; margin-bottom: 8px; }
    .drop-zone .dz-text { font-weight: 600; color: #4a5568; }
    .drop-zone .dz-hint { font-size: 0.8rem; color: #a0aec0; margin-top: 4px; }
    .hero-preview-wrapper {
        position: relative;
        border-radius: 14px;
        overflow: hidden;
        aspect-ratio: 9 / 4;
        background: #edf2f7;
    }
    .hero-preview-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .hero-preview-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.5) 0%, transparent 50%);
        display: flex;
        align-items: flex-end;
        padding: 20px;
        pointer-events: none;
    }
    .hero-preview-overlay .badge {
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(4px);
        border-radius: 20px;
        padding: 4px 14px;
        font-size: 0.7rem;
        color: #fff;
    }
    .hero-preview-actions {
        position: absolute;
        top: 12px;
        right: 12px;
        display: flex;
        gap: 8px;
        z-index: 2;
        opacity: 0;
        transition: opacity 0.2s ease;
    }
    .hero-preview-wrapper:hover .hero-preview-actions { opacity: 1; }
    .hero-preview-actions .btn-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .hero-preview-actions .btn-icon.btn-replace { background: rgba(255,255,255,0.9); color: #2d3748; }
    .hero-preview-actions .btn-icon.btn-remove { background: rgba(239,68,68,0.9); color: #fff; }
    .hero-preview-actions .btn-icon:hover { transform: scale(1.1); }
    .hero-size-hint {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.78rem;
        color: #718096;
        margin-top: 10px;
        padding: 8px 14px;
        background: #f7fafc;
        border-radius: 8px;
    }
    .hero-size-hint i { color: var(--admin-green); }

    /* ── Gallery ── */
    .gallery-drop-zone {
        position: relative;
        border: 2px dashed #cbd5e0;
        border-radius: 14px;
        padding: 30px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.25s ease;
        background: #f7fafc;
        margin-bottom: 16px;
    }
    .gallery-drop-zone:hover, .gallery-drop-zone.dragover { border-color: var(--admin-green); background: var(--admin-green-light); }
    .gallery-drop-zone .dz-icon { font-size: 2rem; color: #a0aec0; margin-bottom: 6px; }
    .gallery-drop-zone .dz-text { font-weight: 600; color: #4a5568; font-size: 0.9rem; }
    .gallery-drop-zone .dz-hint { font-size: 0.78rem; color: #a0aec0; margin-top: 2px; }
    #galleryInput {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 2;
    }
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 12px;
    }
    .gallery-item {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        border: 2px solid transparent;
        transition: all 0.2s ease;
        aspect-ratio: 4/3;
        background: #edf2f7;
        cursor: grab;
    }
    .gallery-item.is-cover { border-color: var(--admin-green); box-shadow: 0 0 0 3px rgba(46,125,50,0.2); }
    .gallery-item img { width: 100%; height: 100%; object-fit: cover; }
    .gallery-item .gallery-actions {
        position: absolute;
        top: 6px;
        right: 6px;
        display: flex;
        gap: 4px;
        opacity: 0;
        transition: opacity 0.2s ease;
    }
    .gallery-item:hover .gallery-actions { opacity: 1; }
    .gallery-item .gallery-actions .btn-icon {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .gallery-item .gallery-actions .btn-remove { background: rgba(239,68,68,0.85); color: #fff; }
    .gallery-item .gallery-actions .btn-cover { background: rgba(255,255,255,0.9); color: var(--admin-green); }
    .gallery-item .gallery-actions .btn-icon:hover { transform: scale(1.15); }
    .gallery-item .cover-badge {
        position: absolute;
        bottom: 6px;
        left: 6px;
        background: var(--admin-green);
        color: #fff;
        font-size: 0.6rem;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 6px;
        display: none;
    }
    .gallery-item.is-cover .cover-badge { display: block; }
    .empty-gallery {
        text-align: center;
        padding: 20px;
        color: #a0aec0;
    }
    .progress-bar-upload {
        height: 4px;
        border-radius: 2px;
        background: #e2e8f0;
        overflow: hidden;
        margin-top: 8px;
        display: none;
    }
    .progress-bar-upload .progress-fill {
        height: 100%;
        background: var(--admin-green);
        border-radius: 2px;
        width: 0%;
        transition: width 0.3s ease;
    }
    .gallery-stats {
        font-size: 0.78rem;
        color: #718096;
        display: flex;
        gap: 16px;
        margin-top: 10px;
    }

    /* ── Rich Text Editor ── */
    .editor-toolbar {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        padding: 8px 12px;
        background: #f7fafc;
        border: 1.5px solid #e2e8f0;
        border-bottom: none;
        border-radius: 10px 10px 0 0;
    }
    .editor-toolbar .btn-edit {
        width: 34px;
        height: 34px;
        border-radius: 6px;
        border: none;
        background: transparent;
        color: #4a5568;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.15s ease;
        font-size: 0.85rem;
    }
    .editor-toolbar .btn-edit:hover { background: #e2e8f0; }
    .editor-toolbar .btn-edit.active { background: var(--admin-green-light); color: var(--admin-green); }
    .editor-toolbar .separator { width: 1px; background: #e2e8f0; margin: 4px 2px; }
    #description-editor {
        min-height: 200px;
        padding: 14px 16px;
        border: 1.5px solid #e2e8f0;
        border-radius: 0 0 10px 10px;
        font-size: 0.9rem;
        line-height: 1.7;
        outline: none;
        overflow-y: auto;
        background: #fff;
    }
    #description-editor:focus { border-color: var(--admin-green); }

    /* ── Additional Info ── */
    .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    @media (max-width: 768px) { .info-grid { grid-template-columns: 1fr; } }

    /* ── Map ── */
    #map-admin {
        height: 420px !important;
        border-radius: 14px;
        z-index: 1;
        border: 1.5px solid #e2e8f0;
    }
    .map-toolbar {
        display: flex;
        gap: 8px;
        margin-bottom: 12px;
        flex-wrap: wrap;
    }
    .map-toolbar .btn-map-tool {
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        background: #fff;
        padding: 8px 16px;
        font-size: 0.82rem;
        font-weight: 600;
        color: #4a5568;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .map-toolbar .btn-map-tool:hover { border-color: var(--admin-green); color: var(--admin-green); background: var(--admin-green-light); }
    .map-toolbar .btn-map-tool i { font-size: 0.85rem; }
    .map-mode-divider {
        width: 1px;
        height: 28px;
        background: #e2e8f0;
        align-self: center;
    }
    .map-mode-pills {
        display: inline-flex;
        gap: 2px;
        background: #f1f5f9;
        border-radius: 8px;
        padding: 2px;
        align-self: center;
    }
    .map-mode-pills .map-mode-pill {
        border: none;
        background: transparent;
        padding: 5px 12px;
        font-size: 0.78rem;
        font-weight: 600;
        color: #64748b;
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 4px;
        transition: all 0.2s ease;
    }
    .map-mode-pills .map-mode-pill i { font-size: 0.75rem; }
    .map-mode-pills .map-mode-pill.active {
        background: #fff;
        color: #2e7d32;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .map-mode-pills .map-mode-pill:hover:not(.active) { color: #334155; }
    .map-coords-display {
        display: flex;
        gap: 16px;
        margin-top: 12px;
        padding: 10px 16px;
        background: #f7fafc;
        border-radius: 10px;
        font-size: 0.82rem;
        font-weight: 600;
        color: #4a5568;
        align-items: center;
    }
    .map-coords-display .coord-value { color: var(--admin-green); }

    /* ── Sticky Bottom Bar ── */
    .bottom-bar {
        position: sticky;
        bottom: 0;
        z-index: 1050;
        background: rgba(255,255,255,0.95);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-top: 1px solid #e2e8f0;
        padding: 14px 0;
        margin-top: 30px;
        box-shadow: 0 -4px 20px rgba(0,0,0,0.06);
    }
    .bottom-bar .bar-inner {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .bottom-bar .bar-left { display: flex; gap: 8px; }
    .bottom-bar .bar-right { display: flex; gap: 8px; }
    .bottom-bar .btn {
        border-radius: 10px;
        padding: 10px 22px;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.2s ease;
    }
    .bottom-bar .btn:hover { transform: translateY(-1px); }
    .bottom-bar .btn-primary {
        background: var(--admin-green);
        border-color: var(--admin-green);
    }
    .bottom-bar .btn-primary:hover { background: var(--admin-green-dark); border-color: var(--admin-green-dark); }
    .bottom-bar .btn-outline-secondary { border-color: #e2e8f0; color: #4a5568; }
    .bottom-bar .btn-outline-secondary:hover { background: #f7fafc; border-color: #cbd5e0; }
    .bottom-bar .btn-outline-danger { border-color: #fecaca; color: #dc2626; }
    .bottom-bar .btn-outline-danger:hover { background: #fef2f2; border-color: #fca5a5; }

    /* ── Live validation styles ── */
    .form-control.is-valid, .form-select.is-valid { border-color: var(--admin-green); }
    .form-control.is-valid:focus, .form-select.is-valid:focus { box-shadow: 0 0 0 3px rgba(46,125,50,0.12); }
    .valid-feedback { display: none; font-size: 0.78rem; }
    .was-validated .form-control:valid ~ .valid-feedback { display: block; }

    /* ── Empty state ── */
    .empty-state {
        text-align: center;
        padding: 30px 20px;
        color: #a0aec0;
    }
    .empty-state .empty-icon { font-size: 2.5rem; margin-bottom: 8px; opacity: 0.5; }
    .empty-state .empty-text { font-size: 0.85rem; }

    /* ── Success/error alert animations ── */
    .alert-modern {
        border-radius: 12px;
        border: none;
        padding: 14px 18px;
        font-size: 0.88rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        animation: slideDown 0.3s ease;
    }
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ── Responsive ── */
    @media (max-width: 768px) {
        .section-card .card-header { padding: 14px 16px; font-size: 0.88rem; }
        .section-card .card-body { padding: 16px; }
        .gallery-grid { grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); }
        .coord-inputs { flex-direction: column; gap: 8px; }
        #map-admin { height: 300px !important; }
        .bottom-bar .bar-inner { justify-content: center; }
        .bottom-bar .bar-left, .bottom-bar .bar-right { width: 100%; justify-content: center; }
        .hero-preview-wrapper { aspect-ratio: 16/9; }
    }
</style>
@endpush

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1a202c;">Tambah Titik / Potensi Desa</h4>
        <p class="text-muted mb-0" style="font-size:0.85rem;">Isi data lengkap untuk menambahkan tempat atau potensi baru di Desa Bilebante</p>
    </div>
</div>

{{-- Validation errors --}}
@if ($errors->any())
    <div class="alert alert-danger alert-modern">
        <div class="d-flex align-items-center gap-2 mb-2">
            <i class="fa-solid fa-circle-exclamation"></i>
            <strong>Terdapat {{ $errors->count() }} kesalahan pada form</strong>
        </div>
        <ul class="mb-0" style="font-size:0.85rem;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.places.store') }}" method="POST" enctype="multipart/form-data" id="placeForm">
    @csrf

    {{-- ════════════════════════════════════════════ --}}
    {{-- SECTION 1: Basic Information --}}
    {{-- ════════════════════════════════════════════ --}}
    <div class="section-card">
        <div class="card-header" data-bs-toggle="collapse" data-bs-target="#section1">
            <span class="icon-circle" style="background:#e8f5e9;color:#2e7d32;"><i class="fa-solid fa-info-circle"></i></span>
            Informasi Dasar
            <i class="fa-solid fa-chevron-down toggle-icon"></i>
        </div>
        <div class="collapse show" id="section1">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fa-solid fa-tag me-1" style="color:#2e7d32;"></i>
                            Nama Tempat <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" required maxlength="255" placeholder="Masukkan nama tempat">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">
                            <i class="fa-solid fa-layer-group me-1" style="color:#2e7d32;"></i>
                            Kategori <span class="text-danger">*</span>
                        </label>
                        <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                            <option value="">— Pilih Kategori —</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->key }}" {{ old('category')==$cat->key ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">
                            <i class="fa-solid fa-location-dot me-1" style="color:#2e7d32;"></i>
                            Koordinat <span class="text-danger">*</span>
                        </label>
                        <div class="coord-inputs">
                            <div class="coord-field">
                                <input type="number" step="any" name="latitude" id="input-lat"
                                       class="form-control @error('latitude') is-invalid @enderror"
                                       value="{{ old('latitude') }}" required readonly
                                       placeholder="Latitude">
                                @error('latitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="coord-field">
                                <input type="number" step="any" name="longitude" id="input-lng"
                                       class="form-control @error('longitude') is-invalid @enderror"
                                       value="{{ old('longitude') }}" required readonly
                                       placeholder="Longitude">
                                @error('longitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <button type="button" class="btn-pick-map mt-2" onclick="document.getElementById('map-admin').scrollIntoView({behavior:'smooth',block:'center'});">
                            <i class="fa-solid fa-map me-2"></i> Lihat Peta
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════ --}}
    {{-- SECTION 2: Location Map --}}
    {{-- ════════════════════════════════════════════ --}}
    <div class="section-card">
        <div class="card-header" data-bs-toggle="collapse" data-bs-target="#section2">
            <span class="icon-circle" style="background:#fce4ec;color:#c62828;"><i class="fa-solid fa-map"></i></span>
            Peta Lokasi
            <i class="fa-solid fa-chevron-down toggle-icon"></i>
        </div>
        <div class="collapse show" id="section2">
            <div class="card-body">
                <div class="map-toolbar">
                    <button type="button" class="btn-map-tool" id="btnSearchMap"><i class="fa-solid fa-magnifying-glass"></i> Cari Lokasi</button>
                    <button type="button" class="btn-map-tool" id="btnGpsMap"><i class="fa-solid fa-location-crosshairs"></i> GPS Saya</button>
                    <button type="button" class="btn-map-tool" id="btnResetMap"><i class="fa-solid fa-arrows-rotate"></i> Reset Peta</button>
                    <span class="map-mode-divider"></span>
                    <span class="map-mode-pills">
                        <button type="button" class="map-mode-pill" data-mode="satellite"><i class="fa-solid fa-satellite"></i> Satelit</button>
                        <button type="button" class="map-mode-pill active" data-mode="osm"><i class="fa-solid fa-road"></i> Jalan</button>
                        <button type="button" class="map-mode-pill" data-mode="light"><i class="fa-solid fa-sun"></i> Terang</button>
                    </span>
                </div>
                <div id="map-admin"></div>
                <div class="map-coords-display">
                    <i class="fa-solid fa-location-dot" style="color:#2e7d32;"></i>
                    <span>Koordinat:</span>
                    <span class="coord-value" id="coordDisplay">Belum dipilih</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════ --}}
    {{-- SECTION 3: Gallery Images --}}
    {{-- ════════════════════════════════════════════ --}}
    <div class="section-card">
        <div class="card-header" data-bs-toggle="collapse" data-bs-target="#section3">
            <span class="icon-circle" style="background:#e3f2fd;color:#1565c0;"><i class="fa-solid fa-images"></i></span>
            Galeri Foto
            <i class="fa-solid fa-chevron-down toggle-icon"></i>
        </div>
        <div class="collapse show" id="section3">
            <div class="card-body">
                <div class="gallery-drop-zone" id="galleryDropZone">
                    <input type="file" name="images[]" id="galleryInput" accept="image/*" multiple>
                    <div class="dz-icon"><i class="fa-solid fa-cloud-arrow-up"></i></div>
                    <div class="dz-text">Tarik & lepas foto di sini</div>
                    <div class="dz-hint">atau klik untuk memilih beberapa file</div>
                </div>
                <div class="progress-bar-upload" id="uploadProgress">
                    <div class="progress-fill" id="uploadProgressFill"></div>
                </div>
                <div id="galleryContainer">
                    <div class="empty-gallery empty-state" id="emptyGallery">
                        <div class="empty-icon"><i class="fa-regular fa-images"></i></div>
                        <div class="empty-text">Belum ada foto galeri. Tambahkan foto untuk ditampilkan.</div>
                    </div>
                    <div class="gallery-grid" id="galleryGrid" style="display:none;"></div>
                </div>
                <div class="d-flex flex-wrap gap-2 mb-2">
                    <span class="hero-size-hint" style="margin:0;font-size:0.72rem;">
                        <i class="fa-solid fa-image"></i> Gambar: JPG, PNG, WEBP, GIF, SVG, AVIF, BMP, TIFF
                    </span>
                    <span class="hero-size-hint" style="margin:0;font-size:0.72rem;">
                        <i class="fa-solid fa-video"></i> Video: MP4, MOV, AVI, MKV, WEBM, MPEG, WMV, 3GP
                    </span>
                    <span class="hero-size-hint" style="margin:0;font-size:0.72rem;">
                        <i class="fa-solid fa-weight-scale"></i> Maks: Gambar 20MB / Video 300MB
                    </span>
                </div>
                <div class="gallery-stats" id="galleryStats" style="display:none;">
                    <span><i class="fa-regular fa-image me-1"></i> <span id="galleryCount">0</span> file</span>
                    <span><i class="fa-solid fa-check-circle me-1" style="color:#2e7d32;"></i> <span id="galleryCoverInfo">Belum ada cover</span></span>
                </div>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════ --}}
    {{-- SECTION 4: Description --}}
    {{-- ════════════════════════════════════════════ --}}
    <div class="section-card">
        <div class="card-header" data-bs-toggle="collapse" data-bs-target="#section4">
            <span class="icon-circle" style="background:#f3e5f5;color:#7b1fa2;"><i class="fa-solid fa-align-left"></i></span>
            Deskripsi
            <i class="fa-solid fa-chevron-down toggle-icon"></i>
        </div>
        <div class="collapse show" id="section4">
            <div class="card-body">
                <div class="editor-toolbar">
                    <button type="button" class="btn-edit" data-cmd="bold" title="Bold"><i class="fa-solid fa-bold"></i></button>
                    <button type="button" class="btn-edit" data-cmd="italic" title="Italic"><i class="fa-solid fa-italic"></i></button>
                    <button type="button" class="btn-edit" data-cmd="underline" title="Underline"><i class="fa-solid fa-underline"></i></button>
                    <div class="separator"></div>
                    <button type="button" class="btn-edit" data-cmd="insertUnorderedList" title="Bullet List"><i class="fa-solid fa-list-ul"></i></button>
                    <button type="button" class="btn-edit" data-cmd="insertOrderedList" title="Numbered List"><i class="fa-solid fa-list-ol"></i></button>
                    <div class="separator"></div>
                    <button type="button" class="btn-edit" data-cmd="formatBlock" data-value="h3" title="Heading"><i class="fa-solid fa-heading"></i></button>
                    <button type="button" class="btn-edit" data-cmd="formatBlock" data-value="p" title="Paragraph"><i class="fa-solid fa-paragraph"></i></button>
                    <div class="separator"></div>
                    <button type="button" class="btn-edit" data-cmd="createLink" title="Link"><i class="fa-solid fa-link"></i></button>
                    <button type="button" class="btn-edit" data-cmd="insertImage" title="Image"><i class="fa-solid fa-image"></i></button>
                    <div class="separator"></div>
                    <button type="button" class="btn-edit" data-cmd="removeFormat" title="Clear Formatting"><i class="fa-solid fa-eraser"></i></button>
                </div>
                <div id="description-editor" contenteditable="true" data-placeholder="Tulis deskripsi tempat di sini..."></div>
                <textarea name="description" id="description-hidden" style="display:none;">{{ old('description') }}</textarea>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════ --}}
    {{-- SECTION 5: Additional Information --}}
    {{-- ════════════════════════════════════════════ --}}
    <div class="section-card">
        <div class="card-header" data-bs-toggle="collapse" data-bs-target="#section5">
            <span class="icon-circle" style="background:#e0f2f1;color:#00695c;"><i class="fa-solid fa-plus-circle"></i></span>
            Informasi Tambahan
            <i class="fa-solid fa-chevron-down toggle-icon"></i>
        </div>
        <div class="collapse show" id="section5">
            <div class="card-body">
                <div class="info-grid">
                    <div>
                        <label class="form-label"><i class="fa-regular fa-clock me-1" style="color:#2e7d32;"></i> Sejarah</label>
                        <textarea name="history" class="form-control" rows="3" placeholder="Cerita sejarah tempat ini...">{{ old('history') }}</textarea>
                    </div>
                    <div>
                        <label class="form-label"><i class="fa-regular fa-star me-1" style="color:#2e7d32;"></i> Makna Budaya</label>
                        <textarea name="cultural_significance" class="form-control" rows="3" placeholder="Nilai budaya dan tradisi...">{{ old('cultural_significance') }}</textarea>
                    </div>
                    <div>
                        <label class="form-label"><i class="fa-brands fa-youtube me-1" style="color:#2e7d32;"></i> URL Video</label>
                        <input type="text" name="video_url" class="form-control" value="{{ old('video_url') }}" placeholder="https://youtube.com/watch?v=...">
                    </div>
                    <div>
                        <label class="form-label"><i class="fa-solid fa-volume-high me-1" style="color:#2e7d32;"></i> URL Audio</label>
                        <input type="text" name="audio_url" class="form-control" value="{{ old('audio_url') }}" placeholder="https://example.com/audio.mp3">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════ --}}
    {{-- STICKY BOTTOM BAR --}}
    {{-- ════════════════════════════════════════════ --}}
    <div class="bottom-bar">
        <div class="container">
            <div class="bar-inner">
                <div class="bar-left">
                    <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='{{ route('admin.places.index') }}'">
                        <i class="fa-solid fa-xmark me-1"></i> Batal
                    </button>
                </div>
                <div class="bar-right">
                    <button type="button" class="btn btn-outline-secondary" id="btnPreview">
                        <i class="fa-regular fa-eye me-1"></i> Pratinjau
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-paper-plane me-1"></i> Publikasikan
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        'use strict';

        // ─── Toggle collapse icon ────────────────────────
        document.querySelectorAll('.section-card .card-header').forEach(function (header) {
            header.addEventListener('click', function () {
                var target = document.querySelector(this.dataset.bsTarget);
                if (target) {
                    setTimeout(function () {
                        header.classList.toggle('collapsed', !target.classList.contains('show'));
                    }, 100);
                }
            });
        });

        // ─── Gallery (Multiple) ──────────────────────────
        var galleryInput = document.getElementById('galleryInput');
        var galleryDropZone = document.getElementById('galleryDropZone');
        var galleryGrid = document.getElementById('galleryGrid');
        var emptyGallery = document.getElementById('emptyGallery');
        var galleryCount = document.getElementById('galleryCount');
        var galleryCoverInfo = document.getElementById('galleryCoverInfo');
        var galleryStats = document.getElementById('galleryStats');
        var uploadProgress = document.getElementById('uploadProgress');
        var uploadProgressFill = document.getElementById('uploadProgressFill');

        function renderGalleryPreviews(files) {
            galleryGrid.innerHTML = '';
            if (!files || files.length === 0) {
                galleryGrid.style.display = 'none';
                emptyGallery.style.display = 'block';
                galleryStats.style.display = 'none';
                return;
            }
            galleryGrid.style.display = 'grid';
            emptyGallery.style.display = 'none';
            galleryStats.style.display = 'flex';
            galleryCount.textContent = files.length;
            galleryCoverInfo.textContent = files.length + ' file dipilih';

            for (var gi = 0; gi < files.length; gi++) {
                (function (file, idx) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        var item = document.createElement('div');
                        item.className = 'gallery-item';
                        if (file.type.match('video.*')) {
                            var vid = document.createElement('video');
                            vid.src = e.target.result;
                            vid.muted = true;
                            vid.loop = true;
                            vid.style.cssText = 'width:100%;height:100%;object-fit:cover;';
                            vid.addEventListener('mouseenter', function () { this.play(); });
                            vid.addEventListener('mouseleave', function () { this.pause(); });
                            item.appendChild(vid);
                            var playIcon = document.createElement('div');
                            playIcon.style.cssText = 'position:absolute;inset:0;display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.7);font-size:1.5rem;pointer-events:none;';
                            playIcon.innerHTML = '<i class="fa-solid fa-play"></i>';
                            item.appendChild(playIcon);
                        } else {
                            var img = document.createElement('img');
                            img.src = e.target.result;
                            img.alt = file.name;
                            item.appendChild(img);
                        }
                        galleryGrid.appendChild(item);
                    };
                    reader.readAsDataURL(file);
                })(files[gi], gi);
            }
        }

        galleryInput.addEventListener('change', function () {
            renderGalleryPreviews(this.files);
        });

        galleryDropZone.addEventListener('dragover', function (e) {
            e.preventDefault();
            this.classList.add('dragover');
        });
        galleryDropZone.addEventListener('dragleave', function () {
            this.classList.remove('dragover');
        });

        // ─── Rich Text Editor ───────────────────────────
        var editor = document.getElementById('description-editor');
        var hiddenDesc = document.getElementById('description-hidden');

        // Placeholder
        editor.addEventListener('focus', function () {
            if (this.textContent.trim() === '' && this.dataset.placeholder) {
                this.style.color = '#1a202c';
            }
        });

        // Toolbar commands
        document.querySelectorAll('.editor-toolbar .btn-edit').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var cmd = this.dataset.cmd;
                var value = this.dataset.value || null;
                if (cmd === 'createLink') {
                    var url = prompt('Masukkan URL:', 'https://');
                    if (url) document.execCommand(cmd, false, url);
                } else if (cmd === 'insertImage') {
                    var url = prompt('Masukkan URL gambar:', 'https://');
                    if (url) document.execCommand(cmd, false, url);
                } else {
                    document.execCommand(cmd, false, value);
                }
                editor.focus();
            });
        });

        // Sync to hidden textarea on form submit
        document.getElementById('placeForm').addEventListener('submit', function () {
            var html = editor.innerHTML;
            // Remove placeholder styling
            if (html === '<br>' || html === '') html = '';
            hiddenDesc.value = html;
        });

        // Restore old value
        if (hiddenDesc.value) {
            editor.innerHTML = hiddenDesc.value;
        }

        // ─── Map ─────────────────────────────────────────
        var map = L.map('map-admin', {
            center: [-8.6248, 116.1882],
            zoom: 14,
            zoomControl: true,
        });

        // Default: OSM jalan (fallback — Esri satellite bisa dipilih via mode)
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
        document.querySelectorAll('.map-mode-pill').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var mode = this.getAttribute('data-mode');
                document.querySelectorAll('.map-mode-pill').forEach(function (p) { p.classList.remove('active'); });
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

        // Boundary
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

        map.on('click', function (e) { setMarker(e.latlng); });

        // Restore old values
        var oldLat = latInput.value;
        var oldLng = lngInput.value;
        if (oldLat && oldLng && !isNaN(oldLat) && !isNaN(oldLng)) {
            var latlng = [parseFloat(oldLat), parseFloat(oldLng)];
            setMarker(L.latLng(latlng));
            map.setView(latlng, 15);
        }

        // Map tools
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
