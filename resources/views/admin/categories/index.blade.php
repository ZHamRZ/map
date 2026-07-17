@extends('layouts.admin')

@section('title', 'Kelola Kategori')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Kategori Tempat</h4>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-success">+ Tambah Kategori</a>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Key</th>
                <th>Nama</th>
                <th>Warna</th>
                <th>Ikon</th>
                <th>Urutan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $cat)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td><code>{{ $cat->key }}</code></td>
                <td>
                    <span style="display:inline-block;width:12px;height:12px;border-radius:50%;background:{{ $cat->color }};margin-right:6px;vertical-align:middle;"></span>
                    {{ $cat->name }}
                </td>
                <td><code>{{ $cat->color }}</code></td>
                <td><i class="fa-solid {{ $cat->icon }}"></i></td>
                <td>{{ $cat->sort_order }}</td>
                <td>
                    @if ($cat->is_active)
                        <span class="badge bg-success">Aktif</span>
                    @else
                        <span class="badge bg-secondary">Tidak Aktif</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST"
                          onsubmit="return confirm('Yakin hapus kategori ini? Tempat dengan kategori ini tidak akan terhapus.')" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center text-muted">Belum ada kategori.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
