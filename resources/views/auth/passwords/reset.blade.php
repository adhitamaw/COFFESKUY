@extends('layouts.app')

@section('title', 'Reset password')

@section('content')
<div class="container auth-wrap py-5">
    <div class="cs-card auth-card p-4 p-md-5">
        <h1 class="h4 fw-800 mb-4">Password baru</h1>
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" type="email" name="email" value="{{ $email ?? old('email') }}" required
                    class="form-control @error('email') is-invalid @enderror">
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password baru</label>
                <input id="password" type="password" name="password" required autofocus autocomplete="new-password"
                    class="form-control @error('password') is-invalid @enderror">
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-4">
                <label for="password-confirm" class="form-label">Ulangi password</label>
                <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password" class="form-control">
            </div>
            <button class="btn btn-primary w-100">Simpan</button>
        </form>
    </div>
</div>
@endsection
