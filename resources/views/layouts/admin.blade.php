<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OkGreen Admin - @yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/beranda-admin.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .topnav { background:#0b5d39; padding:10px; color:#fff; display:flex; gap:12px; align-items:center; }
        .topnav a { color:#fff; text-decoration:none; margin-right:10px; padding:6px 8px; border-radius:6px; font-weight:600; }
        .topnav a:hover { background: rgba(255,255,255,0.06); }
        .btn-logout { background:#c0392b; color:#fff; border:none; padding:6px 10px; border-radius:6px; cursor:pointer; }
        .container { padding:20px; max-width:1100px; margin:0 auto; }
        .muted { color: rgba(255,255,255,0.85); font-weight:600; }
    </style>
</head>
<body>
    <nav class="topnav">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        @if(Route::has('admin.wastes.index'))
            <a href="{{ route('admin.wastes.index') }}">Kelola Sampah</a>
        @endif
        @if(Route::has('admin.users.index'))
            <a href="{{ route('admin.users.index') }}">Kelola Staff</a>
        @endif
        <div style="flex:1"></div>
        <span class="muted">{{ Auth::guard('admin')->check() ? Auth::guard('admin')->user()->name : 'Admin' }}</span>
        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="btn-logout" onclick="return confirm('Logout?')">Logout</button>
        </form>
    </nav>

    <div class="main-container">
        @if(session('success'))
            <div style="background:#e9f9ef;padding:10px;border-left:4px solid #0b5d39;margin-bottom:12px;">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div style="background:#fff0f0;padding:10px;border-left:4px solid #c0392b;margin-bottom:12px;">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>

    @stack('scripts')
    <script src="{{ asset('js/statistik.js') }}"></script>
</body>
</html>
