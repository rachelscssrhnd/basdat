@extends('layouts.admin')

@section('content')
  <div class="card">
    <h3 style="margin-top:0;color:#A31D1D;">Booking Management</h3>
    <p>📅 Fitur booking masih dalam pengembangan.</p>

  <div class="booking-grid">
    <div class="booking-card">
      <h4>Budi Setiawan</h4>
      <p>Tanggal: 25 Sept 2025</p>
      <p>Status: Menunggu Konfirmasi</p>
      <div class="actions">
        <button class="btn confirm">Konfirmasi</button>
        <button class="btn cancel">Batalkan</button>
      </div>
    </div>

    <div class="booking-card">
      <h4>Siti Aisyah</h4>
      <p>Tanggal: 27 Sept 2025</p>
      <p>Status: Dikonfirmasi</p>
      <div class="actions">
        <button class="btn detail">Detail</button>
      </div>
    </div>
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
