@extends('layouts.app')

@section('title', $cafe->nama)

@section('content')
<div class="container py-4">
    <a href="{{ route('cafe.index') }}" class="small text-muted"><i class="fa-solid fa-arrow-left me-1"></i>Semua cafe</a>

    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mt-2 mb-3">
        <div class="min-w-0">
            <h1 class="page-title">{{ $cafe->nama }}</h1>
            <div class="text-muted d-flex flex-wrap align-items-center gap-3 mt-1">
                <span><i class="fa-solid fa-location-dot me-1"></i>{{ $cafe->alamat }}</span>
                @if ($cafe->rating)
                    <span class="score"><i class="fa-solid fa-star"></i>{{ number_format($cafe->rating, 1) }}
                        <span class="text-muted fw-normal">· {{ $cafe->reviews_count }} review</span></span>
                @endif
            </div>
        </div>
        @auth
            <button type="button" class="btn btn-outline-primary fav-inline" data-favorite="{{ route('favorite.toggle', $cafe) }}">
                <i class="{{ $isFavorite ? 'fa-solid' : 'fa-regular' }} fa-heart me-1"></i> Favorit
            </button>
        @endauth
    </div>

    <div class="row g-4 g-lg-5">
        <div class="col-lg-8">
            <img src="{{ asset('image/' . $cafe->gambar) }}" alt="{{ $cafe->nama }}" class="cafe-cover mb-3">
            <p class="cafe-desc">{{ $cafe->content }}</p>

            {{-- Papan menu --}}
            <div class="d-flex justify-content-between align-items-baseline mt-4 mb-2" id="menu">
                <h2 class="section-title">Menu</h2>
                <span class="mono small text-muted">{{ $menus->count() }} item</span>
            </div>
            @if ($menus->isEmpty())
                <div class="empty-state">Cafe ini belum memasang menu.</div>
            @else
                <div class="menu-board">
                    @foreach ($menus as $menu)
                        <div class="menu-row">
                            <img src="{{ asset('image/' . $menu->gambar) }}" alt="" loading="lazy">
                            <div class="line">
                                <span class="item">{{ $menu->nama }}</span>
                                <span class="dots" aria-hidden="true"></span>
                                <span class="price">Rp{{ number_format($menu->harga, 0, ',', '.') }}</span>
                            </div>
                            @auth
                                <button class="btn btn-primary btn-icon" data-add-cart="{{ route('cart.add', $menu) }}" aria-label="Tambah {{ $menu->nama }} ke keranjang">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-icon" aria-label="Masuk untuk memesan"><i class="fa-solid fa-plus"></i></a>
                            @endauth
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
            <section class="panel p-3">
                <div class="d-flex justify-content-between align-items-baseline mb-3">
                    <h2 class="section-title" style="font-size:1.05rem">Review</h2>
                    <span class="mono small text-muted">{{ $reviews->count() }}</span>
                </div>

                @auth
                    @php $editing = $myReview !== null; @endphp
                    <form method="POST" action="{{ $editing ? route('reviews.update', $myReview) : route('reviews.store') }}"
                        class="mb-3 {{ $editing && !$errors->any() ? 'd-none' : '' }}" id="reviewForm">
                        @csrf
                        @if ($editing) @method('PUT') @endif
                        <input type="hidden" name="cafe_id" value="{{ $cafe->id }}">
                        @php $current = old('rating', $myReview->rating ?? null); @endphp
                        <fieldset class="mb-2">
                            <legend class="form-label">Nilai kamu</legend>
                            <div class="star-input">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" @checked($current == $i) required>
                                    <label for="star{{ $i }}" title="{{ $i }} bintang"><i class="fa-solid fa-star"></i></label>
                                @endfor
                            </div>
                        </fieldset>
                        @error('rating') <div class="text-danger small mb-2">{{ $message }}</div> @enderror
                        <label for="komentar" class="visually-hidden">Komentar</label>
                        <textarea id="komentar" name="komentar" rows="2" maxlength="500" class="form-control mb-2 @error('komentar') is-invalid @enderror"
                            placeholder="Kopinya, tempatnya, pelayanannya..." required>{{ old('komentar', $myReview->komentar ?? '') }}</textarea>
                        @error('komentar') <div class="invalid-feedback d-block mb-2">{{ $message }}</div> @enderror
                        <button class="btn btn-primary btn-sm w-100" data-loading="Mengirim...">{{ $editing ? 'Simpan perubahan' : 'Kirim review' }}</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm w-100 mb-3">Masuk untuk menulis review</a>
                @endauth

                @forelse ($reviews as $review)
                    <div class="review">
                        <span class="avatar">{{ strtoupper(substr($review->user->name ?? '?', 0, 1)) }}</span>
                        <div class="flex-grow-1 min-w-0">
                            <div class="d-flex justify-content-between gap-2">
                                <span class="fw-semibold small text-truncate">{{ $review->user->name ?? 'Pengguna' }}</span>
                                <span class="text-muted small text-nowrap">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="small mb-1">@include('partials.stars', ['value' => $review->rating])</div>
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
                    <p class="text-muted small mb-0 text-center py-2">Belum ada review. Jadilah yang pertama.</p>
                @endforelse
            </section>
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
