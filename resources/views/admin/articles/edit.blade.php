@extends('layouts.admin')

@section('title', 'Edit Artikel Budaya')

@section('content')
<h4 class="mb-4">Edit Artikel Budaya</h4>

<form action="{{ route('admin.articles.update', $article) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Judul Artikel <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title', $article->title) }}" required maxlength="255">
            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-3">
            <label class="form-label">Kategori</label>
            <select name="category" class="form-select @error('category') is-invalid @enderror">
                <option value="">— Pilih —</option>
                @foreach (['Sejarah', 'Tradisi', 'Kuliner', 'Wisata', 'Tokoh'] as $cat)
                    <option value="{{ $cat }}" {{ old('category', $article->category)==$cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
            @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-3">
            <label class="form-label">Penulis</label>
            <input type="text" name="author" class="form-control @error('author') is-invalid @enderror"
                   value="{{ old('author', $article->author) }}" maxlength="255">
            @error('author') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-12">
            <label class="form-label">Ringkasan (Excerpt)</label>
            <textarea name="excerpt" rows="2" class="form-control @error('excerpt') is-invalid @enderror"
                      maxlength="1000">{{ old('excerpt', $article->excerpt) }}</textarea>
            @error('excerpt') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-12">
            <label class="form-label">Konten <span class="text-danger">*</span></label>
            <textarea name="body" rows="12" class="form-control @error('body') is-invalid @enderror"
                      required>{{ old('body', $article->body) }}</textarea>
            @error('body') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Gambar Sampul</label>
            @if ($article->cover_url)
                <div class="mb-2">
                    <img src="{{ $article->cover_url }}" alt="{{ $article->title }}" width="120" style="border-radius:6px;object-fit:cover;">
                </div>
            @endif
            <input type="file" name="cover_image" class="form-control @error('cover_image') is-invalid @enderror"
                   accept="image/jpeg,image/png,image/jpg">
            <small class="text-muted">Format: jpeg, png, jpg. Maks 2MB.</small>
            @error('cover_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-12">
            <div class="form-check">
                <input type="checkbox" name="is_published" class="form-check-input" value="1"
                       id="published" {{ old('is_published', $article->is_published) ? 'checked' : '' }}>
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
