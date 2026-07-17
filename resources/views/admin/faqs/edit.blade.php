@extends('layouts.admin')

@section('title', 'Edit FAQ')

@section('content')
<h4 class="mb-4">Edit FAQ</h4>

<form action="{{ route('admin.faqs.update', $faq) }}" method="POST">
    @csrf @method('PUT')

    <div class="row g-3">
        <div class="col-md-8">
            <label class="form-label">Pertanyaan <span class="text-danger">*</span></label>
            <input type="text" name="question" class="form-control @error('question') is-invalid @enderror"
                   value="{{ old('question', $faq->question) }}" required maxlength="500">
            @error('question') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-2">
            <label class="form-label">Kategori</label>
            <select name="category" class="form-select @error('category') is-invalid @enderror">
                <option value="">— Pilih —</option>
                @foreach (['Transportasi', 'Akomodasi', 'Kuliner', 'Budaya', 'Wisata', 'Praktis'] as $cat)
                    <option value="{{ $cat }}" {{ old('category', $faq->category)==$cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
            @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-2">
            <label class="form-label">Urutan</label>
            <input type="number" name="order" class="form-control @error('order') is-invalid @enderror"
                   value="{{ old('order', $faq->order) }}" min="0">
            @error('order') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-12">
            <label class="form-label">Jawaban <span class="text-danger">*</span></label>
            <textarea name="answer" rows="5" class="form-control @error('answer') is-invalid @enderror"
                      required maxlength="10000">{{ old('answer', $faq->answer) }}</textarea>
            @error('answer') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-12">
            <div class="form-check">
                <input type="checkbox" name="is_published" class="form-check-input" value="1"
                       id="published" {{ old('is_published', $faq->is_published) ? 'checked' : '' }}>
                <label class="form-check-label" for="published">Publikasikan</label>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary">Batal</a>
    </div>
</form>
@endsection
