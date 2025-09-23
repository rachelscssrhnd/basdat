<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'E-Clinic Lab' }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Open+Sans:wght@300;400;500;600;700&display=swap');
        
        :root { 
            --maroon:#A31D1D; 
            --maroon-dark:#8C1717; 
            --accent:#FFD3B6; 
            --bg:#FFF7ED; 
        }
        
        body { 
            margin:0; 
            font-family: 'Open Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; 
            background: var(--bg); 
            color:#1b1b18; 
            line-height: 1.6;
            font-weight: 400;
        }
        
        .navbar { 
            position: sticky; 
            top:0; 
            z-index:1000; 
            background: var(--maroon); 
            color:#fff; 
            border-bottom: 1px solid rgba(0,0,0,.06); 
            box-shadow: 0 4px 20px rgba(163, 29, 29, 0.15);
            backdrop-filter: blur(10px);
        }
        
        .nav-wrap { 
            max-width: 1200px; 
            margin: 0 auto; 
            display:flex; 
            align-items:center; 
            justify-content:space-between; 
            padding: 16px 20px; 
        }
        
        .brand { 
            font-family: 'Poppins', sans-serif;
            font-weight: 700; 
            letter-spacing:-0.02em; 
            font-size: 1.4rem;
        }
        
        .nav { 
            display:flex; 
            gap: 8px; 
            align-items:center; 
        }
        
        .nav a, .nav button { 
            color:#fff; 
            text-decoration:none; 
            padding:10px 16px; 
            border-radius:12px; 
            background: transparent; 
            border: 1px solid transparent; 
            cursor:pointer; 
            transition: all .3s ease; 
            font-family: 'Open Sans', sans-serif;
            font-weight: 500;
            font-size: 0.95rem;
        }
        
        .nav a:hover, .nav button:hover { 
            background: var(--maroon-dark); 
            box-shadow: 0 4px 12px rgba(0,0,0,.15); 
            transform: translateY(-1px);
        }
        
        .container { 
            max-width: 1200px; 
            margin: 24px auto; 
            padding: 0 16px; 
        }
        
        .card { 
            background:#fff; 
            border:1px solid #eee; 
            border-radius:16px; 
            padding:24px; 
            box-shadow:0 8px 24px rgba(0,0,0,.08); 
        }
        
        .grid { 
            display:grid; 
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); 
            gap:24px; 
        }
        
        .muted { 
            color:#6b7280; 
            font-size:14px; 
        }
    </style>
    @csrf
</head>
<body>
    <header class="navbar">
        <div class="nav-wrap">
            <div class="brand">E-Clinic Lab</div>
            <nav class="nav">
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('booking.index') }}">Booking</a>
                <a href="{{ route('hasil.index') }}">Test Result</a>
                <a href="{{ route('about') }}">About</a>
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


