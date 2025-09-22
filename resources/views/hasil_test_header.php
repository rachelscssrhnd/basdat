<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Booking</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #FEF9E1;
      margin: 0;
      padding: 20px;
    }

    h1 {
      text-align: center;
      color: #6D2323;
      margin-bottom: 30px;
    }

    .card {
      background: linear-gradient(to right, #EADBC8, #F8ECD1);
      border-radius: 15px;
      padding: 20px;
      margin: 20px auto;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      max-width: 1100px;
      opacity: 0;                /* awalnya pudar */
      transform: translateX(-50px); /* geser ke kiri */
      animation: fadeSlide 1s forwards; /* animasi masuk */
    }

    /* delay tiap card biar muncul berurutan */
    .card:nth-child(2) { animation-delay: 0.3s; }
    .card:nth-child(3) { animation-delay: 0.6s; }

    @keyframes fadeSlide {
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    .left {
      flex: 1;
      font-weight: bold;
      color: #6D2323;
      font-size: 18px;
    }

    .middle {
      flex: 2;
      display: flex;
      justify-content: center;
      text-align: center;
      gap: 40px;
    }

    .field {
      background-color: #6D2323;
      color: #FEF9E1;
      padding: 10px 40px;
      border-radius: 25px;
      font-weight: bold;
      margin-bottom: 8px;
      display: inline-block;
      min-width: 160px;
    }

    .right {
      flex: 1;
      text-align: center;
    }

    .download-btn {
      display: inline-block;
      background-color: #2E7D32; /* hijau */
      color: #fff;
      padding: 10px 25px;
      border-radius: 25px;
      text-decoration: none;
      font-weight: bold;
      transition: background 0.3s;
    }

    .download-btn:hover {
      background-color: #1B5E20;
    }
  </style>
</head>
<body>

  <h1>Silakan Unduh Hasil Tes Laboratorium Kamu</h1>

  <div class="card">
    <div class="left">
      Booking ID <br> BK-001
    </div>
    <div class="middle">
      <div>
        <div class="field">Tanggal Input</div>
        <div>22-09-2025</div>
      </div>
      <div>
        <div class="field">Dibuat Oleh</div>
        <div>Admin Satu</div>
      </div>
    </div>
    <div class="right">
      <a href="#" class="download-btn">Unduh Hasil</a>
    </div>
  </div>

  <div class="card">
    <div class="left">
      Booking ID <br> BK-002
    </div>
    <div class="middle">
      <div>
        <div class="field">Tanggal Input</div>
        <div>23-09-2025</div>
      </div>
      <div>
        <div class="field">Dibuat Oleh</div>
        <div>Admin Dua</div>
      </div>
    </div>
    <div class="right">
      <a href="#" class="download-btn">Unduh Hasil</a>
    </div>
  </div>

</body>
</html>
