@extends('layouts.admin')

@section('title', 'Tambah Paket Itinerary')

@section('content')
<h4 class="mb-4">Tambah Paket Itinerary / Rekomendasi</h4>

<form action="{{ route('admin.itinerary-packages.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Judul Paket <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title') }}" required maxlength="255">
            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-3">
            <label class="form-label">Durasi</label>
            <input type="text" name="duration" class="form-control @error('duration') is-invalid @enderror"
                   value="{{ old('duration') }}" placeholder="mis: 3 Hari 2 Malam" maxlength="100">
            @error('duration') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-3">
            <label class="form-label">Ikon (Font Awesome)</label>
            <input type="text" name="icon" class="form-control @error('icon') is-invalid @enderror"
                   value="{{ old('icon', 'fa-compass') }}" placeholder="fa-compass" maxlength="100">
            <small class="text-muted">fa-mountain, fa-utensils, dll.</small>
            @error('icon') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-12">
            <label class="form-label">Deskripsi Paket</label>
            <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror"
                      maxlength="5000">{{ old('description') }}</textarea>
            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Gambar Paket</label>
            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                   accept="image/jpeg,image/png,image/jpg">
            <small class="text-muted">Format: jpeg, png, jpg. Maks 2MB.</small>
            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-12">
            <label class="form-label">Pilih Tempat (urutkan sesuai keinginan)</label>
            <div class="row g-2" id="places-container">
                @foreach ($places as $place)
                <div class="col-md-4 col-lg-3">
                    <div class="form-check">
                        <input type="checkbox" name="places[]" value="{{ $place->id }}"
                               class="form-check-input place-checkbox"
                               id="place-{{ $place->id }}"
                               {{ in_array($place->id, old('places', [])) ? 'checked' : '' }}>
                        <label class="form-check-label small" for="place-{{ $place->id }}">
                            {{ $place->name }}
                        </label>
                    </div>
                </div>
                @endforeach
            </div>
            @error('places') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="col-12">
            <div class="form-check">
                <input type="checkbox" name="is_published" class="form-check-input" value="1" checked id="published">
                <label class="form-check-label" for="published">Publikasikan</label>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.itinerary-packages.index') }}" class="btn btn-secondary">Batal</a>
    </div>
</form>
@endsection
