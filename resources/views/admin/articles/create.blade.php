@extends('layouts.admin')

@section('title', 'Tambah Artikel Budaya')

@section('content')
<h4 class="mb-4">Tambah Artikel Budaya</h4>

<form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Judul Artikel <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title') }}" required maxlength="255">
            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-3">
            <label class="form-label">Kategori</label>
            <select name="category" class="form-select @error('category') is-invalid @enderror">
                <option value="">— Pilih —</option>
                <option value="Sejarah" {{ old('category')=='Sejarah' ? 'selected' : '' }}>Sejarah</option>
                <option value="Tradisi" {{ old('category')=='Tradisi' ? 'selected' : '' }}>Tradisi</option>
                <option value="Kuliner" {{ old('category')=='Kuliner' ? 'selected' : '' }}>Kuliner</option>
                <option value="Wisata" {{ old('category')=='Wisata' ? 'selected' : '' }}>Wisata</option>
                <option value="Tokoh" {{ old('category')=='Tokoh' ? 'selected' : '' }}>Tokoh</option>
            </select>
            @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-3">
            <label class="form-label">Penulis</label>
            <input type="text" name="author" class="form-control @error('author') is-invalid @enderror"
                   value="{{ old('author') }}" maxlength="255">
            @error('author') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-12">
            <label class="form-label">Ringkasan (Excerpt)</label>
            <textarea name="excerpt" rows="2" class="form-control @error('excerpt') is-invalid @enderror"
                      maxlength="1000">{{ old('excerpt') }}</textarea>
            @error('excerpt') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-12">
            <label class="form-label">Konten <span class="text-danger">*</span></label>
            <textarea name="body" rows="12" class="form-control @error('body') is-invalid @enderror"
                      required>{{ old('body') }}</textarea>
            @error('body') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Gambar Sampul</label>
            <input type="file" name="cover_image" class="form-control @error('cover_image') is-invalid @enderror"
                   accept="image/jpeg,image/png,image/jpg">
            <small class="text-muted">Format: jpeg, png, jpg. Maks 2MB.</small>
            @error('cover_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
        <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">Batal</a>
    </div>
</form>
@endsection
