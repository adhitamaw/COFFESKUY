{{-- @include('partials.cafe-card', ['cafe' => $cafe, 'isFavorite' => bool, 'removeOnUnfavorite' => bool]) --}}
<div class="col">
    <div class="cs-card cafe-card position-relative">
        <a href="{{ route('cafe.show', $cafe) }}">
            <img src="{{ asset('image/' . $cafe->gambar) }}" alt="{{ $cafe->nama }}" class="thumb" loading="lazy">
        </a>
        @auth
            <button type="button" class="btn btn-icon fav-btn shadow-sm" title="Favorit"
                data-favorite="{{ route('favorite.toggle', $cafe) }}" @if (!empty($removeOnUnfavorite)) data-remove-card @endif>
                <i class="{{ !empty($isFavorite) ? 'fa-solid' : 'fa-regular' }} fa-heart"></i>
            </button>
        @endauth
        <div class="p-3">
            <div class="d-flex justify-content-between align-items-start gap-2">
                <a href="{{ route('cafe.show', $cafe) }}" class="fw-bold text-dark text-truncate">{{ $cafe->nama }}</a>
                @if ($cafe->rating)
                    <span class="rating-pill"><i class="fa-solid fa-star"></i>{{ number_format($cafe->rating, 1) }}</span>
                @else
                    <span class="badge badge-soft-secondary">Baru</span>
                @endif
            </div>
            <div class="small text-muted text-truncate mt-1">
                <i class="fa-solid fa-location-dot me-1"></i>{{ $cafe->alamat }}
            </div>
        </div>
    </div>
</div>
