@extends('admin.dashboard')

@section('judulAdmin')
    Halaman Data User
@endsection

@section('contentAdmin')
<table class="table">
    <thead>
      <tr>
        <th scope="col">No</th>
        <th scope="col">Nama</th>
        <th scope="col">Email</th>
        <th scope="col">Created_at</th>
        <th scope="col">Role</th>
        <th scope="col">File</th>
        <th scope="col">Status</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
        @forelse ($users as $key=>$value)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{$value->name}}</td>
                <td>{{$value->email}}</td>
                <td>{{$value->created_at}}</td>
                <td>{{$value->role}}</td>
                <td><a href="{{asset('pdfs/' . $value->pdf_file)}}">{{$value->pdf_file}}</a></td>
                <td>{{$value->status}}</td>
                <td>
                    <form action="/table-user/{{$value->id}}/accept" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-info btn-sm">Accept</button>
                    </form>
                    <form action="/table-user/{{$value->id}}/reject" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-sm">Reject</button>
                    </form>
                    <form action="" method="POST" style="display: inline;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                  </form>
              </td>
            </tr>
        @empty
            <p>No cafe</p>
        @endforelse
    </tbody>
  </table>
@endsection
