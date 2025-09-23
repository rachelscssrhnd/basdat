@extends('layouts.admin')

@section('content')
  <style>
    .container { padding:0; animation: fadeIn 0.6s ease; }
    h3 {
      font-size: 1.8rem; color: #A31D1D; margin-bottom: 20px;
      position: relative; display:inline-block;
    }
    h3::after {
      content:""; position:absolute; bottom:-6px; left:0;
      width:50%; height:3px; background:#FFD3B6; border-radius:3px;
      animation: growLine 1s ease forwards;
    }
    .search-box { margin-bottom:15px; }
    .search-box input {
      width: 100%; padding: 10px 15px; font-size: 1rem;
      border: 2px solid #ddd; border-radius: 10px;
      transition: all 0.3s ease;
    }
    .search-box input:focus {
      border-color: #A31D1D; outline:none;
      box-shadow: 0 0 8px rgba(163,29,29,0.3);
    }
    table {
      width:100%; border-collapse:collapse; background:#fff;
      border-radius:10px; overflow:hidden; box-shadow:0 6px 14px rgba(0,0,0,0.08);
    }
    thead { background:#A31D1D; color:#FEF9E1; cursor:pointer; }
    th, td { padding:12px 15px; text-align:left; }
    tbody tr { transition: background 0.3s ease; }
    tbody tr:hover { background:#fff4c7; }
    th.sortable:hover { background:#8c1717; }
    @keyframes fadeIn { from{opacity:0; transform:translateY(15px);} to{opacity:1; transform:translateY(0);} }
    @keyframes growLine { from{width:0;} to{width:50%;} }
  </style>
  <div class="container">
    <h3>Patient Management</h3>
    @if(session('success'))
      <div class="alert success">{{ session('success') }}</div>
    @endif

    <div class="search-box">
      <form method="GET" action="{{ route('admin.patients') }}">
        <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="🔍 Cari nama/email/no HP...">
      </form>
    </div>

    <div class="card" style="margin-bottom:16px; padding:12px;">
      <form method="POST" action="{{ route('admin.patients.store') }}" class="form-inline">
        @csrf
        <input type="text" name="nama" placeholder="Nama" required class="input" />
        <input type="date" name="tgl_lahir" class="input" />
        <input type="email" name="email" placeholder="Email" class="input" />
        <input type="text" name="no_hp" placeholder="No HP" class="input" />
        <button class="btn" type="submit">Tambah Pasien</button>
      </form>
    </div>

    <table id="patientTable">
      <thead>
        <tr>
          <th>Nama</th>
          <th>Tgl Lahir</th>
          <th>Email</th>
          <th>No HP</th>
          <th style="width:200px;">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($pasiens as $pasien)
          <tr>
            <td>{{ $pasien->nama }}</td>
            <td>{{ $pasien->tgl_lahir }}</td>
            <td>{{ $pasien->email }}</td>
            <td>{{ $pasien->no_hp }}</td>
            <td>
              <form method="POST" action="{{ route('admin.patients.update', $pasien) }}" style="display:flex; gap:8px; flex-wrap:wrap; align-items:center;">
                @csrf
                @method('PUT')
                <input type="text" name="nama" value="{{ $pasien->nama }}" class="input small" />
                <input type="date" name="tgl_lahir" value="{{ $pasien->tgl_lahir }}" class="input small" />
                <input type="email" name="email" value="{{ $pasien->email }}" class="input small" />
                <input type="text" name="no_hp" value="{{ $pasien->no_hp }}" class="input small" />
                <button class="btn confirm" type="submit">Simpan</button>
              </form>
              <form method="POST" action="{{ route('admin.patients.destroy', $pasien) }}" style="margin-top:6px;">
                @csrf
                @method('DELETE')
                <button class="btn cancel" type="submit" onclick="return confirm('Hapus pasien ini?')">Hapus</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="5">Tidak ada data pasien.</td></tr>
        @endforelse
      </tbody>
    </table>

    <div class="pagination">{{ $pasiens->links() }}</div>
  </div>

  <style>
    .alert.success { background:#e8f7ee; color:#2e7d32; padding:10px 12px; border-radius:8px; margin-bottom:12px; }
    .form-inline .input { padding:8px 10px; border:1px solid #ddd; border-radius:8px; margin-right:8px; }
    .input.small { width:140px; }
    .btn { padding:8px 14px; border:none; border-radius:8px; cursor:pointer; font-weight:bold; }
    .btn.confirm { background:#2ecc71; color:#fff; }
    .btn.cancel { background:#e74c3c; color:#fff; }
  </style>
@endsection
