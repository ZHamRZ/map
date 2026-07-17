@extends('layouts.admin')

@section('title', 'Kelola FAQ')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">FAQ (Pertanyaan Sering Diajukan)</h4>
    <a href="{{ route('admin.faqs.create') }}" class="btn btn-success">+ Tambah FAQ</a>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Pertanyaan</th>
                <th>Kategori</th>
                <th>Urutan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($faqs as $faq)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ Str::limit($faq->question, 60) }}</td>
                <td><span class="badge bg-info">{{ $faq->category ?? '-' }}</span></td>
                <td>{{ $faq->order }}</td>
                <td>
                    @if ($faq->is_published)
                        <span class="badge bg-success">Aktif</span>
                    @else
                        <span class="badge bg-secondary">Tidak Aktif</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.faqs.edit', $faq) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST"
                          onsubmit="return confirm('Yakin hapus FAQ ini?')" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-muted">Belum ada FAQ.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $faqs->links() }}
@endsection
