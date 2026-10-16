{{-- @include('partials.cafe-card', ['cafe' => $cafe, 'isFavorite' => bool, 'removeOnUnfavorite' => bool]) --}}
<div class="col">
    <article class="cafe-tile">
        <a href="{{ route('cafe.show', $cafe) }}" class="photo">
            <img src="{{ asset('image/' . $cafe->gambar) }}" alt="{{ $cafe->nama }}" loading="lazy">
        </a>
        @auth
            <button type="button" class="btn btn-icon fav-btn" aria-label="Simpan ke favorit"
                data-favorite="{{ route('favorite.toggle', $cafe) }}" @if (!empty($removeOnUnfavorite)) data-remove-card @endif>
                <i class="{{ !empty($isFavorite) ? 'fa-solid' : 'fa-regular' }} fa-heart"></i>
            </button>
        @endauth
        <div>
            <div class="d-flex justify-content-between align-items-baseline gap-2">
                <a href="{{ route('cafe.show', $cafe) }}" class="name text-truncate">{{ $cafe->nama }}</a>
                @if ($cafe->rating)
                    <span class="score"><i class="fa-solid fa-star"></i>{{ number_format($cafe->rating, 1) }}</span>
                @else
                    <span class="tag-new">Baru</span>
                @endif
            </div>
            <div class="addr text-truncate">{{ $cafe->alamat }}</div>
        </div>
    </article>
</div>
