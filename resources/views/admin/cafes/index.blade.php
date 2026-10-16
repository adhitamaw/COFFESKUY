@extends('layouts.admin')

@section('title', 'Cafe')

@section('actions')
    <a href="{{ route('admin.cafes.create') }}" class="btn btn-primary btn-sm"><i class="fa-solid fa-plus me-1"></i>Tambah</a>
@endsection

@section('content')
<form method="GET" class="mb-3" style="max-width: 360px">
    <input type="search" name="q" value="{{ $q }}" class="form-control" placeholder="Cari cafe...">
</form>

<div class="cs-card overflow-hidden">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Cafe</th>
                    <th class="d-none d-md-table-cell">Pemilik</th>
                    <th>Rating</th>
                    <th class="d-none d-sm-table-cell">Menu</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($cafes as $cafe)
                    <tr>
                        <td>
                            <a href="{{ route('admin.cafes.show', $cafe) }}" class="d-flex align-items-center gap-2 text-dark">
                                <img src="{{ asset('image/' . $cafe->gambar) }}" class="table-thumb" alt="">
                                <span class="min-w-0">
                                    <span class="fw-semibold d-block">{{ $cafe->nama }}</span>
                                    <span class="small text-muted d-block text-truncate" style="max-width: 260px">{{ $cafe->alamat }}</span>
                                </span>
                            </a>
                        </td>
                        <td class="d-none d-md-table-cell small">{{ $cafe->user->name ?? '-' }}</td>
                        <td>
                            @if ($cafe->rating)
                                <span class="rating-pill"><i class="fa-solid fa-star"></i>{{ number_format($cafe->rating, 1) }}</span>
                                <span class="small text-muted">({{ $cafe->reviews_count }})</span>
                            @else
                                <span class="small text-muted">-</span>
                            @endif
                        </td>
                        <td class="d-none d-sm-table-cell">{{ $cafe->menus_count }}</td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('cafe.show', $cafe) }}" target="_blank" class="btn btn-sm btn-outline-primary" title="Lihat"><i class="fa-solid fa-eye"></i></a>
                            <a href="{{ route('admin.cafes.edit', $cafe) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fa-solid fa-pen"></i></a>
                            <form method="POST" action="{{ route('admin.cafes.destroy', $cafe) }}" class="d-inline" data-confirm="Hapus {{ $cafe->nama }} beserta menu & review-nya?">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="fa-solid fa-trash-can"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty-state">Belum ada cafe.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $cafes->links() }}</div>
@endsection
