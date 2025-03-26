@extends('admin.dashboard')

@section('judulAdmin')
    Detail Cafe: {{ $cafe->nama }}
@endsection

@section('contentAdmin')
<div class="row">
    <!-- Informasi utama cafe -->
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0"><i class="fas fa-coffee mr-2"></i>Informasi Cafe</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Informasi cafe -->
                    <div class="col-md-8">
                        <table class="table table-hover">
                            <tr>
                                <th style="width: 150px">Nama Cafe</th>
                                <td><strong>{{ $cafe->nama }}</strong></td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td>{{ $cafe->alamat }}</td>
                            </tr>
                            <tr>
                                <th>Deskripsi</th>
                                <td>
                                    <div style="max-height: 200px; overflow-y: auto;">
                                        {{ $cafe->content }}
                                    </div>
                                </td>
                            </tr>
                        </table>
                        
                        <div class="mt-3">
                            <a href="{{ route('cafe.edit', $cafe->id) }}" class="btn btn-warning">
                                <i class="fas fa-edit mr-1"></i> Edit Cafe
                            </a>
                            <a href="{{ url('/table-cafe') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </a>
                        </div>
                    </div>
                    
                    <!-- Gambar cafe -->
                    <div class="col-md-4 text-center">
                        @if($cafe->gambar)
                            <div class="mb-2">
                                <img src="{{ asset('image/' . $cafe->gambar) }}" alt="{{ $cafe->nama }}" 
                                    class="img-fluid rounded shadow-sm" style="max-height: 250px; object-fit: cover;">
                            </div>
                            <a href="{{ asset('image/' . $cafe->gambar) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-search-plus mr-1"></i> Lihat Gambar Penuh
                            </a>
                        @else
                            <div class="alert alert-secondary">
                                <i class="fas fa-image"></i> Tidak ada gambar
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Ulasan cafe -->
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-comments mr-2"></i>Ulasan dan Rating
                    <span class="badge badge-light ml-2">{{ count($reviews) }}</span>
                </h5>
            </div>
            <div class="card-body">
                @if(count($reviews) > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" style="width: 5%">No.</th>
                                    <th scope="col" style="width: 40%">Komentar</th>
                                    <th scope="col" style="width: 15%">Rating</th>
                                    <th scope="col" style="width: 15%">Tanggal</th>
                                    <th scope="col" style="width: 10%">User ID</th>
                                    <th scope="col" style="width: 15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reviews as $key => $value)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <div style="max-height: 100px; overflow-y: auto;">
                                                {{ $value->komentar }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-warning">
                                                @for($i = 0; $i < $value->rating; $i++)
                                                    <i class="fas fa-star"></i>
                                                @endfor
                                                @for($i = $value->rating; $i < 5; $i++)
                                                    <i class="far fa-star"></i>
                                                @endfor
                                            </div>
                                        </td>
                                        <td>{{ $value->created_at->format('d M Y H:i') }}</td>
                                        <td>{{ $value->user_id }}</td>
                                        <td>
                                            <form action="{{ route('ratings.destroy', $value->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus review ini?')">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-light text-center">
                        <i class="far fa-comment-dots fa-2x mb-2"></i>
                        <p>Belum ada ulasan untuk cafe ini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection