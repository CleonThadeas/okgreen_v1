<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tukar Voucher</title>
    <title>Voucher</title>
    <link rel="stylesheet" href="{{ asset('css/tukarvoucher.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <body class="dashboard-page">
    @include('partials.header')

   <div class="container">
            <button class="back-btn"><a href="{{ route('berandavoucher') }}">←</a></button>
    </div>

<div class="container">
    <div class="voucher-card">
  <div class="voucher-detail">
    <img src="https://upload.wikimedia.org/wikipedia/commons/1/19/Spotify_logo_without_text.svg" alt="Spotify" class="card-logo">
    <h2 id="reward-title">Spotify Premium - 1 Bulan</h2>
    <p class="points">3000 Points</p>
  </div>

  <div class="voucher-info">
    <p><i class="fa fa-coins"></i> Points kamu: <b>3500</b></p>
    <p><i class="fa fa-calendar"></i> Berlaku hingga: <b>30 September 2025</b></p>
  </div>

  <button class="confirm-btn">KLAIM</button>
  <p class="note">Pastikan kamu memiliki cukup poin untuk klaim voucher ini. Setelah klaim, voucher akan tersimpan di akunmu.</p>
</div>
</body>
</html>