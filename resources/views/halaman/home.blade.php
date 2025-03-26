@extends('layout.master')

@section('content')
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

<!-- About Section -->
<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <div class="card" style="width: auto;color:black;border-radius:20px;background-color:rgba(217, 217, 217, 0.8);margin-bottom:50px">
                <div class="card-body text-center p-5">
                    <h2 class="card-title mb-4">About Us</h2>
                    <p class="card-text fs-5">We are Coffeeskuy. World's best coffee guide and reccomendator. We are passionate coffee lovers who have dedicated ourselves to sharing our expertise and knowledge with fellow coffee enthusiasts. Our goal is to help you discover the best coffee for your taste buds and provide you with a delightful coffee experience.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

