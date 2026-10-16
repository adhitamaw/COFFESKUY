<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.head')
</head>
<body class="d-flex flex-column min-vh-100">
    @php $cartCount = collect(session('cart', []))->sum('quantity'); @endphp
    <nav class="navbar navbar-expand-lg cs-nav sticky-top">
        <div class="container">
            <a class="navbar-brand cs-brand" href="{{ route('home') }}"><i class="fa-solid fa-mug-hot me-1"></i>CoffeSkuy</a>

            <div class="d-flex align-items-center gap-2 order-lg-last">
                @auth
                    <a href="{{ route('cart.index') }}" class="btn btn-icon btn-outline-primary position-relative" title="Keranjang">
                        <i class="fa-solid fa-bag-shopping"></i>
                        <span data-cart-count class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-badge {{ $cartCount ? '' : 'd-none' }}">{{ $cartCount }}</span>
                    </a>
                    <div class="dropdown">
                        <button class="btn btn-icon btn-outline-primary" data-bs-toggle="dropdown" title="{{ Auth::user()->name }}">
                            <i class="fa-solid fa-user"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                            <li class="dropdown-header">{{ Auth::user()->name }}</li>
                            @if (Auth::user()->canAccessDashboard())
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge me-2"></i>Dashboard</a></li>
                            @endif
                            <li><a class="dropdown-item" href="{{ route('favorite.index') }}"><i class="fa-solid fa-heart me-2"></i>Favorit</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item text-danger"><i class="fa-solid fa-right-from-bracket me-2"></i>Keluar</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm px-3">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm px-3 d-none d-sm-inline-block">Daftar</a>
                @endauth
                <button class="navbar-toggler border-0 px-1" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav mx-auto gap-lg-3">
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
        <div class="container d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
            <span><i class="fa-solid fa-mug-hot me-1"></i>&copy; {{ date('Y') }} CoffeSkuy</span>
            <span class="d-flex gap-3">
                <a href="{{ route('cafe.index') }}" class="text-muted">Cafe</a>
                <a href="{{ route('contact') }}" class="text-muted">Kontak</a>
            </span>
        </div>
    </footer>

    @include('partials.scripts')
</body>
</html>
