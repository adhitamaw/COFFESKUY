<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>CoffeSkuy Admin</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('/template/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('/template/dist/css/adminlte.min.css') }}">
  <!-- Leaflet Maps -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
    integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
    crossorigin="" />
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
    integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
    crossorigin=""></script>
  <link rel="stylesheet" href="https://unpkg.com/leaflet-geosearch@3.0.0/dist/geosearch.css" />
  <script src="https://unpkg.com/leaflet-geosearch@3.1.0/dist/geosearch.umd.js"></script>
  <!-- Font Awesome Kit -->
  <script src="https://kit.fontawesome.com/804c519240.js" crossorigin="anonymous"></script>
  <style>
    .main-sidebar {
      background-color: #343a40;
      color: #ffffff;
    }
    .nav-sidebar .nav-link {
      color: #c2c7d0;
    }
    .nav-sidebar .nav-link:hover {
      color: #ffffff;
    }
    .nav-sidebar .nav-link.active {
      background-color: #007bff;
      color: #ffffff;
    }
    .content-wrapper {
      background-color: #f4f6f9;
    }
    .card {
      box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    }
  </style>
</head>
<body class="hold-transition sidebar-mini">
  @include('sweetalert::alert')
  
  <!-- Site wrapper -->
  <div class="wrapper">
    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="/" class="brand-link text-center">
        <span class="brand-text font-weight-bold">CoffeSkuy Admin</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <i class="fas fa-user-circle fa-2x text-light mr-2"></i>
          </div>
          <div class="info">
            <a href="#" class="d-block">{{ Auth::user()->name }}</a>
            <small class="text-muted">{{ Auth::user()->role }}</small>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
              <a href="/" class="nav-link">
                <i class="nav-icon fa-solid fa-home"></i>
                <p>Kembali ke Home</p>
              </a>
            </li>    
            <li class="nav-item">
              <a href="/admin" class="nav-link {{ Request::is('admin') ? 'active' : '' }}">
                <i class="nav-icon fa-solid fa-dashboard"></i>
                <p>Dashboard</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/table-cafe" class="nav-link {{ Request::is('table-cafe*') ? 'active' : '' }}">
                <i class="nav-icon fa-solid fa-coffee"></i>
                <p>Kelola Cafe</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/table-menu" class="nav-link {{ Request::is('table-menu*') ? 'active' : '' }}">
                <i class="nav-icon fa-solid fa-utensils"></i>
                <p>Kelola Menu</p>
              </a>
            </li>
            @if(Auth::user()->role == 'admin')
              <li class="nav-item">
                <a href="/table-user" class="nav-link {{ Request::is('table-user*') ? 'active' : '' }}">
                  <i class="nav-icon fa-solid fa-users"></i>
                  <p>Kelola User</p>
                </a>
              </li>
            @endif
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Navbar -->
      <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
          </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
              <i class="fas fa-expand-arrows-alt"></i>
            </a>
          </li>
          <li class="nav-item">
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
              @csrf
              <button type="submit" class="btn btn-link nav-link">
                <i class="fas fa-sign-out-alt"></i> Logout
              </button>
            </form>
          </li>
        </ul>
      </nav>
      <!-- /.navbar -->
      
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>@yield("judulAdmin", "Dashboard Admin")</h1>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        <!-- Default box -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">@yield('judulAdmin', 'Selamat Datang di Panel Admin')</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            @yield('contentAdmin')
            @if(!View::hasSection('contentAdmin'))
              <div class="row">
                <div class="col-lg-4 col-md-6">
                  <div class="small-box bg-info">
                    <div class="inner">
                      <h3>Cafe</h3>
                      <p>Kelola daftar cafe</p>
                    </div>
                    <div class="icon">
                      <i class="fas fa-coffee"></i>
                    </div>
                    <a href="/table-cafe" class="small-box-footer">Lihat <i class="fas fa-arrow-circle-right"></i></a>
                  </div>
                </div>
                <div class="col-lg-4 col-md-6">
                  <div class="small-box bg-success">
                    <div class="inner">
                      <h3>Menu</h3>
                      <p>Kelola menu cafe</p>
                    </div>
                    <div class="icon">
                      <i class="fas fa-utensils"></i>
                    </div>
                    <a href="/table-menu" class="small-box-footer">Lihat <i class="fas fa-arrow-circle-right"></i></a>
                  </div>
                </div>
                @if(Auth::user()->role == 'admin')
                  <div class="col-lg-4 col-md-6">
                    <div class="small-box bg-warning">
                      <div class="inner">
                        <h3>User</h3>
                        <p>Kelola pengguna</p>
                      </div>
                      <div class="icon">
                        <i class="fas fa-users"></i>
                      </div>
                      <a href="/table-user" class="small-box-footer">Lihat <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                  </div>
                @endif
              </div>
            @endif
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
      <div class="float-right d-none d-sm-block">
        <b>Version</b> 1.0
      </div>
      <strong>Copyright &copy; {{ date('Y') }} <a href="/">CoffeSkuy</a>.</strong> All rights reserved.
    </footer>
  </div>
  <!-- ./wrapper -->

  <!-- jQuery -->
  <script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>
  <!-- Bootstrap 4 -->
  <script src="{{ asset('template/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <!-- AdminLTE App -->
  <script src="{{ asset('template/dist/js/adminlte.min.js') }}"></script>
</body>
</html>
