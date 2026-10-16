@extends('layouts.admin')

@section('title', 'Ringkasan')

@section('content')
<div class="row g-3 mb-4">
    @foreach ([
        ['Cafe', $stats['cafe'], 'fa-store', '#f5e9dc', '#6f4e37', route('admin.cafes.index')],
        ['Menu', $stats['menu'], 'fa-mug-hot', '#e8f1fb', '#2563a8', route('admin.menus.index')],
        ['Review', $stats['review'], 'fa-comment', '#e3f5ea', '#1d7a46', route('admin.reviews.index')],
        ['Rata-rata', $stats['rating'] ?: '-', 'fa-star', '#fff3d6', '#b07a00', route('admin.reviews.index')],
    ] as [$label, $value, $icon, $bg, $fg, $url])
        <div class="col-6 col-xl-3">
            <a href="{{ $url }}" class="cs-card stat-card p-3 d-flex align-items-center gap-3 text-dark h-100">
                <span class="icon" style="background: {{ $bg }}; color: {{ $fg }}"><i class="fa-solid {{ $icon }}"></i></span>
                <span>
                    <span class="d-block fs-4 fw-800 lh-1">{{ $value }}</span>
                    <span class="small text-muted">{{ $label }}</span>
                </span>
            </a>
        </div>
    @endforeach
</div>

@if ($pendingSellers->isNotEmpty())
    <div class="cs-card p-3 mb-4 border-warning">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h2 class="h6 fw-bold mb-0"><i class="fa-solid fa-user-clock text-warning me-2"></i>Penjual menunggu persetujuan</h2>
            <a href="{{ route('admin.users.index', ['status' => 'pending']) }}" class="small">Semua</a>
        </div>
        @foreach ($pendingSellers->take(5) as $seller)
            <div class="d-flex align-items-center gap-2 py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                <div class="flex-grow-1 min-w-0">
                    <div class="fw-semibold small text-truncate">{{ $seller->name }}</div>
                    <div class="text-muted small text-truncate">{{ $seller->email }}</div>
                </div>
                @if ($seller->pdf_file)
                    <a href="{{ asset('pdfs/' . $seller->pdf_file) }}" target="_blank" class="btn btn-sm btn-outline-primary" title="Dokumen"><i class="fa-solid fa-file-pdf"></i></a>
                @endif
                <form method="POST" action="{{ route('admin.users.approve', $seller) }}">@csrf
                    <button class="btn btn-sm btn-success">Setujui</button>
                </form>
                <form method="POST" action="{{ route('admin.users.reject', $seller) }}" data-confirm="Tolak {{ $seller->name }}?">@csrf
                    <button class="btn btn-sm btn-outline-danger">Tolak</button>
                </form>
            </div>
        @endforeach
    </div>
@endif

<div class="row g-3">
    <div class="col-lg-4">
        <div class="cs-card p-3 h-100">
            <h2 class="h6 fw-bold mb-3">Sebaran rating</h2>
            @php $totalReviews = max($distribution->sum(), 1); @endphp
            @for ($i = 5; $i >= 1; $i--)
                @php $count = $distribution[$i] ?? 0; @endphp
                <div class="d-flex align-items-center gap-2 mb-2 small">
                    <span class="text-nowrap" style="width: 28px">{{ $i }} <i class="fa-solid fa-star text-warning"></i></span>
                    <div class="dist-bar flex-grow-1"><span style="width: {{ round($count / $totalReviews * 100) }}%"></span></div>
                    <span class="text-muted text-end" style="width: 28px">{{ $count }}</span>
                </div>
            @endfor
        </div>
    </div>
    <div class="col-lg-8">
        <div class="cs-card p-3 h-100">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h2 class="h6 fw-bold mb-0">Review terbaru</h2>
                <a href="{{ route('admin.reviews.index') }}" class="small">Semua</a>
            </div>
            @forelse ($latestReviews as $review)
                <div class="d-flex gap-2 py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <span class="avatar">{{ strtoupper(substr($review->user->name ?? '?', 0, 1)) }}</span>
                    <div class="flex-grow-1 min-w-0">
                        <div class="d-flex justify-content-between gap-2">
                            <span class="small"><strong>{{ $review->user->name ?? 'Pengguna' }}</strong>
                                <span class="text-muted">di {{ $review->cafe->nama ?? '-' }}</span></span>
                            <span class="small text-muted text-nowrap">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="small">@include('partials.stars', ['value' => $review->rating])</div>
                        <div class="small text-muted text-truncate">{{ $review->komentar }}</div>
                    </div>
                </div>
            @empty
                <p class="text-muted small text-center py-4 mb-0">Belum ada review.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
