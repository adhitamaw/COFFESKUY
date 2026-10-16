@extends('layouts.app')

@section('content')
<section class="hero">
    <div class="container hero-grid">
        <div>
            <span class="eyebrow">Panduan ngopi</span>
            <h1>Cari cafe, lihat menu, <em>langsung pesan.</em></h1>
            <p class="lede mb-4">Semua cafe, menu, dan review dalam satu tempat.</p>
            <form action="{{ route('cafe.index') }}" method="GET" class="search-bar" role="search">
                <label for="q" class="visually-hidden">Cari cafe</label>
                <input id="q" type="search" name="q" class="form-control" placeholder="Nama cafe atau kota">
                <button class="btn btn-primary px-4">Cari</button>
            </form>
            <div class="stat-line">
                <div><strong>{{ $stats['cafe'] }}</strong><span>cafe</span></div>
                <div><strong>{{ $stats['menu'] }}</strong><span>menu</span></div>
                <div><strong>{{ $stats['review'] }}</strong><span>review</span></div>
            </div>
        </div>
        <div class="hero-photo">
            <img src="{{ asset('img/bg.png') }}" alt="Barista menuang kopi seduh manual">
            @if ($topCafes->isNotEmpty() && $topCafes->first()->rating)
                @php $best = $topCafes->first(); @endphp
                <a href="{{ route('cafe.show', $best) }}" class="tag">
                    <span class="d-block text-muted" style="font-size:.7rem">Rating tertinggi</span>
                    <strong>{{ $best->nama }}</strong> <span class="score ms-1"><i class="fa-solid fa-star"></i>{{ number_format($best->rating, 1) }}</span>
                </a>
            @endif
        </div>
    </div>
</section>

<section class="container pb-5">
    <div class="d-flex justify-content-between align-items-baseline mb-3">
        <h2 class="section-title">Paling disukai</h2>
        <a href="{{ route('cafe.index') }}" class="fw-semibold">Semua cafe <i class="fa-solid fa-arrow-right ms-1"></i></a>
    </div>

    @if ($topCafes->isEmpty())
        <div class="empty-state"><i class="fa-solid fa-mug-saucer"></i>Belum ada cafe.</div>
    @else
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4">
            @foreach ($topCafes as $cafe)
                @include('partials.cafe-card', ['cafe' => $cafe])
            @endforeach
        </div>
    @endif
</section>

@guest
<section class="seller-band">
    <div class="container d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h2 class="section-title mb-1">Punya cafe?</h2>
            <p class="text-muted mb-0">Pasang menu dan terima pesanan online. Gratis.</p>
        </div>
        <a href="{{ route('register', ['role' => 'penjual']) }}" class="btn btn-dark px-4">Daftar sebagai penjual</a>
    </div>
</section>
@endguest
@endsection
