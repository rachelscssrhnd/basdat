@extends('layouts.app')

@section('content')
  <style>
    .booking-container {
      display: flex;
      width: 100%;
      max-width: 1200px;
      margin: 0 auto;
      border-radius: 16px;
      box-shadow: 0 12px 28px rgba(0,0,0,0.15);
      overflow: hidden;
      background: #fff;
      animation: slideUp 1s ease forwards;
    }
    @keyframes slideUp {
      from { transform: translateY(40px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }
    .booking-left { 
      flex: 1; 
      background: #fff8d8; 
      display: flex; 
      justify-content: center; 
      align-items: center; 
      padding: 30px; 
      animation: float 3s ease-in-out infinite; 
    }
    .booking-left img { 
      max-width: 85%; 
      max-height: 420px; 
      animation: zoomIn 1.5s ease; 
    }
    @keyframes zoomIn { 
      from { transform: scale(0.9); opacity: 0; } 
      to { transform: scale(1); opacity: 1; } 
    }
    @keyframes float { 
      0%, 100% { transform: translateY(0); } 
      50% { transform: translateY(-12px); } 
    }
    .booking-right { 
      flex: 1; 
      background: #6D2323; 
      color: white; 
      padding: 40px; 
      position: relative; 
    }
    .booking-right h2 { 
      margin-bottom: 20px; 
      text-align: center; 
      color: #FEF9E1; 
      font-size: 26px; 
      letter-spacing: 1px; 
      animation: popIn 0.8s ease; 
    }
    @keyframes popIn { 
      from { transform: scale(0.9); opacity: 0; } 
      to { transform: scale(1); opacity: 1; } 
    }
    .booking-right label { 
      display: block; 
      margin-top: 15px; 
      font-weight: bold; 
    }
    .booking-right input, .booking-right select { 
      width: 100%; 
      padding: 12px; 
      margin-top: 6px; 
      border: none; 
      border-radius: 8px; 
      transition: all 0.3s ease; 
      font-size: 14px; 
    }
    .booking-right input:focus, .booking-right select:focus { 
      outline: none; 
      box-shadow: 0 0 12px #FEF9E1; 
      transform: scale(1.02); 
    }
    .booking-right button { 
      margin-top: 25px; 
      width: 100%; 
      padding: 14px; 
      background: #A31D1D; 
      color: #FEF9E1; 
      border: none; 
      border-radius: 8px; 
      font-weight: bold; 
      font-size: 15px; 
      cursor: pointer; 
      transition: all 0.3s ease; 
    }
    .booking-right button:hover { 
      background: #FEF9E1; 
      color: #6D2323; 
      border: 2px solid #A31D1D; 
      transform: translateY(-3px) scale(1.05); 
      box-shadow: 0 8px 18px rgba(0,0,0,0.2); 
    }
    .booking-right .harga { 
      margin-top: 10px; 
      font-size: 14px; 
      color: #FFD580; 
      font-style: italic; 
    }
    
    /* Responsive */
    @media (max-width: 768px) {
      .booking-container {
        flex-direction: column;
      }
      .booking-left {
        padding: 20px;
      }
      .booking-right {
        padding: 30px;
      }
    }
  </style>
  <div class="booking-container">
    <div class="booking-left">
      <img src="{{ asset('images/lab_project/Gambar 1_Jenis Tes.jpeg') }}" alt="Tes Laboratorium">
    </div>
    <div class="booking-right">
      <h2>Formulir Pemesanan Tes</h2>
      <form action="{{ route('booking.store') }}" method="POST">
        @csrf
        <label for="nama_depan">Nama Depan</label>
        <input type="text" id="nama_depan" name="nama_depan" required>

        <label for="nama_belakang">Nama Belakang</label>
        <input type="text" id="nama_belakang" name="nama_belakang" required>

        <label for="telepon">Nomor Telepon</label>
        <input type="tel" id="telepon" name="telepon" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>

        <label for="tes">Pilih Jenis Tes</label>
        <select id="tes" name="tes" required onchange="updateHarga()">
          <option value="">-- Pilih Jenis Tes --</option>
          @if($selectedTestType === 'darah')
            <option value="75000|Tes Darah (Hemoglobin)" selected>Tes Darah (Hemoglobin)</option>
            <option value="90000|Tes Darah (Golongan Darah)">Tes Darah (Golongan Darah)</option>
            <option value="100000|Tes Darah (Agregasi Trombosit)">Tes Darah (Agregasi Trombosit)</option>
          @elseif($selectedTestType === 'urine')
            <option value="50000|Tes Urine" selected>Tes Urine</option>
          @elseif($selectedTestType === 'kehamilan')
            <option value="120000|Tes Kehamilan (Anti-Rubella lgG)" selected>Tes Kehamilan (Anti-Rubella lgG)</option>
            <option value="120000|Tes Kehamilan (Anti-CMV lgG)">Tes Kehamilan (Anti-CMV lgG)</option>
            <option value="120000|Tes Kehamilan (Anti-HSV1 lgG)">Tes Kehamilan (Anti-HSV1 lgG)</option>
          @elseif($selectedTestType === 'gigi')
            <option value="100000|Tes Rontgen Gigi (Dental I CR)" selected>Tes Rontgen Gigi (Dental I CR)</option>
            <option value="150000|Tes Rontgen Gigi (Panoramic)">Tes Rontgen Gigi (Panoramic)</option>
            <option value="200000|Tes Rontgen Gigi (Water's Foto)">Tes Rontgen Gigi (Water's Foto)</option>
          @else
            <option value="100000|Tes Rontgen Gigi (Dental I CR)">Tes Rontgen Gigi (Dental I CR)</option>
            <option value="150000|Tes Rontgen Gigi (Panoramic)">Tes Rontgen Gigi (Panoramic)</option>
            <option value="200000|Tes Rontgen Gigi (Water's Foto)">Tes Rontgen Gigi (Water's Foto)</option>
            <option value="50000|Tes Urine">Tes Urine</option>
            <option value="120000|Tes Kehamilan (Anti-Rubella lgG)">Tes Kehamilan (Anti-Rubella lgG)</option>
            <option value="120000|Tes Kehamilan (Anti-CMV lgG)">Tes Kehamilan (Anti-CMV lgG)</option>
            <option value="120000|Tes Kehamilan (Anti-HSV1 lgG)">Tes Kehamilan (Anti-HSV1 lgG)</option>
            <option value="75000|Tes Darah (Hemoglobin)">Tes Darah (Hemoglobin)</option>
            <option value="90000|Tes Darah (Golongan Darah)">Tes Darah (Golongan Darah)</option>
            <option value="100000|Tes Darah (Agregasi Trombosit)">Tes Darah (Agregasi Trombosit)</option>
          @endif
        </select>
        <div class="harga" id="hargaTes">Harga: -</div>

        <label for="cabang">Pilih Cabang</label>
        <select id="cabang" name="cabang" required>
          <option value="">-- Pilih Cabang --</option>
          <option value="Cabang A">Cabang A</option>
          <option value="Cabang B">Cabang B</option>
          <option value="Cabang C">Cabang C</option>
        </select>

        <label for="tanggal_booking">Pilih Tanggal Tes</label>
        <input type="date" id="tanggal_booking" name="tanggal_booking" required>

        <label for="sesi">Pilih Sesi Tes</label>
        <select id="sesi" name="sesi" required>
          <option value="">-- Pilih Sesi --</option>
          <option value="1">Sesi 1: 09.00 - 10.00</option>
          <option value="2">Sesi 2: 10.00 - 11.00</option>
          <option value="3">Sesi 3: 11.00 - 12.00</option>
          <option value="4">Sesi 4: 13.00 - 14.00</option>
        </select>

        <button type="submit">Pesan Tes</button>
      </form>
    </div>
  </div>

  <script>
    function updateHarga() {
      let tes = document.getElementById("tes");
      let hargaTes = document.getElementById("hargaTes");
      if (tes.value) {
        let harga = tes.value.split("|")[0];
        hargaTes.innerText = "Harga: Rp " + parseInt(harga).toLocaleString();
      } else {
        hargaTes.innerText = "Harga: -";
      }
    }
  </script>
@endsection
