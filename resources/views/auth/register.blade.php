@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
@php $role = old('role', request('role') === 'penjual' ? 'penjual' : 'user'); @endphp
<div class="container auth-wrap py-5">
    <div class="cs-card auth-card p-4 p-md-5">
        <h1 class="h4 fw-800 mb-4">Buat akun</h1>

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
            @csrf
            <div class="row g-2 mb-3">
                <div class="col-6 role-option">
                    <input type="radio" name="role" id="role-user" value="user" @checked($role === 'user')>
                    <label for="role-user"><i class="fa-solid fa-mug-hot d-block mb-1"></i><span class="small fw-semibold">Pembeli</span></label>
                </div>
                <div class="col-6 role-option">
                    <input type="radio" name="role" id="role-penjual" value="penjual" @checked($role === 'penjual')>
                    <label for="role-penjual"><i class="fa-solid fa-store d-block mb-1"></i><span class="small fw-semibold">Penjual</span></label>
                </div>
            </div>
            @error('role') <div class="text-danger small mb-2">{{ $message }}</div> @enderror

            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                    class="form-control @error('name') is-invalid @enderror">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                    class="form-control @error('email') is-invalid @enderror">
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="row g-2 mb-3">
                <div class="col-sm-6">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" name="password" required minlength="8" autocomplete="new-password"
                        class="form-control @error('password') is-invalid @enderror" placeholder="Min. 8 karakter">
                </div>
                <div class="col-sm-6">
                    <label for="password-confirm" class="form-label">Ulangi</label>
                    <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password" class="form-control">
                </div>
                @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3 {{ $role === 'penjual' ? '' : 'd-none' }}" id="pdfField">
                <label for="pdf" class="form-label">Dokumen usaha <span class="text-muted fw-normal">(PDF, opsional)</span></label>
                <input id="pdf" type="file" name="pdf" accept=".pdf" class="form-control @error('pdf') is-invalid @enderror">
                @error('pdf') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <div class="form-text">Akun penjual aktif setelah disetujui admin.</div>
            </div>

            <button class="btn btn-primary w-100" data-loading="Mendaftar...">Daftar</button>
        </form>

        <p class="text-center text-muted small mt-4 mb-0">Sudah punya akun? <a href="{{ route('login') }}" class="fw-semibold">Masuk</a></p>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('input[name="role"]').forEach((el) => el.addEventListener('change', () => {
        document.getElementById('pdfField').classList.toggle('d-none', el.value !== 'penjual' || !el.checked);
    }));
</script>
@endpush
