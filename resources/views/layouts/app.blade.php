<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.head')
</head>
<body class="d-flex flex-column min-vh-100">
    @php $cartCount = collect(session('cart', []))->sum('quantity'); @endphp
    <nav class="navbar navbar-expand-md cs-nav sticky-top py-2">
        <div class="container">
            <a class="cs-brand" href="{{ route('home') }}"><span class="bean"></span>CoffeSkuy</a>

            <div class="d-flex align-items-center gap-2 order-md-last">
                <button type="button" class="btn btn-icon btn-outline-primary" data-theme-toggle title="Ganti tema" aria-label="Ganti tema">
                    <i class="fa-solid fa-moon"></i>
                </button>
                @auth
                    <a href="{{ route('cart.index') }}" class="btn btn-icon btn-outline-primary position-relative" title="Keranjang" aria-label="Keranjang">
                        <i class="fa-solid fa-bag-shopping"></i>
                        <span data-cart-count class="count-badge cart-dot {{ $cartCount ? '' : 'd-none' }}">{{ $cartCount }}</span>
                    </a>
                    <div class="dropdown">
                        <button class="btn btn-icon btn-outline-primary" data-bs-toggle="dropdown" aria-label="Akun">
                            <i class="fa-solid fa-user"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li class="dropdown-header">{{ Auth::user()->name }}</li>
                            @if (Auth::user()->canAccessDashboard())
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge me-2"></i>Dashboard</a></li>
                            @endif
                            <li><a class="dropdown-item" href="{{ route('favorite.index') }}"><i class="fa-solid fa-heart me-2"></i>Favorit</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item"><i class="fa-solid fa-right-from-bracket me-2"></i>Keluar</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm px-3">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm px-3 d-none d-sm-inline-block">Daftar</a>
                @endauth
                <button class="nav-toggle d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-label="Menu">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-md-4 me-auto gap-md-3">
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('cafe.*') ? 'active' : '' }}" href="{{ route('cafe.index') }}">Cafe</a></li>
                    @auth
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('favorite.*') ? 'active' : '' }}" href="{{ route('favorite.index') }}">Favorit</a></li>
                    @endauth
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Kontak</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="flex-grow-1">
        @yield('content')
    </main>

    <footer class="cs-footer py-4 mt-5">
        <div class="container d-flex flex-wrap justify-content-between align-items-center gap-2">
            <span>&copy; {{ date('Y') }} CoffeSkuy</span>
            <span class="d-flex gap-3">
                <a href="{{ route('cafe.index') }}">Cafe</a>
                <a href="{{ route('contact') }}">Kontak</a>
            </span>
        </div>
    </footer>

    @include('partials.scripts')
</body>
</html>
