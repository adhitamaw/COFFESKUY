{{-- @include('partials.stars', ['value' => 4.2]) --}}
<span class="stars">
    @for ($i = 1; $i <= 5; $i++)
        @if ($value >= $i)
            <i class="fa-solid fa-star"></i>
        @elseif ($value >= $i - 0.5)
            <i class="fa-solid fa-star-half-stroke"></i>
        @else
            <i class="fa-solid fa-star off"></i>
        @endif
    @endfor
</span>
