@extends('layout.master')

@section('content')
<!DOCTYPE html>
<html lang="en">

<head>
    <title>CoffeSkuy</title>
    {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script> --}}
    {{-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> --}}
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Register</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>
                                <div class="col-md-6">
                                    <i class="fa fa-user icon"></i>
                                    <input type="text" class="form-control" id="name" name="name" required autofocus>
                                </div>
                            </div>

                            @error('name')
                                <div class="alert alert-danger">{{$message}}</div>
                            @enderror

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>
                                <div class="col-md-6">
                                    <i class="fa fa-envelope icon"></i>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            </div>
                            @error('email')
                                <div class="alert alert-danger">{{$message}}</div>
                            @enderror

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                                <div class="col-md-6">
                                    <i class="fa fa-lock icon"></i>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                            </div>
                            @error('password')
                                <div class="alert alert-danger">{{$message}}</div>
                            @enderror

                            <div class="form-group row">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm Password</label>
                                <div class="col-md-6">
                                    <i class="fa fa-lock icon"></i>
                                    <input type="password" class="form-control" id="password-confirm" name="password_confirmation" required>
                                </div>
                            </div>
                            @error('password-confirm')
                                <div class="alert alert-danger">{{$message}}</div>
                            @enderror

                            <div class="form-group row">
                                <label for="role" class="col-md-4 col-form-label text-md-right">Role</label>
                                <div class="col-md-6">
                                    <select id="role" class="form-control" name="role" onchange="togglePdfForm()">
                                        <option value="user">User</option>
                                        <option value="penjual">Penjual</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row" id="pdfForm" style="display: none;">
                                <div class="row">
                                    <div class="col-md-2" style="margin-left: 8rem;">
                                        <label for="pdf" class="col-form-label text-md-right">Upload PDF</label>
                                    </div>
                                    <div class="col-md-4 " >
                                        <input type="file" id="pdf" name="pdf" accept=".pdf">
                                    </div>
                                </div>
                            </div>

                            @error('pdf')
                                <div class="alert alert-danger">{{$message}}</div>
                            @enderror

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary btn-submit">Register</button>
                                    <a  href="/login">Login</a>
                                </div>
                                
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePdfForm() {
            var roleDropdown = document.getElementById('role');
            var pdfForm = document.getElementById('pdfForm');

            // If the selected role is 'admin', show the PDF form, otherwise hide it
            pdfForm.style.display = roleDropdown.value === 'admin' ? 'block' : 'none';
        }
    </script> 
    

</body>

</html>
@endsection