@extends('layouts.staff')

@section('title','Dashboard Staff')

@section('content')
<div class="card">
  <h2>Dashboard Staff</h2>
  <p class="muted">Quick links</p>
  <!-- Iframe untuk menampilkan halaman -->
  <iframe id="dashboard-iframe" src="{{ route('staff.wastes.index') }}" style="width:100%; height:600px; border:1px solid #ccc; border-radius:8px;"></iframe>
</div>

<script>
    function loadIframe(url) {
        document.getElementById('dashboard-iframe').src = url;
    }
</script>
@endsection
