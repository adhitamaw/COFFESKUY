@extends('layouts.app')

@section('title', 'Cafe')

@section('content')
<div class="container py-4">
    <form method="GET" class="d-flex flex-column flex-sm-row gap-2 mb-4">
        <div class="input-group">
            <span class="input-group-text bg-white border-end-0" style="border-color: var(--cs-line)"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
            <input type="search" name="q" value="{{ $q }}" class="form-control border-start-0" placeholder="Cari nama cafe atau lokasi...">
        </div>
        <select name="sort" class="form-select w-auto" onchange="this.form.submit()">
            <option value="rating" @selected($sort === 'rating')>Rating tertinggi</option>
            <option value="terbaru" @selected($sort === 'terbaru')>Terbaru</option>
        </select>
        <button class="btn btn-primary px-4">Cari</button>
    </form>

    @if ($q)
        <p class="text-muted">{{ $cafes->total() }} hasil untuk "<strong>{{ $q }}</strong>" · <a href="{{ route('cafe.index') }}">reset</a></p>
    @endif

    @if ($cafes->isEmpty())
        <div class="cs-card empty-state">
            <i class="fa-solid fa-mug-saucer d-block"></i>
            Cafe tidak ditemukan.
        </div>
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
