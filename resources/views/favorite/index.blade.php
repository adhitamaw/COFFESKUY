@extends('layout.master')

@section('content')
    <title>CoffeSkuy</title>
    <div class="cardhome">

        <form class="form-inline my-3" action="/cafes/search" method="GET">
            @csrf
            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="query">
        </form>
        <div class="container">
            <div class="row">
                @forelse ($favorites as $favorite)
                    @if ($favorite->cafe)
                        <div class="col-md-4 my-3">
                            <div class="card">
                                <img src="{{ asset('image/' . $favorite->cafe->gambar) }}" class="card-img-top"
                                    alt="...">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $favorite->cafe->nama }}</h5>
                                    <p class="card-text">{{ $favorite->cafe->alamat }}</p>
                                    @if (!empty($favorite->averageRating))
                                        <?php
                                $count = 1;
                                while($count <=  $favorite->averageRating ){?>
                                        <span>&#9733;</span>
                                        <?php $count++;} ?>
                                        ({{ number_format($favorite->averageRating, 1) }})
                                    @else
                                        <p>No reviews yet</p>
                                    @endif
                                    <div>&nbsp;</div>
                                    <div class="card-wrapper">
                                        <a href="/cafe/{{ $favorite->cafe->id }}" class="btn btn-primary">Detail</a>
                                        <i class="fa-regular fa-bookmark"
                                            onclick="toggleBookmark({{ $favorite->cafe->id }})" style="cursor:pointer;"></i>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @else
                        <p>gak ada</p>
                    @endif
                @empty
                    <p>No cafes found</p>
                @endforelse
            </div>




        </div>
    </div>
    <script>
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
                        swal("Success", data.message, "success");
                        
                    } else {
                        swal("Success", data.message, "info");
                        location.reload();
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
@endsection
