@extends('layouts.admin')

@section('title', 'Edit Paket Itinerary')

@section('content')
<h4 class="mb-4">Edit Paket Itinerary / Rekomendasi</h4>

<form action="{{ route('admin.itinerary-packages.update', $itineraryPackage) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Judul Paket <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title', $itineraryPackage->title) }}" required maxlength="255">
            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-3">
            <label class="form-label">Durasi</label>
            <input type="text" name="duration" class="form-control @error('duration') is-invalid @enderror"
                   value="{{ old('duration', $itineraryPackage->duration) }}" maxlength="100">
            @error('duration') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-3">
            <label class="form-label">Ikon</label>
            <input type="text" name="icon" class="form-control @error('icon') is-invalid @enderror"
                   value="{{ old('icon', $itineraryPackage->icon ?? 'fa-compass') }}" maxlength="100">
            @error('icon') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-12">
            <label class="form-label">Deskripsi Paket</label>
            <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror"
                      maxlength="5000">{{ old('description', $itineraryPackage->description) }}</textarea>
            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Gambar Paket</label>
            @if ($itineraryPackage->image_url)
                <div class="mb-2">
                    <img src="{{ $itineraryPackage->image_url }}" alt="{{ $itineraryPackage->title }}" width="120" style="border-radius:6px;object-fit:cover;">
                </div>
            @endif
            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                   accept="image/jpeg,image/png,image/jpg">
            <small class="text-muted">Format: jpeg, png, jpg. Maks 2MB.</small>
            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-12">
            <label class="form-label">Pilih Tempat</label>
            <div class="row g-2">
                @php $selectedPlaces = old('places', $itineraryPackage->places->pluck('id')->toArray()); @endphp
                @foreach ($places as $place)
                <div class="col-md-4 col-lg-3">
                    <div class="form-check">
                        <input type="checkbox" name="places[]" value="{{ $place->id }}"
                               class="form-check-input"
                               id="place-{{ $place->id }}"
                               {{ in_array($place->id, $selectedPlaces) ? 'checked' : '' }}>
                        <label class="form-check-label small" for="place-{{ $place->id }}">
                            {{ $place->name }}
                        </label>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="col-12">
            <div class="form-check">
                <input type="checkbox" name="is_published" class="form-check-input" value="1"
                       id="published" {{ old('is_published', $itineraryPackage->is_published) ? 'checked' : '' }}>
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
