@extends('layouts.app')

@section('title', 'Keranjang')

@section('content')
<div class="container py-4" style="max-width: 860px">
    <h1 class="section-title mb-4">Keranjang</h1>

    @if (empty($cart))
        <div class="cs-card empty-state">
            <i class="fa-solid fa-bag-shopping d-block"></i>
            <p class="mb-3">Keranjang masih kosong.</p>
            <a href="{{ route('cafe.index') }}" class="btn btn-primary">Cari menu</a>
        </div>
    @else
        <div class="row g-4">
            <div class="col-md-8">
                <div class="cs-card">
                    @foreach ($cart as $id => $item)
                        <div class="d-flex align-items-center gap-3 p-3 menu-item {{ !$loop->last ? 'border-bottom' : '' }}">
                            <img src="{{ asset('image/' . $item['photo']) }}" alt="{{ $item['menu_name'] }}">
                            <div class="flex-grow-1 min-w-0">
                                <div class="fw-semibold text-truncate">{{ $item['menu_name'] }}</div>
                                <div class="text-muted small">
                                    @if (!empty($item['cafe'])){{ $item['cafe'] }} · @endif
                                    Rp{{ number_format($item['price'], 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="d-flex align-items-center qty-group">
                                <form method="POST" action="{{ route('cart.update', $id) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="quantity" value="{{ $item['quantity'] - 1 }}">
                                    <button class="btn btn-outline-primary"><i class="fa-solid fa-minus small"></i></button>
                                </form>
                                <span>{{ $item['quantity'] }}</span>
                                <form method="POST" action="{{ route('cart.update', $id) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}">
                                    <button class="btn btn-outline-primary"><i class="fa-solid fa-plus small"></i></button>
                                </form>
                            </div>
                            <form method="POST" action="{{ route('cart.remove', $id) }}">
                                @csrf @method('DELETE')
                                <button class="btn btn-link text-danger p-1" title="Hapus"><i class="fa-solid fa-trash-can"></i></button>
                            </form>
                        </div>
                    @endforeach
                </div>
                <form method="POST" action="{{ route('cart.clear') }}" class="mt-2" data-confirm="Kosongkan keranjang?">
                    @csrf @method('DELETE')
                    <button class="btn btn-link btn-sm text-muted p-0">Kosongkan keranjang</button>
                </form>
            </div>

            <div class="col-md-4">
                <div class="cs-card p-3 sticky-top" style="top: 90px">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Total</span>
                        <span class="fw-800 fs-5">Rp{{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <form method="POST" action="{{ route('checkout') }}">
                        @csrf
                        <button class="btn btn-primary w-100" data-loading="Memproses..."><i class="fa-solid fa-lock me-1"></i> Bayar</button>
                    </form>
                    <a href="{{ route('cafe.index') }}" class="btn btn-link btn-sm w-100 mt-1">Tambah menu lain</a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
