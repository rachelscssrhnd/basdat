<!-- header_admin.blade.php -->
<header style="position:sticky;top:0;z-index:1000;background:#A31D1D;color:#FEF9E1;padding:12px 20px;box-shadow:0 4px 14px rgba(0,0,0,.12)">
  <div style="max-width:1200px;margin:0 auto;display:flex;align-items:center;justify-content:space-between;gap:16px;">
    <h1 style="margin:0;font-weight:700;letter-spacing:-.02em;font-size:20px;">Admin Panel</h1>
    <nav style="display:flex;gap:12px;align-items:center;">
      <a href="{{ route('admin.dashboard') }}" style="color:#FEF9E1;text-decoration:none;padding:8px 12px;border-radius:8px;">Dashboard</a>
      <a href="{{ route('admin.patients') }}" style="color:#FEF9E1;text-decoration:none;padding:8px 12px;border-radius:8px;">Patient Management</a>
      <a href="{{ route('admin.tests') }}" style="color:#FEF9E1;text-decoration:none;padding:8px 12px;border-radius:8px;">Test Management</a>
      <a href="{{ route('admin.booking') }}" style="color:#FEF9E1;text-decoration:none;padding:8px 12px;border-radius:8px;">Booking</a>
      <form method="POST" action="{{ route('logout') }}" style="display:inline;">
        @csrf
        <button type="submit" style="background:#FEF9E1;color:#A31D1D;border:none;border-radius:6px;padding:6px 10px;font-weight:bold;cursor:pointer;">Logout</button>
      </form>
    </nav>
  </div>
  </header>
