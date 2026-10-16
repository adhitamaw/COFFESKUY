@extends('layouts.app')

@section('title', $cafe->nama)

@section('content')
<div class="container py-4">
    <a href="{{ route('cafe.index') }}" class="small text-muted"><i class="fa-solid fa-arrow-left me-1"></i>Semua cafe</a>

    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mt-2 mb-3">
        <div>
            <h1 class="fw-800 h2 mb-1">{{ $cafe->nama }}</h1>
            <div class="text-muted d-flex flex-wrap gap-3">
                <span><i class="fa-solid fa-location-dot me-1"></i>{{ $cafe->alamat }}</span>
                @if ($cafe->rating)
                    <span class="rating-pill text-dark"><i class="fa-solid fa-star"></i>{{ number_format($cafe->rating, 1) }}
                        <span class="text-muted fw-normal">({{ $cafe->reviews_count }})</span></span>
                @endif
            </div>
        </div>
        @auth
            <button type="button" class="btn btn-outline-primary fav-btn" data-favorite="{{ route('favorite.toggle', $cafe) }}">
                <i class="{{ $isFavorite ? 'fa-solid' : 'fa-regular' }} fa-heart me-1"></i> Favorit
            </button>
        @endauth
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <img src="{{ asset('image/' . $cafe->gambar) }}" alt="{{ $cafe->nama }}" class="cafe-cover mb-3">
            <p class="text-muted">{{ $cafe->content }}</p>

            {{-- Menu --}}
            <h2 class="h5 fw-bold mt-4 mb-3" id="menu">Menu <span class="text-muted fw-normal">({{ $menus->count() }})</span></h2>
            @if ($menus->isEmpty())
                <div class="cs-card empty-state py-4">Menu belum tersedia.</div>
            @else
                <div class="row row-cols-1 row-cols-md-2 g-3">
                    @foreach ($menus as $menu)
                        <div class="col">
                            <div class="cs-card p-2 d-flex align-items-center gap-3 menu-item">
                                <img src="{{ asset('image/' . $menu->gambar) }}" alt="{{ $menu->nama }}" loading="lazy">
                                <div class="flex-grow-1 min-w-0">
                                    <div class="fw-semibold text-truncate">{{ $menu->nama }}</div>
                                    <div class="text-muted small">Rp{{ number_format($menu->harga, 0, ',', '.') }}</div>
                                </div>
                                @auth
                                    <button class="btn btn-primary btn-icon" data-add-cart="{{ route('cart.add', $menu) }}" title="Tambah ke keranjang">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-icon" title="Masuk untuk memesan"><i class="fa-solid fa-plus"></i></a>
                                @endauth
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            @if ($cafe->hasLocation())
                <div id="map" class="cs-map mb-2"></div>
                <a href="https://www.google.com/maps/dir/?api=1&destination={{ $cafe->latitude }},{{ $cafe->longitude }}"
                    target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm w-100 mb-4">
                    <i class="fa-solid fa-diamond-turn-right me-1"></i> Petunjuk arah
                </a>
            @endif

            {{-- Review --}}
            <div class="cs-card p-3">
                <h2 class="h6 fw-bold mb-3">Review <span class="text-muted fw-normal">({{ $reviews->count() }})</span></h2>

                @auth
                    @php $editing = $myReview !== null; @endphp
                    <form method="POST" action="{{ $editing ? route('reviews.update', $myReview) : route('reviews.store') }}"
                        class="mb-3 {{ $editing ? 'd-none' : '' }}" id="reviewForm">
                        @csrf
                        @if ($editing) @method('PUT') @endif
                        <input type="hidden" name="cafe_id" value="{{ $cafe->id }}">
                        @php $current = old('rating', $myReview->rating ?? null); @endphp
                        <div class="star-input mb-2">
                            @for ($i = 5; $i >= 1; $i--)
                                <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" @checked($current == $i) required>
                                <label for="star{{ $i }}" title="{{ $i }} bintang"><i class="fa-solid fa-star"></i></label>
                            @endfor
                        </div>
                        @error('rating') <div class="text-danger small">{{ $message }}</div> @enderror
                        <textarea name="komentar" rows="2" maxlength="500" class="form-control mb-2 @error('komentar') is-invalid @enderror"
                            placeholder="Ceritakan pengalamanmu..." required>{{ old('komentar', $myReview->komentar ?? '') }}</textarea>
                        @error('komentar') <div class="invalid-feedback d-block mb-2">{{ $message }}</div> @enderror
                        <button class="btn btn-primary btn-sm w-100" data-loading="Mengirim...">{{ $editing ? 'Simpan perubahan' : 'Kirim review' }}</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm w-100 mb-3">Masuk untuk memberi review</a>
                @endauth

                @forelse ($reviews as $review)
                    <div class="d-flex gap-2 py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <span class="avatar">{{ strtoupper(substr($review->user->name ?? '?', 0, 1)) }}</span>
                        <div class="flex-grow-1 min-w-0">
                            <div class="d-flex justify-content-between">
                                <span class="fw-semibold small">{{ $review->user->name ?? 'Pengguna' }}</span>
                                <span class="text-muted small">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="small">@include('partials.stars', ['value' => $review->rating])</div>
                            <p class="small mb-1">{{ $review->komentar }}</p>
                            @auth
                                @if ($review->user_id === Auth::id())
                                    <button type="button" class="btn btn-link btn-sm p-0 me-2" onclick="document.getElementById('reviewForm').classList.toggle('d-none')">Edit</button>
                                    <form method="POST" action="{{ route('reviews.destroy', $review) }}" class="d-inline" data-confirm="Hapus review ini?">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-link btn-sm p-0 text-danger">Hapus</button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                @empty
                    <p class="text-muted small mb-0 text-center py-2">Belum ada review.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@if ($cafe->hasLocation())
@push('scripts')
<script>
    (function () {
        const pos = [{{ (float) $cafe->latitude }}, {{ (float) $cafe->longitude }}];
        const map = L.map('map', { scrollWheelZoom: false }).setView(pos, 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap',
        }).addTo(map);
        L.marker(pos).addTo(map).bindPopup(@json($cafe->nama));
    })();
</script>
@endpush
@endif
