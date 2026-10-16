<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.head')
</head>
<body class="admin-body">
    @php
        $me = Auth::user();
        $pendingCount = $me->isAdmin() ? \App\Models\User::where('status', 'pending')->where('role', 'penjual')->count() : 0;
        $links = [
            ['admin.dashboard', 'admin.dashboard', 'fa-gauge', 'Ringkasan'],
            ['admin.cafes.index', 'admin.cafes.*', 'fa-store', 'Cafe'],
            ['admin.menus.index', 'admin.menus.*', 'fa-mug-hot', 'Menu'],
            ['admin.reviews.index', 'admin.reviews.*', 'fa-star', 'Review'],
        ];
    @endphp

    <aside class="admin-sidebar p-3" id="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="cs-brand d-block px-2 mb-4"><i class="fa-solid fa-mug-hot me-1"></i>CoffeSkuy</a>
        <nav class="nav flex-column gap-1">
            @foreach ($links as [$route, $pattern, $icon, $label])
                <a href="{{ route($route) }}" class="nav-link {{ request()->routeIs($pattern) ? 'active' : '' }}">
                    <i class="fa-solid {{ $icon }}"></i>{{ $label }}
                </a>
            @endforeach
            @if ($me->isAdmin())
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users"></i>User
                    @if ($pendingCount)<span class="badge bg-warning text-dark ms-auto">{{ $pendingCount }}</span>@endif
                </a>
            @endif
        </nav>
        <div class="mt-auto">
            <a href="{{ route('home') }}" class="nav-link"><i class="fa-solid fa-arrow-up-right-from-square"></i>Lihat situs</a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="nav-link w-100 border-0 bg-transparent"><i class="fa-solid fa-right-from-bracket"></i>Keluar</button>
            </form>
        </div>
    </aside>

    <div class="admin-main">
        <header class="admin-topbar px-3 px-lg-4 py-3 d-flex align-items-center gap-3 sticky-top">
            <button class="btn btn-icon btn-outline-primary d-lg-none" onclick="document.getElementById('sidebar').classList.toggle('show')">
                <i class="fa-solid fa-bars"></i>
            </button>
            <h1 class="h5 fw-800 mb-0 flex-grow-1 text-truncate">@yield('title', 'Ringkasan')</h1>
            @yield('actions')
            <div class="d-none d-sm-flex align-items-center gap-2">
                <span class="avatar">{{ strtoupper(substr($me->name, 0, 1)) }}</span>
                <div class="lh-sm">
                    <div class="small fw-semibold">{{ $me->name }}</div>
                    <div class="small text-muted text-capitalize">{{ $me->role }}</div>
                </div>
            </div>
        </header>

        <main class="p-3 p-lg-4">
            @yield('content')
        </main>
    </div>

    @include('partials.scripts')
</body>
</html>
