<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nama = $_POST["nama_depan"] . " " . $_POST["nama_belakang"];
  $telepon = $_POST["telepon"];
  $email = $_POST["email"];
  
  // tes disimpan sebagai "harga|nama tes"
  $tesData = explode("|", $_POST["tes"]);
  $harga = $tesData[0];
  $tes = $tesData[1];
  
  $cabang = $_POST["cabang"];
  $tanggal = $_POST["tanggal"];
  $sesi = $_POST["sesi"];
} else {
  // fallback kalau akses langsung tanpa booking
  $nama = "John Doe";
  $telepon = "0812-3456-7890";
  $email = "john@example.com";
  $tes = "Tes Darah (Hemoglobin)";
  $harga = 75000;
  $cabang = "Cabang A";
  $tanggal = "2025-09-25";
  $sesi = "2";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Pembayaran Tes</title>
  <style>
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      background: linear-gradient(135deg, #FEF9E1, #FAD4D4);
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }
    .container {
      background: #fff;
      padding: 35px;
      border-radius: 18px;
      max-width: 650px;
      width: 90%;
      box-shadow: 0 10px 28px rgba(0,0,0,0.2);
      animation: fadeInUp 0.9s ease forwards;
    }
    @keyframes fadeInUp {
      from {opacity: 0; transform: translateY(30px);}
      to {opacity: 1; transform: translateY(0);}
    }
    h2 {
      text-align: center;
      color: #6D2323;
      margin-bottom: 25px;
      font-size: 1.9em;
    }
    .detail {
      background: #fdf5f5;
      padding: 18px 20px;
      border-radius: 14px;
      margin-bottom: 25px;
      border-left: 6px solid #A31D1D;
      box-shadow: 0 3px 10px rgba(0,0,0,0.08);
    }
    .detail p { margin: 10px 0; font-size: 0.95em; }
    .total { text-align: right; font-size: 1.2em; font-weight: bold; color: #A31D1D; margin-top: 12px; }
    label { display: block; margin-top: 15px; font-weight: bold; color: #333; }
    select, button {
      width: 100%; padding: 13px; margin-top: 10px;
      border-radius: 10px; border: 1px solid #ccc;
      font-size: 1em; transition: all 0.3s ease;
    }
    button {
      background: #A31D1D; color: #FEF9E1;
      border: none; font-weight: bold; cursor: pointer;
      margin-top: 25px; font-size: 1.05em;
      border-radius: 10px;
    }
    button:hover {
      background: #6D2323;
      box-shadow: 0 6px 16px rgba(0,0,0,0.25);
      transform: translateY(-3px);
    }
    .instruksi {
      margin-top: 15px; padding: 12px;
      border-radius: 10px; background: #f9f9f9;
      border-left: 5px solid #FFD580;
      display: none; font-size: 0.95em; color: #333;
      animation: fadeIn 0.4s ease forwards;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* Modal popup */
    .modal {
      display: none; position: fixed; z-index: 999;
      left: 0; top: 0; width: 100%; height: 100%;
      background: rgba(0,0,0,0.6);
    }
    .modal-content {
      background: #fff; margin: 10% auto; padding: 25px;
      border-radius: 16px; width: 350px; text-align: center;
      animation: slideDown 0.4s ease;
    }
    @keyframes slideDown {
      from {transform: translateY(-40px); opacity: 0;}
      to {transform: translateY(0); opacity: 1;}
    }
    .modal-content img { width: 200px; margin: 20px 0; }
    .close {
      float: right; font-size: 24px; font-weight: bold;
      cursor: pointer; color: #aaa;
    }
    .close:hover { color: black; }
  </style>
</head>
<body>
  <div class="container">
    <h2>Konfirmasi & Pembayaran</h2>
    <div class="detail">
      <p><strong>Nama:</strong> <?= $nama ?></p>
      <p><strong>Telepon:</strong> <?= $telepon ?></p>
      <p><strong>Email:</strong> <?= $email ?></p>
      <p><strong>Jenis Tes:</strong> <?= $tes ?></p>
      <p><strong>Cabang:</strong> <?= $cabang ?></p>
      <p><strong>Tanggal:</strong> <?= $tanggal ?></p>
      <p><strong>Sesi:</strong> Sesi <?= $sesi ?></p>
      <p class="total">Total: Rp <?= number_format($harga, 0, ',', '.') ?></p>
    </div>

    <label for="metode">Pilih Metode Pembayaran</label>
    <select id="metode" name="metode">
      <option value="">-- Pilih Metode --</option>
      <option value="qris">QRIS (All Payment)</option>
      <option value="ewallet">E-Wallet (OVO / GoPay / DANA)</option>
    </select>

    <div class="instruksi" id="instruksi"></div>

    <button onclick="lanjutPembayaran()">Lanjutkan Pembayaran</button>
  </div>

  <!-- Modal -->
  <div id="paymentModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal()">&times;</span>
      <div id="modalBody"></div>
    </div>
  </div>

  <script>
    function showInstruksi() {
      const metode = document.getElementById("metode").value;
      const box = document.getElementById("instruksi");
      if (metode === "qris") {
        box.style.display = "block";
        box.innerText = "Anda akan diarahkan ke pembayaran QRIS.";
      } else if (metode === "ewallet") {
        box.style.display = "block";
        box.innerText = "Anda akan diarahkan ke pembayaran via E-Wallet.";
      } else {
        box.style.display = "none";
      }
    }

    document.getElementById("metode").addEventListener("change", showInstruksi);

    function lanjutPembayaran() {
      const metode = document.getElementById("metode").value;
      const modal = document.getElementById("paymentModal");
      const modalBody = document.getElementById("modalBody");

      if (!metode) {
        alert("Silakan pilih metode pembayaran terlebih dahulu.");
        return;
      }

      if (metode === "qris") {
        modalBody.innerHTML = `
          <h3>Scan QRIS untuk Membayar</h3>
          <img src="qris-sample.png" alt="QRIS Code">
          <p><b>Merchant:</b> HEBAT Health Center</p>
          <p><b>Total:</b> Rp <?= number_format($harga, 0, ',', '.') ?></p>
        `;
      } else if (metode === "ewallet") {
        modalBody.innerHTML = `
          <h3>Pembayaran via E-Wallet</h3>
          <p><b>Merchant:</b> HEBAT Health Center</p>
          <p><b>Total:</b> Rp <?= number_format($harga, 0, ',', '.') ?></p>
          <p><b>No. E-Wallet:</b> 081234567890</p>
          <p>(Bisa transfer via OVO, GoPay, atau DANA)</p>
        `;
      }

      modal.style.display = "block";
    }

    function closeModal() {
      document.getElementById("paymentModal").style.display = "none";
    }

    window.onclick = function(event) {
      let modal = document.getElementById("paymentModal");
      if (event.target === modal) {
        modal.style.display = "none";
      }
    }
  </script>
</body>
</html>
