@extends('admin.dashboard')

@section('judulAdmin')
    Halaman Data Menu
@endsection

@section('contentAdmin')
<a href="/menu/create" class="btn btn-primary btn-sm">Tambah</a>
<table class="table">
    <thead>
      <tr>
        <th scope="col">No</th>
        <th scope="col">Nama</th>
        <th scope="col">harga</th>
        <th scope="col">gambar</th>
        <th scope="col">created_at</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
      
        @forelse ($menu as $key=>$value)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{$value->nama}}</td>
                <td>{{$value->harga}}</td>
                <td>{{$value->gambar}}</td>
                <td>{{$value->created_at}}
                </td>
                <td>
                    <a href="/table-menu/{{$value->id}}/edit" class="btn btn-warning btn-sm">Edit</a>
                    <form id="deleteForm{{ $value->id }}" action="{{ route('menu.destroy', $value->id) }}" method="POST" style="display: inline;">
                      @csrf
                      @method('DELETE')
                      <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('{{ $title }}', '{{ $text }}', '{{ $value->id }}')">Delete</button>
                  </form>
              </td>
            </tr>
        @empty
            <p>No Menu</p>
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

