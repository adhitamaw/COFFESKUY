@extends('layout.master')

@section('content')
    <div class="container">
        <div class='row'>
            <div class='col-md-12'>
                <div class="card">
                    <div class="card-header">
                        Cancel
                    </div>
                    <div class="card-body">
                        <a href="{{ route('cart') }}" class="btn btn-info"> <i class="fa fa-arrow-left"></i> Continue
                            Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
@endsection
