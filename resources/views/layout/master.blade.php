<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CoffeSkuy - World's Leading Coffee Guide</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <!-- Leaflet Maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Inline styles for immediate fixes -->
    <style>
        .bg {
            background-image: url("{{ asset('img/bg.png') }}");
            background-repeat: no-repeat; 
            background-size: cover;  
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
        }
        
        .navbar {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 15px 0;
        }
        
        .navbar-brand {
            color: #fff !important;
            font-weight: 700;
            font-size: 1.8rem;
        }
        
        .nav-link {
            color: #fff !important;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .nav-link:hover {
            color: #FFD54F !important;
        }
        
        footer {
            background-color: rgba(0, 0, 0, 0.8);
            padding: 1.5rem 0;
            margin-top: auto;
        }
        
        .card {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        
        /* Hero Section Styles */
        .hero-section {
            height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
        
        .hero-content {
            animation: fadeIn 1.5s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Feature Cards */
        .feature-card {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            height: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        
        .icon-wrapper {
            color: #4E342E;
        }
    </style>
</head>

<body class="bg d-flex flex-column min-vh-100">
    @include('sweetalert::alert')
    
    <!-- Navigation -->
    @include('partial.nav')

    <!-- Main Content -->
    <main class="flex-grow-1">
        <div class="container py-3">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    @include('partial.footer')

    <!-- Scripts -->
    @yield('scripts')
    
    <!-- SweetAlert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    
    <!-- Bootstrap 5 Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="{{ asset('js/app.js') }}"></script>
    
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/804c519240.js" crossorigin="anonymous"></script>
    
    <!-- Fix navigation dropdown issue -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Bootstrap 5 dropdown initialization
            document.querySelectorAll('.dropdown-toggle').forEach(function(element) {
                element.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    var dropdown = bootstrap.Dropdown.getOrCreateInstance(element);
                    dropdown.toggle();
                });
            });
        });
    </script>
</body>

</html>
