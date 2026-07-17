@extends('layouts.admin')

@section('title', 'Pesan Masuk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Pesan / Inquiry Masuk</h4>
    <span class="badge bg-warning text-dark fs-6">{{ App\Models\Inquiry::unread()->count() }} Belum Dibaca</span>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Pesan</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($inquiries as $inq)
            <tr class="{{ !$inq->is_read ? 'table-info' : '' }}">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $inq->name }}</td>
                <td>{{ $inq->email }}</td>
                <td>{{ $inq->phone ?? '-' }}</td>
                <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                    {{ Str::limit($inq->message, 60) }}
                </td>
                <td>
                    @if ($inq->is_read)
                        <span class="badge bg-secondary">Sudah Dibaca</span>
                    @else
                        <span class="badge bg-warning text-dark">Baru</span>
                    @endif
                </td>
                <td>{{ $inq->created_at->format('d M Y H:i') }}</td>
                <td>
                    <a href="{{ route('admin.inquiries.show', $inq) }}" class="btn btn-sm btn-info text-white">Lihat</a>
                    <form action="{{ route('admin.inquiries.destroy', $inq) }}" method="POST"
                          onsubmit="return confirm('Yakin hapus pesan ini?')" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center text-muted">Belum ada pesan masuk.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $inquiries->links() }}
@endsection
