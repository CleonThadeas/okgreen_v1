@extends('layouts.admin')

@section('title','Dashboard Admin')

@section('content')
<div class="container">
    <div class="header">
      <h2>Beranda Admin</h2>
      <input type="text" placeholder="Cari">
    </div>

    <div class="dashboard-cards">
      <div class="card">
        <div class="card-header">
          <h3>Pengguna</h3>
          <span class="icon purple">📅</span>
        </div>
        <h2><a href="{{ route('detailpengguna') }}">20</a></h2>
        <p><span class="text-bold">14</span> Aktif</p>
      </div>
      <div class="card">
        <div class="card-header">
          <h3>Banyak Sampah</h3>
          <span class="icon blue">📋</span>
        </div>
        <h2><a href="{{ route('stoksampah') }}">132</a></h2>
        <p><span class="text-bold">32</span> Belum Selesai</p>
      </div>
      <div class="card">
        <div class="card-header">
          <h3>Admin</h3>
          <span class="icon red">👥</span>
        </div>
        <h2><a href="{{ route('detailadmin') }}">10</a></h2>
        <p><span class="text-bold">2</span> Aktif</p>
      </div>
      <div class="card">
        <div class="card-header">
          <h3>Status</h3>
          <span class="icon green">♻️</span>
        </div>
         <h2><a href="{{ route('banyaksampah') }}">75%</a></h2>
        <p><span class="text-green">25%</span> Belum Selesai</p>
      </div>
    </div>

    <div class="header-admin">
      <h2>Admin</h2>
      <a href="{{ route('detailadmin') }}" class="btn-tambah">Lihat Admin</a>
    </div>

    <table>
      <thead>
        <tr>
          <th>Nama</th>
          <th>Gmail</th>
          <th>Aktivitas Terakhir</th>
          <th>Role</th>
          <th>Edit</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Jiman</td>
          <td>Morthelp@example.com</td>
          <td>Aktif</td>
          <td>Admin</td>
          <td><button class="delete">Hapus</button></td>
        </tr>
        <tr>
          <td>Jian</td>
          <td>Morthelp@example.com</td>
          <td>Aktif</td>
          <td>Admin</td>
          <td><button class="delete">Hapus</button></td>
        </tr>
      </tbody>
    </table>

    <div class="statistik-card">
      <h2>Statistik</h2>
      <div class="stats-container">
        <div class="chart-container">
          {{-- Canvas bawah --}}
          <canvas id="progressChartCard" width="200" height="200"></canvas>
        </div>
        <div class="legend">
          <div class="legend-item">
            <i class="done">✔</i>
            <div class="percent">75%</div>
            <div>Selesai</div>
          </div>
          <div class="legend-item">
            <i class="progress">↗</i>
            <div class="percent">25%</div>
            <div>Sedang Berlangsung</div>
          </div>
        </div>
      </div>
    </div>
</div>

{{-- Include JS --}}
@push('scripts')
<script src="{{ asset('js/statistik.js') }}"></script>
@endpush
@endsection
