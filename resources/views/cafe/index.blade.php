@extends('layouts.app')

@section('title', 'Cafe')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <h1 class="page-title">Cafe</h1>
        <form method="GET" class="filter-bar flex-grow-1 justify-content-md-end" role="search">
            <label for="q" class="visually-hidden">Cari</label>
            <input id="q" type="search" name="q" value="{{ $q }}" class="form-control" placeholder="Nama cafe atau kota">
            <label for="sort" class="visually-hidden">Urutkan</label>
            <select id="sort" name="sort" class="form-select" onchange="this.form.submit()">
                <option value="rating" @selected($sort === 'rating')>Rating tertinggi</option>
                <option value="terbaru" @selected($sort === 'terbaru')>Terbaru</option>
            </select>
            <button class="btn btn-primary">Cari</button>
        </form>
    </div>

    @if ($q)
        <p class="text-muted"><span class="mono">{{ $cafes->total() }}</span> hasil untuk "{{ $q }}" · <a href="{{ route('cafe.index') }}">hapus pencarian</a></p>
    @endif

    @if ($cafes->isEmpty())
        <div class="empty-state"><i class="fa-solid fa-mug-saucer"></i>Tidak ada cafe yang cocok. Coba kata lain.</div>
    @else
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4">
            @foreach ($cafes as $cafe)
                @include('partials.cafe-card', ['cafe' => $cafe, 'isFavorite' => in_array($cafe->id, $favoriteIds)])
            @endforeach
        </div>
        <div class="mt-4 d-flex justify-content-center">{{ $cafes->links() }}</div>
    @endif
</div>
@endsection
