@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="container py-5">
    <div class="receipt text-center">
        @if ($paid)
            <span class="badge-status badge-soft-success mb-3">LUNAS</span>
            <h1 class="h4 mb-2">Pembayaran berhasil</h1>
            <p class="text-muted small">Pesananmu diteruskan ke cafe.</p>
        @else
            <span class="badge-status badge-soft-warning mb-3">MENUNGGU</span>
            <h1 class="h4 mb-2">Pembayaran belum terkonfirmasi</h1>
            <p class="text-muted small">Keranjangmu masih tersimpan. Coba bayar lagi.</p>
        @endif
        <div class="rule"></div>
        <a href="{{ $paid ? route('cafe.index') : route('cart.index') }}" class="btn btn-primary w-100">{{ $paid ? 'Kembali ke cafe' : 'Buka keranjang' }}</a>
    </div>
</div>
@endsection
