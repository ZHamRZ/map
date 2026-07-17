@extends('layouts.admin')

@section('title', 'Kelola Paket Itinerary')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Paket Itinerary / Rekomendasi</h4>
    <a href="{{ route('admin.itinerary-packages.create') }}" class="btn btn-success">+ Tambah Paket</a>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Judul</th>
                <th>Durasi</th>
                <th>Jumlah Tempat</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($packages as $pkg)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $pkg->title }}</td>
                <td>{{ $pkg->duration ?? '-' }}</td>
                <td>{{ $pkg->places->count() }}</td>
                <td>
                    @if ($pkg->is_published)
                        <span class="badge bg-success">Aktif</span>
                    @else
                        <span class="badge bg-secondary">Tidak Aktif</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.itinerary-packages.edit', $pkg) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.itinerary-packages.destroy', $pkg) }}" method="POST"
                          onsubmit="return confirm('Yakin hapus paket ini?')" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-muted">Belum ada paket itinerary.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $packages->links() }}
@endsection
