@extends('layouts.app')

@section('title', 'Kontak')

@section('content')
<div class="container py-5" style="max-width: 720px">
    <h1 class="section-title mb-4">Hubungi kami</h1>
    <div class="row g-3">
        <div class="col-sm-6">
            <a href="mailto:hello@coffeskuy.com" class="cs-card p-4 d-flex align-items-center gap-3 text-dark h-100">
                <span class="feature-icon"><i class="fa-solid fa-envelope"></i></span>
                <span><span class="fw-bold d-block">Email</span><span class="text-muted small">hello@coffeskuy.com</span></span>
            </a>
        </div>
        <div class="col-sm-6">
            <a href="https://wa.me/6281234567890" target="_blank" rel="noopener" class="cs-card p-4 d-flex align-items-center gap-3 text-dark h-100">
                <span class="feature-icon"><i class="fa-brands fa-whatsapp"></i></span>
                <span><span class="fw-bold d-block">WhatsApp</span><span class="text-muted small">+62 812-3456-7890</span></span>
            </a>
        </div>
        <div class="col-sm-6">
            <a href="https://instagram.com/coffeskuy" target="_blank" rel="noopener" class="cs-card p-4 d-flex align-items-center gap-3 text-dark h-100">
                <span class="feature-icon"><i class="fa-brands fa-instagram"></i></span>
                <span><span class="fw-bold d-block">Instagram</span><span class="text-muted small">@coffeskuy</span></span>
            </a>
        </div>
        <div class="col-sm-6">
            <div class="cs-card p-4 d-flex align-items-center gap-3 h-100">
                <span class="feature-icon"><i class="fa-solid fa-clock"></i></span>
                <span><span class="fw-bold d-block">Jam layanan</span><span class="text-muted small">Senin–Jumat, 09.00–17.00</span></span>
            </div>
        </div>
    </div>
</div>
@endsection
