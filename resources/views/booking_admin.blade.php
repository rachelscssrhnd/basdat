@extends('layouts.admin')

@section('content')
  <div class="card">
    <h3 style="margin-top:0;color:#A31D1D;">Booking Management</h3>
    @if(session('success'))
      <div class="alert success">{{ session('success') }}</div>
    @endif

    <div class="booking-grid">
      @forelse($bookings as $booking)
        <div class="booking-card">
          <h4>{{ $booking->pasien->nama ?? 'Tanpa Nama' }}</h4>
          <p>Tanggal: {{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d M Y') }}</p>
          <p>Cabang: {{ $booking->cabang->nama_cabang ?? '-' }}</p>
          <form method="POST" action="{{ route('admin.booking.update', $booking) }}" class="actions">
            @csrf
            @method('PUT')
            <select name="status_pembayaran" class="select">
              <option value="belum_bayar" {{ $booking->status_pembayaran==='belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
              <option value="menunggu_konfirmasi" {{ $booking->status_pembayaran==='menunggu_konfirmasi' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
              <option value="terbayar" {{ $booking->status_pembayaran==='terbayar' ? 'selected' : '' }}>Terbayar</option>
            </select>
            <select name="status_tes" class="select">
              <option value="menunggu" {{ $booking->status_tes==='menunggu' ? 'selected' : '' }}>Menunggu</option>
              <option value="dijadwalkan" {{ $booking->status_tes==='dijadwalkan' ? 'selected' : '' }}>Dijadwalkan</option>
              <option value="selesai" {{ $booking->status_tes==='selesai' ? 'selected' : '' }}>Selesai</option>
              <option value="dibatalkan" {{ $booking->status_tes==='dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
            <button class="btn confirm" type="submit">Simpan</button>
          </form>
          <form method="POST" action="{{ route('admin.booking.destroy', $booking) }}" class="actions">
            @csrf
            @method('DELETE')
            <button class="btn cancel" type="submit" onclick="return confirm('Hapus booking ini?')">Hapus</button>
          </form>
        </div>
      @empty
        <p>Tidak ada booking.</p>
      @endforelse
    </div>

    <div class="pagination">
      {{ $bookings->links() }}
    </div>
  </div>

<style>
  .container { 
    padding:30px; 
    animation: fadeIn 0.6s ease; 
  }
  h3 {
    font-size: 1.8rem; color: #A31D1D; margin-bottom: 20px;
    position: relative; display:inline-block;
  }
  h3::after {
    content:""; position:absolute; bottom:-6px; left:0;
    width:50%; height:3px; background:#FFD3B6; border-radius:3px;
    animation: growLine 1s ease forwards;
  }
  p { margin-bottom:20px; }

  .booking-grid {
    display:grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap:20px;
  }
  .booking-card {
    background:#fff;
    border-radius:15px;
    padding:20px;
    box-shadow:0 6px 14px rgba(0,0,0,0.08);
    transition:transform 0.3s, box-shadow 0.3s;
  }
  .booking-card:hover {
    transform:translateY(-6px);
    box-shadow:0 10px 18px rgba(0,0,0,0.15);
  }
  .booking-card h4 {
    margin:0 0 10px;
    color:#A31D1D;
  }
  .actions {
    margin-top:15px;
    display:flex;
    gap:10px;
    flex-wrap:wrap;
  }
  .alert.success { background:#e8f7ee; color:#2e7d32; padding:10px 12px; border-radius:8px; margin-bottom:12px; }
  .select { padding:8px 10px; border:1px solid #ddd; border-radius:8px; }
  .pagination { margin-top:16px; }
  .btn {
    padding:8px 14px;
    border:none;
    border-radius:8px;
    cursor:pointer;
    font-weight:bold;
    transition:background 0.3s, transform 0.2s;
  }
  .btn.confirm {
    background:#2ecc71; color:white;
  }
  .btn.confirm:hover {
    background:#27ae60; transform:scale(1.05);
  }
  .btn.cancel {
    background:#e74c3c; color:white;
  }
  .btn.cancel:hover {
    background:#c0392b; transform:scale(1.05);
  }
  .btn.detail {
    background:#A31D1D; color:#FEF9E1;
  }
  .btn.detail:hover {
    background:#8c1717; transform:scale(1.05);
  }

  @keyframes fadeIn { 
    from{opacity:0; transform:translateY(15px);} 
    to{opacity:1; transform:translateY(0);} 
  }
  @keyframes growLine { 
    from{width:0;} to{width:50%;} 
  }
  </style>
@endsection
