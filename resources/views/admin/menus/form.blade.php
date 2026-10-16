@extends('layouts.admin')

@section('title', $menu->exists ? 'Edit menu' : 'Tambah menu')

@section('content')
<form method="POST" enctype="multipart/form-data" style="max-width: 640px"
    action="{{ $menu->exists ? route('admin.menus.update', $menu) : route('admin.menus.store') }}">
    @csrf
    @if ($menu->exists) @method('PUT') @endif

    <div class="cs-card p-3 p-md-4">
        <div class="mb-3">
            <label class="form-label" for="cafe_id">Cafe</label>
            <select id="cafe_id" name="cafe_id" required class="form-select @error('cafe_id') is-invalid @enderror">
                @foreach ($cafes as $c)
                    <option value="{{ $c->id }}" @selected(old('cafe_id', $menu->cafe_id) == $c->id)>{{ $c->nama }}</option>
                @endforeach
            </select>
            @error('cafe_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="row g-3 mb-3">
            <div class="col-sm-7">
                <label class="form-label" for="nama">Nama menu</label>
                <input id="nama" name="nama" value="{{ old('nama', $menu->nama) }}" required class="form-control @error('nama') is-invalid @enderror">
                @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-sm-5">
                <label class="form-label" for="harga">Harga</label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input id="harga" type="number" min="0" step="500" name="harga" value="{{ old('harga', $menu->harga) }}" required class="form-control @error('harga') is-invalid @enderror">
                </div>
                @error('harga') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
        </div>
        <div>
            <label class="form-label" for="gambar">Foto</label>
            <div class="d-flex align-items-center gap-3">
                <img id="preview" src="{{ $menu->gambar ? asset('image/' . $menu->gambar) : '' }}" class="table-thumb {{ $menu->gambar ? '' : 'd-none' }}" alt="">
                <input id="gambar" type="file" name="gambar" accept="image/*" @if (!$menu->exists) required @endif
                    class="form-control @error('gambar') is-invalid @enderror"
                    onchange="const p=document.getElementById('preview'); p.src=URL.createObjectURL(this.files[0]); p.classList.remove('d-none')">
            </div>
            @error('gambar') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            @if ($menu->exists) <div class="form-text">Kosongkan jika tidak ganti foto.</div> @endif
        </div>
    </div>

    <div class="d-flex gap-2 mt-3">
        <button class="btn btn-primary px-4" data-loading="Menyimpan...">Simpan</button>
        <a href="{{ route('admin.menus.index') }}" class="btn btn-outline-primary">Batal</a>
    </div>
</form>
@endsection
