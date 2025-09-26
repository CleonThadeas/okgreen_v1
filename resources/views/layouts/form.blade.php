<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OkGreen Staff - @yield('title')</title>
     <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    @stack('styles')
</head>
<body>
    <div class="container">
        @if(session('success'))
            <div style="background:#eaf7f1;padding:10px;border-left:4px solid #156d4a;margin-bottom:12px;">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div style="background:#fff5f5;padding:10px;border-left:4px solid #b03a2e;margin-bottom:12px;">{{ session('error') }}</div>
        @endif

        @yield('content')
    </div>
</body>
</html>
