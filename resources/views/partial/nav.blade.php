<style>
    /* Penanganan khusus untuk menghapus duplikasi logo */
    .navbar > div:first-child:not(.container),
    .navbar:before,
    .navbar > img:first-child,
    img[alt="COFFEESKUY"] {
        display: none !important;
    }
    
    /* Solusi untuk menghapus logo duplikat di level navbar */
    nav > img,
    .navbar > img {
        display: none !important;
    }
    
    /* Memastikan teks CoffeSkuy terlihat jelas */
    .navbar-brand {
        font-size: 1.8rem !important; 
        font-weight: 700 !important;
        color: white !important;
    }
    
    /* Dropdown menu styling */
    .dropdown-menu {
        background-color: rgba(255, 255, 255, 0.95);
        border: none;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        padding: 8px;
    }
    
    .dropdown-item {
        border-radius: 5px;
        padding: 8px 15px;
        color: #333;
        transition: all 0.2s;
    }
    
    .dropdown-item:hover {
        background-color: rgba(0, 0, 0, 0.1);
    }
    
    /* Auth buttons */
    .auth-buttons {
        display: flex;
        gap: 10px;
        margin-left: 15px;
    }
    
    .btn-auth {
        padding: 8px 15px;
        border-radius: 5px;
        font-weight: 500;
        font-size: 14px;
        transition: all 0.3s;
    }
    
    .btn-login {
        background-color: transparent;
        color: #fff;
        border: 1px solid #fff;
    }
    
    .btn-login:hover {
        background-color: rgba(255, 255, 255, 0.1);
        color: #fff;
    }
    
    .btn-register {
        background-color: #FFD54F;
        color: #333;
        border: 1px solid #FFD54F;
    }
    
    .btn-register:hover {
        background-color: #FFC107;
        border-color: #FFC107;
    }
    
    .btn-logout {
        background-color: #dc3545;
        color: #fff;
        border: 1px solid #dc3545;
    }
    
    .btn-logout:hover {
        background-color: #c82333;
        border-color: #bd2130;
    }
    
    .user-welcome {
        color: #fff;
        margin-right: 15px;
        font-weight: 500;
    }
</style>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <!-- Brand Logo - Hanya teks, tanpa gambar -->
        <a class="navbar-brand" href="/">
            CoffeSkuy
        </a>
        
        <!-- Responsive Hamburger Menu -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
            aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Navbar Links -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                
                <!-- Main Navigation Links -->
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="/">HOME</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('cafe*') ? 'active' : '' }}" href="/cafe">CAFE</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('favorite*') ? 'active' : '' }}" href="/favorite">FAVORITE</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('contact*') ? 'active' : '' }}" href="/contact">CONTACT</a>
                </li>
                
                @guest
                    <!-- Direct Auth Buttons untuk Guest -->
                    <div class="auth-buttons">
                        <a href="/login" class="btn btn-auth btn-login">Login</a>
                        <a href="/register" class="btn btn-auth btn-register">Register</a>
                    </div>
                @else
                    <!-- Menu untuk User yang Sudah Login -->
                    <span class="user-welcome">
                        Hello, {{ Auth::user()->name }}
                        @if(Auth::user()->role == 'admin' || Auth::user()->role == 'penjual')
                            <a href="/admin" class="ms-2 text-warning"><i class="fas fa-crown"></i></a>
                        @endif
                    </span>
                    
                    <!-- Cart Button -->
                    <a href="{{ route('cart') }}" class="btn btn-success me-2">
                        <i class="fas fa-shopping-cart me-1"></i> Keranjang
                        @php $cartCount = count(session('cart', [])) @endphp
                        @if($cartCount > 0)
                            <span class="badge bg-danger">{{ $cartCount }}</span>
                        @endif
                    </a>
                    
                    <!-- Logout Form -->
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-auth btn-logout">
                            <i class="fas fa-sign-out-alt me-1"></i> Logout
                        </button>
                    </form>
                @endguest
            </ul>
        </div>
    </div>
</nav>
