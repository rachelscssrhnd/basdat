@extends('layouts.app')

@section('content')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Open+Sans:wght@300;400;500;600;700&display=swap');
  
  .faq-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 20px;
  }
  
  .faq-title {
    font-family: 'Poppins', sans-serif;
    font-size: 2.8rem;
    font-weight: 700;
    color: #A31D1D;
    text-align: center;
    margin-bottom: 3rem;
    letter-spacing: -0.02em;
  }
  
  .faq-item {
    margin-bottom: 1rem;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(163, 29, 29, 0.08);
    background: white;
    transform: translateY(20px);
    opacity: 0;
    animation: fadeItem 0.6s ease forwards;
  }
  
  .faq-item:nth-child(1) { animation-delay: 0.1s; }
  .faq-item:nth-child(2) { animation-delay: 0.2s; }
  .faq-item:nth-child(3) { animation-delay: 0.3s; }
  .faq-item:nth-child(4) { animation-delay: 0.4s; }
  .faq-item:nth-child(5) { animation-delay: 0.5s; }
  
  @keyframes fadeItem {
    to { transform: translateY(0); opacity: 1; }
  }
  
  .faq-question {
    font-family: 'Open Sans', sans-serif;
    margin: 0;
    padding: 20px 24px;
    background: linear-gradient(135deg, #A31D1D, #8C1717);
    color: white;
    cursor: pointer;
    position: relative;
    transition: all 0.3s ease;
    font-size: 1.1rem;
    font-weight: 600;
    letter-spacing: -0.01em;
  }
  
  .faq-question:hover {
    background: linear-gradient(135deg, #8C1717, #6D2323);
    transform: translateY(-1px);
  }
  
  .faq-question::after {
    content: "▼";
    position: absolute;
    right: 24px;
    top: 50%;
    transform: translateY(-50%);
    transition: transform 0.3s ease;
    font-size: 0.9rem;
  }
  
  .faq-item.active .faq-question::after {
    transform: translateY(-50%) rotate(180deg);
  }
  
  .faq-answer {
    font-family: 'Open Sans', sans-serif;
    max-height: 0;
    opacity: 0;
    overflow: hidden;
    padding: 0 24px;
    background: #FEF9E1;
    line-height: 1.7;
    transition: all 0.4s ease;
    font-size: 1rem;
    color: #374151;
  }
  
  .faq-item.active .faq-answer {
    max-height: 200px;
    opacity: 1;
    padding: 20px 24px;
  }
</style>

<div class="faq-container">
  <h1 class="faq-title">Frequently Asked Questions</h1>

  <div class="faq-item">
    <h3 class="faq-question">Apa itu Formulir Pemesanan Tes?</h3>
    <p class="faq-answer">Formulir Pemesanan Tes adalah sistem online untuk memesan jadwal pemeriksaan kesehatan seperti tes darah, tes urine, atau rontgen gigi di cabang laboratorium kami.</p>
  </div>

  <div class="faq-item">
    <h3 class="faq-question">Bagaimana cara memesan jadwal tes?</h3>
    <p class="faq-answer">Anda hanya perlu mengisi form pemesanan, memilih jenis tes, cabang, tanggal, dan sesi. Setelah itu lanjutkan ke pembayaran.</p>
  </div>

  <div class="faq-item">
    <h3 class="faq-question">Apakah setiap sesi bisa dipakai lebih dari 1 pasien?</h3>
    <p class="faq-answer">Tidak. Satu sesi hanya berlaku untuk 1 pasien. Jika sudah dipesan, sesi tersebut otomatis tidak bisa dipilih lagi.</p>
  </div>

  <div class="faq-item">
    <h3 class="faq-question">Metode pembayaran apa saja yang tersedia?</h3>
    <p class="faq-answer">Kami menyediakan pembayaran via QRIS dan e-wallet (OVO, GoPay, DANA).</p>
  </div>

  <div class="faq-item">
    <h3 class="faq-question">Apakah bisa membatalkan booking?</h3>
    <p class="faq-answer">Tidak, apabila sudah melakukan pemesanan maka tidak dapat dibatalkan.</p>
  </div>
</div>

<script>
  // Toggle FAQ
  document.querySelectorAll(".faq-question").forEach(item => {
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
@endsection
