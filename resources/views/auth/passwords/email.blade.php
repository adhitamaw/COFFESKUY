@extends('layouts.app')

@section('title', 'Lupa password')

@section('content')
<div class="container auth-wrap py-5">
    <div class="cs-card auth-card p-4 p-md-5">
        <h1 class="h4 fw-800 mb-1">Lupa password</h1>
        <p class="text-muted mb-4">Kami kirim link reset ke emailmu.</p>
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="form-control @error('email') is-invalid @enderror">
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <button class="btn btn-primary w-100" data-loading="Mengirim...">Kirim link</button>
        </form>
        <p class="text-center small mt-4 mb-0"><a href="{{ route('login') }}">Kembali ke login</a></p>
    </div>
</div>
@endsection
