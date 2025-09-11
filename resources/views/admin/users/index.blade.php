@extends('layouts.admin')

@section('title','Kelola Staff')

@section('content')
<div class="card">
  <h2>Kelola Staff</h2>

  <p><a href="{{ route('admin.users.create') }}" class="btn">+ Tambah Staff</a></p>

  @if(session('success')) <div style="color:green">{{ session('success') }}</div> @endif
  @if(session('error')) <div style="color:red">{{ session('error') }}</div> @endif

  <table border="1" cellpadding="8" width="100%">
    <thead>
      <tr><th>#</th><th>Nama</th><th>Email</th><th>Dibuat</th><th>Aksi</th></tr>
    </thead>
    <tbody>
    @forelse($staffs as $s)
      <tr>
        <td>{{ $loop->iteration + ($staffs->currentPage()-1)*$staffs->perPage() }}</td>
        <td>{{ $s->name }}</td>
        <td>{{ $s->email }}</td>
        <td>{{ $s->created_at }}</td>
        <td>
          <a href="{{ route('admin.users.edit', $s->id) }}">Edit</a>
          <form action="{{ route('admin.users.destroy', $s->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus akun ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" style="background:none;border:none;color:#c00;cursor:pointer">Hapus</button>
          </form>
        </td>
      </tr>
    @empty
      <tr><td colspan="5">Belum ada staff.</td></tr>
    @endforelse
    </tbody>
  </table>

  <div style="margin-top:10px;">
    {{ $staffs->links() }}
  </div>
</div>
@endsection
