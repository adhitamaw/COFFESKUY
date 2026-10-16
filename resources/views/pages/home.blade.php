@extends('layouts.app')

@section('content')
<section class="hero">
    <div class="container text-center">
        <h1 class="mb-3">Temukan cafe favoritmu</h1>
        <p class="lead opacity-75 mb-4">Cari, lihat menu, dan pesan kopi dalam satu tempat.</p>
        <form action="{{ route('cafe.index') }}" method="GET" class="hero-search d-flex mx-auto">
            <input type="search" name="q" class="form-control" placeholder="Nama cafe atau lokasi...">
            <button class="btn btn-primary px-4"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>
        <div class="d-flex justify-content-center gap-5 mt-5">
            <div><div class="stat-num">{{ $stats['cafe'] }}</div><small class="opacity-75">Cafe</small></div>
            <div><div class="stat-num">{{ $stats['menu'] }}</div><small class="opacity-75">Menu</small></div>
            <div><div class="stat-num">{{ $stats['review'] }}</div><small class="opacity-75">Review</small></div>
        </div>
    </div>
</section>

<section class="container py-5">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <h2 class="section-title mb-0">Rating tertinggi</h2>
        <a href="{{ route('cafe.index') }}" class="fw-semibold">Lihat semua <i class="fa-solid fa-arrow-right ms-1"></i></a>
    </div>

    @if ($topCafes->isEmpty())
        <div class="cs-card empty-state">
            <i class="fa-solid fa-mug-saucer d-block"></i>
            Belum ada cafe.
        </div>
    @else
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4">
            @foreach ($topCafes as $cafe)
                @include('partials.cafe-card', ['cafe' => $cafe])
            @endforeach
        </div>
    @endif
</section>

<section class="container pb-4">
    <div class="row g-4 text-center">
        <div class="col-md-4">
            <div class="feature-icon mb-3"><i class="fa-solid fa-magnifying-glass-location"></i></div>
            <h3 class="h6 fw-bold mb-1">Cari</h3>
            <p class="text-muted small mb-0">Cafe terdekat lengkap dengan peta.</p>
        </div>
        <div class="col-md-4">
            <div class="feature-icon mb-3"><i class="fa-solid fa-bag-shopping"></i></div>
            <h3 class="h6 fw-bold mb-1">Pesan</h3>
            <p class="text-muted small mb-0">Pilih menu, bayar online.</p>
        </div>
        <div class="col-md-4">
            <div class="feature-icon mb-3"><i class="fa-solid fa-star"></i></div>
            <h3 class="h6 fw-bold mb-1">Nilai</h3>
            <p class="text-muted small mb-0">Bagikan pengalamanmu.</p>
        </div>
    </div>
</section>

@guest
<section class="container py-4">
    <div class="cs-card p-4 p-md-5 d-md-flex justify-content-between align-items-center">
        <div class="mb-3 mb-md-0">
            <h2 class="h4 fw-800 mb-1">Punya cafe?</h2>
            <p class="text-muted mb-0">Daftarkan cafe & menumu gratis.</p>
        </div>
        <a href="{{ route('register', ['role' => 'penjual']) }}" class="btn btn-dark px-4">Daftar sebagai penjual</a>
    </div>
</section>
@endguest
@endsection
