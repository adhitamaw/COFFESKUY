@extends('layouts.app')

@section('title', 'Kontak')

@section('content')
<div class="container py-5" style="max-width: 640px">
    <h1 class="page-title mb-2">Kontak</h1>
    <p class="text-muted mb-4">Balasan biasanya dalam satu hari kerja.</p>
    <div class="contact-list">
        <a href="mailto:hello@coffeskuy.com" class="contact-row"><span><i class="fa-solid fa-envelope me-2"></i>Email</span><span>hello@coffeskuy.com</span></a>
        <a href="https://wa.me/6281234567890" target="_blank" rel="noopener" class="contact-row"><span><i class="fa-brands fa-whatsapp me-2"></i>WhatsApp</span><span>+62 812-3456-7890</span></a>
        <a href="https://instagram.com/coffeskuy" target="_blank" rel="noopener" class="contact-row"><span><i class="fa-brands fa-instagram me-2"></i>Instagram</span><span>@coffeskuy</span></a>
        <div class="contact-row"><span><i class="fa-solid fa-clock me-2"></i>Jam layanan</span><span>Sen–Jum 09.00–17.00</span></div>
    </div>
</div>
@endsection
