<?php 
include 'data.php';
$main = isset($_GET['jenis']) ? (int)$_GET['jenis'] : 0;
$tesUtama = $tes[$main];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title><?= $tesUtama['name']; ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#FEF9E1] min-h-screen px-6 py-10">
  <h1 class="text-3xl font-bold text-center text-[#6D2323] mb-10">
    <?= $tesUtama['name']; ?>
  </h1>

  <div class="grid grid-cols-2 md:grid-cols-3 gap-6 max-w-4xl mx-auto">
    <?php foreach ($tesUtama['sub'] as $j => $sub): ?>
      <div class="text-center bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
        <a href="detail.php?main=<?= $main ?>&sub=<?= $j ?>">
          <img src="<?= $sub['icon'] ?>" alt="<?= $sub['name'] ?>" class="w-20 h-20 mx-auto">
          <p class="mt-3 font-semibold text-[#A31D1D]"><?= $sub['name'] ?></p>
        </a>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="text-center mt-10">
    <a href="Jenis_Tes.php" class="text-[#A31D1D] font-semibold hover:underline">
      ← Kembali ke Halaman Sebelumnya
    </a>
  </div>
</body>
</html>
