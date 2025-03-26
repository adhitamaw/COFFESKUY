@extends('layout.master')

@section('content')
<?php
use Illuminate\Support\Facades\Auth;
?>
<div class="cafe-detail-page">
    <!-- Header & Navigation -->
    <div class="cafe-header py-3">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Home</a></li>
                        <li class="breadcrumb-item"><a href="/cafe" class="text-decoration-none">Cafes</a></li>
                        <li class="breadcrumb-item active">{{ $cafe->nama }}</li>
                    </ol>
                </nav>
                <button class="btn btn-outline-danger btn-sm btn-favorite px-3" onclick="toggleBookmark({{ $cafe->id }})">
                    <i class="far fa-heart me-1"></i> Save
                </button>
            </div>
            
            <div class="cafe-title-section mb-3">
                <h1 class="cafe-title mb-2">{{ $cafe->nama }}</h1>
                <div class="d-flex align-items-center mb-2">
                    <div class="location me-3">
                        <i class="fas fa-map-marker-alt text-danger me-1"></i>
                        <span class="text-muted">{{ $cafe->alamat }}</span>
                    </div>
                    
                    @if (!empty($avgRatings))
                        <div class="rating d-flex align-items-center">
                            <span class="text-muted me-2">Rating:</span>
                            <div class="stars">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $avgRatings)
                                        <i class="fas fa-star text-warning"></i>
                                    @elseif ($i - 0.5 <= $avgRatings)
                                        <i class="fas fa-star-half-alt text-warning"></i>
                                    @else
                                        <i class="far fa-star text-warning"></i>
                                    @endif
                                @endfor
                            </div>
                            <span class="ms-2 fw-bold">({{ number_format($avgRatings, 1) }})</span>
                        </div>
                    @else
                        <div class="rating">
                            <span class="badge bg-secondary">No reviews yet</span>
                        </div>
                    @endif
                </div>
                
                <a href="/menu/{{ $cafe->id }}" class="btn btn-primary btn-sm px-4 py-2">
                    <i class="fas fa-utensils me-2"></i>View Menu
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="cafe-main-content py-4">
        <div class="container">
            <div class="row">
                <!-- Main Column - Cafe Info -->
                <div class="col-md-8 pe-md-4">
                    <!-- Cafe Image -->
                    <div class="cafe-image-container mb-4">
                        <img src="{{ asset('image/' . $cafe->gambar) }}" alt="{{ $cafe->nama }}" class="img-fluid rounded cafe-main-image">
                    </div>

                    <!-- About Section -->
                    <div class="cafe-about mb-4">
                        <h2 class="section-title mb-3">About this cafe</h2>
                        <div class="cafe-description">
                            <p>{{ $cafe->content }}</p>
                        </div>
                    </div>
                    
                    <!-- Location Section -->
                    <div class="cafe-location mb-4">
                        <h2 class="section-title mb-3">
                            <i class="fas fa-map-marker-alt text-danger me-2"></i>Location
                        </h2>
                        <div class="map-container rounded">
                            <div id="map" class="cafe-map"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar - Reviews & Rating -->
                <div class="col-md-4">
                    <!-- Reviews Section -->
                    <div class="reviews-section mb-4">
                        <h2 class="section-title mb-3">
                            <i class="fas fa-comments me-2"></i>Customer Reviews
                            <span class="review-count">({{ count($reviews) }})</span>
                        </h2>
                        
                        <div class="reviews-container">
                            @forelse ($reviews as $item)
                                <div class="review-item">
                                    <div class="review-header d-flex justify-content-between align-items-start mb-2">
                                        <div class="reviewer-info d-flex">
                                            <div class="avatar">{{ substr($item->user->name, 0, 1) }}</div>
                                            <div>
                                                <div class="reviewer-name">{{ $item->user->name }}</div>
                                                <div class="review-date">
                                                    <?php
                                                    $created_at = new DateTime($item->created_at);
                                                    $now = new DateTime();
                                                    $interval = $now->diff($created_at);
                                                    echo $interval->days . ' days ago';
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="review-rating">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $item->rating)
                                                    <i class="fas fa-star text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-muted"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                    <div class="review-content">
                                        {{ $item->komentar }}
                                    </div>
                                    @if(Auth::check() && Auth::id() == $item->user_id)
                                        <div class="review-actions mt-2 text-end">
                                            <form action="{{ route('ratings.destroy', $item->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus review ini?')">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="no-reviews text-center py-4">
                                    <div class="icon mb-2">
                                        <i class="far fa-comment-dots"></i>
                                    </div>
                                    <p>No reviews yet. Be the first to review!</p>
                                </div>
                            @endforelse
                        </div>
                        
                        @if(count($reviews) > 3)
                            <div class="text-center mt-3">
                                <button class="btn btn-outline-primary btn-sm">View All Reviews</button>
                            </div>
                        @endif
                    </div>

                    <!-- Write Review Form -->
                    <div class="write-review-section">
                        <h2 class="section-title mb-3">Write a Review</h2>
                        @if(Auth::check())
                            @if(!$userHasReviewed)
                                <form method="POST" action="/ratings" name="ratingForm" id="formRating">
                                    @csrf
                                    <input type="hidden" name="cafe_id" value="{{ $cafe->id }}">
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Rating</label>
                                        <div class="rating-stars">
                                            <div class="rate">
                                                <input type="radio" id="star5" name="rate" value="5" required />
                                                <label for="star5" title="5 Stars">5 stars</label>
                                                <input type="radio" id="star4" name="rate" value="4" />
                                                <label for="star4" title="4 Stars">4 stars</label>
                                                <input type="radio" id="star3" name="rate" value="3" />
                                                <label for="star3" title="3 Stars">3 stars</label>
                                                <input type="radio" id="star2" name="rate" value="2" />
                                                <label for="star2" title="2 Stars">2 stars</label>
                                                <input type="radio" id="star1" name="rate" value="1" />
                                                <label for="star1" title="1 Stars">1 star</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label for="reviewText" class="form-label">Your Review</label>
                                        <textarea name="review" class="form-control" id="reviewText" rows="3" placeholder="Share your experience with this cafe" required></textarea>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary w-100">Submit Review</button>
                                </form>
                            @else
                                <div class="alert alert-info text-center">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Anda sudah memberikan review untuk kafe ini. Satu user hanya boleh memberikan satu review per kafe.
                                </div>
                            @endif
                        @else
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle me-2"></i>
                                Anda harus <a href="{{ route('login') }}" class="alert-link">login</a> terlebih dahulu untuk memberikan komentar dan rating.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Reset and Base Styles */
