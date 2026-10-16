@extends('layouts.admin')

@section('title', 'Menu')

@section('actions')
    <a href="{{ route('admin.menus.create', ['cafe' => $cafeId]) }}" class="btn btn-primary btn-sm"><i class="fa-solid fa-plus me-1"></i>Tambah</a>
@endsection

@section('content')
<form method="GET" class="d-flex flex-wrap gap-2 mb-3">
    <input type="search" name="q" value="{{ $q }}" class="form-control" style="max-width: 260px" placeholder="Cari menu...">
    <select name="cafe" class="form-select w-auto" onchange="this.form.submit()">
        <option value="">Semua cafe</option>
        @foreach ($cafes as $c)
            <option value="{{ $c->id }}" @selected($cafeId == $c->id)>{{ $c->nama }}</option>
        @endforeach
    </select>
</form>

<div class="cs-card overflow-hidden">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Menu</th>
                    <th class="d-none d-md-table-cell">Cafe</th>
                    <th>Harga</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($menus as $menu)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ asset('image/' . $menu->gambar) }}" class="table-thumb" alt="">
                                <span class="fw-semibold">{{ $menu->nama }}</span>
                            </div>
                        </td>
                        <td class="small d-none d-md-table-cell">{{ $menu->cafe->nama ?? '-' }}</td>
                        <td class="text-nowrap">Rp{{ number_format($menu->harga, 0, ',', '.') }}</td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('admin.menus.edit', $menu) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fa-solid fa-pen"></i></a>
                            <form method="POST" action="{{ route('admin.menus.destroy', $menu) }}" class="d-inline" data-confirm="Hapus {{ $menu->nama }}?">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="fa-solid fa-trash-can"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="empty-state">Belum ada menu.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $menus->links() }}</div>
@endsection
