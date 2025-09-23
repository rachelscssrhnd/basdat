@extends('layouts.app')

@section('content')
  <style>
    body { 
      font-family: 'Segoe UI', sans-serif; 
      margin:0; 
      background:#fafafa; 
      color:#333;
    }
    header {
      background: #A31D1D; 
      color:#FEF9E1; 
      padding:15px 40px;
      display:flex; 
      justify-content:space-between; 
      align-items:center;
      box-shadow:0 4px 12px rgba(0,0,0,0.15);
      position: sticky;
      top:0;
      z-index:1000;
      animation: slideDown 0.7s ease;
    }
    @keyframes slideDown {
      from {transform: translateY(-100%); opacity:0;}
      to {transform: translateY(0); opacity:1;}
    }
    nav { display:flex; gap:20px; }
    nav a { 
      color:#FEF9E1; 
      text-decoration:none; 
      font-weight:bold; 
      position:relative;
      transition: all 0.3s ease;
    }
    nav a::after {
      content:"";
      position:absolute;
      bottom:-5px; left:0;
      width:0; height:2px;
      background:#FFD3B6;
      transition: width 0.3s ease;
    }
    nav a:hover { color:#FFD3B6; transform: translateY(-2px); }
    nav a:hover::after { width:100%; }
    
    .hero {
      text-align:center; 
      padding:80px 20px;
      background: linear-gradient(135deg, #FEF9E1, #fff4c7);
      animation: fadeIn 1s ease-in;
      box-shadow: inset 0 -6px 12px rgba(0,0,0,0.05);
    }
    .hero h2 {
      font-size:2.2rem; 
      margin-bottom:10px;
      color:#A31D1D;
      animation: popIn 0.8s ease forwards;
    }
    .hero p {
      font-size:1.1rem; 
      color:#444;
      animation: fadeUp 1.2s ease forwards;
    }

    @keyframes fadeIn {
      from {opacity:0; transform:scale(0.97);}
      to {opacity:1; transform:scale(1);}
    }
    @keyframes popIn {
      from {opacity:0; transform: translateY(-20px) scale(0.95);}
      to {opacity:1; transform: translateY(0) scale(1);}
    }
    @keyframes fadeUp {
      from {opacity:0; transform: translateY(20px);}
      to {opacity:1; transform: translateY(0);}
    }

    .container { 
      padding:20px; 
      animation: fadeIn 0.8s ease-in;
    }
  </style>

  <section class="hero">
    <h2>Selamat Datang, Admin!</h2>
    <p>Kelola pasien, tes, dan booking dengan mudah melalui panel ini.</p>
  </section>
@endsection