.cafe-detail-page {
    background-color: #f8f9fa;
    color: #343a40;
    font-family: 'Poppins', sans-serif;
}

/* Header Styles */
.cafe-header {
    background-color: white;
    border-bottom: 1px solid rgba(0,0,0,.05);
}

.cafe-title {
    font-size: 2rem;
    font-weight: 700;
    color: #343a40;
}

.breadcrumb-item a {
    color: #6c757d;
}

.breadcrumb-item.active {
    color: #343a40;
    font-weight: 500;
}

/* Main Content Styles */
.cafe-main-content {
    background-color: #f8f9fa;
}

.section-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #343a40;
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 0.5rem;
}

/* Image Styles */
.cafe-main-image {
    width: 100%;
    height: 400px;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,.1);
}

/* About Section */
.cafe-description {
    line-height: 1.6;
    color: #495057;
}

/* Map Styles */
.map-container {
    overflow: hidden;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,.1);
}

#map {
    height: 300px;
    width: 100%;
}

/* Reviews Section */
.reviews-section, .write-review-section {
    background: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,.1);
}

.review-count {
    font-size: 0.875rem;
    color: #6c757d;
    font-weight: normal;
}

.reviews-container {
    max-height: 350px;
    overflow-y: auto;
    padding-right: 10px;
}

.review-item {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
}

.reviewer-info {
    display: flex;
    align-items: center;
}

.avatar {
    width: 36px;
    height: 36px;
    background-color: #007bff;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-right: 10px;
}

.reviewer-name {
    font-weight: 600;
    font-size: 0.875rem;
}

