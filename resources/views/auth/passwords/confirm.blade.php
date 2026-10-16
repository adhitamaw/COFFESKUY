@extends('layouts.app')

@section('title', 'Konfirmasi password')

@section('content')
<div class="container auth-wrap">
    <div class="panel auth-card p-4 p-md-5">
        <h1 class="page-title mb-4">Konfirmasi password</h1>
        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf
            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="form-control @error('password') is-invalid @enderror">
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <button class="btn btn-primary w-100">Lanjut</button>
        </form>
    </div>
</div>
@endsection
