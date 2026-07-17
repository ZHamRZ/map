@extends('layouts.admin')

@section('title', 'Kelola Data Tempat')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Data Tempat / Potensi Desa</h4>
    <a href="{{ route('admin.places.create') }}" class="btn btn-success">
        + Tambah Data
    </a>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Kategori</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Foto</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($places as $place)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $place->name }}</td>
                <td><span class="badge bg-info">{{ $place->category }}</span></td>
                <td>{{ $place->latitude }}</td>
                <td>{{ $place->longitude }}</td>
                <td>
                    @php
                        $firstImg = $place->image_url ?: ($place->images->first()?->image_url);
                        $imgCount = $place->images->count();
                    @endphp
                    @if ($firstImg)
                        <div style="position:relative;display:inline-block;">
                            <img src="{{ $firstImg }}" alt="{{ $place->name }}" width="60" height="60"
                                 style="object-fit:cover; border-radius:6px;">
                            @if ($imgCount > 1)
                                <span style="position:absolute;bottom:-4px;right:-4px;background:#198754;color:#fff;font-size:10px;font-weight:700;width:20px;height:20px;border-radius:50%;display:flex;align-items:center;justify-content:center;border:2px solid #fff;">{{ $imgCount }}</span>
                            @endif
                        </div>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.places.edit', $place) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.places.destroy', $place) }}" method="POST"
                          onsubmit="return confirm('Yakin hapus data ini?')" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center text-muted">Belum ada data.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $places->links() }}
@endsection