.review-date {
    font-size: 0.75rem;
    color: #6c757d;
}

.review-content {
    font-size: 0.875rem;
    color: #495057;
    line-height: 1.5;
}

.no-reviews .icon {
    font-size: 2rem;
    color: #6c757d;
}

/* Rating Stars */
.rating-stars {
    display: flex;
    align-items: center;
}

.rate {
    float: left;
    height: 46px;
}

.rate:not(:checked) > input {
    position: absolute;
    top: -9999px;
}

.rate:not(:checked) > label {
    float: right;
    width: 1em;
    overflow: hidden;
    white-space: nowrap;
    cursor: pointer;
    font-size: 25px;
    color: #ccc;
}

.rate:not(:checked) > label:before {
    content: '★ ';
}

.rate > input:checked ~ label {
    color: #ffc700;    
}

.rate:not(:checked) > label:hover,
.rate:not(:checked) > label:hover ~ label {
    color: #deb217;  
}

.rate > input:checked + label:hover,
.rate > input:checked + label:hover ~ label,
.rate > input:checked ~ label:hover,
.rate > input:checked ~ label:hover ~ label,
.rate > label:hover ~ input:checked ~ label {
    color: #c59b08;
}

/* Scrollbar Styling */
.reviews-container::-webkit-scrollbar {
    width: 4px;
}

.reviews-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.reviews-container::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

.reviews-container::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Form Styles */
.form-control {
    border-radius: 6px;
    border: 1px solid #ced4da;
}

.form-control:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
}

/* Responsive Adjustments */
@media (max-width: 767.98px) {
    .cafe-title {
        font-size: 1.5rem;
    }
    
    .cafe-main-image {
        height: 250px;
    }
    
    #map {
        height: 200px;
    }
    
    .reviews-section, .write-review-section {
        margin-top: 2rem;
    }
}

@media (max-width: 575.98px) {
    .cafe-title-section {
        text-align: center;
    }
    
    .location, .rating {
        justify-content: center;
        margin-bottom: 0.5rem;
    }
    
    .cafe-main-image {
        height: 200px;
    }
}
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Favorite button toggle
        const favoriteBtn = document.querySelector('.btn-favorite');
        if (favoriteBtn) {
            favoriteBtn.addEventListener('click', function() {
                const icon = this.querySelector('i');
                if (icon.classList.contains('far')) {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                    this.classList.add('btn-danger');
                    this.classList.remove('btn-outline-danger');
                } else {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                    this.classList.remove('btn-danger');
                    this.classList.add('btn-outline-danger');
                }
            });
        }
        
        // Initialize the map with a slight delay to ensure the container is fully rendered
        setTimeout(initializeMap, 300);
    });
    
    function toggleBookmark(cafeId) {
        fetch('/favorite/' + cafeId, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                cafe_id: cafeId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status) {
                // Café ditambahkan ke favorit
                swal("Success", data.message, "success");
            } else {
                // Café dihapus dari favorit
                swal("Info", data.message, "info");
            }
        })
        .catch(error => console.error('Error:', error));
    }
    
    function initializeMap() {
        var latitude = {{ $cafe->latitude }};
        var longitude = {{ $cafe->longitude }};
        
        var leafletMap = L.map('map', {
            fullscreenControl: true,
            fullscreenControl: {
                pseudoFullscreen: false
            },
            minZoom: 2
        }).setView([latitude, longitude], 15);
        
        // Store map reference globally
        window.leafletMap = leafletMap;
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(leafletMap);
        
        var cafeIcon = L.icon({
            iconUrl: 'https://cdn-icons-png.flaticon.com/512/1047/1047356.png',
            iconSize: [28, 28],
            iconAnchor: [14, 28],
            popupAnchor: [0, -28]
        });
        
        L.marker([latitude, longitude], {icon: cafeIcon}).addTo(leafletMap)
            .bindPopup('<strong>{{ $cafe->nama }}</strong><br>{{ $cafe->alamat }}')
            .openPopup();
            
        // Ensure the map renders correctly
        leafletMap.invalidateSize();
    }
</script>
@endsection
