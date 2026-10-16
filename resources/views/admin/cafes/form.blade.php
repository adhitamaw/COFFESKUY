@extends('layouts.admin')

@section('title', $cafe->exists ? 'Edit cafe' : 'Tambah cafe')

@section('content')
<form method="POST" enctype="multipart/form-data"
    action="{{ $cafe->exists ? route('admin.cafes.update', $cafe) : route('admin.cafes.store') }}">
    @csrf
    @if ($cafe->exists) @method('PUT') @endif

    <div class="row g-3">
        <div class="col-lg-7">
            <div class="panel p-3 p-md-4">
                <div class="mb-3">
                    <label class="form-label" for="nama">Nama</label>
                    <input id="nama" name="nama" value="{{ old('nama', $cafe->nama) }}" required class="form-control @error('nama') is-invalid @enderror">
                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="alamat">Alamat</label>
                    <input id="alamat" name="alamat" value="{{ old('alamat', $cafe->alamat) }}" required class="form-control @error('alamat') is-invalid @enderror">
                    @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="content">Deskripsi</label>
                    <textarea id="content" name="content" rows="4" required class="form-control @error('content') is-invalid @enderror">{{ old('content', $cafe->content) }}</textarea>
                    @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="form-label" for="gambar">Foto</label>
                    <div class="d-flex align-items-center gap-3">
                        <img id="preview" src="{{ $cafe->gambar ? asset('image/' . $cafe->gambar) : '' }}" class="table-thumb {{ $cafe->gambar ? '' : 'd-none' }}" alt="">
                        <input id="gambar" type="file" name="gambar" accept="image/*" @if (!$cafe->exists) required @endif
                            class="form-control @error('gambar') is-invalid @enderror"
                            onchange="const p=document.getElementById('preview'); p.src=URL.createObjectURL(this.files[0]); p.classList.remove('d-none')">
                    </div>
                    @error('gambar') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    @if ($cafe->exists) <div class="form-text">Kosongkan jika tidak ganti foto.</div> @endif
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="panel p-3 p-md-4">
                <label class="form-label">Lokasi <span class="text-muted fw-normal small">· klik peta</span></label>
                <div id="map" class="admin-map mb-2"></div>
                <div class="row g-2">
                    <div class="col-6">
                        <input id="latitude" name="latitude" value="{{ old('latitude', $cafe->latitude) }}" placeholder="Latitude" class="form-control form-control-sm @error('latitude') is-invalid @enderror">
                    </div>
                    <div class="col-6">
                        <input id="longitude" name="longitude" value="{{ old('longitude', $cafe->longitude) }}" placeholder="Longitude" class="form-control form-control-sm @error('longitude') is-invalid @enderror">
                    </div>
                </div>
                @error('latitude') <div class="text-danger small">{{ $message }}</div> @enderror
                @error('longitude') <div class="text-danger small">{{ $message }}</div> @enderror
                <button type="button" class="btn btn-link btn-sm px-0" id="useMyLocation"><i class="fa-solid fa-location-crosshairs me-1"></i>Pakai lokasi saya</button>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 mt-3">
        <button class="btn btn-primary px-4" data-loading="Menyimpan...">Simpan</button>
        <a href="{{ route('admin.cafes.index') }}" class="btn btn-outline-primary">Batal</a>
    </div>
</form>
@endsection

@push('scripts')
<script>
    (function () {
        const lat = document.getElementById('latitude');
        const lng = document.getElementById('longitude');
        const hasPos = lat.value && lng.value && !isNaN(lat.value) && !isNaN(lng.value);
        const start = hasPos ? [parseFloat(lat.value), parseFloat(lng.value)] : [-6.9147, 107.6098];

        const map = L.map('map').setView(start, hasPos ? 15 : 11);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(map);
        let marker = hasPos ? L.marker(start).addTo(map) : null;

        function setPos(p) {
            lat.value = p.lat.toFixed(7);
            lng.value = p.lng.toFixed(7);
            marker ? marker.setLatLng(p) : (marker = L.marker(p).addTo(map));
        }

        map.on('click', (e) => setPos(e.latlng));
        document.getElementById('useMyLocation').addEventListener('click', () => {
            navigator.geolocation?.getCurrentPosition((g) => {
                const p = L.latLng(g.coords.latitude, g.coords.longitude);
                setPos(p);
                map.setView(p, 16);
            }, () => csToast('error', 'Lokasi tidak bisa diakses'));
        });
    })();
</script>
@endpush
