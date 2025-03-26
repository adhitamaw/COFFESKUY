@extends('admin.dashboard')

@section('judulAdmin')
    Halaman Detail Cafe

@endsection

@section('contentAdmin')
<form action="/table-cafe/{{$cafe->id}}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="form-group">
      <label>Nama</label>
      <input type="text" name="nama" value="{{$cafe->nama}}" class="form-control">
    </div>
    @error('nama')
        <div class="alert alert-danger">{{$message}}</div>
    @enderror
    <div class="form-group">
        <label>Alamat</label>
        <input type="text" name="alamat" value="{{$cafe->alamat}}" class="form-control">
    </div>
      @error('alamat')
          <div class="alert alert-danger">{{$message}}</div>
      @enderror
    <div class="form-group">
        <label>Gambar</label>
        <input type="file" name="gambar" value="{{$cafe->gambar}}" class="form-control">
    </div>
      @error('gambar')
          <div class="alert alert-danger">{{$message}}</div>
      @enderror
    <div class="form-group">
        <label>Deskripsi</label>
        <input type="content" name="content" value="{{$cafe->content}}"class="form-control">
    </div>
      @error('content')
          <div class="alert alert-danger">{{$message}}</div>
      @enderror
    <button type="submit" class="btn btn-primary">Submit</button>
</form>


@endsection

