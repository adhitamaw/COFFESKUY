@extends('layout.master')

@section('content')

    <div class="container">
        <div class="row">
            @php $total = 0 @endphp
            @if (session('cart'))
                @foreach (session('cart') as $id => $details)
                    @php $total += $details['price'] * $details['quantity'] @endphp
                    <div class="col-md-4 my-3">
                        <div class="card" data-id="{{ $id }}">
                            <img src="{{ asset('image') }}/{{ $details['photo'] }}" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">{{ $details['menu_name'] }}</h5>
                                <p class="card-text">Rp. {{ number_format($details['price'], 0, ',', '.') }}</p>
                                <input type="number" value="{{ $details['quantity'] }}"
                                    class="form-control quantity cart_update" min="1" readonly data-id="{{ $id }}" />
                                <p>Subtotal</p>
                                <p>Rp. {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</p>
                                <div>&nbsp;</div>
                                <form id="deleteForm{{ $id }}" action="{{ route('remove_from_cart', $id) }}"
                                    method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmDelete('{{ $title }}', '{{ $text }}', '{{ $id }}')">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <tfoot>
            <tr>
                <td colspan="5" style="text-align:right;">
                    <h3><strong>Total Rp. {{ number_format($total, 0, ',', '.') }}</strong></h3>
                </td>
            </tr>
            <tr>
                <td colspan="5" style="text-align:right;">
                    <form action="/session" method="POST">
                        <a href="/cafe" class="btn btn-danger"> <i class="fa fa-arrow-left"></i> Continue Shopping</a>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button class="btn btn-success" type="submit" id="checkout-live-button"><i class="fa fa-money"></i>
                            Checkout</button>
                    </form>
                </td>
            </tr>
        </tfoot>
    </div>



@endsection

@section('scripts')
    <style>
        /* Menghilangkan tombol spin (panah atas bawah) pada input number */
        input[type="number"].quantity {
            -webkit-appearance: textfield;
            -moz-appearance: textfield;
            appearance: textfield;
            background-color: #f8f9fa;
            cursor: default;
        }
        
        /* Khusus untuk Chrome, Safari, Edge, Opera */
        input[type="number"].quantity::-webkit-inner-spin-button,
        input[type="number"].quantity::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        
        /* Firefox */
        input[type="number"].quantity {
            -moz-appearance: textfield;
        }
    </style>
    <script type="text/javascript">
        $(".cart_update").change(function(e) {
            e.preventDefault();

            var ele = $(this);
            var id = ele.parents(".card").attr("data-id");

            $.ajax({
                url: '{{ route('update_cart') }}',
                method: "patch",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    quantity: ele.val()
                },
                success: function(response) {
                    window.location.reload();
                }
            });
        });

        function confirmDelete(title, text, id) {
            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`deleteForm${id}`).submit();
                }
            });
        }
    </script>
@endsection
