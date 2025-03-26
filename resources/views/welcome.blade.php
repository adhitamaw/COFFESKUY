@extends('layout.master')

@section('content')
<title>CoffeSkuy</title>

<div class="hero-section">
    <div class="container text-center text-white">
        <div class="hero-content">
            <h1 class="display-3 fw-bold mb-3">CoffeSkuy</h1>
            <h2 class="h3 mb-5">World's Leading Coffee Guide</h2>
            <p class="lead mb-5">Discover the best coffee shops in your area. <br> Find your perfect brew today.</p>
            <a href="/cafe" class="btn btn-primary btn-lg px-5 py-3 rounded-pill">EXPLORE CAFES</a>
        </div>
    </div>
</div>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="feature-card text-center p-4">
                <div class="icon-wrapper mb-3">
                    <i class="fas fa-coffee fa-3x"></i>
                </div>
                <h3>Find Best Cafes</h3>
                <p>Discover top-rated cafes near you with detailed reviews and ratings.</p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="feature-card text-center p-4">
                <div class="icon-wrapper mb-3">
                    <i class="fas fa-map-marker-alt fa-3x"></i>
                </div>
                <h3>Locate Easily</h3>
                <p>Find the exact location of each cafe with our integrated maps.</p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="feature-card text-center p-4">
                <div class="icon-wrapper mb-3">
                    <i class="fas fa-star fa-3x"></i>
                </div>
                <h3>Review & Rate</h3>
                <p>Share your experiences and help others find the perfect cup of coffee.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Optional: Add some animations or interactive elements
    document.addEventListener('DOMContentLoaded', function() {
        const heroContent = document.querySelector('.hero-content');
        heroContent.classList.add('animate-fade-in');
    });
</script>
@endsection

