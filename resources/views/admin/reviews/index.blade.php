@extends('layouts.admin')

@section('title', 'Review')

@section('content')
<form method="GET" class="d-flex flex-wrap gap-2 mb-3">
    <select name="cafe" class="form-select w-auto" onchange="this.form.submit()">
        <option value="">Semua cafe</option>
        @foreach ($cafes as $c)
            <option value="{{ $c->id }}" @selected($cafeId == $c->id)>{{ $c->nama }}</option>
        @endforeach
    </select>
    <select name="rating" class="form-select w-auto" onchange="this.form.submit()">
        <option value="">Semua bintang</option>
        @for ($i = 5; $i >= 1; $i--)
            <option value="{{ $i }}" @selected($rating == $i)>{{ $i }} bintang</option>
        @endfor
    </select>
    <select name="sort" class="form-select w-auto" onchange="this.form.submit()">
        @foreach (['terbaru' => 'Terbaru', 'terlama' => 'Terlama', 'tertinggi' => 'Rating tertinggi', 'terendah' => 'Rating terendah'] as $key => $label)
            <option value="{{ $key }}" @selected($sort === $key)>{{ $label }}</option>
        @endforeach
    </select>
</form>

@include('admin.reviews.table', ['reviews' => $reviews, 'showCafe' => true])
@endsection
