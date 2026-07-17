@extends('layouts.admin')

@section('title', 'Kelola Acara Budaya')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Acara & Festival Budaya</h4>
    <a href="{{ route('admin.events.create') }}" class="btn btn-success">+ Tambah Acara</a>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Judul</th>
                <th>Kategori</th>
                <th>Tanggal Mulai</th>
                <th>Jam Mulai</th>
                <th>Tanggal Selesai</th>
                <th>Jam Selesai</th>
                <th>Lokasi</th>
                <th>Koordinat</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($events as $event)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $event->title }}</td>
                <td><span class="badge bg-info">{{ $event->category ?? '-' }}</span></td>
                <td>{{ $event->start_date->format('d M Y') }}</td>
                <td>{{ $event->start_time ?? '-' }}</td>
                <td>{{ $event->end_date?->format('d M Y') ?? '-' }}</td>
                <td>{{ $event->end_time ?? '-' }}</td>
                <td>{{ $event->location ?? '-' }}</td>
                <td>
                    @if ($event->latitude && $event->longitude)
                        <span class="small text-muted">{{ number_format($event->latitude, 4) }}, {{ number_format($event->longitude, 4) }}</span>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td>
                    @if ($event->is_published)
                        <span class="badge bg-success">Aktif</span>
                    @else
                        <span class="badge bg-secondary">Tidak Aktif</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.events.destroy', $event) }}" method="POST"
                          onsubmit="return confirm('Yakin hapus acara ini?')" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="11" class="text-center text-muted">Belum ada data acara.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $events->links() }}
@endsection
