@extends('layouts.app')

@section('title', 'Keranjang')

@section('content')
<div class="container py-4">
    <h1 class="page-title mb-4 text-center">Keranjang</h1>

    @if (empty($cart))
        <div class="empty-state">
            <i class="fa-solid fa-receipt"></i>
            <p class="mb-3">Belum ada pesanan. Pilih menu dari halaman cafe.</p>
            <a href="{{ route('cafe.index') }}" class="btn btn-primary">Lihat cafe</a>
        </div>
    @else
        @php $itemCount = collect($cart)->sum('quantity'); @endphp
        <div class="receipt">
            <div class="head">
                <strong>CoffeSkuy</strong>
                <div class="text-muted small mt-1">{{ now()->translatedFormat('d M Y · H.i') }} · {{ Auth::user()->name }}</div>
            </div>

            @foreach ($cart as $id => $item)
                <div class="receipt-line {{ !$loop->first ? 'sep' : '' }}">
                    <div class="min-w-0">
                        <div class="nm">{{ $item['menu_name'] }}</div>
                        <div class="sub">
                            {{ !empty($item['cafe']) ? $item['cafe'] . ' · ' : '' }}Rp{{ number_format($item['price'], 0, ',', '.') }}/item
                        </div>
                    </div>
                    <div class="amt">Rp{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</div>
                    <div class="qty">
                        <form method="POST" action="{{ route('cart.update', $id) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="quantity" value="{{ $item['quantity'] - 1 }}">
                            <button aria-label="Kurangi {{ $item['menu_name'] }}"><i class="fa-solid fa-minus fa-xs"></i></button>
                        </form>
                        <span>{{ $item['quantity'] }}</span>
                        <form method="POST" action="{{ route('cart.update', $id) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}">
                            <button aria-label="Tambah {{ $item['menu_name'] }}"><i class="fa-solid fa-plus fa-xs"></i></button>
                        </form>
                    </div>
                    <form method="POST" action="{{ route('cart.remove', $id) }}" class="text-end">
                        @csrf @method('DELETE')
                        <button class="btn btn-link btn-sm p-0 text-danger">hapus</button>
                    </form>
                </div>
            @endforeach

            <div class="rule"></div>
            <div class="d-flex justify-content-between text-muted small mb-1"><span>{{ $itemCount }} item</span><span>pajak termasuk</span></div>
            <div class="total"><span>TOTAL</span><strong>Rp{{ number_format($total, 0, ',', '.') }}</strong></div>
            <div class="rule"></div>

            <form method="POST" action="{{ route('checkout') }}">
                @csrf
                <button class="btn btn-primary w-100 py-2" data-loading="Membuka pembayaran..."><i class="fa-solid fa-lock me-1"></i> Bayar Rp{{ number_format($total, 0, ',', '.') }}</button>
            </form>
            <div class="d-flex justify-content-between mt-2">
                <a href="{{ route('cafe.index') }}" class="btn btn-link btn-sm px-0">+ Tambah menu</a>
                <form method="POST" action="{{ route('cart.clear') }}" data-confirm="Kosongkan keranjang?">
                    @csrf @method('DELETE')
                    <button class="btn btn-link btn-sm px-0 text-muted">Kosongkan</button>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection
