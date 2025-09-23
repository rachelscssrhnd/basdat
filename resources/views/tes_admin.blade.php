@extends('layouts.admin')

@section('content')
  <div class="card">
    <h3 style="margin-top:0;color:#A31D1D;">Test Management</h3>
    <p>🧪 Kelola data tes pasien di sini.</p>

  <div class="test-grid">
    <div class="test-card">
      <h4>Covid-19</h4>
      <p>Status: Aktif</p>
      <button class="btn">Kelola</button>
    </div>
    <div class="test-card">
      <h4>Diabetes</h4>
      <p>Status: Aktif</p>
      <button class="btn">Kelola</button>
    </div>
    <div class="test-card">
      <h4>Kolesterol</h4>
      <p>Status: Tidak Aktif</p>
      <button class="btn">Kelola</button>
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
  .test-grid {
    display:grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap:20px;
  }
  .test-card {
    background:#fff;
    border-radius:15px;
    padding:20px;
    box-shadow:0 6px 14px rgba(0,0,0,0.08);
    transition:transform 0.3s, box-shadow 0.3s;
  }
  .test-card:hover {
    transform:translateY(-6px);
    box-shadow:0 10px 18px rgba(0,0,0,0.15);
  }
  .test-card h4 {
    margin:0 0 10px;
    color:#A31D1D;
  }
  .btn {
    padding:8px 16px;
    border:none;
    background:#A31D1D;
    color:#FEF9E1;
    font-weight:bold;
    border-radius:8px;
    cursor:pointer;
    transition:background 0.3s, transform 0.2s;
  }
  .btn:hover {
    background:#8c1717;
    transform:scale(1.05);
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
