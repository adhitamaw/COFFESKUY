@extends('admin.dashboard')

@section('judulAdmin')
    Halaman Data Cafe

@endsection

@section('contentAdmin')
@if(Auth::user()->role == 'admin')
    <a href="/cafe/create" class="btn btn-primary btn-sm">Tambah</a>
@endif
<table class="table">
    <thead>
      <tr>
        <th scope="col">No</th>
        <th scope="col">Nama</th>
        <th scope="col">Alamat</th>
        <th scope="col">Content</th>
        <th scope="col">Gambar</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
        @forelse ($cafe as $key=>$value)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{$value->nama}}</td>
                <td>{{$value->alamat}}</td>
                <td>{{$value->content}}</td>
                <td>{{$value->gambar}}
                </td>
                <td><a href="/table-cafe/{{$value->id}}" class="btn btn-info btn-sm">Detail</a>
                    <a href="/table-cafe/{{$value->id}}/edit" class="btn btn-warning btn-sm">Edit</a>
                    
                    <form id="deleteForm{{ $value->id }}" action="{{ route('cafe.destroy', $value->id) }}" method="POST" style="display: inline;">
                      @csrf
                      @method('DELETE')
                      <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('{{ $title }}', '{{ $text }}', '{{ $value->id }}')">Delete</button>
                  </form>
              </td>
            </tr>
        @empty
            <p>No cafe</p>
        @endforelse

       
    </tbody>
  </table>
@endsection

<script>{
    function confirmDelete(title, text, cafeId) {
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
                // If the user confirms, submit the corresponding form for deletion
                document.getElementById(`deleteForm${cafeId}`).submit();
            }
        });
    }
}
</script>