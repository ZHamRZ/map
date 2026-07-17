@extends('layouts.admin')

@section('title', 'Detail Pesan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Detail Pesan</h4>
    <a href="{{ route('admin.inquiries.index') }}" class="btn btn-secondary">Kembali</a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-borderless">
            <tr>
                <th style="width:120px;">Nama</th>
                <td>{{ $inquiry->name }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td><a href="mailto:{{ $inquiry->email }}">{{ $inquiry->email }}</a></td>
            </tr>
            @if ($inquiry->phone)
            <tr>
                <th>Telepon</th>
                <td><a href="tel:{{ $inquiry->phone }}">{{ $inquiry->phone }}</a></td>
            </tr>
            @endif
            <tr>
                <th>Diterima</th>
                <td>{{ $inquiry->created_at->format('d M Y H:i') }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    @if ($inquiry->is_read)
                        <span class="badge bg-secondary">Sudah Dibaca</span>
                    @else
                        <span class="badge bg-warning text-dark">Baru</span>
                    @endif
                </td>
            </tr>
        </table>

        <hr>
        <h6>Pesan:</h6>
        <p style="white-space:pre-wrap;" class="mt-2">{{ $inquiry->message }}</p>

        <hr>
        <a href="mailto:{{ $inquiry->email }}" class="btn btn-primary">
            <i class="fa-regular fa-envelope me-1"></i> Balas via Email
        </a>
        <form action="{{ route('admin.inquiries.destroy', $inquiry) }}" method="POST"
              onsubmit="return confirm('Yakin hapus pesan ini?')" class="d-inline">
            @csrf @method('DELETE')
            <button class="btn btn-danger"><i class="fa-regular fa-trash-can me-1"></i> Hapus</button>
        </form>
    </div>
</div>
@endsection
