@extends('layouts.app')

@section('title', 'Favorit')

@section('content')
<div class="container py-4">
    <h1 class="section-title mb-4">Favorit saya</h1>

    @if ($cafes->isEmpty())
        <div class="cs-card empty-state">
            <i class="fa-regular fa-heart d-block"></i>
            <p class="mb-3">Belum ada cafe favorit.</p>
            <a href="{{ route('cafe.index') }}" class="btn btn-primary">Jelajahi cafe</a>
        </div>
    @else
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4">
            @foreach ($cafes as $cafe)
                @include('partials.cafe-card', ['cafe' => $cafe, 'isFavorite' => true, 'removeOnUnfavorite' => true])
            @endforeach
        </div>
    @endif
</div>
@endsection
