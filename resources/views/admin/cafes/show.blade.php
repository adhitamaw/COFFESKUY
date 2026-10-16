@extends('layouts.admin')

@section('title', $cafe->nama)

@section('actions')
    <a href="{{ route('admin.cafes.edit', $cafe) }}" class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-pen me-1"></i>Edit</a>
    <a href="{{ route('admin.menus.create', ['cafe' => $cafe->id]) }}" class="btn btn-primary btn-sm"><i class="fa-solid fa-plus me-1"></i>Menu</a>
@endsection

@section('content')
<div class="row g-3 mb-3">
    <div class="col-md-4">
        <img src="{{ asset('image/' . $cafe->gambar) }}" class="w-100 rounded-3" style="height: 200px; object-fit: cover" alt="">
    </div>
    <div class="col-md-8">
        <div class="panel p-3 h-100">
            <div class="text-muted small mb-2"><i class="fa-solid fa-location-dot me-1"></i>{{ $cafe->alamat }}</div>
            <p class="mb-3">{{ $cafe->content }}</p>
            <div class="d-flex gap-4">
                <div><div class="mono fs-5 fw-semibold">{{ $cafe->rating ? number_format($cafe->rating, 1) : '-' }}</div><div class="small text-muted">Rating</div></div>
                <div><div class="mono fs-5 fw-semibold">{{ $cafe->reviews_count }}</div><div class="small text-muted">Review</div></div>
                <div><a href="{{ route('admin.menus.index', ['cafe' => $cafe->id]) }}" class="text-reset"><div class="mono fs-5 fw-semibold">{{ $cafe->menus_count }}</div><div class="small text-muted">Menu</div></a></div>
            </div>
        </div>
    </div>
</div>

@include('admin.reviews.table', ['reviews' => $reviews, 'showCafe' => false])
@endsection
