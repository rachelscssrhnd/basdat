<?php 
include 'data.php'; 

// Ambil parameter dari URL
$main = isset($_GET['main']) ? (int)$_GET['main'] : 0;
$sub  = isset($_GET['sub']) ? (int)$_GET['sub'] : 0;

// Ambil data sesuai parameter
$tesUtama = $tes[$main];
$tesDetail = $tesUtama['sub'][$sub];
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detail Tes</title>
<style>
body {
  font-family: Arial, sans-serif;
  background-color: #FEF9E1;
  margin:0; padding:0;
}
.container {
  max-width: 800px;
  margin: 20px auto;
  background: #fff;
  padding: 30px;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0,0,0,0.1);
}
h1 {
  color: #A31D1D;
  text-align: center;
  margin-top:0;
}
h2 {
  color: #A31D1D;
  margin-top:30px;
}
p, li {
  color:#333;
  line-height:1.6;
}
.desc {
  font-size: 18px; 
  text-align: justify;
  margin-top: 15px;
}
.back {
  display: block;
  margin: 40px auto 0; 
  text-align: center;
  color:#A31D1D;
  text-decoration:none;
  font-weight: bold;
}
.back:hover {text-decoration:underline;}
</style>
</head>
<body>

<div class="container">
  <img src="<?php echo $tesDetail['icon']; ?>" alt="icon" style="display:block;margin:0 auto;width:60px;">
  <h1><?php echo $tesDetail['name']; ?></h1>

  <!-- Deskripsi tanpa tulisan 'Deskripsi:' -->
  <p class="desc"><?php echo $tesDetail['detail']['deskripsi']; ?></p>

  <h2>Manfaat Tes</h2>
  <ul>
    <li><?php echo $tesDetail['detail']['manfaat']; ?></li>
  </ul>

  <h2>Persiapan Tes</h2>
  <ul>
    <li><?php echo $tesDetail['detail']['persiapan']; ?></li>
  </ul>

  <!-- Tombol kembali fix -->
  <a href="subtes.php?jenis=<?php echo $main; ?>" class="back">← Kembali ke Halaman Sebelumnya</a>
</div>

</body>
</html>
