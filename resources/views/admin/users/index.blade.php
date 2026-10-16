@extends('layouts.admin')

@section('title', 'User')

@section('content')
@php
    $statusBadge = ['approved' => 'success', 'pending' => 'warning', 'rejected' => 'danger'];
    $statusLabel = ['approved' => 'Aktif', 'pending' => 'Menunggu', 'rejected' => 'Ditolak'];
    $roleLabel = ['admin' => 'Admin', 'penjual' => 'Penjual', 'user' => 'Pembeli'];
@endphp

<form method="GET" class="d-flex flex-wrap gap-2 mb-3">
    <input type="search" name="q" value="{{ $q }}" class="form-control" style="max-width: 260px" placeholder="Nama atau email...">
    <select name="role" class="form-select w-auto" onchange="this.form.submit()">
        <option value="">Semua role</option>
        @foreach ($roleLabel as $key => $label)
            <option value="{{ $key }}" @selected($role === $key)>{{ $label }}</option>
        @endforeach
    </select>
    <select name="status" class="form-select w-auto" onchange="this.form.submit()">
        <option value="">Semua status</option>
        @foreach ($statusLabel as $key => $label)
            <option value="{{ $key }}" @selected($status === $key)>{{ $label }}</option>
        @endforeach
    </select>
</form>

<div class="panel overflow-hidden">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th class="d-none d-md-table-cell">Daftar</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>
                            <div class="fw-semibold small">{{ $user->name }}</div>
                            <div class="text-muted small">{{ $user->email }}</div>
                        </td>
                        <td class="small">{{ $roleLabel[$user->role] ?? $user->role }}</td>
                        <td><span class="badge-status badge-soft-{{ $statusBadge[$user->status] ?? 'secondary' }}">{{ $statusLabel[$user->status] ?? $user->status }}</span></td>
                        <td class="small text-muted d-none d-md-table-cell">{{ $user->created_at?->translatedFormat('d M Y') }}</td>
                        <td class="text-end text-nowrap">
                            @if ($user->pdf_file)
                                <a href="{{ asset('pdfs/' . $user->pdf_file) }}" target="_blank" class="btn btn-sm btn-outline-primary" title="Dokumen"><i class="fa-solid fa-file-pdf"></i></a>
                            @endif
                            @if (!$user->is(Auth::user()))
                                @if ($user->status !== 'approved')
                                    <form method="POST" action="{{ route('admin.users.approve', $user) }}" class="d-inline">@csrf
                                        <button class="btn btn-sm btn-outline-success" title="Setujui"><i class="fa-solid fa-check"></i></button>
                                    </form>
                                @endif
                                @if ($user->status !== 'rejected')
                                    <form method="POST" action="{{ route('admin.users.reject', $user) }}" class="d-inline" data-confirm="Tolak/nonaktifkan {{ $user->name }}?">@csrf
                                        <button class="btn btn-sm btn-outline-warning" title="Tolak"><i class="fa-solid fa-ban"></i></button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline" data-confirm="Hapus {{ $user->name }} beserta datanya?">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="fa-solid fa-trash-can"></i></button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty-state">Tidak ada user.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $users->links() }}</div>
@endsection
