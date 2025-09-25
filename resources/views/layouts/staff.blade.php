<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OkGreen Staff - @yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
      /* staff visual different from admin & user */
      .topnav { background:#156d4a; padding:10px; color:#fff; display:flex; gap:12px; align-items:center; }
      .topnav a { color:#fff; text-decoration:none; margin-right:10px; padding:6px 8px; border-radius:6px; font-weight:600; }
      .topnav a:hover { background: rgba(255,255,255,0.06); }
      .btn-logout { background:#b03a2e; color:#fff; border:none; padding:6px 10px; border-radius:6px; cursor:pointer; }
      .container { padding:20px; max-width:1100px; margin:0 auto; }
      .muted { color: rgba(255,255,255,0.9); font-weight:600; }
    </style>
</head>
<body>
    <nav class="topnav">
        <a href="{{ route('staff.dashboard') }}">Dashboard</a>

        @if(Route::has('staff.wastes.index'))
          <a href="{{ route('staff.wastes.index') }}">Kelola Sampah</a>
        @endif

        @if(Route::has('staff.sell_requests.index'))
          <a href="{{ route('staff.sell_requests.index') }}">Permintaan Jual</a>
        @endif

        <a href="{{ route('staff.notifications.index') }}">Notifikasi</a>

        <a href="{{ route('staff.contacts.index') }}">Laporan</a>

        <div style="flex:1"></div>

        <span class="muted">
          {{ Auth::guard('staff')->check() ? Auth::guard('staff')->user()->name : 'Staff' }}
        </span>

        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="btn-logout" onclick="return confirm('Logout?')">Logout</button>
        </form>
    </nav>

    <div class="container">
        @if(session('success')) <div style="background:#eaf7f1;padding:10px;border-left:4px solid #156d4a;margin-bottom:12px;">{{ session('success') }}</div> @endif
        @if(session('error')) <div style="background:#fff5f5;padding:10px;border-left:4px solid #b03a2e;margin-bottom:12px;">{{ session('error') }}</div> @endif

        @yield('content')
    </div>
</body>
</html>
