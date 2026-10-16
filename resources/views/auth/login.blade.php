@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
<div class="container auth-wrap">
    <div class="panel auth-card p-4 p-md-5">
        <h1 class="page-title mb-1">Masuk</h1>
        <p class="text-muted mb-4">Selamat datang kembali.</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                    class="form-control @error('email') is-invalid @enderror">
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="form-control @error('password') is-invalid @enderror">
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" @checked(old('remember'))>
                    <label class="form-check-label small" for="remember">Ingat saya</label>
                </div>
                <a href="{{ route('password.request') }}" class="small">Lupa password?</a>
            </div>
            <button class="btn btn-primary w-100" data-loading="Masuk...">Masuk</button>
        </form>

        <p class="text-center text-muted small mt-4 mb-0">Belum punya akun? <a href="{{ route('register') }}" class="fw-semibold">Daftar</a></p>
    </div>
</div>
@endsection
