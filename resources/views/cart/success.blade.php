@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="container py-5" style="max-width: 520px">
    <div class="cs-card p-5 text-center">
        @if ($paid)
            <div class="feature-icon mb-3" style="background:#e3f5ea;color:#1d7a46"><i class="fa-solid fa-check"></i></div>
            <h1 class="h4 fw-800">Pembayaran berhasil</h1>
            <p class="text-muted">Pesananmu sedang diproses cafe.</p>
        @else
            <div class="feature-icon mb-3"><i class="fa-solid fa-hourglass-half"></i></div>
            <h1 class="h4 fw-800">Pembayaran belum terkonfirmasi</h1>
            <p class="text-muted">Cek lagi keranjangmu atau coba bayar ulang.</p>
        @endif
        <a href="{{ $paid ? route('cafe.index') : route('cart.index') }}" class="btn btn-primary mt-2">{{ $paid ? 'Kembali belanja' : 'Ke keranjang' }}</a>
    </div>
</div>
@endsection
