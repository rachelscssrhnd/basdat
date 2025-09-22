<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Basdat Lab' }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        :root { --maroon:#A31D1D; --maroon-dark:#8C1717; --accent:#FFD3B6; --bg:#FFF7ED; }
        body { margin:0; font-family: Arial, Helvetica, sans-serif; background: var(--bg); color:#1b1b18; }
        .navbar { position: sticky; top:0; z-index:999; background: var(--maroon); color:#fff; border-bottom: 1px solid rgba(0,0,0,.06); box-shadow: 0 4px 14px rgba(0,0,0,.08); }
        .nav-wrap { max-width: 1200px; margin: 0 auto; display:flex; align-items:center; justify-content:space-between; padding: 14px 20px; }
        .brand { font-weight: 700; letter-spacing:.3px; }
        .nav { display:flex; gap: 16px; align-items:center; }
        .nav a, .nav button { color:#fff; text-decoration:none; padding:8px 12px; border-radius:10px; background: transparent; border: 1px solid transparent; cursor:pointer; transition: all .2s ease; }
        .nav a:hover, .nav button:hover { background: var(--maroon-dark); box-shadow: 0 2px 8px rgba(0,0,0,.12); }
        .container { max-width: 1200px; margin: 24px auto; padding: 0 16px; }
        .card { background:#fff; border:1px solid #eee; border-radius:12px; padding:18px; box-shadow:0 6px 18px rgba(0,0,0,.05); }
        .grid { display:grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap:18px; }
        .muted { color:#6b7280; font-size:14px; }
    </style>
    @csrf
</head>
<body>
    <header class="navbar">
        <div class="nav-wrap">
            <div class="brand">Basdat Klinik</div>
            <nav class="nav">
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('booking.index') }}">Booking</a>
                <a href="{{ route('hasil.index') }}">Test Result</a>
                <a href="{{ route('faq') }}">FAQ</a>
                @if (session('role') === 'admin')
                    <a href="{{ route('home.admin') }}">Admin Panel</a>
                @endif
                @if (session()->has('user_id'))
                    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Login</a>
                @endif
            </nav>
        </div>
    </header>

    <main class="container">
        @yield('content')
    </main>
    
</body>
</html>


