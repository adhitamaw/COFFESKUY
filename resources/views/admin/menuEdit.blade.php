@extends('admin.dashboard')

@section('judulAdmin')
    Halaman Edit Menu

@endsection

@section('contentAdmin')
<form action="/table-menu/{{$menu->id}}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="form-group">
      <label>Nama</label>
      <input type="text" name="nama" value="{{$menu->nama}}" class="form-control">
    </div>
    @error('nama')
        <div class="alert alert-danger">{{$message}}</div>
    @enderror
    <div class="form-group">
        <label>Harga</label>
        <input type="text" name="harga" value="{{$menu->harga}}" class="form-control">
    </div>
      @error('harga')
          <div class="alert alert-danger">{{$message}}</div>
      @enderror
    <div class="form-group">
        <label>Gambar</label>
        <input type="file" name="gambar" value="{{$menu->gambar}}" class="form-control">
    </div>
      @error('gambar')
          <div class="alert alert-danger">{{$message}}</div>
      @enderror
      <div class="form-group">
        <label>Cafe(s)</label>
    @forelse($cafes as $cafe)
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="cafe_id" value="{{ $cafe->id }}">
            <label class="form-check-label">{{ $cafe->nama }}</label>
        </div>
    @empty
        <p>No cafes available.</p>
    @endforelse
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
@endsection