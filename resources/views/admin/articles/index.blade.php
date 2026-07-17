@extends('layouts.admin')

@section('title', 'Kelola Artikel Budaya')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Artikel Budaya</h4>
    <a href="{{ route('admin.articles.create') }}" class="btn btn-success">+ Tambah Artikel</a>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Judul</th>
                <th>Kategori</th>
                <th>Penulis</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($articles as $article)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $article->title }}</td>
                <td><span class="badge bg-info">{{ $article->category ?? '-' }}</span></td>
                <td>{{ $article->author ?? '-' }}</td>
                <td>
                    @if ($article->is_published)
                        <span class="badge bg-success">Terbit</span>
                    @else
                        <span class="badge bg-secondary">Draft</span>
                    @endif
                </td>
                <td>{{ $article->created_at->format('d M Y') }}</td>
                <td>
                    <a href="{{ route('admin.articles.edit', $article) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.articles.destroy', $article) }}" method="POST"
                          onsubmit="return confirm('Yakin hapus artikel ini?')" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center text-muted">Belum ada artikel.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $articles->links() }}
@endsection
