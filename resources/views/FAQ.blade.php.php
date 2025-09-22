<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>FAQ - Booking Tes</title>
  <style>
    body {
      font-family: "Segoe UI", Arial, sans-serif;
      background: linear-gradient(135deg, #FEF9E1, #fceabb);
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      min-height: 100vh;
      overflow-x: hidden;
      animation: fadeIn 1s ease-in;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .container {
      background: #fff;
      padding: 40px;
      border-radius: 16px;
      max-width: 850px;
      width: 90%;
      margin: 50px auto;
      box-shadow: 0 10px 25px rgba(0,0,0,0.15);
      animation: slideUp 0.8s ease;
    }

    @keyframes slideUp {
      from { transform: translateY(40px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }

    h2 {
      text-align: center;
      color: #6D2323;
      margin-bottom: 30px;
      font-size: 28px;
    }

    .faq-item {
      margin-bottom: 15px;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 3px 8px rgba(0,0,0,0.08);
      transform: translateY(20px);
      opacity: 0;
      animation: fadeItem 0.8s ease forwards;
    }

    .faq-item:nth-child(1) { animation-delay: 0.2s; }
    .faq-item:nth-child(2) { animation-delay: 0.4s; }
    .faq-item:nth-child(3) { animation-delay: 0.6s; }
    .faq-item:nth-child(4) { animation-delay: 0.8s; }
    .faq-item:nth-child(5) { animation-delay: 1s; }

    @keyframes fadeItem {
      to { transform: translateY(0); opacity: 1; }
    }

    .faq-item h3 {
      margin: 0;
      padding: 16px 20px;
      background: linear-gradient(90deg, #6D2323, #912d2d);
      color: #FEF9E1;
      cursor: pointer;
      position: relative;
      transition: all 0.3s ease;
      font-size: 18px;
    }

    .faq-item h3:hover {
      background: linear-gradient(90deg, #912d2d, #b53b3b);
    }

    .faq-item h3::after {
      content: "▶";
      position: absolute;
      right: 20px;
      transition: transform 0.4s ease;
    }

    .faq-item.active h3::after {
      transform: rotate(90deg);
    }

    .faq-item p {
      max-height: 0;
      opacity: 0;
      overflow: hidden;
      padding: 0 20px;
      background: #f9f9f9;
      line-height: 1.6;
      transition: all 0.6s ease;
    }

    .faq-item.active p {
      max-height: 500px;
      opacity: 1;
      padding: 16px 20px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>❓ Frequently Asked Questions</h2>

    <div class="faq-item">
      <h3>Apa itu Formulir Pemesanan Tes?</h3>
      <p>Formulir Pemesanan Tes adalah sistem online untuk memesan jadwal pemeriksaan kesehatan seperti tes darah, tes urine, atau rontgen gigi di cabang laboratorium kami.</p>
    </div>

    <div class="faq-item">
      <h3>Bagaimana cara memesan jadwal tes?</h3>
      <p>Anda hanya perlu mengisi form pemesanan, memilih jenis tes, cabang, tanggal, dan sesi. Setelah itu lanjutkan ke pembayaran.</p>
    </div>

    <div class="faq-item">
      <h3>Apakah setiap sesi bisa dipakai lebih dari 1 pasien?</h3>
      <p>Tidak. Satu sesi hanya berlaku untuk 1 pasien. Jika sudah dipesan, sesi tersebut otomatis tidak bisa dipilih lagi.</p>
    </div>

    <div class="faq-item">
      <h3>Metode pembayaran apa saja yang tersedia?</h3>
      <p>Kami menyediakan pembayaran via QRIS dan e-wallet (OVO, GoPay, DANA).</p>
    </div>

    <div class="faq-item">
      <h3>Apakah bisa membatalkan booking?</h3>
      <p>Tidak, apabila sudah melakukan pemesanan maka tidak dapat dibatalkan.</p>
    </div>
  </div>

  <script>
    // Toggle FAQ
    document.querySelectorAll(".faq-item h3").forEach(item => {
      item.addEventListener("click", () => {
        const parent = item.parentElement;
        parent.classList.toggle("active");

        // Close others
        document.querySelectorAll(".faq-item").forEach(faq => {
          if (faq !== parent) faq.classList.remove("active");
        });
      });
    });
  </script>
</body>
</html>
