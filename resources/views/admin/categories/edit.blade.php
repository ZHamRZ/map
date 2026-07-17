@extends('layouts.admin')

@section('title', 'Edit Kategori')

@section('content')
<h4 class="mb-4">Edit Kategori</h4>

<form action="{{ route('admin.categories.update', $category) }}" method="POST">
    @csrf @method('PUT')

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Key <span class="text-danger">*</span></label>
            <input type="text" name="key" class="form-control @error('key') is-invalid @enderror"
                   value="{{ old('key', $category->key) }}" required maxlength="100">
            <small class="text-muted">Identifier unik. Ubah dengan hati-hati — tempat dengan key lama tidak otomatis terupdate.</small>
            @error('key') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Nama Tampilan <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name', $category->name) }}" required maxlength="100">
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-4">
            <label class="form-label">Warna (hex) <span class="text-danger">*</span></label>
            <div class="input-group">
                <input type="color" name="color" class="form-control form-control-color @error('color') is-invalid @enderror"
                       value="{{ old('color', $category->color) }}" style="max-width:60px;padding:2px;">
                <input type="text" name="color_text" class="form-control @error('color') is-invalid @enderror"
                       value="{{ old('color', $category->color) }}" placeholder="#RRGGBB" maxlength="20"
                       oninput="this.form.color.value=this.value" onchange="this.form.color.value=this.value">
            </div>
            @error('color') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-4">
            <label class="form-label">Ikon Font Awesome</label>
            <input type="text" name="icon" class="form-control @error('icon') is-invalid @enderror"
                   value="{{ old('icon', $category->icon) }}" maxlength="50" placeholder="fa-heart">
            @error('icon') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-4">
            <label class="form-label">Urutan</label>
            <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror"
                   value="{{ old('sort_order', $category->sort_order) }}" min="0">
            @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-12">
            <label class="form-label">SVG Path <span class="text-danger">*</span></label>
            <textarea name="svg_path" rows="3" class="form-control @error('svg_path') is-invalid @enderror"
                      maxlength="2000" required>{{ old('svg_path', $category->svg_path) }}</textarea>
            <small class="text-muted">SVG path untuk ikon marker di peta.</small>
            @error('svg_path') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-12">
            <div class="form-check">
                <input type="checkbox" name="is_active" class="form-check-input" value="1"
                       id="active" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="active">Aktif</label>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Batal</a>
    </div>
</form>
@endsection
